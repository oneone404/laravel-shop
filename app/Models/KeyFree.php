<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyFree extends Model
{
    use HasFactory;

    protected $table = 'key_free';

    protected $fillable = [
        'key_value',
        'game',
        'time_use',
        'created_at',
    ];
}
