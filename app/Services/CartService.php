<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    protected string $sessionKey = 'cart';

    public function items(): array
    {
        return session($this->sessionKey, []);
    }

    public function add(Product $product, int $qty = 1, ?string $color = null, ?string $size = null): void
    {
        $cart = $this->items();
        $key  = $this->makeKey($product->id, $color, $size);

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $cart[$key] = [
                'key'   => $key,
                'id'    => $product->id,
                'name'  => $product->name,
                'slug'  => $product->slug,
                'price' => (float) $product->price,
                'image' => $product->image,
                'qty'   => $qty,
                'color' => $color,
                'size'  => $size,
            ];
        }

        session([$this->sessionKey => $cart]);
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
        session()->forget($this->sessionKey);
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
}
