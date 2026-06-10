<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusans')->nullOnDelete()->after('name');
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete()->after('jurusan_id');
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->nullOnDelete()->after('academic_year_id');
            $table->unsignedTinyInteger('grade_level')->nullable()->comment('Tingkat: 10, 11, 12')->after('semester_id');
        });
    }

    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['jurusan_id']);
            $table->dropForeign(['academic_year_id']);
            $table->dropForeign(['semester_id']);
            $table->dropColumn(['jurusan_id', 'academic_year_id', 'semester_id', 'grade_level']);
        });
    }
};
