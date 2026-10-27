<?php

namespace App\Support;

use App\Models\Dispute;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Shipment;
use App\Services\ReferralService;

/** Numbers for the Seller Center Performance and Referrals pages. */
class SellerInsights
{
    public const WINDOW_DAYS = 90;
    /** Targets shown next to each health number. */
    public const ON_TIME_TARGET = 95;
    public const DISPUTE_TARGET = 1;
    public const REFUND_TARGET = 2;

    public static function performance(Seller $seller, string $filter): array
    {
        $since = now()->subDays(self::WINDOW_DAYS);
        $shipments = Shipment::with('order:id,created_at')->where('seller_id', $seller->id)->where('created_at', '>=', $since)
            ->whereHas('order', fn ($o) => $o->where('payment_status', 'paid'))->get();

        // Due = already shipped, or past its ship-by date. Shipped on time = by the end of the handling window.
        $handling = max(1, (int) $seller->handling_days);
        $due = $shipments->filter(fn ($s) => $s->shipped_at || $s->order->created_at->copy()->addDays($handling)->endOfDay()->isPast());
        $onTime = $due->filter(fn ($s) => $s->shipped_at && $s->shipped_at->lte($s->order->created_at->copy()->addDays($handling)->endOfDay()));
        $onTimePct = $due->count() ? round($onTime->count() / $due->count() * 100, 1) : null;

        $disputes = Dispute::where('seller_id', $seller->id)->where('created_at', '>=', $since)->count();
        $disputePct = $shipments->count() ? round($disputes / $shipments->count() * 100, 1) : null;
        $refunded = $shipments->where('status', 'refunded')->count();
        $refundPct = $shipments->count() ? round($refunded / $shipments->count() * 100, 1) : null;

        $reviews = Review::with('product:id,name,slug')->whereIn('product_id', $seller->products()->pluck('id'));
        $all = (clone $reviews)->get(['id', 'rating']);
        $avg = $all->count() ? round((float) $all->avg('rating'), 1) : null;
        $needReply = (clone $reviews)->whereNull('seller_reply')->count();

        $list = (clone $reviews)->when($filter === 'reply', fn ($q) => $q->whereNull('seller_reply'))->when($filter === 'low', fn ($q) => $q->where('rating', '<=', 3))
            ->latest('id')->limit(30)->get();

        $good = fn (?float $v, int $target, bool $higher) => $v === null ? ['No data yet', '#6B7280', 0] : (($higher ? $v >= $target : $v <= $target) ? ['On target', '#15803D', 100] : ['Needs attention', '#B91C1C', 60]);
        $health = collect([
            ['On-time shipping', $onTimePct, self::ON_TIME_TARGET . '%+', true, self::ON_TIME_TARGET],
            ['Dispute rate', $disputePct, self::DISPUTE_TARGET . '% or less', false, self::DISPUTE_TARGET],
            ['Refund rate', $refundPct, self::REFUND_TARGET . '% or less', false, self::REFUND_TARGET],
        ])->map(function ($r) use ($good) {
            [$status, $color, $pct] = $good($r[1], $r[4], $r[3]);

            return ['t' => $r[0], 'v' => $r[1] === null ? '—' : $r[1] . '%', 'target' => $r[2], 'status' => $status, 'color' => $color, 'pct' => $pct . '%'];
        })->all();

        $money = fn ($n) => '$' . number_format((float) $n, 2);

        return [
            'perfKpis' => [
                ['h' => 'Seller rating', 'v' => $avg ? $avg . ' ★' : 'No reviews yet', 's' => $all->count() . ' ' . str('review')->plural($all->count())],
                ['h' => 'On-time shipping', 'v' => $onTimePct === null ? '—' : $onTimePct . '%', 's' => 'Last ' . self::WINDOW_DAYS . ' days · target ' . self::ON_TIME_TARGET . '%+'],
                ['h' => 'Dispute rate', 'v' => $disputePct === null ? '—' : $disputePct . '%', 's' => $disputes . ' ' . str('claim')->plural($disputes) . ' · target ' . self::DISPUTE_TARGET . '% or less'],
                ['h' => 'Reviews to answer', 'v' => (string) $needReply, 's' => 'Public replies build trust'],
            ],
            'health' => $health, 'allGood' => collect($health)->every(fn ($h) => $h['status'] !== 'Needs attention'),
            'avg' => $avg, 'ratingCount' => $all->count(),
            'rateBars' => collect([5, 4, 3, 2, 1])->map(fn ($s) => ['s' => $s, 'pct' => $all->count() ? round($all->where('rating', $s)->count() / $all->count() * 100) . '%' : '0%'])->all(),
            'filter' => $filter,
            'revTabs' => [['k' => '', 'label' => 'All'], ['k' => 'reply', 'label' => 'Needs reply'], ['k' => 'low', 'label' => '3 stars or less']],
            'reviews' => $list,
            'refunds' => Dispute::with('order:id,tracking_number')->where('seller_id', $seller->id)->where('status', 'refunded')->where('resolved_at', '>=', now()->subDays(30))->latest('resolved_at')->limit(10)->get()
                ->map(fn ($d) => ['amt' => $money($d->amount), 'reason' => DisputeLabels::reason($d->reason), 'no' => $d->order?->tracking_number, 'd' => $d->resolved_at?->format('M j')])->all(),
        ];
    }

    public static function referrals(Seller $seller, ReferralService $svc): array
    {
        $rows = Seller::where('referred_by', $seller->id)->latest('id')->get()->map(function (Seller $r) {
            [$status, $reward] = match (true) {
                (bool) $r->referral_credited_at => ['Approved and paid · free month earned', '+$' . number_format(Seller::SUBSCRIPTION_PRICE, 2)],
                $r->status === 'approved' && $r->subscription_status === 'active' => ['Approved · your credit is being applied', 'Pending'],
                $r->status === 'approved' => ['Approved · waiting for their first payment', 'Pending'],
                in_array($r->status, ['rejected'], true) => ['Application not approved', '—'],
                default => ['Signed up · application in progress', 'Pending'],
            };

            return ['n' => $r->store_name ?: ($r->business_name ?: 'New seller'), 's' => $status, 'r' => $reward];
        })->all();

        return [
            'refLink' => $svc->linkFor($seller), 'refs' => $rows, 'used' => $svc->usedThisYear($seller), 'max' => ReferralService::MAX_PER_YEAR,
            'price' => '$' . number_format(Seller::SUBSCRIPTION_PRICE, 2),
        ];
    }
}
