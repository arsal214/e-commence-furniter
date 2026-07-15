<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Flow B: a user whose account was auto-created at guest checkout still holds a
 * temporary password. Until they set their own, funnel every authenticated
 * customer-area request to the set-password screen. The set-password routes and
 * logout are exempt so the user can actually complete (or escape) the reset.
 */
class EnsurePasswordSet
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user
            && $user->must_reset_password
            && ! $request->routeIs('account.set-password')
            && ! $request->routeIs('logout')) {
            return redirect()->route('account.set-password');
        }

        return $next($request);
    }
}
