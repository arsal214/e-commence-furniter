<?php

namespace App\Support;

use App\Http\Controllers\Admin\SellerController;
use App\Models\Dispute;
use App\Models\Payout;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Shipment;
use App\Models\Slider;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

/** Data for the marketplace pages of the admin (resources/views/admin/dc), in the shapes the design uses. */
class AdminViews
{
    private const SELLER_STATUS = [
        'in_review' => ['In review', '#FEF3C7', '#92400E'], 'info_requested' => ['Info requested', '#DBEAFE', '#1E40AF'],
        'approved' => ['Approved', '#ECFDF3', '#15803D'], 'rejected' => ['Rejected', '#FEE2E2', '#B91C1C'], 'draft' => ['Not submitted', '#F3F4F6', '#374151'],
    ];

    private static function tabs(array $defs, ?string $current, string $base, array $counts = []): array
    {
        return collect($defs)->map(fn ($label, $key) => [
            'label' => $label . (isset($counts[$key]) ? " ({$counts[$key]})" : ''), 'href' => $base . '?tab=' . $key,
            'bg' => $current === $key ? '#111827' : '#FFFFFF', 'fg' => $current === $key ? '#FFFFFF' : '#111827',
        ])->values()->all();
    }

    private static function pager(LengthAwarePaginator $p): string
    {
        if ($p->lastPage() <= 1) {
            return '';
        }
        $link = fn ($url, $text) => $url ? '<a href="' . e($url) . '" style="font-weight:700;font-size:13px">' . $text . '</a>' : '<span style="color:#9CA3AF;font-size:13px">' . $text . '</span>';

        return '<div style="display:flex;gap:16px;align-items:center;justify-content:center;padding:6px">' . $link($p->previousPageUrl(), '← Previous') . '<span style="font-size:13px;color:#4B5563">Page ' . $p->currentPage() . ' of ' . $p->lastPage() . '</span>' . $link($p->nextPageUrl(), 'Next →') . '</div>';
    }

    public static function overview(): array
    {
        $review = Seller::whereIn('status', ['in_review', 'info_requested'])->count();
        $mod = Product::whereNotNull('seller_id')->whereIn('moderation_status', ['pending', 'flagged'])->count();
        $flagged = Product::whereNotNull('seller_id')->where('moderation_status', 'flagged')->count();
        $disputes = Dispute::where('status', 'open')->count();
        $oldest = Dispute::where('status', 'open')->min('created_at');
        $active = Seller::where('subscription_status', 'active')->count();
        $failed = Seller::where('subscription_status', 'past_due')->count();
        $levels = Seller::where('status', 'approved')->selectRaw('level, count(*) c')->groupBy('level')->pluck('c', 'level');
        $top = max(1, (int) $levels->max());

        $attention = collect();
        foreach (Seller::whereIn('status', ['in_review', 'info_requested'])->orderBy('submitted_at')->limit(2)->get() as $s) {
            $attention->push(['t' => $s->store_name, 's' => 'Seller application · ' . ($s->status === 'info_requested' ? 'waiting for the seller' : 'documents uploaded'), 'tag' => 'Approval', 'bg' => '#FEF3C7', 'fg' => '#92400E', 'href' => route('admin.sellers.show', $s)]);
        }
        foreach (Product::whereNotNull('seller_id')->where('moderation_status', 'flagged')->limit(2)->get() as $p) {
            $attention->push(['t' => $p->name, 's' => $p->moderation_note ?: 'Flagged by the automatic check', 'tag' => 'Flagged', 'bg' => '#FEE2E2', 'fg' => '#B91C1C', 'href' => route('admin.moderation.show', $p)]);
        }
        foreach (Dispute::with('order', 'seller')->where('status', 'open')->oldest()->limit(2)->get() as $d) {
            $attention->push(['t' => $d->order->tracking_number . ' · ' . (DisputeLabels::reason($d->reason)), 's' => 'Opened ' . $d->created_at->diffForHumans() . ' · ' . $d->seller->store_name, 'tag' => 'Dispute', 'bg' => '#FEE2E2', 'fg' => '#B91C1C', 'href' => route('admin.disputes.index')]);
        }
        if ($failed) {
            $attention->push(['t' => $failed . ' ' . str('seller')->plural($failed) . ' with failed payments', 's' => 'Stores hidden until paid', 'tag' => 'Billing', 'bg' => '#DBEAFE', 'fg' => '#1E40AF', 'href' => route('admin.subscriptions.index')]);
        }

        return [
            'kpis' => [
                ['h' => 'Seller applications waiting', 'v' => (string) $review, 's' => 'Target: review within 2 business days', 'c' => '#92400E', 'href' => route('admin.sellers.index')],
                ['h' => 'Listings to moderate', 'v' => (string) $mod, 's' => "{$flagged} flagged by the automatic check", 'c' => '#B91C1C', 'href' => route('admin.moderation.index')],
                ['h' => 'Open disputes', 'v' => (string) $disputes, 's' => $oldest ? 'Oldest: ' . \Illuminate\Support\Carbon::parse($oldest)->diffForHumans(null, true) : 'None open', 'c' => '#B91C1C', 'href' => route('admin.disputes.index')],
                ['h' => 'Active sellers', 'v' => number_format($active), 's' => '$19.99/mo · ' . ($active + $failed ? round($active / ($active + $failed) * 100, 1) : 100) . '% paid up', 'c' => '#15803D', 'href' => route('admin.subscriptions.index')],
            ],
            'attention' => $attention->all(),
            'levelMix' => collect(['new' => ['New Seller', '#9CA3AF'], 'rising' => ['Rising Seller', '#2563EB'], 'top' => ['Top Seller', '#7C3AED'], 'elite' => ['Elite Seller', '#B45309']])
                ->map(fn ($v, $k) => ['n' => $v[0], 'c' => number_format((int) ($levels[$k] ?? 0)), 'w' => round(($levels[$k] ?? 0) / $top * 100) . '%', 'col' => $v[1]])->values()->all(),
        ];
    }

