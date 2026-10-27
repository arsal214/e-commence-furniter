<?php

namespace App\Support;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Wishlist;

/** Data for the shopper's account (resources/views/market/dc/account), in the shapes the design uses. */
class AccountViews
{
    public const TABS = ['overview' => 'Overview', 'orders' => 'Orders & tracking', 'returns' => 'Returns & claims', 'reviews' => 'Your reviews', 'wishlist' => 'Saved items',
        'rewards' => 'Rewards', 'giftcards' => 'Gift cards', 'wallet' => 'Addresses', 'messages' => 'Messages', 'following' => 'Following', 'notifications' => 'Notifications', 'settings' => 'Login & security'];

    private const STATUS = ['pending' => ['Preparing', '#FEF3C7', '#92400E', 1], 'shipped' => ['Shipped', '#DBEAFE', '#1E40AF', 2],
        'delivered' => ['Delivered', '#ECFDF3', '#15803D', 3], 'refunded' => ['Refunded', '#F3F4F6', '#374151', 0]];

    public static function build(User $user, string $tab): array
    {
        $orders = self::orders($user);
        $wishlist = Wishlist::where('user_id', $user->id)->whereHas('product', fn ($q) => $q->visible())->with('product.category.parent')->latest()->get();
        $toReview = self::toReview($user);
        $active = collect($orders)->first(fn ($o) => ! $o['delivered'] && $o['status'] !== 'Refunded');

        $card = fn (Product $p) => ['id' => $p->id, 'href' => route('product-details', $p->slug), 'name' => $p->name, 'price' => MarketViews::money($p->from_price),
            'sub' => $p->category?->name ?? '', 'tint' => MarketViews::tint(($p->category?->parent ?? $p->category)?->slug), 'img' => $p->image ? Product::imageUrl($p->image) : null, 'hasDrop' => false, 'drop' => ''];

        $titles = ['overview' => 'Account overview'] + self::TABS;

        return [
            'acctInitial' => mb_strtoupper(mb_substr(trim($user->name) ?: '?', 0, 1)), 'acctName' => $user->name,
            'acctSince' => 'Member since ' . ($user->created_at?->format('Y') ?? now()->format('Y')) . ' · ' . count($orders) . ' ' . str('order')->plural(count($orders)),
            'acctTitle' => $titles[$tab], 'user' => $user,
            'acctTabs' => collect(self::TABS)->map(fn ($label, $key) => ['label' => $label, 'href' => $key === 'overview' ? url('/my-account') : url("/my-account/{$key}"),
                'bg' => $tab === $key ? '#FFF1E8' : 'transparent', 'fg' => $tab === $key ? '#9A3412' : '#111827', 'fw' => $tab === $key ? '700' : '500',
                'mbg' => $tab === $key ? '#111827' : '#F3F4F6', 'mfg' => $tab === $key ? '#FFFFFF' : '#111827'])->values()->all(),
            'tab_overview' => $tab === 'overview', 'tab_orders' => $tab === 'orders', 'tab_wishlist' => $tab === 'wishlist', 'tab_reviews' => $tab === 'reviews', 'tab_security' => $tab === 'settings',
            'tab_returns' => $tab === 'returns', 'tab_wallet' => $tab === 'wallet', 'tab_following' => $tab === 'following', 'tab_notifications' => $tab === 'notifications',
            'tab_rewards' => $tab === 'rewards', 'tab_giftcards' => $tab === 'giftcards', 'tab_messages' => $tab === 'messages',
            'ovLabel' => $active ? strtoupper($active['status']) : 'NOTHING ON THE WAY',
            'ovActive' => $active ?: ['eta' => 'No orders on the way right now', 'line' => 'Browse the marketplace to find something you love.', 'steps' => [], 'trackUrl' => ''],
            'ovTiles' => [
                ['href' => url('/my-account/orders'), 'ini' => 'O', 't' => 'Orders', 'd' => count($orders) . ' ' . str('package')->plural(count($orders)) . ' so far', 'hasBadge' => false, 'badge' => '', 'bg' => '#F3F4F6', 'fg' => '#374151'],
                ['href' => url('/my-account/wishlist'), 'ini' => 'W', 't' => 'Wishlist', 'd' => $wishlist->count() . ' saved', 'hasBadge' => false, 'badge' => '', 'bg' => '#F3F4F6', 'fg' => '#374151'],
                ['href' => url('/my-account/reviews'), 'ini' => 'R', 't' => 'Reviews', 'd' => count($toReview) ? count($toReview) . ' waiting for your review' : 'All caught up', 'hasBadge' => count($toReview) > 0, 'badge' => (string) count($toReview), 'bg' => '#FFF1E8', 'fg' => '#9A3412'],
                ['href' => url('/my-account/settings'), 'ini' => 'S', 't' => 'Settings', 'd' => 'Name, email, password and emails', 'hasBadge' => false, 'badge' => '', 'bg' => '#F3F4F6', 'fg' => '#374151'],
            ],
            'buyAgain' => Product::visible()->with('category.parent')->whereIn('products.id', OrderItem::whereHas('order', fn ($o) => $o->where('user_id', $user->id)->where('payment_status', 'paid'))->latest('id')->limit(30)->pluck('product_id'))
                ->limit(6)->get()->map($card)->all(),
            'orders' => $orders,
            'wishEmpty' => $wishlist->isEmpty(), 'wishSome' => $wishlist->isNotEmpty(), 'wishItems' => $wishlist->map(fn ($w) => $card($w->product))->all(),
            'toReview' => $toReview,
            ...($tab === 'returns' ? self::claims($user, $orders) : ['claims' => [], 'claimOrders' => []]), 'addresses' => $tab === 'wallet' ? $user->addresses()->orderByDesc('is_default')->latest('id')->get() : collect(),
            'follows' => $tab === 'following' ? self::follows($user) : [], ...($tab === 'rewards' ? self::rewards($user) : []), ...($tab === 'giftcards' ? self::giftCards($user) : []), ...($tab === 'messages' ? self::messages($user) : []), 'marketingOn' => $user->marketing_opt_out_at === null,
            'myRevs' => Review::with('product')->where('user_id', $user->id)->latest('id')->get()->map(fn ($r) => ['name' => $r->product?->name, 'href' => $r->product ? route('product-details', $r->product->slug) : '#',
                'rating' => (int) $r->rating, 'text' => $r->comment, 'date' => $r->created_at->format('M j, Y')])->all(),
        ];
    }

