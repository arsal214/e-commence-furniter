<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payout;
use Stripe\StripeClient;

/** The only place that moves money out through Stripe: seller payouts and buyer refunds. */
class StripeTransfers
{
    protected function stripe(): StripeClient
    {
        return new StripeClient(config('services.stripe.secret'));
    }

    /** Sends a payout to the seller's connected account. The idempotency key makes a retry safe. */
    public function transfer(Payout $payout, string $account): string
    {
        $t = $this->stripe()->transfers->create([
            'amount' => (int) round((float) $payout->amount * 100), 'currency' => 'usd', 'destination' => $account,
            'description' => "Peyton Ghalib payout #{$payout->id}", 'metadata' => ['payout_id' => $payout->id, 'seller_id' => $payout->seller_id],
        ], ['idempotency_key' => "payout-{$payout->id}"]);

        return $t->id;
    }

    /** Refunds part of a card payment. Throws when the order was not paid by card through Stripe. */
    public function refund(Order $order, float $amount, string $key): string
    {
        if ($order->payment_method !== 'stripe' || ! $order->stripe_payment_intent) {
            throw new \RuntimeException('Only Stripe payments can be refunded here. Refund PayPal orders in PayPal.');
        }

        return $this->stripe()->refunds->create([
            'payment_intent' => $order->stripe_payment_intent, 'amount' => (int) round($amount * 100), 'metadata' => ['order_id' => $order->id],
        ], ['idempotency_key' => $key])->id;
    }
}
