<?php

namespace App\Console\Commands;

use App\Models\BankDeposit;
use App\Models\BankAccount;
use App\Models\User;
use App\Models\MoneyTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FetchACBTransactionsPay2S extends Command
{
    protected $signature = 'fetch:acb-transactions';
    protected $description = 'Fetch new ACB transactions via Pay2S API';

    protected function logToBankFile($message)
    {
        $logPath = storage_path('logs/bank_acb_pay2s.log');
        $timestamp = now()->format('Y-m-d H:i:s');
        File::append($logPath, "[$timestamp] $message" . PHP_EOL);
    }

    public function handle()
    {
        $this->info('===== QUÉT GIAO DỊCH NGÂN HÀNG ACB (PAY2S) =====');

        // Lấy các tài khoản ACB kích hoạt auto + có token
        $bankAccounts = BankAccount::where('auto_confirm', true)
            ->where('is_active', true)
            ->where('bank_name', 'ACB')
            ->whereNotNull('access_token')
            ->get();

        if ($bankAccounts->isEmpty()) {
            $this->warn('Không Có Tài Khoản Nào Được Cấu Hình');
            return;
        }

        $this->info('Tìm Thấy ' . $bankAccounts->count() . ' Ngân Hàng ACB Đã Cấu Hình');
        $totalProcessed = 0;

        // Config Pay2S
        $baseUrl   = rtrim(config('services.pay2s.api_base', ''), '/');
        $apiPath   = config('services.pay2s.transactions_path', '/transactions');
        $apiUrl    = $baseUrl . $apiPath;
        $minAmount = (int) config('services.pay2s.min_amount', 10000);
        $tzApi     = config('services.pay2s.timezone', 'Asia/Ho_Chi_Minh');

        foreach ($bankAccounts as $bankAccount) {
            $this->info('------------------------------');
            $this->info('Ngân Hàng ' . $bankAccount->bank_name . ' - ' . $bankAccount->account_number);

            // Lấy prefix, nếu null thì in ra mặc định
            $prefix = $bankAccount->prefix ?? 'NAPTIEN';
            $this->info('Nội Dung Xử Lý: (' . $prefix . ')');

            $token = $bankAccount->access_token;
            if (empty($token)) {
                $msg = 'Tài Khoản ' . $bankAccount->account_number . ' Chưa Cấu Hình Access Token (Pay2S)';
                $this->error($msg);
                $this->logToBankFile($msg);
                continue;
            }

            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'pay2s-token'  => $bankAccount->access_token,
                ])->post(
                    rtrim(config('services.pay2s.api_base'), '/') . config('services.pay2s.transactions_path'),
                    [
                        'bankAccounts' => $bankAccount->account_number,
                        'begin'        => now()->format('d/m/Y'),
                        'end'          => now()->format('d/m/Y'),
                    ]
                );

                if (!$response->successful()) {
                    $msg = 'Pay2S: ' . $response->status() . ' - ' . $response->body();
                    $this->error($msg);
                    $this->logToBankFile($msg);
                    continue;
                }

                $payload = $response->json();

                // Lưu raw JSON để audit
                $rawPath = __DIR__ . '/lsgd.json';
                File::put($rawPath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $this->info("Đã Lưu Vào lsgd.json");

                $txs = $this->extractTransactionsArray($payload);
                $this->info('Tìm Thấy ' . count($txs) . ' Giao Dịch');

                $processedCount = 0;
                $skippedCount   = 0;

                foreach ($txs as $rawTx) {
                    $tx = $this->normalizePay2sTx($rawTx, $tzApi);

                    // Chỉ xét tiền vào
                    if ($tx['amount_in'] <= 0) {
                        $skippedCount++;
                        continue;
                    }

                    if ($tx['amount_in'] < $minAmount) {
                        $msg = 'Bỏ Qua Giao Dịch Nhỏ Hơn 10.000 VND ' . number_format($tx['amount_in']) . ' VND';
                        $this->line($msg);
                        $this->logToBankFile($msg);
                        $skippedCount++;
                        continue;
                    }

                    $prefix = $bankAccount->prefix ?? 'NAPTIEN';
                    $userId = get_id_bank($prefix, $tx['content']);

                    if (!$userId || !User::find($userId)) {
                        $this->line("Bỏ Qua Giao Dịch Không Tìm Thấy Người Dùng");
                        $skippedCount++;
                        continue;
                    }

                    $txHash = $this->makeTransactionHash($tx, $bankAccount);

                    // Chống trùng bằng HASH
                    if (BankDeposit::where('transaction_hash', $txHash)->exists()) {
                        $this->line('Bỏ Qua Giao Dịch Đã Xử Lý: ' . $tx['reference']);
                        $skippedCount++;
                        continue;
                    }

                    try {
                        DB::beginTransaction();

                        // 👉 ĐÃ CHECK HASH Ở NGOÀI → TỚI ĐÂY LÀ GIAO DỊCH MỚI
                        BankDeposit::create([
                            'transaction_id'   => $tx['reference'], // chỉ để audit
                            'transaction_hash' => $txHash,
                            'user_id'          => $userId,
                            'account_number'   => $tx['account_number'] ?? $bankAccount->account_number,
                            'amount'           => $tx['amount_in'],
                            'content'          => $tx['content'],
                            'bank'             => 'ACB',
                            'created_at'       => $tx['occurred_at'] ?? now(),
                            'updated_at'       => $tx['occurred_at'] ?? now(),
                        ]);

                        // 👉 LUÔN CỘNG TIỀN
                        $user = User::findOrFail($userId);
                        $balanceBefore = $user->balance;
                        $amount = $tx['amount_in'];

                        $user->balance += $amount;
                        $user->total_deposited += $amount;
                        $user->save();

                        MoneyTransaction::create([
                            'user_id'        => $userId,
                            'type'           => 'deposit',
                            'amount'         => $amount,
                            'balance_before' => $balanceBefore,
                            'balance_after'  => $user->balance,
                            'description'    => "NẠP TIỀN ACB - {$tx['account_number']} - {$tx['reference']}",
                            'reference_id'   => $tx['reference'],
                            'created_at'     => $tx['occurred_at'] ?? now(),
                            'updated_at'     => $tx['occurred_at'] ?? now(),
                        ]);

                        DB::commit();

                        // ✅ LUÔN IN RA KHI CỘNG TIỀN
                        $this->info(
                            "Cộng Thành Công "
                            . number_format($amount)
                            . " VND Cho User #{$userId} (ACB {$tx['account_number']} - {$tx['reference']})"
                        );

                        $totalProcessed++;
                        $processedCount++;

                    } catch (\Throwable $e) {
                        DB::rollBack();

                        $msg = 'Lỗi Xử Lý Giao Dịch '
                            . ($tx['reference'] ?? 'UNKNOWN')
                            . ': '
                            . $e->getMessage();

                        $this->error($msg);
                        Log::error($msg, ['tx' => $tx, 'raw' => $rawTx]);

                        $skippedCount++;
                        continue;
                    }
                }

            } catch (\Throwable $e) {
                $msg = 'Lỗi Pay2S: ' . $e->getMessage();
                $this->error($msg);
                $this->logToBankFile($msg);
            }
        }

        $this->info('===== KẾT THÚC QUÉT GIAO DỊCH NGÂN HÀNG ACB (PAY2S) =====');
        $this->info("Tổng Số Giao Dịch Xử Lý: $totalProcessed");
    }

    protected function makeTransactionHash(array $tx, BankAccount $bankAccount): string
    {
        return hash('sha256', implode('|', [
            'ACB',
            $bankAccount->id,                 // 🔥 QUAN TRỌNG NHẤT
            $tx['account_number'],
            $tx['amount_in'],
            trim(mb_strtolower($tx['content'])),
            $tx['occurred_at']->format('Y-m-d H:i'),
        ]));
    }
    /**
     * Chuẩn hóa mảng giao dịch về format chung để xử lý.
     * Tuỳ cấu trúc Pay2S, bạn map lại các key tại đây.
     */
    protected function normalizePay2sTx(array $raw, string $tzApi): array
    {
        // Pay2S fields: transaction_id, amount, description, type, account_number, transaction_date, checksum
        $reference      = $raw['transaction_id'] ?? $raw['reference'] ?? $raw['ref'] ?? null;
        $amountIn       = ($raw['type'] ?? null) === 'IN' ? (int) ($raw['amount'] ?? 0) : 0;
        $content        = (string) ($raw['description'] ?? $raw['content'] ?? $raw['memo'] ?? '');
        $accountNumber  = $raw['account_number'] ?? $raw['accountNo'] ?? null;
        $occurredString = $raw['transaction_date'] ?? $raw['transacted_at'] ?? $raw['created_at'] ?? $raw['time'] ?? null;
        $checksum       = $raw['checksum'] ?? null; // unique từ Pay2S

        $occurredAt = $occurredString
            ? Carbon::parse($occurredString, $tzApi)
            : now();

        return [
            'reference'      => $reference ? (string) $reference : null,
            'checksum'       => $checksum ? (string) $checksum : null,
            'amount_in'      => (int) $amountIn,
            'content'        => $content,
            'account_number' => $accountNumber,
            'occurred_at'    => $occurredAt,
        ];
    }
    /**
     * Chuẩn bị mảng transactions từ payload Pay2S (tùy API có thể nested khác nhau)
     */
    protected function extractTransactionsArray($payload): array
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
