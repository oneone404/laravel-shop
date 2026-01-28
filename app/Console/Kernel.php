<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Câu lệnh chạy CRON TAB: php artisan fetch:mb-transactions
        $schedule->command('fetch:acb-transactions')->everyMinute();
        
        // Quét thanh toán trực tiếp mỗi phút
        $schedule->command('process:direct-payments')->everyMinute();
        
        // Keep-alive HackViet session mỗi giờ
        $schedule->command('hackviet:keep-alive')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
