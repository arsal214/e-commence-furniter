<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ReferralService;
use App\Support\SellerInsights;
use Illuminate\Http\Request;

/** Seller Center: how the store is doing (performance) and the invite-a-seller program. */
class InsightsController extends Controller
{
    public function performance(Request $request)
    {
        $filter = in_array($request->query('filter'), ['reply', 'low'], true) ? $request->query('filter') : '';

        return view('seller.center.performance', ['seller' => $request->user()->seller] + SellerInsights::performance($request->user()->seller, $filter));
    }

    /** A public reply under a review of one of this seller's own products. */
    public function reply(Request $request, int $review)
    {
        $seller = $request->user()->seller;
        $data = $request->validate(['reply' => ['required', 'string', 'min:2', 'max:1000']]);

        $review = Review::whereIn('product_id', $seller->products()->select('id'))->findOrFail($review);
        $review->update(['seller_reply' => trim($data['reply']), 'seller_replied_at' => now()]);

        return back()->with('status', 'Your reply is now public on the product page.');
    }

    public function help(Request $request)
    {
        return view('seller.center.help', ['seller' => $request->user()->seller]);
    }

    public function referrals(Request $request, ReferralService $referrals)
    {
        $seller = $request->user()->seller;

        return view('seller.center.referrals', ['seller' => $seller] + SellerInsights::referrals($seller, $referrals));
    }
}
