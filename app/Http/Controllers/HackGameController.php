<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\GameHack;

class HackGameController extends Controller
{
    /**
     * Trang danh sách tất cả hack (show-all)
     */
public function index()
{
    $hacks = GameHack::select('id', 'name', 'logo', 'thumbnail', 'active')
        ->orderBy('id')
        ->get();
    // đổi 'hack.show-all' -> 'user.hacks.show-all'
    return view('user.hacks.show-all', compact('hacks'));
}

    /**
     * Trang chi tiết 1 hack (show)
     * Route Model Binding: /hacks/{hack}
     */
public function show(\App\Models\GameHack $hack)
{
    // đổi 'hack.show' -> 'user.hacks.show'
    return view('user.hacks.show', compact('hack'));
}

    /**
     * Tải hack — nếu active=0 thì back() và flash lỗi; nếu active=1 thì redirect ra link ngoài
     */
    public function download(GameHack $hack)
    {
        if ((int) $hack->active === 0) {
            return back()->with('error', 'Hiện Tại Hack Đang Bảo Trì, Vui Lòng Quay Lại Sau Nhé. Thank You !');
        }

        // 302 ra link ngoài (vượt được IUAM vì là điều hướng trực tiếp)
        return redirect()->away($hack->download_link);
    }

    /**
     * Tải hack Global — dành riêng cho Play Together có 2 bản VNG và Global
     */
    public function downloadGlobal(GameHack $hack)
    {
        if ((int) $hack->active === 0) {
            return back()->with('error', 'Hiện Tại Hack Đang Bảo Trì, Vui Lòng Quay Lại Sau Nhé. Thank You !');
        }

        if (empty($hack->download_link_global)) {
            return back()->with('error', 'Game này không có bản Global.');
        }

        // 302 ra link ngoài (vượt được IUAM vì là điều hướng trực tiếp)
        return redirect()->away($hack->download_link_global);
    }

    /**
     * Lấy key (gọi API bên thứ ba) — giữ nguyên logic của bạn, chỉ đổi sang binding
     */
    public function getKey(GameHack $hack)
    {
        try {
            $response = Http::withHeaders([
                    'Accept' => '*/*',
                    'Accept-Language' => 'vi,en-US;q=0.9,en;q=0.8',
                    'Connection' => 'keep-alive',
                    'Referer' => 'https://hmgteam.net/GETKEY/index.php?api=' . $hack->api_hack,
                    'User-Agent' => 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Mobile Safari/537.36',
                    'sec-ch-ua' => '"Chromium";v="136", "Google Chrome";v="136", "Not.A/Brand";v="99"',
                    'sec-ch-ua-mobile' => '?1',
                    'sec-ch-ua-platform' => '"Android"',
                ])
                ->withCookies([
                    'inputValue'    => 'ONE',
                    'addkeydevice'  => 'true',
                    'addkeytime'    => 'true',
                ], 'hmgteam.net')
                ->get('https://hmgteam.net/GETKEY/index.php', [
                    'api' => $hack->api_hack,
                    'type' => $hack->api_type,
                    'g-recaptcha-response' => '-iJ7iy5JoTSp9LlJak515mzXTyWuHw4K9tEg4a8SOVjSSk',
                ]);

            if (!$response->ok()) {
                return back()->with('error', 'ERROR API: ' . $response->status() . ' - ' . $response->body());
            }

            $body = trim($response->body());

            if (filter_var($body, FILTER_VALIDATE_URL)) {
                if (str_contains($body, '/404.php')) {
                    return back()->with('error', 'VUI LÒNG THỬ LẠI');
                }

                // Nếu không phải 'oneone' thì rút gọn
                if ($hack->api_hack !== 'oneone') {
                    $shortUrl = $this->shortenUrl($body);
                    return redirect()->away($shortUrl);
                }

                // 'oneone' → trả link gốc
                return redirect()->away($body);
            }

            return back()->with('error', 'VUI LÒNG THỬ LẠI');
        } catch (\Exception $e) {
            return back()->with(
                'error',
                'LỖI HỆ THỐNG: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine()
            );
        }
    }

