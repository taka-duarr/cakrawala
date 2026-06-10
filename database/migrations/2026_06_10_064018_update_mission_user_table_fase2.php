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
        Schema::table('mission_user', function (Blueprint $table) {
            $table->string('status')->default('taken')->change();
            $table->text('proof_content')->nullable()->after('proof_url');
            $table->text('notes')->nullable()->after('proof_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mission_user', function (Blueprint $table) {
            $table->dropColumn(['proof_content', 'notes']);
        });
    }
};
