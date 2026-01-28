<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Models\MoneyTransaction;
use App\Models\ServiceHistory;
use App\Models\ServicePackage;
use App\Models\GameService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\PayZingHistory;

class ServiceOrderController extends Controller
{
    public function processOrder(Request $request)
    {
        $this->validateBasic($request);

        $service = GameService::findOrFail($request->input('service_id'));
        $this->validateByType($request, $service);

        $package = ServicePackage::findOrFail($request->input('package_id'));
        $user    = User::findOrFail(auth()->id());

        $finalPriceData = $this->calculateFinalPrice($request, $package);

        // Giảm 5k cho seller với gói 22/30
        if ($service->type === 'pay-game' && $user->role === 'seller' && in_array($package->id, [22, 30], true)) {
            $finalPriceData['final_price']      = max(0, $finalPriceData['final_price'] - 5000);
            $finalPriceData['discount_amount'] += 5000;
        }

        if ($user->balance < $finalPriceData['final_price']) {
            return back()->with('error', 'SỐ DƯ KHÔNG ĐỦ ĐỂ THANH TOÁN')->withInput();
        }

        if ($this->isPlusPackage($request)) {
            $error = $this->checkCanBuyPlus($request, $service);
            if ($error) {
                return back()->with('error', $error)->withInput();
            }
        }

        // --- CHỈ PRE-CREATE PayZingHistory (KHÔNG MUA THẺ Ở ĐÂY) ---
        $needsCard = ($service->type === 'pay-game' && in_array($package->id, [22, 30], true));
        $pz = null;
        if ($needsCard) {
            $pz = PayZingHistory::create([
                'user_id'      => $user->id,
                'service_id'   => $service->id,
                'package_id'   => $package->id,
                'role_id'      => $request->input('id_account'),
                'server'       => $request->input('server'),
                'provider'     => 'cardws',
                'request_id'   => now()->format('YmdHis') . $user->id . Str::random(5),
                'service_code' => config('card.service_codes.ZING', 'ZING'),
                'value'        => 20000,
                'qty'          => 1,
                'status'       => 'pending',
                'description'  => 'Đã tạo yêu cầu. Job sẽ mua thẻ & nạp.',
                // meta có thể set sẵn flag buy chưa attempt (job sẽ đánh dấu)
                'meta'         => ['init' => true],
            ]);
        }

        try {
            // Transaction: tạo order, trừ tiền, gắn service_history_id vào PZ
            $result = DB::transaction(function () use ($request, $user, $service, $package, $finalPriceData, $pz) {
                return $this->createOrderAndDeductBalance($request, $user, $service, $package, $finalPriceData, $pz);
            });

            // SAU COMMIT: nếu có PZ thì dispatch đúng 1 job lo toàn bộ (mua → chờ/redl khi fail → nạp → notify)
            if ($pz) {
                DB::afterCommit(function () use ($pz) {
                    \App\Jobs\TopupCardJob::dispatch($pz->id);
                });
            }

            // Flash UI: chỉ nói đã đặt, còn lại job xử lý
            session()->flash('success', $pz
                ? 'ĐẶT DỊCH VỤ THÀNH CÔNG'
                : 'ĐẶT DỊCH VỤ THÀNH CÔNG');

            return back();

        } catch (\Throwable $e) {
            if (!empty($pz)) {
                $pz->forceFill([
                    'status'      => 'error',
                    'description' => 'Lỗi quy trình: ' . $e->getMessage(),
                ])->save();
            }
            return back()->with('error', 'Có lỗi xảy ra: '.$e->getMessage())->withInput();
        }
    }

    private function validateBasic(Request $request)
    {
        Validator::make($request->all(), [
            'service_id' => 'required|exists:game_services,id',
            'package_id' => 'required|exists:service_packages,id',
            'server'     => 'required|string',
            'giftcode'   => 'nullable|string',
            'note'       => 'nullable|string|max:500',
        ])->validate();
    }

    private function validateByType(Request $request, $service)
    {
        if ($service->type === 'leveling') {
            Validator::make($request->all(), [
                'game_account'  => 'required|string|max:50',
                'game_password' => 'required|string|max:100',
                'login_game'    => 'required|string',
            ])->validate();
        } elseif (in_array($service->type, ['pay-game', 'buff-game'])) {
            Validator::make($request->all(), [
                'id_account' => 'required|string|max:50',
            ])->validate();
        }
    }

    private function calculateFinalPrice(Request $request, $package): array
    {
        $finalPrice = $package->price;
        $discountAmount = 0;
        $discountCode = null;

        if ($request->filled('giftcode')) {
            $discountCode = DiscountCode::where('code', $request->giftcode)->where('is_active', 1)->first();
            if ($discountCode) {
                if ($discountCode->discount_type === 'percentage') {
                    $discountAmount = ($package->price * $discountCode->discount_value) / 100;
                    if ($discountCode->max_discount_value && $discountAmount > $discountCode->max_discount_value) {
                        $discountAmount = $discountCode->max_discount_value;
                    }
                } else {
                    $discountAmount = $discountCode->discount_value;
                }
                $finalPrice = max($package->price - $discountAmount, 0);
            }
        }
        return [
            'final_price'    => max(0, $finalPrice),
            'discount_amount'=> $discountAmount,
            'original_price' => $package->price,
            'discount_code'  => $discountCode
        ];
    }

    private function isPlusPackage(Request $request): bool
    {
        return $request->input('service_id') == 10 && $request->input('package_id') == 30;
    }

