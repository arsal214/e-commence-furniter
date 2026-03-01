<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $item = Product::with(['category', 'productImages'])
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

        return view('product-details', compact('item', 'newProducts'));
    }
}
