<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeOfferTier extends Model
{
    public const FIXED = 'fixed_amount';
    public const PERCENT = 'percent';
    public const FREE_SHIPPING = 'free_shipping';

    protected $fillable = ['welcome_offer_id', 'type', 'amount', 'min_subtotal', 'max_discount', 'sort_order'];

    protected $casts = [
        'amount'       => 'decimal:2',
        'min_subtotal' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    public function offer()
    {
        return $this->belongsTo(WelcomeOffer::class, 'welcome_offer_id');
    }

    /** Money off the product subtotal for this tier (free shipping is handled on the shipping line). */
    public function discountFor(float $subtotal): float
    {
        if ($subtotal + 0.00001 < (float) $this->min_subtotal) {
            return 0.0;
        }

        $off = match ($this->type) {
            self::FIXED   => (float) $this->amount,
            self::PERCENT => round($subtotal * (float) $this->amount / 100, 2),
            default       => 0.0,
        };

        if ($this->type === self::PERCENT && $this->max_discount !== null) {
            $off = min($off, (float) $this->max_discount);
        }

        // Never more than the goods: the order total cannot go negative.
        return round(min($off, $subtotal), 2);
    }

    public function label(): string
    {
        $money = fn ($v) => '$' . rtrim(rtrim(number_format((float) $v, 2), '0'), '.');
        $base = match ($this->type) {
            self::FIXED   => $money($this->amount) . ' off',
            self::PERCENT => rtrim(rtrim(number_format((float) $this->amount, 2), '0'), '.') . '% off',
            default       => 'Free shipping',
        };

        return $base . ((float) $this->min_subtotal > 0 ? ' orders ' . $money($this->min_subtotal) . '+' : '');
    }
}
