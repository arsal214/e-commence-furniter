<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('supplier_cost', 10, 2)->nullable()->after('sale_price');
            $table->unsignedTinyInteger('roi_percent')->nullable()->after('supplier_cost');
            $table->unsignedTinyInteger('sale_discount_percent')->nullable()->after('roi_percent');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['supplier_cost', 'roi_percent', 'sale_discount_percent']);
        });
    }
};
