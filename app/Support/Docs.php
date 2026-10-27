<?php

namespace App\Support;

/** Company, help and legal pages: the copy lives in resources/docs/pages.json (from the design's pg-content.js). */
class Docs
{
    /** slug in pages.json => public path */
    public const PATHS = [
        'about' => '/about', 'contact' => '/contact', 'accessibility' => '/accessibility',
        'shipping' => '/shipping-policy', 'returns' => '/return-policy', 'buyer-protection' => '/buyer-protection',
        'seller-agreement' => '/seller-agreement', 'prohibited' => '/prohibited-items',
        'terms' => '/terms-and-conditions', 'privacy' => '/privacy-policy', 'cookies' => '/cookies', 'ip' => '/ip-policy', 'ccpa' => '/do-not-sell', 'referral-terms' => '/referral-terms', 'rewards-terms' => '/rewards-terms',
    ];

    public static function all(): array
    {
        static $docs;

        return $docs ??= json_decode(file_get_contents(resource_path('docs/pages.json')), true) ?: [];
    }

    public static function get(string $slug): ?array
    {
        return self::all()[$slug] ?? null;
    }

    /** Left navigation: documents grouped as the design groups them, the open one highlighted. */
    public static function nav(string $current): array
    {
        $groups = [];
        foreach (self::all() as $slug => $d) {
            $groups[$d['group']][] = ['t' => $d['title'], 'href' => url(self::PATHS[$slug]), 'bg' => $slug === $current ? '#FFF1E8' : 'transparent', 'fw' => $slug === $current ? '700' : '500'];
        }

        return collect($groups)->map(fn ($items, $g) => ['g' => $g, 'items' => $items])->values()->all();
    }
}
