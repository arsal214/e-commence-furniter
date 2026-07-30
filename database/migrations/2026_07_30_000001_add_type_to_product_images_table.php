<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            // 'image' (default) or 'video'. Video entries still use the `image`
            // column to store their file path, and are skipped by colour matching.
            $table->string('type')->default('image')->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
