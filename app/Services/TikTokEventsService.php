<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use function Illuminate\Support\defer;

/**
 * Server-side TikTok Events API (v1.3) integration.
 *
 * Pairs with the browser pixel in layouts/main.blade.php. Every event sent from
 * here is emitted with an event_id that the browser pixel re-uses verbatim, so
 * TikTok deduplicates the browser/server pair into a single conversion.
 *
 * Failures are logged and swallowed: analytics must never break a checkout.
 */
class TikTokEventsService
{
    /** Session key holding events the browser pixel still needs to fire. */
    public const BROWSER_QUEUE = 'tiktok_browser_events';

    public function enabled(): bool
    {
        return (bool) config('services.tiktok.enabled')
            && filled(config('services.tiktok.pixel_id'))
            && filled(config('services.tiktok.access_token'));
    }

    /**
     * Generate a deduplication key shared by the server event and its browser twin.
     */
    public function newEventId(string $event): string
    {
        return $event . '.' . Str::uuid()->toString();
    }

    /**
     * Normalise then SHA-256 an identifier, per TikTok's Advanced Matching spec:
     * trim whitespace, lowercase, then hash. Returns null for empty input so the
     * key can be omitted rather than sent as a hash of "".
     */
    public function hash(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return hash('sha256', Str::lower($value));
    }

    /**
     * Phone numbers must be hashed in E.164 form (digits + leading '+'),
     * otherwise the hash will never match TikTok's side.
     */
    public function hashPhone(?string $phone): ?string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $phone);

        if ($digits === '' || $digits === null) {
            return null;
        }

        // Assume US/CA when no country code is present (10 national digits).
        if (strlen($digits) === 10) {
            $digits = '1' . $digits;
        }

        return hash('sha256', '+' . $digits);
    }

    /**
     * Build the `user` object: hashed PII plus the click/cookie identifiers and
     * IP/user-agent that TikTok uses for match-rate.
     */
    public function buildUser(Request $request, ?string $email = null, ?string $phone = null): array
    {
        $email ??= $request->user()?->email;

        $user = array_filter([
            'email'      => $this->hash($email),
            'phone'      => $this->hashPhone($phone),
            'external_id' => $this->hash((string) $request->user()?->id ?: null),
            'ttclid'     => $request->query('ttclid') ?: $request->cookie('ttclid'),
            'ttp'        => $request->cookie('_ttp'),
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ], fn ($v) => filled($v));

        return $user;
    }

    /**
     * Queue the browser-side twin of a server event. The layout renders these as
     * ttq.track(...) calls carrying the identical event_id.
     *
     * @param  bool  $immediate  true for events rendered in the current response
     *                           (a GET page view), false when the response is a
     *                           redirect and the twin must survive to the next request.
     */
    public function queueBrowserEvent(string $event, string $eventId, array $properties, bool $immediate = false): void
    {
        $existing = session(self::BROWSER_QUEUE, []);
        $existing[] = ['event' => $event, 'event_id' => $eventId, 'properties' => $properties];

        $immediate
            ? session()->now(self::BROWSER_QUEUE, $existing)
            : session()->flash(self::BROWSER_QUEUE, $existing);
    }

    /**
     * Fire a server event. Dispatched after the response is sent, so the HTTP
     * round-trip to TikTok never delays the user.
     */
    public function track(string $event, string $eventId, array $properties, array $user, ?string $pageUrl = null): void
    {
        if (! $this->enabled()) {
            return;
        }

        $payload = [
            'event_source'    => 'web',
            'event_source_id' => config('services.tiktok.pixel_id'),
            'data'            => [array_filter([
                'event'      => $event,
                'event_time' => time(),
                'event_id'   => $eventId,
                'user'       => $user,
                'page'       => $pageUrl ? ['url' => $pageUrl] : null,
                'properties' => $properties,
            ], fn ($v) => filled($v))],
        ];

        if ($code = config('services.tiktok.test_event_code')) {
            $payload['test_event_code'] = $code;
        }

        defer(fn () => $this->send($payload, $event));
    }

    protected function send(array $payload, string $event): void
    {
        try {
            $response = Http::withHeaders([
                'Access-Token' => config('services.tiktok.access_token'),
                'Content-Type' => 'application/json',
            ])->timeout(8)->post(config('services.tiktok.endpoint'), $payload);

            // TikTok answers 200 OK with a non-zero body `code` on rejection,
            // so an HTTP-level check alone would hide real failures.
            $code = $response->json('code');

            if (! $response->successful() || $code !== 0) {
                Log::warning('TikTok Events API rejected ' . $event, [
                    'http_status' => $response->status(),
                    'code'        => $code,
                    'message'     => $response->json('message'),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('TikTok Events API request failed for ' . $event . ': ' . $e->getMessage());
        }
    }

    /**
     * Map cart/order line items into TikTok `contents`.
     *
     * Per the integration spec this store does not track SKUs, so content_id is
     * intentionally omitted and only content_name/quantity/price are sent.
     */
    public function contents(iterable $items): array
    {
        $contents = [];

        foreach ($items as $item) {
            $contents[] = array_filter([
                'content_name' => $item['name'] ?? null,
                'quantity'     => isset($item['qty']) ? (int) $item['qty'] : null,
                'price'        => isset($item['price']) ? (float) $item['price'] : null,
            ], fn ($v) => filled($v));
        }

        return $contents;
    }
}
