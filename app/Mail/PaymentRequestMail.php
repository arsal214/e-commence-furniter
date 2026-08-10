<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Asks a customer to settle an unpaid order before it is fulfilled.
 *
 * Sent by hand from the admin order screen — an order can be placed without the
 * payment going through, and nothing else in the app chases it.
 */
class PaymentRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string|null  $adminNote  Free text typed by staff for this send.
     */
    public function __construct(
        public Order $order,
        public ?string $adminNote = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Required to Process Your Order – ' . $this->order->tracking_number . ' | PeytonGhalib',
        );
    }

    public function content(): Content
    {
        // Thumbnails read $item->product->image — eager load to avoid a query per line item.
        $this->order->loadMissing('items.product');

        $name = $this->order->name;

        return new Content(
            view: 'emails.order-mail',
            text: 'emails.order-mail-text',
            with: [
                'order'     => $this->order,
                'eyebrow'   => 'Payment Required',
                'heading'   => 'Your order is waiting on payment.',
                'intro'     => "Thanks for your order, {$name}. We've held everything ready for you, but the payment hasn't come through yet — so we can't start processing it. Settling the balance below is all that's needed and we'll get straight to work.",
                'adminNote' => $this->adminNote,
                'cta'       => [
                    'eyebrow' => 'Amount Due',
                    'amount'  => '$' . number_format($this->order->total, 2),
                    'label'   => 'Pay Securely Now',
                    'url'     => $this->order->pay_url,
                    'caption' => 'Card payment handled by Stripe. Your order reference is ' . $this->order->tracking_number . '.',
                ],
                // The tracking panel would sit directly under the pay panel and
                // compete with it; the reference is already in the caption.
                'showTracking' => false,
                'noteTitle'    => 'Already paid, or need another way to pay?',
                'noteBody'     => "Reply to this email and we'll check it against your order — please don't pay twice.",
            ],
        );
    }
}
