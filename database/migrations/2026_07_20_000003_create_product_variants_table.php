<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            // A variant is one option value with its own pricing. type is the
            // dimension ('color' or 'size'); value is the option ('Black','Large').
            // Colour and size are priced independently, so each lives in its own row.
            $table->string('type', 20);
            $table->string('value');
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('sku')->nullable();
            $table->string('image')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // One variant row per option value within a dimension.
            $table->unique(['product_id', 'type', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
