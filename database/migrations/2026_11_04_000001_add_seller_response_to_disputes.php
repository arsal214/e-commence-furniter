<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A seller can answer a refund request: refund it, or decline with a note and leave it to staff.
        Schema::table('disputes', function (Blueprint $table) {
            $table->text('seller_note')->nullable();
            $table->timestamp('seller_declined_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('disputes', function (Blueprint $table) {
            $table->dropColumn(['seller_note', 'seller_declined_at']);
        });
    }
};