    public static function sellers(Request $request): array
    {
        $tab = $request->query('tab', 'review');
        $groups = ['review' => ['in_review', 'info_requested'], 'Approved' => ['approved'], 'Rejected' => ['rejected']];
        $rows = Seller::with('user')->where('onboarding_step', 'done')->when(isset($groups[$tab]), fn ($q) => $q->whereIn('status', $groups[$tab]))->orderBy('submitted_at')->limit(100)->get();

        return [
            'appTabs' => self::tabs(['review' => 'Needs review', 'Approved' => 'Approved', 'Rejected' => 'Rejected', 'all' => 'All'], $tab, route('admin.sellers.index')),
            'appRows' => $rows->map(function (Seller $s) {
                [$label, $bg, $fg] = self::SELLER_STATUS[$s->status] ?? self::SELLER_STATUS['draft'];

                return ['name' => $s->store_name, 'owner' => $s->owner_name, 'city' => trim("{$s->city}, {$s->state}", ', '), 'type' => $s->business_type, 'date' => $s->submitted_at?->format('M j'),
                    'docs' => $s->documents()->count() . ' of 4 documents', 'initial' => mb_substr($s->store_name ?: '?', 0, 1), 'href' => route('admin.sellers.show', $s), 'status' => $label, 'bg' => $bg, 'fg' => $fg];
            })->all(),
        ];
    }

    public static function seller(Seller $seller): array
    {
        $seller->load('user', 'documents');
        [$label, $bg, $fg] = self::SELLER_STATUS[$seller->status] ?? self::SELLER_STATUS['draft'];
        $checks = SellerController::checks($seller);
        $blockers = SellerController::blockers($seller);
        $mark = ['ok' => ['✓', '#15803D'], 'warn' => ['!', '#92400E'], 'fail' => ['✕', '#B91C1C']];
        $docLabels = ['registration' => 'Registration certificate', 'id_front' => 'ID, front', 'id_back' => 'ID, back', 'ein_letter' => 'EIN letter (IRS CP 575)'];

        return ['app' => [
            'name' => $seller->store_name, 'status' => $label, 'bg' => $bg, 'fg' => $fg,
            'fields' => collect([
                'Store name' => $seller->store_name, 'Legal name' => $seller->business_name, 'Business type' => $seller->business_type, 'EIN' => $seller->ein, 'Registered in' => $seller->state,
                'Business address' => trim("{$seller->address}, {$seller->city} {$seller->zip}", ', '), 'Business phone' => $seller->business_phone,
                'Owner' => $seller->owner_name . ($seller->owner_title ? " ({$seller->owner_title})" : ''), 'Owner email' => $seller->owner_email, 'Owner phone' => $seller->owner_phone,
                'Date of birth' => $seller->owner_dob, 'SSN (last 4)' => $seller->owner_ssn_last4, 'Home address' => $seller->owner_address, 'Account email' => $seller->user->email,
                'Plan' => '$19.99/mo · ' . str_replace('_', ' ', $seller->subscription_status), 'Bank' => $seller->stripe_account_id ? 'Connected through Stripe' : 'Not connected',
                'Applied' => $seller->submitted_at?->format('M j, Y'),
            ])->map(fn ($v, $k) => ['k' => $k, 'v' => $v ?: '—'])->values()->all(),
            'docs' => collect($docLabels)->map(function ($label, $type) use ($seller) {
                $doc = $seller->documents->firstWhere('type', $type);

                return ['t' => $label, 'href' => $doc ? route('admin.sellers.document', [$seller, $type]) : '#', 'open' => $doc ? 'Open document' : 'Missing', 's' => $doc ? 'Uploaded ' . $doc->created_at->format('M j') : 'Missing', 'c' => $doc ? '#15803D' : '#B91C1C'];
            })->values()->all(),
            'checks' => collect($checks)->map(fn ($c) => ['m' => $mark[$c[0]][0], 't' => $c[1], 'c' => $mark[$c[0]][1]])->all(),
            'canDecide' => in_array($seller->status, ['in_review', 'info_requested'], true), 'decided' => ! in_array($seller->status, ['in_review', 'info_requested'], true),
            'blockers' => $blockers ? implode('; ', $blockers) : '', 'postUrl' => route('admin.sellers.decide', $seller), 'note' => $seller->admin_note,
        ]];
    }

