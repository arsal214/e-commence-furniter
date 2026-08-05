<?php

namespace Database\Seeders;

use Database\Seeders\Reviews\ProductReviewSeeder;

/**
 * Reviews for the Chunky Knit Throw Blanket 50x60 (live product 2277).
 *
 * Transcribed from the supplied document. Its headings are not carried over —
 * product_reviews stores a rating, a comment, a photo and a name, with no title
 * column — so only the review bodies are kept.
 *
 * Ten reviews, two photos: the document attaches a picture to the first two
 * only, so the remaining eight are seeded without one rather than reusing
 * someone else's shot.
 *
 * Photos are the document's own, resized to 800px and re-encoded as JPEG
 * (5.5 MB of PNG became 276 KB, still retina-sharp at the 120px the cards
 * render them at).
 *
 * Run:  php artisan db:seed --class=ChunkyKnitThrowReviewSeeder
 */
class ChunkyKnitThrowReviewSeeder extends ProductReviewSeeder
{
    protected function slug(): string
    {
        return 'chunky-knit-throw-blanket-50x60';
    }

    protected function fallbackId(): int
    {
        return 2277;
    }

    protected function reviews(): array
    {
        return [
            [
                'name'   => 'Ashley Coleman',
                'rating' => 5,
                'body'   => 'Love this blanket.',
                'image'  => 'chunky-knit-review-1.jpg',
            ],
            [
                'name'   => 'Megan Fletcher',
                'rating' => 5,
                'body'   => 'Soft, warm, and looks beautiful on my sofa.',
                'image'  => 'chunky-knit-review-2.jpg',
            ],
            [
                'name'   => 'Natalie Ward',
                'rating' => 5,
                'body'   => 'I bought this for my living room and it instantly made the space feel more cozy. The chunky knit design looks really nice when folded over the couch.',
                'image'  => null,
            ],
            [
                'name'   => 'Brian Sutton',
                'rating' => 4,
                'body'   => 'Very soft and comfortable. The size is perfect for relaxing while watching TV.',
                'image'  => null,
            ],
            [
                'name'   => 'Chloe Harrington',
                'rating' => 5,
                'body'   => 'This has become my go-to blanket every evening. It has a nice weight to it and feels super comfortable without being too heavy.',
                'image'  => null,
            ],
            [
                'name'   => 'Victoria Lane',
                'rating' => 5,
                'body'   => 'The chunky design is exactly what I wanted. It adds a warm, stylish touch to my room.',
                'image'  => null,
            ],
            [
                'name'   => 'Ethan Caldwell',
                'rating' => 5,
                'body'   => 'Perfect for my reading chair.',
                'image'  => null,
            ],
            [
                'name'   => 'Samantha Doyle',
                'rating' => 5,
                'body'   => 'Looks just like the pictures and feels amazing.',
                'image'  => null,
            ],
            [
                'name'   => 'Kevin Barrett',
                'rating' => 4,
                'body'   => 'Soft blanket and nice size. Looks great as a home decor piece.',
                'image'  => null,
            ],
            [
                'name'   => 'Jessica Monroe',
                'rating' => 5,
                'body'   => 'My new favorite blanket ❤️',
                'image'  => null,
            ],
        ];
    }
}
