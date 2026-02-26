<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Toggle a product in/out of the authenticated user's wishlist.
     * Returns JSON: { added: bool, count: int, wishlist_ids: int[] }
     */
    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $userId    = Auth::id();
        $productId = $request->product_id;

        $existing = Wishlist::where('user_id', $userId)
                            ->where('product_id', $productId)
                            ->first();

        if ($existing) {
            $existing->delete();
            $added = false;
        } else {
            Wishlist::create(['user_id' => $userId, 'product_id' => $productId]);
            $added = true;
        }

        $count = Wishlist::where('user_id', $userId)->count();
        $ids   = Wishlist::where('user_id', $userId)->pluck('product_id');

        return response()->json([
            'added'       => $added,
            'count'       => $count,
            'wishlist_ids' => $ids,
        ]);
    }

    /**
     * Remove a product from the wishlist (used on the wishlist page form submit).
     */
    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        Wishlist::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->delete();

        return back()->with('success', 'Product removed from wishlist.');
    }
}
