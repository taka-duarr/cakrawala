<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'points',
        'jurusan_id',
        'academic_year_id',
        'semester_id',
        'grade_level',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(User::class)->whereHas('role', function($q) {
            $q->where('name', 'siswa');
        });
    }

    public function waliKelas()
    {
        return $this->hasOne(User::class)->whereHas('role', function($q) {
            $q->where('name', 'walikelas');
        });
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}

