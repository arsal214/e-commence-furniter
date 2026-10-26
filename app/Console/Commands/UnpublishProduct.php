<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

/**
 * Hide a product from the storefront without deleting it (order history keeps
 * its lines).
 *
 *   php artisan products:unpublish eu-rotating-power-strip
 *   php artisan products:unpublish eu-rotating-power-strip --restore
 */
class UnpublishProduct extends Command
{
    protected $signature = 'products:unpublish {slug} {--restore : Publish it again}';

    protected $description = 'Set a product inactive (or active again with --restore) by slug';

    public function handle(): int
    {
        $product = Product::where('slug', $this->argument('slug'))->first();

        if (! $product) {
            $this->error('No product with that slug.');

            return self::FAILURE;
        }

        $product->update(['is_active' => (bool) $this->option('restore')]);
        $this->info("{$product->name}: " . ($product->is_active ? 'published' : 'unpublished'));

        return self::SUCCESS;
    }
}
