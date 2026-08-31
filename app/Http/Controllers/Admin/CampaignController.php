<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PromotionalMail;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use App\Models\User;
use App\Support\EmailHtml;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * Composes and sends a deals-and-offers email to a single recipient.
 *
 * One recipient per send by design. The store mails from its own SMTP account,
 * and a bulk blast from a shared host is the fastest way to get the sending
 * domain blocked; sending individually also means every message is personalised
 * and carries its own unsubscribe link.
 *
 * The recipient is addressed as ?user={id} for an account holder or
 * ?subscriber={id} for a newsletter-only address, rather than as a free-text
 * email field — staff pick from the customer list, they do not retype addresses.
 */
class CampaignController extends Controller
{
    public function compose(Request $request)
    {
        [$recipient, $type] = $this->resolveRecipient($request);

        return view('admin.campaigns.compose', [
            'recipient'     => $recipient,
            'type'          => $type,
            'name'          => $type === 'user' ? $recipient->name : '',
            'email'         => $recipient->email,
            'optedOut'      => ! $recipient->acceptsMarketing(),
            'mergeTags'     => EmailHtml::mergePreview($type === 'user' ? (string) $recipient->name : '', $recipient->email),
            'templates'     => EmailTemplate::active()->ordered()->get(),
            'quickLinks'    => $this->quickLinks(),
            'recentOffers'  => EmailLog::where('mailable', PromotionalMail::class)
                                    ->where('to_email', $recipient->email)
                                    ->latest('sent_at')->limit(5)->get(),
        ]);
    }

    /**
     * Render the composed email exactly as it will be sent, for the preview
     * pane. Returns bare HTML — the compose screen drops it into an iframe so
     * the email's own CSS cannot leak into the admin panel.
     */
    public function preview(Request $request)
    {
        [$recipient, $type] = $this->resolveRecipient($request);
        $data = $this->validated($request);

        return response($this->build($data, $recipient, $type, preview: true)->render())
            ->header('Content-Type', 'text/html; charset=utf-8');
    }

    public function send(Request $request)
    {
        [$recipient, $type] = $this->resolveRecipient($request);
        $data = $this->validated($request);

        // A test goes to the signed-in admin, so staff can check rendering in a
        // real inbox without spending a send on the customer.
        $isTest = $request->boolean('test');

        if (! $isTest && ! $recipient->acceptsMarketing()) {
            return back()->withInput()->with('error',
                $recipient->email . ' has unsubscribed from offers. Marketing email cannot be sent to them.');
        }

        $toEmail = $isTest ? $request->user()->email : $recipient->email;
        $toName  = $isTest ? $request->user()->name : ($type === 'user' ? $recipient->name : '');

        $mailable = $this->build($data, $recipient, $type);

        try {
            Mail::to($toEmail, $toName ?: null)->send($mailable);
        } catch (\Throwable $e) {
            \Log::warning('Promotional email failed for ' . $toEmail . ': ' . $e->getMessage());
            EmailLog::recordFailure(
                $toEmail,
                PromotionalMail::class,
                $e->getMessage(),
                $type === 'user' ? $recipient->id : null,
            );

            return back()->withInput()->with('error', 'Email could not be sent — check the email log for the reason.');
        }

        if ($isTest) {
            return back()->withInput()->with('success', 'Test email sent to ' . $toEmail . '. The draft is still here.');
        }

        return redirect()
            ->route('admin.customers.index', $type === 'subscriber' ? ['tab' => 'subscribers'] : [])
            ->with('success', 'Offer emailed to ' . $toEmail . '.');
    }

    /**
     * Build the mailable with merge tags resolved against this recipient.
     *
     * In preview mode the unsubscribe link is omitted: signedRoute would work,
     * but there is no reason to mint a live opt-out URL for a page that is only
     * being looked at.
     */
    protected function build(array $data, User|NewsletterSubscriber $recipient, string $type, bool $preview = false): PromotionalMail
    {
        $name  = $type === 'user' ? (string) $recipient->name : '';
        $email = (string) $recipient->email;

        $merge = fn (?string $value) => filled($value) ? EmailHtml::merge($value, $name, $email) : null;

        return new PromotionalMail(
            subjectLine:    (string) $merge($data['subject']),
            bodyHtml:       (string) $merge(EmailHtml::sanitize($data['body_html'])),
            // The greeting reads better on a first name; the full name stays
            // available to the author through the {{name}} merge tag.
            recipientName:  (string) trim(strtok(trim($name), ' ') ?: ''),
            eyebrow:        $merge($data['eyebrow'] ?? null),
            heading:        $merge($data['heading'] ?? null),
            ctaLabel:       $data['cta_label'] ?? null,
            ctaUrl:         $data['cta_url'] ?? null,
            promoCode:      $data['promo_code'] ?? null,
            promoNote:      $merge($data['promo_note'] ?? null),
            unsubscribeUrl: $preview ? null : EmailHtml::unsubscribeUrl($email),
            user:           $type === 'user' ? $recipient : null,
        );
    }

    /**
     * @return array{0: User|NewsletterSubscriber, 1: string}
     */
    protected function resolveRecipient(Request $request): array
    {
        if ($request->filled('user')) {
            $user = User::where('role', '!=', 'admin')->findOrFail($request->integer('user'));

            return [$user, 'user'];
        }

        if ($request->filled('subscriber')) {
            return [NewsletterSubscriber::findOrFail($request->integer('subscriber')), 'subscriber'];
        }

        throw ValidationException::withMessages([
            'recipient' => 'Pick a customer or subscriber to email.',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request): array
    {
        return $request->validate([
            'subject'    => 'required|string|max:180',
            'eyebrow'    => 'nullable|string|max:60',
            'heading'    => 'nullable|string|max:120',
            'body_html'  => 'required|string|max:60000',
            'cta_label'  => 'nullable|string|max:40|required_with:cta_url',
            'cta_url'    => 'nullable|url|max:500|required_with:cta_label',
            'promo_code' => 'nullable|string|max:40',
            'promo_note' => 'nullable|string|max:160',
        ], [
            'cta_label.required_with' => 'Give the button a label, or clear the button link.',
            'cta_url.required_with'   => 'Give the button a link, or clear the button label.',
        ]);
    }

    /**
     * Store URLs offered as one-click fills for the button link, so staff never
     * have to hand-type a campaign destination.
     *
     * @return array<int, array{label: string, url: string}>
     */
    protected function quickLinks(): array
    {
        $links = [
            ['label' => 'Shop — all products', 'url' => url('/shop')],
            ['label' => 'Homepage',            'url' => url('/')],
        ];

        try {
            foreach (\App\Models\Category::orderBy('name')->limit(8)->get() as $category) {
                $links[] = ['label' => 'Category — ' . $category->name, 'url' => url('/category/' . $category->slug)];
            }

            foreach (Product::where('is_best_seller', true)->orderBy('name')->limit(8)->get() as $product) {
                $links[] = ['label' => 'Product — ' . $product->name, 'url' => url('/product-details/' . $product->slug)];
            }
        } catch (\Throwable $e) {
            // Convenience only — a schema that has drifted must not break compose.
            \Log::warning('Campaign quick links unavailable: ' . $e->getMessage());
        }

        return $links;
    }
}
