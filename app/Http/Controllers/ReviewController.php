<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();

        Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => auth()->id()],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }
}
