<?php

namespace App\Support;

use App\Models\Dispute;
use App\Support\DisputeLabels;
use App\Models\OrderItem;
use App\Models\Payout;
use App\Models\PointEntry;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Shipment;
use App\Services\SellerBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/** Data for the Seller Center pages (resources/views/seller/dc), in the shapes the design uses. */
class SellerViews
{
    private const TITLES = ['seller.dashboard' => 'Dashboard', 'seller.products.index' => 'Products', 'seller.products.create' => 'Add a product', 'seller.products.edit' => 'Edit product',
        'seller.orders' => 'Orders', 'seller.payouts' => 'Payouts', 'seller.levels' => 'Levels & rewards', 'seller.billing' => 'Billing & taxes',
        'seller.messages' => 'Messages', 'seller.help' => 'Help & guides', 'seller.marketing' => 'Marketing', 'seller.performance' => 'Performance', 'seller.referrals' => 'Referrals', 'seller.storefront' => 'Storefront', 'seller.settings' => 'Settings'];

    private static function money(float|string|null $n): string
    {
        return '$' . number_format((float) $n, 2);
    }

    /** The frame: sidebar, top bar and banners. */
    public static function shell(Seller $seller, Request $request): array
    {
        $route = $request->route()?->getName() ?? 'seller.dashboard';
        $toShip = Shipment::where('seller_id', $seller->id)->where('status', 'pending')->whereHas('order', fn ($o) => $o->where('payment_status', 'paid'))->count();
        $items = [['dashboard', 'Dashboard', '/seller', 'seller.dashboard'], ['products', 'Products', '/seller/products', 'seller.products.*'], ['orders', 'Orders', '/seller/orders', 'seller.orders'],
            ['payouts', 'Payouts', '/seller/payouts', 'seller.payouts*'], ['levels', 'Levels & rewards', '/seller/levels', 'seller.levels'], ['billing', 'Billing & taxes', '/seller/billing', 'seller.billing*'],
            ['messages', 'Messages', '/seller/messages', 'seller.messages*'], ['marketing', 'Marketing', '/seller/marketing', 'seller.marketing*'], ['performance', 'Performance', '/seller/performance', 'seller.performance'], ['referrals', 'Referrals', '/seller/referrals', 'seller.referrals'],
            ['storefront', 'Storefront', '/seller/storefront', 'seller.storefront*'], ['settings', 'Settings', '/seller/settings', 'seller.settings*'],
            ['help', 'Help & guides', '/seller/help', 'seller.help']];
        $unread = app(\App\Services\MessageService::class)->unreadForSeller($seller);
        $nav = collect($items)->map(function ($i) use ($request, $toShip, $unread) {
            $on = $request->routeIs($i[3]);
            $badge = $i[0] === 'orders' && $toShip ? (string) $toShip : ($i[0] === 'messages' && $unread ? (string) $unread : '');

            return ['label' => $i[1], 'href' => $i[2], 'bg' => $on ? '#1F2937' : 'transparent', 'fg' => $on ? '#FFFFFF' : '#D1D5DB', 'fw' => $on ? '700' : '500', 'badge' => $badge, 'hasBadge' => $badge !== ''];
        })->all();

        return [
            'nav' => $nav, 'pageTitle' => self::TITLES[$route] ?? 'Seller Center', 'storeName' => $seller->store_name, 'storeUrl' => $seller->isLive() ? $seller->url() : url('/'),
            'initials' => mb_strtoupper(collect(explode(' ', trim($seller->store_name ?: $seller->owner_name ?: 'S')))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('')),
            'isDesk' => true, 'isMobile' => false, 'pending' => $seller->status === 'in_review', 'billingFailed' => $seller->subscription_status === 'past_due',
            'vacation' => false, 'vacMsg' => '', 'menu' => false, 'hasToast' => false, 'toast' => '',
        ];
    }

