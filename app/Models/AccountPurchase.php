<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountPurchase extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account_purchases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'game_account_id',
        'amount',
        'account_details',
    ];

    /**
     * Get the user that owns the purchase.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the account that was purchased.
     */
    public function account()
    {
        return $this->belongsTo(GameAccount::class, 'game_account_id');
    }
}
