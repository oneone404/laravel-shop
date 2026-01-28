<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\KeyPurchaseHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\MoneyTransaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\DiscountKey;
use Illuminate\Support\Str;

class GameKeyController extends Controller
{
    public function showForm()
    {
        $downloadLink = env('DOWNLOAD_LINK', null);

        $purchases = Auth::check()
            ? \App\Models\KeyPurchaseHistory::where('user_id', Auth::id())->orderByDesc('created_at')->paginate(5)
            : collect();

        // Thêm isSeller
        $isSeller = Auth::check() && Auth::user()->role === 'seller';

        return view('user.buy-key', [
            'purchases' => $purchases,
            'buykeyHistories' => $purchases, // để dùng trong view nếu bạn dùng $buykeyHistories
            'downloadLink' => $downloadLink,
            'isSeller' => $isSeller
        ]);
    }

    public function ajaxGetKeyDetails(Request $request)
    {
        $request->validate(['key_value' => 'required|string']);
        $service = app(\App\Services\HackVietService::class);
        return response()->json($service->getKeyDetails($request->key_value));
    }

    public function ajaxResetDevices(Request $request)
    {
        $request->validate(['hackviet_id' => 'required|integer']);
        $service = app(\App\Services\HackVietService::class);
        return response()->json($service->resetDevices($request->hackviet_id));
    }

    public function ajaxDeleteDevice(Request $request)
    {
        $request->validate([
            'hackviet_id' => 'required|integer',
            'device_id' => 'required|string'
        ]);
        
        $service = app(\App\Services\HackVietService::class);
        return response()->json($service->deleteDevice($request->hackviet_id, [$request->device_id]));
    }

