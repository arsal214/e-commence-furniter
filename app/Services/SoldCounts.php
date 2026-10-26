<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * "N sold" for product cards. Real units on PAID orders in the last 30 days, never an
 * estimate, and hidden below the minimum so a small number never reads as a weak signal.
 * Off unless checkout.sold_badge_enabled.
 */
class SoldCounts
{
    private static ?array $memo = null;

    /** @return array<int,int> product_id => units sold in the last 30 days (cached 10 minutes) */
    public static function all(): array
    {
        return self::$memo ??= Cache::remember('sold_counts_30d', 600, fn () => DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.created_at', '>=', now()->subDays(30))
            ->groupBy('order_items.product_id')
            ->selectRaw('order_items.product_id as pid, SUM(order_items.qty) as units')
            ->pluck('units', 'pid')
            ->map(fn ($n) => (int) $n)
            ->all());
    }

    public static function label(int $productId): ?string
    {
        if (! config('checkout.sold_badge_enabled')) {
            return null;
        }

        $units = self::all()[$productId] ?? 0;

        return $units >= (int) config('checkout.sold_badge_min', 10) ? number_format($units) . ' sold in the last 30 days' : null;
    }

    /** Test helper: drop the per-request copy. */
    public static function flush(): void
    {
        self::$memo = null;
        Cache::forget('sold_counts_30d');
    }
}
