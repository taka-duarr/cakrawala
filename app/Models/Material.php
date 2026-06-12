<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_session_id',
        'title',
        'description',
        'file_path',
    ];

    public function attendanceSession()
    {
        return $this->belongsTo(AttendanceSession::class);
    }
}
