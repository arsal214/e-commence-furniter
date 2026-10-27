<?php

namespace App\Services;

use App\Mail\GiftCardMail;
use App\Models\GiftCard;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Stripe\StripeClient;

/**
 * Digital gift cards. Bought through Stripe Checkout, redeemed into a shopper's balance, and spent at checkout.
 * A card only exists as spendable money once Stripe says it was paid.
 */
class GiftCardService
{
    public const MIN = 10;
    public const MAX = 500;
    private const ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // no 0/O/1/I

    protected function stripe(): object
    {
        return new StripeClient(config('services.stripe.secret'));
    }

    public static function newCode(): string
    {
        do {
            $raw = '';
            for ($i = 0; $i < 16; $i++) {
                $raw .= self::ALPHABET[random_int(0, strlen(self::ALPHABET) - 1)];
            }
            $code = implode('-', str_split($raw, 4));
        } while (GiftCard::where('code', $code)->exists());

        return $code;
    }

    public static function normalize(string $input): string
    {
        $raw = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $input));

        return implode('-', str_split($raw, 4));
    }

    /** Creates the unpaid card and the Stripe page to pay for it. */
    public function startPurchase(User $buyer, float $amount, ?string $recipientEmail, ?string $message): string
    {
        $card = GiftCard::create(['code' => self::newCode(), 'amount' => $amount, 'balance' => 0, 'status' => 'pending', 'buyer_id' => $buyer->id,
            'recipient_email' => $recipientEmail ?: null, 'message' => $message ?: null]);

        $session = $this->stripe()->checkout->sessions->create([
            'mode' => 'payment', 'customer_email' => $buyer->email, 'client_reference_id' => (string) $card->id,
            'line_items' => [['quantity' => 1, 'price_data' => ['currency' => 'usd', 'unit_amount' => (int) round($amount * 100), 'product_data' => ['name' => 'Peyton Ghalib gift card']]]],
            'metadata' => ['gift_card_id' => (string) $card->id],
            'success_url' => route('giftcards.return') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url('/my-account/giftcards'),
        ]);
        $card->update(['stripe_session_id' => $session->id]);

        return $session->url;
    }

    /** Called when the shopper comes back from Stripe: checks with Stripe, never trusts the URL. */
    public function confirmSession(User $buyer, string $sessionId): ?GiftCard
    {
        $session = $this->stripe()->checkout->sessions->retrieve($sessionId);
        $card = GiftCard::where('stripe_session_id', $sessionId)->where('buyer_id', $buyer->id)->first();

        return $card && $this->activateIfPaid($card, $session) ? $card->fresh() : null;
    }

    /** Stripe's own notice that a Checkout session finished (covers shoppers who never came back). */
    public function handleWebhook(object $event): bool
    {
        if ($event->type !== 'checkout.session.completed' || empty($event->data->object->metadata->gift_card_id)) {
            return false;
        }
        $session = $event->data->object;
        if ($card = GiftCard::find($session->metadata->gift_card_id)) {
            $this->activateIfPaid($card, $session);
        }

        return true;
    }

    protected function activateIfPaid(GiftCard $card, object $session): bool
    {
        if (($session->payment_status ?? null) !== 'paid' || (string) $session->client_reference_id !== (string) $card->id
            || (int) $session->amount_total !== (int) round((float) $card->amount * 100)) {
            return false;
        }

        $activated = DB::transaction(function () use ($card) {
            $locked = GiftCard::whereKey($card->id)->lockForUpdate()->first();
            if ($locked->status === 'active') {
                return false; // someone else already did it
            }
            $locked->update(['status' => 'active', 'balance' => $locked->amount]);

            return true;
        });

        if ($activated) {
            $this->deliver($card->fresh());
        }

        return true;
    }

    /** Emails the code: to the recipient when one was given, otherwise to the buyer. */
    protected function deliver(GiftCard $card): void
    {
        $to = $card->recipient_email ?: $card->buyer?->email;
        if (! $to) {
            return;
        }
        try {
            Mail::to($to)->send(new GiftCardMail($card, $card->buyer?->name));
            $card->update(['delivered_at' => now()]);
        } catch (\Throwable $e) {
            Log::warning("Gift card {$card->id} email failed: " . $e->getMessage());
        }
    }

    /** Adds a card to a shopper's balance. The same message for a wrong, used-up or already claimed code. */
    public function redeem(User $user, string $code): bool
    {
        return DB::transaction(function () use ($user, $code) {
            $card = GiftCard::where('code', self::normalize($code))->where('status', 'active')->lockForUpdate()->first();
            if (! $card || (float) $card->balance <= 0 || ($card->owner_id && $card->owner_id !== $user->id)) {
                return false;
            }
            $card->update(['owner_id' => $user->id]);

            return true;
        });
    }

    public function balance(User $user): float
    {
        return round((float) GiftCard::where('owner_id', $user->id)->where('status', 'active')->sum('balance'), 2);
    }

    /** Balance not already promised to an order still waiting for payment. */
    public function available(User $user): float
    {
        $promised = (float) Order::where('user_id', $user->id)->where('payment_status', 'pending')->where('created_at', '>=', now()->subMinutes(30))->sum('gift_card_discount');

        return max(0.0, round($this->balance($user) - $promised, 2));
    }

    /** What the shopper's gift cards pay towards an order that would otherwise cost $due. */
    public function quote(?User $user, float $due): float
    {
        return $user && $due > 0 ? round(min($this->available($user), $due), 2) : 0.0;
    }

    /** Takes the money off the cards (oldest first) once the order is paid. Once per order. */
    public function spend(Order $order): void
    {
        if (! $order->user_id || (float) $order->gift_card_discount <= 0) {
            return;
        }
        DB::transaction(function () use ($order) {
            $left = (float) $order->gift_card_discount;
            foreach (GiftCard::where('owner_id', $order->user_id)->where('status', 'active')->where('balance', '>', 0)->orderBy('id')->lockForUpdate()->get() as $card) {
                $take = min($left, (float) $card->balance);
                $card->update(['balance' => round((float) $card->balance - $take, 2)]);
                $left = round($left - $take, 2);
                if ($left <= 0) {
                    break;
                }
            }
            if ($left > 0) {
                Log::warning("Order {$order->id}: gift card balance was {$left} short when the order was paid.");
            }
        });
    }
}
