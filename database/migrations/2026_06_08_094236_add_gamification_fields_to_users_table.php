<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->integer('points_kebaikan')->default(0);
            $table->integer('points_pelanggaran')->default(0);
            $table->string('current_level')->default('Pemula');
            $table->string('class_name')->nullable(); // e.g. "X IPA 1"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'points_kebaikan', 'points_pelanggaran', 'current_level', 'class_name']);
        });
    }
};
