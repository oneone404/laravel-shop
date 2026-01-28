<?php

namespace App\Console\Commands;

use App\Services\HackVietService;
use Illuminate\Console\Command;

class HackVietKeepAlive extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'hackviet:keep-alive {--force : Force keep-alive even if not needed}';

    /**
     * The console command description.
     */
    protected $description = 'Keep HackViet session alive by sending periodic requests';

    /**
     * Execute the console command.
     */
    public function handle(HackVietService $service): int
    {
        $force = $this->option('force');

        if (!$force && !$service->needsKeepAlive()) {
            $this->info('[HackViet] Session still active, no keep-alive needed.');
            return Command::SUCCESS;
        }

        $this->info('[HackViet] Sending keep-alive request...');
        
        $result = $service->keepAlive();

        if ($result['success']) {
            $this->info('[HackViet] ✓ ' . ($result['message'] ?? 'Session refreshed'));
            return Command::SUCCESS;
        }

        $this->error('[HackViet] ✗ ' . ($result['error'] ?? 'Unknown error'));
        return Command::FAILURE;
    }
}
