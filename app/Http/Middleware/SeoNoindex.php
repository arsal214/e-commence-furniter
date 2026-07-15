<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SEO: emit `X-Robots-Tag: noindex, follow` on utility / private / thin pages so
 * they don't become Google landing pages. Using the header (instead of blocking
 * in robots.txt) lets Google crawl the page and actually read the directive —
 * the audit flagged pages that were robots-blocked AND carried index signals.
 * "follow" keeps link equity flowing to the real product/category pages.
 */
class SeoNoindex
{
    /** Exact paths (leading slash, no trailing) that must never be indexed. */
    private const PATHS = [
        '/login', '/register', '/forgot-password', '/forger-password',
        '/my-account', '/my-profile', '/edit-account', '/order-history',
        '/wishlist', '/cart', '/checkout', '/checkout/success',
        '/thank-you', '/track-order', '/coming-soon', '/error',
        '/invoice', '/payment-confirmation', '/payment-success',
        '/payment-failure', '/short-code',
    ];

    /** Path prefixes whose sub-paths are all private. */
    private const PREFIXES = ['/reset-password', '/admin'];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldNoindex($request)) {
            $response->headers->set('X-Robots-Tag', 'noindex, follow');
        }

        return $response;
    }

    private function shouldNoindex(Request $request): bool
    {
        $path = '/' . trim($request->path(), '/');
        $path = $path === '/' ? '/' : rtrim($path, '/');

        if (in_array($path, self::PATHS, true)) {
            return true;
        }

        foreach (self::PREFIXES as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return true;
            }
        }

        // Internal search result listings (e.g. /shop?search=chair) are thin,
        // near-duplicate pages — index the clean /shop, not every query.
        if ($path === '/shop' && $request->filled('search')) {
            return true;
        }

        return false;
    }
}
