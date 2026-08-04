<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Reviews for the HYUGF 3D Moving Sand Art Picture (live product id 2322).
 *
 * Comments are transcribed from the supplied document. The photos are the ones
 * attached to it, resized to 800px and re-encoded as JPEG — 16.6 MB of PNG
 * became 677 KB, which still leaves retina headroom at the 120px the cards
 * render them at.
 *
 * The product_reviews table stores a rating, a comment, a photo and a name;
 * there is no title column, so the headings from the document are not carried
 * over — only the review bodies are stored.
 *
 * Safe to run anywhere and safe to run twice:
 *  - the product is matched by slug, with the live id as a fallback, and the
 *    seeder exits quietly when neither resolves, so a database that has never
 *    imported this product is skipped rather than erroring;
 *  - each review is keyed on product + reviewer name, so re-running updates the
 *    existing rows instead of stacking duplicates.
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
     * The reviews, in the order they appeared in the document.
     *
     * `verified` stays false throughout: the badge tells shoppers the reviewer
     * actually bought the item, so it is left off unless that is known to be
     * true. Customer-submitted reviews earn it automatically from paid orders.
     */
    private function reviews(): array
    {
        return [
            [
                'name'   => 'Emily Carter',
                'rating' => 5,
                'body'   => "I bought this for my office desk and honestly didn't expect to use it this much. Whenever I take a break from my computer, I just flip it and watch the sand create different patterns. It's really satisfying and looks beautiful next to my monitor.",
                'image'  => 'sand-art-review-1.jpg',
            ],
            [
                'name'   => 'James Whitfield',
                'rating' => 4,
                'body'   => "The pictures don't really show how cool this looks in person. The sand slowly moves and creates a different design every time. My coworkers keep asking where I got it from.",
                'image'  => 'sand-art-review-2.jpg',
            ],
            [
                'name'   => 'Sophie Bennett',
                // The document shows three stars here despite the positive text.
                // Kept as written rather than "corrected" — change it if that
                // was a typo on your side.
                'rating' => 3,
                'body'   => 'I bought this as a gift for my sister who loves unique home decor. She loved it immediately. It feels different from the usual decorations you find everywhere.',
                'image'  => 'sand-art-review-3.jpg',
            ],
            [
                'name'   => 'Daniel Hughes',
                'rating' => 4,
                'body'   => 'The colors are really nice and it adds something interesting to my desk. It took a few flips to get the sand flowing the way I wanted, but once adjusted it works great.',
                'image'  => 'sand-art-review-4.jpg',
            ],
            [
                'name'   => 'Olivia Reed',
                'rating' => 5,
                'body'   => 'There is something really satisfying about watching the sand fall slowly and create mountains and shapes. I keep it beside my laptop and flip it between tasks.',
                'image'  => 'sand-art-review-6.jpg',
            ],
            [
                'name'   => 'Michael Turner',
                'rating' => 5,
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
            Review::updateOrCreate(
                ['product_id' => $product->id, 'reviewer_name' => $row['name']],
                [
                    'user_id'     => null,          // not tied to an account
                    'rating'      => $row['rating'],
                    'comment'     => $row['body'],
                    'image'       => $this->storePhoto($disk, $row['image']),
                    'is_verified' => false,
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
