<?php

namespace App\Services;

use App\Models\Seller;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Customer;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

/**
 * Everything the seller sign-up needs from Stripe. Card details and bank/ID details are
 * entered on Stripe's own pages (Checkout and Connect Express), so our servers never
 * see a card number or a bank account number.
 */
class SellerBilling
{
    protected function stripe(): StripeClient
    {
        return new StripeClient(config('services.stripe.secret'));
    }

    /** Stripe Checkout URL for the $19.99/month seller plan. */
    public function subscribeUrl(Seller $seller): string
    {
        if (! config('services.stripe.seller_price_id')) {
            throw new \RuntimeException('STRIPE_SELLER_PRICE_ID is not set.');
        }

        $stripe = $this->stripe();
        if (! $seller->stripe_customer_id) {
            $customer = $stripe->customers->create(['email' => $seller->user->email, 'name' => $seller->user->name, 'metadata' => ['seller_id' => $seller->id]]);
            $seller->update(['stripe_customer_id' => $customer->id]);
        }

        $session = $stripe->checkout->sessions->create([
            'mode' => 'subscription',
            'customer' => $seller->stripe_customer_id,
            'client_reference_id' => (string) $seller->id,
            'line_items' => [['price' => config('services.stripe.seller_price_id'), 'quantity' => 1]],
            'success_url' => route('seller.plan.return') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('seller.plan'),
            'subscription_data' => ['metadata' => ['seller_id' => $seller->id]],
        ]);

        return $session->url;
    }

    /** True when the Checkout session belongs to this seller and the subscription is paid. */
    public function confirmSubscription(Seller $seller, string $sessionId): bool
    {
        $session = $this->stripe()->checkout->sessions->retrieve($sessionId);

        if ((string) $session->client_reference_id !== (string) $seller->id || $session->payment_status !== 'paid') {
            return false;
        }

        $seller->update([
            'subscription_status' => 'active',
            'stripe_customer_id' => $session->customer,
            'stripe_subscription_id' => $session->subscription,
        ]);

        return true;
    }

    /** Stripe-hosted page where the seller adds their US bank and verifies identity. */
    public function bankUrl(Seller $seller): string
    {
        $stripe = $this->stripe();

        if (! $seller->stripe_account_id) {
            $account = $stripe->accounts->create([
                'type' => 'express',
                'country' => 'US',
                'email' => $seller->user->email,
                'capabilities' => ['transfers' => ['requested' => true]],
                'metadata' => ['seller_id' => $seller->id],
            ]);
            $seller->update(['stripe_account_id' => $account->id]);
        }

        return $stripe->accountLinks->create([
            'account' => $seller->stripe_account_id,
            'type' => 'account_onboarding',
            'refresh_url' => route('seller.bank'),
            'return_url' => route('seller.bank.return'),
        ])->url;
    }

    /** Takes dollars off the next invoice (a negative customer balance Stripe applies automatically). */
    public function credit(Seller $seller, int $dollars): void
    {
        $this->stripe()->customers->createBalanceTransaction($seller->stripe_customer_id, [
            'amount' => -$dollars * 100, 'currency' => 'usd', 'description' => 'Reward points redeemed',
        ]);
    }

    /** Takes an exact amount (in dollars) off the next invoice. */
    public function creditAmount(Seller $seller, float $dollars, string $description): void
    {
        $this->stripe()->customers->createBalanceTransaction($seller->stripe_customer_id, [
            'amount' => -(int) round($dollars * 100), 'currency' => 'usd', 'description' => $description,
        ]);
    }

    /** Adds a one-off charge (such as promoted-listing clicks) to the seller's next plan invoice. */
    public function addInvoiceItem(Seller $seller, float $dollars, string $description): void
    {
        $this->stripe()->invoiceItems->create(['customer' => $seller->stripe_customer_id, 'amount' => (int) round($dollars * 100), 'currency' => 'usd', 'description' => $description]);
    }

    /** Stripe's page for changing the card or cancelling the plan. Needs the Customer Portal switched on in Stripe. */
    public function portalUrl(Seller $seller): string
    {
        return $this->stripe()->billingPortal->sessions->create(['customer' => $seller->stripe_customer_id, 'return_url' => route('seller.billing')])->url;
    }

    /** Stripe's Express dashboard, where the seller manages the bank account. */
    public function bankLoginUrl(Seller $seller): string
    {
        return $this->stripe()->accounts->createLoginLink($seller->stripe_account_id)->url;
    }

    /** @return list<array{d:string,v:string,s:string}> the plan's recent invoices, empty when Stripe is unreachable */
    public function invoices(Seller $seller): array
    {
        if (! $seller->stripe_customer_id) {
            return [];
        }

        try {
            return collect($this->stripe()->invoices->all(['customer' => $seller->stripe_customer_id, 'limit' => 12])->data)->map(fn ($i) => [
                'd' => date('M j, Y', $i->created), 'v' => '$' . number_format($i->amount_paid / 100, 2), 's' => ucfirst((string) $i->status),
            ])->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function bankReady(Seller $seller): bool
    {
        if (! $seller->stripe_account_id) {
            return false;
        }

        return (bool) $this->stripe()->accounts->retrieve($seller->stripe_account_id)->details_submitted;
    }
}
