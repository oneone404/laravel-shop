<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\GameAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = Auth::id();
        $selectedMonth = $request->month ?? now()->month;

        $onlineUsers = User::where('updated_at', '>=', now()->subMinutes(60))->count();

        // 1. Doanh thu từ tài khoản thường (Play/Clone)
        $revenueNormal = GameAccount::where('created_by', $sellerId)
            ->where('status', 'sold')
            ->whereNotNull('buyer_id')
            ->whereMonth('updated_at', $selectedMonth)
            ->whereYear('updated_at', now()->year)
            ->sum('price');

        // 2. Doanh thu từ tài khoản Random
        $revenueRandom = \App\Models\PurchasedRandomAccount::where('seller_id', $sellerId)
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', now()->year)
            ->sum('price');

        $totalRevenue = $revenueNormal + $revenueRandom;

        // Số lượng đã bán
        $soldNormal = GameAccount::where('created_by', $sellerId)
            ->where('status', 'sold')
            ->whereNotNull('buyer_id')
            ->count();
        
        $soldRandom = \App\Models\PurchasedRandomAccount::where('seller_id', $sellerId)->count();
        $soldAccounts = $soldNormal + $soldRandom;

        // Số lượng còn lại (Available)
        // Với Play/Clone: đếm số row available
        $availableNormal = GameAccount::where('created_by', $sellerId)
            ->where('status', 'available')
            ->whereHas('category', function($q) {
                $q->whereIn('type', ['play', 'clone']);
            })
            ->count();
        
        // Với Random: Quét TOÀN BỘ các nhóm random để tìm acc có sid là mình
        $allRandomGroups = GameAccount::whereHas('category', function($q) {
                $q->where('type', 'random');
            })
            ->where('status', 'available')
            ->get();
        
        $availableRandom = 0;
        foreach($allRandomGroups as $group) {
            if (is_array($group->accounts_data)) {
                foreach($group->accounts_data as $item) {
                    $ownerId = is_array($item) ? ($item['sid'] ?? $group->created_by) : $group->created_by;
                    if ($ownerId == $sellerId) {
                        $availableRandom++;
                    }
                }
            }
        }
        
        $availableAccounts = $availableNormal + $availableRandom;

        // Đã bán hôm nay
        $soldNormalToday = GameAccount::where('created_by', $sellerId)
            ->where('status','sold')
            ->whereNotNull('buyer_id')
            ->whereDate('updated_at', today())
            ->count();
        $soldRandomToday = \App\Models\PurchasedRandomAccount::where('seller_id', $sellerId)
            ->whereDate('created_at', today())
            ->count();
        $soldToday = $soldNormalToday + $soldRandomToday;

        // Doanh thu hôm nay
        $revenueNormalToday = GameAccount::where('created_by', $sellerId)
            ->where('status','sold')
            ->whereNotNull('buyer_id')
            ->whereDate('updated_at', today())
            ->sum('price');
        $revenueRandomToday = \App\Models\PurchasedRandomAccount::where('seller_id', $sellerId)
            ->whereDate('created_at', today())
            ->sum('price');
        $revenueToday = $revenueNormalToday + $revenueRandomToday;

        // Chart (Daily Revenue)
        $dailyRevenueNormal = GameAccount::select(
                DB::raw('DAY(updated_at) as day'),
                DB::raw('SUM(price) as total')
            )
            ->where('created_by', $sellerId)
            ->where('status', 'sold')
            ->whereNotNull('buyer_id')
            ->whereMonth('updated_at', $selectedMonth)
            ->groupBy('day')
            ->get();

        $dailyRevenueRandom = \App\Models\PurchasedRandomAccount::select(
                DB::raw('DAY(created_at) as day'),
                DB::raw('SUM(price) as total')
            )
            ->where('seller_id', $sellerId)
            ->whereMonth('created_at', $selectedMonth)
            ->groupBy('day')
            ->get();

        // Merge chart data
        $mergedDaily = [];
        foreach($dailyRevenueNormal as $d) {
            $mergedDaily[$d->day] = ($mergedDaily[$d->day] ?? 0) + $d->total;
        }
        foreach($dailyRevenueRandom as $d) {
            $mergedDaily[$d->day] = ($mergedDaily[$d->day] ?? 0) + $d->total;
        }
        ksort($mergedDaily);

        $chartLabels = [];
        $chartData = [];
        foreach($mergedDaily as $day => $total) {
            $chartLabels[] = $day . "/$selectedMonth";
            $chartData[] = $total;
        }

        return view('seller.dashboard', compact(
            'onlineUsers',
            'totalRevenue',
            'soldAccounts',
            'availableAccounts',
            'soldToday',
            'revenueToday',
            'chartLabels',
            'chartData',
            'selectedMonth'
        ));
    }

}
