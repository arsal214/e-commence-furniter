<?php

namespace App\Listeners;

use App\Models\EmailLog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Mail\Events\MessageSent;
use Symfony\Component\Mime\Address;

/**
 * Writes an email_logs row for every message the application sends.
 *
 * Listening to the framework event rather than instrumenting each Mail::to()
 * call means password-reset mail, queued mailables and anything added later are
 * all captured without a call site having to remember.
 */
class LogSentEmail
{
    public function handle(MessageSent $event): void
    {
        try {
            $message = $event->message;

            $to = $message->getTo();
            if ($to === []) {
                return;
            }

            // A message can carry several To addresses; one row each, so a search
            // by recipient finds the send regardless of who else was on it.
            foreach ($to as $recipient) {
                EmailLog::create([
                    'to_email'   => $recipient->getAddress(),
                    'to_name'    => $recipient->getName() ?: null,
                    'cc'         => $this->addressList($message->getCc()),
                    'bcc'        => $this->addressList($message->getBcc()),
                    'subject'    => $message->getSubject(),
                    'mailable'   => $event->data['__laravel_mailable'] ?? null,
                    'user_id'    => $this->userId($event->data, $recipient->getAddress()),
                    'order_id'   => $this->orderId($event->data),
                    'status'     => EmailLog::STATUS_SENT,
                    'message_id' => $event->sent->getMessageId(),
                    'sent_at'    => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // The email was already delivered by this point. Failing to write the
            // log must not bubble up and turn a successful send into an error.
            \Log::warning('Email logging failed: ' . $e->getMessage());
        }
    }

    /**
     * @param  array<int, Address>  $addresses
     */
    protected function addressList(array $addresses): ?string
    {
        if ($addresses === []) {
            return null;
        }

        return implode(', ', array_map(fn (Address $a) => $a->getAddress(), $addresses));
    }

    /**
     * The user this email belongs to.
     *
     * Preferred source is the mailable's own view data, which is authoritative.
     * Otherwise the recipient address is matched against the users table, so a
     * customer's mail is still attributed even when the mailable was not built
     * around a User — but a guest checkout simply stays unattributed rather than
     * being pinned to a wrong account.
     */
    protected function userId(array $data, string $email): ?int
    {
        foreach ($data as $value) {
            if ($value instanceof User) {
                return $value->id;
            }

            if ($value instanceof Order && $value->user_id) {
                return $value->user_id;
            }
        }

        return User::where('email', $email)->value('id');
    }

    protected function orderId(array $data): ?int
    {
        foreach ($data as $value) {
            if ($value instanceof Order) {
                return $value->id;
            }
        }

        return null;
    }
}
