<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BankDeposit;

class BackfillBankDepositHash extends Command
{
    protected $signature = 'bank:backfill-hash';
    protected $description = 'Backfill transaction_hash for old bank deposits';

    public function handle()
    {
        $this->info('Bắt đầu cập nhật transaction_hash...');

        $count = 0;

        BankDeposit::whereNull('transaction_hash')
            ->chunk(200, function ($rows) use (&$count) {
                foreach ($rows as $row) {
                    $hash = hash('sha256', implode('|', [
                        $row->bank,
                        $row->account_number,
                        $row->amount,
                        trim(mb_strtolower($row->content)),
                        optional($row->created_at)->format('Y-m-d H:i'),
                    ]));

                    // nếu trùng hash (hiếm) thì bỏ qua
                    if (!BankDeposit::where('transaction_hash', $hash)->exists()) {
                        $row->transaction_hash = $hash;
                        $row->save();
                        $count++;
                    }
                }
            });

        $this->info("Hoàn tất. Đã cập nhật {$count} bản ghi.");
    }
}
