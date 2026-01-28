<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankDeposit;
use App\Models\CardDeposit;
use App\Models\Category;
use App\Models\DiscountCode;
use App\Models\GameAccount;
use App\Models\GameService;
use App\Models\LuckyWheel;
use App\Models\MoneyTransaction;
use App\Models\MoneyWithdrawalHistory;
use App\Models\Notification;
use App\Models\RandomCategory;
use App\Models\RandomCategoryAccount;
use App\Models\ServiceHistory;
use App\Models\ServicePackage;
use App\Models\User;
use App\Models\WithdrawalHistory;
use App\Models\KeyPurchaseHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index(): View
    {
        try {
            // === Thống kê người dùng ===
            $statistics['users'] = [
                'total' => User::count(),
                'admin' => User::where('role', 'admin')->count(),
                'user' => User::where('role', 'member')->count(),
                'new_today' => User::whereDate('created_at', Carbon::today())->count(),
                'new_this_week' => User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
                'new_this_month' => User::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count(),
                'total_balance' => User::sum('balance'),
            ];

            // === Tài khoản game ===
            $statistics['accounts'] = [
                'total' => GameAccount::count(),
                'available' => GameAccount::where('status', 'available')->count(),
                'sold' => GameAccount::where('status', 'sold')->count(),
                'locked' => GameAccount::where('status', 'locked')->count(),
                'pending' => GameAccount::where('status', 'pending')->count(),
            ];

            // === Tài khoản random ===
            $statistics['random_accounts'] = [
                'total' => RandomCategoryAccount::count(),
                'available' => RandomCategoryAccount::where('status', 'available')->count(),
                'sold' => RandomCategoryAccount::where('status', 'sold')->count(),
            ];

            // === Dịch vụ ===
            $statistics['services'] = [
                'total' => GameService::count(),
                'active' => GameService::where('active', true)->count(),
                'inactive' => GameService::where('active', false)->count(),
            ];

            // === Gói dịch vụ ===
            $statistics['packages'] = [
                'total' => ServicePackage::count(),
            ];

            // === Danh mục game và random ===
            $statistics['categories'] = [
                'total' => Category::count(),
                'active' => Category::where('active', true)->count(),
                'inactive' => Category::where('active', false)->count(),
            ];

            $statistics['random_categories'] = [
                'total' => RandomCategory::count(),
                'active' => RandomCategory::where('active', true)->count(),
                'inactive' => RandomCategory::where('active', false)->count(),
            ];

            // === Vòng quay may mắn ===
            $statistics['lucky_wheels'] = [
                'total' => LuckyWheel::count(),
                'active' => LuckyWheel::where('active', true)->count(),
                'inactive' => LuckyWheel::where('active', false)->count(),
            ];

            // === Dịch vụ theo loại ===
            $servicesByType = GameService::select('type', DB::raw('count(*) as total'))
                ->groupBy('type')
                ->get();

            $recentTransactions = MoneyTransaction::with(['user:id,username'])
                ->orderByDesc('created_at')
                ->limit(10)
                ->get(['id','user_id','amount','balance_before','balance_after','description','type','created_at']);

            // === Tổng hợp giao dịch ===
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            $startOfWeek = Carbon::now()->startOfWeek();
            $startOfMonth = Carbon::now()->startOfMonth();

            $transactionSummary = [
                'deposit' => [
                    'day' => MoneyTransaction::where('type', 'deposit')->whereDate('created_at', $today)->sum('amount'),
                    'yesterday' => MoneyTransaction::where('type', 'deposit')->whereDate('created_at', $yesterday)->sum('amount'),
                    'week' => MoneyTransaction::where('type', 'deposit')->whereBetween('created_at', [$startOfWeek, now()])->sum('amount'),
                    'month' => MoneyTransaction::where('type', 'deposit')->whereBetween('created_at', [$startOfMonth, now()])->sum('amount'),
                ],
                'purchase' => [
                    'day' => MoneyTransaction::where('type', 'purchase')->whereDate('created_at', $today)->sum('amount'),
                    'yesterday' => MoneyTransaction::where('type', 'purchase')->whereDate('created_at', $yesterday)->sum('amount'),
                    'week' => MoneyTransaction::where('type', 'purchase')->whereBetween('created_at', [$startOfWeek, now()])->sum('amount'),
                    'month' => MoneyTransaction::where('type', 'purchase')->whereBetween('created_at', [$startOfMonth, now()])->sum('amount'),
                ],
            ];

            // === Các đơn dịch vụ đang chờ xử lý ===
            $pendingServices = ServiceHistory::with('user', 'gameService', 'servicePackage')
                ->where('status', 'pending')
                ->latest()
                ->limit(5)
                ->get();

            // === Rút tiền chờ xử lý ===
            $pendingWithdrawals = MoneyWithdrawalHistory::with('user')
                ->where('status', 'pending')
                ->latest()
                ->limit(5)
                ->get();

            // === Rút tài nguyên chờ xử lý ===
            $pendingResourceWithdrawals = WithdrawalHistory::with('user')
                ->where('status', 'pending')
                ->latest()
                ->limit(5)
                ->get();

            // === Nạp thẻ gần đây ===
            $recentCardDeposits = CardDeposit::with('user')
                ->latest()
                ->limit(5)
                ->get();

            // === Nạp bank gần đây ===
            $recentBankDeposits = BankDeposit::with('user', 'bankAccount')
                ->latest()
                ->limit(5)
                ->get();

            // === Mã giảm giá đang hoạt động ===
            $activeDiscountCodes = DiscountCode::where('is_active', 1)
                ->where(function ($query) {
                    $query->where('expire_date', '>=', Carbon::now())->orWhereNull('expire_date');
                })
                ->limit(5)
                ->get();

            // === Doanh thu từng tháng trong năm hiện tại ===
            $monthlyRevenue = [];
            $currentYear = Carbon::now()->year;
            for ($month = 1; $month <= 12; $month++) {
                $monthlyRevenue[] = [
                    'month' => Carbon::createFromDate($currentYear, $month, 1)->format('m/Y'),
                    'purchases' => MoneyTransaction::where('type', 'purchase')->whereYear('created_at', $currentYear)->whereMonth('created_at', $month)->sum('amount'),
                    'deposits' => MoneyTransaction::where('type', 'deposit')->whereYear('created_at', $currentYear)->whereMonth('created_at', $month)->sum('amount'),
                ];
            }

            // === Tài khoản được mua gần đây ===
            $recentPurchases = GameAccount::with(['buyer', 'category'])
                ->where('status', 'sold')
                ->whereNotNull('buyer_id')
                ->latest()
                ->limit(3)
                ->get();

            $recentRandomPurchases = RandomCategoryAccount::with(['buyer', 'randomCategory'])
                ->where('status', 'sold')
                ->whereNotNull('buyer_id')
                ->latest()
                ->limit(2)
                ->get();

            $recentPurchases = $recentPurchases->merge($recentRandomPurchases)->sortByDesc('created_at')->take(5);

            return view('admin.dashboard', compact(
                'statistics',
                'transactionSummary',
                'servicesByType',
                'recentTransactions',
                'pendingServices',
                'pendingWithdrawals',
                'pendingResourceWithdrawals',
                'recentCardDeposits',
                'recentBankDeposits',
                'activeDiscountCodes',
                'monthlyRevenue',
                'recentPurchases'
            ));
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());

            return view('admin.dashboard', [
                'error' => $e->getMessage(),
                'statistics' => [],
                'transactionSummary' => [],
                'servicesByType' => collect(),
                'recentTransactions' => collect(),
                'pendingServices' => collect(),
                'pendingWithdrawals' => collect(),
                'pendingResourceWithdrawals' => collect(),
                'recentCardDeposits' => collect(),
                'recentBankDeposits' => collect(),
                'activeDiscountCodes' => collect(),
                'monthlyRevenue' => [],
                'recentPurchases' => collect(),
            ]);
        }
    }
}
