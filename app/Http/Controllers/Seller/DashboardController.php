<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Seller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->user()->seller;

        return view('seller.center.page', ['seller' => $seller, 'page' => 'dashboard'] + \App\Support\SellerViews::dashboard($seller));
    }

    /** After "more information needed": back into the review queue. */
    public function resubmit(Request $request)
    {
        $seller = $request->user()->seller;
        if ($seller->status === 'info_requested') {
            $seller->update(['status' => 'in_review', 'submitted_at' => now()]);
        }

        return redirect()->route('seller.dashboard')->with('status', 'Thanks! Your application is back in review.');
    }
}
