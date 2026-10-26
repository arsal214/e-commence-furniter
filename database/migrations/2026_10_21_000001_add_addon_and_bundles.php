<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Cheap items that ship free only inside an order that reaches the free-shipping threshold.
            $table->boolean('is_addon')->default(false)->after('is_best_seller');
        });

        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(false);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::create('bundle_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->unsignedSmallInteger('qty')->default(1);
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Set on the lines a bundle expanded into, so fulfilment sees real products and reports can group the set.
            $table->unsignedBigInteger('bundle_id')->nullable();
            $table->string('bundle_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['bundle_id', 'bundle_name']);
        });
        Schema::dropIfExists('bundle_items');
        Schema::dropIfExists('bundles');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_addon');
        });
    }
};
