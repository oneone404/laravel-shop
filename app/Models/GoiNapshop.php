<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoiNapshop extends Model
{
    protected $table = 'goi_napshop';

    protected $fillable = [
        'product_ID',
        'productName',
        'image',
        'price',
        'active',
    ];
}
