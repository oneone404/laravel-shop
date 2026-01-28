<?php

namespace App\Console\Commands;

use App\Models\DirectOrder;
use App\Models\BankAccount;
use App\Http\Controllers\DirectPaymentController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProcessDirectPayments extends Command
{
    protected $signature = 'process:direct-payments';
    protected $description = 'Process pending direct payment orders by checking bank transactions';

    public function handle()
    {
        // Kiểm tra có đơn hàng pending không
        $pendingCount = DirectOrder::pendingAndNotExpired()->count();

        if ($pendingCount === 0) {
            $this->info('Không có đơn hàng pending.');
            return;
        }

        $this->info("===== QUÉT THANH TOÁN TRỰC TIẾP ({$pendingCount} đơn pending) =====");

        // Lấy các tài khoản ACB đang active
        $bankAccounts = BankAccount::where('auto_confirm', true)
            ->where('is_active', true)
            ->where('bank_name', 'ACB')
            ->whereNotNull('access_token')
            ->get();

        if ($bankAccounts->isEmpty()) {
            $this->warn('Không có tài khoản ngân hàng nào được cấu hình.');
            return;
        }

        // Lấy danh sách đơn hàng pending
        $pendingOrders = DirectOrder::pendingAndNotExpired()
            ->orderBy('created_at', 'asc')
            ->get();

        // Tạo map nội dung -> order để tìm nhanh & kiểm tra tính khả dụng
        $paymentContentMap = [];
        $cancelledCount = 0;
        foreach ($pendingOrders as $order) {
            // Kiểm tra xem acc còn không, nếu không thì hủy luôn
            if (!$order->checkAvailability()) {
                $cancelledCount++;
                continue;
            }

            $key = strtoupper(trim($order->payment_content));
            $paymentContentMap[$key] = $order;
        }

        if ($cancelledCount > 0) {
            $this->info("Đã hủy {$cancelledCount} đơn hàng do tài khoản không còn khả dụng.");
        }

        $this->info('Nội dung thanh toán cần tìm: ' . implode(', ', array_keys($paymentContentMap)));

        $processedCount = 0;

        // Config Pay2S
        $baseUrl = rtrim(config('services.pay2s.api_base', ''), '/');
        $apiPath = config('services.pay2s.transactions_path', '/transactions');
        $tzApi = config('services.pay2s.timezone', 'Asia/Ho_Chi_Minh');

        foreach ($bankAccounts as $bankAccount) {
            $this->info("Quét ngân hàng: {$bankAccount->bank_name} - {$bankAccount->account_number}");

            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'pay2s-token' => $bankAccount->access_token,
                ])->post(
                    $baseUrl . $apiPath,
                    [
                        'bankAccounts' => $bankAccount->account_number,
                        'begin' => now()->format('d/m/Y'),
                        'end' => now()->format('d/m/Y'),
                    ]
                );

                if (!$response->successful()) {
                    $this->error('Pay2S Error: ' . $response->status());
                    continue;
                }

                $payload = $response->json();
                $transactions = $this->extractTransactions($payload);

                $this->info('Tìm thấy ' . count($transactions) . ' giao dịch');

                foreach ($transactions as $tx) {
                    // Chỉ xét tiền vào
                    if (($tx['type'] ?? '') !== 'IN') {
                        continue;
                    }

                    $content = strtoupper(trim($tx['description'] ?? ''));
                    $amount = (int)($tx['amount'] ?? 0);

                    // Tìm trong map các đơn hàng pending
                    foreach ($paymentContentMap as $paymentContent => $order) {
                        // Kiểm tra nội dung có chứa payment_content không
                        if (strpos($content, $paymentContent) !== false) {
                            // Kiểm tra số tiền khớp
                            if ($amount >= $order->amount) {
                                $this->info("✓ Tìm thấy giao dịch khớp cho đơn {$order->order_code}");
                                $this->info("  Nội dung: {$content}");
                                $this->info("  Số tiền: " . number_format($amount));

                                $transactionId = $tx['transaction_id'] ?? $tx['reference'] ?? null;

                                // Mark as paid
                                $order->markAsPaid($transactionId);

                                // Process order (giao account)
                                $success = DirectPaymentController::processOrder($order);

                                if ($success) {
                                    $this->info("✓ Đã xử lý thành công đơn hàng {$order->order_code}");
                                    $processedCount++;
                                } else {
                                    $this->error("✗ Lỗi khi xử lý đơn hàng {$order->order_code}");
                                }

                                // Xóa khỏi map để không xử lý lại
                                unset($paymentContentMap[$paymentContent]);
                                break;
                            }
                        }
                    }
                }

            } catch (\Exception $e) {
                $this->error('Lỗi: ' . $e->getMessage());
                Log::error('[DirectPayment] Process error: ' . $e->getMessage());
            }
        }

        // Đánh dấu các đơn hết hạn
        $expiredCount = DirectOrder::where('status', DirectOrder::STATUS_PENDING)
            ->where('expires_at', '<', now())
            ->update(['status' => DirectOrder::STATUS_EXPIRED]);

        if ($expiredCount > 0) {
            $this->info("Đã đánh dấu {$expiredCount} đơn hàng hết hạn.");
        }

        // Xử lý các đơn hàng đã paid nhưng chưa completed (ví dụ khi test thủ công)
        $paidOrders = DirectOrder::where('status', DirectOrder::STATUS_PAID)->get();
        
        foreach ($paidOrders as $paidOrder) {
            $this->info("Xử lý đơn hàng paid: {$paidOrder->order_code}");
            
            $success = DirectPaymentController::processOrder($paidOrder);
            
            if ($success) {
                $this->info("✓ Đã xử lý thành công đơn hàng {$paidOrder->order_code}");
                $processedCount++;
            } else {
                $this->error("✗ Lỗi khi xử lý đơn hàng {$paidOrder->order_code}");
            }
        }

        $this->info("===== KẾT THÚC - Đã xử lý {$processedCount} đơn hàng =====");
    }

    /**
     * Extract transactions array from Pay2S response
     */
    private function extractTransactions($payload): array
    {
        if (isset($payload['transactions']) && is_array($payload['transactions'])) {
            return $payload['transactions'];
        }
        if (isset($payload['data']['transactions']) && is_array($payload['data']['transactions'])) {
            return $payload['data']['transactions'];
        }
        if (isset($payload['data']) && is_array($payload['data'])) {
            return $payload['data'];
        }
        return is_array($payload) ? $payload : [];
    }
}
