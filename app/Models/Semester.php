<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = ['academic_year_id', 'name', 'is_active'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function teachingAssignments()
    {
        return $this->hasMany(TeachingAssignment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
