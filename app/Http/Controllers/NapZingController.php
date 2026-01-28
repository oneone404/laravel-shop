<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
class NapZingController extends Controller
{
public function getGiftcode(Request $request)
{
    $validated = $request->validate([
        'roleId' => 'required|string',
        'code'   => 'required|string',
    ]);

    $role = $validated['roleId'];

    $payload = [
        'serverId' => '2',
        'gameCode' => '661',
        'roleId'   => $role,
        'roleName' => $role,
        'code'     => $validated['code'],
    ];

    $headers = [
        'accept'              => 'application/json, text/plain, */*',
        'accept-language'     => 'vi-VN,vi;q=0.9',
        'content-type'        => 'application/json',
        'origin'              => 'https://giftcode.vnggames.com',
        'referer'             => 'https://giftcode.vnggames.com/',
        'sec-fetch-dest'      => 'empty',
        'sec-fetch-mode'      => 'cors',
        'sec-fetch-site'      => 'same-site',
        'sec-ch-ua'           => '"Not_A Brand";v="99", "Chromium";v="142", "Google Chrome";v="142"',
        'sec-ch-ua-mobile'    => '?0',
        'sec-ch-ua-platform'  => '"Windows"',
        'user-agent'          => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',
        'x-client-region'     => 'VN',
        'x-request-id'        => (string) Str::uuid(),
    ];

    $response = Http::withHeaders($headers)
        ->post('https://vgrapi-sea.vnggames.com/coordinator/api/v1/code/redeem', $payload);

    return response()->json([
        'status'  => $response->successful(),
        'data'    => $response->json(),
        'message' => $response->successful() ? 'OK' : 'Error from VNG API',
    ], $response->status());
}

