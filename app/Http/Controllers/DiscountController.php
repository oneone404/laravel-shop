<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\RankHelper;
use App\Models\DiscountKey;

class DiscountController extends Controller
{
    public function getDiscountCodes()
    {
        $user = Auth::user();
        $rank = RankHelper::getUserRank($user->total_deposited);
        $totalDeposited = $user->total_deposited;

        $rankName = $rank['name'];
        $codesPerMonth = match ($rankName) {
            'Thành Viên Mới' => 0,
            'Thành Viên Bạc' => 1,
            'Thành Viên Vàng' => 3,
            'Thành Viên Bạch Kim' => 5,
            'Thành Viên Kim Cương' => 10,
            'Thành Viên Huyền Thoại' => 20,
            default => 0,
        };

        $alreadyClaimed = DiscountKey::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->exists();

        $codes = DiscountKey::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->get();

        $expiryDate = now()->endOfMonth()->format('d/m/Y');

        return view('user.discount_codes', compact(
            'codes',
            'rank',
            'rankName',
            'expiryDate',
            'codesPerMonth',
            'alreadyClaimed',
            'totalDeposited'
        ));
    }

    public function claimReward()
    {
        $user = Auth::user();
        $rank = RankHelper::getUserRank($user->total_deposited);

        $rankName = $rank['name'];
        $codesPerMonth = match ($rankName) {
            'Thành Viên Mới' => 0,
            'Thành Viên Bạc' => 1,
            'Thành Viên Vàng' => 3,
            'Thành Viên Bạch Kim' => 5,
            'Thành Viên Kim Cương' => 10,
            'Thành Viên Huyền Thoại' => 20,
            default => 0,
        };

        // Nếu là thành viên mới thì không cho nhận
        if ($rankName == 'Thành Viên Mới') {
            return back()->with('error', 'Thành Viên Bạc Trở Lên Mới Có Thể Nhận Thưởng');
        }

        // Nếu đã nhận tháng này thì báo lỗi
        $alreadyClaimed = DiscountKey::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->exists();

        if ($alreadyClaimed) {
            return back()->with('error', 'Bạn Đã Nhận Phần Thưởng');
        }

        // Tạo mã mới
        for ($i = 0; $i < $codesPerMonth; $i++) {
            DiscountKey::create([
                'user_id' => $user->id,
                'code' => strtoupper(Str::random(10)),
                'discount_type' => 'fixed_amount',
                'discount_value' => 10000,
                'applicable_to' => 'buy_key',
                'min_amount' => 50000,
                'max_discount' => 1,
                'used_count' => 0,
                'expires_at' => now()->endOfMonth()
            ]);
        }

        return back()->with('success', 'Nhận Thành Công Phần Thưởng');
    }
}
