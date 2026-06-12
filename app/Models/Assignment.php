<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_session_id',
        'title',
        'description',
        'file_path',
        'points_reward',
        'deadline',
    ];

    protected $casts = [
        'points_reward' => 'integer',
        'deadline' => 'datetime',
    ];

    public function attendanceSession()
    {
        return $this->belongsTo(AttendanceSession::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
