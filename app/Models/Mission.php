<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    protected $fillable = ['title', 'description', 'points_reward', 'type', 'is_active'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('status', 'proof_url')->withTimestamps();
    }
}
