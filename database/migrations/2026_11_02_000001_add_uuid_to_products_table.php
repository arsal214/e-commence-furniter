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
        // What shoppers' browsers see instead of the product's row number.
        Schema::table('products', function (Blueprint $table) {
            $table->char('uuid', 36)->nullable()->unique()->after('id');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('UPDATE products SET uuid = UPPER(UUID()) WHERE uuid IS NULL'); // one pass, a fresh uuid per row
        } else {
            DB::table('products')->whereNull('uuid')->orderBy('id')->pluck('id')->each(
                fn ($id) => DB::table('products')->where('id', $id)->update(['uuid' => Str::upper((string) Str::uuid())])
            );
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
