<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Slider;
use App\Services\DisputeService;
use App\Support\AdminViews;
use Illuminate\Http\Request;

/** The read-mostly marketplace pages: overview, claims, payouts, subscriptions, banners. */
class MarketAdminController extends Controller
{
    public function overview()
    {
        return view('admin.market', ['page' => 'overview', 'heading' => 'Overview'] + AdminViews::overview());
    }

    public function disputes(Request $request)
    {
        return view('admin.market', ['page' => 'disputes', 'heading' => 'Disputes & refunds'] + AdminViews::disputes($request));
    }

    public function decideDispute(Request $request, Dispute $dispute, DisputeService $service)
    {
        $data = $request->validate(['action' => 'required|in:refund,refund_manual,seller', 'note' => 'nullable|string|max:500']);

        $result = match ($data['action']) {
            'refund' => $service->refund($dispute),
            'refund_manual' => $service->refund($dispute, manual: true),
            'seller' => $service->sideWithSeller($dispute, $data['note'] ?? null),
        };

        return $result === true
            ? back()->with('success', $data['action'] === 'seller' ? 'Closed in the seller\'s favor. The payout is released.' : 'Buyer refunded. The seller will not be paid for these items.')
            : back()->with('error', $result);
    }

    public function payouts()
    {
        return view('admin.market', ['page' => 'payouts', 'heading' => 'Payouts'] + AdminViews::payouts());
    }

    public function subscriptions()
    {
        return view('admin.market', ['page' => 'subs', 'heading' => 'Subscriptions'] + AdminViews::subs());
    }

    public function marketing()
    {
        return view('admin.market', ['page' => 'marketing', 'heading' => 'Banners & coupons'] + AdminViews::marketing());
    }

    public function toggleBanner(Slider $slider)
    {
        $slider->update(['is_active' => ! $slider->is_active]);

        return back()->with('success', $slider->is_active ? 'Banner is live.' : 'Banner hidden.');
    }

    public function reports()
    {
        return view('admin.market', ['page' => 'reports', 'heading' => 'Reports']);
    }
}
