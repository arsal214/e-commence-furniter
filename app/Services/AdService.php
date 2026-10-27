<?php

namespace App\Services;

use App\Models\AdClick;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Promoted listings: a seller's live products can be shown first in search and category results.
 * The seller pays a fixed amount per real click, never more per day than their budget. Clicks are
 * added to the seller's next plan invoice.
 */
class AdService
{
    public const SLOTS = 2;

    public function __construct(protected SellerBilling $billing) {}

    public static function cpc(): float
    {
        return (float) config('marketplace.ad_cost_per_click');
    }

    /** Sellers whose ads may be shown right now: switched on, store live, and today's budget not used up. */
    public function activeSellerIds(): array
    {
        $sellers = Seller::live()->where('ads_active', true)->get(['id', 'ads_daily_budget']);
        if ($sellers->isEmpty()) {
            return [];
        }
        $spent = AdClick::whereIn('seller_id', $sellers->pluck('id'))->where('created_at', '>=', now()->startOfDay())->selectRaw('seller_id, SUM(cost) as spent')->groupBy('seller_id')->pluck('spent', 'seller_id');

        return $sellers->filter(fn ($s) => (float) ($spent[$s->id] ?? 0) + self::cpc() <= (float) $s->ads_daily_budget + 0.0001)->pluck('id')->all();
    }

    /** Up to two promoted products that match this listing, most recently active stores rotated by chance. */
    public function pick(Builder $matching): \Illuminate\Support\Collection
    {
        $ids = $this->activeSellerIds();
        if (! $ids) {
            return collect();
        }

        return (clone $matching)->whereIn('products.seller_id', $ids)->where('products.stock', '>', 0)
            ->with('seller:id,uuid,store_name,slug,level', 'variants', 'category.parent')->withAvg('reviews', 'rating')->withCount('reviews')
            ->inRandomOrder()->limit(self::SLOTS)->get();
    }

    /**
     * Records a click on a promoted listing and bills it, once per visitor and product every six hours.
     * Sellers clicking their own ads, visitors without a browser identity, and clicks past the daily budget cost nothing.
     */
    public function click(Product $product, Request $request): bool
    {
        $seller = $product->seller;
        if (! $seller || ! $seller->ads_active || ! in_array($seller->id, $this->activeSellerIds(), true)) {
            return false;
        }
        $ua = strtolower((string) $request->userAgent());
        if ($ua === '' || preg_match('/bot|crawl|spider|slurp|preview|facebookexternalhit/', $ua)) {
            return false;
        }
        if ($request->user()?->id === $seller->user_id) {
            return false;
        }

        // Add() is atomic, so two taps in a row cannot both pass.
        $who = $request->user()?->id ?: sha1($request->ip() . $ua);
        if (! Cache::add("ad-click:{$who}:{$product->id}", 1, now()->addHours(6))) {
            return false;
        }

        AdClick::create(['seller_id' => $seller->id, 'product_id' => $product->id, 'cost' => self::cpc()]);

        return true;
    }

    public function spentToday(Seller $seller): float
    {
        return (float) AdClick::where('seller_id', $seller->id)->where('created_at', '>=', now()->startOfDay())->sum('cost');
    }

    /** Puts every seller's unbilled click costs on their next plan invoice. Anything that fails stays unbilled for the next run. */
    public function billAll(): int
    {
        $billed = 0;
        foreach (AdClick::whereNull('billed_at')->where('created_at', '<', now()->startOfDay())->distinct()->pluck('seller_id') as $sellerId) {
            $seller = Seller::find($sellerId);
            $clicks = AdClick::where('seller_id', $sellerId)->whereNull('billed_at')->where('created_at', '<', now()->startOfDay());
            $cost = round((float) (clone $clicks)->sum('cost'), 2);
            $ids = (clone $clicks)->pluck('id');
            if (! $seller || ! $seller->stripe_customer_id || $cost <= 0) {
                continue;
            }
            try {
                $this->billing->addInvoiceItem($seller, $cost, 'Promoted listings: ' . $ids->count() . ' ' . str('click')->plural($ids->count()));
                AdClick::whereIn('id', $ids)->update(['billed_at' => now()]);
                $billed++;
            } catch (\Throwable $e) {
                Log::warning("Ad billing failed for seller {$sellerId}: " . $e->getMessage());
            }
        }

        return $billed;
    }
}
