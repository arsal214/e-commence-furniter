<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $item = Product::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $newProducts = Product::where('is_active', true)
            ->where('id', '!=', $item->id)
            ->latest()
            ->take(4)
            ->get();

        return view('product-details', compact('item', 'newProducts'));
    }
}
