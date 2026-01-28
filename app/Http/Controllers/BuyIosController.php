<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\MoneyTransaction;
use App\Models\User;

class BuyIosController extends Controller
{
    public function showForm()
    {
        $games = ['PLAY TOGETHER VNG'];
        $packages = [
            '1 Tháng' => 300000,
            '2 Tháng' => 500000,
            'Vĩnh Viễn' => 1000000,
        ];

        return view('user.buy-ios', compact('games', 'packages'));
    }

    public function purchase(Request $request)
{
    $request->validate([
        'game' => 'required|string',
        'package' => 'required|string',
    ]);

    $user = Auth::user();

    $packages = [
        '1_month' => 300000,
        '2_month' => 500000,
        'vinhvien' => 1000000,
    ];

    $package = $request->package;

    if (!isset($packages[$package])) {
        return back()->with('error', 'HIỆN TẠI KHÔNG CÓ KEY CHO GAME NÀY');
    }

    $price = $packages[$package];
    $previousBalance = $user->balance;

    if ($user->balance < $price) {
        return back()->with('error', 'SỐ DƯ KHÔNG ĐỦ ĐỂ GIAO DỊCH');
    }

    DB::beginTransaction();
    try {
        // Trừ tiền
        $user->balance -= $price;
        $user->save();

        // Ghi log giao dịch (nếu bạn muốn lưu lại)
        MoneyTransaction::create([
            'user_id' => $user->id,
            'type' => 'purchase',
            'amount' => $price,
            'balance_before' => $previousBalance,
            'balance_after' => $user->balance,
            'description' => 'MUA GÓI IOS ' . strtoupper($package) . ' - GAME ' . strtoupper($request->game),
        ]);

        DB::commit();

        return back()->with('success', 'MUA THÀNH CÔNG');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'ERROR: ' . $e->getMessage());
    }
}

}
