<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class FakeIdController extends Controller
{
    public function index()
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

        // Sort tăng dần theo ID, null sẽ đẩy xuống cuối
        usort($fakeIds, function ($a, $b) {
            if (is_null($a['id'])) return 1;
            if (is_null($b['id'])) return -1;
            return $a['id'] <=> $b['id'];
        });

        return view('user.id-fake', compact('fakeIds'));
    }
}
