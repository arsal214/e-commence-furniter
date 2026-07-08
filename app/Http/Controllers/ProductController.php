<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\TikTokEventsService;
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

        $results = Product::where('is_active', true)
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
        $item = Product::with(['category', 'productImages', 'reviews.user'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Related: same category first, then fill with latest
        $newProducts = Product::where('is_active', true)
            ->where('id', '!=', $item->id)
            ->where('category_id', $item->category_id)
            ->latest()
            ->take(4)
            ->get();

        if ($newProducts->count() < 4) {
            $ids = $newProducts->pluck('id')->push($item->id);
            $newProducts = $newProducts->merge(
                Product::where('is_active', true)
                    ->whereNotIn('id', $ids)
                    ->latest()
                    ->take(4 - $newProducts->count())
                    ->get()
            );
        }

        $this->trackViewContent($item, $request);

        return view('product-details', compact('item', 'newProducts'));
    }

    protected function trackViewContent(Product $item, Request $request): void
    {
        $eventId    = $this->tiktok->newEventId('ViewContent');
        $properties = [
            'content_type' => 'product',
            'contents'     => [['content_name' => $item->name]],
            'currency'     => 'USD',
            'value'        => (float) ($item->sale_price ?: $item->price),
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
