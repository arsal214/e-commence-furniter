<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Shopper reward points: a ledger, the balance is the sum.
        Schema::create('reward_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('delta');
            $table->string('reason', 120);
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shipment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('kind', 10)->default('earn'); // earn | spend | restore | remove | expire
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
            $table->unique(['shipment_id', 'kind']); // a delivery earns once, a refund removes once
        });

        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('code', 19)->unique();
            $table->decimal('amount', 8, 2);
            $table->decimal('balance', 8, 2);
            $table->string('status', 10)->default('pending'); // pending (unpaid) | active
            $table->foreignId('buyer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete(); // who redeemed it
            $table->string('recipient_email')->nullable();
            $table->string('message', 300)->nullable();
            $table->string('stripe_session_id')->nullable()->unique();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', ['cod', 'stripe', 'paypal', 'gift_card'])->default('cod')->change();
            $table->unsignedInteger('points_used')->default(0);
            $table->decimal('points_discount', 10, 2)->default(0);
            $table->decimal('gift_card_discount', 10, 2)->default(0);
        });

        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            $table->unique(['buyer_id', 'seller_id']);
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->string('from', 6); // buyer | seller
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['conversation_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
        Schema::table('orders', fn (Blueprint $t) => $t->dropColumn(['points_used', 'points_discount', 'gift_card_discount']));
        Schema::table('orders', fn (Blueprint $t) => $t->enum('payment_method', ['cod', 'stripe', 'paypal'])->default('cod')->change());
        Schema::dropIfExists('gift_cards');
        Schema::dropIfExists('reward_entries');
    }
};
