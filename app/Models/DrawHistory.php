<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrawHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'daily',
        'ip_address',
        'device_id',
        'user_agent',
    ];


    protected $dates = ['daily'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
