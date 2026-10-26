<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BundleItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['bundle_id', 'product_id', 'variant_id', 'qty'];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /** Current regular price of one unit of this component. */
    public function unitPrice(): float
    {
        return (float) ($this->variant?->effective_price ?? $this->product?->effective_price ?? 0);
    }

    /** How many sets this component can supply (0 when the product or variant is hidden/out of stock). */
    public function available(): int
    {
        $product = $this->product;
        if (! $product || ! $product->is_active || ! $product->category?->is_active) {
            return 0;
        }

        if ($this->variant) {
            if (! $this->variant->is_active) {
                return 0;
            }
            $stock = (int) $this->variant->stock;
        } else {
            $stock = (int) $product->stock;
        }

        return intdiv(max(0, $stock), max(1, $this->qty));
    }
}
