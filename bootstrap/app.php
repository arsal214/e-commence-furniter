<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'       => \App\Http\Middleware\AdminMiddleware::class,
            'must-reset'  => \App\Http\Middleware\EnsurePasswordSet::class,
        ]);
        // Gmail/Outlook one-click unsubscribe POSTs the List-Unsubscribe URL with
        // no session and no token. The signature on the URL is the credential.
        $middleware->validateCsrfTokens(except: [
            'unsubscribe',
        ]);
        // SEO: 301 trailing-slash URLs to their slashless canonical.
        $middleware->web(prepend: [
            \App\Http\Middleware\RedirectTrailingSlash::class,
        ]);
        // SEO: noindex header on utility / private / search pages.
        // Security: CSP, HSTS, clickjacking (X-Frame-Options), COOP.
        $middleware->web(append: [
            \App\Http\Middleware\SeoNoindex::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
