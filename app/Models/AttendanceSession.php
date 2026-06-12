<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'teaching_assignment_id',
        'meeting_number',
        'session_date',
        'deadline',
        'mode',
        'school_location_id',
        'qr_token',
        'status',
    ];

    protected $casts = [
        'session_date' => 'date',
        'deadline' => 'datetime',
        'meeting_number' => 'integer',
    ];

    public function teachingAssignment()
    {
        return $this->belongsTo(TeachingAssignment::class);
    }

    public function schoolLocation()
    {
        return $this->belongsTo(SchoolLocation::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
