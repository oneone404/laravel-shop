<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use App\Models\GameAccount;
use App\Models\MoneyTransaction;
use App\Models\DiscountCode;
use App\Models\PurchasedRandomAccount;
use App\Http\Controllers\DiscountCodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameAccountController extends Controller
{
    public function show($id)
    {
        $account = GameAccount::findOrFail($id);

        // Tự động cast thành array bởi model
        $images = $account->images ?? [];

        return view("user.account.detail", compact('account', 'images'));
    }

    public function purchase(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $account = GameAccount::where('id', $id)
                ->where('status', 'available')
                ->lockForUpdate()
                ->firstOrFail();

            $user = Auth::user();
            $finalPrice = $account->price;
            $discountAmount = 0;
            $discountCodeController = new DiscountCodeController();

            // Check for discount code if provided
            if ($request->filled('discount_code')) {
                $discountCode = DiscountCode::where('code', $request->discount_code)
                    ->where('is_active', '1')
                    ->first();

                if ($discountCode) {
                    // Calculate discount
                    if ($discountCode->discount_type === 'percentage') {
                        $discountAmount = ($account->price * $discountCode->discount_value) / 100;
                        // Apply max discount if set
                        if ($discountCode->max_discount_value && $discountAmount > $discountCode->max_discount_value) {
                            $discountAmount = $discountCode->max_discount_value;
                        }
                    } else {
                        $discountAmount = $discountCode->discount_value;
                    }

                    // Calculate final price
                    $finalPrice = $account->price - $discountAmount;
                    if ($finalPrice < 0) {
                        $finalPrice = 0;
                    }

                    // Apply discount code
                    if ($discountCode) {
                        // Update usage count directly in database
                        DB::table('discount_codes')
                            ->where('id', $discountCode->id)
                            ->increment('usage_count');

                        // Record usage details
                        DB::table('discount_code_usages')->insert([
                            'discount_code_id' => $discountCode->id,
                            'user_id' => $user->id,
                            'context' => 'account',
                            'item_id' => $account->id,
                            'original_price' => $account->price,
                            'discounted_price' => $finalPrice,
                            'discount_amount' => $discountAmount,
                            'used_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            if ($user->balance < $finalPrice) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Số Dư Không Đủ!'
                ]);
            }

            // Update user balance
            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore - $finalPrice;

            // Use direct DB update instead of model save
            DB::table('users')
                ->where('id', $user->id)
                ->update(['balance' => $balanceAfter]);

            // … đoạn code sau khi update game_accounts …
            DB::table('game_accounts')
                ->where('id', $account->id)
                ->update([
                    'status'     => 'sold',
                    'buyer_id'   => $user->id,
                    'updated_at' => now(),
                ]);

            // Kiểm tra còn acc nào available không
            $remaining = DB::table('game_accounts')
                ->where('game_category_id', $account->game_category_id)
                ->where('status', 'available')
                ->count();

            if ($remaining <= 0) {
                DB::table('game_categories')
                    ->where('id', $account->game_category_id)
                    ->update([
                        'active' => 0,
                        'updated_at' => now(),
                    ]);
            }

            // Thêm lịch sử biến động số dư
            DB::table('money_transactions')->insert([
                'user_id' => $user->id,
                'type' => 'purchase',
                'amount' => $finalPrice,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => 'MUA TÀI KHOẢN #' . $account->id . ($discountAmount > 0 ? ' (GIẢM GIÁ: ' . number_format($discountAmount) . ' VND)' : ''),
                'reference_id' => $account->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mua Tài Khoản Thành Công!',
                'data' => [
                    'new_balance' => $balanceAfter
                ],
                'redirect_url' => route('profile.purchased-accounts')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi!: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Random Purchase - Mua ngẫu nhiên 1 tài khoản từ nhóm random
     * Cấu trúc mới: mỗi row trong game_accounts có accounts_data là JSON array
     */
    public function randomPurchase(Request $request, $categoryId)
    {
        try {
            DB::beginTransaction();

            $query = GameAccount::where('game_category_id', $categoryId)
                ->where('status', 'available')
                ->whereHas('category', function ($q) {
                    $q->where('type', 'random');
                })
                ->whereNotNull('accounts_data');

            if ($request->has('group_id') && $request->group_id) {
                $query->where('id', $request->group_id);
            } else {
                $query->inRandomOrder();
            }

            $randomGroup = $query->lockForUpdate()->first();

            if (!$randomGroup) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Không còn tài khoản nào trong nhóm này!'
                ]);
            }

            // Tự động cast thành mảng bởi model
            $accountsData = $randomGroup->accounts_data ?? [];
            $totalAvailable = count($accountsData);

            // Lấy số lượng khách muốn mua
            $quantity = max(1, min((int)$request->input('quantity', 1), 100));

            if ($totalAvailable < $quantity) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Chỉ còn {$totalAvailable} tài khoản trong nhóm này!"
                ]);
            }

            $user = Auth::user();
            $unitPrice = $randomGroup->price;
            $totalPrice = $unitPrice * $quantity;

            if ($user->balance < $totalPrice) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Số Dư Không Đủ!'
                ]);
            }

            // ⭐ LOGIC CÔNG BẰNG: Gom nhóm index theo seller_id
            $sellerBuckets = [];
            foreach ($accountsData as $index => $acc) {
                $sid = is_array($acc) ? ($acc['sid'] ?? $randomGroup->created_by) : $randomGroup->created_by;
                $sellerBuckets[$sid][] = $index;
            }

            $purchasedIds = [];
            $indicesToRemove = [];

            // Vòng lặp chọn accounts
            for ($i = 0; $i < $quantity; $i++) {
                $availableSellers = array_keys($sellerBuckets);
                if (empty($availableSellers)) break;

                // 1. Chọn Seller ngẫu nhiên từ những người còn hàng
                $randomSellerId = $availableSellers[array_rand($availableSellers)];
                
                // 2. Chọn ngẫu nhiên index tài khoản của Seller đó
                $bucketKey = array_rand($sellerBuckets[$randomSellerId]);
                $index = $sellerBuckets[$randomSellerId][$bucketKey];
                $selectedAccount = $accountsData[$index];

                // Trích xuất thông tin
                if (is_array($selectedAccount)) {
                    $accountName = $selectedAccount['u'] ?? '';
                    $password = $selectedAccount['p'] ?? '';
                    $sellerId = $selectedAccount['sid'] ?? $randomGroup->created_by;
                } else {
                    $parts = explode('|', (string)$selectedAccount, 2);
                    $accountName = $parts[0] ?? '';
                    $password = $parts[1] ?? '';
                    $sellerId = $randomGroup->created_by;
                }

                // Ghi nhận đơn hàng random
                $soldAccount = PurchasedRandomAccount::create([
                    'user_id'         => $user->id,
                    'seller_id'       => $sellerId,
                    'game_account_id' => $randomGroup->id,
                    'account_name'    => $accountName,
                    'password'        => $password,
                    'price'           => $unitPrice,
                ]);

                $purchasedIds[] = $soldAccount->id;
                $indicesToRemove[] = $index;

                // Xóa khỏi bucket để không chọn lại trong cùng 1 lần loop
                unset($sellerBuckets[$randomSellerId][$bucketKey]);
                if (empty($sellerBuckets[$randomSellerId])) {
                    unset($sellerBuckets[$randomSellerId]);
                }
            }

            // Update user balance
            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore - $totalPrice;

            DB::table('users')
                ->where('id', $user->id)
                ->update(['balance' => $balanceAfter]);

            // Cập nhật sold_count của nhóm random
            $randomGroup->increment('sold_count', count($indicesToRemove));

            // Xóa toàn bộ account đã chọn khỏi mảng accounts_data
            foreach ($indicesToRemove as $idx) {
                unset($accountsData[$idx]);
            }
            $accountsData = array_values($accountsData); // Re-index

            if (count($accountsData) === 0) {
                $randomGroup->update([
                    'accounts_data' => [],
                    'status' => 'sold'
                ]);
            } else {
                $randomGroup->update([
                    'accounts_data' => $accountsData
                ]);
            }

            // Check if category should be deactivated (không còn nhóm nào available)
            $remainingGroups = DB::table('game_accounts')
                ->where('game_category_id', $categoryId)
                ->where('status', 'available')
                ->whereNotNull('accounts_data')
                ->count();

            if ($remainingGroups <= 0) {
                DB::table('game_categories')
                    ->where('id', $categoryId)
                    ->update([
                        'active' => 0,
                        'updated_at' => now(),
                    ]);
            }

            // Add transaction history
            DB::table('money_transactions')->insert([
                'user_id' => $user->id,
                'type' => 'purchase',
                'amount' => $totalPrice, // Code cũ là -finalPrice nhưng cấu trúc bảng này thường lưu dương cho chi tiêu, mình giữ theo logic chung của hệ thống
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => "MUA {$quantity} TÀI KHOẢN RANDOM (#" . implode(', #', $purchasedIds) . ")",
                'reference_id' => $purchasedIds[0], // Lấy id đầu tiên làm reference
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mua Ngẫu Nhiên Thành Công!',
                'account' => [
                    'id' => $soldAccount->id,
                    'price' => $soldAccount->price,
                ],
                'data' => [
                    'new_balance' => $balanceAfter
                ],
                'redirect_url' => route('profile.purchased-accounts')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi!: ' . $e->getMessage()
            ]);
        }
    }
}
