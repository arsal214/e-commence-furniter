<?php

namespace App\Http\Controllers;

use App\Mail\AccountCreatedMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
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
        ]);
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

        $order->update(['stripe_payment_intent' => $intent->id]);

        return view('checkout-stripe', [
            'order'        => $order,
            'clientSecret' => $intent->client_secret,
            'stripeKey'    => config('services.stripe.key'),
        ]);
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
                $order->update(['payment_status' => 'paid', 'status' => 'processing']);

                if (! $alreadyPaid) {
                    $this->trackCompletePayment($order, $request);
                }
                try {
                    Mail::to($order->email)->send(new OrderConfirmationMail($order));
                } catch (\Exception $e) {
                    \Log::warning('Stripe order confirmation email failed for order #' . $order->tracking_number . ': ' . $e->getMessage());
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
