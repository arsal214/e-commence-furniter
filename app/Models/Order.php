<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'city', 'state', 'zip', 'country',
        'address', 'address2', 'notes', 'payment_method',
        'payment_status', 'stripe_payment_intent', 'shipping',
        'subtotal', 'shipping_cost', 'total', 'status',
        'tracking_number', 'supplier_name', 'supplier_order_id',
        'supplier_tracking', 'carrier', 'shipped_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
