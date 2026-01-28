<?php

namespace App\Jobs;

use App\Models\PayZingHistory;
use App\Models\GameService;
use App\Models\ServicePackage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;

class NotifyTelegramTopupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $payzingId;

    public function __construct(int $payzingId)
    {
        $this->payzingId = $payzingId;
    }

    public function handle(): void
    {
        if (env('TELEGRAM_NOTIFICATION', 'OFF') !== 'ON') return;

        $row = PayZingHistory::find($this->payzingId);
        if (!$row) return;

        $service = GameService::find($row->service_id);
        $package = ServicePackage::find($row->package_id);

        // --- Lấy thẻ thật (không mã hóa / không mask) ---
        $serial = $row->card_serial ?: '';
        $pin    = '';
        if (!empty($row->card_pin_enc)) {
            try {
                $pin = Crypt::decryptString($row->card_pin_enc);
            } catch (\Throwable $e) {
                $pin = '(Giải mã lỗi)';
            }
        }

        // --- Lấy số dư ví từ đối tác thẻ ---
        [$ok, $balance, $err] = $this->fetchWalletBalance();
        $balanceStr = $ok && $balance !== null
            ? number_format((int)$balance, 0, ',', '.') . ' VND'
            : ($err ? "N/A ($err)" : 'N/A');

        $telegramToken  = env('TELEGRAM_TOKEN');
        $telegramChatId = env('TELEGRAM_ID');
        if (!$telegramToken || !$telegramChatId) {
            \Log::warning('NotifyTelegramTopupJob: missing TELEGRAM_TOKEN/TELEGRAM_ID');
            return;
        }

        // --- Tạo message chi tiết ---
        $message = "ĐƠN NẠP DỊCH VỤ TỰ ĐỘNG\n\n"
            . "USER: {$row->user_id}\n"
            . "DỊCH VỤ: " . ($service->name ?? $row->service_id) . "\n"
            . "GÓI: " . ($package->name ?? $row->package_id) . "\n"
            . "ACCOUNT: {$row->role_id} (" . ($row->server ?: '-') . ")\n"
            . "SERIAL: {$serial}\n"
            . "MÃ THẺ: {$pin}\n"
            . "STATUS: {$row->status}\n"
            . "MÔ TẢ: " . ($row->description ?: '-') . "\n"
            . "BALANCE: {$balanceStr}\n"
            . "THỜI GIAN: " . now()->format('H:i d/m/Y');

        if (strlen($message) > 4000) {
            $message = substr($message, 0, 3990) . '…';
        }

        try {
            Http::timeout(12)
                ->retry(2, 300)
                ->post("https://api.telegram.org/bot{$telegramToken}/sendMessage", [
                    'chat_id' => $telegramChatId,
                    'text'    => $message,
                    'disable_web_page_preview' => true,
                ]);
        } catch (\Throwable $e) {
            \Log::error('NotifyTelegramTopupJob error: ' . $e->getMessage());
        }
    }

    private function fetchWalletBalance(): array
    {
        $base   = rtrim(env('CARD_BASE_URL', ''), '/');
        $pid    = env('CARD_PARTNER_ID');
        $pkey   = env('CARD_PARTNER_KEY');
        $wallet = env('CARD_WALLET_NUMBER');
        $cmd    = 'getbalance';

        if (!$base || !$pid || !$pkey || !$wallet) {
            return [false, null, 'missing-env'];
        }

        $requestId = uniqid('bal-', true);
        $sign = md5($pkey . $pid . $cmd . $requestId);

        try {
            $resp = Http::asForm()->timeout(12)->post($base.'/api/cardws', [
                'partner_id'    => $pid,
                'wallet_number' => $wallet,
                'command'       => $cmd,
                'request_id'    => $requestId,
                'sign'          => $sign,
            ]);

            if (!$resp->successful()) {
                return [false, null, 'http-'.$resp->status()];
            }

            $data = $resp->json();
            if (!isset($data['balance'])) {
                return [false, null, 'no-balance-field'];
            }

            return [true, (int)$data['balance'], null];

        } catch (\Throwable $e) {
            \Log::warning('fetchWalletBalance error: '.$e->getMessage());
            return [false, null, 'exception'];
        }
    }
}