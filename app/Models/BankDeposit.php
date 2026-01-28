<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDeposit extends Model
{
    use HasFactory;

    /**
     * Primary key kỹ thuật (auto increment)
     */
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Các cột cho phép mass assign
     */
    protected $fillable = [
        'transaction_id',     // ID từ ngân hàng (có thể trùng)
        'transaction_hash',   // HASH chống trùng (UNIQUE)
        'user_id',
        'account_number',
        'amount',
        'content',
        'bank',
    ];

    /**
     * Cast kiểu dữ liệu
     */
    protected $casts = [
        'amount' => 'decimal:0',
        'bank'   => 'string',
    ];

    /**
     * Quan hệ: giao dịch thuộc về user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * (OPTIONAL) Nếu bạn có bảng bank_accounts
     */
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'account_number', 'account_number');
    }

    /**
     * ❌ KHÔNG nên map transaction_id sang bảng khác
     * vì transaction_id không còn là khóa duy nhất
     */
    // public function transaction()
    // {
    //     return $this->belongsTo(Transaction::class, 'transaction_id');
    // }
}
