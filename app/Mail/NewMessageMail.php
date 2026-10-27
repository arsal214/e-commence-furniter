<?php

namespace App\Mail;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Conversation $conversation, public Message $message) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->message->from === 'buyer' ? 'New message from a shopper' : 'New message from ' . $this->conversation->seller->store_name);
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.new-message');
    }
}
