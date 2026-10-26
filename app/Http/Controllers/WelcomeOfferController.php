<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeOfferMail;
use App\Models\EmailLog;
use App\Models\WelcomeClaim;
use App\Models\WelcomeOffer;
use App\Models\WelcomeOfferTier;
use App\Services\CartService;
use App\Services\WelcomeOfferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class WelcomeOfferController extends Controller
{
    public function __construct(protected WelcomeOfferService $welcome, protected CartService $cart) {}

    /** Everything the front end needs to decide what (if anything) to render. */
    public function status(Request $request)
    {
        return response()->json($this->state($request))->header('Cache-Control', 'private, no-store');
    }

    public function claim(Request $request)
    {
        $data = $request->validate([
            'email'      => 'required|email:rfc|max:255',
            'sms_opt_in' => 'sometimes|boolean',
        ]);

        $visitorId = $request->cookie('pg_vid') ?: (string) Str::uuid();

        try {
            $claim = $this->welcome->claim($data['email'], $visitorId, $request->boolean('sms_opt_in'), $request->ip());
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        session(['welcome_claim_id' => $claim->id]);

        if ($claim->wasRecentlyCreated) {
            $meta = app(\App\Services\MetaEventsService::class);
            $meta->track('Lead', 'Lead.welcome-' . $claim->id, ['content_name' => 'welcome_offer'], $meta->buildUser($request, $claim->email), $request->headers->get('referer') ?: $request->fullUrl());

            try {
                Mail::to($claim->email)->send(new WelcomeOfferMail($claim->load('offer.tiers')));
            } catch (\Exception $e) {
                Log::warning('Welcome offer email failed for ' . $claim->email . ': ' . $e->getMessage());
                EmailLog::recordFailure($claim->email, WelcomeOfferMail::class, $e->getMessage());
            }
        }

        return response()
            ->json($this->state($request) + ['lead_event_id' => 'Lead.welcome-' . $claim->id, 'created' => $claim->wasRecentlyCreated])
            ->withCookie(Cookie::make('pg_vid', $visitorId, 60 * 24 * 365, null, null, null, true, false, 'Lax'));
    }

    protected function state(Request $request): array
    {
        $offer = WelcomeOffer::live();
        $user  = $request->user();

        if (! $offer || ! $this->welcome->isEligibleVisitor($user)) {
            return ['enabled' => false];
        }

        $claim = WelcomeClaim::where('welcome_offer_id', $offer->id)->find(session('welcome_claim_id'));
        // A claim belongs to the visitor who made it; a customer who has since paid no longer qualifies.
        if ($claim && $this->welcome->hasPaidOrder($claim->email)) {
            $claim = null;
        }

        $showShipping = (bool) config('checkout.shipping_fee_enabled');
        $tiers = $offer->tiers
            ->reject(fn (WelcomeOfferTier $t) => $t->type === WelcomeOfferTier::FREE_SHIPPING && ! $showShipping)
            ->map(fn (WelcomeOfferTier $t) => [
                'type' => $t->type, 'amount' => (float) $t->amount, 'min' => (float) $t->min_subtotal, 'label' => $t->label(),
            ])->values();

        $subtotal = $this->cart->total();
        $money    = $offer->tiers->reject(fn ($t) => $t->type === WelcomeOfferTier::FREE_SHIPPING);
        $current  = $money->map(fn ($t) => $t->discountFor($subtotal))->max() ?: 0.0;
        $next     = $money->filter(fn ($t) => (float) $t->min_subtotal > $subtotal && $t->discountFor((float) $t->min_subtotal) > $current)
            ->sortBy(fn ($t) => (float) $t->min_subtotal)->first();

        return [
            'enabled' => true,
            'copy'    => [
                'bar_text' => $offer->copy('bar_text'), 'bar_button' => $offer->copy('bar_button'),
                'modal_title' => $offer->copy('modal_title'), 'modal_button' => $offer->copy('modal_button'),
                'sms_text' => $offer->copy('sms_text'),
            ],
            'tiers'   => $tiers,
            'claim'   => $claim ? [
                'email'      => $claim->email,
                'code'       => $claim->code,
                'expires_at' => $claim->expires_at->toIso8601String(),
                'active'     => $claim->isUsable(),
            ] : null,
            'cart'    => [
                'subtotal' => round($subtotal, 2),
                'discount' => $claim && $claim->isUsable() ? (float) $current : 0.0,
                'next'     => $next ? ['need' => round((float) $next->min_subtotal - $subtotal, 2), 'discount' => $next->discountFor((float) $next->min_subtotal)] : null,
            ],
        ];
    }
}
