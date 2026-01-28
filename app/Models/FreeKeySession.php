<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FreeKeySession extends Model
{
    protected $fillable = [
        'token',
        'game_hack_id',
        'short_url',
        'ip_address',
        'client_id',
        'status',
        'hackviet_session_code',
        'key_value',
        'hackviet_key_id',
        'expires_at',
        'activated_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'activated_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVATED = 'activated';
    const STATUS_EXPIRED = 'expired';

    /**
     * Tạo token dạng UUID unique
     */
    public static function generateToken(): string
    {
        do {
            $token = Str::uuid()->toString();
        } while (self::where('token', $token)->exists());
        
        return $token;
    }

    /**
     * Kiểm tra session còn valid không (pending và chưa quá 30 phút)
     */
    public function isValid(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }
        
        // Session có hiệu lực đến hết ngày hôm nay (23:59:59)
        return $this->created_at->isToday();
    }

    /**
     * Kiểm tra đã activate chưa
     */
    public function isActivated(): bool
    {
        return $this->status === self::STATUS_ACTIVATED;
    }

    /**
     * Scope: Lọc session pending
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: Lọc theo IP trong 1 phút gần nhất (rate limiting)
     */
    public function scopeByIpRecent($query, string $ip, int $minutes = 1)
    {
        return $query->where('ip_address', $ip)
                     ->where('created_at', '>=', now()->subMinutes($minutes));
    }

    /**
     * Kiểm tra key còn hạn không
     */
    public function isKeyExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        return $this->expires_at->isPast();
    }

    /**
     * Lấy thời gian còn lại (seconds)
     */
    public function getRemainingSeconds(): int
    {
        if (!$this->expires_at || $this->isKeyExpired()) {
            return 0;
        }
        return now()->diffInSeconds($this->expires_at, false);
    }
}
