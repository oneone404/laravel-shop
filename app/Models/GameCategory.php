<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameCategory extends Model
{
    use HasFactory;

    // ✅ Cho phép gán hàng loạt (mass assignable)
    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'description',
        'type',
        'price',      // 🔥 Thêm trường giá cho danh mục (đặc biệt là random)
        'active',
        'is_global',   // 🔥 Thêm trường này để hỗ trợ danh mục dùng chung
        'created_by',
    ];

    // ✅ Ép kiểu dữ liệu cho đúng
    protected $casts = [
        'active' => 'boolean',
        'is_global' => 'boolean',
    ];

    // ✅ Quan hệ: người tạo danh mục
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ✅ Quan hệ: danh mục có nhiều tài khoản
    public function accounts()
    {
        return $this->hasMany(GameAccount::class, 'game_category_id');
    }

    // ✅ Lấy tổng số tài khoản đã bán
    public function getSoldCountAttribute()
    {
        if ($this->type === 'random') {
            // Tổng sold_count từ các nhóm random + các bản ghi sold lẻ (nếu có từ hệ thống cũ)
            return (int) $this->accounts()->sum('sold_count') + 
                         (int) $this->accounts()->where('status', 'sold')->whereNull('accounts_data')->count();
        }
        return $this->accounts()->where('status', 'sold')->count();
    }

    // ✅ Lấy tổng số tài khoản còn lại
    public function getAvailableAccountAttribute()
    {
        if ($this->type === 'random') {
            // Tổng số lượng acc trong mảng accounts_data của các nhóm available
            $groups = $this->accounts()->where('status', 'available')->get();
            $total = 0;
            foreach ($groups as $group) {
                if (is_array($group->accounts_data)) {
                    $total += count($group->accounts_data);
                }
            }
            return $total;
        }
        return $this->accounts()->where('status', 'available')->count();
    }

    // ✅ Tạo scope cho phép lọc dễ hơn
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }

    public function scopeOwnedOrGlobal($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('created_by', $userId)->orWhere('is_global', true);
        });
    }
}
