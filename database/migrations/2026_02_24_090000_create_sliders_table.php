<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('button_text')->default('Shop Now');
            $table->string('button_url')->default('/shop-v1');
            $table->string('image')->nullable();           // banner image (right side)
            $table->string('badge_price')->nullable();     // e.g. "$140"
            $table->string('badge_label')->nullable();     // e.g. "Aurora Flexible Sofa"
            $table->string('badge_color')->default('#BB976D'); // SVG blob color
            $table->string('year_text')->default('2026');  // big year number on left
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