    public static function dashboard(Seller $seller): array
    {
        $paidItems = fn () => OrderItem::where('order_items.seller_id', $seller->id)->whereHas('order', fn ($o) => $o->where('payment_status', 'paid'));
        $since = fn (int $days, int $to = 0) => fn ($o) => $o->where('created_at', '>=', now()->subDays($days))->where('created_at', $to === 0 ? '<=' : '<', now()->subDays($to));
        $sales30 = (float) $paidItems()->whereHas('order', $since(30))->sum('total');
        $salesPrev = (float) $paidItems()->whereHas('order', $since(60, 30))->sum('total');
        $change = $salesPrev > 0 ? round(($sales30 - $salesPrev) / $salesPrev * 100) : null;
        $orders30 = Shipment::where('seller_id', $seller->id)->where('created_at', '>=', now()->subDays(30))->count();
        $waiting = Shipment::where('seller_id', $seller->id)->where('status', 'pending')->count();
        $next = self::nextPayout($seller);
        $live = $seller->products()->where('is_active', true)->where('moderation_status', 'approved')->count();
        $review = $seller->products()->whereIn('moderation_status', ['pending', 'flagged'])->count();

        // One bar per day for 30 days, tallest = best day.
        $perDay = $paidItems()->join('orders', 'orders.id', '=', 'order_items.order_id')->where('orders.created_at', '>=', now()->subDays(29)->startOfDay())
            ->get(['order_items.total', 'orders.created_at'])->groupBy(fn ($r) => Carbon::parse($r->created_at)->toDateString())->map(fn ($g) => (float) $g->sum('total'));
        $days = collect(range(29, 0))->map(fn ($d) => $perDay[now()->subDays($d)->toDateString()] ?? 0.0);
        $max = max(1.0, (float) $days->max());
        $bars = $days->map(fn ($v, $i) => ['h' => max(3, round($v / $max * 100)) . '%', 'bg' => $i === 29 ? '#C2410C' : '#FDBA74'])->values()->all();

        $lv = self::levelBlock($seller);
        $points = (int) $seller->points;
        $hasProducts = $seller->products()->exists();
        $list = [['Verify your email', true, '/seller'], ['Subscribe to the seller plan', $seller->subscription_status === 'active', '/seller/billing'], ['Submit business & owner documents', $seller->onboarding_step === 'done', '/seller'],
            ['Add your US bank account', (bool) $seller->stripe_account_id, '/seller/payouts'], ['Add your first product', $hasProducts, '/seller/products/create'], ['Get approved by our team', $seller->status === 'approved', '/seller']];
        $done = collect($list)->where(1, true)->count();

        return [
            'kpis' => [
                ['h' => 'Sales · last 30 days', 'v' => self::money($sales30), 's' => $change === null ? 'First sales will show here' : ($change >= 0 ? '+' : '') . $change . '% vs previous 30 days', 'c' => ($change ?? 0) >= 0 ? '#15803D' : '#B91C1C'],
                ['h' => 'Orders · last 30 days', 'v' => (string) $orders30, 's' => "{$waiting} waiting to ship", 'c' => '#9A3412'],
                ['h' => 'Next payout', 'v' => self::money($next['amount']), 's' => $next['when'], 'c' => '#4B5563'],
                ['h' => 'Live products', 'v' => (string) $live, 's' => "{$review} in review", 'c' => '#4B5563'],
            ],
            'bars' => $bars, 'barLabels' => [now()->subDays(29)->format('M j'), now()->subDays(15)->format('M j')], 'lv' => $lv,
            'points' => number_format($points), 'pointsVal' => self::money($points / config('marketplace.points_per_credit_dollar')),
            'notRedeemed' => $points >= config('marketplace.points_per_credit_dollar'), 'redeemed' => false, 'redeemAmount' => '$' . intdiv($points, config('marketplace.points_per_credit_dollar')),
            'checklist' => collect($list)->map(fn ($c) => ['t' => $c[0], 'href' => $c[2], 'done' => $c[1], 'todo' => ! $c[1], 'mark' => $c[1] ? '✓' : '', 'bg' => $c[1] ? '#15803D' : '#FFFFFF',
                'bd' => $c[1] ? '#15803D' : '#D1D5DB', 'fg' => $c[1] ? '#6B7280' : '#111827', 'deco' => $c[1] ? 'line-through' : 'none'])->all(),
            'checkDone' => "{$done} of " . count($list), 'checkPct' => round($done / count($list) * 100) . '%',
            'lowStock' => $seller->products()->where('is_active', true)->where('stock', '<=', 5)->orderBy('stock')->limit(5)->get()->map(fn (Product $p) => ['name' => $p->name, 'left' => $p->stock == 0 ? 'Out of stock' : $p->stock . ' left'])->all(),
            'toShip' => Shipment::with('order')->where('seller_id', $seller->id)->where('status', 'pending')->whereHas('order', fn ($o) => $o->where('payment_status', 'paid'))->oldest()->limit(3)->get()->map(fn (Shipment $s) => self::orderLine($s) + ['no' => $s->order->tracking_number])->all(),
            'planLine' => $seller->subscription_status === 'active' ? 'billed monthly' : 'payment needed',
        ];
    }

