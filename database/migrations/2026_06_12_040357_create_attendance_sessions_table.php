<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teaching_assignment_id')->constrained('teaching_assignments')->cascadeOnDelete();
            $table->integer('meeting_number');
            $table->date('session_date');
            $table->dateTime('deadline');
            $table->string('mode')->default('button_location'); // qr_location, button_location
            $table->foreignId('school_location_id')->nullable()->constrained('school_locations')->nullOnDelete();
            $table->string('qr_token')->unique();
            $table->string('status')->default('open'); // open, closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
