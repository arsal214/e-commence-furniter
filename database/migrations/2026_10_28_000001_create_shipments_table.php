<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One parcel per seller per order: what the seller must ship, earns, and is paid for.
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->string('status', 12)->default('pending')->index(); // pending | shipped | delivered | refunded
            $table->string('carrier', 40)->nullable();
            $table->string('tracking_number', 80)->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('auto_deliver_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->decimal('gross', 10, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('net', 10, 2);
            $table->timestamp('release_at')->nullable()->index(); // earliest payout moment (delivery + level hold)
            $table->foreignId('payout_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->unique(['order_id', 'seller_id']);
        });

        Schema::table('disputes', function (Blueprint $table) {
            $table->string('email')->nullable()->after('user_id');
            $table->string('stripe_refund_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('disputes', function (Blueprint $table) {
            $table->dropColumn(['email', 'stripe_refund_id']);
        });
        Schema::dropIfExists('shipments');
    }
};