    public function ajaxResetDevicesWithPayment(Request $request)
    {
        $request->validate([
            'hackviet_id' => 'required|integer',
            'purchase_id' => 'required|integer'
        ]);

        $user = auth()->user();
        $purchase = \App\Models\KeyPurchaseHistory::where('id', $request->purchase_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$purchase) {
            return response()->json(['success' => false, 'error' => 'Không tìm thấy key']);
        }

        // Check reset_count: lần đầu free, từ lần 2 trở đi mất 5k
        $resetFee = $purchase->reset_count >= 1 ? 5000 : 0;

        if ($resetFee > 0) {
            if ($user->balance < $resetFee) {
                return response()->json(['success' => false, 'error' => 'Số dư không đủ. Cần ' . number_format($resetFee) . ' VND']);
            }

            // Trừ tiền
            $user->decrement('balance', $resetFee);

            // Lưu lịch sử giao dịch
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'amount' => -$resetFee,
                'type' => 'reset_device',
                'description' => 'Reset thiết bị cho key: ' . $purchase->key_value,
                'status' => 'completed'
            ]);
        }

        // Gọi API reset
        $service = app(\App\Services\HackVietService::class);
        $result = $service->resetDevices($request->hackviet_id);

        if ($result['success']) {
            // Tăng reset_count
            $purchase->increment('reset_count');
            
            $message = $resetFee > 0 
                ? "Reset thành công! Đã trừ " . number_format($resetFee) . " VND"
                : "Reset thành công! (Miễn phí lần đầu)";
            
            return response()->json(['success' => true, 'message' => $message, 'fee' => $resetFee]);
        }

        // Nếu lỗi, hoàn lại tiền
        if ($resetFee > 0) {
            $user->increment('balance', $resetFee);
        }

        return response()->json($result);
    }

    public function ajaxGetDeviceInfo(Request $request)
    {
        $request->validate([
            'key_value' => 'required|string',
        ]);

        $key = $request->key_value;

        // Gọi thẳng API không cache
        $deviceInfo = $this->fetchDeviceInfoFromHMG($key);

        if (!$deviceInfo) {
            return response()->json(['success' => false, 'message' => 'KHÔNG LẤY ĐƯỢC THÔNG TIN']);
        }

        return response()->json([
            'success' => true,
            'device_info' => $deviceInfo
        ]);
    }

    private function fetchDeviceInfoFromHMG($key_value)
    {
        try {
            $client = new \GuzzleHttp\Client(['cookies' => true]);
            $hmgUsername = env('HMGTEAM_USERNAME');
            $hmgPassword = env('HMGTEAM_PASSWORD');

            $loginResponse = $client->post('https://hmgteam.net/auth/xacminh.php', [
                'form_params' => [
                    'taikhoan' => $hmgUsername,
                    'matkhau' => $hmgPassword,
                ],
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0',
                    'Referer' => 'https://hmgteam.net/auth/login.php'
                ]
            ]);

            if ($loginResponse->getStatusCode() !== 200) return null;

            $searchResponse = $client->get('https://hmgteam.net/admin/realtime/get_data_key.php', [
                'query' => [
                    'type' => 'get_data_key.php',
                    'page' => 1,
                    'keyword' => $key_value,
                ],
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Referer' => 'https://hmgteam.net/admin/quanlykey.php',
                    'User-Agent' => 'Mozilla/5.0'
                ]
            ]);

            if ($searchResponse->getStatusCode() !== 200) return null;

            $html = (string) $searchResponse->getBody();
            preg_match_all('/<tr.*?>.*?<\/tr>/s', $html, $rows);

            foreach ($rows[0] as $row) {
                if (stripos($row, $key_value) !== false) {
                    preg_match_all('/<td.*?>(.*?)<\/td>/s', $row, $cols);

                    if (isset($cols[1][6])) {
                        return strip_tags(trim($cols[1][6])); // Trả về "0/1"
                    }
                }
            }

        } catch (\Exception $e) {
            // Log::error($e->getMessage());
        }

        return null;
    }

    public function createKey(Request $request)
{
    // 🔒 SECURITY: Bắt buộc đăng nhập
    if (!Auth::check()) {
        return back()->with('error', 'VUI LÒNG ĐĂNG NHẬP ĐỂ MUA KEY');
    }

    // 🔒 SECURITY: Rate limiting - max 5 lần mua/phút
    $user = Auth::user();
    $rateLimitKey = 'buy_key_' . $user->id;
    $maxAttempts = 5;
    $decayMinutes = 1;

    if (Cache::has($rateLimitKey)) {
        $attempts = Cache::get($rateLimitKey);
        if ($attempts >= $maxAttempts) {
            return back()->with('error', 'BẠN ĐANG THAO TÁC QUÁ NHANH. VUI LÒNG ĐỢI 1 PHÚT');
        }
        Cache::put($rateLimitKey, $attempts + 1, now()->addMinutes($decayMinutes));
    } else {
        Cache::put($rateLimitKey, 1, now()->addMinutes($decayMinutes));
    }

    try {
        $request->validate([
            'chonGame' => 'required|string|max:100',
            'time_type' => 'required|in:D,W,2W,3W,M,F',
            'somay' => 'required|integer|min:1',  // API: không giới hạn, DB: check sau
            'discount_code' => 'nullable|string|max:50',
        ]);

        $discountCode = strtoupper(trim($request->input('discount_code', '')));

        // 🔹 Giá từng loại thời gian
        $priceList = [
            'D' => 15000,
            'W' => 70000,
            '2W' => 100000,
            '3W' => 130000,
            'M' => 170000,
            'F' => 1000000,
        ];

        // 🔹 Mapping time_type -> duration_value (ngày)
        $timeMapping = [
            'D'  => 1,
            'W'  => 7,
            '2W' => 14,
            '3W' => 21,
            'M'  => 30,
            'F'  => 3650,
        ];

        $time_type = $request->time_type;
        $somay = $request->somay;

        $pricePerKey = $priceList[$time_type];
        $totalPrice = $pricePerKey + (($somay - 1) * ($pricePerKey / 2));

        // Seller giảm 50%
        if ($user->role === 'seller') {
            $totalPrice *= 0.5;
        }

        // ==========================
        // 🔥 XỬ LÝ MÃ GIẢM GIÁ
        // ==========================
        $discountAmount = 0;
        $discountToUpdate = null;

        if (!empty($discountCode)) {
            $discount = \App\Models\DiscountKey::where('code', $discountCode)
                ->where('applicable_to', 'buy_key')
                ->first();

            if ($discount) {
                if ($discount->expires_at && now()->greaterThan($discount->expires_at)) {
                    $discount->used_count = $discount->max_discount ?? 1;
                    $discount->save();
                } else {
                    if (
                        ($discount->max_discount === null || $discount->max_discount == 0 || $discount->used_count < $discount->max_discount) &&
                        (is_null($discount->min_amount) || $totalPrice >= $discount->min_amount)
                    ) {
                        $discountAmount = $discount->discount_type === 'percentage'
                            ? ($totalPrice * $discount->discount_value / 100)
                            : $discount->discount_value;

                        $discountAmount = min($discountAmount, $totalPrice);
                        $totalPrice -= $discountAmount;
                        $discountToUpdate = $discount;
                    }
                }
            }
        }

        // Kiểm tra số dư
        if ($user->balance < $totalPrice) {
            return back()->with('error', 'SỐ DƯ KHÔNG ĐỦ ĐỂ GIAO DỊCH');
        }

        // Giới hạn số máy chỉ áp dụng cho DB mode
        $keyMode = strtolower(\App\Helpers\ConfigHelper::get('KEY_MODE', 'db'));
        if ($keyMode === 'db' && $somay > 10) {
            return back()->with('error', 'MUA NHIỀU THIẾT BỊ NHẮN ZALO AD: 0967.699.321');
        }

        $time_use_days = $timeMapping[$time_type] ?? null;
        if (!$time_use_days) {
            return back()->with('error', 'Loại thời gian không hợp lệ');
        }

        $timeUseLabel = $time_use_days . ' Ngày';

        // ================================================================
        // 🔥 CHỌN MODE: API hoặc DB
        // ================================================================
        $keyMode = strtolower(\App\Helpers\ConfigHelper::get('KEY_MODE', 'db')); // 'api' hoặc 'db'

        if ($keyMode === 'api') {
            // ===== API MODE: Gọi HackViet API tạo key =====
            return $this->createKeyViaApi($request, $user, $time_use_days, $somay, $totalPrice, $timeUseLabel, $discountToUpdate);
        } else {
            // ===== DB MODE: Lấy key từ database =====
            return $this->createKeyViaDatabase($request, $user, $time_use_days, $somay, $totalPrice, $timeUseLabel, $discountToUpdate);
        }

    } catch (\Throwable $e) {
        return back()->with('error', 'LỖI: ' . $e->getMessage());
    }
}

