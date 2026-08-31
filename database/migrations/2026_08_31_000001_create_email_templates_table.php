<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reusable copy for the promotional emails staff send from the customer list.
 *
 * The body is TinyMCE HTML, the same as a product description. Templates are
 * starting points only — the compose screen loads one into the editor and the
 * sender is free to edit before sending, so nothing here is sent verbatim.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->string('eyebrow')->nullable();
            $table->string('heading')->nullable();
            $table->longText('body_html');
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('promo_code', 60)->nullable();
            $table->string('promo_note')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
