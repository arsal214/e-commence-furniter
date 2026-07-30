<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Baseline security headers (Content-Security-Policy, HSTS, clickjacking
 * mitigation, COOP) — none of these existed before, so the site was flagged
 * for all of them.
 *
 * CSP is deliberately permissive rather than strict: this codebase relies
 * heavily on inline <script>/<style> blocks and onclick="" handlers across
 * dozens of views (not something to refactor blind in one pass), so
 * 'unsafe-inline' stays in script-src/style-src. That keeps the site working
 * but doesn't buy full inline-script XSS protection — a stricter nonce-based
 * policy is a real follow-up project, not a drop-in fix.
 *
 * script-src/connect-src/frame-src allowlist every third-party the codebase
 * actually loads (GA, Meta/TikTok pixels, jsdelivr, Google Fonts, Stripe
 * Elements, Ahrefs analytics) — grep resources/views for external domains
 * before adding a new one, or it'll get silently blocked.
 *
 * COOP uses same-origin-allow-popups, not same-origin: Stripe's 3D Secure
 * step can open a popup that talks back to window.opener, and strict
 * same-origin would sever that and break checkout.
 *
 * Trusted Types is intentionally NOT enabled — TinyMCE (admin) and the
 * jQuery-plugin bundle (Owl Carousel, AOS, etc.) both write innerHTML
 * directly, and turning on `require-trusted-types-for 'script'` without a
 * policy covering every one of those call sites breaks them outright.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$response->headers->has('Content-Security-Policy')) {
            $response->headers->set('Content-Security-Policy', $this->csp());
        }

        // HSTS only makes sense once the site is confirmed HTTPS-only — it tells
        // browsers to refuse plain HTTP for the max-age window, which would be
        // actively harmful to flip on over a connection that isn't fully secured.
        if ($request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=15552000; includeSubDomains'
            );
        }

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');

        return $response;
    }

    private function csp(): string
    {
        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'self'",
            // Tracking pixels (GA/GTM, Meta, TikTok) call CDN/regional sub-domains
            // their own SDKs pick at runtime that aren't documented anywhere fixed
            // (e.g. TikTok's IPv6-enrichment beacon hit analytics-ipv6.tiktokw.us,
            // found only by actually running the page under this CSP locally) —
            // wildcarded per-vendor so a tracker doesn't silently stop reporting
            // data over a sub-domain nobody enumerated in advance. Real app code
            // stays on the exact 'self' origin, so this doesn't loosen anything
            // for first-party script.
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net "
                . "https://*.googletagmanager.com https://*.google-analytics.com "
                . "https://*.facebook.net https://*.facebook.com "
                . "https://*.tiktok.com https://*.tiktokw.us https://*.ibyteimg.com https://*.muscdn.com "
                . "https://analytics.ahrefs.com https://js.stripe.com",
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com",
            "font-src 'self' data: https://cdn.jsdelivr.net https://fonts.gstatic.com",
            "img-src 'self' data: blob: https: ",
            "connect-src 'self' https://*.google-analytics.com https://*.googletagmanager.com https://*.analytics.google.com "
                . "https://*.facebook.com https://*.facebook.net "
                . "https://*.tiktok.com https://*.tiktokw.us https://*.ibyteimg.com https://*.muscdn.com "
                . "https://analytics.ahrefs.com https://api.stripe.com",
            "frame-src 'self' https://js.stripe.com https://hooks.stripe.com https://*.facebook.com",
        ];

        return implode('; ', $directives);
    }
}
