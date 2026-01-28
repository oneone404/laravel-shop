<?php

namespace App\Http\Controllers;

use App\Models\DirectOrder;
use App\Models\GameAccount;
use App\Models\GameCategory;
use App\Models\MoneyTransaction;
use App\Models\PurchasedRandomAccount;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DirectPaymentController extends Controller
{
    /**
     * Tạo đơn hàng thanh toán trực tiếp cho tài khoản thường (account/play/clone)
     */
    public function createAccountOrder(Request $request, $accountId)
    {
        $request->validate([
            'account_id' => 'sometimes|integer',
        ]);

        try {
            // ⭐ 1. Chống Spam: Tìm đơn hàng cũ còn hạn cho CHÍNH NICK NÀY trước
            $existingOrder = DirectOrder::where('status', DirectOrder::STATUS_PENDING)
                ->where('expires_at', '>', now())
                ->where('item_id', $accountId)
                ->where('order_type', DirectOrder::TYPE_ACCOUNT)
                ->where(function($q) {
                    if (Auth::check()) {
                        $q->where('user_id', Auth::id());
                    } else {
                        $q->where('guest_session', session()->getId());
                    }
                })
                ->first();

            if ($existingOrder) {
                return response()->json([
                    'success' => true,
                    'order_code' => $existingOrder->order_code,
                    'redirect_url' => route('direct-payment.show', $existingOrder->order_code),
                ]);
            }

            // ⭐ 2. Giới hạn tạo đơn MỚI (Khác biệt): Tối đa 10 đơn hàng/10p (User ID, Session hoặc IP)
            $throttleCount = DirectOrder::where('created_at', '>', now()->subMinutes(10))
                ->where(function($q) use ($request) {
                    $q->where('guest_ip', $request->ip())
                      ->orWhere('guest_session', session()->getId());
                    if (Auth::check()) {
                        $q->orWhere('user_id', Auth::id());
                    }
                })
                ->count();

            if ($throttleCount >= 10) {
                return response()->json([
                    'success' => false,
                    'message' => "Bạn Đang Có $throttleCount Đơn Hàng Chưa Thanh Toán"
                ], 429);
            }

            $account = GameAccount::where('id', $accountId)
                ->where('status', 'available')
                ->firstOrFail();

            $category = $account->category;

            // Kiểm tra loại category (play hoặc clone)
            if (!in_array($category->type, ['play', 'clone'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Loại tài khoản không hỗ trợ thanh toán trực tiếp!'
                ], 400);
            }

            $orderCode = DirectOrder::generateOrderCode();
            $paymentContent = DirectOrder::generatePaymentContent($orderCode);

            $order = DirectOrder::create([
                'order_code' => $orderCode,
                'user_id' => Auth::id(), // null nếu guest
                'guest_session' => session()->getId(),
                'guest_ip' => $request->ip(),
                'order_type' => DirectOrder::TYPE_ACCOUNT,
                'category_id' => $category->id,
                'item_id' => $account->id,
                'group_id' => null,
                'quantity' => 1,
                'amount' => $account->price,
                'payment_content' => $paymentContent,
                'status' => DirectOrder::STATUS_PENDING,
                'expires_at' => now()->addMinutes(10),
            ]);

            return response()->json([
                'success' => true,
                'order_code' => $orderCode,
                'redirect_url' => route('direct-payment.show', $orderCode),
            ]);

        } catch (\Exception $e) {
            Log::error('[DirectPayment] Create account order error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo đơn hàng thanh toán trực tiếp cho random account
     */
    public function createRandomOrder(Request $request, $categoryId)
    {
        $request->validate([
            'group_id' => 'sometimes|integer',
            'quantity' => 'sometimes|integer|min:1|max:50',
        ]);

        try {
            $groupId = $request->input('group_id');
            $quantity = $request->input('quantity', 1);

            // Tìm nhóm random
            $query = GameAccount::where('game_category_id', $categoryId)
                ->where('status', 'available')
                ->whereNotNull('accounts_data');

            if ($groupId) {
                $query->where('id', $groupId);
            }

            $randomGroup = $query->first();

            if (!$randomGroup) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không còn tài khoản nào trong nhóm này!'
                ], 400);
            }

            // ⭐ 1. Chống Spam: Tìm đơn hàng cũ còn hạn cho CÙNG NHÓM & SỐ LƯỢNG trước
            $existingOrder = DirectOrder::where('status', DirectOrder::STATUS_PENDING)
                ->where('expires_at', '>', now())
                ->where('group_id', $randomGroup->id)
                ->where('quantity', $quantity)
                ->where('order_type', DirectOrder::TYPE_RANDOM)
                ->where(function($q) {
                    if (Auth::check()) {
                        $q->where('user_id', Auth::id());
                    } else {
                        $q->where('guest_session', session()->getId());
                    }
                })
                ->first();

            if ($existingOrder) {
                return response()->json([
                    'success' => true,
                    'order_code' => $existingOrder->order_code,
                    'redirect_url' => route('direct-payment.show', $existingOrder->order_code),
                ]);
            }

            // ⭐ 2. Giới hạn tạo đơn MỚI: Tối đa 10 đơn hàng/10p (User ID, Session hoặc IP)
            $throttleCount = DirectOrder::where('created_at', '>', now()->subMinutes(10))
                ->where(function($q) use ($request) {
                    $q->where('guest_ip', $request->ip())
                      ->orWhere('guest_session', session()->getId());
                    if (Auth::check()) {
                        $q->orWhere('user_id', Auth::id());
                    }
                })
                ->count();

            if ($throttleCount >= 10) {
                return response()->json([
                    'success' => false,
                    'message' => "Bạn Đang Có $throttleCount Đơn Hàng Chưa Thanh Toán"
                ], 429);
            }

            $category = GameCategory::where('id', $categoryId)
                ->where('type', 'random')
                ->where('active', true)
                ->firstOrFail();

            // Kiểm tra số lượng còn đủ không
            $availableCount = count($randomGroup->accounts_data ?? []);
            if ($availableCount < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Chỉ còn {$availableCount} tài khoản! Vui lòng giảm số lượng."
                ], 400);
            }

            $totalAmount = $randomGroup->price * $quantity;

            $orderCode = DirectOrder::generateOrderCode();
            $paymentContent = DirectOrder::generatePaymentContent($orderCode);

            $order = DirectOrder::create([
                'order_code' => $orderCode,
                'user_id' => Auth::id(),
                'guest_session' => session()->getId(),
                'guest_ip' => $request->ip(),
                'order_type' => DirectOrder::TYPE_RANDOM,
                'category_id' => $category->id,
                'item_id' => null,
                'group_id' => $randomGroup->id,
                'quantity' => $quantity,
                'amount' => $totalAmount,
                'payment_content' => $paymentContent,
                'status' => DirectOrder::STATUS_PENDING,
                'expires_at' => now()->addMinutes(10),
            ]);

            return response()->json([
                'success' => true,
                'order_code' => $orderCode,
                'redirect_url' => route('direct-payment.show', $orderCode),
            ]);

        } catch (\Exception $e) {
            Log::error('[DirectPayment] Create random order error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị trang thanh toán với QR code
     */
    public function show(string $orderCode)
    {
        $order = DirectOrder::where('order_code', $orderCode)->firstOrFail();

        // Nếu đã hoàn thành, redirect đến trang kết quả
        if ($order->status === DirectOrder::STATUS_COMPLETED) {
            return redirect()->route('direct-payment.result', $orderCode);
        }

        // Nếu đã hết hạn, cập nhật status
        if ($order->isPending() && $order->isExpired()) {
            $order->markAsExpired();
        }

        // Kiểm tra xem acc còn không
        $order->checkAvailability();

        // Lấy thông tin ngân hàng
        $bankAccount = BankAccount::where('is_active', true)
            ->where('auto_confirm', true)
            ->first();

        if (!$bankAccount) {
            return view('user.direct-payment-error', [
                'error' => 'Hệ Thống Đang Bảo Trì!'
            ]);
        }

        // Tạo QR URL từ SePay
        $qrUrl = "https://qr.sepay.vn/img?bank={$bankAccount->bank_name}&acc={$bankAccount->account_number}&template=&amount={$order->amount}&des={$order->payment_content}";

        return view('user.direct-payment', [
            'order' => $order,
            'bankAccount' => $bankAccount,
            'qrUrl' => $qrUrl,
            'remainingSeconds' => $order->getRemainingSeconds(),
        ]);
    }

    /**
     * API kiểm tra trạng thái đơn hàng (polling)
     */
    public function checkStatus(string $orderCode)
    {
        $order = DirectOrder::where('order_code', $orderCode)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại!'
            ], 404);
        }

        // ⭐ Bảo mật: Kiểm tra quyền xem trạng thái đơn hàng (chỉ chủ đơn được check)
        $canAccess = false;
        if (Auth::check() && Auth::id() === $order->user_id) {
            $canAccess = true;
        } elseif (!$order->user_id && $order->guest_session === session()->getId()) {
            $canAccess = true;
        } elseif (!$order->user_id && $order->guest_ip === request()->ip()) {
            $canAccess = true;
        }

        if (!$canAccess) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xem trạng thái đơn hàng này!'
            ], 403);
        }

        // Check expired
        if ($order->isPending() && $order->isExpired()) {
            $order->markAsExpired();
        }

        // Kiểm tra xem acc còn không
        $order->checkAvailability();

        return response()->json([
            'success' => true,
            'status' => $order->status,
            'remaining_seconds' => $order->getRemainingSeconds(),
            'redirect_url' => $order->status === DirectOrder::STATUS_COMPLETED 
                ? route('direct-payment.result', $orderCode) 
                : null,
        ]);
    }

    /**
     * Hiển thị trang kết quả
     */
    public function result(string $orderCode)
    {
        $order = DirectOrder::where('order_code', $orderCode)->firstOrFail();

        // Nếu chưa hoàn thành, quay lại trang thanh toán
        if ($order->status !== DirectOrder::STATUS_COMPLETED) {
            return redirect()->route('direct-payment.show', $orderCode);
        }

        // Kiểm tra quyền xem (chỉ owner hoặc guest session)
        $canView = false;
        if (Auth::check() && Auth::id() === $order->user_id) {
            $canView = true;
        } elseif (!$order->user_id && $order->guest_session === session()->getId()) {
            $canView = true;
        } elseif (!$order->user_id && $order->guest_ip === request()->ip()) {
            $canView = true;
        }

        if (!$canView) {
            abort(403, 'Bạn không có quyền xem đơn hàng này!');
        }

        return view('user.direct-order-result', [
            'order' => $order,
            'isGuest' => is_null($order->user_id),
        ]);
    }

    /**
     * Tải file TXT cho guest
     */
    public function downloadTxt(string $orderCode)
    {
        $order = DirectOrder::where('order_code', $orderCode)
            ->where('status', DirectOrder::STATUS_COMPLETED)
            ->firstOrFail();

        // Kiểm tra quyền
        if ($order->user_id && (!Auth::check() || Auth::id() !== $order->user_id)) {
            abort(403);
        }

        if (!$order->user_id) {
            // Guest - check session or IP
            if ($order->guest_session !== session()->getId() && $order->guest_ip !== request()->ip()) {
                abort(403);
            }
        }

        $accounts = $order->account_data ?? [];
        $content = "=== ĐƠN HÀNG: {$order->order_code} ===\n";
        $content .= "Ngày Mua: " . $order->completed_at->format('d/m/Y H:i:s') . "\n";
        $content .= "Số Tiền: " . number_format($order->amount) . " VND\n";
        $content .= "Số Lượng: " . count($accounts) . " Tài Khoản\n";
        $content .= "\n=== THÔNG TIN TÀI KHOẢN ===\n\n";

        foreach ($accounts as $index => $acc) {
            $content .= "--- Tài Khoản " . ($index + 1) . " ---\n";
            $content .= "Username: " . ($acc['account_name'] ?? $acc['u'] ?? 'N/A') . "\n";
            $content .= "Password: " . ($acc['password'] ?? $acc['p'] ?? 'N/A') . "\n";
            $content .= "\n";
        }
        
        $filename = "donhang_{$order->order_code}.txt";

        return response($content)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Xử lý đơn hàng sau khi nhận được tiền
     * Được gọi từ ProcessDirectPayments command
     */
    public static function processOrder(DirectOrder $order): bool
    {
        if ($order->status !== DirectOrder::STATUS_PAID) {
            return false;
        }

        try {
            DB::beginTransaction();

            $accountsData = [];

            if ($order->order_type === DirectOrder::TYPE_ACCOUNT) {
                // Mua tài khoản thường
                $account = GameAccount::where('id', $order->item_id)
                    ->where('status', 'available')
                    ->lockForUpdate()
                    ->first();

                if (!$account) {
                    Log::error("[DirectPayment] Account #{$order->item_id} not available for order {$order->order_code}");
                    DB::rollBack();
                    return false;
                }

                // Mark as sold
                $account->update([
                    'status' => 'sold',
                    'buyer_id' => $order->user_id,
                ]);

                $accountsData[] = [
                    'account_name' => $account->account_name,
                    'password' => $account->password,
                ];

                // Nếu đã đăng nhập, ghi lịch sử giao dịch
                if ($order->user_id) {
                    MoneyTransaction::create([
                        'user_id' => $order->user_id,
                        'type' => 'purchase',
                        'amount' => $order->amount,
                        'balance_before' => 0,
                        'balance_after' => 0,
                        'description' => "MUA TÀI KHOẢN #{$account->id} (THANH TOÁN TRỰC TIẾP - {$order->order_code})",
                        'reference_id' => $account->id,
                    ]);
                }

            } elseif ($order->order_type === DirectOrder::TYPE_RANDOM) {
                // Mua random account
                $randomGroup = GameAccount::where('id', $order->group_id)
                    ->where('status', 'available')
                    ->lockForUpdate()
                    ->first();

                if (!$randomGroup) {
                    Log::error("[DirectPayment] Random group #{$order->group_id} not available for order {$order->order_code}");
                    DB::rollBack();
                    return false;
                }

                $availableAccounts = $randomGroup->accounts_data ?? [];
                $quantity = $order->quantity;

                if (count($availableAccounts) < $quantity) {
                    Log::error("[DirectPayment] Not enough accounts in group. Need: {$quantity}, Have: " . count($availableAccounts));
                    DB::rollBack();
                    return false;
                }

                // ⭐ LOGIC CÔNG BẰNG: Chọn Người bán trước, sau đó mới chọn Tài khoản của người đó
                $selectedIndexes = [];
                $tempAccounts = $availableAccounts;

                // Gom nhóm index theo seller_id
                $sellerBuckets = [];
                foreach ($tempAccounts as $index => $acc) {
                    $sid = is_array($acc) ? ($acc['sid'] ?? $randomGroup->created_by) : $randomGroup->created_by;
                    $sellerBuckets[$sid][] = $index;
                }

                $pricePerAccount = $randomGroup->price;

                // Chọn lặp lại theo số lượng khách mua
                for ($i = 0; $i < $quantity; $i++) {
                    $availableSellers = array_keys($sellerBuckets);
                    if (empty($availableSellers)) break;

                    // 1. Chọn Seller ngẫu nhiên từ những người còn hàng
                    $randomSellerId = $availableSellers[array_rand($availableSellers)];
                    
                    // 2. Chọn ngẫu nhiên index tài khoản của Seller đó
                    $bucketKey = array_rand($sellerBuckets[$randomSellerId]);
                    $index = $sellerBuckets[$randomSellerId][$bucketKey];

                    $selectedAccount = $tempAccounts[$index];

                    // Xử lý thông tin tài khoản
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

                    $accountsData[] = [
                        'account_name' => $accountName,
                        'password' => $password,
                    ];

                    // Nếu đã đăng nhập, lưu vào PurchasedRandomAccount
                    if ($order->user_id) {
                        PurchasedRandomAccount::create([
                            'user_id' => $order->user_id,
                            'seller_id' => $sellerId,
                            'game_account_id' => $randomGroup->id,
                            'account_name' => $accountName,
                            'password' => $password,
                            'price' => $pricePerAccount,
                        ]);
                    }

                    // Đánh dấu đã chọn bằng cách xóa khỏi bucket và khỏi danh sách gốc
                    unset($sellerBuckets[$randomSellerId][$bucketKey]);
                    if (empty($sellerBuckets[$randomSellerId])) {
                        unset($sellerBuckets[$randomSellerId]);
                    }
                    unset($availableAccounts[$index]);
                }

                // Re-index và cập nhật
                $availableAccounts = array_values($availableAccounts);
                $randomGroup->increment('sold_count', $quantity);

                if (count($availableAccounts) === 0) {
                    $randomGroup->update([
                        'accounts_data' => [],
                        'status' => 'sold'
                    ]);
                } else {
                    $randomGroup->update([
                        'accounts_data' => $availableAccounts
                    ]);
                }

                // Nếu đã đăng nhập, ghi lịch sử
                if ($order->user_id) {
                    MoneyTransaction::create([
                        'user_id' => $order->user_id,
                        'type' => 'purchase',
                        'amount' => $order->amount,
                        'balance_before' => 0,
                        'balance_after' => 0,
                        'description' => "MUA {$quantity} TÀI KHOẢN RANDOM (THANH TOÁN TRỰC TIẾP - {$order->order_code})",
                        'reference_id' => $randomGroup->id,
                    ]);
                }
            }

            // Mark order as completed
            $order->markAsCompleted($accountsData);

            DB::commit();

            Log::info("[DirectPayment] Order {$order->order_code} completed successfully with " . count($accountsData) . " accounts");

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("[DirectPayment] Process order error: " . $e->getMessage());
            return false;
        }
    }
}
