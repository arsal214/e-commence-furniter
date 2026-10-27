<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Store-wide sale: it is applied by writing real sale prices, so every price rule keeps working unchanged.
        Schema::table('sellers', function (Blueprint $table) {
            $table->unsignedTinyInteger('sale_percent')->nullable();
            $table->boolean('sale_active')->default(false);
            $table->boolean('ads_active')->default(false);
            $table->decimal('ads_daily_budget', 8, 2)->default(5);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('storewide_sale')->default(false);
            $table->decimal('sale_backup', 10, 2)->nullable(); // the seller's own sale price, put back when the store sale ends
        });
        Schema::table('product_variants', function (Blueprint $table) {
            $table->boolean('storewide_sale')->default(false);
        });

        Schema::create('seller_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->string('code', 30);
            $table->string('type', 8); // percent | amount
            $table->decimal('value', 8, 2);
            $table->decimal('min_order', 8, 2)->default(0);
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('uses')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['seller_id', 'code']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('coupon_code', 30)->nullable();
            $table->decimal('coupon_discount', 10, 2)->default(0);
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('coupon_discount', 10, 2)->default(0); // the part of this line the seller's coupon paid for
        });

        Schema::create('ad_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('cost', 6, 2);
            $table->timestamp('billed_at')->nullable()->index();
            $table->timestamps();
            $table->index(['seller_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_clicks');
        Schema::table('order_items', fn (Blueprint $t) => $t->dropColumn('coupon_discount'));
        Schema::table('orders', fn (Blueprint $t) => $t->dropColumn(['coupon_code', 'coupon_discount']));
        Schema::dropIfExists('seller_coupons');
        Schema::table('product_variants', fn (Blueprint $t) => $t->dropColumn('storewide_sale'));
        Schema::table('products', fn (Blueprint $t) => $t->dropColumn(['storewide_sale', 'sale_backup']));
        Schema::table('sellers', fn (Blueprint $t) => $t->dropColumn(['sale_percent', 'sale_active', 'ads_active', 'ads_daily_budget']));
    }
};
