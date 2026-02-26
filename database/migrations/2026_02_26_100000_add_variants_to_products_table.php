<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('colors')->nullable()->after('sku'); // e.g. ["Red","Blue","Black"]
            $table->json('sizes')->nullable()->after('colors'); // e.g. ["S","M","L","XL"] or ["36","37","38"]
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['colors', 'sizes']);
        });
    }
};
