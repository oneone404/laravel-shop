<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class FishIdController extends Controller
{
    public function index()
    {
        $cacheKey  = 'fish_id_data';
        $cacheTTL  = 60;
        $backupFile = 'fish_backup.json';

        /**
         * ================== LOAD DATA (CACHE + FILE FALLBACK) ==================
         */
        $fishList = Cache::remember($cacheKey, $cacheTTL, function () use ($backupFile) {
            try {
                $response = Http::timeout(10)->get('https://hackviet.io/api/fish');

                if ($response->successful()) {
                    $data = [
                        'updated_at' => now()->toDateTimeString(),
                        'items' => $response->json(),
                    ];

                    Storage::put(
                        $backupFile,
                        json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                    );

                    return $data;
                }
            } catch (\Throwable $e) {
                // API lỗi → fallback file
            }

            // Fallback file JSON
            if (Storage::exists($backupFile)) {
                return json_decode(Storage::get($backupFile), true);
            }

            return [];
        });

        $lastUpdated = $fishList['updated_at'] ?? null;
        $fishList = $fishList['items'] ?? [];

        /**
         * ================== BUILD MAP ==================
         */
        $fishMap    = [];
        $trashMap   = [];
        $rainbowMap = [];
        $purpleMap  = [];
        $maxTypes   = 0;

        foreach ($fishList as $item) {
            $id       = $item['id'] ?? null;
            $name     = $item['name'] ?? '...';
            $grade    = $item['grade'] ?? 1;
            $itemType = $item['ItemType'] ?? null;

            if (!$id) continue;

            // ================== CÁ ==================
            if ($itemType == 17) {
                $uniqueKey = $id . '|' . $name . '|' . $grade;

                $fishMap[$id][$uniqueKey] = [
                    'name'  => $name,
                    'grade' => $grade,
                ];

                $maxTypes = max($maxTypes, count($fishMap[$id]));
            }

            // ================== RÁC ==================
            elseif ($itemType == 9) {
                $uniqueKey = $id . '|' . $name . '|' . $grade;

                $trashMap[$id][$uniqueKey] = [
                    'name'  => $name,
                    'grade' => $grade,
                ];

                $maxTypes = max($maxTypes, count($trashMap[$id]));
            }

            // ================== CẦU VỒNG ==================
            if ($grade == 5) {
                $uniqueKey = $id . '|' . $name;

                $rainbowMap[$id][$uniqueKey] = [
                    'name'  => $name,
                    'grade' => $grade,
                ];
            }

            // ================== TÍM ==================
            if ($grade == 4) {
                $uniqueKey = $id . '|' . $name;

                $purpleMap[$id][$uniqueKey] = [
                    'name'  => $name,
                    'grade' => $grade,
                ];
            }
        }

        /**
         * ================== RESET KEY (KHÔNG ẢNH HƯỞNG VIEW) ==================
         */
        foreach ($fishMap as $id => $items) {
            $fishMap[$id] = array_values($items);
        }

        foreach ($trashMap as $id => $items) {
            $trashMap[$id] = array_values($items);
        }

        foreach ($rainbowMap as $id => $items) {
            $rainbowMap[$id] = array_values($items);
        }

        foreach ($purpleMap as $id => $items) {
            $purpleMap[$id] = array_values($items);
        }

        ksort($fishMap);
        ksort($trashMap);
        ksort($rainbowMap);
        ksort($purpleMap);

        /**
         * ================== RETURN VIEW ==================
         */
        return view('user.id-fish', compact(
            'fishMap',
            'trashMap',
            'rainbowMap',
            'purpleMap',
            'maxTypes',
            'lastUpdated'
        ));
    }
}
