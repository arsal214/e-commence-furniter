<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // A persistent secret for the customer's pay link. Not derived from the
            // order id or tracking number — both of those are known to anyone who
            // has seen the order, and this token is the only thing guarding the
            // payment page.
            $table->string('payment_token', 64)->nullable()->unique()->after('stripe_livemode');
            $table->timestamp('payment_requested_at')->nullable()->after('payment_token');
            $table->unsignedSmallInteger('payment_request_count')->default(0)->after('payment_requested_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['payment_token']);
            $table->dropColumn(['payment_token', 'payment_requested_at', 'payment_request_count']);
        });
    }
};
