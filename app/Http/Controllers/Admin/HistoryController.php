<?php
/**
 * Copyright (c) 2025 FPT University
 *
 * @author    Phạm Hoàng Tuấn
 * @email     phamhoangtuanqn@gmail.com
 * @facebook  fb.com/phamhoangtuanqn
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RandomAccountPurchase;
use App\Models\ServiceOrder;
use App\Models\BankDeposit;
use App\Models\CardDeposit;
use App\Models\DiscountCodeUsage;
use App\Models\MoneyTransaction;
use App\Models\GameAccount;
use App\Models\ServiceHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HistoryController extends Controller
{
    /**
     * Display the general transaction history
     */
    public function transactions()
    {
        $title = 'Lịch Sử Giao Dịch';
        $transactions = MoneyTransaction::with('user')->orderBy('created_at', 'desc')->paginate(100);

        return view('admin.history.transactions', compact('title', 'transactions'));
    }

    /**
     * Display the account purchase history
     */
    public function accounts()
    {
        $title = 'Lịch sử mua tài khoản';
        $accounts = GameAccount::with(['buyer', 'category'])
            ->where('status', 'sold')
            ->whereNotNull('buyer_id')
            ->orderBy('updated_at', 'desc')
            ->paginate(100);

        return view('admin.history.accounts', compact('title', 'accounts'));
    }

    /**
     * Display the random account purchase history
     */
    public function randomAccounts()
    {
        $title = 'Lịch sử mua tài khoản random';
        $purchases = RandomAccountPurchase::with(['user', 'account'])->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.history.random-accounts', compact('title', 'purchases'));
    }

    /**
     * Display the bank deposit history
     */
    public function bankDeposits()
    {
        $title = 'Lịch Sử Nạp Bank';
        $deposits = BankDeposit::with(['user', 'bankAccount'])->orderBy('created_at', 'desc')->paginate(100);

        return view('admin.history.bank-deposits', compact('title', 'deposits'));
    }

    /**
     * Display the card deposit history
     */
    public function cardDeposits()
{
    $title = 'Lịch Sử Nạp Thẻ';

    $deposits = CardDeposit::with('user')
        ->orderByRaw("
            CASE 
                WHEN status = 'processing' AND created_at < ? THEN 0 
                ELSE 1 
            END
        ", [Carbon::now()->subMinutes(5)])
        ->orderBy('created_at', 'desc')
        ->paginate(100);

    return view('admin.history.card-deposits', compact('title', 'deposits'));
}

    /**
     * Display the discount code usage history
     */
    public function discountUsages()
    {
        $title = 'Lịch sử sử dụng mã giảm giá';
        $usages = DiscountCodeUsage::with(['user', 'discountCode'])->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.history.discount-usages', compact('title', 'usages'));
    }
}
