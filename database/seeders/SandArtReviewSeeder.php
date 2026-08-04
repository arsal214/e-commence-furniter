<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Reviews for the HYUGF 3D Moving Sand Art Picture (live product id 2322).
 *
 * Content transcribed from the supplied document; photos are the ones attached
 * to it, resized to 800px and re-encoded as JPEG (16.6 MB of PNG became 677 KB
 * with no visible loss at the size they render).
 *
 * Safe to run anywhere and safe to run twice:
 *  - the product is matched by slug, with the live id as a fallback, and the
 *    seeder exits quietly when neither resolves (so a local database that has
 *    never imported this product is simply skipped rather than erroring);
 *  - each review is keyed on product + title, so re-running updates the existing
 *    rows instead of stacking duplicates.
 *
 * Run:  php artisan db:seed --class=SandArtReviewSeeder
 */
class SandArtReviewSeeder extends Seeder
{
    /** Product slug on live; the id is only a fallback if the slug ever changes. */
    private const SLUG = 'hyugf-3d-moving-sand-art-picture-decor-7';
    private const FALLBACK_ID = 2322;

    /** Where the bundled photos live, relative to this file. */
    private const ASSET_DIR = __DIR__ . '/assets/reviews';

    /**
     * The reviews.
     *
     * `name` is deliberately null: the source document carried no reviewer
     * names, and inventing them would put fabricated people on the storefront.
     * Fill these in with the real names and re-run — the seeder updates in
     * place. Left null, the card falls back to the neutral "Customer".
     *
     * `verified` is likewise false unless you know the purchase is real: the
     * badge is a claim to shoppers, not decoration.
     */
    private function reviews(): array
    {
        return [
            [
                'name'   => null,
                'rating' => 5,
                'title'  => 'My new favorite desk decoration',
                'body'   => "I bought this for my office desk and honestly didn't expect to use it this much. Whenever I take a break from my computer, I just flip it and watch the sand create different patterns. It's really satisfying and looks beautiful next to my monitor.",
                'image'  => 'sand-art-review-1.jpg',
            ],
            [
                'name'   => null,
                'rating' => 4,
                'title'  => 'Better than I expected',
                'body'   => "The pictures don't really show how cool this looks in person. The sand slowly moves and creates a different design every time. My coworkers keep asking where I got it from.",
                'image'  => 'sand-art-review-2.jpg',
            ],
            [
                'name'   => null,
                'rating' => 3,
                'title'  => 'Perfect little gift',
                'body'   => 'I bought this as a gift for my sister who loves unique home decor. She loved it immediately. It feels different from the usual decorations you find everywhere.',
                'image'  => 'sand-art-review-3.jpg',
            ],
            [
                'name'   => null,
                'rating' => 4,
                'title'  => 'Beautiful piece for my workspace',
                'body'   => 'The colors are really nice and it adds something interesting to my desk. It took a few flips to get the sand flowing the way I wanted, but once adjusted it works great.',
                'image'  => 'sand-art-review-4.jpg',
            ],
            [
                'name'   => null,
                'rating' => 5,
                'title'  => 'So relaxing to watch',
                'body'   => 'There is something really satisfying about watching the sand fall slowly and create mountains and shapes. I keep it beside my laptop and flip it between tasks.',
                'image'  => 'sand-art-review-6.jpg',
            ],
            [
                'name'   => null,
                'rating' => 5,
                'title'  => 'Everyone notices it',
                'body'   => 'This has become a conversation piece in my home office. Almost everyone who visits picks it up and wants to try flipping it.',
                'image'  => 'sand-art-review-7.jpg',
            ],
        ];
    }

    public function run(): void
    {
        $product = Product::where('slug', self::SLUG)->first()
            ?? Product::find(self::FALLBACK_ID);

        if (! $product) {
            $this->command?->warn("SandArtReviewSeeder: product '" . self::SLUG . "' not found — nothing seeded.");

            return;
        }

        $disk = Storage::disk('public');
        $seeded = 0;

        foreach ($this->reviews() as $row) {
            $stored = $this->storePhoto($disk, $row['image']);

            Review::updateOrCreate(
                ['product_id' => $product->id, 'title' => $row['title']],
                [
                    'user_id'       => null,          // not tied to an account
                    'reviewer_name' => $row['name'],
                    'rating'        => $row['rating'],
                    'comment'       => $row['body'],
                    'image'         => $stored,
                    'is_verified'   => false,
                ]
            );

            $seeded++;
        }

        $this->command?->info("SandArtReviewSeeder: {$seeded} reviews seeded for #{$product->id} ({$product->name}).");
    }

    /**
     * Copy a bundled photo into public storage, skipping the write when an
     * identical file is already there so re-running does not churn the disk.
     * Returns the stored path, or null if the source file is missing.
     */
    private function storePhoto($disk, string $file): ?string
    {
        $source = self::ASSET_DIR . '/' . $file;

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
