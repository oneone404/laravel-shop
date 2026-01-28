<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LuckyWheel;
use App\Models\LuckyWheelHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class LuckyCategoryController extends Controller
{
    // Hiển thị tất cả danh mục vòng quay đang active
    public function showAll()
    {
        $title = 'Vòng Quay May Mắn';
        $categories = LuckyWheel::where('active', 1)->get();

        foreach ($categories as $category) {
            $category->soldCount = $category->histories->count();
        }

        return view('user.wheel.show-all', compact('categories', 'title'));
    }

    // Hiển thị chi tiết vòng quay theo slug
    public function index($slug)
{
    $wheel = LuckyWheel::where('slug', $slug)->where('active', 1)->firstOrFail();

    $history = [];
    $freeSpinsLeft = 0;
    $totalDeposited = 0; // default nếu chưa đăng nhập

    if (Auth::check()) {
        $user = Auth::user();

        // Lấy lịch sử user hiện tại
        $history = LuckyWheelHistory::with('user')
            ->where('lucky_wheel_id', $wheel->id)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Tính lượt quay free còn lại theo logic spin free
        $today = date('Y-m-d');
        if ($user->daily_free_spin_at !== $today) {
            $user->daily_free_spin_at = $today;
            $user->daily_free_spin_count = 0;
            $user->save();
        }

        $maxFreeSpins = $user->getMaxFreeSpinsPerDay();
        $freeSpinsLeft = max(0, $maxFreeSpins - $user->daily_free_spin_count);

        // Truyền tổng nạp
        $totalDeposited = $user->total_deposited;
    }

    $config = $this->getPrizeConfig();
    $lucky = Auth::check() ? Auth::user()->lucky : 0;

    return view('user.wheel.detail', compact('wheel', 'history', 'config', 'lucky', 'freeSpinsLeft', 'totalDeposited'));
}

public function getHistoryHtml($slug)
{
    $wheel = LuckyWheel::where('slug', $slug)
        ->where('active', 1)
        ->firstOrFail();

    $history = [];
    if (Auth::check()) {
        $history = LuckyWheelHistory::with('user')
            ->where('lucky_wheel_id', $wheel->id)
            ->where('user_id', Auth::id())  // Chỉ lấy lịch sử user hiện tại
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    return view('user.wheel.partials.history-table', compact('history'))->render();
}

public function spin(Request $request, $slug)
{
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Vui Lòng Đăng Nhập!'
        ]);
    }

    // Thực hiện trong transaction để tránh lỗi nửa chừng làm mất đồng bộ dữ liệu
    return DB::transaction(function () use ($request, $slug) {

        $request->validate([
            'spin_count' => 'required|integer|min:1|max:10',
        ]);

        $user = Auth::user();
        $wheel = LuckyWheel::where('slug', $slug)->where('active', 1)->firstOrFail();
        $spinCount = $request->input('spin_count');

        $today = date('Y-m-d');

        // Reset lượt free spin nếu là ngày mới
        if ($user->daily_free_spin_at !== $today) {
            $user->daily_free_spin_at = $today;
            $user->daily_free_spin_count = 0;
        }

        $maxFreeSpins = $user->getMaxFreeSpinsPerDay();
        $freeSpinsLeft = $maxFreeSpins - $user->daily_free_spin_count;

        $canFreeSpinCount = 0;
        if ($freeSpinsLeft > 0) {
            $canFreeSpinCount = min($spinCount, $freeSpinsLeft);
        }

        $paidSpinCount = $spinCount - $canFreeSpinCount;
        $totalCost = $wheel->price_per_spin * $paidSpinCount;

        if ($paidSpinCount > 0 && $user->balance < $totalCost) {
            return response()->json([
                'success' => false,
                'message' => 'Số Dư Không Đủ!'
            ]);
        }

        $config = $this->getPrizeConfig();
        $isLuckyMax = $user->lucky >= 100;

        // Nếu lucky đã đầy, loại bỏ phần thưởng "none"
        if ($isLuckyMax) {
            foreach ($config as &$item) {
                if ($item['type'] !== 'key' || $item['content'] !== 'Trúng Key 1 Ngày') {
                    $item['probability'] = 0;
                } else {
                    $item['probability'] = 100; // đảm bảo nó được chọn
                }
            }
            unset($item);
        }
        
        $rewardsResult = [];
        $totalLuckyAdded = 0;
        $lastReward = null;
        $lastGeneratedKey = null;

        for ($i = 0; $i < $spinCount; $i++) {
            $rewardIndex = $this->calculateReward($config);
            $reward = $config[$rewardIndex];

            if ($reward['type'] === 'none') {
                $totalLuckyAdded += 10;
            }

            $generatedKey = null;
            if ($reward['type'] === 'key') {
                $generatedKey = $this->createKey($reward['content'], 1);
                if (!$generatedKey) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vui Lòng Thử Lại!'
                    ]);
                }
            }

            $rewardResult = [
                'type' => $reward['type'],
                'content' => $reward['content'],
                'amount' => $reward['amount'],
                'index' => $rewardIndex,
            ];

            if ($generatedKey) {
                $rewardResult['key'] = $generatedKey;
            }

            $rewardsResult[] = $rewardResult;

            $lastReward = $reward;
            $lastGeneratedKey = $generatedKey;
        }

        if ($totalLuckyAdded > 0) {
            $user->lucky += $totalLuckyAdded;
            if ($user->lucky > 100) $user->lucky = 100;
        }

        $user->daily_free_spin_count += $canFreeSpinCount;

        // Lưu số dư trước khi trừ
        $balanceBefore = $user->balance;

        if ($paidSpinCount > 0) {
            $user->balance -= $totalCost;
        }

        if ($isLuckyMax) {
            $user->lucky = 0;
        }

        $user->save();

        // Lưu lịch sử biến động số dư 
        $type = ($lastReward['content'] === 'Tăng 10% May Mắn') ? 'none' : 'purchase';
        
        DB::table('money_transactions')->insert([
            'user_id' => $user->id,
            'type' => $type,
            'amount' => $totalCost,
            'balance_before' => $balanceBefore,
            'balance_after' => $user->balance,
            'description' => $lastReward['content'],
            'reference_id' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);


        LuckyWheelHistory::create([
            'user_id' => $user->id,
            'lucky_wheel_id' => $wheel->id,
            'spin_count' => $spinCount,
            'total_cost' => $totalCost,
            'reward_type' => $lastReward['type'],
            'reward_amount' => $lastReward['amount'],
            'description' => $lastReward['type'] === 'key' ? $lastGeneratedKey : $lastReward['content'],
        ]);
        
        return response()->json([
            'success' => true,
            'rewards' => $rewardsResult,
            'new_balance' => $user->balance,
            'lucky' => $user->lucky,
            'free_spins_used_today' => $user->daily_free_spin_count,
            'max_free_spins_per_day' => $maxFreeSpins,
            'free_spins_left' => $maxFreeSpins - $user->daily_free_spin_count,
        ]);
    });
}

    // Cấu hình phần thưởng
    private function getPrizeConfig()
    {
        return [
            ["type" => "key", "content" => "Trúng Key 1 Tháng", "amount" => 30, "probability" => 0.0],
            ["type" => "key", "content" => "Trúng Key 1 Ngày", "amount" => 1, "probability" => 2.0],
            ["type" => "key", "content" => "Trúng Key 1 Tuần", "amount" => 7, "probability" => 0.0],
            ["type" => "key", "content" => "Trúng Key 1 Ngày", "amount" => 1, "probability" => 2.0],
            ["type" => "none", "content" => "Tăng 10% May Mắn", "amount" => 0, "probability" => 92],
            ["type" => "key", "content" => "Trúng Key 1 Ngày", "amount" => 1, "probability" => 2.0],
            ["type" => "key", "content" => "Trúng Key 1 Tuần", "amount" => 7, "probability" => 0.0],
            ["type" => "key", "content" => "Trúng Key 1 Ngày", "amount" => 1, "probability" => 2.0],
        ];
    }


    // Tính xác suất trúng thưởng
    private function calculateReward($config)
    {
        // Scale probability để xử lý float chính xác
        $scale = 1000;
        $totalProbability = 0;
    
        foreach ($config as $reward) {
            $totalProbability += $reward['probability'] * $scale;
        }
    
        $random = mt_rand(1, $totalProbability);
        $current = 0;
    
        foreach ($config as $index => $reward) {
            $current += $reward['probability'] * $scale;
            if ($random <= $current) {
                return $index;
            }
        }
    
        return 0; // fallback nếu không match
    }


    // Tạo key ngẫu nhiên 16 ký tự
    private function createKey($content, $spinCount = 1)
    {
        $session = new Client([ // Sửa: new Client thay vì new \GuzzleHttp\Client (nếu đã use ở trên)
            'cookies' => true,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)...',
            ]
        ]);

        // Lấy thông tin đăng nhập từ file .env
        $hmgUsername = env('HMGTEAM_USERNAME');
        $hmgPassword = env('HMGTEAM_PASSWORD');

        // Kiểm tra xem biến môi trường đã được thiết lập chưa
        if (!$hmgUsername || !$hmgPassword) {
            // Ghi log lỗi hoặc xử lý tùy theo ứng dụng của bạn
            // Ví dụ: Log::error('HMGTEAM_USERNAME hoặc HMGTEAM_PASSWORD chưa được cấu hình trong file .env');
            return null; // Hoặc throw một exception
        }

        // 1. Đăng nhập
        try {
            $loginResponse = $session->post('https://hmgteam.net/auth/xacminh.php', [
                'form_params' => [
                    'taikhoan' => $hmgUsername,
                    'matkhau' => $hmgPassword,
                ]
            ]);

            if ($loginResponse->getStatusCode() !== 200) {
                // Ghi log lỗi đăng nhập
                // Log::error('Đăng nhập HMGTeam thất bại. Status code: ' . $loginResponse->getStatusCode());
                return null;
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Ghi log lỗi kết nối hoặc request
            // Log::error('Lỗi khi thực hiện request đăng nhập đến HMGTeam: ' . $e->getMessage());
            return null;
        }


        // 2. Tách time_type & hansudung từ nội dung content
        $time_type = 'D'; // Mặc định
        $hansudung = 1;

        // Cân nhắc chuyển cấu trúc này vào config phần thưởng để tránh phân tích chuỗi mong manh
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

        // NHÂN hạn sử dụng với số lần quay
        $hansudung = $hansudung * $spinCount;

        // 3. Tạo key
        try {
            $response = $session->post('https://hmgteam.net/admin/ajax/add_key.php', [
                'form_params' => [
                    'type' => 'taokey',
                    'key' => '1', // Giá trị này có thể cần xem xét lại hoặc cấu hình nếu cần
                    'chudau' => 'ONE', // Tương tự, có thể cần cấu hình
                    'hansudung' => $hansudung,
                    'time_type' => $time_type,
                    'somay' => 1, // Cấu hình nếu cần
                    'chonGame' => 'com.vng.playtogether', // Cấu hình nếu cần
                    'chonBeta' => 'khong', // Cấu hình nếu cần
                    'addkeydevice' => '1', // Cấu hình nếu cần
                    'addkeytime' => '1', // Cấu hình nếu cần
                ],
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Referer' => 'https://hmgteam.net/admin/quanlykey.php', // Referer có thể cần hoặc không
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $body = json_decode((string)$response->getBody(), true);

                if ($body && isset($body['ketqua']) && $body['ketqua'] === 'thanhcong' && isset($body['list'])) {
                    preg_match('/KEY\s*:\s*(\S+)/', $body['list'], $matches);
                    return $matches[1] ?? null;
                } else {
                    // Ghi log lỗi khi kết quả trả về không thành công hoặc thiếu dữ liệu
                    // Log::error('Tạo key HMGTeam không thành công hoặc dữ liệu trả về không hợp lệ: ' . json_encode($body));
                    return null;
                }
            } else {
                // Ghi log lỗi khi tạo key
                // Log::error('Tạo key HMGTeam thất bại. Status code: ' . $response->getStatusCode());
                return null;
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Ghi log lỗi kết nối hoặc request
            // Log::error('Lỗi khi thực hiện request tạo key đến HMGTeam: ' . $e->getMessage());
            return null;
        }

        return null; // Fallback cuối cùng
    }
}