<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyVip extends Model
{
    use HasFactory;

    protected $table = 'key_vips'; // Tên bảng trong cơ sở dữ liệu

    // Các cột có thể được gán giá trị
    protected $fillable = [
        'game',
        'key_value',
        'time_use',
        'device_limit',
        'price',
    ];
}
