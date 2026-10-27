<?php

namespace App\Services;

use App\Models\Bundle;
use App\Models\Product;

class CartService
{
    protected string $sessionKey = 'cart';

    public function items(): array
    {
        $cart = session($this->sessionKey, []);
        if (! $cart) {
            return [];
        }

        // Bundle lines are priced and validated as a set (see Bundle); product lines below.
        $bundleLines  = array_filter($cart, fn ($line) => ! empty($line['bundle_id']));
        $productLines = array_diff_key($cart, $bundleLines);

        // Drop lines whose product was deactivated, or whose category was, since it
        // was added — a hidden product must not be purchasable from an old cart.
        // ponytail: one indexed query per call; memoise per request if it shows up in profiling.
        $ids  = array_values(array_unique(array_column($productLines, 'id')));
        $live = Product::visible()->with('variants')->whereIn('id', $ids)->get()->keyBy('id');
        $dirty = count($live) !== count($ids);

        if ($dirty) {
            $productLines = array_filter($productLines, fn ($line) => $live->has($line['id']));
        }

        // The session only remembers WHICH product/option was added. The price is
        // re-read from the database on every call, so an admin price change reaches
        // carts that were filled earlier and the amount charged is never a stale copy.
        foreach ($productLines as $key => $line) {
            $fresh = $live[$line['id']]->effectivePriceFor($line['color'] ?? null, $line['size'] ?? null);
            if (round((float) $line['price'], 2) !== round($fresh, 2)) {
                $productLines[$key]['price'] = $fresh;
                $dirty = true;
            }
        }

        // A bundle stays in the cart only while every component is active and in stock,
        // and always at the price stored on the bundle, never a copy from the session.
        $bundles = $bundleLines
            ? Bundle::live()->with('items.product.category', 'items.product.variants', 'items.variant')
                ->whereIn('id', array_column($bundleLines, 'bundle_id'))->get()->keyBy('id')
            : collect();
        foreach ($bundleLines as $key => $line) {
            $bundle = $bundles->get($line['bundle_id']);
            if (! $bundle || $bundle->availableQty() < 1) {
                unset($bundleLines[$key]);
                $dirty = true;
                continue;
            }
            if (round((float) $line['price'], 2) !== round((float) $bundle->price, 2)) {
                $bundleLines[$key]['price'] = (float) $bundle->price;
                $dirty = true;
            }
        }

        $cart = $productLines + $bundleLines;

        if ($dirty) {
            session([$this->sessionKey => $cart]);
        }

        return $cart;
    }

    /** What a cart page prints for a line: it identifies the line without showing the product's row number. */
    public static function ref(string $key): string
    {
        return substr(hash_hmac('sha256', $key, (string) config('app.key')), 0, 24);
    }

    /** The real line key for what a form sent: a ref from the page, or (older callers) the key itself. */
    public function resolveKey(string $given): string
    {
        $cart = session('cart', []);
        if (isset($cart[$given])) {
            return $given;
        }

        return collect(array_keys($cart))->first(fn ($k) => hash_equals(self::ref((string) $k), $given)) ?? $given;
    }

    public function getQty(int $productId, ?string $color, ?string $size): int
    {
        $key  = $this->makeKey($productId, $color, $size);
        $cart = $this->items();
        return $cart[$key]['qty'] ?? 0;
    }

    public function add(Product $product, int $qty = 1, ?string $color = null, ?string $size = null): void
    {
        $cart = $this->items();
        $key  = $this->makeKey($product->id, $color, $size);

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            // Price the exact colour/size chosen — variant price when one exists,
            // else the product's base price (Product::effectivePriceFor). The SKU
            // of the resolved variant is snapshotted for the order later.
            $variant = $product->variantFor('size', $size) ?? $product->variantFor('color', $color);

            $cart[$key] = [
                'key'   => $key,
                'id'    => $product->id,
                'name'  => $product->name,
                'slug'  => $product->slug,
                'price' => $product->effectivePriceFor($color, $size),
                'sku'   => $variant?->sku ?? $product->sku,
                'image' => $product->image,
                'qty'   => $qty,
                'color' => $color,
                'size'  => $size,
            ];
        }

