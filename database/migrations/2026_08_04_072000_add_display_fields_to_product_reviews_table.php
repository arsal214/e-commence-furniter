<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fields the review grid displays: a customer photo, a country for the flag,
 * and whether the reviewer actually bought the product.
 *
 * is_verified is stored rather than computed on render because it is a claim
 * made to shoppers and has to stay true to the moment it was earned — an order
 * later refunded or deleted must not silently rewrite the badge. It is set at
 * submission time by checking the reviewer's own order history.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->string('image')->nullable()->after('comment');
            $table->string('country', 2)->nullable()->after('image');
            $table->boolean('is_verified')->default(false)->after('country');
        });

        // The store sells to the US only, so existing reviews are US reviews.
        \DB::table('product_reviews')
            ->whereNull('country')
            ->update(['country' => config('checkout.default_country', 'US')]);
    }

    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn(['image', 'country', 'is_verified']);
        });
    }
};
