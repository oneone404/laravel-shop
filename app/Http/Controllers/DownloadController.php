<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameHack;

class DownloadController extends Controller
{
    // --- Giữ nguyên 2 hàm tiện ích bạn đã có ---
    private function latestByPattern(string $pattern): ?string
    {
        $dir = storage_path('app/public/downloads');
        $files = glob($dir . '/' . $pattern, GLOB_NOSORT);
        if (!$files) return null;

        $candidates = [];
        foreach ($files as $f) {
            $base = basename($f);
            if (preg_match('/(\d+(?:\.\d+){1,3})/u', $base, $m)) {
                $ver = $m[1];
            } else {
                $ver = '0.0.0';
            }
            $candidates[] = [
                'base'   => $base,
                'ver'    => $ver,
                'mtime'  => filemtime($f),
            ];
        }

        usort($candidates, function ($a, $b) {
            $vc = version_compare($b['ver'], $a['ver']); // desc
            return $vc !== 0 ? $vc : ($b['mtime'] <=> $a['mtime']);
        });

        return $candidates[0]['base'];
    }

    private function latestPublicUrl(string $pattern, string $cacheKey): ?string
    {
        $basename = cache()->remember($cacheKey, 300, function () use ($pattern) {
            return $this->latestByPattern($pattern);
        });
        if (!$basename) return null;

        return asset('storage/downloads/' . $basename);
    }

    // --- Map api_type -> URL file mới nhất ---
    private function latestUrlForApiType(string $apiType): ?string
    {
        return match ($apiType) {
            'com.vng.playtogether'    => $this->latestPublicUrl('PLAY_VNG_*.apk', 'latest_vng_apk'),
            'com.haegin.playtogether' => $this->latestPublicUrl('PLAY_GLOBAL_*.apk', 'latest_global_apk'),
            default                   => null,
        };
    }

    /**
     * Endpoint JSON: /downloads/latest/{hack}
     * - Check active (1/0)
     * - Trả JSON { url } nếu active và có file
     * - Nếu bảo trì: 409 để frontend hiện popup
     */
    public function latestByHack(GameHack $hack)
    {
        if ((int) $hack->active !== 1) {
            return response()->json([
                'error' => 'Game đang bảo trì'
            ], 409);
        }

        $url = $this->latestUrlForApiType($hack->api_type);
        if (!$url) {
            return response()->json([
                'error' => 'Không tìm thấy file phù hợp'
            ], 404);
        }

        return response()->json(['url' => $url], 200, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }
    public function activeDownloads()
    {
        // Gọi Apache server-status
        $status = @file_get_contents('http://127.0.0.1/server-status?auto');
        if (!$status) {
            return response()->json(['error' => 'Không lấy được dữ liệu Apache'], 500);
        }

        // Parse dòng BusyWorkers
        preg_match('/BusyWorkers:\s+(\d+)/', $status, $matches);
        $busy = $matches[1] ?? 0;

        return response()->json([
            'BusyWorkers' => (int) $busy
        ]);
    }
}