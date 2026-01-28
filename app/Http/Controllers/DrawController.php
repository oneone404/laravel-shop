<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\DrawHistory;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class DrawController extends Controller
{
    public function index()
    {
        return view('user.draw');
    }

    public function spin(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Cần Đăng Nhập Để Bốc Thăm'], 403);
    }
    $game = $request->input('game', 'com.vng.playtogether'); // mặc định là VNG nếu không có
    $today = Carbon::today()->toDateString();
    $ip = $request->ip();
    $deviceId = $request->header('X-Device-ID') ?? 'unknown';
    $userAgent = $request->userAgent();

    // Kiểm tra user đã bốc hôm nay chưa
    $alreadyUserDrawn = DrawHistory::where('user_id', $user->id)
        ->whereDate('daily', $today)
        ->exists();

    if ($alreadyUserDrawn) {
        return response()->json(['error' => 'Bạn Chỉ Được Bốc Thăm 1 Lần Trong Ngày!'], 429);
    }

    // Kiểm tra IP hoặc device_id đã dùng hôm nay chưa
    $alreadyDeviceUsed = DrawHistory::whereDate('daily', $today)
        ->where(function ($query) use ($ip, $deviceId) {
            $query->where('ip_address', $ip)
                  ->orWhere('device_id', $deviceId);
        })
        ->exists();

    if ($alreadyDeviceUsed) {
        return response()->json(['error' => 'Bạn Chỉ Được Bốc Thăm 1 Lần Trong Ngày!'], 429);
    }

    if ($user->total_deposited < 10000) {
        return response()->json(['error' => 'Tổng Nạp Trên 10K Mới Có Thể Tham Gia Bốc Thăm'], 402);
    }
    
    // Danh sách phần thưởng
    $prizes = [
        'Chúc Bạn May Mắn Lần Sau',
        'Chúc Bạn May Mắn Lần Sau',
        'Chúc Bạn May Mắn Lần Sau',
        'Key 1 Ngày',
        'Chúc Bạn May Mắn Lần Sau',
        'Chúc Bạn May Mắn Lần Sau',
        'Chúc Bạn May Mắn Lần Sau',
    ];

    // Chọn ngẫu nhiên phần thưởng
    $prize = $prizes[array_rand($prizes)];

    // Tạo key hoặc mã ngẫu nhiên
    if ($prize === 'Key 1 Ngày') {
        $key = $this->createKey($prize, 1, $game); // truyền thêm $game vào
        $finalKey = $key ?: 'fail';
    } else {
        $finalKey = Str::upper(Str::random(11));
    }

    // --- Bước lưu biến động số dư ---

    // Ví dụ chi phí quay: 100 (hoặc 0 nếu miễn phí)
    $totalCost = 0;
    $type = $prize === 'Key 1 Ngày' ? 'draw' : 'none';
    $balanceBefore = $user->balance;

    // Lưu lịch sử bốc thăm
    DrawHistory::create([
        'user_id' => $user->id,
        'description' => $prize,
        'daily' => $today,
        'ip_address' => $ip,
        'device_id' => $deviceId,
        'user_agent' => $userAgent,
    ]);

    // Rút gọn link
    $redirectUrl = "https://accone.vn/draw/result?key=" . urlencode($finalKey);
    $shortenUrl = $this->shortenUrl($redirectUrl);

    return response()->json(['link' => $shortenUrl]);
}


    public function result(Request $request)
    {
        return view('user.draw_result', [
            'key' => $request->get('key')
        ]);
    }

    private function shortenUrl($url)
    {
        $apiUrl = \App\Helpers\ConfigHelper::get('XLINK_API_URL');
        $apiToken = \App\Helpers\ConfigHelper::get('XLINK_API_TOKEN');
    
        try {
            $response = Http::get($apiUrl, [
                'token' => $apiToken,
                'url' => $url,
            ]);
    
            if ($response->ok() && $response->json('status') === 'success') {
                $shortenedUrl = $response->json('shortenedUrl');
                return $shortenedUrl ?? $url;
            }
        } catch (\Exception $e) {
            Log::error('Shorten URL failed: ' . $e->getMessage());
        }
    
        return $url;
    }


    private function createKey($content, $spinCount = 1, $game = 'com.vng.playtogether')
    {
        $session = new Client([
            'cookies' => true,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)...',
            ]
        ]);

        $hmgUsername = env('HMGTEAM_USERNAME');
        $hmgPassword = env('HMGTEAM_PASSWORD');

        if (!$hmgUsername || !$hmgPassword) {
            return null;
        }

        try {
            $loginResponse = $session->post('https://hmgteam.net/auth/xacminh.php', [
                'form_params' => [
                    'taikhoan' => $hmgUsername,
                    'matkhau' => $hmgPassword,
                ]
            ]);

            if ($loginResponse->getStatusCode() !== 200) {
                return null;
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return null;
        }

        $time_type = 'D';
        $hansudung = 1;

        if (str_contains($content, 'Tháng')) {
            $time_type = 'M';
            preg_match('/(\d+)/', $content, $matches);
            $hansudung = $matches[1] ?? 1;
        } elseif (str_contains($content, 'Tuần')) {
            $time_type = 'W';
            preg_match('/(\d+)/', $content, $matches);
            $hansudung = $matches[1] ?? 1;
        } elseif (str_contains($content, 'Ngày')) {
            $time_type = 'D';
            preg_match('/(\d+)/', $content, $matches);
            $hansudung = $matches[1] ?? 1;
        }

        $hansudung = $hansudung * $spinCount;

        try {
            $response = $session->post('https://hmgteam.net/admin/ajax/add_key.php', [
                'form_params' => [
                    'type' => 'taokey',
                    'key' => '1',
                    'chudau' => 'ONE',
                    'hansudung' => $hansudung,
                    'time_type' => $time_type,
                    'somay' => 1,
                    'chonGame' => $game, // <-- Dùng giá trị được truyền vào
                    'chonBeta' => 'khong',
                    'addkeydevice' => '1',
                    'addkeytime' => '1',
                ],
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Referer' => 'https://hmgteam.net/admin/quanlykey.php',
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $body = json_decode((string)$response->getBody(), true);
                if ($body && $body['ketqua'] === 'thanhcong' && isset($body['list'])) {
                    preg_match('/KEY\s*:\s*(\S+)/', $body['list'], $matches);
                    return $matches[1] ?? null;
                }
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return null;
        }

        return null;
    }
}