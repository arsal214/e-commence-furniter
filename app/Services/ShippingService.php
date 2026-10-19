<?php

namespace App\Services;

use App\Models\Product;

/**
 * Standard shipping cost, decided on the server from config.
 *
 * Off (the default): shipping is free on every order, exactly as before.
 * On: free at/above the threshold, a flat rate below it. The threshold is judged on
 * the product subtotal BEFORE any welcome-deal discount, so a $35 basket stays "free
 * shipping" the shopper was shown, even after $5 comes off.
 */
class ShippingService
{
    public function enabled(): bool
    {
        return (bool) config('checkout.shipping_fee_enabled');
    }

    public function threshold(): float
    {
        return (float) config('checkout.free_shipping_threshold', 35);
    }

    public function flatRate(): float
    {
        return (float) config('checkout.flat_shipping_rate', 4.99);
    }

    public function cost(float $subtotal): float
    {
        if (! $this->enabled()) {
            return 0.0;
        }

        return $subtotal + 0.00001 >= $this->threshold() ? 0.0 : round($this->flatRate(), 2);
    }

    /** Dollars still needed for free shipping, or null when the feature is off / already free. */
    public function remaining(float $subtotal): ?float
    {
        if (! $this->enabled() || $subtotal + 0.00001 >= $this->threshold()) {
            return null;
        }

        return round($this->threshold() - $subtotal, 2);
    }

    /** True when the cart holds only add-on items and is still under the threshold (it can't check out). */
    public function addonOnlyBelowThreshold(array $cartItems, float $subtotal): bool
    {
        if (! $this->enabled() || $cartItems === [] || $subtotal + 0.00001 >= $this->threshold()) {
            return false;
        }

        $productIds = collect($cartItems)->pluck('id')->filter()->unique();
        if ($productIds->count() !== count($cartItems)) {
            return false; // a bundle line is in the cart: never an add-on
        }

        return Product::whereIn('id', $productIds)->where('is_addon', false)->doesntExist();
    }

    public function money(float $v): string
    {
        return '$' . rtrim(rtrim(number_format($v, 2), '0'), '.');
    }

    /** Short form for titles/descriptions ("{shipping}" token): stays in step with the threshold flag. */
    public function shortPromo(): string
    {
        return $this->enabled() ? 'Free US shipping on ' . $this->money($this->threshold()) . '+' : 'Free US shipping';
    }

    /** One sentence used wherever the site makes a shipping claim, so a flag flip updates them all. */
    public function promo(): string
    {
        return $this->enabled()
            ? 'Free US shipping on orders ' . $this->money($this->threshold()) . '+'
            : 'Free US shipping on every order';
    }
}
