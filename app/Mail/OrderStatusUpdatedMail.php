<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Statuses a customer should hear about. 'pending' is the state an order is
     * born in, so moving back to it is an internal correction, not news.
     */
    public const NOTIFIABLE = ['processing', 'shipped', 'delivered', 'cancelled'];

    public function __construct(public Order $order) {}

    public static function shouldNotify(string $status): bool
    {
        return in_array($status, self::NOTIFIABLE, true);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->copy()['subject'] . ' – ' . $this->order->tracking_number . ' | PeytonGhalib',
        );
    }

    public function content(): Content
    {
        // Thumbnails read $item->product->image — eager load to avoid a query per line item.
        $this->order->loadMissing('items.product');

        $copy = $this->copy();

        return new Content(
            view: 'emails.order-mail',
            text: 'emails.order-mail-text',
            with: [
                'order'        => $this->order,
                'eyebrow'      => $copy['eyebrow'],
                'heading'      => $copy['heading'],
                'intro'        => $copy['intro'],
                'noteTitle'    => $copy['noteTitle'],
                'noteBody'     => $copy['noteBody'],
                'showTracking' => $copy['showTracking'],
            ],
        );
    }

    private function copy(): array
    {
        $name = $this->order->name;

        return match ($this->order->status) {
            'processing' => [
                'subject'      => 'Your Order Is Being Prepared',
                'eyebrow'      => 'Order Update',
                'heading'      => "We're preparing your order.",
                'intro'        => "Good news, {$name} — your order has moved into production and our team is picking and packing your items. We'll email you the moment it leaves our warehouse.",
                'noteTitle'    => 'Need to change something?',
                'noteBody'     => "Reply to this email as soon as you can — we can still adjust your order until it ships.",
                'showTracking' => true,
            ],
            'shipped' => [
                'subject'      => 'Your Order Has Shipped',
                'eyebrow'      => 'On Its Way',
                'heading'      => 'Your order is on its way.',
                'intro'        => "Your order has left our warehouse, {$name}. Use the tracking details below to follow it to your door.",
                'noteTitle'    => 'Delivery questions?',
                'noteBody'     => "Reply to this email and we'll chase it up with the carrier for you.",
                'showTracking' => true,
            ],
            'delivered' => [
                'subject'      => 'Your Order Has Been Delivered',
                'eyebrow'      => 'Delivered',
                'heading'      => 'Your order has arrived.',
                'intro'        => "Your order has been marked as delivered, {$name}. We hope your new pieces feel right at home.",
                'noteTitle'    => 'Something not right?',
                'noteBody'     => "If anything arrived damaged or isn't what you expected, reply to this email within 14 days and we'll make it right.",
                'showTracking' => true,
            ],
            'cancelled' => [
                'subject'      => 'Your Order Has Been Cancelled',
                'eyebrow'      => 'Order Cancelled',
                'heading'      => 'Your order has been cancelled.',
                'intro'        => $this->order->payment_status === 'paid'
                    ? "Your order has been cancelled, {$name}. Your refund has been issued and should reach your account within 5–10 business days."
                    : "Your order has been cancelled, {$name}. You have not been charged.",
                'noteTitle'    => "Didn't expect this?",
                'noteBody'     => "If this cancellation wasn't requested by you, reply to this email and we'll look into it right away.",
                'showTracking' => false,
            ],
            default => [
                'subject'      => 'Order Update',
                'eyebrow'      => 'Order Update',
                'heading'      => 'There is an update to your order.',
                'intro'        => "The status of your order has changed, {$name}. The latest details are below.",
                'noteTitle'    => 'Questions?',
                'noteBody'     => 'Just reply to this email and our team will help.',
                'showTracking' => true,
            ],
        };
    }
}
