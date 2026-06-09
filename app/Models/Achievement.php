<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'criteria',
        'icon',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'achievement_user')->withTimestamps();
    }
}