    /** One card per seller parcel (or per order for house items) of the shopper's paid orders. */
    private static function orders(User $user): array
    {
        $items = OrderItem::with('order', 'product.category.parent', 'seller')->whereHas('order', fn ($o) => $o->where('user_id', $user->id)->where('payment_status', 'paid'))->latest('id')->get();
        $shipments = Shipment::whereIn('order_id', $items->pluck('order_id')->unique())->get()->keyBy(fn ($s) => $s->order_id . '-' . $s->seller_id);
        $cards = [];

        foreach ($items->groupBy(fn ($i) => $i->order_id . '-' . ($i->seller_id ?? 'house')) as $key => $lines) {
            $first = $lines->first();
            $order = $first->order;
            $ship = $first->seller_id ? $shipments->get($order->id . '-' . $first->seller_id) : null;
            $state = $ship?->status ?? (['shipped' => 'shipped', 'delivered' => 'delivered', 'cancelled' => 'refunded'][$order->status] ?? 'pending');
            [$label, $bg, $fg, $step] = self::STATUS[$state];

            $cards[] = [
                'no' => $order->tracking_number, 'date' => $order->created_at->format('M j, Y'), 'total' => MarketViews::money($lines->sum('total')),
                'storeName' => $first->seller?->store_name ?? 'Peyton Ghalib', 'storeHref' => $first->seller?->isLive() ? $first->seller->url() : url('/'),
                'status' => $label, 'sb' => $bg, 'sc' => $fg, 'delivered' => $state === 'delivered', 'trackUrl' => route('track-order', ['tracking' => $order->tracking_number]),
                'eta' => ['pending' => 'Waiting for the seller to ship', 'shipped' => 'On its way' . ($ship?->shipped_at ? ' · expected by ' . $ship->shipped_at->copy()->addDays(DeliveryEstimate::maxDays())->format('M j') : ''),
                    'delivered' => 'Delivered' . ($ship?->delivered_at ? ' ' . $ship->delivered_at->format('M j') : ''), 'refunded' => 'Refunded'][$state],
                'line' => 'Order ' . $order->tracking_number . ' · ' . ($first->seller?->store_name ?? 'Peyton Ghalib'),
                'carrier' => $ship?->tracking_number ? "{$ship->carrier} · {$ship->tracking_number}" : '',
                'steps' => collect(['Order placed', 'Preparing', 'Shipped', 'Delivered'])->map(fn ($t, $i) => ['t' => $t, 'bg' => $i <= $step && $state !== 'refunded' ? '#C2410C' : '#E5E7EB',
                    'fg' => $i <= $step && $state !== 'refunded' ? '#111827' : '#6B7280', 'fw' => $i <= $step && $state !== 'refunded' ? '700' : '500'])->all(),
                'items' => $lines->map(fn ($l) => ['href' => $l->product ? route('product-details', $l->product->slug) : '#', 'name' => $l->name, 'sub' => $l->product?->category?->name ?? '',
                    'tint' => MarketViews::tint(($l->product?->category?->parent ?? $l->product?->category)?->slug)])->values()->all(),
            ];
        }

        return $cards;
    }

