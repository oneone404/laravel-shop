<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HackVietConfigSeeder extends Seeder
{
    public function run()
    {
        $configs = [
            ['key' => 'KEY_MODE', 'value' => 'api'],
            ['key' => 'HACKVIET_EMAIL', 'value' => 'acckidphong02@gmail.com'],
            ['key' => 'HACKVIET_PASSWORD', 'value' => 'Oneone111@'],
            ['key' => 'HACKVIET_BASE_URL', 'value' => 'https://hackviet.io'],
            ['key' => 'HACKVIET_SHOP_SLUG', 'value' => 'shop-82-kcvara'],
            ['key' => 'HACKVIET_GAME_SLUG', 'value' => 'play-together'],
            ['key' => 'HACKVIET_KEEP_ALIVE_MINUTES', 'value' => '90'],
            ['key' => 'XLINK_API_URL', 'value' => 'https://xlink.co/api'],
            ['key' => 'XLINK_API_TOKEN', 'value' => 'cecd5bf3-4657-48d7-8f5a-db237e39b292'],
        ];

        foreach ($configs as $config) {
            DB::table('configs')->updateOrInsert(
                ['key' => $config['key']],
                [
                    'value' => $config['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        echo "✅ Đã thêm " . count($configs) . " configs vào database!\n";
    }
}