    /** @return array{amount: float, when: string} */
    private static function nextPayout(Seller $seller): array
    {
        $due = Shipment::where('seller_id', $seller->id)->where('status', 'delivered')->whereNull('payout_id');
        $amount = (float) (clone $due)->sum('net');
        $at = (clone $due)->min('release_at');
        $to = $seller->stripe_account_id ? 'to your bank ••' . substr($seller->stripe_account_id, -4) : 'add your bank to get paid';

        return ['amount' => $amount, 'when' => $amount > 0 ? ($at && Carbon::parse($at)->isFuture() ? 'From ' . Carbon::parse($at)->format('M j') . ' ' . $to : 'Today ' . $to) : 'Nothing waiting ' . $to];
    }

    private static function levelBlock(Seller $seller): array
    {
        $sales = (float) $seller->delivered_sales;
        $levels = collect(Seller::LEVELS)->sortBy('min');
        $current = Seller::LEVELS[$seller->level];
        $nextKey = $levels->search(fn ($l) => $l['min'] > $sales);
        $next = $nextKey ? $levels[$nextKey] : null;
        $hold = $current['hold'] === 0 ? 'the same day' : $current['hold'] . ' ' . str('day')->plural($current['hold']) . ' after delivery';

        return [
            'name' => ucfirst($seller->level) . ' Seller', 'next' => $next ? ucfirst($nextKey) . ' Seller' : '', 'have' => self::money($sales), 'hold' => $hold,
            'goal' => $next ? '$' . number_format($next['min']) : '$' . number_format($current['min']),
            'need' => $next ? '$' . number_format($next['min'] - $sales, 0) : '$0',
            'pct' => $next ? min(100, round(($sales - $current['min']) / max(1, $next['min'] - $current['min']) * 100)) . '%' : '100%',
            'line' => $next ? '$' . number_format($next['min'] - $sales, 0) . ' more in sales to reach ' . ucfirst($nextKey) . ' Seller' : 'You are at the top level',
        ];
    }

    private static function orderLine(Shipment $s, $claims = null): array
    {
        $lines = $s->lines()->with('product')->get();
        $first = $lines->first();
        $more = $lines->count() - 1;
        $o = $s->order;
        $claim = $claims ? $claims->get($o->id) : Dispute::where('order_id', $o->id)->where('seller_id', $s->seller_id)->where('status', 'open')->latest('id')->first();

        // What a seller needs to pack and ship: who, where, what. The buyer's email is deliberately not here.
        return ['items' => ($first ? $first->name . ' × ' . $first->qty : 'Order') . ($more > 0 ? " +{$more} more" : ''), 'buyer' => $o->name, 'city' => trim("{$o->address}, {$o->city}, {$o->state} {$o->zip}", ', '),
            'phone' => (string) $o->phone, 'addrLines' => array_values(array_filter([$o->address, $o->address2, trim("{$o->city}, {$o->state} {$o->zip}", ', ')])),
            'note' => trim((string) $o->notes), 'paid' => $o->payment_status === 'paid',
            'refund' => ['can' => $o->payment_status === 'paid' && $s->status !== 'refunded', 'amount' => self::money($s->gross), 'url' => route('seller.orders.refund', $s), 'declineUrl' => route('seller.orders.refund.decline', $s),
                'claim' => $claim ? ['reason' => DisputeLabels::reason($claim->reason), 'details' => (string) $claim->details, 'when' => $claim->created_at->format('M j'),
                    'declined' => (bool) $claim->seller_declined_at, 'note' => (string) $claim->seller_note] : null],
            'lines' => $lines->map(fn ($l) => ['name' => $l->name, 'variant' => collect([$l->color ? "Color: {$l->color}" : null, $l->size ? "Size: {$l->size}" : null])->filter()->implode(' · '),
                'sku' => (string) $l->sku, 'qty' => (int) $l->qty, 'unit' => self::money($l->price), 'total' => self::money($l->total),
                'img' => $l->product?->image ? Product::imageUrl($l->product->image) : null])->all(),
            'due' => $s->status === 'pending' ? 'Ship by ' . ($o->created_at->copy()->addDays(max(1, (int) $s->seller->handling_days))->format('M j')) : ''];
    }

