<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Chỉ nhận POST
        if (!$request->isMethod('post')) {
            return response()->json(['ok' => false, 'error' => 'method not allowed'], 405);
        }

        // Lấy input (Laravel tự merge form + JSON)
        $in = $request->all();

        $partnerId = (string)($in['partner_id'] ?? '');
        $command   = (string)($in['command'] ?? '');
        $requestId = (string)($in['request_id'] ?? '');
        $status    = (int)   ($in['status'] ?? -1);
        $sign      = (string)($in['sign'] ?? '');
        $message   = (string)($in['message'] ?? '');

        // Nếu data là chuỗi JSON -> decode
        $data = $in['data'] ?? null;
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $in['data'] = $data = $decoded;
            }
        }

        // Tính chữ ký mong đợi
        $expected = md5(config('card.partner_key') . $partnerId . $command . $requestId);

        // ---- GHI LOG THÔ (đã mask) để soi format thật lần sau ----
        $logFile = __DIR__ . '/card_callback.log';
        $masked  = $this->maskSensitive($in);
        $raw     = $request->getContent();
        $headers = [
            'content-type'      => $request->header('content-type'),
            'user-agent'        => $request->header('user-agent'),
            'x-forwarded-for'   => $request->header('x-forwarded-for'),
            'cf-connecting-ip'  => $request->header('cf-connecting-ip'),
        ];
        $logLine = sprintf(
            "[%s] IP=%s METHOD=%s\nSIGN_EXPECTED=%s SIGN_SENT=%s\nHEADERS=%s\nPAYLOAD=%s\nRAW=%s\n%s\n",
            now()->toDateTimeString(),
            $request->ip(),
            $request->method(),
            $expected,
            $sign,
            json_encode($headers, JSON_UNESCAPED_UNICODE),
            json_encode($masked, JSON_UNESCAPED_UNICODE),
            $raw,
            str_repeat('-', 80)
        );
        // Bắt lỗi ghi file để tránh chặn callback
        try {
            file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
        } catch (\Throwable $e) {
            // fallback: không làm gì, bạn có thể \Log::warning(...) nếu muốn
        }
        // ----------------------------------------------------------

        // Verify chữ ký
        if (!hash_equals($expected, $sign)) {
            return response()->json(['ok' => false, 'error' => 'invalid signature'], 401);
        }

        // TODO: IP whitelist nếu NCC cung cấp
        // TODO: tìm payzing_histories theo request_id, cập nhật status:
        //  - status=1: success, lưu serial + pin (ENCRYPT!), description=message
        //  - status=2: pending (chờ redownload)
        //  - khác: error

        return response()->json(['ok' => true]);
    }

    private function maskSensitive($payload)
    {
        // Đệ quy mask các key nhạy cảm
        $sensitiveKeys = ['pin','card_pin','cardPassword','card_password','code','cardCode','serial','card_serial','cardSeri'];
        $mask = function ($v) {
            $s = (string)$v; $n = strlen($s);
            if ($n <= 6) return str_repeat('*', $n);
            return substr($s,0,3) . str_repeat('*', $n-6) . substr($s,-3);
        };

        $walk = function ($item) use (&$walk, $sensitiveKeys, $mask) {
            if (is_array($item)) {
                $out = [];
                foreach ($item as $k => $v) {
                    if (in_array($k, $sensitiveKeys, true) && is_scalar($v)) {
                        $out[$k] = $mask($v);
                    } else {
                        $out[$k] = $walk($v);
                    }
                }
                return $out;
            }
            return $item;
        };

        return $walk($payload);
    }
}