    /**
     * Free Key - Bước 1: Tạo session trên HackViet
     * Gọi API createFreeKeySession để lấy session_code
     * Lưu session_code vào DB, trả về link rút gọn
     * Key chỉ được claim khi user vượt link xong
     */
    public function freeKey(GameHack $hack)
    {
        // Kiểm tra hack có đang active không
        if ((int) $hack->active === 0) {
            return response()->json([
                'success' => false,
                'error' => 'Bản Hack Đang Trong Quá Trình Bảo Trì Sửa Lỗi, Xin Cảm Ơn!',
            ], 503);
        }

        // Lấy hoặc tạo visitor_id từ cookie để định danh người dùng
        $visitorId = request()->cookie('free_key_visitor_id');
        if (!$visitorId) {
            $visitorId = \Illuminate\Support\Str::uuid()->toString();
        }

        // 1. Kiểm tra xem có session nào đang chờ (pending) gắn với visitor_id và BẢN HACK này không
        // Session chỉ có hiệu lực trong ngày (đến 23:59:59)
        $existingSession = \App\Models\FreeKeySession::where('client_id', $visitorId)
            ->where('game_hack_id', $hack->id)
            ->where('status', \App\Models\FreeKeySession::STATUS_PENDING)
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->first();

        if ($existingSession && $existingSession->short_url) {
            \Log::info('[FreeKey] Returning existing pending session for visitor: ' . $visitorId . ' | Hack: ' . $hack->id);
            return response()->json([
                'success' => true,
                'short_url' => $existingSession->short_url,
                'token' => $existingSession->token,
                'message' => 'Tiếp tục vượt link cũ để nhận key!',
            ])->withCookie(cookie('free_key_visitor_id', $visitorId, 60 * 24 * 365)); // 1 year
        }

        // 2. Nếu không có session cũ, tiến hành tạo mới (Bỏ hoàn toàn Rate Limiting theo yêu cầu)
        try {
            $service = app(\App\Services\HackVietService::class);
            
            // Bước 1: Gọi API tạo session trên HackViet
            $sessionResult = $service->createFreeKeySession();
            
            if (!$sessionResult['success']) {
                \Log::error('[FreeKey] createFreeKeySession failed: ' . ($sessionResult['error'] ?? 'Unknown'));
                return response()->json([
                    'success' => false,
                    'error' => $sessionResult['error'] ?? 'Không thể tạo session, vui lòng thử lại!',
                ], 500);
            }
            
            $hackvietSessionCode = $sessionResult['data']['session_code'] ?? null;
            
            if (empty($hackvietSessionCode)) {
                \Log::error('[FreeKey] No session_code in response');
                return response()->json([
                    'success' => false,
                    'error' => 'Không nhận được session code từ server!',
                ], 500);
            }
            
            // Tạo token cho local session
            $token = \App\Models\FreeKeySession::generateToken();
            
            // Tạo URL activate với TOKEN (sau khi user vượt link)
            $activateUrl = route('keyfree.activate', ['token' => $token]);
            
            // Rút gọn URL qua XLink API
            $shortUrl = $this->shortenUrl($activateUrl);
            
            // Lưu vào DB
            $session = \App\Models\FreeKeySession::create([
                'token' => $token,
                'game_hack_id' => $hack->id, // Lưu ID bản hack
                'short_url' => $shortUrl,
                'ip_address' => request()->ip(),
                'client_id' => $visitorId, // Lưu định danh từ cookie
                'status' => \App\Models\FreeKeySession::STATUS_PENDING,
                'hackviet_session_code' => $hackvietSessionCode,
            ]);
            
            \Log::info('[FreeKey] Session created: ' . $token . ' | Visitor: ' . $visitorId . ' | Hack: ' . $hack->id);
            
            // Trả về short_url và set cookie visitor_id
            return response()->json([
                'success' => true,
                'short_url' => $shortUrl,
                'token' => $token,
                'message' => 'Vượt link để nhận key miễn phí!',
            ])->withCookie(cookie('free_key_visitor_id', $visitorId, 60 * 24 * 365));

        } catch (\Exception $e) {
            \Log::error('[FreeKey] Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Free Key - Hiển thị trang xác nhận với Turnstile
     * GET /keyfree/session/{token}
     */
    public function showActivatePage(string $token)
    {
        if (empty($token)) {
            return view('user.keyfree-error', ['error' => 'Token không hợp lệ!']);
        }

        // Tìm session
        $session = \App\Models\FreeKeySession::where('token', $token)->first();
        
        if (!$session) {
            return view('user.keyfree-error', ['error' => 'Session không tồn tại!']);
        }

        // Nếu đã activate trước đó → hiển thị key
        if ($session->isActivated()) {
            return $this->showActivatedKey($session);
        }

        // Kiểm tra session còn valid không (hết hạn vào cuối ngày)
        if (!$session->isValid()) {
            $session->update(['status' => \App\Models\FreeKeySession::STATUS_EXPIRED]);
            return view('user.keyfree-error', ['error' => 'Session đã hết hạn! Vui lòng tạo lại.']);
        }

        // Tính thời gian - random từ 60-75 giây
        $minSeconds = rand(60, 75);
        $elapsedSeconds = now()->diffInSeconds($session->created_at);
        $tooFast = $elapsedSeconds < $minSeconds;

        // Hiển thị trang xác nhận với Turnstile
        return view('user.keyfree-confirm', [
            'token' => $token,
            'turnstileSiteKey' => config('services.turnstile.site_key'),
            'tooFast' => $tooFast,
        ]);
    }

    /**
     * Free Key - Bước 2: Verify Turnstile và Activate key
     * POST /keyfree/session/{token}
     */
    public function activateFreeKey(Request $request, string $token)
    {
        if (empty($token)) {
            return view('user.keyfree-error', ['error' => 'Token không hợp lệ!']);
        }

        // Verify Turnstile
        $turnstileResponse = $request->input('cf-turnstile-response');
        if (empty($turnstileResponse)) {
            return back()->with('error', 'Vui lòng hoàn thành xác minh!');
        }

        $verified = $this->verifyTurnstile($turnstileResponse);
        if (!$verified) {
            return back()->with('error', 'Xác minh thất bại! Vui lòng thử lại.');
        }

        // Tìm session
        $session = \App\Models\FreeKeySession::where('token', $token)->first();
        
        if (!$session) {
            return view('user.keyfree-error', ['error' => 'Session không tồn tại!']);
        }

        // Nếu đã activate trước đó → hiển thị key
        if ($session->isActivated()) {
            return $this->showActivatedKey($session);
        }

        // Kiểm tra session còn valid không (hết hạn vào cuối ngày)
        if (!$session->isValid()) {
            $session->update(['status' => \App\Models\FreeKeySession::STATUS_EXPIRED]);
            return view('user.keyfree-error', ['error' => 'Session đã hết hạn! Vui lòng tạo lại.']);
        }

        // Kiểm tra thời gian - random 60-75 giây
        $minSeconds = rand(60, 75);
        $elapsedSeconds = now()->diffInSeconds($session->created_at);
        
        if ($elapsedSeconds < $minSeconds) {
            \Log::warning('[FreeKey] Too fast! Token: ' . $token . ' | Elapsed: ' . $elapsedSeconds . 's');
            return view('user.keyfree-confirm', [
                'token' => $token,
                'turnstileSiteKey' => config('services.turnstile.site_key'),
                'tooFast' => true,
            ]);
        }

        // Kiểm tra có session_code không
        if (empty($session->hackviet_session_code)) {
            return view('user.keyfree-error', ['error' => 'Session code không tồn tại! Vui lòng tạo lại.']);
        }

        // === BƯỚC 1: Verify session để HackViet tự claim key ===
        try {
            $service = app(\App\Services\HackVietService::class);
            
            // Gọi verifyFreeKeySession để kích hoạt session trên HackViet
            // HackViet sẽ tự claim key khi verify thành công
            $verifyResult = $service->verifyFreeKeySession($session->hackviet_session_code);
            
            \Log::info('[FreeKey Activate] Verify completed');
            
            if (!$verifyResult['success']) {
                \Log::error('[FreeKey Activate] verifyFreeKeySession failed: ' . ($verifyResult['error'] ?? 'Unknown'));
                return view('user.keyfree-error', ['error' => $verifyResult['error'] ?? 'Không thể verify session, vui lòng thử lại!']);
            }
            
            // === BƯỚC 2: Lấy key đã được claim ===
            // Gọi claimFreeKey để lấy key (server đã claim khi verify)
            $claimResult = $service->claimFreeKey($session->hackviet_session_code);
            
            \Log::info('[FreeKey Activate] Claim completed');
            
            $key = $claimResult['key'] ?? null;
            
            if (empty($key)) {
                // Nếu claimFreeKey không trả key, thử parse từ verify response
                \Log::warning('[FreeKey Activate] No key from claim, trying to parse from verify response');
                
                // Thử extract key từ HTML response của verify (nếu có)
                $verifyBody = $verifyResult['body'] ?? '';
                if (preg_match('/key["\s:]+([A-Z0-9\-]+)/i', $verifyBody, $matches)) {
                    $key = $matches[1];
                    \Log::info('[FreeKey Activate] Extracted key from HTML: ' . $key);
                }
            }
            
            if (empty($key)) {
                \Log::error('[FreeKey Activate] No key found');
                return view('user.keyfree-error', ['error' => 'Không nhận được key, vui lòng thử lại!']);
            }
            
            // Lấy thông tin chi tiết key từ API
            $keyDetails = $service->searchKey($key);
            
            $expiresAt = null;
            $hackvietKeyId = null;
            $devices = [];
            $deviceLimit = 1;
            
            if ($keyDetails['success'] && !empty($keyDetails['data'])) {
                $hackvietKeyId = $keyDetails['data']['id'] ?? null;
                $deviceLimit = $keyDetails['data']['device_limit'] ?? 1;
                
                // Hiển thị hết hạn chỉ đến 23:59:59 cùng ngày
                $expiresAt = now('Asia/Ho_Chi_Minh')->endOfDay();
                
                // Lấy danh sách devices nếu có hackviet_key_id
                if ($hackvietKeyId) {
                    $deviceDetails = $service->getKeyDetails($key);
                    if ($deviceDetails['success'] && !empty($deviceDetails['data']['devices'])) {
                        $devices = $deviceDetails['data']['devices'];
                    }
                }
            } else {
                // Nếu không lấy được details từ API nhưng vẫn có key, mặc định là cuối ngày
                $expiresAt = now('Asia/Ho_Chi_Minh')->endOfDay();
            }
            
            // Cập nhật session với key
            $session->update([
                'status' => \App\Models\FreeKeySession::STATUS_ACTIVATED,
                'key_value' => $key,
                'hackviet_key_id' => $hackvietKeyId,
                'expires_at' => $expiresAt,
                'activated_at' => now(),
            ]);
            
            \Log::info('[FreeKey Activate] Success! Token: ' . $token . ' | Key: ' . $key);
            
            return view('user.keyfree', [
                'session' => $session,
                'key' => $key,
                'expires_at' => $expiresAt,
                'devices' => $devices,
                'deviceLimit' => $deviceLimit,
            ]);

        } catch (\Exception $e) {
            \Log::error('[FreeKey Activate] Error: ' . $e->getMessage());
            return view('user.keyfree-error', ['error' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
    }

    /**
     * Rút gọn link (fallback trả lại link gốc nếu lỗi)
     */
    private function shortenUrl(string $url): string
    {
        $apiUrl   = (string) \App\Helpers\ConfigHelper::get('XLINK_API_URL');
        $apiToken = (string) \App\Helpers\ConfigHelper::get('XLINK_API_TOKEN');

        try {
            $response = Http::get($apiUrl, [
                'token' => $apiToken,
                'url'   => $url,
            ]);

            if ($response->ok() && $response->json('status') === 'success') {
                return $response->json('shortenedUrl') ?: $url;
            }
        } catch (\Exception $e) {
            \Log::error('Shorten URL failed: ' . $e->getMessage());
        }

        return $url;
    }

    /**
     * Helper: Hiển thị key đã được activate
     */
    private function showActivatedKey(\App\Models\FreeKeySession $session)
    {
        $devices = [];
        $deviceLimit = 1;
        
        // Lấy thông tin devices từ API
        if ($session->key_value) {
            $service = app(\App\Services\HackVietService::class);
            $deviceDetails = $service->getKeyDetails($session->key_value);
            if ($deviceDetails['success'] && !empty($deviceDetails['data']['devices'])) {
                $devices = $deviceDetails['data']['devices'];
            }
            if ($deviceDetails['success'] && isset($deviceDetails['data']['device_limit'])) {
                $deviceLimit = $deviceDetails['data']['device_limit'];
            }
        }
        
        $expiresAt = $session->expires_at;
        if ($expiresAt && $expiresAt->format('H:i:s') !== '23:59:59') {
            $baseDate = $session->activated_at ?: $session->created_at;
            $expiresAt = $baseDate->copy()->setTimezone('Asia/Ho_Chi_Minh')->endOfDay();
            $session->update(['expires_at' => $expiresAt]);
        }

        return view('user.keyfree', [
            'session' => $session,
            'key' => $session->key_value,
            'expires_at' => $expiresAt,
            'devices' => $devices,
            'deviceLimit' => $deviceLimit,
        ]);
    }

    /**
     * Helper: Verify Cloudflare Turnstile response
     */
    private function verifyTurnstile(string $token): bool
    {
        $secretKey = config('services.turnstile.secret_key');
        
        if (empty($secretKey)) {
            \Log::warning('[Turnstile] Secret key not configured, skipping verification');
            return true; // Skip nếu chưa config
        }

        try {
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secretKey,
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();
            
            if ($result['success'] ?? false) {
                return true;
            }
            
            \Log::warning('[Turnstile] Verification failed: ' . json_encode($result));
            return false;

        } catch (\Exception $e) {
            \Log::error('[Turnstile] Error: ' . $e->getMessage());
            return false;
        }
    }
}
