<?php

namespace Database\Seeders\Reviews;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Shared behaviour for seeding a product's reviews together with their photos.
 *
 * A subclass supplies the product it belongs to and the rows; everything about
 * locating the product, copying photos into public storage and staying
 * idempotent lives here, so adding another product's reviews is a short file.
 *
 * Guarantees for every subclass:
 *  - the product is matched by slug, falling back to the live id, and the
 *    seeder exits quietly when neither resolves — running against a database
 *    that has never imported the product does nothing rather than erroring;
 *  - rows are keyed on product + reviewer name, so re-running updates in place
 *    instead of stacking duplicates;
 *  - a photo is only written when it is missing or a different size, so
 *    re-running does not churn the disk;
 *  - is_verified stays false. The badge tells shoppers the reviewer actually
 *    bought the item, so it is never set from a data file. Customer-submitted
 *    reviews earn it automatically from their own paid orders.
 */
abstract class ProductReviewSeeder extends Seeder
{
    /** Product slug — the stable identifier across environments. */
    abstract protected function slug(): string;

    /** Live product id, used only if the slug ever changes. */
    abstract protected function fallbackId(): int;

    /**
     * The reviews.
     *
     * @return array<int, array{name: string, rating: int, body: string, image: ?string}>
     */
    abstract protected function reviews(): array;

    /** Bundled photos live alongside the seeders. */
    protected function assetDir(): string
    {
        return dirname(__DIR__) . '/assets/reviews';
    }

    public function run(): void
    {
        $product = Product::where('slug', $this->slug())->first()
            ?? Product::find($this->fallbackId());

        if (! $product) {
            $this->command?->warn(static::class . ": product '{$this->slug()}' not found — nothing seeded.");

            return;
        }

        $disk = Storage::disk('public');
        $count = 0;

        foreach ($this->reviews() as $row) {
            Review::updateOrCreate(
                ['product_id' => $product->id, 'reviewer_name' => $row['name']],
                [
                    'user_id'     => null,   // not tied to a registered account
                    'rating'      => $row['rating'],
                    'comment'     => $row['body'],
                    'image'       => empty($row['image']) ? null : $this->storePhoto($disk, $row['image']),
                    'is_verified' => false,
                ]
            );

            $count++;
        }

        $this->command?->info(class_basename(static::class) . ": {$count} reviews seeded for #{$product->id} ({$product->name}).");
    }

    /**
     * Copy a bundled photo into public storage. Returns the stored path, or
     * null when the source file is missing — a missing photo drops the image
     * rather than failing the whole seed.
     */
    protected function storePhoto($disk, string $file): ?string
    {
        $source = $this->assetDir() . '/' . $file;

        if (! is_file($source)) {
            $this->command?->warn("  missing photo: {$file}");

            return null;
        }

        $target = 'reviews/' . $file;

        if (! $disk->exists($target) || $disk->size($target) !== filesize($source)) {
            $disk->put($target, file_get_contents($source));
        }

        return $target;
    }
}
