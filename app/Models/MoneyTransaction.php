<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class MoneyTransaction extends Model
{
    use HasFactory;
    protected $table = "money_transactions";
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'reference_id'
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function getFormattedTime()
{
    $now = Carbon::now();
    $diff = $this->created_at->diffInMinutes($now);

    if ($diff < 60) {
        return "$diff PHÚT TRƯỚC";
    } elseif ($diff < 1440) { // dưới 1 ngày
        $hours = $this->created_at->diffInHours($now);
        return "$hours GIỜ TRƯỚC";
    } else {
        return $this->created_at->format('d/m/Y H:i');
    }
}

}