    public static function orders(Seller $seller, ?string $tab): array
    {
        $tab = in_array($tab, ['pending', 'shipped', 'delivered', 'refunded'], true) ? $tab : 'pending';
        $counts = Shipment::where('seller_id', $seller->id)->whereHas('order', fn ($o) => $o->where('payment_status', 'paid'))->selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status');
        $label = ['pending' => 'To ship', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'refunded' => 'Refunded'];
        $rows = Shipment::with('order', 'seller')->where('seller_id', $seller->id)->where('status', $tab)->whereHas('order', fn ($o) => $o->where('payment_status', 'paid'))->latest('id')->limit(100)->get();
        $claims = Dispute::where('seller_id', $seller->id)->where('status', 'open')->latest('id')->get()->unique('order_id')->keyBy('order_id');

        return [
            'orderTabs' => collect($label)->map(fn ($l, $k) => ['label' => $l . ' (' . ($counts[$k] ?? 0) . ')', 'href' => route('seller.orders', ['tab' => $k]),
                'bg' => $tab === $k ? '#111827' : '#FFFFFF', 'fg' => $tab === $k ? '#FFFFFF' : '#111827'])->values()->all(),
            'orderRows' => $rows->map(function (Shipment $s) use ($claims) {
                $base = self::orderLine($s, $claims);

                return array_merge($base, ['no' => $s->order->tracking_number, 'date' => $s->order->created_at->format('M j'), 'total' => self::money($s->gross), 'canShip' => $s->status === 'pending',
                    'shipUrl' => route('seller.orders.ship', $s), 'labeled' => in_array($s->status, ['shipped', 'delivered'], true), 'isReturn' => false,
                    'labelText' => $s->status === 'delivered' ? 'Delivered ' . $s->delivered_at?->format('M j') : "Shipped with {$s->carrier} · tracking {$s->tracking_number}",
                    'due' => $s->status === 'pending' ? $base['due'] : ($s->status === 'refunded' ? 'Buyer refunded' : 'You earn ' . self::money($s->net) . ' after card fees')]);
            })->all(),
        ];
    }

