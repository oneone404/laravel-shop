<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FreeKey;
use App\Services\HackVietService;

class FreeKeyController extends Controller
{
    /**
     * Hiển thị trang nhận key miễn phí
     * URL: /keyfree?token=XXXXXX
     */
    public function show(Request $request, HackVietService $hackVietService)
    {
        $token = $request->query('token');
        
        // Nếu không có token, kiểm tra key (backward compatible)
        if (empty($token)) {
            $key = $request->query('key');
            if (!empty($key)) {
                // Tìm key trong DB
                $freeKey = FreeKey::where('key_value', $key)->first();
                if ($freeKey) {
                    return $this->renderKeyPage($freeKey, $hackVietService);
                }
            }
            
            return view('user.keyfree', [
                'freeKey' => null,
                'error' => 'Không tìm thấy key! Link có thể đã hết hạn.',
            ]);
        }
        
        // Tìm key bằng token
        $freeKey = FreeKey::where('token', $token)->first();
        
        if (!$freeKey) {
            return view('user.keyfree', [
                'freeKey' => null,
                'error' => 'Link không hợp lệ hoặc đã hết hạn!',
            ]);
        }
        
        return $this->renderKeyPage($freeKey, $hackVietService);
    }
    
    /**
     * Render trang hiển thị key
     */
    private function renderKeyPage(FreeKey $freeKey, HackVietService $hackVietService)
    {
        // Kiểm tra key còn hạn không
        $isExpired = $freeKey->isExpired();
        
        // Lấy thông tin chi tiết từ API (devices, status, etc.)
        $devices = [];
        $deviceLimit = 1;
        $deviceCount = 0;
        $status = 'active';
        
        try {
            $keyDetails = $hackVietService->getKeyDetails($freeKey->key_value);
            
            if ($keyDetails['success'] && !empty($keyDetails['data'])) {
                $data = $keyDetails['data'];
                $devices = $data['devices'] ?? [];
                $deviceLimit = $data['device_limit'] ?? 1;
                $deviceCount = is_array($devices) ? count($devices) : 0;
                $status = $data['status'] ?? 'active';
                
                // Hiển thị hết hạn chỉ đến 23:59:59 cùng ngày
                if ($freeKey->expires_at === null || $freeKey->expires_at->format('H:i:s') !== '23:59:59') {
                    $baseDate = $freeKey->created_at_api ?: $freeKey->created_at;
                    $freeKey->expires_at = $baseDate->copy()->setTimezone('Asia/Ho_Chi_Minh')->endOfDay();
                    $freeKey->save();
                }
            }
        } catch (\Exception $e) {
            \Log::error('[FreeKey] Error getting key details: ' . $e->getMessage());
        }
        
        return view('user.keyfree', [
            'freeKey' => $freeKey,
            'key' => $freeKey->key_value,
            'isExpired' => $isExpired,
            'remainingSeconds' => $freeKey->getRemainingSeconds(),
            'remainingFormatted' => $freeKey->getRemainingTimeFormatted(),
            'devices' => $devices,
            'deviceLimit' => $deviceLimit,
            'deviceCount' => $deviceCount,
            'status' => $status,
            'error' => null,
        ]);
    }
}
