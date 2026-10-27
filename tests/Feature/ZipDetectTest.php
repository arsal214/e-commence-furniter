<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(fn () => Cache::flush());

function zipFrom(array $json, int $status = 200): void
{
    // The first registered fake keeps winning, so it reads the reply from here and later calls only change it.
    $GLOBALS['zipFake'] = [$json, $status];
    Http::fake(['ipapi.co/*' => fn () => Http::response(...$GLOBALS['zipFake'])]);
}

it('estimates a first-time visitor\'s ZIP from a public IP and says it is an estimate', function () {
    zipFrom(['country_code' => 'US', 'postal' => '07305']);

    $res = $this->withServerVariables(['REMOTE_ADDR' => '8.8.8.8'])->postJson('/location/detect')->assertOk()->assertJson(['zip' => '07305']);
    $res->assertCookie('pg_zip', '07305')->assertCookie('pg_zip_src', 'ip');

    $this->withCookies(['pg_zip' => '07305', 'pg_zip_src' => 'ip'])->get('/')->assertSee('Estimated from your connection', false);
});

it('does not look again once the browser already has a ZIP or has been tried, and never overrides a ZIP', function () {
    zipFrom(['country_code' => 'US', 'postal' => '10001']);
    $first = $this->withServerVariables(['REMOTE_ADDR' => '8.8.8.8'])->postJson('/location/detect')->assertJson(['zip' => '10001']);

    // What the browser sends back: the encrypted cookies from the first response.
    foreach ([['pg_zip'], ['pg_zip_try']] as $names) {
        $cookies = collect($names)->mapWithKeys(fn ($n) => [$n => $first->getCookie($n, false)->getValue()])->all();
        $this->call('POST', '/location/detect', [], $cookies, [], ['REMOTE_ADDR' => '8.8.4.4', 'HTTP_ACCEPT' => 'application/json'])
            ->assertJson(['zip' => null])->assertCookieMissing('pg_zip');
    }
    Http::assertSentCount(1); // only the first visit asked the service
});

it('gives up quietly for local IPs, non-US visitors, odd values, rate limits and outages', function () {
    zipFrom(['country_code' => 'US', 'postal' => '07305']);
    $this->postJson('/location/detect')->assertJson(['zip' => null]); // test client is 127.0.0.1
    Http::assertNothingSent();

    foreach ([['country_code' => 'CA', 'postal' => 'M5V'], ['country_code' => 'US', 'postal' => '7305-1234'], ['error' => true, 'reason' => 'RateLimited']] as $i => $body) {
        Cache::flush();
        zipFrom($body);
        $this->withServerVariables(['REMOTE_ADDR' => '8.8.4.' . $i])->postJson('/location/detect')->assertJson(['zip' => null])->assertCookie('pg_zip_try');
    }

    Cache::flush();
    zipFrom([], 503);
    $this->withServerVariables(['REMOTE_ADDR' => '8.8.4.9'])->postJson('/location/detect')->assertOk()->assertJson(['zip' => null]);
});

it('looks an IP up once, not on every visit', function () {
    zipFrom(['country_code' => 'US', 'postal' => '07305']);
    foreach (range(1, 3) as $_) {
        $this->withServerVariables(['REMOTE_ADDR' => '8.8.8.8'])->postJson('/location/detect');
    }
    Http::assertSentCount(1);
});

it('lets a typed ZIP replace the estimate', function () {
    $this->withCookies(['pg_zip' => '07305', 'pg_zip_src' => 'ip'])->postJson('/location', ['zip' => '10001'])->assertOk()->assertCookie('pg_zip', '10001')->assertCookieExpired('pg_zip_src');
});
