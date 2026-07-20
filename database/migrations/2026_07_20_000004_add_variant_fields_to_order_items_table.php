<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Snapshot the exact option bought so the order stays accurate even if
            // the product's colours/prices change later. price already snapshots
            // the variant price via the cart.
            $table->string('color')->nullable()->after('name');
            $table->string('size')->nullable()->after('color');
            $table->string('sku')->nullable()->after('size');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['color', 'size', 'sku']);
        });
    }
};
