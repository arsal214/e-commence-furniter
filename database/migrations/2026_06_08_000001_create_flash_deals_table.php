<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_deals', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(false);
            $table->string('title')->default('Flash Deal');
            $table->string('subtitle')->nullable();
            $table->string('discount_label')->default('Up to 40% OFF');
            $table->string('badge_text')->default('Limited Time');
            $table->datetime('ends_at')->nullable();
            $table->string('cta_text')->default('Shop Now');
            $table->string('cta_url')->default('/shop');
            $table->string('bg_color')->default('#0F1E2E');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_deals');
    }
};
