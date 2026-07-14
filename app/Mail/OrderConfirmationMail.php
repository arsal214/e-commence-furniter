<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmed – ' . $this->order->tracking_number . ' | PeytonGhalib',
        );
    }

    public function content(): Content
    {
        // Thumbnails read $item->product->image — eager load to avoid a query per line item.
        $this->order->loadMissing('items.product');

        $count = $this->order->items->count();

        return new Content(
            view: 'emails.order-mail',
            text: 'emails.order-mail-text',
            with: [
                'order'   => $this->order,
                'eyebrow' => 'Order Confirmed',
                'heading' => 'Thank you, ' . $this->order->name . '.',
                'intro'   => "We've received your order of {$count} " . str('item')->plural($count)
                    . ' and our team is preparing it for dispatch. Everything you need is below — keep this email for your records.',
                'noteTitle'    => 'Need to change something?',
                'noteBody'     => "Just reply to this email within 24 hours and we'll sort it out before your order ships.",
                'showTracking' => true,
            ],
        );
    }
}
