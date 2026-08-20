<?php

use App\Services\TikTokEventsService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    // defer() would otherwise hold the request back until the response is sent,
    // which never happens when the service is exercised directly.
    $this->withoutDefer();

    Http::fake([
        '*' => Http::response(['code' => 0, 'message' => 'OK']),
    ]);

    config([
        'services.tiktok.enabled'           => true,
        'services.tiktok.endpoint'          => 'https://business-api.tiktok.com/open_api/v1.3/event/track/',
        'services.tiktok.pixel_id'          => 'PIXEL_ONE',
        'services.tiktok.access_token'      => 'token-one',
        'services.tiktok.test_event_code'   => null,
        'services.tiktok.pixel_id_2'        => 'PIXEL_TWO',
        'services.tiktok.access_token_2'    => 'token-two',
        'services.tiktok.test_event_code_2' => null,
    ]);
});

function track(): string
{
    $service = app(TikTokEventsService::class);
    $eventId = $service->newEventId('AddToCart');

    $service->track('AddToCart', $eventId, ['value' => 10.0, 'currency' => 'USD'], ['ip' => '1.2.3.4']);

    return $eventId;
}

it('posts the event to both pixels, each with its own access token', function () {
    $eventId = track();

    Http::assertSentCount(2);

    foreach ([['PIXEL_ONE', 'token-one'], ['PIXEL_TWO', 'token-two']] as [$pixel, $token]) {
        Http::assertSent(function ($request) use ($pixel, $token, $eventId) {
            return $request['event_source_id'] === $pixel
                && $request->header('Access-Token')[0] === $token
                // Dedup is scoped to a pixel, so the browser twin's event_id is
                // reused for both rather than regenerated.
                && $request['data'][0]['event_id'] === $eventId
                && $request['data'][0]['event'] === 'AddToCart';
        });
    }
});

it('skips the second pixel when it has no access token of its own', function () {
    config(['services.tiktok.access_token_2' => null]);

    track();

    Http::assertSentCount(1);
    Http::assertSent(fn ($request) => $request['event_source_id'] === 'PIXEL_ONE');
});

it('sends each pixel its own test event code', function () {
    config([
        'services.tiktok.test_event_code'   => 'TEST111',
        'services.tiktok.test_event_code_2' => 'TEST222',
    ]);

    track();

    Http::assertSent(fn ($r) => $r['event_source_id'] === 'PIXEL_ONE' && $r['test_event_code'] === 'TEST111');
    Http::assertSent(fn ($r) => $r['event_source_id'] === 'PIXEL_TWO' && $r['test_event_code'] === 'TEST222');
});

it('sends nothing when tiktok events are disabled', function () {
    config(['services.tiktok.enabled' => false]);

    track();

    Http::assertNothingSent();
    expect(app(TikTokEventsService::class)->enabled())->toBeFalse();
});
