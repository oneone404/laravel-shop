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
use Carbon\Carbon;

class FetchMBTransactions extends Command
{
    protected function logToBankFile($message)
    {
        $logPath = __DIR__ . '/bank.log';
        $timestamp = now()->format('Y-m-d H:i:s');
        File::append($logPath, "[$timestamp] $message" . PHP_EOL);
    }
    protected $signature = 'fetch:mb-transactions';
    protected $description = 'Fetch new transactions from bank accounts via SePay API';

    public function handle()
    {
        $this->info('===== QUÉT GIAO DỊCH NGÂN HÀNG MBBANK (SEPAY) =====');
        // Lấy tất cả tài khoản ngân hàng có tự động cộng tiền
        $bankAccounts = BankAccount::where('auto_confirm', true)
            ->where('is_active', true)
            ->where('bank_name', 'MBBank')   // <-- thêm dòng này
            ->whereNotNull('access_token')
            ->get();

        if ($bankAccounts->isEmpty()) {
            $this->warn('Không Có Tài Khoản Nào Được Cấu Hình');
            return;
        }

        $this->info('Tìm Thấy ' . $bankAccounts->count() . ' Ngân Hàng MBbank Đã Cấu Hình');
        $apiUrl = 'https://my.sepay.vn/userapi/transactions/list';
        $totalProcessed = 0;

        foreach ($bankAccounts as $bankAccount) {
            $this->info('------------------------------');
            $this->info('Ngân Hàng: ' . $bankAccount->bank_name . ' - ' . $bankAccount->account_number);
            $prefix = $bankAccount->prefix ?? 'NAPTIEN';
            $this->info('Nội Dung Xử Lý: (' . $prefix . ')');
            // Sử dụng access_token riêng của mỗi tài khoản
            if (empty($bankAccount->access_token)) {
                $this->error('Tài Khoản ' . $bankAccount->bank_name . ' Chưa Cấu Hình Access Token (PAY2S)');
                continue;
            }

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $bankAccount->access_token,
                    'Content-Type' => 'application/json',
                ])->get($apiUrl, [
                            'account_number' => $bankAccount->account_number,
                            'limit' => 10,
                        ]);

                if ($response->successful()) {
                    $transactions = $response->json();
                    $processedCount = 0;
                    $skippedCount = 0;
                    // Lưu toàn bộ lịch sử giao dịch trả về ra file JSON
                    $lsgdPath = __DIR__ . '/lsgd.json';
                    File::put($lsgdPath, json_encode($transactions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    $this->info("Đã Lưu Vào lsgd.json");

                    $this->info('Tìm Thấy ' . count($transactions['transactions'] ?? []) . ' Giao Dịch');
                    // print_r($transactions['transactions']); debug
                    foreach ($transactions['transactions'] ?? [] as $transaction) {
                        // Sử dụng prefix từ cấu hình tài khoản ngân hàng
                        $prefix = $bankAccount->prefix ?? 'naptien';
                        $id = get_id_bank($prefix, $transaction['transaction_content']);

                        $amountIn = (float) $transaction['amount_in'];

                        // Bỏ qua nếu không phải tiền vào
                        if ($amountIn <= 0) {
                            $skippedCount++;
                            continue;
                        }

                        // Bỏ qua nếu tiền vào quá nhỏ (< 10.000đ)
                        if ($amountIn < 10000) {
                            $msg = 'Bỏ Qua Giao Dịch Nhỏ Hơn 10.000 VND: ' . number_format($amountIn) . ' VND';
                            $this->line($msg);
                            $this->logToBankFile($msg);
                            $skippedCount++;
                            continue;
                        }

                        if ($id == 0) {
                            $msg = 'Bỏ Qua Giao Dịch Không Tìm Thấy Người Dùng: ' . $transaction['transaction_content'];
                            $this->line($msg);
                            $this->logToBankFile($msg);
                            $skippedCount++;
                            continue;
                        }

                        if (BankDeposit::where('transaction_id', $transaction['reference_number'])->exists() || !User::find($id)) {
                            $this->line('Bỏ Qua Giao Dịch Dã Xử Lý: ' . $transaction['reference_number']);
                            $skippedCount++;
                            continue;
                        }

                        try {
                            DB::beginTransaction();

                            // Kiểm tra và lưu thông tin giao dịch ngân hàng
                            $bankDeposit = BankDeposit::updateOrCreate(
                                ['transaction_id' => $transaction['reference_number']], // Kiểm tra nếu đã có giao dịch này chưa
                                [
                                    'user_id' => $id,
                                    'account_number' => $transaction['account_number'],
                                    'amount' => $transaction['amount_in'],
                                    'content' => $transaction['transaction_content'],
                                    'bank' => $bankAccount->bank_name
                                ]
                            );

                            // Chỉ cập nhật số dư và lưu lịch sử nếu bản ghi mới được tạo
                            if ($bankDeposit->wasRecentlyCreated) {
                                // Tìm user và cập nhật số dư
                                $user = User::find($id);

                                if (!$user) {
                                    $this->error("Không tìm thấy người dùng với ID: $id");
                                    DB::rollBack();
                                    continue;
                                }

                                $balanceBefore = $user->balance;
                                $amount = $transaction['amount_in'];

                                // Cập nhật số dư và tổng tiền đã nạp
                                $user->balance += $amount;
                                $user->total_deposited += $amount;
                                $user->save();

                                // Lưu lịch sử giao dịch
                                MoneyTransaction::create([
                                    'user_id' => $id,
                                    'type' => 'deposit',
                                    'amount' => $amount,
                                    'balance_before' => $balanceBefore,
                                    'balance_after' => $user->balance,
                                    'description' => "NẠP TIỀN {$bankAccount->bank_name} - MÃ GIAO DỊCH: {$transaction['reference_number']}",
                                    'reference_id' => $transaction['reference_number']
                                ]);

                                $this->info("Cộng Thành Công " . number_format($amount) . " VND Cho User #$id");
                                $processedCount++;
                                $totalProcessed++;
                            }

                            DB::commit();

                        } catch (\Exception $e) {
                            DB::rollBack();
                            $this->error('Lỗi xử lý giao dịch: ' . $e->getMessage());
                            continue;
                        }
                    }

                } else {
                    $this->error('Không thể lấy dữ liệu giao dịch: ' . $response->status() . ' - ' . $response->body());
                }
            } catch (\Exception $e) {
                $this->error('Lỗi kết nối API: ' . $e->getMessage());
            }
        }

        $this->info('===== KẾT THÚC QUÉT GIAO DỊCH NGÂN HÀNG MBBANK (SEPAY) =====');
        $this->info("Tổng Số Giao Dịch Xử Lý: $totalProcessed");
    }
}
