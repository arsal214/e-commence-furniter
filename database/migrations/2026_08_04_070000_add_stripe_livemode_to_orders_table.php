<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Records whether a Stripe order was taken against live keys or test keys.
 *
 * The value comes from the PaymentIntent's own `livemode` flag rather than from
 * inspecting the configured API key, because the key can be swapped later and
 * the question being asked is about the moment the payment was taken.
 *
 * Nullable on purpose, and it stays null for COD orders and for any Stripe order
 * placed before this column existed — "unknown" is the honest answer there, and
 * the admin badge renders it as such instead of guessing "live".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('stripe_livemode')->nullable()->after('stripe_payment_intent');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('stripe_livemode');
        });
    }
};
