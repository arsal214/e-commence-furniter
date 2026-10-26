<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('welcome_offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(false);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedSmallInteger('expiry_hours')->default(72);
            $table->json('display_settings')->nullable();
            $table->timestamps();
        });

        Schema::create('welcome_offer_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('welcome_offer_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20); // fixed_amount | percent | free_shipping
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('min_subtotal', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable(); // cap for percent tiers
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('welcome_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('welcome_offer_id')->constrained()->cascadeOnDelete();
            $table->string('email')->index();
            // Lower-cased, +tag stripped, gmail dots removed: stops one inbox claiming twice via aliases.
            $table->string('email_key');
            $table->string('code', 24)->unique();
            $table->uuid('visitor_id')->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->boolean('sms_opt_in')->default(false);
            $table->timestamp('claimed_at');
            $table->timestamp('expires_at');
            $table->foreignId('used_order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->timestamp('used_at')->nullable();
            $table->string('used_tier_label')->nullable(); // snapshot: tiers can be edited later
            $table->decimal('discount_given', 10, 2)->nullable();
            $table->string('card_fingerprint')->nullable()->index();
            $table->string('address_hash', 64)->nullable()->index();
            $table->timestamps();

            $table->unique(['welcome_offer_id', 'email_key']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount', 10, 2)->default(0)->after('shipping_cost');
            $table->unsignedBigInteger('welcome_claim_id')->nullable()->after('discount');
            $table->timestamp('confirmation_sent_at')->nullable();
            $table->timestamp('purchase_tracked_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['discount', 'welcome_claim_id', 'confirmation_sent_at', 'purchase_tracked_at']);
        });
        Schema::dropIfExists('welcome_claims');
        Schema::dropIfExists('welcome_offer_tiers');
        Schema::dropIfExists('welcome_offers');
    }
};
