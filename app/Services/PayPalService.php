<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Thin wrapper over the PayPal Orders v2 REST API — no SDK, it's three
 * endpoints (token, create, capture) that the Laravel HTTP client covers
 * directly.
 */
class PayPalService
{
    public function livemode(): bool
    {
        return config('services.paypal.mode') === 'live';
    }

    protected function baseUrl(): string
    {
        return $this->livemode()
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    /**
     * Client-credentials token, cached just under its ~9h expiry so requests
     * don't round-trip an OAuth call each time. Keyed by mode so a sandbox
     * token is never handed to a live request or vice versa.
     */
    protected function accessToken(): string
    {
        return Cache::remember('paypal_access_token_' . config('services.paypal.mode'), 27000, function () {
            return Http::asForm()
                ->withBasicAuth(config('services.paypal.client_id'), config('services.paypal.secret'))
                ->post($this->baseUrl() . '/v1/oauth2/token', ['grant_type' => 'client_credentials'])
                ->throw()
                ->json('access_token');
        });
    }

    public function createOrder(float $amount, string $orderReference): array
    {
        return Http::withToken($this->accessToken())
            ->post($this->baseUrl() . '/v2/checkout/orders', [
                'intent'         => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $orderReference,
                    'amount'       => [
                        'currency_code' => 'USD',
                        'value'         => number_format($amount, 2, '.', ''),
                    ],
                ]],
            ])
            ->throw()
            ->json();
    }

    public function captureOrder(string $paypalOrderId): array
    {
        return Http::withToken($this->accessToken())
            // An empty array body would be sent as `[]`, which PayPal's schema
            // rejects (MALFORMED_REQUEST_JSON) — capture takes no body at all,
            // so an explicit empty object is what's actually valid here.
            ->withBody('{}', 'application/json')
            ->post($this->baseUrl() . "/v2/checkout/orders/{$paypalOrderId}/capture")
            ->throw()
            ->json();
    }
}
