<?php

namespace App\Mail;

use App\Models\User;
use App\Support\EmailHtml;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

/**
 * A deals-and-offers email written by staff and sent to one customer.
 *
 * Everything on it is authored in the admin compose screen; nothing is derived
 * from the customer's account beyond the name used for the greeting and merge
 * tags. Sent one recipient at a time on purpose — each send is its own message
 * with its own unsubscribe link, so recipients never see each other and an
 * opt-out is unambiguous.
 *
 * @see \App\Http\Controllers\Admin\CampaignController::send()
 */
class PromotionalMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string       $subjectLine   Already merge-tag substituted.
     * @param  string       $bodyHtml      Sanitised editor HTML.
     * @param  string|null  $ctaLabel      Renders the action button when paired with a URL.
     * @param  User|null    $user          Present only for registered customers; lets the
     *                                     email log attribute the send to the account.
     */
    public function __construct(
        public string $subjectLine,
        public string $bodyHtml,
        public string $recipientName = '',
        public ?string $eyebrow = null,
        public ?string $heading = null,
        public ?string $ctaLabel = null,
        public ?string $ctaUrl = null,
        public ?string $promoCode = null,
        public ?string $promoNote = null,
        public ?string $unsubscribeUrl = null,
        public ?User $user = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->subjectLine);
    }

    /**
     * List-Unsubscribe lets Gmail and Outlook show their own unsubscribe control
     * next to the sender. Without it bulk mail is far likelier to be marked spam
     * rather than unsubscribed, which damages the sending domain's reputation.
     */
    public function headers(): Headers
    {
        if (! $this->unsubscribeUrl) {
            return new Headers();
        }

        return new Headers(text: [
            'List-Unsubscribe'      => '<' . $this->unsubscribeUrl . '>',
            'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
        ]);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.promotion',
            with: [
                'plainText' => EmailHtml::toPlainText($this->bodyHtml),
            ],
        );
    }
}
