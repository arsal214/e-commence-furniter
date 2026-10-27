<?php

namespace App\Http\Middleware;

use App\Models\Seller;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $seller = $request->user()?->seller;
        if (! $seller) {
            return redirect()->route('login')->with('error', 'Seller account required.');
        }

        // Seller Center opens once the application is submitted; before that, finish sign-up.
        if ($seller->onboarding_step !== 'done' && ! $request->routeIs('seller.landing')) {
            return redirect()->route('seller.' . $seller->onboarding_step);
        }

        return $next($request);
    }
}
