<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'discount_type',
        'discount_value',
        'applicable_to',
        'min_amount',
        'max_discount',
        'used_count',
        'expires_at', // 🆕 thêm vào đây
    ];

    protected $dates = [
        'expires_at', // để Laravel tự cast sang Carbon
    ];
}
