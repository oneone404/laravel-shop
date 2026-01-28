<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeyReceiveHistory extends Model
{
    protected $fillable = ['user_id', 'key_value', 'ip_address'];
}
