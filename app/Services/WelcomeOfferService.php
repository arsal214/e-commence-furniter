<?php

namespace App\Services;

use App\Models\Order;
use App\Models\WelcomeClaim;
use App\Models\WelcomeOffer;
use App\Models\WelcomeOfferTier;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * New-customer welcome offer. Every decision is made here, on the server, from
 * the database: the browser (and its cookie) only decides what to *show*.
 */
class WelcomeOfferService
{
    /** Why a claim can't be honoured; the keys double as machine-readable reasons in tests. */
    public const REASONS = [
        'no_offer'      => 'The welcome offer is not running right now.',
        'no_claim'      => 'No welcome deal is saved for this email.',
        'expired'       => 'Your welcome deal has expired.',
        'used'          => 'This welcome deal has already been used.',
        'returning'     => 'The welcome deal is for first orders only.',
        'address'       => 'The welcome deal has already been used for this address.',
        'below_minimum' => 'Your order does not reach the minimum for a welcome deal yet.',
    ];

    /** Lower-case, strip +tags, and drop gmail dots, so aliases of one inbox share a key. */
    public function emailKey(string $email): string
    {
        $email = Str::lower(trim($email));
        [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');
        $local = explode('+', $local, 2)[0];

        if (in_array($domain, ['gmail.com', 'googlemail.com'], true)) {
            $local  = str_replace('.', '', $local);
            $domain = 'gmail.com';
        }

        return $local . '@' . $domain;
    }

    public function isDisposable(string $email): bool
    {
        $domain = Str::after(Str::lower(trim($email)), '@');

        return in_array($domain, config('checkout.disposable_email_domains', []), true);
    }

    /** Has this email (any alias spelling of the exact address) ever paid for an order? */
    public function hasPaidOrder(string $email): bool
    {
        return Order::where('payment_status', 'paid')
            ->whereRaw('LOWER(email) = ?', [Str::lower(trim($email))])
            ->exists();
    }

    /**
     * Whether to *show* the offer to this visitor. A logged-in customer with a paid
     * order never sees it; a visitor who already holds a claim sees the claimed state
     * instead (handled by the caller).
     */
    public function isEligibleVisitor(?\App\Models\User $user = null): bool
    {
        if (! WelcomeOffer::live()) {
            return false;
        }

        return ! ($user && $this->hasPaidOrder($user->email));
    }

    /**
     * Create (or return) the claim for an email. Claiming twice never creates a
     * second offer and never extends the expiry.
     *
     * @throws \DomainException with a customer-safe message when the email can't claim.
     */
    public function claim(string $email, ?string $visitorId = null, bool $smsOptIn = false, ?string $ip = null): WelcomeClaim
    {
        $offer = WelcomeOffer::live();
        if (! $offer) {
            throw new \DomainException(self::REASONS['no_offer']);
        }

        $email = Str::lower(trim($email));
        if ($this->isDisposable($email)) {
            throw new \DomainException('Please use a permanent email address.');
        }
        if ($this->hasPaidOrder($email)) {
            throw new \DomainException(self::REASONS['returning']);
        }

        $key      = $this->emailKey($email);
        $existing = WelcomeClaim::where('welcome_offer_id', $offer->id)->where('email_key', $key)->first();
        if ($existing) {
            return $existing;
        }

        try {
            return WelcomeClaim::create([
                'welcome_offer_id' => $offer->id,
                'email'            => $email,
                'email_key'        => $key,
                'code'             => 'WELCOME-' . Str::upper(Str::random(6)),
                'visitor_id'       => $visitorId,
                'ip_hash'          => $ip ? hash('sha256', $ip . config('app.key')) : null,
                'sms_opt_in'       => $smsOptIn,
                'claimed_at'       => now(),
                'expires_at'       => now()->addHours(max(1, (int) $offer->expiry_hours)),
            ]);
        } catch (QueryException $e) {
            // Two simultaneous claims for one inbox: the unique index lets exactly one win.
            return WelcomeClaim::where('welcome_offer_id', $offer->id)->where('email_key', $key)->firstOrFail();
        }
    }

    /** The claim an email may redeem on the live offer, or null. */
    public function claimFor(string $email): ?WelcomeClaim
    {
        $offer = WelcomeOffer::live();

        return $offer
            ? WelcomeClaim::where('welcome_offer_id', $offer->id)->where('email_key', $this->emailKey($email))->first()
            : null;
    }

    /**
     * Best tier for a basket: the largest money-off among the tiers the subtotal
     * qualifies for. Free-shipping tiers count only when shipping actually has a fee.
     *
     * @return array{tier: ?WelcomeOfferTier, discount: float, shipping_discount: float}
     */
    public function bestTierFor(WelcomeOffer $offer, float $subtotal, float $shippingCost = 0.0): array
    {
        $best = ['tier' => null, 'discount' => 0.0, 'shipping_discount' => 0.0];

        foreach ($offer->tiers as $tier) {
            if ($tier->type === WelcomeOfferTier::FREE_SHIPPING) {
                if (config('checkout.shipping_fee_enabled') && $shippingCost > 0 && $subtotal + 0.00001 >= (float) $tier->min_subtotal
                    && $best['tier'] === null && $best['discount'] === 0.0) {
                    $best = ['tier' => $tier, 'discount' => 0.0, 'shipping_discount' => $shippingCost];
                }
                continue;
            }

            $off = $tier->discountFor($subtotal);
            if ($off > $best['discount']) {
                $best = ['tier' => $tier, 'discount' => $off, 'shipping_discount' => 0.0];
            }
        }

        return $best;
    }

    /**
     * Everything the server checks before a welcome discount reaches an order.
     * The card-fingerprint check can't run here (no card yet); it runs when payment
     * settles (markUsed).
     *
     * @param array{address?: ?string, address2?: ?string, zip?: ?string, country?: ?string} $ship
     * @return array{claim: ?WelcomeClaim, tier: ?WelcomeOfferTier, discount: float, shipping_discount: float, reason: ?string}
     */
    public function validateAtCheckout(string $email, float $subtotal, float $shippingCost, array $ship = []): array
    {
        $none = fn (?string $reason, ?WelcomeClaim $claim = null) => [
            'claim' => $claim, 'tier' => null, 'discount' => 0.0, 'shipping_discount' => 0.0, 'reason' => $reason,
        ];

        $offer = WelcomeOffer::live();
        if (! $offer) {
            return $none(null); // nothing running: silently nothing to apply
        }

        $claim = $this->claimFor($email);
        if (! $claim) {
            return $none(null);
        }
        if ($claim->used_at) {
            return $none('used', $claim);
        }
        if ($claim->expires_at->isPast()) {
            return $none('expired', $claim);
        }
        if ($this->hasPaidOrder($email)) {
            return $none('returning', $claim);
        }

        $hash = $this->addressHash($ship['address'] ?? '', $ship['address2'] ?? '', $ship['zip'] ?? '', $ship['country'] ?? '');
        if ($hash && WelcomeClaim::whereNotNull('used_at')->where('address_hash', $hash)->where('id', '!=', $claim->id)->exists()) {
            return $none('address', $claim);
        }

        $best = $this->bestTierFor($offer, $subtotal, $shippingCost);
        if (! $best['tier']) {
            return $none('below_minimum', $claim);
        }

        return $best + ['claim' => $claim, 'reason' => null];
    }

    /** Preview of the discount for the claim held in this session (display only; checkout re-validates). */
    public function sessionDiscount(float $subtotal): float
    {
        $claim = WelcomeClaim::find(session('welcome_claim_id'));

        return $claim ? $this->validateAtCheckout($claim->email, $subtotal, 0.0)['discount'] : 0.0;
    }

    /** Lower-cased street + unit + 5-digit ZIP, with unit words and punctuation removed. */
    public function addressHash(string $address, ?string $address2, ?string $zip, ?string $country): ?string
    {
        $street = Str::lower(trim($address . ' ' . $address2));
        $street = preg_replace('/\b(apartment|apt|unit|suite|ste|floor|fl|no|number)\b/', '', $street);
        $street = preg_replace('/[^a-z0-9]/', '', $street);

        if ($street === '') {
            return null;
        }

        return hash('sha256', $street . '|' . substr(preg_replace('/\D/', '', (string) $zip), 0, 5) . '|' . Str::lower((string) $country));
    }

    /**
     * Record that an order consumed the claim. Runs once payment has settled, inside a transaction with a row lock so two
     * simultaneous settlements can't both spend it. Returns true when this order now
     * owns the claim.
     */
    public function markUsed(Order $order, ?string $cardFingerprint = null): bool
    {
        if (! $order->welcome_claim_id) {
            return false;
        }

        return DB::transaction(function () use ($order, $cardFingerprint) {
            $claim = WelcomeClaim::whereKey($order->welcome_claim_id)->lockForUpdate()->first();
            if (! $claim) {
                return false;
            }

            if ($claim->used_at) {
                if ($claim->used_order_id !== $order->id) {
                    // A second order was priced with the same claim and also got paid.
                    // The money is already taken, so the discount stands; flag it for a human.
                    Log::warning("Welcome claim {$claim->id} already used by order {$claim->used_order_id}; order {$order->id} also paid with it.");
                }

                return $claim->used_order_id === $order->id;
            }

            if ($cardFingerprint && WelcomeClaim::where('card_fingerprint', $cardFingerprint)->where('id', '!=', $claim->id)->exists()) {
                Log::warning("Welcome claim {$claim->id} (order {$order->id}): card fingerprint already used on another welcome claim.");
            }

            $claim->update([
                'used_order_id'    => $order->id,
                'used_at'          => now(),
                'discount_given'   => $order->discount,
                'used_tier_label'  => $this->bestTierFor($claim->offer()->with('tiers')->first(), (float) $order->subtotal, (float) $order->shipping_cost)['tier']?->label(),
                'card_fingerprint' => $cardFingerprint,
                'address_hash'     => $this->addressHash((string) $order->address, $order->address2, $order->zip, $order->country),
            ]);

            return true;
        });
    }
}
