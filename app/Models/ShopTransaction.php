<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopTransaction extends Model
{
    protected $fillable = [
        'shop_user_id',
        'student_user_id',
        'item_name',
        'cart_items',
        'points_amount',
        'qr_token',
        'status',
        'paid_at',
        'expires_at',
    ];

    protected $casts = [
        'paid_at'    => 'datetime',
        'expires_at' => 'datetime',
        'cart_items' => 'array',
    ];

    public function shop()
    {
        return $this->belongsTo(User::class, 'shop_user_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
