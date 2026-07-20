<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\TikTokEventsService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cart, protected TikTokEventsService $tiktok) {}

    public function index()
    {
        $items = $this->cart->items();

        return view('cart', [
            'cartItems' => $items,
            'cartTotal' => $this->cart->total(),
            'crossSell' => $this->crossSell($items),
        ]);
    }

    /**
     * Companion products for the cart's "You might also like" tray: items not
     * already in the cart, preferring the shopper's own categories and cheapest
     * first (impulse-friendly). Falls back to the cheapest of anything else so the
     * tray always fills. Empty cart → empty collection (the tray isn't shown).
     */
    protected function crossSell(array $items): \Illuminate\Support\Collection
    {
        $cartIds = collect($items)->pluck('id')->filter()->values();
        if ($cartIds->isEmpty()) {
            return collect();
        }

        $eff    = 'COALESCE(NULLIF(sale_price, 0), price)';
        $catIds = Product::whereIn('id', $cartIds)->pluck('category_id')->filter()->unique()->values();

        $picks = Product::where('is_active', true)
            ->whereNotIn('id', $cartIds)
            ->when($catIds->isNotEmpty(), fn ($q) => $q->whereIn('category_id', $catIds))
            ->orderByRaw("$eff ASC")
            ->take(4)
            ->get();

        if ($picks->count() < 4) {
            $exclude = $picks->pluck('id')->merge($cartIds);
            $picks = $picks->merge(
                Product::where('is_active', true)
                    ->whereNotIn('id', $exclude)
                    ->orderByRaw("$eff ASC")
                    ->take(4 - $picks->count())
                    ->get()
            );
        }

        return $picks;
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty     = (int) $request->input('qty', 1);
        $color   = $request->input('color') ?: null;
        $size    = $request->input('size')  ?: null;

        // Price and stock follow the chosen colour/size (variant when one exists).
        $unitPrice = $product->effectivePriceFor($color, $size);
        $stock     = $product->effectiveStockFor($color, $size);
        if ($stock > 0) {
            $inCart = $this->cart->getQty($product->id, $color, $size);
            if ($inCart >= $stock) {
                $message = '"' . $product->name . '" is already at the maximum stock quantity (' . $stock . ') in your cart.';
                if ($request->wantsJson()) {
                    return response()->json($this->cartPayload('error', $message, $product, 0, $unitPrice), 422);
                }
                return back()->with('error', $message);
            }
            if ($inCart + $qty > $stock) {
                $qty = $stock - $inCart;
                $this->cart->add($product, $qty, $color, $size);
                $this->trackAddToCart($product, $qty, $request, $unitPrice);
                $message = 'Only ' . $qty . ' more unit(s) added — stock limit of ' . $stock . ' reached for "' . $product->name . '".';
                if ($request->wantsJson()) {
                    return response()->json($this->cartPayload('partial', $message, $product, $qty, $unitPrice));
                }
                return back()->with('error', $message);
            }
        }

        $this->cart->add($product, $qty, $color, $size);
        $this->trackAddToCart($product, $qty, $request, $unitPrice);

        $message = '"' . $product->name . '" added to cart.';
        if ($request->wantsJson()) {
            return response()->json($this->cartPayload('success', $message, $product, $qty, $unitPrice));
        }

        return back()->with('success', $message);
    }

    /**
     * Shape the JSON the "added to cart" modal consumes: the product just acted on
     * plus the live cart totals for the badge and the modal summary line.
     */
    protected function cartPayload(string $status, string $message, Product $product, int $qty, ?float $unitPrice = null): array
    {
        $image = $product->image
            ? (str_starts_with($product->image, 'assets/') ? asset($product->image) : \Storage::url($product->image))
            : asset('assets/img/logo.svg');

        $unitPrice ??= $product->effective_price;

        return [
            'status'  => $status,
            'message' => $message,
            'product' => [
                'name'  => $product->name,
                'image' => $image,
                'qty'   => $qty,
                'price' => '$' . number_format($unitPrice, 2),
            ],
            'cart' => [
                'count' => $this->cart->count(),
                'total' => '$' . number_format($this->cart->total(), 2),
            ],
        ];
    }

    protected function trackAddToCart(Product $product, int $qty, Request $request, ?float $unitPrice = null): void
    {
        $unitPrice ??= $product->effective_price;
        $eventId    = $this->tiktok->newEventId('AddToCart');
        $properties = [
            'content_type' => 'product',
            'contents'     => [[
                'content_name' => $product->name,
                'quantity'     => $qty,
                'price'        => $unitPrice,
            ]],
            'currency' => 'USD',
            'value'    => round($unitPrice * $qty, 2),
        ];

        $this->tiktok->track(
            'AddToCart',
            $eventId,
            $properties,
            $this->tiktok->buildUser($request),
            $request->headers->get('referer') ?: $request->fullUrl(),
        );

        // This response is a redirect — flash so the twin fires on the next page.
        $this->tiktok->queueBrowserEvent('AddToCart', $eventId, $properties);
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
            'qty'      => 'required|integer|min:0',
        ]);

        $qty       = (int) $request->qty;
        $cartItems = $this->cart->items();

        if ($qty > 0 && isset($cartItems[$request->cart_key])) {
            $line    = $cartItems[$request->cart_key];
            $product = Product::find($line['id']);
            if ($product) {
                // Respect the stock of the exact colour/size in this cart line.
                $stock = $product->effectiveStockFor($line['color'] ?? null, $line['size'] ?? null);
                if ($stock > 0 && $qty > $stock) {
                    return back()->with('error', 'Only ' . $stock . ' unit(s) of "' . $product->name . '" are in stock.');
                }
            }
        }

        $this->cart->update($request->cart_key, $qty);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request)
    {
        $request->validate(['cart_key' => 'required|string']);
        $this->cart->remove($request->cart_key);

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $this->cart->clear();
        return back()->with('success', 'Cart cleared.');
    }
}
