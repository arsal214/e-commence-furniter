<?php

namespace Database\Seeders;

use Database\Seeders\Reviews\ProductReviewSeeder;

/**
 * Reviews for the Der Rose 3 Pcs Fake Succulents (live product 2241).
 *
 * Transcribed from the supplied document. Its headings are not carried over —
 * product_reviews stores a rating, a comment, a photo and a name, with no title
 * column — so only the review bodies are kept.
 *
 * Two entries in the document were incomplete; both are marked inline below.
 * The first carries no star rating at all, and "Great for small spaces" is the
 * one heading with no review text under it. See the notes on each.
 *
 * Ten reviews, one photo: only the first has a picture attached, so the rest
 * are seeded without one rather than reusing someone else's shot. That photo is
 * the document's own, resized to 800px and re-encoded as JPEG (2.4 MB of PNG
 * became 119 KB, still retina-sharp at the 120px the cards render it at).
 *
 * Run:  php artisan db:seed --class=FakeSucculentsReviewSeeder
 */
class FakeSucculentsReviewSeeder extends ProductReviewSeeder
{
    protected function slug(): string
    {
        return 'der-rose-3-pcs-fake-succulents-plants-for-home-decor';
    }

    protected function fallbackId(): int
    {
        return 2241;
    }

    protected function reviews(): array
    {
        return [
            [
                'name'   => 'Amber Whitaker',
                // The document shows no stars on this one — every other entry
                // has them. Assumed 5 from the wording and the rest of the set;
                // change it here if it was meant to be lower.
                'rating' => 5,
                'body'   => 'Love these little plants.',
                'image'  => 'succulents-review-1.jpg',
            ],
            [
                'name'   => 'Derek Lawson',
                'rating' => 5,
                'body'   => 'Added these to my office desk and they make the space feel much nicer.',
                'image'  => null,
            ],
            [
                'name'   => 'Lily Ferguson',
                'rating' => 5,
                'body'   => "I wanted some greenery but don't have time to take care of real plants. These look adorable and fit perfectly on my shelf.",
                'image'  => null,
            ],
            [
                'name'   => 'Marcus Reilly',
                'rating' => 4,
                'body'   => 'They are smaller than I expected, but they look really cute in my bathroom.',
                'image'  => null,
            ],
            [
                'name'   => 'Paige Donnelly',
                'rating' => 5,
                'body'   => 'These little plants add a nice touch to my room without any maintenance.',
                'image'  => null,
            ],
            [
                'name'   => 'Sean Kavanagh',
                'rating' => 5,
                'body'   => 'Perfect size and looks great with my home decor.',
                'image'  => null,
            ],
            [
                'name'   => 'Bethany Cross',
                'rating' => 5,
                // This heading had no review text under it, just the divider.
                // The heading itself reads as a complete short review, so it is
                // used as the comment — an empty comment would render a blank
                // card. Delete this entry instead if you would rather drop it.
                'body'   => 'Great for small spaces.',
                'image'  => null,
            ],
            [
                'name'   => 'Julia Vance',
                'rating' => 5,
                'body'   => 'Looks better than I expected.',
                'image'  => null,
            ],
            [
                'name'   => 'Ryan Whitmore',
                'rating' => 4,
                'body'   => 'Nice decoration for my shelf and coffee table.',
                'image'  => null,
            ],
            [
                'name'   => 'Nicole Ashford',
                'rating' => 5,
                'body'   => 'My favorite part is that I never have to worry about watering them 😂',
                'image'  => null,
            ],
        ];
    }
}
