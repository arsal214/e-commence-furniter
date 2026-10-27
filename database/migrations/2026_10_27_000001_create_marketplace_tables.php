<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('store_name')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->string('business_name')->nullable();
            $table->string('business_type', 40)->nullable();
            $table->string('ein', 20)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('business_phone', 30)->nullable();
            $table->string('address')->nullable();
            $table->string('city', 80)->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('main_category', 120)->nullable();
            // Owner (kept private; dob and ssn_last4 are stored encrypted).
            $table->string('owner_name')->nullable();
            $table->string('owner_title', 80)->nullable();
            $table->string('owner_email')->nullable();
            $table->string('owner_phone', 30)->nullable();
            $table->text('owner_dob')->nullable();
            $table->text('owner_ssn_last4')->nullable();
            $table->string('owner_address')->nullable();
            $table->string('logo')->nullable();
            $table->text('about')->nullable();
            // Where the applicant is in sign-up: verify -> plan -> business -> owner -> bank -> done.
            $table->string('onboarding_step', 12)->default('verify');
            $table->timestamp('submitted_at')->nullable();
            // draft -> in_review -> approved | rejected; info_requested sends it back to the seller.
            $table->string('status', 20)->default('draft')->index();
            $table->text('admin_note')->nullable();
            $table->timestamp('approved_at')->nullable();
            // Level is derived from delivered sales, stored so storefront queries stay cheap.
            $table->string('level', 10)->default('new');
            $table->decimal('delivered_sales', 12, 2)->default(0);
            $table->unsignedInteger('points')->default(0);
            // none -> active | past_due | cancelled. Only 'active' stores are visible to buyers.
            $table->string('subscription_status', 20)->default('none')->index();
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            // Newest Stripe event applied; older events arriving late are ignored.
            $table->timestamp('subscription_event_at')->nullable();
            $table->string('stripe_account_id')->nullable();
            $table->unsignedSmallInteger('handling_days')->default(2);
            $table->unsignedSmallInteger('return_window_days')->default(30);
            $table->string('return_shipping_payer', 10)->default('buyer');
            $table->decimal('free_shipping_over', 8, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('seller_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20); // registration | id_front | id_back | ein_letter
            $table->string('path');
            $table->timestamps();
            $table->unique(['seller_id', 'type']);
        });

        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('status', 10)->default('scheduled')->index(); // scheduled|paid|held|failed
            $table->timestamp('release_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('stripe_transfer_id')->nullable();
            $table->string('failure_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reason', 40); // not_received | not_as_described | damaged
            $table->text('details');
            $table->decimal('amount', 10, 2);
            $table->string('status', 20)->default('open')->index(); // open | refunded | closed_seller
            $table->text('resolution_note')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            // null = sold by the house store (every pre-marketplace product).
            $table->foreignId('seller_id')->nullable()->after('id')->constrained()->nullOnDelete();
            // pending -> approved | flagged | removed. Existing rows stay approved.
            $table->string('moderation_status', 10)->default('approved')->index();
            $table->string('moderation_note')->nullable();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('seller_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            $table->foreignId('payout_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payout_id');
            $table->dropConstrainedForeignId('seller_id');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('seller_id');
            $table->dropColumn(['moderation_status', 'moderation_note']);
        });
        Schema::dropIfExists('disputes');
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('seller_documents');
        Schema::dropIfExists('sellers');
    }
};
