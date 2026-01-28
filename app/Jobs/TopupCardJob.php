<?php

namespace App\Jobs;

use App\Models\PayZingHistory;
use App\Models\ServiceHistory;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class TopupCardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** ID PayZingHistory */
    public int $payzingId;

    /**
     * Số lần thử tối đa. Để lớn (20) vì retryUntil sẽ khóa tổng thời gian 5 phút.
     */
    public $tries = 20;

    /**
     * Timeout mỗi attempt (giây)
     */
    public $timeout = 20;

    /**
     * Deadline tuyệt đối dạng ISO8601 — lưu dạng string để serialize an toàn.
     * Khi chạy, ta parse về Carbon trong retryUntil().
     */
    protected string $deadlineIso;

    /**
     * Delay giữa các attempt. Ở đây cố định 30 giây để đủ “nhịp” trong 5 phút.
     */
    public function backoff(): int|array
    {
        return 30; // hoặc: return array_fill(0, $this->tries, 30);
    }

    /**
     * Hạn cuối tuyệt đối của job — sau mốc này, Laravel sẽ fail job tự động.
     */
    public function retryUntil(): \DateTimeInterface
    {
        return Carbon::parse($this->deadlineIso);
    }

    public function __construct(int $payzingId)
    {
        $this->payzingId = $payzingId;
        // Ấn định “đến hạn” ngay khi tạo job: 5 phút kể từ lúc dispatch
        $this->deadlineIso = now()->addMinutes(5)->toIso8601String();
    }

    public function handle(): void
    {
        $pz = PayZingHistory::find($this->payzingId);
        if (!$pz) return;

        // Nếu đã chốt trạng thái cuối cùng => thoát & vẫn notify để chắc chắn.
        if (in_array($pz->status, ['success', 'error'], true)) {
            \App\Jobs\NotifyTelegramTopupJob::dispatch($pz->id);
            return;
        }

        // Chuẩn hoá meta (array)
        $meta = $this->toArrayMeta($pz->meta);

        // 0) Liên kết về ServiceHistory nếu có
        $serviceHistoryId = Arr::get($meta, 'service_history_id');
        if (!$serviceHistoryId && method_exists($pz, 'serviceHistory') && $pz->serviceHistory) {
            $serviceHistoryId = $pz->serviceHistory->id ?? null;
            if ($serviceHistoryId) {
                $meta['service_history_id'] = $serviceHistoryId;
                $pz->update(['meta' => $meta]);
            }
        }

        // 1) Nếu chưa "mua thẻ" lần nào thì MUA THẺ (chỉ 1 lần)
        if (!Arr::get($meta, 'buy.attempted')) {
            $this->attemptBuyOnce($pz, $meta);
            // Sau attempt đầu, nếu NCC báo thành công có thẻ thì tiếp tục; nếu chưa có thẻ/đang xử lý thì release cho lần sau.
            $pz->refresh();
        }

        // 2) Đang chờ NCC cấp thẻ?
        if ($this->isWaitingForCard($pz)) {
            // (Tùy chọn) Ping giữa chừng sau 3 phút nếu vẫn pending
            $this->maybeNotifyLongWait($pz);

            // Release theo backoff 30s (nhịp nhanh trong 5')
            $this->release($this->currentBackoffSeconds());
            return;
        }

        // 3) Nếu mua thẻ THẤT BẠI, lúc này mới redownload đúng theo yêu cầu
        if ($this->shouldRedownload($pz)) {
            $this->attemptRedownload($pz);
            $pz->refresh();

            if ($this->isWaitingForCard($pz) || $this->shouldRedownload($pz)) {
                // (Tùy chọn) Ping giữa chừng sau 3 phút nếu vẫn pending
                $this->maybeNotifyLongWait($pz);

                $this->release($this->currentBackoffSeconds());
                return;
            }
        }

        // 4) Nếu đã có thẻ -> NẠP
        if (!$pz->card_serial || !$pz->card_pin_enc) {
            // Không có thẻ và cũng không chờ/không redownload được nữa => xem như lỗi kết thúc.
            $this->finalize($pz, false, 'Không có thẻ để nạp (missing card data).');
            return;
        }

        $serial = $pz->card_serial;
        try {
            $pin = Crypt::decryptString($pz->card_pin_enc);
        } catch (\Throwable $e) {
            $this->finalize($pz, false, 'Giải mã PIN lỗi: '.$e->getMessage(), ['decrypt_error' => $e->getMessage()]);
            return;
        }

        // map type (ví dụ 22/30)
        $type = (int) $pz->package_id;

        // 5) Gọi API nạp
        try {
            $resp = Http::asForm()
                ->timeout(20)
                ->retry(2, 300)
                ->post('https://accone.vn/api/nap-zing', [
                    'role_id'      => $pz->role_id,
                    'cardSerial'   => $serial,
                    'cardPassword' => $pin,
                    'type'         => $type,
                ])->json();
        } catch (\Throwable $e) {
            $this->finalize($pz, false, 'Lỗi HTTP khi nạp: '.$e->getMessage(), ['topup_exception' => $e->getMessage()]);
            return;
        }

        $ok  = false;
        $msg = 'Nạp thất bại';
        if (is_array($resp)) {
            $rc  = data_get($resp, 'data.returnCode');
            $ok  = (bool) (data_get($resp, 'success') === true && (string)$rc === '1');
            $msg = $ok ? 'Nạp Thành Công' : (data_get($resp, 'data.message') ?? data_get($resp, 'message') ?? $msg);
        }

        $this->finalize($pz, $ok, $msg, ['topup_result' => $resp ?? null]);
    }

    /* ======================= Helpers ======================= */

    private function attemptBuyOnce(PayZingHistory $pz, array $meta): void
    {
        // Đánh dấu đã thử mua (idempotent)
        $meta['buy'] = array_merge(Arr::get($meta, 'buy', []), [
            'attempted'   => true,
            'request_id'  => $pz->request_id,
            'attempt_at'  => now()->toIso8601String(),
        ]);
        $pz->update([
            'status'      => 'pending',
            'description' => 'Đang mua thẻ…',
            'meta'        => $meta,
        ]);

        try {
            $gw   = app(\App\Services\CardGateway::class);
            $resp = $gw->buyCard($pz->service_code ?: config('card.service_codes.ZING', 'ZING'),
                                 (int)($pz->value ?: 20000),
                                 (int)($pz->qty ?: 1),
                                 $pz->request_id);

            // Lưu phản hồi NCC
            $newMeta = $this->toArrayMeta($pz->meta);
            $newMeta['buy']['result'] = $resp;
            $pz->update([
                'provider_status'  => $resp['status'] ?? null,
                'provider_message' => $resp['message'] ?? null,
                'order_code'       => $resp['provider']['order_code'] ?? $pz->order_code,
                'meta'             => $newMeta,
            ]);

            $statusCode = (int)($resp['status'] ?? -1);

            if ($statusCode === 1) {
                // Mua thành công
                [$serial, $pin] = $this->extractCardFromResp($resp);

                if ($serial && $pin) {
                    $pz->update([
                        'status'       => 'pending',
                        'description'  => 'Đã mua thẻ, chờ nạp.',
                        'card_serial'  => $serial,
                        'card_pin_enc' => Crypt::encryptString($pin),
                    ]);
                } else {
                    // Thành công nhưng chưa cấp thẻ -> chờ
                    $pz->update([
                        'status'      => 'pending',
                        'description' => 'Mua thành công, NCC chưa cấp thẻ. Đang chờ.',
                    ]);
                }
            } elseif ($statusCode === 2) {
                // Đang xử lý -> chỉ chờ
                $pz->update([
                    'status'      => 'pending',
                    'description' => 'Thanh toán thành công, NCC đang xử lý. Đang chờ.',
                ]);
            } else {
                // Mua thất bại -> đánh dấu cho luồng redownload ở lần chạy sau
                $newMeta = $this->toArrayMeta($pz->meta);
                $newMeta['redownload'] = [
                    'needed'    => true,
                    'attempts'  => 0,
                    'last_try'  => null,
                ];
                $pz->update([
                    'status'      => 'pending',
                    'description' => 'Mua thẻ thất bại, sẽ thử redownload.',
                    'meta'        => $newMeta,
                ]);
            }
        } catch (\Throwable $e) {
            // Xem như mua thất bại và kích hoạt redownload
            $newMeta = $this->toArrayMeta($pz->meta);
            $newMeta['buy']['exception'] = $e->getMessage();
            $newMeta['redownload'] = [
                'needed'    => true,
                'attempts'  => 0,
                'last_try'  => null,
            ];
            $pz->update([
                'status'      => 'pending',
                'description' => 'Lỗi khi mua thẻ, sẽ thử redownload.',
                'meta'        => $newMeta,
            ]);
        }
    }

    private function extractCardFromResp(array $resp): array
    {
        $serial = null; $pin = null;

        if (!empty($resp['cards'][0])) {
            $c = $resp['cards'][0];
            $serial = $c['serial'] ?? null;
            $pin    = $c['pin'] ?? ($c['code'] ?? null);
        }
        if ((!$serial || !$pin) && !empty($resp['provider']['raw']['data']['cards'][0])) {
            $it     = $resp['provider']['raw']['data']['cards'][0];
            $serial = $serial ?: ($it['serial'] ?? null);
            $pin    = $pin ?: ($it['pin'] ?? ($it['code'] ?? null));
        }

        return [$serial, $pin];
    }

    private function isWaitingForCard(PayZingHistory $pz): bool
    {
        // Đang chờ thẻ: đã mua/đang xử lý nhưng chưa có serial/pin
        $providerStatus = (int)($pz->provider_status ?? -1);
        $hasCard        = !empty($pz->card_serial) && !empty($pz->card_pin_enc);

        // status==1 (success) hoặc ==2 (processing) nhưng chưa có thẻ => chờ
        return !$hasCard && in_array($providerStatus, [1,2], true);
    }

    private function shouldRedownload(PayZingHistory $pz): bool
    {
        $meta = $this->toArrayMeta($pz->meta);
        $need = (bool) Arr::get($meta, 'redownload.needed', false);

        if (!$need) return false;

        // Nếu NCC đã báo success/processing thì KHÔNG redownload (theo yêu cầu)
        $providerStatus = (int)($pz->provider_status ?? -1);
        if (in_array($providerStatus, [1,2], true)) {
            return false;
        }
        return true;
    }

    private function attemptRedownload(PayZingHistory $pz): void
    {
        $meta = $this->toArrayMeta($pz->meta);
        $rd   = Arr::get($meta, 'redownload', ['needed' => true, 'attempts' => 0]);

        $rd['attempts'] = (int)($rd['attempts'] ?? 0) + 1;
        $rd['last_try'] = now()->toIso8601String();
        $meta['redownload'] = $rd;
        $pz->update(['meta' => $meta]);

        try {
            $gw = app(\App\Services\CardGateway::class);

            // Tuỳ NCC: nếu có phương thức redownload/queryOrder thì ưu tiên:
            $resp = null;
            if (method_exists($gw, 'redownload')) {
                $resp = $gw->redownload($pz->request_id);
            } elseif (method_exists($gw, 'queryOrder')) {
                $resp = $gw->queryOrder($pz->request_id);
            } else {
                // fallback: thử buyCard lại với cùng request_id (nếu NCC idempotent)
                $resp = $gw->buyCard($pz->service_code ?: config('card.service_codes.ZING', 'ZING'),
                                     (int)($pz->value ?: 20000),
                                     (int)($pz->qty ?: 1),
                                     $pz->request_id);
            }

            $newMeta = $this->toArrayMeta($pz->meta);
            $newMeta['redownload']['last_result'] = $resp;
            $pz->update([
                'provider_status'  => $resp['status'] ?? $pz->provider_status,
                'provider_message' => $resp['message'] ?? $pz->provider_message,
                'order_code'       => $resp['provider']['order_code'] ?? $pz->order_code,
                'meta'             => $newMeta,
            ]);

            $statusCode = (int)($resp['status'] ?? -1);

            if ($statusCode === 1) {
                // Thành công (lần này)
                [$serial, $pin] = $this->extractCardFromResp($resp);

                if ($serial && $pin) {
                    $pz->update([
                        'status'       => 'pending',
                        'description'  => 'Đã nhận thẻ sau redownload, chờ nạp.',
                        'card_serial'  => $serial,
                        'card_pin_enc' => Crypt::encryptString($pin),
                    ]);
                    // Tắt cờ redownload
                    $newMeta = $this->toArrayMeta($pz->meta);
                    Arr::forget($newMeta, 'redownload.needed');
                    $newMeta['redownload']['needed'] = false;
                    $pz->update(['meta' => $newMeta]);
                } else {
                    // NCC báo success nhưng chưa trả thẻ => chờ
                    $pz->update([
                        'status'      => 'pending',
                        'description' => 'NCC xác nhận thành công (redownload) nhưng chưa cấp thẻ. Đang chờ.',
                    ]);
                }
            } elseif ($statusCode === 2) {
                // Đang xử lý => chờ
                $pz->update([
                    'status'      => 'pending',
                    'description' => 'NCC đang xử lý (redownload). Đang chờ.',
                ]);
            } else {
                // Tiếp tục giữ cờ redownload để retry theo backoff
                $pz->update([
                    'status'      => 'pending',
                    'description' => 'Redownload chưa thành công, sẽ thử lại.',
                ]);
            }
        } catch (\Throwable $e) {
            $newMeta = $this->toArrayMeta($pz->meta);
            $newMeta['redownload']['exception'] = $e->getMessage();
            $pz->update([
                'status'      => 'pending',
                'description' => 'Lỗi khi redownload, sẽ thử lại.',
                'meta'        => $newMeta,
            ]);
        }
    }

    private function finalize(PayZingHistory $pz, bool $ok, string $msg, array $extraMeta = []): void
    {
        $pz->update([
            'status'      => $ok ? 'success' : 'error',
            'description' => $msg,
            'meta'        => array_merge($this->toArrayMeta($pz->meta), ['final' => $extraMeta]),
        ]);

        // Cập nhật ServiceHistory nếu có
        $meta = $this->toArrayMeta($pz->meta);
        $shId = Arr::get($meta, 'service_history_id');

        if ($shId) {
            $updated = ServiceHistory::whereKey($shId)
                ->update(['status' => $ok ? 'completed' : 'cancelled']);

            \Log::info('TopupCardJob.updateServiceHistory', [
                'pzId'   => $pz->id,
                'shId'   => $shId,
                'updated'=> $updated,
                'final'  => $ok ? 'completed' : 'cancelled',
            ]);
        } else {
            \Log::warning('TopupCardJob.noServiceHistoryId', ['pzId' => $pz->id, 'meta' => $pz->meta]);
        }

        // Luôn notify kết quả cuối cùng
        \App\Jobs\NotifyTelegramTopupJob::dispatch($pz->id);
    }

    /**
     * Ping giữa chừng nếu đã chờ >= 3 phút trong trạng thái pending.
     * Đảm bảo chỉ ping một lần.
     */
    private function maybeNotifyLongWait(PayZingHistory $pz): void
    {
        $meta = $this->toArrayMeta($pz->meta);
        $firstWaitAtIso = Arr::get($meta, 'wait.first_at');
        $notified = (bool) Arr::get($meta, 'wait.notified3m', false);

        if (!$firstWaitAtIso) {
            Arr::set($meta, 'wait.first_at', now()->toIso8601String());
            $pz->update(['meta' => $meta]);
            return;
        }

        if (!$notified) {
            $first = Carbon::parse($firstWaitAtIso);
            if (now()->diffInMinutes($first) >= 3) {
                Arr::set($meta, 'wait.notified3m', true);
                $pz->update(['meta' => $meta]);
                \App\Jobs\NotifyTelegramTopupJob::dispatch($pz->id); // Nội dung job notify nên ghi rõ: "Đang xử lý, đã chờ >=3'"
            }
        }
    }

    private function toArrayMeta($meta): array
    {
        if (is_array($meta)) return $meta;
        if (is_string($meta)) {
            $d = json_decode($meta, true);
            return is_array($d) ? $d : [];
        }
        return [];
    }

    private function currentBackoffSeconds(): int
    {
        $b = $this->backoff();
        if (is_array($b)) {
            // chọn phần tử theo số lần đã thử hiện tại (0-based)
            $attempt = max(1, (int) $this->attempts());
            return $b[min($attempt - 1, count($b) - 1)];
        }
        return (int) $b;
    }

    /**
     * Khi job bị fail (hết hạn 5', exception không bắt, v.v.)
     * Đảm bảo vẫn chốt trạng thái & bắn Telegram.
     */
    public function failed(\Throwable $e): void
    {
        try {
            $pz = \App\Models\PayZingHistory::find($this->payzingId);

            if ($pz && !in_array($pz->status, ['success','error'], true)) {
                $pz->update([
                    'status'      => 'error',
                    'description' => 'Hết hạn 5 phút: NCC chưa cấp thẻ / nạp không thành công.',
                    'meta'        => array_merge($this->toArrayMeta($pz->meta), [
                        'failed_exception' => $e->getMessage(),
                        'deadline_at'      => $this->deadlineIso ?? null,
                    ]),
                ]);
            }

            \App\Jobs\NotifyTelegramTopupJob::dispatch($this->payzingId);
        } catch (\Throwable $ignored) {
            // tránh throw trong failed()
        }
    }
}
