<?php

// app/Models/UgPhoneHistory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UgPhoneHistory extends Model
{
    protected $table = 'ug_phone_histories';
    protected $fillable = ['user_id', 'sever', 'hansudung', 'price', 'cauhinh'];
}
