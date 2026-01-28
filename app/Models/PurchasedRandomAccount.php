<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedRandomAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seller_id',
        'game_account_id',
        'account_name',
        'password',
        'price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gameAccount()
    {
        return $this->belongsTo(GameAccount::class, 'game_account_id');
    }
}
