<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label', 30);
            $table->string('name', 120);
            $table->string('phone', 30)->nullable();
            $table->string('address', 160);
            $table->string('address2', 80)->nullable();
            $table->string('city', 80);
            $table->string('state', 2);
            $table->string('zip', 10);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('store_follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'seller_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_follows');
        Schema::dropIfExists('user_addresses');
    }
};
