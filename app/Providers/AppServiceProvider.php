<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Helpers\RankHelper;
use App\Models\BankAccount; // 👉 thêm dòng này

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Đăng ký các services tại đây
        $this->app->bind('config-helper', function () {
            return new \App\Helpers\ConfigHelper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Đăng ký view composer để chia sẻ dữ liệu vào tất cả các view
        View::composer('*', function ($view) {
            $user = Auth::user();

            if ($user) {
                // 🏅 Rank
                $rank = RankHelper::getUserRank($user->total_deposited);

                // 🏦 Bank Account Number (STK)
                $bank = \App\Models\BankAccount::where('is_active', 1)->first();
                $accountNumber = $bank ? $bank->account_number : 'Chưa có';
                $accountName = $bank ? $bank->account_name : 'Chưa có';

                $bankCode = $bank ? $bank->bank_name : 'ACB'; // hoặc $bank->prefix nếu bạn muốn

                // Chia sẻ dữ liệu ra toàn bộ view
                $view->with([
                    'rank' => $rank,
                    'accountNumber' => $accountNumber,
                    'bankCode' => $bankCode,
                    'accountName' => $accountName,
                ]);
            }
        });

        // Thay đổi view mặc định của paginator
        Paginator::defaultView('vendor.pagination.default');
    }

    /**
     * Get the user's rank based on their total deposit.
     */
    private function getUserRank($totalDeposited)
    {
        if ($totalDeposited < 100000) {
            return ['name' => 'Thành Viên Mới', 'image' => 'images/rank/dong.png'];
        } elseif ($totalDeposited < 300000) {
            return ['name' => 'Thành Viên Bạc', 'image' => 'images/rank/bac.png'];
        } elseif ($totalDeposited < 1000000) {
            return ['name' => 'Thành Viên Vàng', 'image' => 'images/rank/vang.png'];
        } elseif ($totalDeposited < 2000000) {
            return ['name' => 'Thành Viên Bạch Kim', 'image' => 'images/rank/bachkim.png'];
        } elseif ($totalDeposited < 5000000) {
            return ['name' => 'Thành Viên Kim Cương', 'image' => 'images/rank/kimcuong.png'];
        } else {
            return ['name' => 'Thành Viên Huyền Thoại', 'image' => 'images/rank/huyenthoai.png'];
        }
    }
}
