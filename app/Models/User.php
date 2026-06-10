<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'current_level',
        'classroom_id',
        'is_active',
        'points',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function missions()
    {
        return $this->belongsToMany(Mission::class)->withPivot('status', 'proof_url', 'proof_content', 'notes')->withTimestamps();
    }

    public function children()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'parent_id', 'student_id')->withTimestamps();
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id')->withTimestamps();
    }

    public function claimedRewards()
    {
        return $this->belongsToMany(Reward::class, 'reward_user')->withPivot('id', 'status')->withTimestamps();
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'achievement_user')->withTimestamps();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
}
