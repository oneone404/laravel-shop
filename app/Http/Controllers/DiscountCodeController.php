<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DiscountCodeController extends Controller
{
    /**
     * Validate a discount code for a specific context.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
        public function validateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'context' => 'required|string|in:account,random_account,service',
            'item_id' => 'required|integer'
        ]);

        $code = $request->input('code');
        $context = $request->input('context');
        $itemId = $request->input('item_id');

        // Find the discount code
        $discountCode = DiscountCode::where('code', $code)
            ->where('is_active', '1')
            ->first();

        if (!$discountCode) {
            return response()->json([
                'success' => false,
                'message' => 'Mã Giảm Giá Không Hợp Lệ'
            ]);
        }

        // Check if the code is expired
        if ($discountCode->expire_date && now() > $discountCode->expire_date) {
            return response()->json([
                'success' => false,
                'message' => 'Mã Giảm Giá Quá Hạn Sử Dụng'
            ]);
        }

        // Check if the code has reached its usage limit
        if ($discountCode->usage_limit && $discountCode->usage_count >= $discountCode->usage_limit) {
            return response()->json([
                'success' => false,
                'message' => 'Mã Giảm Giá Giới Hạn Sử Dụng'
            ]);
        }

        // Check context-specific rules
        if ($discountCode->applicable_to && $discountCode->applicable_to !== $context) {
            return response()->json([
                'success' => false,
                'message' => 'Mã Giảm Giá Không Dùng Cho Giao Dịch Này'
            ]);
        }

        // Check if the user already used this code
        if (Auth::check() && $discountCode->per_user_limit) {
            $userUsageCount = $discountCode->usages()
                ->where('user_id', Auth::id())
                ->count();

            if ($userUsageCount >= $discountCode->per_user_limit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn Đã Dùng Đủ Số Lần Cho Mã Giảm Giá Này'
                ]);
            }
        }

        // ✅ Trả về thông tin mã giảm giá hợp lệ
        return response()->json([
            'success' => true,
            'message' => 'Thành Công',
            'discount_type' => $discountCode->discount_type, // 'percentage' hoặc 'fixed'
            'discount_value' => $discountCode->discount_value,
            'code' => $discountCode->code
        ]);
    }
    /**
     * Get original price based on context and item_id
     *
     * @param string $context
     * @param int $itemId
     * @return float
     */
    private function getOriginalPrice($context, $itemId)
    {
        switch ($context) {
            case 'account':
                // Get price from accounts table
                $account = DB::table('game_accounts')->where('id', $itemId)->first();
                return $account ? $account->price : 0;

            case 'random_account':
                // Get price from random_category_accounts table
                $randomAccount = DB::table('random_category_accounts')->where('id', $itemId)->first();
                return $randomAccount ? $randomAccount->price : 0;

            case 'service':
                // Get price from service_packages table
                $servicePackage = DB::table('service_packages')->where('id', $itemId)->first();
                return $servicePackage ? $servicePackage->price : 0;

            default:
                return 0;
        }
    }

    /**
     * Calculate discounted price based on original price and discount code
     *
     * @param float $originalPrice
     * @param DiscountCode $discountCode
     * @return float
     */
    private function calculateDiscountedPrice($originalPrice, $discountCode)
    {
        if ($discountCode->discount_type === 'percentage') {
            $discount = $originalPrice * ($discountCode->discount_value / 100);
            // If there's a maximum discount value, apply it
            if ($discountCode->max_discount_value && $discount > $discountCode->max_discount_value) {
                $discount = $discountCode->max_discount_value;
            }
            return $originalPrice - $discount;
        } else { // fixed_amount
            return max(0, $originalPrice - $discountCode->discount_value);
        }
    }

    /**
     * Apply a discount code during a purchase
     *
     * @param DiscountCode $discountCode
     * @param string $context
     * @param int $itemId
     * @param int $userId
     * @param float $originalPrice
     * @param float $discountedPrice
     * @return void
     */
    public function applyDiscountCode($discountCode, $context, $itemId, $userId, $originalPrice, $discountedPrice)
    {
        try {
            // Increment usage count
            DB::table('discount_codes')
                ->where('id', $discountCode->id)
                ->increment('usage_count');

            // Record usage
            DB::table('discount_code_usages')->insert([
                'discount_code_id' => $discountCode->id,
                'user_id' => $userId,
                'context' => $context,
                'item_id' => $itemId,
                'original_price' => $originalPrice,
                'discounted_price' => $discountedPrice,
                'discount_amount' => $originalPrice - $discountedPrice,
                'used_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail transaction
            Log::error('Error applying discount code: ' . $e->getMessage());
        }
    }
}
