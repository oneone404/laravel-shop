<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ToolController extends Controller
{
    /**
     * Show the tools hub page.
     */
    public function index()
    {
        return view('user.apps.index');
    }

    /**
     * Show the gift code entry tool.
     */
    public function giftCode()
    {
        return view('user.apps.gift-code');
    }

    /**
     * Show the Fish ID list tool.
     */
    public function fishId()
    {
        $cacheKey = 'fish_id_data';
        $cacheTTL = 60;
        $backupFile = 'fish_backup.json';

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
                // API lỗi
            }

            if (Storage::exists($backupFile)) {
                return json_decode(Storage::get($backupFile), true);
            }

            return [];
        });

        $lastUpdated = $fishList['updated_at'] ?? null;
        $fishList = $fishList['items'] ?? [];

        $fishMap = [];
        $trashMap = [];
        $rainbowMap = [];
        $purpleMap = [];
        $maxTypes = 0;

        foreach ($fishList as $item) {
            $id = $item['id'] ?? null;
            $name = $item['name'] ?? '...';
            $grade = $item['grade'] ?? 1;
            $itemType = $item['ItemType'] ?? null;

            if (!$id)
                continue;

            if ($itemType == 17) {
                $uniqueKey = $id . '|' . $name . '|' . $grade;
                $fishMap[$id][$uniqueKey] = ['name' => $name, 'grade' => $grade];
                $maxTypes = max($maxTypes, count($fishMap[$id]));
            } elseif ($itemType == 9) {
                $uniqueKey = $id . '|' . $name . '|' . $grade;
                $trashMap[$id][$uniqueKey] = ['name' => $name, 'grade' => $grade];
                $maxTypes = max($maxTypes, count($trashMap[$id]));
            }

            if ($grade == 5) {
                $uniqueKey = $id . '|' . $name;
                $rainbowMap[$id][$uniqueKey] = ['name' => $name, 'grade' => $grade];
            }
            if ($grade == 4) {
                $uniqueKey = $id . '|' . $name;
                $purpleMap[$id][$uniqueKey] = ['name' => $name, 'grade' => $grade];
            }
        }

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

        return view('user.apps.fish-id', compact(
            'fishMap',
            'trashMap',
            'rainbowMap',
            'purpleMap',
            'maxTypes',
            'lastUpdated'
        ));
    }

    /**
     * Show the Fake ID / Region ID tool.
     */
    public function fakeId()
    {
        $fakeIds = [
            ['id' => 503, 'name' => 'Vòi rồng'],
            ['id' => 508, 'name' => 'Dấu vết quái vật biển sâu ( Đang bị game fix không dùng được )'],
            ['id' => 509, 'name' => 'Dấu vết của Cá voi Lyngbakr'],
            ['id' => 510, 'name' => 'Dấu vết của Nauthveli'],
            ['id' => 511, 'name' => 'Dấu vết của Skelljunger'],
            ['id' => 512, 'name' => 'Dấu vết của Horosvalur'],
            ['id' => 513, 'name' => 'Dấu vết của Taumafiskur'],
            ['id' => 514, 'name' => 'Dấu vết của Sverdvalur'],
            ['id' => 515, 'name' => 'Dấu vết của Muspelheim'],
            ['id' => 516, 'name' => 'Dấu vết của Katthveli'],
            ['id' => 517, 'name' => 'Đàn cá đột biến'],
            ['id' => null, 'name' => 'Đang Thu Thập Thêm...'],
        ];

        usort($fakeIds, function ($a, $b) {
            if (is_null($a['id']))
                return 1;
            if (is_null($b['id']))
                return -1;
            return $a['id'] <=> $b['id'];
        });

        return view('user.apps.fake-id', compact('fakeIds'));
    }
}