    public function checkPlus(Request $request)
    {
        $request->validate([
            'role_id' => 'required|string|max:50',
        ]);

        $roleId = $request->input('role_id');

        try {
            // Bước 1: login
            $loginData = [
                'platform' => 'mobile',
                'clientKey' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjIjoxMDY2MSwiYSI6MTA2NjEsInMiOjF9.B08-6v9oP3rNxrvImC-WBO-AN0mru77ZNLOgqosNIjA',
                'loginType' => '9',
                'lang' => 'VI',
                'roleID' => $roleId,
                'roleName' => $roleId,
                'getVgaId' => '1',
            ];

            $loginResp = Http::asForm()
                ->post('https://billing.vnggames.com/fe/api/auth/quick', $loginData);

            if (!$loginResp->ok() || !$loginResp->json('data')) {
                return response()->json([
                    'success' => true,
                    'has_plus' => false,
                    'message' => 'Login False'
                ]);
            }

            $auth = $loginResp->json('data');

            // Bước 2: getProducts
            $productData = [
                'jtoken' => $auth['jtoken'],
                'serverID' => $auth['serverID'],
                'userID' => $auth['userID'],
                'loginType' => $auth['loginType'],
                'roleID' => $auth['roleID'],
                'roleName' => $auth['roleName'],
                'lang' => 'VI',
                'bonusInfo' => 'false',
            ];

            $productsResp = Http::asForm()
                ->post('https://billing.vnggames.com/fe/api/multiitemorder/getProducts', $productData);

            if (!$productsResp->ok()) {
                return response()->json([
                    'success' => true,
                    'has_plus' => false,
                    'message' => 'Lấy Danh Sách False'
                ]);
            }

            $json = $productsResp->json();
            $products = $json['data']['products'] ?? [];

            $targetId = env('PRODUCT_ID_PLUS', '92110025');

            if (!is_array($products) || !array_key_exists($targetId, $products)) {
                return response()->json([
                    'success' => true,
                    'has_plus' => false,
                    'message' => 'Không Tìm Thấy Gói Plus'
                ]);
            }

            $p = $products[$targetId];

            if (
                !empty($p) &&
                ($p['enable'] ?? 0) == 1 &&
                ($p['hidden'] ?? 1) == 0
            ) {
                return response()->json([
                    'success' => true,
                    'has_plus' => true,
                    'product_name' => $p['productName'] ?? '',
                    'price' => $p['prices']['VND']['price'] ?? 0,
                ]);
            }

            return response()->json([
                'success' => true,
                'has_plus' => false,
                'message' => 'Không Tìm Thấy Gói Plus'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function insertCard(Request $request)
    {
        $request->validate([
            'cardSerial' => 'required|string|max:50|unique:cardzing_deposit,cardSerial',
            'cardPassword' => 'required|string|max:50',
        ]);

        $card = \App\Models\CardZingDeposit::create([
            'cardSerial' => $request->input('cardSerial'),
            'cardPassword' => $request->input('cardPassword'),
            'status' => 'available',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thành Công',
            'data' => $card
        ]);
    }

    public function countAvailableCards()
    {
        $count = \App\Models\CardZingDeposit::where('status', 'available')->count();

        return response()->json([
            'success' => true,
            'message' => 'Thẻ Zing',
            'data' => [
                'available_cards' => $count
            ]
        ]);
    }

    public function nap(Request $request)
    {
        $request->validate([
            'role_id' => 'required|string',
            'cardSerial' => 'required|string',
            'cardPassword' => 'required|string',
            'type' => 'required|in:22,30',
        ]);

        $role_id = $request->input('role_id');
        $role_name = $role_id;
        $cardSerial = $request->input('cardSerial');
        $cardPassword = $request->input('cardPassword');
        $type = (int) $request->input('type');

        // xác định productID
        $productID = match ($type) {
            22 => '92110005',
            30 => env('PRODUCT_ID_PLUS', '92110025'),
            default => null,
        };

        if (!$productID) {
            return response()->json([
                'success' => false,
                'message' => 'Loại type không hợp lệ',
            ], 400);
        }

        // 🔷 Gọi auth/quick
        $data_quick = [
            'platform' => 'mobile',
            'clientKey' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjIjoxMDY2MSwiYSI6MTA2NjEsInMiOjF9.B08-6v9oP3rNxrvImC-WBO-AN0mru77ZNLOgqosNIjA',
            'loginType' => '9',
            'lang' => 'VI',
            'jtoken' => '',
            'userID' => '',
            'roleID' => $role_id,
            'roleName' => $role_name,
            'serverID' => '',
            'getVgaId' => '1',
        ];

        $resp_quick = Http::asForm()
            ->post('https://billing.vnggames.com/fe/api/auth/quick', $data_quick);

        $resp_data = $resp_quick->json();

        if (!isset($resp_data['returnCode']) || $resp_data['returnCode'] != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi auth/quick',
                'data' => $resp_data,
            ], 400);
        }

        $info = $resp_data['data'];
        $real_role_name = $info['roleName'];

        // 🔷 Gọi createOrder
        $data_order = [
            'pmcID' => '1',
            'paymentGatewayID' => '1',
            'paymentGroupID' => 'card',
            'paymentPartnerID' => '1',
            'providerID' => '1',
            'amount' => '20000',
            'description' => 'S0FJQTNEWURFNSBu4bqhcCAyMC4wMDAgVk5EIHbDoG8gZ2FtZSBQbGF5IFRvZ2V0aGVyIFZORyBxdWEgVGjhursgWmluZyB04bqhaSBTaG9wLnZuZ2dhbWVzLmNvbQ',
            'currency' => 'VND',
            'country' => 'VN',
            'lang' => 'VI',
            'cardSerial' => $cardSerial,
            'cardPassword' => $cardPassword,
            'paymentMethodID' => '1',
            'paymentProviderID' => '1',
            'payingAmount' => '20000',
            'serverName' => $info['serverName'] ?? '',
            'jtoken' => $info['jtoken'],
            'serverID' => $info['serverID'],
            'userID' => $info['userID'],
            'roleID' => $role_id,
            'roleName' => $real_role_name,
            'products' => json_encode([
                [
                    'productID' => $productID,
                    'quantity' => 1
                ]
            ]),
        ];

        $resp_order = Http::asForm()
            ->post('https://billing.vnggames.com/fe/api/multiitemorder/createOrder', $data_order);

        $order_data = $resp_order->json();

        return response()->json([
            'success' => true,
            'message' => 'Tạo Đơn Hàng Thành Công',
            'data' => $order_data,
        ]);
    }
}
