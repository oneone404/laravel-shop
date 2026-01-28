<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DirectOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'guest_session',
        'guest_ip',
        'order_type',
        'category_id',
        'item_id',
        'group_id',
        'quantity',
        'amount',
        'payment_content',
        'status',
        'account_data',
        'bank_transaction_id',
        'paid_at',
        'completed_at',
        'expires_at',
    ];

    protected $casts = [
        'account_data' => 'array',
        'amount' => 'decimal:0',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_COMPLETED = 'completed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    // Order type constants
    const TYPE_ACCOUNT = 'account';
    const TYPE_RANDOM = 'random_account';

    /**
     * Generate unique order code
     */
    public static function generateOrderCode(): string
    {
        do {
            // Format: DH + timestamp (last 6 digits) + random 4 chars
            $code = 'DH' . substr(time(), -6) . strtoupper(Str::random(4));
        } while (self::where('order_code', $code)->exists());

        return $code;
    }

    /**
     * Generate payment content from order code
     */
    public static function generatePaymentContent(string $orderCode): string
    {
        return 'DONHANG' . $orderCode;
    }

    /**
     * Check if order is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if order is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if order can be paid (pending and not expired)
     */
    public function canBePaid(): bool
    {
        return $this->isPending() && !$this->isExpired();
    }

    /**
     * Get remaining seconds before expiration
     */
    public function getRemainingSeconds(): int
    {
        if (!$this->expires_at || $this->isExpired()) {
            return 0;
        }
        return now()->diffInSeconds($this->expires_at, false);
    }

    /**
     * Mark as expired
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => self::STATUS_EXPIRED]);
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(string $bankTransactionId = null): void
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'bank_transaction_id' => $bankTransactionId,
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark as completed with account data
     */
    public function markAsCompleted(array $accountData): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'account_data' => $accountData,
            'completed_at' => now(),
        ]);
    }

    /**
     * Check if the linked account/group is still available.
     * If not, mark as cancelled.
     */
    public function checkAvailability(): bool
    {
        if (!$this->isPending()) return true;

        if ($this->order_type === self::TYPE_ACCOUNT) {
            $account = $this->account;
            if (!$account || $account->status !== 'available') {
                $this->update(['status' => self::STATUS_CANCELLED]);
                return false;
            }
        } else if ($this->order_type === self::TYPE_RANDOM) {
            $group = $this->randomGroup;
            if (!$group || $group->status !== 'available' || count($group->accounts_data ?? []) < $this->quantity) {
                $this->update(['status' => self::STATUS_CANCELLED]);
                return false;
            }
        }

        return true;
    }

    // ========== Relationships ==========

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(GameCategory::class, 'category_id');
    }

    public function account()
    {
        return $this->belongsTo(GameAccount::class, 'item_id');
    }

    public function randomGroup()
    {
        return $this->belongsTo(GameAccount::class, 'group_id');
    }

    // ========== Scopes ==========

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeNotExpired($query)
    {
        return $query->where('expires_at', '>', now());
    }

    public function scopePendingAndNotExpired($query)
    {
        return $query->pending()->notExpired();
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Get pending orders for current user or guest
     */
    public static function getCurrentPendingOrders()
    {
        $query = self::pendingAndNotExpired();
        
        if (auth()->check()) {
            // Nếu đã đăng nhập, ưu tiên theo user_id
            $query->where('user_id', auth()->id());
        } else {
            // Nếu chưa đăng nhập, theo session
            $query->where('guest_session', session()->getId())
                  ->whereNull('user_id');
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }
}
