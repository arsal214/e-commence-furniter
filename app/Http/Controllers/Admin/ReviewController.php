<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->input('search');
        $productId = $request->input('product_id') ?: null;

        $reviews = Review::query()
            ->with(['product:id,name,slug', 'user:id,name'])
            ->when($productId, fn($q) => $q->where('product_id', $productId))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('reviewer_name', 'like', "%{$search}%")
                       ->orWhere('comment', 'like', "%{$search}%")
                       ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                       ->orWhereHas('product', fn($p) => $p->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Products dropdown for the filter (and quick reference of review counts).
        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('admin.reviews.index', compact('reviews', 'products', 'search', 'productId'));
    }

    public function create(Request $request)
    {
        $products = Product::orderBy('name')->get(['id', 'name']);
        $selectedProduct = $request->input('product_id');

        return view('admin.reviews.create', compact('products', 'selectedProduct'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, requireName: true);

        Review::create([
            'product_id'    => $data['product_id'],
            'user_id'       => null, // admin-authored review, not tied to an account
            'reviewer_name' => $data['reviewer_name'],
            'rating'        => $data['rating'],
            'comment'       => $data['comment'] ?? null,
        ]);

        return redirect()->route('admin.reviews.index')
                         ->with('success', 'Review added successfully.');
    }

    public function edit(Review $review)
    {
        $products = Product::orderBy('name')->get(['id', 'name']);
        $review->load('user:id,name');

        return view('admin.reviews.edit', compact('review', 'products'));
    }

    public function update(Request $request, Review $review)
    {
        // A customer review's name is locked to their account, so the form omits the
        // reviewer_name field there — only require it for admin-authored reviews.
        $data = $this->validated($request, requireName: $review->user_id === null);

        // Reviews written by a real customer keep their account link; only the
        // rating/comment (and product) are editable there. Admin reviews may set a name.
        $update = [
            'product_id' => $data['product_id'],
            'rating'     => $data['rating'],
            'comment'    => $data['comment'] ?? null,
        ];

        if ($review->user_id === null) {
            $update['reviewer_name'] = $data['reviewer_name'];
        }

        $review->update($update);

        return redirect()->route('admin.reviews.index')
                         ->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
                         ->with('success', 'Review deleted successfully.');
    }

    private function validated(Request $request, bool $requireName): array
    {
        return $request->validate([
            'product_id'    => ['required', 'exists:products,id'],
            'reviewer_name' => [$requireName ? 'required' : 'nullable', 'string', 'max:100'],
            'rating'        => ['required', 'integer', 'min:1', 'max:5'],
            'comment'       => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
