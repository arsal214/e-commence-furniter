<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Support\Facades\DB;

/**
 * A seller's store-wide sale. It is applied by writing ordinary sale prices on the seller's products,
 * so the product page, listings, cart, checkout and payouts all keep using the one price rule.
 * A seller's own, better sale price is never replaced, and is put back exactly when the sale ends.
 */
class StoreSale
{
    public const MIN_PERCENT = 5;
    public const MAX_PERCENT = 70;

    public function start(Seller $seller, int $percent): void
    {
        DB::transaction(function () use ($seller, $percent) {
            $seller->update(['sale_percent' => $percent, 'sale_active' => true]);
            $seller->products()->with('variants')->get()->each(fn (Product $p) => $this->refresh($p, $seller->fresh()));
        });
    }

    public function stop(Seller $seller): void
    {
        DB::transaction(function () use ($seller) {
            $seller->update(['sale_active' => false]);
            $seller->products()->with('variants')->get()->each(fn (Product $p) => $this->clear($p));
        });
    }

    /** Puts one product (new, edited or just covered by a new percentage) in line with the seller's current sale. */
    public function refresh(Product $product, ?Seller $seller = null): void
    {
        $seller ??= $product->seller;
        $this->clear($product);
        if (! $seller || ! $seller->sale_active || ! $seller->sale_percent) {
            return;
        }

        $factor = 1 - $seller->sale_percent / 100;
        $sale = round((float) $product->price * $factor, 2);
        $own = (float) $product->sale_price;
        if ($sale >= 0.01 && ! ($own > 0 && $own <= $sale)) {
            $product->forceFill(['sale_backup' => $own > 0 ? $own : null, 'sale_price' => $sale, 'storewide_sale' => true])->save();
        }

        foreach ($product->variants as $v) {
            $vs = round((float) $v->price * $factor, 2);
            if ($vs >= 0.01 && ! ((float) $v->sale_price > 0)) {
                $v->forceFill(['sale_price' => $vs, 'storewide_sale' => true])->save();
            }
        }
    }

    /** Takes this sale off one product, restoring the seller's own sale price if there was one. */
    public function clear(Product $product): void
    {
        $product->loadMissing('variants');
        if ($product->storewide_sale) {
            $product->forceFill(['sale_price' => $product->sale_backup, 'sale_backup' => null, 'storewide_sale' => false])->save();
        }
        $product->variants->where('storewide_sale', true)->each(fn (ProductVariant $v) => $v->forceFill(['sale_price' => null, 'storewide_sale' => false])->save());
        $product->unsetRelation('variants');
        $product->load('variants');
    }
}
