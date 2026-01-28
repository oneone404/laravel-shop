<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyPurchaseHistory extends Model
{
    use HasFactory;

    // Khai báo tên bảng (nếu khác tên mặc định)
    protected $table = 'key_purchase_history';

    // Các trường có thể được gán giá trị
    protected $fillable = [
        'user_id',
        'game',
        'key_value',
        'device_count',
        'time_use',
        'price',
        'reset_count',
        'created_at',
        'updated_at'
    ];
}
