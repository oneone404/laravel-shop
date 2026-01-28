<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_category_id',
        'account_name',
        'password',
        'price',
        'status',
        'server',
        'registration_type',
        'planet',
        'earring',
        'note',
        'thumb',
        'images',
        'accounts_data',
        'created_by',
        'buyer_id',
        'views',
        'sold_count'
    ];

    protected $casts = [
        'accounts_data' => 'array',
        'images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(GameCategory::class, 'game_category_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
