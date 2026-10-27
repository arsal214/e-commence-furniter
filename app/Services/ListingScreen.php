<?php

namespace App\Services;

/**
 * First-pass screen against the Prohibited Items policy. It never blocks a seller: every
 * listing is reviewed by a person anyway. A hit only marks the listing "flagged" with the
 * matched word so the reviewer looks at it first. False positives cost a reviewer seconds.
 */
class ListingScreen
{
    protected const TERMS = [
        'firearm', 'firearms', 'gun', 'guns', 'rifle', 'pistol', 'ammunition', 'ammo', 'explosive', 'explosives', 'fireworks',
        'cbd', 'thc', 'cannabis', 'marijuana', 'hemp oil', 'vape', 'vapes', 'e-cigarette', 'tobacco', 'cigarette', 'cigarettes',
        'alcohol', 'whiskey', 'vodka', 'prescription', 'counterfeit', 'replica', 'knockoff', 'designer-style', 'gift card',
    ];

    /** The first prohibited word found in the text, or null. */
    public function match(string ...$texts): ?string
    {
        $haystack = mb_strtolower(implode(' ', $texts));

        foreach (self::TERMS as $term) {
            if (preg_match('/(?<![\p{L}\p{N}])' . preg_quote($term, '/') . '(?![\p{L}\p{N}])/u', $haystack)) {
                return $term;
            }
        }

        return null;
    }
}
