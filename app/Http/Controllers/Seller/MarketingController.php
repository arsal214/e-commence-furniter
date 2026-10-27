<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\AdClick;
use App\Services\AdService;
use App\Services\CouponService;
use App\Services\StoreSale;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Seller Center: coupons, a store-wide sale and promoted listings. */
class MarketingController extends Controller
{
    public function index(Request $request, AdService $ads)
    {
        $seller = $request->user()->seller;
        $monthClicks = AdClick::where('seller_id', $seller->id)->where('created_at', '>=', now()->startOfMonth());

        return view('seller.center.marketing', [
            'seller' => $seller, 'coupons' => $seller->coupons()->latest('id')->get(),
            'spentToday' => $ads->spentToday($seller), 'cpc' => AdService::cpc(), 'monthClicks' => (clone $monthClicks)->count(), 'monthCost' => (float) (clone $monthClicks)->sum('cost'),
            'min' => config('marketplace.ad_min_budget'), 'max' => config('marketplace.ad_max_budget'),
        ]);
    }

    public function createCoupon(Request $request)
    {
        $seller = $request->user()->seller;
        $request->merge(['code' => CouponService::clean($request->input('code'))]);
        $data = $request->validate([
            'code' => ['required', 'string', 'min:3', 'max:30', Rule::unique('seller_coupons', 'code')->where('seller_id', $seller->id)],
            'type' => ['required', Rule::in(['percent', 'amount'])],
            'value' => ['required', 'numeric', 'min:0.5', $request->input('type') === 'percent' ? 'max:90' : 'max:500'],
            'min_order' => ['nullable', 'numeric', 'min:0', 'max:10000'],
            'ends_at' => ['nullable', 'date', 'after:today'],
            'max_uses' => ['nullable', 'integer', 'min:1', 'max:100000'],
        ], ['code.unique' => 'You already have a coupon with that code.', 'value.max' => 'That discount is too large.']);

        if ($seller->coupons()->where('is_active', true)->count() >= 50) {
            return back()->with('error', 'You can have up to 50 active coupons. Switch one off first.')->withInput();
        }

        $seller->coupons()->create([
            'code' => $data['code'], 'type' => $data['type'], 'value' => $data['value'], 'min_order' => $data['min_order'] ?? 0,
            'ends_at' => isset($data['ends_at']) ? \Illuminate\Support\Carbon::parse($data['ends_at'])->endOfDay() : null, 'max_uses' => $data['max_uses'] ?? null,
        ]);

        return back()->with('status', "Coupon {$data['code']} is live.");
    }

    public function toggleCoupon(Request $request, int $coupon)
    {
        $coupon = $request->user()->seller->coupons()->findOrFail($coupon);
        $coupon->update(['is_active' => ! $coupon->is_active]);

        return back()->with('status', "Coupon {$coupon->code} is now " . ($coupon->is_active ? 'on.' : 'off.'));
    }

    public function sale(Request $request, StoreSale $sale)
    {
        $seller = $request->user()->seller;

        if ($request->input('action') === 'stop') {
            $sale->stop($seller);

            return back()->with('status', 'Your store-wide sale has ended and your own sale prices are back.');
        }

        $data = $request->validate(['percent' => ['required', 'integer', 'min:' . StoreSale::MIN_PERCENT, 'max:' . StoreSale::MAX_PERCENT]]);
        $sale->start($seller, (int) $data['percent']);

        return back()->with('status', "Your store-wide sale is on: {$data['percent']}% off everything.");
    }

    public function ads(Request $request)
    {
        $seller = $request->user()->seller;
        $data = $request->validate([
            'enabled' => ['nullable', 'boolean'],
            'budget' => ['required', 'numeric', 'min:' . config('marketplace.ad_min_budget'), 'max:' . config('marketplace.ad_max_budget')],
        ]);
        $on = $request->boolean('enabled');

        if ($on && ! $seller->stripe_customer_id) {
            return back()->with('error', 'Promoted listings are billed on your plan invoice, so your plan must be active first.');
        }
        $seller->update(['ads_active' => $on, 'ads_daily_budget' => round((float) $data['budget'], 2)]);

        return back()->with('status', $on ? 'Promoted listings are on.' : 'Promoted listings are off.');
    }
}
