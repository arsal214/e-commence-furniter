<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\TikTokEventsService;
use App\Support\DeliveryEstimate;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected TikTokEventsService $tiktok) {}

    public function suggestions(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $results = Product::visible()
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
            })
            ->with('category:id,name')
            ->select('id', 'name', 'slug', 'price', 'sale_price', 'category_id', 'image')
            ->latest()
            ->limit(7)
            ->get()
            ->map(function ($p) {
                return [
                    'name'       => $p->name,
                    'slug'       => $p->slug,
                    'price'      => number_format($p->price, 2),
                    'sale_price' => $p->sale_price ? number_format($p->sale_price, 2) : null,
                    'image'      => $p->image
                        ? asset('storage/' . $p->image)
                        : asset('assets/imgs/theme/no-image.png'),
                    'category'   => optional($p->category)->name,
                    'url'        => route('product-details', $p->slug),
                ];
            });

        return response()->json($results);
    }

    public function show(string $slug, Request $request)
    {
        $item = Product::with(['category.parent', 'seller', 'productImages', 'variants', 'reviews.user'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('slug', $slug)
            ->visible()
            ->firstOrFail();

        // Related products: same category AND closest in price first, so a premium
        // item isn't paired with bargain-bin products (a $350 machine next to $9
        // brushes reads as a broken recommender). Effective price mirrors what the
        // cart charges — sale price when set, otherwise the list price.
        $eff       = 'COALESCE(NULLIF(sale_price, 0), price)';
        $itemPrice = (float) $item->effective_price;

        $newProducts = Product::visible()
            ->where('id', '!=', $item->id)
            ->where('category_id', $item->category_id)
            ->orderByRaw("ABS($eff - ?) ASC", [$itemPrice])
            ->take(4)
            ->get();

        // No filler from other categories: a related-products rail that pairs a coat with a
        // camera is worse than a shorter rail. The view hides the section when it is empty.

        $this->trackViewContent($item, $request);

        // Meta ViewContent: a fresh id per view, shared with the browser event (a fixed id per
        // product would let Meta drop every view after the first).
        $metaViewContentId = app(\App\Services\MetaEventsService::class)->newEventId('ViewContent');
        app(\App\Services\MetaEventsService::class)->track('ViewContent', $metaViewContentId, array_filter([
            'content_type' => 'product', 'content_ids' => [(string) $item->id], 'content_name' => $item->name,
            'content_category' => $item->category?->name, 'value' => round((float) $item->effective_price, 2),
        ]), app(\App\Services\MetaEventsService::class)->buildUser($request), $request->fullUrl());

        $completeSet = \App\Models\Bundle::offeredFor([$item->id], 1)->first();

        $delivery      = DeliveryEstimate::window();
        $boughtTogether = $this->bundleFor($item);

        // Delivery dates are per-day; a CDN/shared cache must not replay yesterday's page.
        return response()
            ->view('market.product', compact('item', 'newProducts', 'delivery', 'boughtTogether', 'completeSet', 'metaViewContentId')
                + \App\Support\PageViews::product($item, $delivery, $newProducts, $boughtTogether, $completeSet))
            ->header('Cache-Control', 'private, no-cache, must-revalidate');
    }

    /**
     * Companions for the "Frequently bought together" bundle.
     *
     * Real co-purchase history first (Product::frequentlyBoughtWith). When the
     * store has not sold enough for that to return anything — which is the normal
     * state of a young catalogue — it is topped up with in-stock items from the
     * same category, priced nearest the one being viewed so the bundle total stays
     * plausible rather than pairing a $9 accessory with a $900 centrepiece.
     *
     * Only in-stock, active products are ever offered: the bundle's add button
     * posts straight to the cart, and CartController now rejects out-of-stock
     * lines, so suggesting one would produce an error instead of an add.
     */
    protected function bundleFor(Product $item, int $limit = 2)
    {
        $picks = $item->frequentlyBoughtWith($limit);

        if ($picks->count() < $limit) {
            $eff     = 'COALESCE(NULLIF(sale_price, 0), price)';
            $exclude = $picks->pluck('id')->push($item->id);

            $picks = $picks->merge(
                Product::visible()
                    ->where('stock', '>', 0)
                    ->whereRaw("$eff > 0")
                    ->whereNotIn('id', $exclude)
                    ->when($item->category_id, fn ($q) => $q->where('category_id', $item->category_id))
                    ->orderByRaw("ABS($eff - ?) ASC", [(float) $item->effective_price])
                    ->take($limit - $picks->count())
                    ->get()
            );
        }

        return $picks->values();
    }

    protected function trackViewContent(Product $item, Request $request): void
    {
        $eventId    = $this->tiktok->newEventId('ViewContent');
        $properties = [
            'content_type' => 'product',
            'contents'     => [['content_name' => $item->name]],
            'currency'     => 'USD',
            'value'        => $item->effective_price,
        ];

        $this->tiktok->track(
            'ViewContent',
            $eventId,
            $properties,
            $this->tiktok->buildUser($request),
            $request->fullUrl(),
        );

        // Rendered in this same response, so the browser twin carries the same id.
        $this->tiktok->queueBrowserEvent('ViewContent', $eventId, $properties, immediate: true);
    }
}
