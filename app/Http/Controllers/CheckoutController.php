<?php

namespace App\Http\Controllers;

use App\Mail\AccountCreatedMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use App\Services\TikTokEventsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected TikTokEventsService $tiktok,
    ) {}

    /**
     * Fire CompletePayment for a paid order.
     *
     * The event_id is derived from the order id rather than randomly generated, so
     * a refreshed Stripe return page cannot register the purchase twice.
     */
    protected function trackCompletePayment(Order $order, Request $request): void
    {
        $properties = [
            'content_type' => 'product',
            'contents'     => $this->tiktok->contents($order->items),
            'currency'     => 'USD',
            'value'        => (float) $order->total,
        ];

        $eventId = 'CompletePayment.order-' . $order->id;

        $this->tiktok->track(
            'CompletePayment',
            $eventId,
            $properties,
            $this->tiktok->buildUser($request, $order->email, $order->phone),
            $request->fullUrl(),
        );

        // Redirect to thank-you follows — flash the twin to the next request.
        $this->tiktok->queueBrowserEvent('CompletePayment', $eventId, $properties);
    }

    public function index()
    {
        if ($this->cart->count() === 0) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $shippingOptions = [
            'free'  => ['label' => 'Free Shipping',  'cost' => 0],
            // 'fast'  => ['label' => 'Fast Shipping',   'cost' => 10],
            // 'local' => ['label' => 'Local Pickup',    'cost' => 15],
        ];

        return view('checkout', [
            'cartItems'       => $this->cart->items(),
            'cartTotal'       => $this->cart->total(),
            'shippingOptions' => $shippingOptions,
            'stripeKey'       => config('services.stripe.key'),
            'countries'       => config('checkout.countries', []),
            'defaultCountry'  => config('checkout.default_country', 'US'),
            'countryRules'    => $this->countryRulesForJs(),
            'orderBump'       => $this->orderBump(),
        ]);
    }

    /**
     * A single low-friction add-on offered at checkout.
     *
     * The cheapest in-stock item the buyer does not already have, so the ask is
     * small next to a basket they have already decided on. Deliberately one
     * product, not a rail: an order bump works because it is a yes/no decision at
     * the moment of purchase, and a grid of choices reintroduces the browsing the
     * customer just finished.
     *
     * Returns null on an empty catalogue or when everything is already in the
     * cart, and the section then does not render at all.
     */
    protected function orderBump(): ?Product
    {
        $cartIds = collect($this->cart->items())->pluck('id')->filter()->values();
        $eff     = 'COALESCE(NULLIF(sale_price, 0), price)';

        return Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->when($cartIds->isNotEmpty(), fn ($q) => $q->whereNotIn('id', $cartIds))
            ->orderByRaw("$eff ASC")
            ->first();
    }

    /**
     * The country table in a shape the checkout script can use.
     *
     * PCRE patterns cannot be handed to `new RegExp()` as-is — the delimiters and
     * trailing flags are PHP syntax and would be read as part of the pattern — so
     * each one is split into source + flags here. Config stays the single source
     * of truth: the client hint and the server rule are compiled from the same
     * string, and cannot drift into disagreeing about what a valid ZIP is.
     */
    protected function countryRulesForJs(): array
    {
        return collect(config('checkout.countries', []))
            ->map(function (array $country) {
                [$source, $flags] = $this->splitPattern($country['postal_regex'] ?? null);

                return [
                    'subdivision_label' => $country['subdivision_label'] ?? 'State',
                    'subdivisions'      => $country['subdivisions'] ?? [],
                    'postal_regex'      => $source,
                    // 'u' is valid in PHP but changes meaning in JS; only the
                    // case-insensitivity flag is meaningful to both.
                    'postal_flags'      => str_contains($flags, 'i') ? 'i' : '',
                    'postal_example'    => $country['postal_example'] ?? '',
                    'postal_required'   => (bool) ($country['postal_required'] ?? true),
                    'postal_label'      => $country['postal_label'] ?? 'ZIP / Postcode',
                    // Drives inputmode: a digits-only pattern gets the numeric keypad.
                    'postal_numeric'    => $source !== null && ! preg_match('/[a-z]/i', preg_replace('/\\\\[a-z]/i', '', $source)),
                ];
            })
            ->all();
    }

    /**
     * ZIP rules for a country: presence from `postal_required`, format from
     * `postal_regex`. A country with no pattern is length-checked only, rather
     * than having a US format silently imposed on it.
     */
    protected function zipRules(?array $country): array
    {
        $rules = ($country['postal_required'] ?? true)
            ? ['required', 'string', 'max:20']
            : ['nullable', 'string', 'max:20'];

        if (! empty($country['postal_regex'])) {
            $rules[] = 'regex:' . $country['postal_regex'];
        }

        return $rules;
    }

    /**
     * Split a delimited PCRE into [source, flags]. Returns [null, ''] when the
     * pattern is missing or malformed, which makes the caller skip ZIP format
     * checking for that country rather than reject every address in it.
     *
     * @return array{0: ?string, 1: string}
     */
    protected function splitPattern(?string $pattern): array
    {
        if (! is_string($pattern) || strlen($pattern) < 2) {
            return [null, ''];
        }

        $delimiter = $pattern[0];
        $end       = strrpos($pattern, $delimiter);

        if ($end === false || $end === 0) {
            return [null, ''];
        }

        return [substr($pattern, 1, $end - 1), substr($pattern, $end + 1)];
    }

    public function store(Request $request)
    {
        if ($this->cart->count() === 0) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $countries = config('checkout.countries', []);

        // Resolved before validation so the state and ZIP rules can be built for
        // the country actually submitted. An unknown code falls back to the
        // default, and the `in:` rule below still rejects the submission — this
        // only keeps rule construction from blowing up on garbage input.
        $countryCode = $request->input('country');
        $country     = $countries[$countryCode] ?? $countries[config('checkout.default_country', 'US')] ?? null;

        $subdivisions = array_keys($country['subdivisions'] ?? []);

        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:30',
            'city'           => 'required|string|max:100',
            'country'        => ['required', 'string', 'size:2', Rule::in(array_keys($countries))],
            // Required only where the country actually has subdivisions, so a
            // future country without them is not blocked by an unanswerable field.
            'state'          => $subdivisions
                ? ['required', 'string', Rule::in($subdivisions)]
                : ['nullable', 'string', 'max:64'],
            'zip'            => $this->zipRules($country),
            'address'        => 'required|string|max:255',
            'address2'       => 'nullable|string|max:255',
            'notes'          => 'nullable|string',
            'payment_method' => 'required|in:cod,stripe',
            'shipping'       => 'required|in:free,fast,local',
            'agree'          => 'accepted',
            'order_bump'     => 'nullable|integer|exists:products,id',
        ], [
            'state.in'       => 'Choose a valid ' . strtolower($country['subdivision_label'] ?? 'state') . ' for the selected country.',
            'state.required' => 'Please choose a ' . strtolower($country['subdivision_label'] ?? 'state') . '.',
            'country.in'     => 'We do not currently ship to that country.',
            'zip.regex'      => 'Enter a valid postcode for the selected country, e.g. ' . ($country['postal_example'] ?? ''),
        ]);

        // Store codes uppercase regardless of how they arrived, so 'nj' and 'NJ'
        // are one value in reporting and in the Meta payload.
        $data['country'] = strtoupper($data['country']);
        if (! empty($data['state'])) {
            $data['state'] = strtoupper($data['state']);
        }

        // Order bump: added to the cart before any total is computed, so it flows
        // through pricing, the stock check and the order lines exactly like a
        // normally-added product. Re-checked server-side rather than trusted from
        // the form — the posted id is user input, and the item may have sold out
        // while the checkout page sat open.
        if (! empty($data['order_bump'])) {
            $bump = Product::where('id', $data['order_bump'])
                ->where('is_active', true)
                ->first();

            if ($bump && $bump->effectiveStockFor(null, null) > $this->cart->getQty($bump->id, null, null)) {
                $this->cart->add($bump, 1, null, null);
            }
        }

        $shippingCosts = ['free' => 0, 'fast' => 10, 'local' => 15];
        $shippingCost  = $shippingCosts[$data['shipping']];
        $subtotal      = $this->cart->total();
        $total         = $subtotal + $shippingCost;

        // Resolve the buyer's account (guest checkout, flow B).
        //  - logged in         → use their account
        //  - email is known    → attach the order to that account, but never log an
        //                        anonymous visitor in or touch its password
        //  - brand-new email   → auto-create an account with a temporary password and
        //                        the must_reset flag; credentials are emailed below
        $newUser       = null;
        $plainPassword = null;
        $buyerId       = auth()->id();

        if (! $buyerId) {
            $existing = User::where('email', $data['email'])->first();
            if ($existing) {
                $buyerId = $existing->id;
            } else {
                $plainPassword = Str::password(12);
                $newUser = User::create([
                    'name'                => $data['name'],
                    'email'               => $data['email'],
                    'role'                => 'customer',
                    'password'            => Hash::make($plainPassword),
                    'must_reset_password' => true,
                ]);
                $buyerId = $newUser->id;
            }
        }

        // Create the order
        $order = Order::create([
            'user_id'         => $buyerId,
            'name'            => $data['name'],
            'email'           => $data['email'],
            'phone'           => $data['phone'],
            'city'            => $data['city'] ?? null,
            'state'           => $data['state'] ?? null,
            'zip'             => $data['zip'] ?? null,
            'country'         => $data['country'],
            'address'         => $data['address'],
            'address2'        => $data['address2'] ?? null,
            'notes'           => $data['notes'] ?? null,
            'payment_method'  => $data['payment_method'],
            'payment_status'  => 'pending',
            'shipping'        => $data['shipping'],
            'subtotal'        => $subtotal,
            'shipping_cost'   => $shippingCost,
            'total'           => $total,
            'status'          => 'pending',
            'tracking_number' => 'FRN-' . date('Y') . '-' . strtoupper(Str::random(8)),
        ]);

        // Save order items
        foreach ($this->cart->items() as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['id'],
                'name'       => $item['name'],
                'color'      => $item['color'] ?? null,
                'size'       => $item['size'] ?? null,
                'sku'        => $item['sku'] ?? null,
                'price'      => $item['price'],
                'qty'        => $item['qty'],
                'total'      => $item['price'] * $item['qty'],
            ]);
        }

        // A freshly auto-created account: sign the buyer in for this session and
        // email their temporary credentials. Done here (not per payment branch) so
        // both COD and the Stripe hand-off get the same treatment.
        if ($newUser) {
            Auth::login($newUser);
            try {
                Mail::to($newUser->email)->send(new AccountCreatedMail($newUser, $plainPassword));
            } catch (\Exception $e) {
                \Log::warning('Account-created email failed for ' . $newUser->email . ': ' . $e->getMessage());
                \App\Models\EmailLog::recordFailure($newUser->email, AccountCreatedMail::class, $e->getMessage(), $newUser->id);
            }
        }

        if ($data['payment_method'] === 'cod') {
            $this->cart->clear();
            $order->load('items');
            $this->trackCompletePayment($order, $request);
            try {
                Mail::to($order->email)->send(new OrderConfirmationMail($order));
            } catch (\Exception $e) {
                \Log::warning('Order confirmation email failed for order #' . $order->tracking_number . ': ' . $e->getMessage());
                \App\Models\EmailLog::recordFailure($order->email, OrderConfirmationMail::class, $e->getMessage(), $order->user_id, $order->id);
            }
            // Kept in the session (not flashed) so a page refresh still shows the order.
            // The purchase pixel stays on the flashed order_total, so it fires only once.
            session()->put('recent_order', $order->tracking_number);

            return redirect()->route('thank-you')
                ->with('order_total', $total);
        }

        // Stripe payment
        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount'   => (int) round($total * 100), // cents
            'currency' => 'usd',
            'metadata' => ['order_id' => $order->id],
        ]);

        // Stamped here rather than derived at read time: the API keys get swapped
        // from test to live later, and this has to stay a fact about the moment
        // the payment was taken.
        $order->update([
            'stripe_payment_intent' => $intent->id,
            'stripe_livemode'       => $this->stripeLivemode(),
        ]);

        return view('checkout-stripe', [
            'order'        => $order,
            'clientSecret' => $intent->client_secret,
            'stripeKey'    => config('services.stripe.key'),
        ]);
    }

    /**
     * The pay-by-link page for an order that was placed but never paid for.
     *
     * Reached from the payment-request email an admin sends by hand. The token
     * is the only credential — it is a long random string held on the order, so
     * knowing an order number or an email address is not enough to open it.
     */
    public function payLink(string $token)
    {
        $order = Order::with('items.product')->where('payment_token', $token)->firstOrFail();

        if ($order->payment_status === 'paid') {
            return redirect()->route('track-order', ['tracking' => $order->tracking_number])
                ->with('success', 'This order has already been paid — thank you! Nothing further is needed.');
        }

        if ($order->status === 'cancelled') {
            return redirect('/')->with('error', 'Order ' . $order->tracking_number . ' was cancelled, so it can no longer be paid. Please contact us if this is unexpected.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $intent = $this->paymentIntentFor($order);
        } catch (\Exception $e) {
            \Log::error('Pay link could not open a payment for order #' . $order->id . ': ' . $e->getMessage());

            return redirect('/')->with('error', 'We could not open the payment page just now. Please try again shortly, or reply to our email and we will help.');
        }

        // Already settled on Stripe's side but never recorded here — most likely
        // the customer closed the tab before the return redirect landed.
        if ($intent->status === 'succeeded') {
            $order->update(['payment_status' => 'paid', 'status' => 'processing']);

            return redirect()->route('track-order', ['tracking' => $order->tracking_number])
                ->with('success', 'This order has already been paid — thank you! Nothing further is needed.');
        }

        $order->update([
            'stripe_payment_intent' => $intent->id,
            'stripe_livemode'       => $this->stripeLivemode(),
        ]);

        return view('checkout-stripe', [
            'order'        => $order,
            'clientSecret' => $intent->client_secret,
            'stripeKey'    => config('services.stripe.key'),
            // No cart sits behind this page, so the "back to details" and "change
            // address" links would lead to an empty checkout.
            'payLink'      => true,
        ]);
    }

    /**
     * A usable PaymentIntent for an order, reusing the existing one where we can.
     *
     * Minting a fresh intent on every visit would litter the Stripe dashboard
     * with abandoned intents for the same order and make reconciliation harder.
     * The existing one is reused unless it is unusable — gone, cancelled, or
     * created against the other set of keys — and its amount is re-synced in
     * case the order total moved since.
     */
    protected function paymentIntentFor(Order $order): PaymentIntent
    {
        $amount = (int) round($order->total * 100);

        if ($order->stripe_payment_intent) {
            try {
                $intent = PaymentIntent::retrieve($order->stripe_payment_intent);

                if ($intent->status === 'succeeded') {
                    return $intent;
                }

                if ($intent->status !== 'canceled') {
                    if ($intent->amount !== $amount) {
                        $intent = PaymentIntent::update($intent->id, ['amount' => $amount]);
                    }

                    return $intent;
                }
            } catch (\Exception $e) {
                // Wrong key mode, deleted in a sandbox reset, or a malformed id.
                // A fresh intent below is the right recovery, not a hard failure.
                \Log::info('Reusing payment intent failed for order #' . $order->id . ': ' . $e->getMessage());
            }
        }

        return PaymentIntent::create([
            'amount'   => $amount,
            'currency' => 'usd',
            'metadata' => ['order_id' => $order->id],
        ]);
    }

    /**
     * Whether the configured Stripe keys are live or test, read from the key
     * prefix (`sk_live_` / `pk_test_` and friends).
     *
     * A test key can only ever create test payments and a live key only live
     * ones, so the prefix settles the question without trusting a field on the
     * API response — where a missing value would coerce to false and label a
     * real, paid order as a sandbox test.
     *
     * Returns null when no key is configured or the prefix is unrecognised, so
     * the admin shows "Mode unknown" rather than asserting something false. The
     * secret key is checked first because it is the key that actually takes the
     * money; if the two were ever mismatched, that is the one that decided.
     */
    protected function stripeLivemode(): ?bool
    {
        foreach ([config('services.stripe.secret'), config('services.stripe.key')] as $key) {
            $key = (string) $key;

            if (str_contains($key, '_live_')) {
                return true;
            }

            if (str_contains($key, '_test_')) {
                return false;
            }
        }

        return null;
    }

    public function stripeSuccess(Request $request)
    {
        $request->validate(['payment_intent' => 'required|string']);

        Stripe::setApiKey(config('services.stripe.secret'));
        $intent = PaymentIntent::retrieve($request->payment_intent);

        if ($intent->status === 'succeeded') {
            $order = Order::with('items')->where('stripe_payment_intent', $intent->id)->first();
            if ($order) {
                $alreadyPaid = $order->payment_status === 'paid';
                $order->update([
                    'payment_status'  => 'paid',
                    'status'          => 'processing',
                    // This route is only ever reached through Stripe, so the
                    // method is settled — including for an order taken as COD
                    // and later paid by card through the pay link.
                    'payment_method'  => 'stripe',
                    // Re-stamped so orders created before this column existed
                    // still get labelled once they succeed.
                    'stripe_livemode' => $this->stripeLivemode(),
                ]);

                if (! $alreadyPaid) {
                    $this->trackCompletePayment($order, $request);
                }
                try {
                    Mail::to($order->email)->send(new OrderConfirmationMail($order));
                } catch (\Exception $e) {
                    \Log::warning('Stripe order confirmation email failed for order #' . $order->tracking_number . ': ' . $e->getMessage());
                    \App\Models\EmailLog::recordFailure($order->email, OrderConfirmationMail::class, $e->getMessage(), $order->user_id, $order->id);
                }
            }
            $this->cart->clear();

            if ($order) {
                session()->put('recent_order', $order->tracking_number);
            }

            // The order row is the preferred source, but if the lookup missed we
            // still have an authoritative amount on the succeeded intent. Falling
            // back to 0 here used to hand the Meta pixel a zero-value Purchase,
            // which Meta rejects as a malformed conversion.
            return redirect()->route('thank-you')
                ->with('order_total', $order?->total ?? ($intent->amount / 100));
        }

        return redirect()->route('checkout')->with('error', 'Payment was not completed. Please try again.');
    }
}
