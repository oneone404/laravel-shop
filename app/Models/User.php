<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'email',
        'google_id',
        'facebook_id',
        'role',
        'balance',
        'total_deposited',
        'banned',
        'ip_address',
        'email_verified_at',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_seen' => 'datetime',
    ];

    /**
     * Gửi thông báo đặt lại mật khẩu với token
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        try {
            $this->notify(new ResetPasswordNotification($token));
            Log::info('Đã gửi email đặt lại mật khẩu thành công', ['user_id' => $this->id, 'email' => $this->email]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi gửi email đặt lại mật khẩu', [
                'user_id' => $this->id,
                'email' => $this->email,
                'error' => $e->getMessage()
            ]);

            // Không ném ngoại lệ để không làm gián đoạn luồng người dùng
            // Người dùng vẫn có thể nhận được token thông qua URL trong trường hợp email không gửi được
        }
    }
    public function getMaxFreeSpinsPerDay()
    {
        if ($this->total_deposited >= 1000000) return 10;
        if ($this->total_deposited >= 500000) return 5;
        if ($this->total_deposited >= 300000) return 3;
        if ($this->total_deposited >= 150000) return 2;
        if ($this->total_deposited >= 50000) return 1;
        return 0;
    }
}
