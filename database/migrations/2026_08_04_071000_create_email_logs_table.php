<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A record of every email the application sends.
 *
 * Written from the framework's MessageSent event rather than from each call
 * site, so password-reset mail (sent through Notifications, not Mail::to) and
 * any mailable added later are captured without anyone remembering to log them.
 *
 * user_id and order_id are plain nullable columns, not foreign keys: the log is
 * evidence of what was sent and must survive the deletion of the order or
 * account it refers to. Recipients are stored as the address that was actually
 * mailed, so the record stays true even if the customer later changes it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();

            $table->string('to_email')->index();
            $table->string('to_name')->nullable();
            $table->text('cc')->nullable();
            $table->text('bcc')->nullable();
            $table->string('subject')->nullable();

            // Mailable class, e.g. App\Mail\OrderConfirmationMail. Null for mail
            // sent outside a mailable (raw sends, some notifications).
            $table->string('mailable')->nullable()->index();

            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('order_id')->nullable()->index();

            $table->string('status', 16)->default('sent')->index();
            $table->text('error')->nullable();

            // The transport's Message-ID, for matching against SMTP/provider logs.
            $table->string('message_id')->nullable();

            $table->timestamp('sent_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
