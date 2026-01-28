<?php

namespace App\Jobs;

use App\Models\PayZingHistory;
use App\Services\CardGateway;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class RedownloadCardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $pzId;
    public $tries = 6;
    public $backoff = [60, 180, 300, 600, 900, 1200]; // 1m,3m,5m,10m,15m,20m

    public function __construct(int $pzId) { $this->pzId = $pzId; }

    public function handle(): void
    {
        $pz = PayZingHistory::find($this->pzId);
        if (!$pz) return;

        // Nếu đã có thẻ hoặc đã kết thúc -> bỏ
        if (in_array($pz->status, ['success','error']) || ($pz->card_serial && $pz->card_pin_enc)) {
            return;
        }

        // Cần có request_id (và tốt nhất có cả order_code)
        if (!$pz->request_id) {
            $pz->update(['status' => 'error', 'description' => 'Thiếu request_id để redownload']);
            return;
        }

        $gw   = app(CardGateway::class);
        $resp = $gw->redownload($pz->request_id, $pz->order_code);

        // Lưu meta NCC
        $pz->update([
            'provider_status'  => $resp['status'] ?? null,
            'provider_message' => $resp['message'] ?? null,
            'order_code'       => $resp['provider']['order_code'] ?? $pz->order_code,
            'meta'             => array_merge((array)$pz->meta, ['redownload' => $resp]),
        ]);

        $cards = $resp['cards'] ?? [];
        if (empty($cards)) {
            // chưa có thẻ → throw để retry theo backoff
            throw new \RuntimeException('Redownload chưa có thẻ, sẽ thử lại.');
        }

        // Lấy 1 thẻ (nếu NCC trả nhiều)
        $c = $cards[0];
        if (empty($c['serial']) || empty($c['pin'])) {
            throw new \RuntimeException('Dữ liệu thẻ không hợp lệ.');
        }

        // lưu thẻ và xếp hàng nạp
        $pz->update([
            'status'       => 'pending',
            'description'  => 'Đã nhận thẻ từ redownload, chờ nạp.',
            'card_serial'  => $c['serial'],
            'card_pin_enc' => Crypt::encryptString($c['pin']),
        ]);

        TopupCardJob::dispatch($pz->id);
    }

    public function failed(\Throwable $e): void
    {
        // Sau khi cố hết $tries mà vẫn lỗi
        $pz = PayZingHistory::find($this->pzId);
        if ($pz && $pz->status === 'pending') {
            $pz->update([
                'status'      => 'error',
                'description' => 'Hết lượt redownload: '.$e->getMessage(),
            ]);
        }
        $meta = $pz->meta;
        if (is_string($meta)) $meta = json_decode($meta, true) ?: [];
        $shId = data_get($meta, 'service_history_id');
        if ($shId && $pz->status === 'pending') {
            \App\Models\ServiceHistory::whereKey($shId)->update(['status' => 'cancelled']);
        }
        Log::error('RedownloadCardJob failed', ['pzId' => $this->pzId, 'err' => $e->getMessage()]);
    }
}
