<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HackVietService
{
    protected string $baseUrl;
    protected string $shopSlug;
    protected string $gameSlug;
    protected string $email;
    protected string $password;
    
    // Cache keys
    const CACHE_COOKIES_KEY = 'hackviet_cookies';
    const CACHE_LAST_ACTIVITY = 'hackviet_last_activity';
    
    public function __construct()
    {
        $this->baseUrl = rtrim(\App\Helpers\ConfigHelper::get('HACKVIET_BASE_URL', 'https://hackviet.io'), '/');
        $this->shopSlug = \App\Helpers\ConfigHelper::get('HACKVIET_SHOP_SLUG', 'shop-82-kcvara');
        $this->gameSlug = \App\Helpers\ConfigHelper::get('HACKVIET_GAME_SLUG', 'play-together');
        $this->email = \App\Helpers\ConfigHelper::get('HACKVIET_EMAIL', '');
        $this->password = \App\Helpers\ConfigHelper::get('HACKVIET_PASSWORD', '');
    }

    /**
     * Lấy cookies từ cache hoặc file
     */
    public function getCookies(): array
    {
        // Ưu tiên lấy từ cache (được cập nhật khi login thành công)
        $cached = Cache::get(self::CACHE_COOKIES_KEY);
        if ($cached && is_array($cached)) {
            return $cached;
        }

        // Fallback: đọc từ file cấu hình
        $cookiesPath = storage_path('app/hackviet_cookies.json');
        if (file_exists($cookiesPath)) {
            $content = file_get_contents($cookiesPath);
            $cookies = json_decode($content, true);
            if ($cookies) {
                Cache::put(self::CACHE_COOKIES_KEY, $cookies, now()->addHours(3));
                return $cookies;
            }
        }

        return [];
    }

    /**
     * Lưu cookies vào cache và file
     */
    public function saveCookies(array $cookies): void
    {
        Cache::put(self::CACHE_COOKIES_KEY, $cookies, now()->addHours(3));
        
        $cookiesPath = storage_path('app/hackviet_cookies.json');
        file_put_contents($cookiesPath, json_encode($cookies, JSON_PRETTY_PRINT));
    }

    /**
     * Tạo Free Key Session
     * @return array ['success' => bool, 'data' => [...], 'error' => string]
     */
    public function createFreeKeySession(): array
    {
        $cookies = $this->getCookies();
        
        if (empty($cookies)) {
            // Thử login nếu chưa có cookies
            $loginResult = $this->login();
            if (!$loginResult['success']) {
                return $loginResult;
            }
            $cookies = $this->getCookies();
        }

        try {
            $response = Http::withHeaders($this->getHeaders($cookies))
                ->withCookies($cookies, parse_url($this->baseUrl, PHP_URL_HOST))
                ->timeout(30)
                ->post("{$this->baseUrl}/api/free-key/session", [
                    'shop_slug' => $this->shopSlug,
                    'game_slug' => $this->gameSlug,
                ]);

            // Cập nhật last activity
            Cache::put(self::CACHE_LAST_ACTIVITY, now(), now()->addHours(3));

            if ($response->successful()) {
                $data = $response->json();
                if ($data['success'] ?? false) {
                    return [
                        'success' => true,
                        'data' => $data['data'],
                    ];
                }
            }

            // Session expired? Thử login lại
            if ($response->status() === 401 || $response->status() === 419) {
                Log::warning('[HackViet] Session expired, attempting re-login...');
                $loginResult = $this->login();
                if ($loginResult['success']) {
                    // Retry sau khi login
                    return $this->createFreeKeySession();
                }
                return $loginResult;
            }

            return [
                'success' => false,
                'error' => 'API Error: ' . $response->status() . ' - ' . $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('[HackViet] createFreeKeySession failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Lỗi kết nối: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Claim Free Key từ session_code
     * Gọi API /api/free-key/claim để lấy key miễn phí 24h
     * @param string $sessionCode Session code từ createFreeKeySession
     * @return array ['success' => bool, 'key' => string, 'data' => [...], 'error' => string]
     */
    public function claimFreeKey(string $sessionCode): array
    {
        $cookies = $this->getCookies();
        
        if (empty($cookies)) {
            $loginResult = $this->login();
            if (!$loginResult['success']) {
                return $loginResult;
            }
            $cookies = $this->getCookies();
        }

        try {
            // Build referer URL với session_code
            $refererPath = "shop/{$this->shopSlug}/free-key-success/{$sessionCode}";
            
            $response = Http::withHeaders($this->getHeaders($cookies, $refererPath))
                ->withCookies($cookies, parse_url($this->baseUrl, PHP_URL_HOST))
                ->timeout(30)
                ->post("{$this->baseUrl}/api/free-key/claim", [
                    'session_code' => $sessionCode,
                ]);

            // Cập nhật last activity
            Cache::put(self::CACHE_LAST_ACTIVITY, now(), now()->addHours(3));

            if ($response->successful()) {
                $data = $response->json();
                // Response: { success: true, message: "...", data: { key: "XXX", ... } }
                if (($data['success'] ?? false) && !empty($data['data']['key'])) {
                    Log::info('[HackViet] Free key claimed successfully: ' . $data['data']['key']);
                    return [
                        'success' => true,
                        'key' => $data['data']['key'],
                        'data' => $data['data'],
                        'message' => $data['message'] ?? 'Đã tạo key miễn phí thành công',
                    ];
                }
                
                return [
                    'success' => false,
                    'error' => $data['message'] ?? 'API không trả về key: ' . json_encode($data),
                ];
            }

            // Session expired? Thử login lại
            if ($response->status() === 401 || $response->status() === 419) {
                Log::warning('[HackViet] Session expired during claimFreeKey, attempting re-login...');
                $loginResult = $this->login();
                if ($loginResult['success']) {
                    return $this->claimFreeKey($sessionCode);
                }
                return $loginResult;
            }

            return [
                'success' => false,
                'error' => 'API Error: ' . $response->status() . ' - ' . $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('[HackViet] claimFreeKey failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Lỗi kết nối: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify Free Key Session - Giả lập user đã vượt link
     * Gọi URL /r/free/{session_code}?slug={shopSlug} để kích hoạt session
     * @param string $sessionCode Session code từ createFreeKeySession
     * @return array ['success' => bool, 'error' => string]
     */
    public function verifyFreeKeySession(string $sessionCode): array
    {
        $cookies = $this->getCookies();
        
        if (empty($cookies)) {
            $loginResult = $this->login();
            if (!$loginResult['success']) {
                return $loginResult;
            }
            $cookies = $this->getCookies();
        }

        try {
            // URL verification: /r/free/{session_code}?slug={shopSlug}
            $verifyUrl = "{$this->baseUrl}/r/free/{$sessionCode}?slug={$this->shopSlug}";
            
            Log::info('[HackViet] Verifying session at: ' . $verifyUrl);
            
            $response = Http::withHeaders([
                'User-Agent' => $this->getUserAgent(),
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'vi-VN,vi;q=0.9,en-GB;q=0.8,en;q=0.7',
                'Referer' => "{$this->baseUrl}/shop/{$this->shopSlug}",
            ])
            ->withCookies($cookies, parse_url($this->baseUrl, PHP_URL_HOST))
            ->timeout(30)
            ->get($verifyUrl);

            // Cập nhật cookies từ response nếu có
            $newCookies = $response->cookies()->toArray();
            if (!empty($newCookies)) {
                foreach ($newCookies as $cookie) {
                    $cookies[$cookie['Name']] = $cookie['Value'];
                }
                $this->saveCookies($cookies);
            }

            Cache::put(self::CACHE_LAST_ACTIVITY, now(), now()->addHours(3));

            if ($response->successful() || $response->status() === 302) {
                Log::info('[HackViet] Session verified successfully');
                return [
                    'success' => true,
                    'message' => 'Session verified',
                    'body' => $response->body(), // Có thể chứa key trong HTML
                ];
            }

            // Session expired? Thử login lại
            if ($response->status() === 401 || $response->status() === 419) {
                Log::warning('[HackViet] Session expired during verify, attempting re-login...');
                $loginResult = $this->login();
                if ($loginResult['success']) {
                    return $this->verifyFreeKeySession($sessionCode);
                }
                return $loginResult;
            }

            return [
                'success' => false,
                'error' => 'Verify Error: ' . $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('[HackViet] verifyFreeKeySession failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Lỗi kết nối: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Full flow: Tạo session → Verify → Claim → Trả về key
     * @return array ['success' => bool, 'key' => string, 'data' => [...], 'error' => string]
     */
    public function getFullFreeKey(): array
    {
        // Bước 1: Tạo session
        $sessionResult = $this->createFreeKeySession();
        if (!$sessionResult['success']) {
            return $sessionResult;
        }
        
        $sessionCode = $sessionResult['data']['session_code'] ?? null;
        if (empty($sessionCode)) {
            return [
                'success' => false,
                'error' => 'Không nhận được session code',
            ];
        }
        
        Log::info('[HackViet] Got session_code: ' . $sessionCode);
        
        // Bước 2: Verify session (giả lập vượt link)
        $verifyResult = $this->verifyFreeKeySession($sessionCode);
        if (!$verifyResult['success']) {
            Log::warning('[HackViet] Verify failed but continuing to claim: ' . ($verifyResult['error'] ?? ''));
            // Vẫn thử claim dù verify thất bại
        }
        
        // Bước 3: Claim key
        $claimResult = $this->claimFreeKey($sessionCode);
        if (!$claimResult['success']) {
            return $claimResult;
        }
        
        return $claimResult;
    }

    /**
     * Tìm kiếm key và lấy thông tin chi tiết
     * Gọi API /api/seller/keys?search={key}
     * @param string $keyValue Key cần tìm
     * @return array ['success' => bool, 'data' => [...], 'error' => string]
     */
    public function searchKey(string $keyValue): array
    {
        $cookies = $this->getCookies();
        
        if (empty($cookies)) {
            $loginResult = $this->login();
            if (!$loginResult['success']) {
                return $loginResult;
            }
            $cookies = $this->getCookies();
        }

        try {
            $response = Http::withHeaders($this->getHeaders($cookies, 'seller/keys'))
                ->withCookies($cookies, parse_url($this->baseUrl, PHP_URL_HOST))
                ->timeout(30)
                ->get("{$this->baseUrl}/api/seller/keys", [
                    'search' => $keyValue,
                    'sort_field' => 'created_at',
                    'sort_direction' => 'desc',
                    'per_page' => 15,
                    'page' => 1,
                ]);

            Cache::put(self::CACHE_LAST_ACTIVITY, now(), now()->addHours(3));

            if ($response->successful()) {
                $data = $response->json();
                
                // Tìm key trong kết quả
                if (!empty($data['data']) && is_array($data['data'])) {
                    foreach ($data['data'] as $item) {
                        if ($item['key'] === $keyValue) {
                            Log::info('[HackViet] Found key details: ' . $keyValue);
                            return [
                                'success' => true,
                                'data' => [
                                    'id' => $item['id'],
                                    'key' => $item['key'],
                                    'expires_at' => $item['expires_at'],
                                    'created_at' => $item['created_at'],
                                    'duration_value' => $item['duration_value'],
                                    'duration_type' => $item['duration_type'],
                                    'device_limit' => $item['device_limit'],
                                    'status' => $item['status'],
                                    'is_vip' => $item['is_vip'],
                                    'devices' => $item['devices'],
                                ],
                            ];
                        }
                    }
                }
                
                return [
                    'success' => false,
                    'error' => 'Không tìm thấy key: ' . $keyValue,
                ];
            }

            // Session expired? Thử login lại
            if ($response->status() === 401 || $response->status() === 419) {
                Log::warning('[HackViet] Session expired during searchKey, attempting re-login...');
                $loginResult = $this->login();
                if ($loginResult['success']) {
                    return $this->searchKey($keyValue);
                }
                return $loginResult;
            }

            return [
                'success' => false,
                'error' => 'API Error: ' . $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('[HackViet] searchKey failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Lỗi kết nối: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Tạo VIP Key qua API
     * @param string $username Username của user (sẽ được format thành key_prefix)
     * @param int $durationValue Số lượng thời gian (1, 7, 14, 21, 30, 3650)
     * @param string $durationType Loại thời gian (day)
     * @param int $deviceLimit Số thiết bị cho phép
     * @return array ['success' => bool, 'key' => string, 'error' => string]
     */
    public function createVipKey(string $username, int $durationValue, string $durationType = 'day', int $deviceLimit = 1): array
    {
        $cookies = $this->getCookies();
        
        if (empty($cookies)) {
            $loginResult = $this->login();
            if (!$loginResult['success']) {
                return $loginResult;
            }
            $cookies = $this->getCookies();
        }

        // Format username thành key_prefix (≤5 ký tự, không dấu, chữ hoa)
        $keyPrefix = $this->formatKeyPrefix($username);

        try {
            $response = Http::withHeaders($this->getHeaders($cookies))
                ->withCookies($cookies, parse_url($this->baseUrl, PHP_URL_HOST))
                ->timeout(30)
                ->post("{$this->baseUrl}/api/seller/keys/bulk", [
                    'game_id' => 1, // Hiện tại chỉ có 1 game
                    'duration_value' => $durationValue,
                    'duration_type' => $durationType,
                    'device_limit' => $deviceLimit,
                    'status' => 'active',
                    'is_vip' => true,
                    'quantity' => 1,
                    'key_prefix' => $keyPrefix,
                ]);

            // Cập nhật last activity
            Cache::put(self::CACHE_LAST_ACTIVITY, now(), now()->addHours(3));

            if ($response->successful()) {
                $data = $response->json();
                // Response: { message: "...", data: [{ key: "ONEJPXXVGTJ", ... }] }
                if (!empty($data['data']) && is_array($data['data']) && isset($data['data'][0]['key'])) {
                    return [
                        'success' => true,
                        'key' => $data['data'][0]['key'],
                        'data' => $data['data'][0],
                    ];
                }
                
                return [
                    'success' => false,
                    'error' => 'API không trả về key: ' . json_encode($data),
                ];
            }

            // Session expired? Thử login lại
            if ($response->status() === 401 || $response->status() === 419) {
                Log::warning('[HackViet] Session expired during createVipKey, attempting re-login...');
                $loginResult = $this->login();
                if ($loginResult['success']) {
                    return $this->createVipKey($username, $durationValue, $durationType, $deviceLimit);
                }
                return $loginResult;
            }

            return [
                'success' => false,
                'error' => 'API Error: ' . $response->status() . ' - ' . $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('[HackViet] createVipKey failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Lỗi kết nối: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Lấy chi tiết key (bao gồm danh sách thiết bị)
     * @param string $keyValue
     * @return array
     */
    public function getKeyDetails(string $keyValue): array
    {
        $cookies = $this->getCookies();
        if (empty($cookies)) {
            $this->login();
            $cookies = $this->getCookies();
        }

        try {
            $response = Http::withHeaders($this->getHeaders($cookies, 'seller/keys'))
                ->withCookies($cookies, parse_url($this->baseUrl, PHP_URL_HOST))
                ->timeout(30)
                ->get("{$this->baseUrl}/api/seller/keys", [
                    'search' => $keyValue,
                    'per_page' => 15,
                    'page' => 1,
                    'sort_field' => 'created_at',
                    'sort_direction' => 'desc',
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['data']) && is_array($data['data'])) {
                    foreach ($data['data'] as $item) {
                        if ($item['key'] === $keyValue) {
                            return [
                                'success' => true,
                                'data' => $item
                            ];
                        }
                    }
                }
                return ['success' => false, 'error' => 'Không tìm thấy thông tin key trên API'];
            }

            if ($response->status() === 401 || $response->status() === 419) {
                $this->login();
                return $this->getKeyDetails($keyValue);
            }

            return ['success' => false, 'error' => 'API Error: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error('[HackViet] getKeyDetails failed: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Lỗi kết nối: ' . $e->getMessage()];
        }
    }

    /**
     * Xóa từng thiết bị riêng lẻ
     * @param int $hackvietKeyId ID của key trên hệ thống HackViet
     * @param array $deviceIds Mảng các device_id cần xóa
     * @return array
     */
    public function deleteDevice(int $hackvietKeyId, array $deviceIds): array
    {
        $cookies = $this->getCookies();
        if (empty($cookies)) {
            $this->login();
            $cookies = $this->getCookies();
        }

        try {
            $response = Http::withHeaders($this->getHeaders($cookies, 'seller/keys'))
                ->withCookies($cookies, parse_url($this->baseUrl, PHP_URL_HOST))
                ->timeout(30)
                ->post("{$this->baseUrl}/api/seller/keys/{$hackvietKeyId}/devices/delete", [
                    'device_ids' => $deviceIds
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => $response->json('message') ?? 'Xóa thiết bị thành công',
                    'data' => $response->json('data')
                ];
            }

            if ($response->status() === 401 || $response->status() === 419) {
                $this->login();
                return $this->deleteDevice($hackvietKeyId, $deviceIds);
            }

            return ['success' => false, 'error' => 'API Error: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error('[HackViet] deleteDevice failed: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Lỗi kết nối: ' . $e->getMessage()];
        }
    }

    /**
     * Reset toàn bộ thiết bị của một key
     * @param int $hackvietKeyId ID của key trên hệ thống HackViet
     * @return array
     */
    public function resetDevices(int $hackvietKeyId): array
    {
        $cookies = $this->getCookies();
        if (empty($cookies)) {
            $this->login();
            $cookies = $this->getCookies();
        }

        try {
            $response = Http::withHeaders($this->getHeaders($cookies, 'seller/keys'))
                ->withCookies($cookies, parse_url($this->baseUrl, PHP_URL_HOST))
                ->timeout(30)
                ->post("{$this->baseUrl}/api/seller/keys/{$hackvietKeyId}/devices/reset");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => $response->json('message') ?? 'Reset thiết bị thành công'
                ];
            }

            if ($response->status() === 401 || $response->status() === 419) {
                $this->login();
                return $this->resetDevices($hackvietKeyId);
            }

            return ['success' => false, 'error' => 'API Error: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error('[HackViet] resetDevices failed: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Lỗi kết nối: ' . $e->getMessage()];
        }
    }

    /**
     * Format username thành key_prefix
     * - Bỏ dấu tiếng Việt
     * - Chỉ giữ chữ cái và số
     * - Tối đa 5 ký tự
     * - Chữ hoa
     */
    protected function formatKeyPrefix(string $username): string
    {
        // Bỏ dấu tiếng Việt
        $str = $this->removeVietnameseAccents($username);
        
        // Chỉ giữ chữ cái và số
        $str = preg_replace('/[^a-zA-Z0-9]/', '', $str);
        
        // Nếu rỗng thì dùng mặc định
        if (empty($str)) {
            $str = 'ONE';
        }
        
        // Tối đa 5 ký tự, chữ hoa
        return strtoupper(substr($str, 0, 5));
    }

    /**
     * Bỏ dấu tiếng Việt
     */
    protected function removeVietnameseAccents(string $str): string
    {
        $vietnameseMap = [
            'à'=>'a', 'á'=>'a', 'ạ'=>'a', 'ả'=>'a', 'ã'=>'a',
            'â'=>'a', 'ầ'=>'a', 'ấ'=>'a', 'ậ'=>'a', 'ẩ'=>'a', 'ẫ'=>'a',
            'ă'=>'a', 'ằ'=>'a', 'ắ'=>'a', 'ặ'=>'a', 'ẳ'=>'a', 'ẵ'=>'a',
            'è'=>'e', 'é'=>'e', 'ẹ'=>'e', 'ẻ'=>'e', 'ẽ'=>'e',
            'ê'=>'e', 'ề'=>'e', 'ế'=>'e', 'ệ'=>'e', 'ể'=>'e', 'ễ'=>'e',
            'ì'=>'i', 'í'=>'i', 'ị'=>'i', 'ỉ'=>'i', 'ĩ'=>'i',
            'ò'=>'o', 'ó'=>'o', 'ọ'=>'o', 'ỏ'=>'o', 'õ'=>'o',
            'ô'=>'o', 'ồ'=>'o', 'ố'=>'o', 'ộ'=>'o', 'ổ'=>'o', 'ỗ'=>'o',
            'ơ'=>'o', 'ờ'=>'o', 'ớ'=>'o', 'ợ'=>'o', 'ở'=>'o', 'ỡ'=>'o',
            'ù'=>'u', 'ú'=>'u', 'ụ'=>'u', 'ủ'=>'u', 'ũ'=>'u',
            'ư'=>'u', 'ừ'=>'u', 'ứ'=>'u', 'ự'=>'u', 'ử'=>'u', 'ữ'=>'u',
            'ỳ'=>'y', 'ý'=>'y', 'ỵ'=>'y', 'ỷ'=>'y', 'ỹ'=>'y',
            'đ'=>'d',
            'À'=>'A', 'Á'=>'A', 'Ạ'=>'A', 'Ả'=>'A', 'Ã'=>'A',
            'Â'=>'A', 'Ầ'=>'A', 'Ấ'=>'A', 'Ậ'=>'A', 'Ẩ'=>'A', 'Ẫ'=>'A',
            'Ă'=>'A', 'Ằ'=>'A', 'Ắ'=>'A', 'Ặ'=>'A', 'Ẳ'=>'A', 'Ẵ'=>'A',
            'È'=>'E', 'É'=>'E', 'Ẹ'=>'E', 'Ẻ'=>'E', 'Ẽ'=>'E',
            'Ê'=>'E', 'Ề'=>'E', 'Ế'=>'E', 'Ệ'=>'E', 'Ể'=>'E', 'Ễ'=>'E',
            'Ì'=>'I', 'Í'=>'I', 'Ị'=>'I', 'Ỉ'=>'I', 'Ĩ'=>'I',
            'Ò'=>'O', 'Ó'=>'O', 'Ọ'=>'O', 'Ỏ'=>'O', 'Õ'=>'O',
            'Ô'=>'O', 'Ồ'=>'O', 'Ố'=>'O', 'Ộ'=>'O', 'Ổ'=>'O', 'Ỗ'=>'O',
            'Ơ'=>'O', 'Ờ'=>'O', 'Ớ'=>'O', 'Ợ'=>'O', 'Ở'=>'O', 'Ỡ'=>'O',
            'Ù'=>'U', 'Ú'=>'U', 'Ụ'=>'U', 'Ủ'=>'U', 'Ũ'=>'U',
            'Ư'=>'U', 'Ừ'=>'U', 'Ứ'=>'U', 'Ự'=>'U', 'Ử'=>'U', 'Ữ'=>'U',
            'Ỳ'=>'Y', 'Ý'=>'Y', 'Ỵ'=>'Y', 'Ỷ'=>'Y', 'Ỹ'=>'Y',
            'Đ'=>'D',
        ];
        
        return strtr($str, $vietnameseMap);
    }

    /**
     * Login vào HackViet để lấy session mới
     */
    public function login(): array
    {
        if (empty($this->email) || empty($this->password)) {
            return [
                'success' => false,
                'error' => 'Thiếu thông tin đăng nhập HackViet trong .env',
            ];
        }

        try {
            // Bước 1: GET trang login để lấy CSRF token
            $loginPageResponse = Http::withHeaders([
                'User-Agent' => $this->getUserAgent(),
                'Accept' => 'text/html,application/xhtml+xml',
            ])->get("{$this->baseUrl}/login");

            $cookies = $loginPageResponse->cookies()->toArray();
            $cookieJar = [];
            foreach ($cookies as $cookie) {
                $cookieJar[$cookie['Name']] = $cookie['Value'];
            }

            // Extract XSRF-TOKEN từ response
            $xsrfToken = $cookieJar['XSRF-TOKEN'] ?? '';
            
            // Bước 2: POST login
            $loginResponse = Http::withHeaders([
                'User-Agent' => $this->getUserAgent(),
                'Accept' => 'application/json, text/plain, */*',
                'Content-Type' => 'application/json',
                'Origin' => $this->baseUrl,
                'Referer' => "{$this->baseUrl}/login",
                'X-XSRF-TOKEN' => urldecode($xsrfToken),
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->withCookies($cookieJar, parse_url($this->baseUrl, PHP_URL_HOST))
            ->post("{$this->baseUrl}/login", [
                'email' => $this->email,
                'password' => $this->password,
                'remember' => true,
            ]);

            if ($loginResponse->successful() || $loginResponse->status() === 302) {
                // Lấy cookies mới từ response
                $newCookies = $loginResponse->cookies()->toArray();
                foreach ($newCookies as $cookie) {
                    $cookieJar[$cookie['Name']] = $cookie['Value'];
                }

                $this->saveCookies($cookieJar);
                Log::info('[HackViet] Login successful');

                return [
                    'success' => true,
                    'message' => 'Đăng nhập thành công',
                ];
            }

            return [
                'success' => false,
                'error' => 'Login failed: ' . $loginResponse->status() . ' - ' . $loginResponse->body(),
            ];

        } catch (\Exception $e) {
            Log::error('[HackViet] Login failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Lỗi đăng nhập: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Keep-alive: Gửi request đơn giản để giữ session sống
     */
    public function keepAlive(): array
    {
        $cookies = $this->getCookies();
        
        if (empty($cookies)) {
            return $this->login();
        }

        try {
            // Gọi một endpoint nhẹ nhàng
            $response = Http::withHeaders([
                'User-Agent' => $this->getUserAgent(),
                'Accept' => 'application/json',
            ])
            ->withCookies($cookies, parse_url($this->baseUrl, PHP_URL_HOST))
            ->timeout(15)
            ->get("{$this->baseUrl}/api/user");

            Cache::put(self::CACHE_LAST_ACTIVITY, now(), now()->addHours(3));

            if ($response->successful()) {
                Log::info('[HackViet] Keep-alive ping successful');
                return ['success' => true, 'message' => 'Session alive'];
            }

            // Session expired, re-login
            if ($response->status() === 401 || $response->status() === 419) {
                Log::warning('[HackViet] Session expired during keep-alive, re-logging in...');
                return $this->login();
            }

            return ['success' => false, 'error' => 'Keep-alive failed: ' . $response->status()];

        } catch (\Exception $e) {
            Log::error('[HackViet] Keep-alive error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Kiểm tra xem có cần keep-alive không
     */
    public function needsKeepAlive(): bool
    {
        $lastActivity = Cache::get(self::CACHE_LAST_ACTIVITY);
        if (!$lastActivity) {
            return true;
        }

        $keepAliveMinutes = (int) \App\Helpers\ConfigHelper::get('HACKVIET_KEEP_ALIVE_MINUTES', 90);
        return now()->diffInMinutes($lastActivity) >= $keepAliveMinutes;
    }

    /**
     * Headers chuẩn cho API requests
     */
    protected function getHeaders(array $cookies = [], string $refererPath = 'free-key/oneone'): array
    {
        $xsrfToken = $cookies['XSRF-TOKEN'] ?? '';
        
        return [
            'Accept' => 'application/json, text/plain, */*',
            'Accept-Language' => 'vi-VN,vi;q=0.9,en-GB;q=0.8,en;q=0.7',
            'Connection' => 'keep-alive',
            'Content-Type' => 'application/json',
            'Origin' => $this->baseUrl,
            'Referer' => "{$this->baseUrl}/" . ltrim($refererPath, '/'),
            'User-Agent' => $this->getUserAgent(),
            'X-App-Locale' => 'vi',
            'X-Requested-With' => 'XMLHttpRequest',
            'X-XSRF-TOKEN' => urldecode($xsrfToken),
        ];
    }

    protected function getUserAgent(): string
    {
        return 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36';
    }
}
