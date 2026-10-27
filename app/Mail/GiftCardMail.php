<?php

namespace App\Mail;

use App\Models\GiftCard;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GiftCardMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public GiftCard $card, public ?string $from_name = null) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->card->recipient_email ? 'You have received a Peyton Ghalib gift card' : 'Your Peyton Ghalib gift card');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.gift-card');
    }
}