/**
 * Tạo key qua HackViet API
 */
private function createKeyViaApi($request, $user, $time_use_days, $somay, $totalPrice, $timeUseLabel, $discountToUpdate)
{
    try {
        $service = app(\App\Services\HackVietService::class);
        $result = $service->createVipKey(
            $user->username ?? $user->email ?? 'USER',
            $time_use_days,
            'day',
            $somay
        );

        if (!$result['success']) {
            \Log::error('[BuyKey API] Failed: ' . ($result['error'] ?? 'Unknown'));
            return back()->with('error', 'LỖI TẠO KEY: ' . ($result['error'] ?? 'Vui lòng thử lại'));
        }

        $keyValue = $result['key'];

        DB::beginTransaction();
        try {
            // Trừ tiền user
            $previousBalance = $user->balance;
            $user->balance -= $totalPrice;
            $user->save();

            // Lưu lịch sử
            $purchaseHistory = \App\Models\KeyPurchaseHistory::create([
                'user_id'      => $user->id,
                'game'         => $request->chonGame,
                'key_value'    => $keyValue,
                'device_count' => $somay,
                'time_use'     => $timeUseLabel,
                'price'        => $totalPrice,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            // Lưu giao dịch tiền
            MoneyTransaction::create([
                'user_id'       => $user->id,
                'type'          => 'purchase',
                'amount'        => $totalPrice,
                'balance_before'=> $previousBalance,
                'balance_after' => $user->balance,
                'description'   => 'MUA KEY VIP (API)',
                'reference_id'  => $purchaseHistory->id,
            ]);

            if ($discountToUpdate) {
                $discountToUpdate->increment('used_count');
            }

            DB::commit();
            return back()->with('success', 'MUA KEY THÀNH CÔNG!')
                        ->with('key_value', $keyValue)
                        ->with('time_use', $timeUseLabel);

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('[BuyKey API] DB Error: ' . $e->getMessage());
            return back()->with('error', 'LỖI LƯU GIAO DỊCH: ' . $e->getMessage());
        }

    } catch (\Throwable $e) {
        \Log::error('[BuyKey API] Exception: ' . $e->getMessage());
        return back()->with('error', 'LỖI HỆ THỐNG: ' . $e->getMessage());
    }
}

/**
 * Tạo key từ Database
 */
private function createKeyViaDatabase($request, $user, $time_use_days, $somay, $totalPrice, $timeUseLabel, $discountToUpdate)
{
    DB::beginTransaction();
    try {
        // Tìm key phù hợp với lockForUpdate để tránh race condition
        $keyVip = DB::table('key_vips')
            ->where(function($query) use ($request) {
                $query->where('game', $request->chonGame)
                    ->orWhere('game', 'all');
            })
            ->where('time_use', $time_use_days)
            ->where('device_limit', $somay)
            ->orderBy('id')
            ->lockForUpdate()  // 🔒 Tránh race condition
            ->first();

        if (!$keyVip) {
            DB::rollBack();
            return back()->with('error', 'NHẮN ADMIN ĐỂ CẬP NHẬT KEY MỚI CHO LOẠI NÀY NHÉ');
        }

        $keyValue = $keyVip->key_value;

        // Trừ tiền user
        $previousBalance = $user->balance;
        $user->balance -= $totalPrice;
        $user->save();

        // Lưu lịch sử
        $purchaseHistory = \App\Models\KeyPurchaseHistory::create([
            'user_id'      => $user->id,
            'game'         => $request->chonGame,
            'key_value'    => $keyValue,
            'device_count' => $somay,
            'time_use'     => $timeUseLabel,
            'price'        => $totalPrice,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // Lưu giao dịch tiền
        MoneyTransaction::create([
            'user_id'       => $user->id,
            'type'          => 'purchase',
            'amount'        => $totalPrice,
            'balance_before'=> $previousBalance,
            'balance_after' => $user->balance,
            'description'   => 'MUA KEY VIP (LOCAL DB)',
            'reference_id'  => $purchaseHistory->id,
        ]);

        // Xóa key đã bán
        DB::table('key_vips')->where('id', $keyVip->id)->delete();

        if ($discountToUpdate) {
            $discountToUpdate->increment('used_count');
        }

        DB::commit();
        return back()->with('success', 'MUA KEY THÀNH CÔNG!')
                    ->with('key_value', $keyValue)
                    ->with('time_use', $timeUseLabel);

    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->with('error', 'LỖI LOCAL DB: ' . $e->getMessage());
    }
}

    public function checkDiscount(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Đăng Nhập Để Sử Dụng']);
        }

        $request->validate([
            'code' => 'required|string',
            'total' => 'required|numeric'
        ]);

        $user = Auth::user();
        $code = strtoupper(trim($request->code));
        $total = $request->total;

        $discount = DiscountKey::where('code', $code)
            ->where('applicable_to', 'buy_key')
            ->first();

        if (!$discount) {
            return response()->json(['success' => false, 'message' => 'Mã Giảm Giá Không Hợp Lệ']);
        }

        // ❗ Kiểm tra hạn sử dụng
        if ($discount->expires_at && now()->greaterThan($discount->expires_at)) {
            // Nếu hết hạn thì khóa mã luôn
            $discount->used_count = $discount->max_discount ?? 1;
            $discount->save();

            return response()->json([
                'success' => false,
                'message' => 'Mã Giảm Giá Đã Quá Hạn'
            ]);
        }

        // ❗ Kiểm tra số lần dùng
        if ($discount->max_discount > 0 && $discount->used_count >= $discount->max_discount) {
            return response()->json([
                'success' => false,
                'message' => 'Giới Hạn Số Lần Sử Dụng'
            ]);
        }

        // ❗ Kiểm tra min_amount nếu có
        if (!is_null($discount->min_amount) && $total < $discount->min_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Tổng Tiền Phải Lớn Hơn ' . number_format($discount->min_amount) . ' VND'
            ]);
        }

        // ✅ Tính giảm giá
        $discountAmount = $discount->discount_type === 'percentage'
            ? ($total * $discount->discount_value / 100)
            : $discount->discount_value;

        return response()->json([
            'success' => true,
            'discount' => (float) min($discountAmount, $total),
            'message' => 'Bạn Được Giảm ' . number_format(min($discountAmount, $total)) . ' VND'
        ]);
    }

    public function resetKey(Request $request)
    {
        $request->validate([
            'key_value' => 'required|string'
        ]);
        $keyword = $request->key_value;
        // Kiểm tra định dạng key
        $keyword = $request->key_value;

        // Kiểm tra key có tồn tại trong KeyPurchaseHistory không
        $hasKey = KeyPurchaseHistory::where('key_value', $keyword)->exists();

        if (!$hasKey) {
            return back()->with('error', 'KEY KHÔNG TỒN TẠI TRONG HỆ THỐNG');
        }

        try {
            $client = new \GuzzleHttp\Client(['cookies' => true]);
            $hmgUsername = env('HMGTEAM_USERNAME');
            $hmgPassword = env('HMGTEAM_PASSWORD');

            // Đăng nhập
            $loginResponse = $client->post('https://hmgteam.net/auth/xacminh.php', [
                'form_params' => [
                    'taikhoan' => $hmgUsername,
                    'matkhau' => $hmgPassword,
                ],
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0',
                    'Referer' => 'https://hmgteam.net/auth/login.php'
                ]
            ]);

            if ($loginResponse->getStatusCode() != 200) {
                return back()->with('error', 'LỖI ĐĂNG NHẬP. BÁO NGAY CHO ADMIN');
            }

            // Tìm ID key
            $searchResponse = $client->get('https://hmgteam.net/admin/realtime/get_data_key.php', [
                'query' => [
                    'type' => 'get_data_key.php',
                    'page' => 1,
                    'keyword' => $keyword,
                ],
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Referer' => 'https://hmgteam.net/admin/quanlykey.php',
                    'User-Agent' => 'Mozilla/5.0'
                ]
            ]);

            if ($searchResponse->getStatusCode() != 200) {
                return back()->with('error', 'KHÔNG TÌM THẤY KEY');
            }

            $html = (string) $searchResponse->getBody();
            preg_match_all('/<tr.*?>.*?<\/tr>/s', $html, $rows);

            $keyId = null;
            $deviceInfo = null;

            foreach ($rows[0] as $row) {
                if (stripos($row, $keyword) !== false) {
                    preg_match_all('/<td.*?>(.*?)<\/td>/s', $row, $cols);

                    if (isset($cols[1][1])) {
                        $keyId = strip_tags(trim($cols[1][1]));
                    }

                    if (isset($cols[1][6])) {
                        $deviceInfo = strip_tags(trim($cols[1][6]));
                    }

                    break;
                }
            }

            if (!$keyId) {
                return back()->with('error', 'KEY KHÔNG TỒN TẠI');
            }

            $user = Auth::user();
            $baseCost = 5000;

            // Lấy lịch sử key (nếu có)
            $purchase = KeyPurchaseHistory::where('key_value', $keyword)->first();

            $resetCount = $purchase?->reset_count ?? 0;
            $cost = ($resetCount >= 1) ? $baseCost : 0;

            if ($cost > 0 && $user->balance < $cost) {
                return back()->with('error', 'SỐ DƯ KHÔNG ĐỦ ĐỂ GIAO DỊCH');
            }

            DB::beginTransaction();

            try {
                // Reset key trên HMG
                $resetResponse = $client->post('https://hmgteam.net/admin/ajax/reset_key.php', [
                    'form_params' => ['id' => $keyId],
                    'headers' => [
                        'X-Requested-With' => 'XMLHttpRequest',
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Referer' => 'https://hmgteam.net/admin/quanlykey.php',
                        'User-Agent' => 'Mozilla/5.0'
                    ]
                ]);

                $result = json_decode($resetResponse->getBody(), true);

                if ($resetResponse->getStatusCode() == 200 && $result) {
                    $previousBalance = $user->balance;

                    if ($cost > 0) {
                        $user->balance -= $cost;
                        $user->save();

                        MoneyTransaction::create([
                            'user_id' => $user->id,
                            'type' => 'none',
                            'amount' => $cost,
                            'balance_before' => $previousBalance,
                            'balance_after' => $user->balance,
                            'description' => "RESET KEY: {$keyword} ({$deviceInfo})",
                        ]);
                    }

                    // Cập nhật reset_count
                    if ($purchase) {
                        $purchase->increment('reset_count');
                    } else {
                        KeyPurchaseHistory::create([
                            'user_id' => $user->id,
                            'game' => 'N/A', // hoặc để null nếu không có
                            'key_value' => $keyword,
                            'time_use' => 'N/A',
                            'price' => 0,
                            'reset_count' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    DB::commit();

                    return back()->with('success', 'LÀM MỚI THÀNH CÔNG! ' . $deviceInfo . ' THIẾT BỊ' . ($cost === 0 ? ' (0 VND)' : ''));
                } else {
                    DB::rollBack();
                    return back()->with('error', 'RESET KEY THẤT BẠI');
                }

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'LỖI GIAO DỊCH: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            return back()->with('error', 'LỖI: ' . $e->getMessage());
        }
    }
}
