<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FreeKey extends Model
{
    protected $fillable = [
        'token',
        'key_value',
        'hackviet_key_id',
        'ip_address',
        'created_at_api',
        'expires_at',
        'duration_hours',
        'is_vip',
    ];

    protected $casts = [
        'created_at_api' => 'datetime',
        'expires_at' => 'datetime',
        'is_vip' => 'boolean',
    ];

    /**
     * Tạo token ngẫu nhiên unique
     */
    public static function generateToken(): string
    {
        do {
            $token = Str::random(32);
        } while (self::where('token', $token)->exists());
        
        return $token;
    }

    /**
     * Kiểm tra key còn hạn không
     */
    public function isExpired(): bool
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
        if (!$this->expires_at || $this->isExpired()) {
            return 0;
        }
        return now()->diffInSeconds($this->expires_at, false);
    }

    /**
     * Format thời gian còn lại
     */
    public function getRemainingTimeFormatted(): string
    {
        $seconds = $this->getRemainingSeconds();
        
        if ($seconds <= 0) {
            return 'Đã hết hạn';
        }
        
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        
        if ($hours > 0) {
            return "{$hours} giờ {$minutes} phút";
        }
        
        return "{$minutes} phút";
    }

    /**
     * Scope: Lọc key còn hạn
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope: Lọc theo IP trong 24h gần nhất
     */
    public function scopeByIpToday($query, string $ip)
    {
        return $query->where('ip_address', $ip)
                     ->where('created_at', '>=', now()->subHours(24));
    }
}
