<?php

namespace App\Http\Controllers;

use App\Services\MarketCatalog;
use App\Support\Docs;
use App\Support\SiteCopy;

/** Company, help and policy pages, plus the help center and the HTML sitemap. */
class PageController extends Controller
{
    private const FAQS = [
        ['Where is my order?', 'Go to Account › Orders to see live tracking for every package. Items from different sellers ship separately, so you may get more than one tracking number.'],
        ['How do returns work?', 'Each seller sets a return window of 14, 30 or 60 days, shown on every product page. Start a return from Account › Orders; damaged or wrong items are always covered at the seller\'s cost.'],
        ['What is Buyer Protection?', 'If your order doesn\'t arrive or isn\'t as described, and the seller can\'t fix it within 48 hours, open a claim and we\'ll refund you in full — including shipping.'],
        ['When will I get my refund?', 'Sellers refund within 5 business days of receiving your return. Your bank may take 3–5 more days to show it.'],
        ['Do you ship outside the US?', 'Not yet — Peyton Ghalib ships to all 50 states and Washington, D.C.'],
        ['How do I contact a seller?', 'Use the seller\'s store page or your order page. Sellers\' typical response times are shown on their store.'],
    ];

    public function doc(string $slug)
    {
        $doc = Docs::get($slug) ?? abort(404);

        // Pages whose search title and description an admin can edit under Site text.
        $key = request()->is('refund-policy') ? 'refund-policy' : ['about' => 'about', 'contact' => 'contact', 'shipping' => 'shipping-policy', 'returns' => 'return-policy'][$slug] ?? null;

        return view('market.page', [
            'doc' => $doc + ['updated' => 'October 26, 2026'], 'docNav' => Docs::nav($slug),
            'title' => $key ? SiteCopy::title($key) : $doc['title'] . ' | Peyton Ghalib', 'description' => $key ? SiteCopy::description($key) : $doc['intro'],
        ]);
    }

    public function help()
    {
        return view('market.help', [
            'helpTopics' => [['t' => 'Orders & tracking', 'href' => url('/my-account/orders')], ['t' => 'Returns & refunds', 'href' => url('/return-policy')], ['t' => 'Shipping', 'href' => url('/shipping-policy')],
                ['t' => 'Buyer Protection', 'href' => url('/buyer-protection')], ['t' => 'Account & privacy', 'href' => url('/privacy-policy')]],
            'faqs' => array_map(fn ($f) => ['q' => $f[0], 'a' => $f[1], 'open' => false, 'sign' => '+'], self::FAQS),
            'title' => SiteCopy::title('faq'), 'description' => SiteCopy::description('faq'),
        ]);
    }

    public function sitemap(MarketCatalog $catalog)
    {
        return view('market.sitemap', [
            'mapCats' => $catalog->tree()->map(fn ($c) => ['name' => $c->name, 'href' => route('category.landing', $c->slug),
                'subs' => $c->children->map(fn ($s) => ['name' => $s->name, 'href' => route('category.landing', $s->slug)])->all()])->all(),
            'mapPages' => collect(Docs::all())->map(fn ($d, $slug) => ['t' => $d['title'], 'href' => url(Docs::PATHS[$slug])])->values()->all(),
            'title' => 'All categories & pages | Peyton Ghalib', 'description' => 'Every category and help page on Peyton Ghalib.',
        ]);
    }
}
