<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardZingDeposit extends Model
{
    protected $table = 'cardzing_deposit';

    protected $fillable = [
        'cardSerial',
        'cardPassword',
        'status',
    ];
}
