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
        Schema::table('products', function (Blueprint $table) {
            $table->string('supplier_name')->nullable()->after('sku');
            $table->string('supplier_url')->nullable()->after('supplier_name');
            $table->string('supplier_sku')->nullable()->after('supplier_url');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['supplier_name', 'supplier_url', 'supplier_sku']);
        });
    }
};
