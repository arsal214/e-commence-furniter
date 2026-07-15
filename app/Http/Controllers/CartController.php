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
        return view('cart', [
            'cartItems' => $this->cart->items(),
            'cartTotal' => $this->cart->total(),
        ]);
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

        $stock = (int) $product->stock;
        if ($stock > 0) {
            $inCart = $this->cart->getQty($product->id, $color, $size);
            if ($inCart >= $stock) {
                $message = '"' . $product->name . '" is already at the maximum stock quantity (' . $stock . ') in your cart.';
                if ($request->wantsJson()) {
                    return response()->json($this->cartPayload('error', $message, $product, 0), 422);
                }
                return back()->with('error', $message);
            }
            if ($inCart + $qty > $stock) {
                $qty = $stock - $inCart;
                $this->cart->add($product, $qty, $color, $size);
                $this->trackAddToCart($product, $qty, $request);
                $message = 'Only ' . $qty . ' more unit(s) added — stock limit of ' . $stock . ' reached for "' . $product->name . '".';
                if ($request->wantsJson()) {
                    return response()->json($this->cartPayload('partial', $message, $product, $qty));
                }
                return back()->with('error', $message);
            }
        }

        $this->cart->add($product, $qty, $color, $size);
        $this->trackAddToCart($product, $qty, $request);

        $message = '"' . $product->name . '" added to cart.';
        if ($request->wantsJson()) {
            return response()->json($this->cartPayload('success', $message, $product, $qty));
        }

        return back()->with('success', $message);
    }

    /**
     * Shape the JSON the "added to cart" modal consumes: the product just acted on
     * plus the live cart totals for the badge and the modal summary line.
     */
    protected function cartPayload(string $status, string $message, Product $product, int $qty): array
    {
        $image = $product->image
            ? (str_starts_with($product->image, 'assets/') ? asset($product->image) : \Storage::url($product->image))
            : asset('assets/img/logo.svg');

        return [
            'status'  => $status,
            'message' => $message,
            'product' => [
                'name'  => $product->name,
                'image' => $image,
                'qty'   => $qty,
                'price' => '$' . number_format($product->effective_price, 2),
            ],
            'cart' => [
                'count' => $this->cart->count(),
                'total' => '$' . number_format($this->cart->total(), 2),
            ],
        ];
    }

    protected function trackAddToCart(Product $product, int $qty, Request $request): void
    {
        $unitPrice  = $product->effective_price;
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
            $product = Product::find($cartItems[$request->cart_key]['id']);
            $stock   = (int) ($product->stock ?? 0);
            if ($stock > 0 && $qty > $stock) {
                return back()->with('error', 'Only ' . $stock . ' unit(s) of "' . $product->name . '" are in stock.');
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
