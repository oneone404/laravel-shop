<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\GameAccount;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    /**
     * Hiển thị lịch sử bán tài khoản cho seller
     */
    public function accounts()
    {
        $title = 'Lịch Sử Bán Tài Khoản';

        // ✅ Lấy danh sách acc đã bán của seller hiện tại
        $accounts = GameAccount::with(['category', 'buyer'])
            ->where('created_by', auth()->id())
            ->where('status', 'sold')
            ->orderByDesc('updated_at')
            ->paginate(10);

        // ✅ Tính tổng doanh thu & tổng số acc bán trong tháng này
        $stats = GameAccount::select(
                DB::raw('COUNT(*) as total_sold'),
                DB::raw('SUM(price) as total_revenue')
            )
            ->where('created_by', auth()->id())
            ->where('status', 'sold')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->first();

        $totalSold = $stats->total_sold ?? 0;
        $totalRevenue = $stats->total_revenue ?? 0;

        return view('seller.history.accounts', compact(
            'title',
            'accounts',
            'totalSold',
            'totalRevenue'
        ));
    }
}
