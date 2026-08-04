<?php

namespace Database\Seeders;

use Database\Seeders\Reviews\ProductReviewSeeder;

/**
 * Reviews for the Thinker Statue Abstract Resin Figurines (live product 2281).
 *
 * Transcribed from the supplied document. Its headings are not carried over —
 * product_reviews stores a rating, a comment, a photo and a name, with no title
 * column — so only the review bodies are kept.
 *
 * Photos are the ones attached to the document, resized to 800px and re-encoded
 * as JPEG (4.0 MB of PNG became 150 KB, still retina-sharp at the 120px the
 * cards render them at). The middle review had no photo in the document and is
 * seeded without one.
 *
 * Run:  php artisan db:seed --class=ThinkerStatueReviewSeeder
 */
class ThinkerStatueReviewSeeder extends ProductReviewSeeder
{
    protected function slug(): string
    {
        return 'thinker-statue-abstract-resin-figurines';
    }

    protected function fallbackId(): int
    {
        return 2281;
    }

    protected function reviews(): array
    {
        return [
            [
                'name'   => 'Rachel Adams',
                'rating' => 4,
                'body'   => 'I was surprised by how nice this looks in person. The details and design give my shelf a much more interesting look. It’s a simple piece but gets a lot of attention.',
                'image'  => 'thinker-statue-review-1.jpg',
            ],
            [
                'name'   => 'Thomas Blake',
                'rating' => 5,
                'body'   => 'I bought this because I wanted something different for my room. It has a modern feel and fits perfectly with my other decor pieces.',
                'image'  => null,   // no photo attached to this one in the document
            ],
            [
                'name'   => 'Laura Mitchell',
                'rating' => 4,
                'body'   => 'Added this to my workspace and it looks great beside my books and laptop. The size is perfect and it gives the desk a more creative feel.',
                'image'  => 'thinker-statue-review-2.jpg',
            ],
        ];
    }
}
