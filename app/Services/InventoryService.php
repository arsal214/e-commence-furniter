<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Stock is read when an item is added to the cart, but the cart can sit for hours
 * and two shoppers can hold the last unit. This is the point that settles it:
 * checked again before an order is created, and taken off atomically when paid.
 */
class InventoryService
{
    /**
     * Cart lines asking for more than is on hand right now.
     *
     * @return list<string> human messages, empty when everything can be fulfilled
     */
    public function shortfalls(array $cartItems): array
    {
        $lines = collect($cartItems)->filter(fn ($l) => empty($l['bundle_id']));
        $products = Product::with('variants')->whereIn('id', $lines->pluck('id'))->get()->keyBy('id');

        // Same product in two colour/size lines still draws on one pool when it has no variants.
        $out = [];
        foreach ($lines as $line) {
            $p = $products->get($line['id']);
            $have = $p ? $p->effectiveStockFor($line['color'] ?? null, $line['size'] ?? null) : 0;
            if ($have < (int) $line['qty']) {
                $out[] = $have > 0
                    ? "Only {$have} left of \"{$line['name']}\". Please lower the quantity."
                    : "\"{$line['name']}\" just sold out.";
            }
        }

        return $out;
    }

    /**
     * Take the order's quantities off stock. Runs inside the paid-once transaction in
     * OrderSettlement, so it executes exactly once per order. Each decrement is a
     * conditional UPDATE (stock >= qty), which is atomic even with two buyers at once.
     * Money has already been taken, so a shortfall is recorded for staff, not thrown.
     */
    public function commit(Order $order): void
    {
        $problems = [];

        foreach ($order->items as $item) {
            $product = Product::with('variants')->find($item->product_id);
            if (! $product) {
                continue;
            }

            // Mirror Product::effectiveStockFor: every matching variant, else the product itself.
            $variants = collect([$product->variantFor('size', $item->size), $product->variantFor('color', $item->color)])->filter();
            $targets = $variants->isNotEmpty()
                ? $variants->map(fn ($v) => ['table' => 'product_variants', 'id' => $v->id])->all()
                : [['table' => 'products', 'id' => $product->id]];

            foreach ($targets as $t) {
                $done = DB::table($t['table'])->where('id', $t['id'])->where('stock', '>=', $item->qty)->decrement('stock', $item->qty);
                if (! $done) {
                    $problems[] = "{$item->name} x{$item->qty}";
                    DB::table($t['table'])->where('id', $t['id'])->update(['stock' => 0]);
                }
            }
        }

        if ($problems) {
            $note = 'OVERSOLD: not enough stock for ' . implode(', ', array_unique($problems)) . '. Contact the buyer or refund.';
            $order->update(['stock_issue' => $note]);
            Log::critical("Order {$order->id}: {$note}");
        }
    }
}
