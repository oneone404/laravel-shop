<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\GameAccount;
use App\Models\GameService;
use App\Models\LuckyWheel;
use App\Models\ServiceHistory;
use App\Models\RandomCategory;
use App\Models\RandomCategoryAccount;
use App\Models\MoneyTransaction;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //
    public function index()
    {
        // ✅ Chia nhóm category theo type
        $categories_play = Category::where('active', 1)
            ->where('type', 'play')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($categories_play as $category) {
            $category->soldCount = $category->sold_count;
            $category->availableAccount = $category->available_account;
        }

        $categories_clone = Category::where('active', 1)
            ->where('type', 'clone')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($categories_clone as $category) {
            $category->soldCount = $category->sold_count;
            $category->availableAccount = $category->available_account;
        }

        // ✅ Random categories (đã có bảng riêng - cũ)
        $randomCategories = RandomCategory::where('active', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($randomCategories as $category) {
            $category->soldCount = RandomCategoryAccount::where('random_category_id', $category->id)
                ->where('status', 'sold')
                ->count();
            $category->allAccount = RandomCategoryAccount::where('random_category_id', $category->id)->count();
        }

        // ✅ Random categories từ GameCategory (mới)
        $categories_random = Category::where('active', 1)
            ->where('type', 'random')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($categories_random as $category) {
            $category->soldCount = $category->sold_count;
            $category->availableAccount = $category->available_account;
        }

        // ✅ Dịch vụ cày thuê
        $services = GameService::where('active', 1)->orderBy('updated_at', 'desc')->get();
        foreach ($services as $service) {
            $service->orderCount = ServiceHistory::where('game_service_id', $service->id)->count();
        }

        // ✅ Vòng quay may mắn
        $LuckWheel = LuckyWheel::where('active', 1)->orderBy('updated_at', 'desc')->get();
        foreach ($LuckWheel as $wheel) {
            $wheel->soldCount = $wheel->histories->count();
        }

        // ✅ Giao dịch gần đây
        $recentTransactions = MoneyTransaction::with('user')
            ->whereNotIn('type', ['none', 'draw'])
            ->where(function ($query) {
                $query->where('description', 'LIKE', '%MUA TÀI KHOẢN%')
                    ->orWhere('description', 'LIKE', '%MUA KEY%')
                    ->orWhere('type', 'deposit');
            })
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // ✅ Tổng số giao dịch thành công (để hiển thị Uy tín)
        $totalTransactions = MoneyTransaction::whereNotIn('type', ['none', 'draw'])->count();

        // ✅ Top 3 người nạp nhiều nhất tháng hiện tại
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $topDepositors = MoneyTransaction::select('user_id', DB::raw('SUM(amount) as total_amount'))
            ->where('type', 'deposit')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->groupBy('user_id')
            ->orderBy('total_amount', 'desc')
            ->limit(3)
            ->get();

        foreach ($topDepositors as $depositor) {
            $depositor->user = \App\Models\User::find($depositor->user_id);
        }

        $notifications = Notification::orderBy('created_at', 'asc')->get();

        $pendingOrdersCount = \App\Models\DirectOrder::getCurrentPendingOrders()->count();

        // Đếm số thông báo từ bản hack (ID=1)
        $hackNotiCount = 0;
        $mainHack = \App\Models\GameHack::find(1);
        if ($mainHack && !empty($mainHack->description)) {
            $hackNotiCount = count(array_filter(explode("\n", str_replace("\r", "", $mainHack->description))));
        }

        return view('user.home', compact(
            'categories_play',
            'categories_clone',
            'categories_random',
            'randomCategories',
            'services',
            'LuckWheel',
            'recentTransactions',
            'topDepositors',
            'notifications',
            'pendingOrdersCount',
            'hackNotiCount',
            'totalTransactions'
        ));
    }

    public function tools()
    {
        return view('user.tools');
    }
}
