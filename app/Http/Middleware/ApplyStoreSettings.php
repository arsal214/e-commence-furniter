<?php

namespace App\Http\Middleware;

use App\Support\StoreSettings;
use Closure;
use Illuminate\Http\Request;

/** Applies the settings saved in Admin -> Store Settings before any controller or view reads config. */
class ApplyStoreSettings
{
    public function handle(Request $request, Closure $next)
    {
        StoreSettings::apply();

        return $next($request);
    }
}
