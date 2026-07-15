<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * SEO fix: correct the "Beauty Produts" category spelling and its slug.
 * Idempotent — only touches the row if the typo is still present, so it is
 * safe to run on any environment. The old /category/beauty-produts URL is
 * 301-redirected in routes/web.php.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('categories')
            ->where('slug', 'beauty-produts')
            ->update([
                'name' => 'Beauty Products',
                'slug' => 'beauty-products',
            ]);
    }

    public function down(): void
    {
        DB::table('categories')
            ->where('slug', 'beauty-products')
            ->where('name', 'Beauty Products')
            ->update([
                'name' => 'Beauty Produts',
                'slug' => 'beauty-produts',
            ]);
    }
};
