<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\TikTokEventsService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cart, protected TikTokEventsService $tiktok, protected \App\Services\WelcomeOfferService $welcome, protected \App\Services\ShippingService $shipping) {}

    public function index()
    {
        $items = $this->cart->items();

        $subtotal = $this->cart->total();

        return view('cart', [
            'cartItems' => $items,
            'cartTotal' => $subtotal,
            'shippingEnabled'   => $this->shipping->enabled(),
            'shippingCost'      => $this->shipping->cost($subtotal),
            'shippingRemaining' => $this->shipping->remaining($subtotal),
            'shippingThreshold' => $this->shipping->threshold(),
            'addonBlocked'      => $this->shipping->addonOnlyBelowThreshold($items, $subtotal),
            'shippingPromo'     => $this->shipping->promo(),
            // Sets that complete something already in the cart (and aren't in it yet).
            'bundleOffers'      => \App\Models\Bundle::offeredFor(collect($items)->pluck('id'), 2)
                ->reject(fn ($b) => isset($items['bundle|' . $b->id])),
            'welcomeDiscount' => $this->welcome->sessionDiscount($subtotal),
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
        $picks = Product::visible()
            ->where('stock', '>', 0)
            ->whereNotIn('id', $cartIds)
            ->when($catIds->isNotEmpty(), fn ($q) => $q->whereIn('category_id', $catIds))
            ->orderByRaw("$eff ASC")
            ->take(4)
            ->get();

        // Same-category only: an unrelated filler item (a coat beside a camera) wastes the slot.
        // Fewer suggestions, or none, is the right answer when the catalogue has no match.
        return $catIds->isEmpty() ? collect() : $picks;
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'integer|min:1',
        ]);

        $product = Product::visible()->findOrFail($request->product_id);
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

        $products = Product::visible()->whereIn('id', $data['product_ids'])
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
    /** The set that completes this product, when one is live, in stock, and really cheaper than its parts. */
    protected function bundleOffer(Product $product): ?array
    {
        $bundle = \App\Models\Bundle::offeredFor([$product->id], 1)->first();
        if (! $bundle || isset($this->cart->items()['bundle|' . $bundle->id])) {
            return null;
        }

        return [
            'id'       => $bundle->id,
            'name'     => $bundle->name,
            'price'    => '$' . number_format((float) $bundle->price, 2),
            'regular'  => '$' . number_format($bundle->regularTotal(), 2),
            'saves'    => '$' . number_format($bundle->savings(), 2),
            'includes' => $bundle->items->map(fn ($i) => $i->product->name)->values()->all(),
        ];
    }

    protected function cartPayload(string $status, string $message, Product $product, int $qty, ?float $unitPrice = null): array
    {
        $image = $product->image
            ? (str_starts_with($product->image, 'assets/') ? asset($product->image) : \Storage::url($product->image))
            : asset('assets/img/logo.svg');

        $unitPrice ??= $product->effective_price;

        $payload = [
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
                'meta_event_id' => $this->metaEventId,
            ],
            'cart' => [
                'count' => $this->cart->count(),
                'total' => '$' . number_format($this->cart->total(), 2),
            ],
        ];

        if (in_array($status, ['success', 'partial'], true)) {
            $payload['bundle'] = $this->bundleOffer($product);
        }

        return $payload;
    }

    /** Meta event id of the AddToCart just tracked; echoed to the browser so both twins share it. */
    protected ?string $metaEventId = null;

    protected function trackAddToCart(Product $product, int $qty, Request $request, ?float $unitPrice = null): void
    {
        $unitPrice ??= $product->effective_price;

        // The browser may have minted the id already (full-page submit); otherwise we do and return it.
        $given = (string) $request->input('meta_event_id');
        $this->metaEventId = preg_match('/^AddToCart\.[A-Za-z0-9]{8,40}$/', $given) ? $given : app(\App\Services\MetaEventsService::class)->newEventId('AddToCart');
        app(\App\Services\MetaEventsService::class)->track('AddToCart', $this->metaEventId, [
            'content_type' => 'product', 'content_ids' => [(string) $product->id], 'content_name' => $product->name,
            'contents' => [['id' => (string) $product->id, 'quantity' => $qty, 'item_price' => round((float) $unitPrice, 2)]],
            'value' => round($unitPrice * $qty, 2),
        ], app(\App\Services\MetaEventsService::class)->buildUser($request), $request->headers->get('referer') ?: $request->fullUrl());

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

    public function addBundle(Request $request)
    {
        $request->validate(['bundle_id' => 'required|integer', 'qty' => 'sometimes|integer|min:1|max:10']);

        $bundle = \App\Models\Bundle::live()->with('items.product.category', 'items.variant')->find($request->bundle_id);
        $qty    = (int) $request->input('qty', 1);
        $held   = (int) ($this->cart->items()['bundle|' . $request->bundle_id]['qty'] ?? 0);

        // Server decides: every component must be active and in stock for the set to sell.
        if (! $bundle || ! $bundle->isPurchasable() || $held + $qty > $bundle->availableQty()) {
            return back()->with('error', 'Sorry, that set is not available right now.');
        }

        $swapped = $this->cart->addBundle($bundle, $qty);

        $message = '"' . $bundle->name . '" added to your cart.';
        if ($swapped) {
            $message .= ' Your ' . implode(' and ', $swapped) . ' moved into the set, so you only pay once.';
        }

        return redirect()->route('cart')->with('success', $message);
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
            'qty'      => 'required|integer|min:0',
        ]);

        $qty       = (int) $request->qty;
        $cartItems = $this->cart->items();

        if ($qty > 0 && ! empty($cartItems[$request->cart_key]['bundle_id'])) {
            $bundle = \App\Models\Bundle::with('items.product.category', 'items.variant')->find($cartItems[$request->cart_key]['bundle_id']);
            $max    = $bundle ? $bundle->availableQty() : 0;
            if ($qty > $max) {
                return back()->with('error', $max > 0 ? 'Only ' . $max . ' of this set are in stock.' : 'This set is out of stock.');
            }
        }

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
