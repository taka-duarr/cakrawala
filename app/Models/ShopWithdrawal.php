<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_user_id',
        'points_amount',
        'status',
        'admin_notes',
    ];

    public function shop()
    {
        return $this->belongsTo(User::class, 'shop_user_id');
    }
}
