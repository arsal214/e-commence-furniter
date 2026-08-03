<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Structured state + country on orders.
 *
 * Both are stored as codes ('NJ', 'US'), not display names: the labels live in
 * config/checkout.php and can be reworded without rewriting order history, and
 * Meta's Conversions API wants the two-letter forms anyway.
 *
 * Existing rows are backfilled to the default country. Every order placed before
 * this migration was taken on a US-only form, so 'US' is a fact about them, not
 * a guess — but state is genuinely unknown and stays null.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('state', 64)->nullable()->after('city');
            $table->string('country', 2)->nullable()->after('zip');
        });

        \DB::table('orders')
            ->whereNull('country')
            ->update(['country' => config('checkout.default_country', 'US')]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['state', 'country']);
        });
    }
};