    private function checkCanBuyPlus(Request $request, $service): string|null
    {
        $account = $service->type === 'leveling'
            ? $request->input('game_account')
            : $request->input('id_account');

        // Ghi log check
        file_put_contents(
            __DIR__ . '/order.log',
            "[" . now() . "] CHECK PLUS: {$account}\n",
            FILE_APPEND
        );

        try {
            $checkResp = Http::post(url('/api/check-plus'), [
                'role_id' => $account,
            ])->json();

            file_put_contents(
                __DIR__ . '/order.log',
                "[" . now() . "] CHECK PLUS RESPONSE: " . json_encode($checkResp, JSON_UNESCAPED_UNICODE) . "\n\n",
                FILE_APPEND
            );

            if (!($checkResp['has_plus'] ?? false)) {
                return 'MỖI TÀI KHOẢN CHỈ ĐƯỢC MUA (GÓI PLUS 7 NGÀY) 1 LẦN MỖI KHI GAME CẬP NHẬT';
            }

            return null;
        } catch (\Throwable $e) {
            file_put_contents(
                __DIR__ . '/order.log',
                "[" . now() . "] CHECK PLUS ERROR: {$e->getMessage()}\n\n",
                FILE_APPEND
            );

            return 'Không thể kiểm tra gói Plus, vui lòng thử lại';
        }
    }

    /**
     * CHỈ tạo order, trừ tiền, ghi transaction, cập nhật mã giảm
     * Nếu có $pz: chỉ gắn service_history_id + mô tả, KHÔNG gọi NCC ở đây.
     */
    private function createOrderAndDeductBalance(Request $request, $user, $service, $package, $finalPriceData, ?PayZingHistory $pz = null)
    {
        $data = [
            'user_id'            => $user->id,
            'game_service_id'    => $service->id,
            'service_package_id' => $package->id,
            'server'             => $request->input('server'),
            'note'               => $request->input('note'),
            'price'              => $finalPriceData['final_price'],
            'status'             => 'pending',
            'update_version'     => env('GAME_UPDATE_VERSION', '1.0.0'),
        ];

        if ($service->type === 'leveling') {
            $data['game_account']  = $request->input('game_account');
            $data['game_password'] = $request->input('game_password');
            $data['admin_note']    = $request->input('login_game');
        } elseif ($service->type === 'pay-game') {
            $data['game_account'] = $request->input('id_account');
        }

        // 1) Tạo order
        $serviceHistory = ServiceHistory::create($data);

        // 1.1) Gắn service_history_id vào PayZingHistory (chỉ meta, không gọi NCC)
        if ($pz) {
            $pz->update([
                'meta'        => array_merge((array)$pz->meta, ['service_history_id' => $serviceHistory->id]),
                'status'      => 'pending',
                'description' => 'Order đã tạo — Job sẽ mua thẻ & nạp.',
            ]);
        }

        // 2) Trừ tiền & ghi giao dịch
        $balanceBefore  = $user->balance;
        $finalPrice     = $finalPriceData['final_price'];
        $discountAmount = $finalPriceData['discount_amount'];
        $balanceAfter   = $balanceBefore - $finalPrice;

        $user->update(['balance' => $balanceAfter]);

        MoneyTransaction::create([
            'user_id'        => $user->id,
            'type'           => 'purchase',
            'amount'         => $finalPrice,
            'balance_before' => $balanceBefore,
            'balance_after'  => $balanceAfter,
            'description'    => 'THANH TOÁN ' . $service->name . ' #' . $serviceHistory->id .
                ($discountAmount > 0 ? ' (GIẢM: ' . number_format($discountAmount) . ' VND)' : ''),
            'reference_id'   => $serviceHistory->id,
        ]);

        // 3) Ghi nhận mã giảm giá (nếu có)
        if ($finalPriceData['discount_code']) {
            $discountCode = $finalPriceData['discount_code'];
            $discountCode->increment('usage_count');

            DB::table('discount_code_usages')->insert([
                'discount_code_id' => $discountCode->id,
                'user_id'          => $user->id,
                'context'          => 'service',
                'item_id'          => $serviceHistory->id,
                'original_price'   => $package->price,
                'discounted_price' => $finalPrice,
                'discount_amount'  => $discountAmount,
                'used_at'          => now(),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        // Không gọi NCC tại controller nữa.
        return ['ui_state' => $pz ? 'pending' : 'completed'];
    }

    public function getRoleName(Request $request)
    {
        $request->validate([
            'id_account' => 'required|string|max:50'
        ]);

        try {
            $loginData = [
                'platform'  => 'mobile',
                'clientKey' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjIjoxMDY2MSwiYSI6MTA2NjEsInMiOjF9.B08-6v9oP3rNxrvImC-WBO-AN0mru77ZNLOgqosNIjA',
                'loginType' => '9',
                'lang'      => 'VI',
                'roleID'    => $request->id_account,
                'roleName'  => $request->id_account,
                'getVgaId'  => '1',
            ];

            $loginResp = Http::asForm()
                ->post('https://billing.vnggames.com/fe/api/auth/quick', $loginData);

            if (!$loginResp->ok() || !$loginResp->json('data')) {
                return response()->json(['success' => false]);
            }

            $auth = $loginResp->json('data');

            return response()->json([
                'success'   => true,
                'role_name' => $auth['roleName'] ?? 'Không rõ'
            ]);
        } catch (\Throwable $e) {
            \Log::error('getRoleName error: ' . $e->getMessage());
            return response()->json(['success' => false]);
        }
    }

    private function mask(string $s): string {
        $len = strlen($s);
        if ($len <= 6) return str_repeat('*', $len);
        return substr($s, 0, 3) . str_repeat('*', $len - 6) . substr($s, -3);
    }
}