    public static function products(Request $request): array
    {
        $tab = in_array($request->query('tab'), ['pending', 'flagged', 'approved', 'removed'], true) ? $request->query('tab') : 'pending';
        $base = Product::whereNotNull('seller_id');
        $counts = (clone $base)->selectRaw('moderation_status, count(*) c')->groupBy('moderation_status')->pluck('c', 'moderation_status')->all();
        $page = (clone $base)->with('seller', 'category.parent')->where('moderation_status', $tab)->orderBy('created_at')->paginate(20)->withQueryString();
        $colors = ['pending' => ['Pending', '#FEF3C7', '#92400E'], 'flagged' => ['Flagged', '#FEE2E2', '#B91C1C'], 'approved' => ['Approved', '#ECFDF3', '#15803D'], 'removed' => ['Removed', '#F3F4F6', '#374151']];

        return [
            'modTabs' => self::tabs(['pending' => 'Pending', 'flagged' => 'Flagged', 'approved' => 'Approved', 'removed' => 'Removed'], $tab, route('admin.moderation.index'), $counts),
            'modRows' => $page->getCollection()->map(function (Product $p) use ($colors) {
                [$label, $bg, $fg] = $colors[$p->moderation_status] ?? $colors['pending'];

                return ['name' => $p->name, 'seller' => $p->seller->store_name, 'cat' => trim(($p->category?->parent?->name ? $p->category->parent->name . ' › ' : '') . $p->category?->name, ' ›'),
                    'price' => MarketViews::money($p->from_price), 'tint' => MarketViews::tint(($p->category?->parent ?? $p->category)?->slug), 'img' => $p->image ? Product::imageUrl($p->image) : null,
                    'status' => $label, 'bg' => $bg, 'fg' => $fg, 'fc' => $p->moderation_status === 'flagged' ? '#B91C1C' : '#4B5563',
                    'flag' => $p->moderation_note ?: ($p->moderation_status === 'pending' ? 'New or edited listing · ' . $p->variants()->count() . ' options' : ''),
                    'open' => in_array($p->moderation_status, ['pending', 'flagged', 'approved'], true), 'postUrl' => route('admin.moderation.decide', $p)];
            })->all(),
            'modPager' => self::pager($page),
        ];
    }

    public static function disputes(Request $request): array
    {
        $tab = $request->query('tab', 'open');
        $rows = Dispute::with('order', 'seller')->when($tab === 'open', fn ($q) => $q->where('status', 'open'))->when($tab === 'resolved', fn ($q) => $q->where('status', '!=', 'open'))
            ->orderByRaw("status = 'open' desc")->oldest()->limit(100)->get();
        $colors = ['open' => ['Open', '#FEE2E2', '#B91C1C'], 'refunded' => ['Refunded', '#ECFDF3', '#15803D'], 'closed_seller' => ['Closed · seller', '#F3F4F6', '#374151']];

        return [
            'dispTabs' => self::tabs(['open' => 'Open', 'resolved' => 'Resolved', 'all' => 'All'], $tab, route('admin.disputes.index')),
            'disputes' => $rows->map(function (Dispute $d) use ($colors) {
                [$label, $bg, $fg] = $colors[$d->status];

                return ['reason' => DisputeLabels::reason($d->reason), 'amount' => MarketViews::money($d->amount), 'no' => $d->order->tracking_number, 'buyer' => $d->order->name, 'seller' => $d->seller->store_name,
                    'date' => $d->created_at->format('M j'), 'status' => $label, 'bg' => $bg, 'fg' => $fg, 'summary' => $d->details . ($d->resolution_note ? ' · ' . $d->resolution_note : ''),
                    'open' => $d->status === 'open', 'canManual' => $d->order->payment_method !== 'stripe', 'postUrl' => route('admin.disputes.decide', $d)];
            })->all(),
        ];
    }

