<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameHack extends Model
{
    protected $table = 'game_hacks';

    protected $fillable = [
        'name',
        'version',
        'description',
        'logo',
        'thumbnail',
        'download_link',
        'download_link_global',
        'api_hack',
        'api_type',
        'solink',
        'active',
        'platform',
        'size',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
        // (tuỳ chọn) cast active
        // 'active' => 'boolean',
    ];
    // App\Models\GameHack.php
    public function keys()
    {
        return $this->hasMany(KeyVip::class, 'game', 'name');
        // game trong KeyVip lưu tên game
    }
}
