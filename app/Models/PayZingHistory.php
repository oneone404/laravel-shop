<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayZingHistory extends Model
{
    protected $table = 'payzing_histories';

    protected $fillable = [
        'user_id','service_id','package_id',
        'role_id','server',
        'provider','request_id','order_code','service_code','value','qty',
        'status',
        'card_serial','card_pin_enc',
        'provider_status','provider_message',
        'description','meta',
    ];

    // Ẩn mã PIN đã mã hoá khỏi JSON/log
    protected $hidden = ['card_pin_enc'];

    protected $casts = [
        'meta'             => 'array',
        'value'            => 'integer',
        'qty'              => 'integer',
        'provider_status'  => 'integer',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    // Mặc định meta là JSON rỗng
    protected $attributes = [
        'meta' => '[]',
    ];

    // Quan hệ (tuỳ chọn)
    public function user()         { return $this->belongsTo(User::class); }
    public function service()      { return $this->belongsTo(GameService::class, 'service_id'); }
    public function package()      { return $this->belongsTo(ServicePackage::class, 'package_id'); }

    // Scopes (tuỳ chọn)
    public function scopePending($q)     { return $q->where('status', 'pending'); }
    public function scopeOfRequest($q, $rid) { return $q->where('request_id', $rid); }
}
