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

        // Only offer what can actually be bought. The tray's "Add" button posts
        // straight to cart/add with no colour/size, so the stock that governs it
        // is the product's own — filtering on it here is exactly the check the
        // add will apply. Without this the tray advertised out-of-stock items and
        // the add came back as an error, which reads as a broken cart.
        $picks = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->whereNotIn('id', $cartIds)
            ->when($catIds->isNotEmpty(), fn ($q) => $q->whereIn('category_id', $catIds))
            ->orderByRaw("$eff ASC")
            ->take(4)
            ->get();

        if ($picks->count() < 4) {
            $exclude = $picks->pluck('id')->merge($cartIds);
            $picks = $picks->merge(
                Product::where('is_active', true)
                    ->where('stock', '>', 0)
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

        // Out of stock is a hard stop, checked before anything else.
        //
        // Every cap below used to sit inside an `if ($stock > 0)` wrapper, which
        // inverted the intent: at exactly zero stock the checks were all skipped
        // and execution fell straight through to cart->add(). The one case the
        // cap exists to catch was the one case that bypassed it, so a zero-stock
        // item could be added in unlimited quantity.
        if ($stock <= 0) {
            $message = '"' . $product->name . '" is out of stock.';
            if ($request->wantsJson()) {
                return response()->json($this->cartPayload('error', $message, $product, 0, $unitPrice), 422);
            }
            return back()->with('error', $message);
        }

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

        $this->cart->add($product, $qty, $color, $size);
        $this->trackAddToCart($product, $qty, $request, $unitPrice);

        $message = '"' . $product->name . '" added to cart.';
        if ($request->wantsJson()) {
            return response()->json($this->cartPayload('success', $message, $product, $qty, $unitPrice));
        }

        return back()->with('success', $message);
    }

    /**
     * Add several products in one request — the "Frequently bought together"
     * bundle on the product page.
     *
     * Adds one unit of each, with no colour/size: the bundle offers whole products
     * rather than configured variants, so base stock is the right thing to check
     * and matches what the tile displayed.
     *
     * Partial success is the expected outcome, not an error. If one line went out
     * of stock while the page was open, the rest still go in and the message says
     * which did not — failing the whole bundle over one item would cost the sale
     * on everything else in it.
     */
    public function addMany(Request $request)
    {
        $data = $request->validate([
            'product_ids'   => 'required|array|min:1|max:10',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $products = Product::whereIn('id', $data['product_ids'])
            ->where('is_active', true)
            ->get();

        $added   = [];
        $skipped = [];

        foreach ($products as $product) {
            $stock  = $product->effectiveStockFor(null, null);
            $inCart = $this->cart->getQty($product->id, null, null);

            if ($stock <= 0 || $inCart >= $stock) {
                $skipped[] = $product->name;
                continue;
            }

            $this->cart->add($product, 1, null, null);
            $this->trackAddToCart($product, 1, $request, $product->effectivePriceFor(null, null));
            $added[] = $product->name;
        }

        if ($added === []) {
            return back()->with('error', $skipped
                ? 'Those items are out of stock right now.'
                : 'Nothing was added to your cart.');
        }

        $message = count($added) . ' item' . (count($added) === 1 ? '' : 's') . ' added to cart.';
        if ($skipped) {
            $message .= ' Unavailable: ' . implode(', ', $skipped) . '.';
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
                // Raw numbers for the Meta AddToCart pixel. The formatted `price`
                // above is display-only ("$1,299.00") and cannot be fed to fbq.
                'id'         => (string) $product->id,
                'unit_price' => round((float) $unitPrice, 2),
                'value'      => round((float) $unitPrice * $qty, 2),
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
                // Same inversion as add() had: `$stock > 0 &&` meant a zero-stock
                // line accepted any quantity at all.
                $stock = $product->effectiveStockFor($line['color'] ?? null, $line['size'] ?? null);
                if ($stock <= 0) {
                    return back()->with('error', '"' . $product->name . '" is out of stock.');
                }
                if ($qty > $stock) {
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
