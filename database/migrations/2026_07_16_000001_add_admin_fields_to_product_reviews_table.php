<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admin-authored reviews aren't tied to a registered account, so:
     *  - user_id becomes nullable (admin reviews leave it null)
     *  - reviewer_name holds the display name for those admin reviews
     *
     * MySQL treats NULLs as distinct in a unique index, so the existing
     * unique(product_id, user_id) still permits many admin reviews per product
     * while keeping the "one review per real user" rule for customers.
     */
    public function up(): void
    {
        // Drop the FK so the column can be made nullable, then re-add it as nullOnDelete.
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->string('reviewer_name')->nullable()->after('user_id');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('reviewer_name');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
