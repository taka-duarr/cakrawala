<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE point_histories MODIFY COLUMN type ENUM('kebaikan', 'pelanggaran', 'transfer_in', 'transfer_out') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE point_histories MODIFY COLUMN type ENUM('kebaikan', 'pelanggaran') NOT NULL");
        }
    }
};
