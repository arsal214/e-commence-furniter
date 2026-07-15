<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SEO: collapse trailing-slash URLs to their slashless canonical with a 301.
 * Prevents /product-details/foo/ and /product-details/foo being indexed as two
 * pages. Only touches GET/HEAD; leaves the site root and real files alone.
 */
class RedirectTrailingSlash
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->getPathInfo();

        if ($request->isMethod('GET') && $path !== '/' && str_ends_with($path, '/')) {
            $clean = rtrim($path, '/');
            $query = $request->getQueryString();
            $target = $clean . ($query ? '?' . $query : '');

            return redirect($target, 301);
        }

        return $next($request);
    }
}