    /** Delivered things the shopper has not reviewed yet. */
    private static function toReview(User $user): array
    {
        $reviewed = Review::where('user_id', $user->id)->pluck('product_id');

        return OrderItem::with('order', 'product', 'seller')->whereNotNull('product_id')->whereNotIn('product_id', $reviewed)
            ->whereHas('order', fn ($o) => $o->where('user_id', $user->id)->where('payment_status', 'paid')->where('status', 'delivered'))
            ->latest('id')->get()->unique('product_id')->filter(fn ($i) => $i->product)->take(20)
            ->map(fn ($i) => ['name' => $i->name, 'slug' => $i->product->slug, 'store' => $i->seller?->store_name ?? 'Peyton Ghalib', 'order' => $i->order->tracking_number, 'when' => 'Delivered'])->values()->all();
    }

    /** The shopper's claims (refund requests), newest first, plus a link to open one from each recent order. */
    private static function claims(User $user, array $orders): array
    {
        $claims = \App\Models\Dispute::with('order:id,tracking_number', 'seller:id,store_name')
            ->where(fn ($q) => $q->where('user_id', $user->id)->orWhere('email', $user->email))->latest('id')->limit(30)->get()
            ->map(fn ($d) => ['no' => $d->order?->tracking_number, 'store' => $d->seller?->store_name ?? 'Peyton Ghalib', 'reason' => DisputeLabels::reason($d->reason), 'amount' => MarketViews::money($d->amount),
                'when' => $d->created_at->format('M j, Y'), 'status' => ['open' => 'In review', 'refunded' => 'Refunded', 'closed_seller' => 'Closed'][$d->status] ?? ucfirst($d->status),
                'note' => $d->resolution_note, 'done' => $d->status !== 'open'])->all();

        return ['claims' => $claims, 'claimOrders' => collect($orders)->take(8)->map(fn ($o) => ['no' => $o['no'], 'store' => $o['storeName'], 'url' => $o['trackUrl'], 'status' => $o['status']])->all()];
    }

