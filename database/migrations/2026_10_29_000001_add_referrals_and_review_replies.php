<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('referral_code', 12)->nullable()->unique();
            $table->foreignId('referred_by')->nullable()->constrained('sellers')->nullOnDelete();
            $table->timestamp('referral_credited_at')->nullable();
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->text('seller_reply')->nullable();
            $table->timestamp('seller_replied_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn(['seller_reply', 'seller_replied_at']);
        });
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('referred_by');
            $table->dropColumn(['referral_code', 'referral_credited_at']);
        });
    }
};
