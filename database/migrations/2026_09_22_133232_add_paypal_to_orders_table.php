<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', ['cod', 'stripe', 'paypal'])->default('cod')->change();
            $table->string('paypal_order_id')->nullable()->after('stripe_livemode');
            // Mirrors stripe_livemode: sandbox vs. live, read from config at the
            // moment the order was placed rather than at read time.
            $table->boolean('paypal_livemode')->nullable()->after('paypal_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['paypal_order_id', 'paypal_livemode']);
            $table->enum('payment_method', ['cod', 'stripe'])->default('cod')->change();
        });
    }
};
