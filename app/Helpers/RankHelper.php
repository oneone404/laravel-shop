<?php

namespace App\Helpers;

class RankHelper
{
    public static function getUserRank($totalDeposited)
    {
        if ($totalDeposited < 100000) {
            return ['name' => 'Thành Viên Mới', 'image' => 'images/rank/dong.png']; 
        } elseif ($totalDeposited < 300000) {
            return ['name' => 'Thành Viên Bạc', 'image' => 'images/rank/bac.png'];
        } elseif ($totalDeposited < 1000000) {
            return ['name' => 'Thành Viên Vàng', 'image' => 'images/rank/vang.png'];
        } elseif ($totalDeposited < 2000000) {
            return ['name' => 'Thành Viên Bạch Kim', 'image' => 'images/rank/bachkim.png'];
        } elseif ($totalDeposited < 5000000) {
            return ['name' => 'Thành Viên Kim Cương', 'image' => 'images/rank/kimcuong.png'];
        } else {
            return ['name' => 'Thành Viên Huyền Thoại', 'image' => 'images/rank/huyenthoai.png'];
        }
    }
}
