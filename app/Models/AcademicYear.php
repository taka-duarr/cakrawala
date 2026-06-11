<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = ['name', 'is_active'];

    public function semesters()
    {
        return $this->hasMany(Semester::class);
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
