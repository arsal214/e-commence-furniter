<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('utm_term')->nullable();
            // Captured at order time so the webhook (no browser) can still match the buyer on Meta.
            $table->string('meta_fbp')->nullable();
            $table->string('meta_fbc')->nullable();
            $table->string('client_ip', 64)->nullable();
            $table->string('client_user_agent', 512)->nullable();
            $table->timestamp('meta_event_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term',
                'meta_fbp', 'meta_fbc', 'client_ip', 'client_user_agent', 'meta_event_sent_at']);
        });
    }
};
