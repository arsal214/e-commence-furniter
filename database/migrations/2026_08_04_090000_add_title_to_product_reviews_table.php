<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Optional headline for a review ("My new favorite desk decoration").
 *
 * Nullable, so every existing review stays valid and the card simply renders
 * without a heading where there isn't one.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->string('title')->nullable()->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
