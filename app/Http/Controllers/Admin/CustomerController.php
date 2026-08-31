<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Mail\PromotionalMail;
use Illuminate\Http\Request;

/**
 * The audience list for one-to-one promotional email.
 *
 * Two separate audiences share this screen: registered customers (users) and
 * newsletter-only addresses (no account). Both are mailed the same way from the
 * compose screen, so keeping them in one place saves staff hunting for whoever
 * they meant to reach.
 *
 * @see \App\Http\Controllers\Admin\CampaignController
 */
class CustomerController extends Controller
{
    /** @var array<string, string>|null Memo for lastOfferSentAt(). */
    protected ?array $offerMap = null;

    public function index(Request $request)
    {
        $tab = $request->input('tab') === 'subscribers' ? 'subscribers' : 'customers';

        return view('admin.customers.index', [
            'tab'              => $tab,
            'customers'        => $tab === 'customers' ? $this->customers($request) : null,
            'subscribers'      => $tab === 'subscribers' ? $this->subscribers($request) : null,
            'customerCount'    => User::where('role', '!=', 'admin')->count(),
            'subscriberCount'  => NewsletterSubscriber::count(),
            'optedOutCount'    => User::where('role', '!=', 'admin')->whereNotNull('marketing_opt_out_at')->count()
                                  + NewsletterSubscriber::whereNotNull('unsubscribed_at')->count(),
            'offersSentCount'  => EmailLog::where('mailable', PromotionalMail::class)
                                    ->where('status', EmailLog::STATUS_SENT)->count(),
        ]);
    }

    /**
     * Registered customers with the order history that decides who is worth a
     * targeted offer, plus when they were last sent one.
     */
    protected function customers(Request $request)
    {
        return User::query()
            ->where('role', '!=', 'admin')
            ->withCount('orders')
            ->withSum('orders as orders_total', 'total')
            ->withMax('orders as last_order_at', 'created_at')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->string('q')->trim() . '%';
                $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->when($request->input('filter') === 'buyers', fn ($q) => $q->has('orders'))
            ->when($request->input('filter') === 'no-orders', fn ($q) => $q->doesntHave('orders'))
            ->when($request->input('filter') === 'opted-out', fn ($q) => $q->whereNotNull('marketing_opt_out_at'))
            ->when($request->input('sort') === 'spend', fn ($q) => $q->orderByDesc('orders_total'))
            ->when($request->input('sort') === 'orders', fn ($q) => $q->orderByDesc('orders_count'))
            ->when(! in_array($request->input('sort'), ['spend', 'orders'], true), fn ($q) => $q->latest('id'))
            ->paginate(25)
            ->withQueryString()
            ->through(function (User $user) {
                // One query for the whole page rather than a lookup per row.
                $user->setAttribute('last_offer_at', $this->lastOfferSentAt()[strtolower($user->email)] ?? null);

                return $user;
            });
    }

    protected function subscribers(Request $request)
    {
        return NewsletterSubscriber::query()
            ->when($request->filled('q'), fn ($q) => $q->where('email', 'like', '%' . $request->string('q')->trim() . '%'))
            ->when($request->input('filter') === 'opted-out', fn ($q) => $q->whereNotNull('unsubscribed_at'))
            ->latest('id')
            ->paginate(25)
            ->withQueryString()
            ->through(function (NewsletterSubscriber $subscriber) {
                $subscriber->setAttribute('last_offer_at', $this->lastOfferSentAt()[strtolower($subscriber->email)] ?? null);

                return $subscriber;
            });
    }

    /**
     * Last promotional send per address, keyed by lowercased email.
     *
     * Memoised: the list builders call this once per row, and the answer for the
     * whole page is a single grouped query.
     *
     * @return array<string, string>
     */
    protected function lastOfferSentAt(): array
    {
        if ($this->offerMap === null) {
            $this->offerMap = EmailLog::query()
                ->where('mailable', PromotionalMail::class)
                ->where('status', EmailLog::STATUS_SENT)
                ->selectRaw('LOWER(to_email) as addr, MAX(sent_at) as last_sent')
                ->groupBy('addr')
                ->pluck('last_sent', 'addr')
                ->all();
        }

        return $this->offerMap;
    }
}
