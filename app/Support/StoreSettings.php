<?php

namespace App\Support;

use App\Models\StoreSetting;

/**
 * Store settings editable from Admin -> Store Settings.
 *
 * A value saved there overrides the matching config value (which itself comes from .env)
 * on every web request. A setting with no saved value keeps the .env/config default, and a
 * missing table (migration not run yet) changes nothing. Secrets (payment keys, access
 * tokens) are deliberately NOT listed here: they stay in .env.
 */
class StoreSettings
{
    /**
     * key => [group, label, help, type (bool|int|money), config path, min, max]
     */
    public static function definitions(): array
    {
        return [
            'shipping_fee_enabled' => ['Shipping', 'Free-shipping threshold', 'Off: shipping is free on every order. On: free at or above the threshold, flat rate below it, and add-on item rules apply.', 'bool', 'checkout.shipping_fee_enabled'],
            'free_shipping_threshold' => ['Shipping', 'Free shipping from ($)', 'Orders at or above this product subtotal ship free (judged before any welcome discount).', 'money', 'checkout.free_shipping_threshold', 1, 1000],
            'flat_shipping_rate' => ['Shipping', 'Flat shipping rate under the threshold ($)', 'Charged on orders below the threshold.', 'money', 'checkout.flat_shipping_rate', 0, 100],

            'delivery_processing_days' => ['Delivery estimate', 'Processing days before it ships', 'Business days before the order leaves. Weekends and US federal holidays are skipped.', 'int', 'checkout.delivery.processing_days', 0, 10],
            'delivery_min_days' => ['Delivery estimate', 'Fastest delivery (business days)', 'Start of the delivery window shown on product pages and in the shipping policy.', 'int', 'checkout.delivery.min_days', 1, 30],
            'delivery_max_days' => ['Delivery estimate', 'Slowest delivery (business days)', 'End of the window. Must not be lower than the fastest.', 'int', 'checkout.delivery.max_days', 1, 45],

            'welcome_offer_enabled' => ['Offers & badges', 'Welcome offer master switch', 'Emergency switch. The offer itself is configured under Welcome Offer; turning this Off hides it everywhere at once.', 'bool', 'checkout.welcome_offer_enabled'],
            'sold_badge_enabled' => ['Offers & badges', 'Show "N sold" on product cards', 'Real units from paid orders in the last 30 days.', 'bool', 'checkout.sold_badge_enabled'],
            'sold_badge_min' => ['Offers & badges', 'Show "N sold" only from', 'Hide the badge below this many units.', 'int', 'checkout.sold_badge_min', 1, 1000],

            'apple_pay' => ['Payments & tracking', 'Mention Apple Pay / Google Pay', 'Turn on only after the wallets work in Stripe and Apple Pay domain verification is done. This controls the icon and FAQ wording, not Stripe itself.', 'bool', 'checkout.apple_pay'],
            'meta_capi_enabled' => ['Payments & tracking', 'Send Meta server-side events', 'Conversions API. Also needs META_CAPI_TOKEN in .env; with no token nothing is sent.', 'bool', 'services.meta.capi_enabled'],
        ];
    }

    /** All saved overrides as key => raw string. A missing table counts as "none". */
    public static function saved(): array
    {
        try {
            return StoreSetting::pluck('value', 'key')->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /** The .env/config value each setting would have with no override (snapshotted once per app instance). */
    public static function defaults(): array
    {
        if (! app()->bound('store_settings.defaults')) {
            $d = [];
            foreach (self::definitions() as $key => $def) {
                $d[$key] = config($def[4]);
            }
            app()->instance('store_settings.defaults', $d);
        }

        return app('store_settings.defaults');
    }

    /** Push saved overrides into config for this request. */
    public static function apply(): void
    {
        $defs     = self::definitions();
        $defaults = self::defaults();   // snapshot before anything is overridden
        $saved    = self::saved();

        // Saved values are applied; a setting that WAS applied earlier in this process but has
        // since been cleared goes back to its .env default (matters when one process serves
        // several requests). Settings never saved are left exactly as config holds them.
        $applied = app()->bound('store_settings.applied') ? app('store_settings.applied') : [];
        foreach ($defs as $key => $def) {
            $raw = $saved[$key] ?? null;
            if ($raw !== null && $raw !== '') {
                config([$def[4] => self::cast($def[3], $raw)]);
                $applied[$key] = true;
            } elseif (! empty($applied[$key])) {
                config([$def[4] => $defaults[$key]]);
                unset($applied[$key]);
            }
        }
        app()->instance('store_settings.applied', $applied);

        // A hand-edited or conflicting pair must never produce a backwards delivery window.
        if (config('checkout.delivery.max_days') < config('checkout.delivery.min_days')) {
            config(['checkout.delivery.max_days' => config('checkout.delivery.min_days')]);
        }
    }

    public static function cast(string $type, mixed $raw): bool|int|float
    {
        return match ($type) {
            'bool' => in_array(strtolower((string) $raw), ['1', 'on', 'true'], true),
            'int'  => (int) $raw,
            default => round((float) $raw, 2),
        };
    }

    /** Store (or clear, when $value is null/blank) one setting. */
    public static function put(string $key, ?string $value): void
    {
        if (! isset(self::definitions()[$key])) {
            return;
        }

        if ($value === null || trim($value) === '') {
            StoreSetting::where('key', $key)->delete();
        } else {
            StoreSetting::updateOrCreate(['key' => $key], ['value' => trim($value)]);
        }
    }
}
