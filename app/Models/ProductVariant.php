<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'type', 'value', 'price', 'sale_price', 'stock', 'sku',
        'image', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'price'      => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock'      => 'integer',
        'is_active'  => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeColors($query)
    {
        return $query->where('type', 'color');
    }

    public function scopeSizes($query)
    {
        return $query->where('type', 'size');
    }

    /**
     * The price actually charged for this variant: sale_price when discounted,
     * otherwise price. Mirrors Product::getEffectivePriceAttribute so variant and
     * product pricing follow the exact same discount rule.
     */
    public function getEffectivePriceAttribute(): float
    {
        $sale = $this->sale_price === null ? null : (float) $this->sale_price;

        return $sale !== null && $sale > 0 ? $sale : (float) $this->price;
    }

    /** Whether the effective price is below the list price (show a strike-through). */
    public function getHasStrikeAttribute(): bool
    {
        return $this->effective_price < (float) $this->price;
    }
}
