<?php

namespace App\Services;

use App\Mail\NewMessageMail;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

/** Messages between a shopper and a store. One conversation per shopper and store. */
class MessageService
{
    public const MAX_LENGTH = 2000;
    public const PER_HOUR = 30;

    /** The shopper's conversation with a store, started if there is none. Only live stores can be written to first. */
    public function open(User $buyer, Seller $seller): Conversation
    {
        return Conversation::firstOrCreate(['buyer_id' => $buyer->id, 'seller_id' => $seller->id]);
    }

    /** @return true|string true when sent, otherwise what to tell the person */
    public function send(Conversation $conversation, string $from, User $author, string $body): true|string
    {
        $body = trim(strip_tags($body));
        if ($body === '' || mb_strlen($body) > self::MAX_LENGTH) {
            return 'Write a message of up to ' . self::MAX_LENGTH . ' characters.';
        }
        $key = 'message-send:' . $author->id;
        if (RateLimiter::tooManyAttempts($key, self::PER_HOUR)) {
            return 'You are sending messages very quickly. Please try again in a little while.';
        }
        RateLimiter::hit($key, 3600);

        $message = $conversation->messages()->create(['from' => $from, 'body' => $body]);
        $conversation->update(['last_message_at' => now()]);
        $this->notify($conversation, $message);

        return true;
    }

    /** Marks what the other side wrote as read by this side. */
    public function markRead(Conversation $conversation, string $side): void
    {
        $conversation->unreadFor($side)->update(['read_at' => now()]);
    }

    public function unreadForBuyer(User $user): int
    {
        return Message::whereHas('conversation', fn ($q) => $q->where('buyer_id', $user->id))->where('from', 'seller')->whereNull('read_at')->count();
    }

    public function unreadForSeller(Seller $seller): int
    {
        return Message::whereHas('conversation', fn ($q) => $q->where('seller_id', $seller->id))->where('from', 'buyer')->whereNull('read_at')->count();
    }

    /** One email per conversation every ten minutes, however many messages arrive in between. */
    protected function notify(Conversation $conversation, Message $message): void
    {
        if (! Cache::add("message-mail:{$conversation->id}:{$message->from}", 1, now()->addMinutes(10))) {
            return;
        }
        $conversation->loadMissing('buyer', 'seller.user');
        $to = $message->from === 'buyer' ? $conversation->seller?->user?->email : $conversation->buyer?->email;
        if (! $to) {
            return;
        }
        try {
            Mail::to($to)->send(new NewMessageMail($conversation, $message));
        } catch (\Throwable $e) {
            Log::warning('New-message email failed: ' . $e->getMessage());
        }
    }
}
