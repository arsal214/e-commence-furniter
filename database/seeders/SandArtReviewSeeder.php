<?php

namespace Database\Seeders;

use Database\Seeders\Reviews\ProductReviewSeeder;

/**
 * Reviews for the HYUGF 3D Moving Sand Art Picture (live product 2322).
 *
 * Transcribed from the supplied document. Its headings are not carried over —
 * product_reviews stores a rating, a comment, a photo and a name, with no title
 * column — so only the review bodies are kept.
 *
 * Photos are the ones attached to the document, resized to 800px and re-encoded
 * as JPEG (16.6 MB of PNG became 677 KB, still retina-sharp at the 120px the
 * cards render them at). Two reviews carried a second photo; the table holds one
 * per review, so sand-art-review-5.jpg and -8.jpg are bundled but unused.
 *
 * Run:  php artisan db:seed --class=SandArtReviewSeeder
 */
class SandArtReviewSeeder extends ProductReviewSeeder
{
    protected function slug(): string
    {
        return 'hyugf-3d-moving-sand-art-picture-decor-7';
    }

    protected function fallbackId(): int
    {
        return 2322;
    }

    protected function reviews(): array
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
}
