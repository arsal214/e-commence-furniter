<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'city', 'state', 'zip', 'country',
        'address', 'address2', 'notes', 'payment_method',
        'payment_status', 'stripe_payment_intent', 'stripe_livemode', 'shipping',
        'subtotal', 'shipping_cost', 'total', 'status',
        'tracking_number', 'supplier_name', 'supplier_order_id',
        'supplier_tracking', 'carrier', 'shipped_at',
        'payment_token', 'payment_requested_at', 'payment_request_count',
    ];

    protected $casts = [
        'shipped_at'           => 'datetime',
        'payment_requested_at' => 'datetime',
        'stripe_livemode'      => 'boolean',
    ];

    /**
     * Whether an admin can still chase this order for payment: the money is
     * outstanding and the order is alive. A cancelled order is neither.
     */
    public function awaitingPayment(): bool
    {
        return $this->payment_status !== 'paid' && $this->status !== 'cancelled';
    }

    /**
     * The order's pay-link token, minted on first use.
     *
     * Kept for the life of the order rather than regenerated per email, so a
     * customer who digs out the first reminder after a second one was sent still
     * lands on a working page.
     */
    public function ensurePaymentToken(): string
    {
        if (! $this->payment_token) {
            $this->forceFill(['payment_token' => Str::random(48)])->save();
        }

        return $this->payment_token;
    }

    /** Null until a token exists — call ensurePaymentToken() before emailing it. */
    public function getPayUrlAttribute(): ?string
    {
        return $this->payment_token ? url('/pay/' . $this->payment_token) : null;
    }

    /** A Stripe order taken against test keys — not real money. */
    public function isStripeTestOrder(): bool
    {
        return $this->payment_method === 'stripe' && $this->stripe_livemode === false;
    }

    /**
     * 'live' | 'test' | null. Null covers COD and any Stripe order placed before
     * the column existed, where the mode genuinely isn't known.
     */
    public function getStripeModeAttribute(): ?string
    {
        if ($this->payment_method !== 'stripe' || $this->stripe_livemode === null) {
            return null;
        }

        return $this->stripe_livemode ? 'live' : 'test';
    }

    public function scopeStripeTest($query)
    {
        return $query->where('payment_method', 'stripe')->where('stripe_livemode', false);
    }

    public function scopeStripeLive($query)
    {
        return $query->where('payment_method', 'stripe')->where('stripe_livemode', true);
    }

    /**
     * Real orders only — sandbox payments carry no money and must not reach the
     * revenue figures. Null is kept in, since COD orders and pre-column Stripe
     * orders are real; only an explicit false is excluded.
     */
    public function scopeExcludingStripeTest($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('stripe_livemode')->orWhere('stripe_livemode', true);
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deliveryProofs()
    {
        return $this->hasMany(DeliveryProof::class)->latest('id');
    }

    /**
     * State and country are stored as codes; the display names live in
     * config/checkout.php. Orders taken before those columns existed have no
     * code at all, so every accessor here degrades to null rather than
     * inventing a location.
     */
    public function getCountryLabelAttribute(): ?string
    {
        return $this->country
            ? config("checkout.countries.{$this->country}.label", $this->country)
            : null;
    }

    public function getStateLabelAttribute(): ?string
    {
        if (! $this->state) {
            return null;
        }

        return config("checkout.countries.{$this->country}.subdivisions.{$this->state}", $this->state);
    }

    /**
     * The address as it would be written on a label, skipping anything missing
     * so an older order doesn't render stray commas.
     */
    public function getFormattedAddressAttribute(): string
    {
        $cityLine = collect([
            $this->city,
            $this->state_label,
        ])->filter()->implode(', ');

        return collect([
            $this->address,
            $this->address2,
            trim($cityLine.' '.$this->zip),
            $this->country_label,
        ])->map(fn ($line) => trim((string) $line))->filter()->implode("\n");
    }
}