        session([$this->sessionKey => $cart]);
    }

    /**
     * Add a whole set as one cart line, priced from the bundle row.
     *
     * With $swap (the default) the set REPLACES the loose items it contains: if the shopper
     * already holds the throw and then takes "Cozy Night In", the throw moves into the set
     * instead of being bought twice. Only the quantity the set covers is taken out.
     *
     * @return string[] names of the products that were swapped into the set
     */
    public function addBundle(Bundle $bundle, int $qty = 1, bool $swap = true): array
    {
        $cart    = $this->items();
        $key     = 'bundle|' . $bundle->id;
        $swapped = [];

        if ($swap) {
            $bundle->loadMissing('items.product', 'items.variant');
            foreach ($bundle->items as $item) {
                $need = $item->qty * $qty;
                foreach ($cart as $lineKey => $line) {
                    if ($need <= 0) {
                        break;
                    }
                    if (! empty($line['bundle_id']) || (int) ($line['id'] ?? 0) !== (int) $item->product_id) {
                        continue;
                    }

                    $match = $item->variant
                        ? strtolower(trim((string) ($item->variant->type === 'color' ? ($line['color'] ?? '') : ($line['size'] ?? '')))) === strtolower(trim($item->variant->value))
                        : (empty($line['color']) && empty($line['size']));
                    if (! $match) {
                        continue;
                    }

                    $take = min($need, (int) $line['qty']);
                    $need -= $take;
                    $cart[$lineKey]['qty'] -= $take;
                    if ($cart[$lineKey]['qty'] <= 0) {
                        unset($cart[$lineKey]);
                    }
                    $swapped[] = $item->product->name;
                }
            }
        }

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $cart[$key] = [
                'key' => $key, 'id' => null, 'bundle_id' => $bundle->id, 'name' => $bundle->name, 'slug' => null,
                'price' => (float) $bundle->price, 'sku' => null, 'image' => $bundle->image(),
                'qty' => $qty, 'color' => null, 'size' => null,
            ];
        }

        session([$this->sessionKey => $cart]);

        return array_values(array_unique($swapped));
    }

    public function update(string $cartKey, int $qty): void
    {
        $cart = $this->items();

        if (isset($cart[$cartKey])) {
            if ($qty <= 0) {
                unset($cart[$cartKey]);
            } else {
                $cart[$cartKey]['qty'] = $qty;
            }
            session([$this->sessionKey => $cart]);
        }
    }

    public function remove(string $cartKey): void
    {
        $cart = $this->items();
        unset($cart[$cartKey]);
        session([$this->sessionKey => $cart]);
    }

    public function clear(): void
    {
        session()->forget([$this->sessionKey, CouponService::SESSION]);
    }

    public function total(): float
    {
        return array_reduce($this->items(), fn($carry, $item) => $carry + ($item['price'] * $item['qty']), 0.0);
    }

    public function count(): int
    {
        return array_reduce($this->items(), fn($carry, $item) => $carry + $item['qty'], 0);
    }

    private function makeKey(int $productId, ?string $color, ?string $size): string
    {
        return $productId . '|' . ($color ?? '') . '|' . ($size ?? '');
    }

    /** Product id => the store that sells it, for grouping cart lines. House products show as "Peyton Ghalib". */
    public function sellerNames(array $items): array
    {
        $ids = collect($items)->pluck('id')->filter()->unique();

        return Product::with('seller:id,store_name')->whereIn('id', $ids)->get()
            ->mapWithKeys(fn ($p) => [$p->id => $p->seller?->store_name ?? 'Peyton Ghalib'])->all();
    }
}
