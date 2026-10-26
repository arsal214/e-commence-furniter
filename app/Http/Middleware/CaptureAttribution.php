<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Remember the campaign that brought the visitor, so the order can carry it.
 * Last touch wins; a visit with no campaign parameters leaves the stored one alone.
 */
class CaptureAttribution
{
    public const KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'fbclid'];

    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET')) {
            $seen = collect(self::KEYS)
                ->mapWithKeys(fn ($k) => [$k => substr((string) $request->query($k, ''), 0, 255)])
                ->filter();

            if ($seen->isNotEmpty()) {
                $request->session()->put('attribution', $seen->all());
            }
        }

        return $next($request);
    }
}
