<?php

namespace App\Console\Commands;

use App\Services\HackVietService;
use Illuminate\Console\Command;

class HackVietLogin extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'hackviet:login';

    /**
     * The console command description.
     */
    protected $description = 'Login to HackViet and refresh session cookies';

    /**
     * Execute the console command.
     */
    public function handle(HackVietService $service): int
    {
        $this->info('[HackViet] Attempting login...');
        
        $result = $service->login();

        if ($result['success']) {
            $this->info('[HackViet] ✓ Login successful! Cookies saved.');
            return Command::SUCCESS;
        }

        $this->error('[HackViet] ✗ Login failed: ' . ($result['error'] ?? 'Unknown error'));
        return Command::FAILURE;
    }
}
