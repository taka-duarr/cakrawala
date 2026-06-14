<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_transactions', function (Blueprint $table) {
            // JSON array of cart items: [{id, name, qty, points_price, subtotal}]
            $table->json('cart_items')->nullable()->after('item_name');
        });
    }

    public function down(): void
    {
        Schema::table('shop_transactions', function (Blueprint $table) {
            $table->dropColumn('cart_items');
        });
    }
};