    public static function payouts(Seller $seller): array
    {
        $next = self::nextPayout($seller);
        $level = Seller::LEVELS[$seller->level];
        $due = Shipment::where('seller_id', $seller->id)->where('status', 'delivered')->whereNull('payout_id')->orderBy('release_at')->get()->groupBy(fn ($s) => $s->release_at->toDateString());
        $afterDelivery = Shipment::where('seller_id', $seller->id)->where('status', 'shipped');
        $upcoming = $due->map(fn ($g, $d) => ['d' => Carbon::parse($d)->isPast() || Carbon::parse($d)->isToday() ? 'Next payout run' : Carbon::parse($d)->format('D, M j'), 't' => $g->count() . ' ' . str('parcel')->plural($g->count()) . ' delivered', 'v' => self::money($g->sum('net'))])->values();
        if ($afterDelivery->exists()) {
            $upcoming->push(['d' => 'After delivery', 't' => (clone $afterDelivery)->count() . ' shipped, not delivered yet', 'v' => self::money((clone $afterDelivery)->sum('net'))]);
        }
        $held = Dispute::where('seller_id', $seller->id)->where('status', 'open')->count();

        return [
            'nextLabel' => $next['amount'] > 0 ? 'NEXT PAYOUT' : 'NO PAYOUT WAITING', 'nextAmount' => self::money($next['amount']), 'nextTo' => $next['when'],
            'holdLine' => 'Daily payouts · ' . ucfirst($seller->level) . ' Seller hold: ' . ($level['hold'] === 0 ? 'same day as delivery' : $level['hold'] . ' ' . str('day')->plural($level['hold']) . ' after delivery') . ($held ? " · {$held} open " . str('claim')->plural($held) . ' on hold' : ''),
            'bankLine' => ($seller->business_name ?: $seller->store_name) . ($seller->stripe_account_id ? ' · Account ••' . substr($seller->stripe_account_id, -4) : ' · no bank connected'), 'bankReady' => (bool) $seller->stripe_account_id,
            'payoutsUpcoming' => $upcoming->all(),
            'payoutsHist' => Payout::where('seller_id', $seller->id)->latest('id')->limit(30)->get()->map(fn (Payout $p) => ['d' => $p->created_at->format('M j'), 'v' => self::money($p->amount),
                's' => $p->status === 'paid' ? 'Paid' : ($p->status === 'failed' ? 'Failed: ' . $p->failure_reason : ucfirst($p->status)), 'c' => $p->status === 'paid' ? '#15803D' : '#92400E'])->all(),
        ];
    }

    public static function levels(Seller $seller): array
    {
        $lv = self::levelBlock($seller);
        $points = (int) $seller->points;
        $cards = collect([['new', 'New Seller', '3 days', 'Standard', 'Standard', '#F3F4F6', '#374151'], ['rising', 'Rising Seller', '2 days', 'Higher', 'Standard', '#DBEAFE', '#1E40AF'],
            ['top', 'Top Seller', '1 day', 'Even higher', 'Priority', '#EDE9FE', '#5B21B6'], ['elite', 'Elite Seller', 'Same day', 'Top', 'Priority, fastest', '#FEF3C7', '#92400E']])
            ->map(fn ($l) => ['name' => $l[1], 'min' => '$' . number_format(Seller::LEVELS[$l[0]]['min']), 'hold' => $l[2], 'search' => $l[3], 'support' => $l[4], 'bg' => $l[5], 'fg' => $l[6],
                'current' => $seller->level === $l[0], 'bd' => $seller->level === $l[0] ? '#111827' : '#E5E7EB'])->all();

        return [
            'levelLine' => $lv['name'] . ' · ' . $lv['have'] . ' lifetime sales', 'levelPct' => $lv['pct'], 'nextLine' => $lv['next'] ? $lv['need'] . ' more to ' . $lv['next'] . '.' : 'You have reached the top level.',
            'levelCards' => $cards, 'points' => number_format($points), 'pointsVal' => self::money($points / config('marketplace.points_per_credit_dollar')),
            'ptsHist' => PointEntry::where('seller_id', $seller->id)->latest('id')->limit(20)->get()->map(fn ($e) => ['d' => $e->created_at->format('M j'), 't' => $e->reason, 'v' => ($e->delta > 0 ? '+' : '−') . abs($e->delta), 'c' => $e->delta > 0 ? '#15803D' : '#B91C1C'])->all(),
        ];
    }

    public static function billing(Seller $seller): array
    {
        $status = ['active' => 'Active', 'past_due' => 'Payment failed', 'cancelled' => 'Cancelled', 'none' => 'Not subscribed'][$seller->subscription_status] ?? 'Unknown';

        return ['planStatus' => $status, 'planLine' => $seller->subscription_status === 'active' ? 'billed monthly' : 'your store is hidden until the plan is paid',
            'invoices' => app(SellerBilling::class)->invoices($seller)];
    }
}
