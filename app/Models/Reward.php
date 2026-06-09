<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'name',
        'description',
        'points_cost',
        'category',
        'image',
        'is_available',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'reward_user')->withPivot('id', 'status')->withTimestamps();
    }
}
