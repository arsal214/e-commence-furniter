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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_number')->nullable()->unique()->after('status');
            $table->string('supplier_name')->nullable()->after('tracking_number');
            $table->string('supplier_order_id')->nullable()->after('supplier_name');
            $table->string('supplier_tracking')->nullable()->after('supplier_order_id');
            $table->string('carrier')->nullable()->after('supplier_tracking');
            $table->timestamp('shipped_at')->nullable()->after('carrier');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'supplier_name', 'supplier_order_id', 'supplier_tracking', 'carrier', 'shipped_at']);
        });
    }
};
