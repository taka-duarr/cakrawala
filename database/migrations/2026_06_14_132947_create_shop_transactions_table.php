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
        Schema::create('shop_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('item_name');
            $table->integer('points_amount');
            $table->string('qr_token')->unique();
            $table->enum('status', ['pending', 'paid', 'expired'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_transactions');
    }
};
