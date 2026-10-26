<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderSettlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;

/**
 * Stripe -> us. Settles orders even when the buyer closes the tab before the
 * return page loads. The signature is the only credential; with no signing secret
 * configured the endpoint refuses everything rather than trusting an unsigned body.
 */
class StripeWebhookController extends Controller
{
    public function __invoke(Request $request, OrderSettlement $settlement)
    {
        $secret = config('services.stripe.webhook_secret');
        if (! $secret) {
            return response('Webhook not configured.', 503);
        }

        try {
            $event = Webhook::constructEvent($request->getContent(), (string) $request->header('Stripe-Signature'), $secret);
        } catch (\Throwable $e) {
            return response('Invalid signature.', 400);
        }

        if ($event->type !== 'payment_intent.succeeded') {
            return response('Ignored.', 200);
        }

        $intent = $event->data->object;
        $order  = Order::where('stripe_payment_intent', $intent->id)->first();

        if (! $order) {
            Log::warning("Stripe webhook: no order for payment intent {$intent->id}.");

            return response('No matching order.', 200); // 200 so Stripe doesn't retry forever
        }

        $fingerprint = $this->fingerprint($intent->id);

        if ($settlement->markPaid($order, 'stripe', (int) $intent->amount, $fingerprint, (bool) $event->livemode)) {
            $settlement->sendConfirmation($order);
        }
        $settlement->reportPurchase($order);   // idempotent; also retries a send that failed earlier

        return response('OK', 200);
    }

    /** Card fingerprint of the charge behind the intent (wallets report one too); null if unavailable. */
    public static function fingerprint(string $intentId): ?string
    {
        if (! config('services.stripe.secret')) {
            return null;
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $intent = \Stripe\PaymentIntent::retrieve(['id' => $intentId, 'expand' => ['latest_charge']]);

            return $intent->latest_charge->payment_method_details->card->fingerprint ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
