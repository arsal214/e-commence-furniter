<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;

class LandingController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $this->rememberReferral($request);

        return view('seller.landing', [
            'price' => Seller::SUBSCRIPTION_PRICE,
            'steps' => [
                ['Create your account', 'Name, business email, phone and password.'],
                ['Verify your email', 'Enter the 6-digit code we send you.'],
                ['Choose the plan', '$19.99/month, 0% commission. Cancel any time.'],
                ['Add business details', 'Legal name, EIN and registration certificate.'],
                ['Verify the owner', 'Name, contact details and a government photo ID.'],
                ['Add your US bank', 'Get paid daily for delivered orders.'],
            ],
            'features' => [
                ['Unlimited listings & variants', 'Sizes, colors, materials, each with its own price, stock and SKU.'],
                ['Bulk upload by CSV', 'Import hundreds of products at once from a spreadsheet.'],
                ['Daily payouts', 'Straight to your US bank for delivered orders.'],
                ['Your shipping, your way', 'Set your own rates and handling time.'],
                ['Coupons & sales', 'Create discount codes and store-wide sales.'],
                ['Sales analytics', 'Revenue, orders, views and best sellers at a glance.'],
                ['Your branded storefront', 'Banner, logo, story and policies, plus vacation mode.'],
                ['Tax handled', 'We collect sales tax as marketplace facilitator.'],
            ],
            'levels' => [
                ['New Seller', '$0', ['Payouts 3 days after delivery', 'Standard search placement']],
                ['Rising Seller', '$1,000', ['Payouts 2 days after delivery', 'Higher search placement', 'Rising badge on your store']],
                ['Top Seller', '$10,000', ['Payouts 1 day after delivery', 'Even higher search placement', 'Priority support']],
                ['Elite Seller', '$50,000', ['Same-day payouts after delivery', 'Top search placement', 'Fastest support']],
            ],
            'needs' => [
                ['US-registered business', 'LLC, corporation, partnership or sole proprietorship'],
                ['EIN', 'Your federal Employer Identification Number'],
                ['Registration certificate', 'Articles of organization or state business license'],
                ['Owner details', 'Name, email, phone, date of birth and photo ID'],
                ['US bank account', "In your business's name, for payouts"],
                ['Verified email', "We'll send a 6-digit code"],
            ],
            'faq' => [
                ['Is there really no commission?', 'Yes. You pay $19.99 a month and we take 0% of your sales. Card processing (2.9% + $0.30 per order) is charged by our payment partner Stripe and comes out of each order before your payout.'],
                ['How long does approval take?', 'Most applications are reviewed by our team within 1–2 business days. New products are also reviewed before they go live.'],
                ['When do I get paid?', 'Daily, for orders that have been delivered. Higher seller levels get paid sooner after delivery.'],
                ['What if my monthly payment fails?', 'Your store is hidden from shoppers right away and comes back automatically as soon as the payment succeeds.'],
                ['Can I sell outside the US?', 'Not yet. Peyton Ghalib is US-only for both sellers and shoppers.'],
                ['Who handles returns?', 'You set your return window (14, 30 or 60 days) and who pays return shipping. Damaged or wrong items must always be accepted at your cost.'],
            ],
        ]);
    }

    public function pricing(\Illuminate\Http\Request $request)
    {
        $this->rememberReferral($request);

        return view('seller.pricing', [
            'price' => Seller::SUBSCRIPTION_PRICE,
            'includes' => [
                '0% commission on every sale', 'Unlimited products and variants', 'Daily payouts to your US bank',
                'Coupons and store-wide sales', 'Sales analytics and inventory alerts', 'Seller levels and reward points',
                'Seller support and tutorials',
            ],
        ]);
    }

    /** An invite link (?ref=CODE) is remembered for 30 days so the sign-up that follows can credit the inviter. */
    protected function rememberReferral(\Illuminate\Http\Request $request): void
    {
        $code = strtoupper((string) $request->query('ref'));
        if (app(\App\Services\ReferralService::class)->referrerFor($code)) {
            \Illuminate\Support\Facades\Cookie::queue(\App\Services\ReferralService::COOKIE, $code, 60 * 24 * 30);
        }
    }
}
