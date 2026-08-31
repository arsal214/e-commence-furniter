<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Opt-out flag for marketing mail.
 *
 * Promotional sends are not transactional, so a customer must be able to stop
 * them. The unsubscribe link in every promotional email stamps this column, and
 * the admin send is refused for anyone carrying it. Order and password mail is
 * unaffected — that is transactional and always goes out.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('marketing_opt_out_at')->nullable()->after('must_reset_password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('marketing_opt_out_at');
        });
    }
};
