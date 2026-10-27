<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // A store's public address uses this id, never the database's own row number.
        Schema::table('sellers', function (Blueprint $table) {
            $table->char('uuid', 36)->nullable()->unique()->after('id');
        });

        DB::table('sellers')->whereNull('uuid')->pluck('id')->each(
            fn ($id) => DB::table('sellers')->where('id', $id)->update(['uuid' => Str::upper((string) Str::uuid())])
        );
    }

    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
