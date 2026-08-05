<?php

namespace Database\Seeders;

use Database\Seeders\Reviews\ProductReviewSeeder;

/**
 * Reviews for the Green Flower Table Lamp (live product 2303).
 *
 * Transcribed from the supplied document. Its headings are not carried over —
 * product_reviews stores a rating, a comment, a photo and a name, with no title
 * column — so only the review bodies are kept.
 *
 * The document holds two reviews but only one photo: both entries point at the
 * same embedded file (rId5), not two different pictures. Showing one shopper's
 * photo twice reads as fabricated, so it is attached to the first review only
 * and the second is seeded without one. Send a second picture and it can be
 * dropped in beside this file.
 *
 * That photo is the document's own, resized to 800px and re-encoded as JPEG
 * (2.3 MB of PNG became 89 KB, still retina-sharp at the 120px the cards render
 * it at).
 *
 * Run:  php artisan db:seed --class=GreenFlowerLampReviewSeeder
 */
class GreenFlowerLampReviewSeeder extends ProductReviewSeeder
{
    protected function slug(): string
    {
        return 'green-flower-table-lamp';
    }

    protected function fallbackId(): int
    {
        return 2303;
    }

    protected function reviews(): array
    {
        return [
            [
                'name'   => 'Hannah Brooks',
                'rating' => 5,
                'body'   => 'This lamp is such a unique decoration piece. I placed it on my desk and it instantly made the space feel more personal. My friends noticed it right away and asked about it.',
                'image'  => 'green-lamp-review-1.jpg',
            ],
            [
                'name'   => 'Grace Sullivan',
                'rating' => 4,
                'body'   => 'The design is what caught my attention and it looks great in my room. It’s more of a decorative lamp than a main light source, but it creates a really nice warm atmosphere.',
                'image'  => null,   // the document reuses the first review's photo here
            ],
        ];
    }
}
