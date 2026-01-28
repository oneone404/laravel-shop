<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AddCardController extends Controller
{
    private const API_URL       = 'https://accone.vn/api/nap-zing/insert-card';
    private const API_COUNT_URL = 'https://accone.vn/api/nap-zing/available-count';

    public function index()
    {
        // Trang form nhập thẻ
        return view('admin.add-card');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cards' => ['required', 'string', 'min:3'],
        ], [
            'cards.required' => 'Vui Lòng Nhập Dữ Liệu',
        ]);

        $text = trim($request->input('cards', ''));
        $lines = preg_split("/\r\n|\n|\r/", $text);

        // Regex: Số Seri: ABC123 - Mã nạp: XYZ456
        $pattern = '/Số\s*Seri:\s*([A-Z0-9]+)\s*-\s*Mã\s*nạp:\s*([A-Z0-9]+)/i';

        $results = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            if (preg_match($pattern, $line, $m)) {
                $serial   = strtoupper($m[1]);
                $password = strtoupper($m[2]);
                $results[] = $this->submitCard($serial, $password);
            } else {
                $results[] = "⚠️ Không parse được dòng: {$line}";
            }
        }

        return back()->with([
            'results' => $results,
            'old_cards' => $text,
        ]);
    }

    public function checkCount(Request $request)
    {
        try {
            $resp = Http::timeout(10)
                ->acceptJson()
                ->get(self::API_COUNT_URL);

            if ($resp->successful() && Str::startsWith($resp->header('Content-Type', ''), 'application/json')) {
                $json = $resp->json();
                if (data_get($json, 'success') === true) {
                    $count = (int) data_get($json, 'data.available_cards', 0);
                    return response()->json(['ok' => true, 'count' => $count]);
                }
            }
            return response()->json(['ok' => false, 'message' => 'Không lấy được số thẻ.'], 422);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    private function submitCard(string $serial, string $password): string
    {
        $payload = [
            'cardSerial'   => $serial,
            'cardPassword' => $password,
        ];

        try {
            $resp = Http::timeout(10)
                ->acceptJson()
                ->asJson()
                ->post(self::API_URL, $payload);

            $status = 'FALSE';

            if (Str::startsWith($resp->header('Content-Type', ''), 'application/json')) {
                $json = $resp->json();
                $status = data_get($json, 'success') ? 'SUCCESS' : 'FALSE';
            } else {
                $status = 'FALSE';
            }

            return "{$serial} | {$password} | {$status}";
        } catch (\Throwable $e) {
            return "{$serial} | {$password} | ERROR: " . $e->getMessage();
        }
    }
}
