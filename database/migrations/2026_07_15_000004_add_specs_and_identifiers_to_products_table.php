<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Flexible key/value spec rows (label + value), stored in display order.
            // JSON keeps it category-agnostic — dimensions/weight for furniture,
            // wattage/capacity for gadgets — without a column per attribute.
            $table->json('specifications')->nullable()->after('key_features');

            // Global trade identifiers for Google Merchant / Product schema.
            $table->string('gtin')->nullable()->after('sku');   // UPC/EAN/GTIN-8/12/13/14
            $table->string('mpn')->nullable()->after('gtin');   // Manufacturer Part Number
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['specifications', 'gtin', 'mpn']);
        });
    }
};
