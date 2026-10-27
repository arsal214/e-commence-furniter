<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerCoupon extends Model
{
    protected $fillable = ['seller_id', 'code', 'type', 'value', 'min_order', 'ends_at', 'max_uses', 'uses', 'is_active'];

    protected $casts = ['ends_at' => 'datetime', 'value' => 'decimal:2', 'min_order' => 'decimal:2', 'is_active' => 'boolean'];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function isUsable(): bool
    {
        return $this->is_active && (! $this->ends_at || $this->ends_at->isFuture()) && ($this->max_uses === null || $this->uses < $this->max_uses);
    }

    /** What this coupon takes off a seller's items worth $subtotal; zero when the minimum is not met. */
    public function discountOn(float $subtotal): float
    {
        if (! $this->isUsable() || $subtotal <= 0 || $subtotal < (float) $this->min_order) {
            return 0.0;
        }
        $off = $this->type === 'percent' ? $subtotal * (float) $this->value / 100 : (float) $this->value;

        return round(min($subtotal, max(0, $off)), 2);
    }

    public function label(): string
    {
        return $this->type === 'percent' ? rtrim(rtrim(number_format((float) $this->value, 2), '0'), '.') . '% off' : '$' . number_format((float) $this->value, 2) . ' off';
    }
}
