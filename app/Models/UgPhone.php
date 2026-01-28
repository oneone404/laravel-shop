<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UgPhone extends Model
{
    protected $table = 'ug_phone'; // thêm dòng này
    protected $fillable = ['code', 'sever', 'hansudung', 'price', 'cauhinh'];
}
