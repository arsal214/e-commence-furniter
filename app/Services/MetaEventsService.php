<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Meta Conversions API (server-side twin of the browser pixel).
 *
 * Every event carries the SAME event_id as its browser twin, so Meta counts it once.
 * Customer data is hashed exactly as Meta specifies; IP, user agent and the _fbp/_fbc
 * cookies go unhashed. Nothing is sent unless META_CAPI_ENABLED is true AND an access
 * token is set. A failure here is logged and never breaks the shopper's request.
 */
class MetaEventsService
{
    public function enabled(): bool
    {
        return (bool) config('services.meta.capi_enabled')
            && filled(config('services.meta.capi_token'))
            && filled(config('services.meta.pixel_id'));
    }

    /** A fresh id for one occurrence of an event (browser and server twins share it). */
    public function newEventId(string $event): string
    {
        return $event . '.' . Str::random(20);
    }

    public function norm(?string $value): ?string
    {
        $value = Str::lower(trim((string) $value));

        return $value === '' ? null : hash('sha256', $value);
    }

    /** Digits only, with the US country code, per Meta's phone normalisation. */
    public function hashPhone(?string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $phone);
        if (strlen($digits) === 10) {
            $digits = '1' . $digits;
        }

        return $digits === '' ? null : hash('sha256', $digits);
    }

    /**
     * user_data for a live request. $order adds name/address match keys when known.
     *
     * @return array<string,mixed>
     */
    public function buildUser(?Request $request, ?string $email = null, ?string $phone = null, ?Order $order = null): array
    {
        $email ??= $request?->user()?->email ?? $order?->email;
        $phone ??= $order?->phone;
        $first = $last = null;
        if ($order && $order->name) {
            $parts = preg_split('/\s+/', trim($order->name));
            $first = $parts[0] ?? null;
            $last  = count($parts) > 1 ? end($parts) : null;
        }

        $fbclid = $request?->query('fbclid') ?: ($request?->session()->get('attribution.fbclid'));

        return array_filter([
            'em'          => $this->norm($email) ? [$this->norm($email)] : null,
            'ph'          => $this->hashPhone($phone) ? [$this->hashPhone($phone)] : null,
            'fn'          => $this->norm($first) ? [$this->norm($first)] : null,
            'ln'          => $this->norm($last) ? [$this->norm($last)] : null,
            'ct'          => $this->norm(preg_replace('/[^a-z]/i', '', (string) $order?->city)),
            'st'          => $this->norm($order?->state),
            'zp'          => $this->norm(substr(preg_replace('/\D/', '', (string) $order?->zip), 0, 5)),
            'country'     => $this->norm($order?->country),
            'external_id' => ($id = $request?->user()?->id ?? $order?->user_id) ? [hash('sha256', (string) $id)] : null,
            'client_ip_address' => $request?->ip() ?? $order?->client_ip,
            'client_user_agent' => $request?->userAgent() ?? $order?->client_user_agent,
            'fbp'         => $request?->cookie('_fbp') ?? $order?->meta_fbp,
            'fbc'         => $request?->cookie('_fbc')
                ?? ($fbclid ? 'fb.1.' . now()->getTimestampMs() . '.' . $fbclid : $order?->meta_fbc),
        ], fn ($v) => filled($v));
    }

    /**
     * Send one event. Deferred until after the response by default, so the shopper
     * never waits on Meta.
     *
     * @param array<string,mixed> $custom
     * @param array<string,mixed> $user
     */
    public function track(string $event, string $eventId, array $custom, array $user, ?string $sourceUrl = null, bool $defer = true, ?int $eventTime = null): bool
    {
        if (! $this->enabled()) {
            return false;
        }

        $send = fn () => $this->send([
            'event_name'       => $event,
            'event_time'       => $eventTime ?? time(),
            'event_id'         => $eventId,
            'action_source'    => 'website',
            'event_source_url' => $sourceUrl,
            'user_data'        => $user,
            'custom_data'      => $custom + ['currency' => config('services.meta.currency', 'USD')],
        ]);

        if ($defer) {
            // Run once even if the application instance outlives the request (tests, Octane).
            $done = false;
            app()->terminating(function () use (&$done, $send) {
                if (! $done) {
                    $done = true;
                    $send();
                }
            });

            return true;
        }

        return $send();
    }

    protected function send(array $event): bool
    {
        $body = ['data' => [array_filter($event, fn ($v) => $v !== null)]];
        if ($code = config('services.meta.test_event_code')) {
            $body['test_event_code'] = $code;
        }

        try {
            $url = sprintf('https://graph.facebook.com/%s/%s/events', config('services.meta.graph_version', 'v21.0'), config('services.meta.pixel_id'));
            $response = Http::timeout(8)->asJson()->withToken(config('services.meta.capi_token'))->post($url, $body);

            if ($response->failed()) {
                Log::warning("Meta CAPI {$event['event_name']} failed: " . $response->status() . ' ' . $response->body());

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning("Meta CAPI {$event['event_name']} error: " . $e->getMessage());

            return false;
        }
    }

    /** content_ids / contents / value for cart-style lines ([id|bundle_id, qty, price]). */
    public function cartData(array $lines): array
    {
        $contents = collect($lines)->map(fn ($l) => [
            'id'         => (string) ($l['id'] ?? ('bundle-' . ($l['bundle_id'] ?? ''))),
            'quantity'   => (int) $l['qty'],
            'item_price' => round((float) $l['price'], 2),
        ])->values();

        return [
            'content_type' => 'product',
            'content_ids'  => $contents->pluck('id')->unique()->values()->all(),
            'contents'     => $contents->all(),
            'num_items'    => (int) $contents->sum('quantity'),
            'value'        => round($contents->sum(fn ($c) => $c['item_price'] * $c['quantity']), 2),
        ];
    }

    /**
     * Purchase for a settled (or cash-on-delivery) order. At most once per order:
     * meta_event_sent_at is claimed atomically first and released again if Meta could
     * not be reached, so the next path (return page, webhook retry) tries again.
     */
    public function purchase(Order $order): bool
    {
        if (! $this->enabled() || (float) $order->total <= 0) {
            return false;
        }

        if (! Order::whereKey($order->id)->whereNull('meta_event_sent_at')->update(['meta_event_sent_at' => now()])) {
            return false;
        }

        $order->loadMissing('items');
        $data = $this->cartData($order->items->map(fn ($i) => ['id' => $i->product_id, 'qty' => $i->qty, 'price' => $i->price])->all());
        $data['value'] = round((float) $order->total, 2);   // what was actually charged: after discount, with shipping
        $data['order_id'] = (string) $order->tracking_number;

        $ok = $this->track('Purchase', 'Purchase.order-' . $order->id, $data, $this->buildUser(null, null, null, $order),
            url('/thank-you'), defer: false, eventTime: $order->created_at?->getTimestamp());

        if (! $ok) {
            Order::whereKey($order->id)->update(['meta_event_sent_at' => null]);
        }

        return $ok;
    }
}