    private static function follows(User $user): array
    {
        return \App\Models\StoreFollow::with('seller')->where('user_id', $user->id)->latest('id')->get()->filter(fn ($f) => $f->seller?->isLive())->map(function ($f) {
            $s = $f->seller;

            return ['initial' => mb_strtoupper(mb_substr($s->store_name, 0, 1)), 'name' => $s->store_name, 'slug' => $s->slug, 'href' => $s->url(), 'city' => trim("{$s->city}, {$s->state}", ', '),
                'items' => Product::visible()->where('seller_id', $s->id)->latest('id')->limit(3)->get()->map(fn ($p) => ['href' => route('product-details', $p->slug), 'name' => $p->name, 'price' => MarketViews::money($p->from_price)])->all()];
        })->values()->all();
    }

    private static function rewards(User $user): array
    {
        $svc = app(\App\Services\RewardsService::class);
        $balance = $svc->balance($user);

        return ['points' => $balance, 'pointsWorth' => MarketViews::money(\App\Services\RewardsService::worth(intdiv($balance, \App\Services\RewardsService::STEP) * \App\Services\RewardsService::STEP)),
            'pointsHistory' => \App\Models\RewardEntry::where('user_id', $user->id)->latest('id')->limit(40)->get()->map(fn ($e) => ['t' => $e->reason, 'd' => $e->created_at->format('M j, Y'), 'v' => ($e->delta > 0 ? '+' : '') . number_format($e->delta)])->all()];
    }

    private static function giftCards(User $user): array
    {
        $svc = app(\App\Services\GiftCardService::class);

        return ['gcBalance' => MarketViews::money($svc->balance($user)), 'gcAmounts' => [10, 25, 50, 100],
            'gcOwned' => \App\Models\GiftCard::where('owner_id', $user->id)->where('status', 'active')->latest('id')->get(['code', 'amount', 'balance'])
                ->map(fn ($c) => ['code' => '•••• ' . substr($c->code, -4), 'amount' => MarketViews::money($c->amount), 'balance' => MarketViews::money($c->balance)])->all(),
            'gcSent' => \App\Models\GiftCard::where('buyer_id', $user->id)->where('status', 'active')->latest('id')->limit(20)->get(['code', 'amount', 'recipient_email', 'created_at'])
                ->map(fn ($c) => ['code' => $c->code, 'amount' => MarketViews::money($c->amount), 'to' => $c->recipient_email ?: 'yourself', 'when' => $c->created_at->format('M j, Y')])->all()];
    }

    private static function messages(User $user): array
    {
        $svc = app(\App\Services\MessageService::class);
        $threads = \App\Models\Conversation::with('seller:id,uuid,store_name,slug', 'lastMessage')->where('buyer_id', $user->id)->whereHas('messages')->orderByDesc('last_message_at')->limit(50)->get();
        $request = request();

        $composeTo = null;
        if ($to = $request->query('to')) {
            $s = \App\Models\Seller::live()->where('slug', (string) $to)->first();
            $existing = $s ? $threads->firstWhere('seller_id', $s->id) : null;
            if ($existing) {
                $request->query->set('c', (string) $existing->id); // already talking: continue that conversation
            } elseif ($s) {
                $composeTo = ['name' => $s->store_name, 'slug' => $s->slug];
            }
        }

        $open = $composeTo ? null : ($request->query('c') ? $threads->firstWhere('id', (int) $request->query('c')) : $threads->first());
        $thread = null;
        if ($open) {
            $svc->markRead($open, 'buyer');
            $thread = ['id' => $open->id, 'name' => $open->seller->store_name, 'href' => $open->seller->url(),
                'messages' => $open->messages()->orderBy('id')->limit(200)->get()->map(fn ($m) => ['body' => $m->body, 'mine' => $m->from === 'buyer', 'time' => $m->created_at->format('M j, g:i A')])->all()];
        }

        return ['threads' => $threads->map(fn ($t) => ['id' => $t->id, 'initial' => mb_strtoupper(mb_substr($t->seller->store_name, 0, 1)), 'name' => $t->seller->store_name,
            'time' => $t->last_message_at?->diffForHumans(short: true), 'last' => $t->lastMessage?->body, 'unread' => $open && $open->id === $t->id ? 0 : $t->unreadFor('buyer')->count()])->all(),
            'openThread' => $thread, 'composeTo' => $composeTo];
    }
}