    public static function payouts(): array
    {
        $now = now();
        $paidToday = Payout::where('status', 'paid')->whereDate('paid_at', $now->toDateString());
        $held = Shipment::where('status', 'delivered')->whereNull('payout_id')->whereIn('order_id', Dispute::where('status', 'open')->pluck('order_id'));
        $due = Shipment::where('status', 'delivered')->whereNull('payout_id')->where('release_at', '<=', $now->copy()->addDay());
        $colors = ['paid' => ['Paid', '#ECFDF3', '#15803D'], 'scheduled' => ['Scheduled', '#DBEAFE', '#1E40AF'], 'held' => ['Held', '#FEF3C7', '#92400E'], 'failed' => ['Failed', '#FEE2E2', '#B91C1C']];

        return [
            'payKpis' => [
                ['h' => 'Paid out today', 'v' => MarketViews::money((clone $paidToday)->sum('amount')), 's' => (clone $paidToday)->distinct('seller_id')->count('seller_id') . ' sellers'],
                ['h' => 'Due within a day', 'v' => MarketViews::money((clone $due)->sum('net')), 's' => 'Delivered parcels past their hold'],
                ['h' => 'On hold', 'v' => MarketViews::money($held->sum('net')), 's' => Dispute::where('status', 'open')->count() . ' open claims'],
                ['h' => 'Failed payouts', 'v' => (string) Payout::where('status', 'failed')->where('created_at', '>=', $now->copy()->subDays(30))->count(), 's' => 'Bank details need an update'],
            ],
            'payRows' => Payout::with('seller')->latest('id')->limit(50)->get()->map(function (Payout $p) use ($colors) {
                [$label, $bg, $fg] = $colors[$p->status] ?? $colors['scheduled'];
                $hold = Seller::LEVELS[$p->seller->level]['hold'] ?? 3;

                return ['seller' => $p->seller->store_name, 'level' => ucfirst($p->seller->level) . ' Seller', 'hold' => $hold === 0 ? 'same day' : "{$hold} " . str('day')->plural($hold),
                    'bank' => $p->failure_reason ?: ($p->seller->stripe_account_id ? 'Stripe ••' . substr($p->seller->stripe_account_id, -4) : 'No bank'), 'amt' => MarketViews::money($p->amount), 'status' => $label, 'bg' => $bg, 'fg' => $fg];
            })->all(),
        ];
    }

    public static function subs(): array
    {
        $active = Seller::where('subscription_status', 'active')->count();
        $colors = ['active' => ['Active', '#ECFDF3', '#15803D'], 'past_due' => ['Payment failed · hidden', '#FEE2E2', '#B91C1C'], 'cancelled' => ['Cancelled', '#F3F4F6', '#374151'], 'none' => ['Not subscribed', '#DBEAFE', '#1E40AF']];

        return [
            'subKpis' => [
                ['h' => 'Active subscriptions', 'v' => number_format($active), 's' => 'MRR ' . MarketViews::money($active * config('marketplace.plan_price'))],
                ['h' => 'Failed right now', 'v' => (string) Seller::where('subscription_status', 'past_due')->count(), 's' => 'Stores hidden'],
                ['h' => 'Cancelled (30 days)', 'v' => (string) Seller::where('subscription_status', 'cancelled')->where('subscription_event_at', '>=', now()->subDays(30))->count(), 's' => 'Churn'],
                ['h' => 'New sellers (30 days)', 'v' => (string) Seller::where('submitted_at', '>=', now()->subDays(30))->count(), 's' => 'Applications received'],
            ],
            'subRows' => Seller::where('onboarding_step', 'done')->orderByRaw("subscription_status = 'past_due' desc")->latest('id')->limit(100)->get()->map(function (Seller $s) use ($colors) {
                [$label, $bg, $fg] = $colors[$s->subscription_status] ?? $colors['none'];

                return ['seller' => $s->store_name, 'note' => $s->subscription_event_at ? 'Last update ' . $s->subscription_event_at->format('M j, Y') : 'No billing event yet', 'status' => $label, 'bg' => $bg, 'fg' => $fg];
            })->all(),
        ];
    }

    public static function marketing(): array
    {
        return ['banners' => Slider::orderBy('sort_order')->orderBy('id')->get()->map(fn (Slider $s) => [
            't' => $s->title, 'link' => $s->button_text ?: 'No button', 'sched' => 'Always on', 'bg' => '#FFF1E8', 'img' => $s->image ? Product::imageUrl($s->image) : null,
            'label' => $s->is_active ? 'Live · turn off' : 'Hidden · go live', 'btnBg' => $s->is_active ? '#111827' : '#FFFFFF', 'btnFg' => $s->is_active ? '#FFFFFF' : '#111827',
            'postUrl' => route('admin.marketing.banner', $s),
        ])->all()];
    }
}
