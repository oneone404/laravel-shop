<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = "game_categories";
    protected $fillable = [
        'name',
        'thumbnail',
        'description',
        'active'
    ];
    // ✅ Quan hệ: danh mục có nhiều tài khoản
    public function accounts()
    {
        return $this->hasMany(GameAccount::class, 'game_category_id');
    }

    // ✅ Lấy tổng số tài khoản đã bán
    public function getSoldCountAttribute()
    {
        if ($this->type === 'random') {
            return (int) $this->accounts()->sum('sold_count') + 
                         (int) $this->accounts()->where('status', 'sold')->whereNull('accounts_data')->count();
        }
        return $this->accounts()->where('status', 'sold')->count();
    }

    // ✅ Lấy tổng số tài khoản còn lại
    public function getAvailableAccountAttribute()
    {
        if ($this->type === 'random') {
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
}
