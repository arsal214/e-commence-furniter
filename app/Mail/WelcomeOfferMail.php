<?php

namespace App\Mail;

use App\Models\WelcomeClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeOfferMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public WelcomeClaim $claim) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your PeytonGhalib welcome deal (expires ' . $this->claim->expires_at->format('M j, g:i A T') . ')');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.welcome-offer');
    }
}
