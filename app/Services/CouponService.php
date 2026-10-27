<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\SellerCoupon;
use Illuminate\Support\Facades\DB;

/**
 * Seller coupons. A code only ever discounts the items of the seller who made it, and the seller
 * pays for it: their payout is reduced, the platform's welcome offer is a separate thing.
 */
class CouponService
{
    public const SESSION = 'coupon_code';

    public static function clean(?string $code): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9_-]/', '', (string) $code));
    }

    public function code(): ?string
    {
        return session(self::SESSION) ?: null;
    }

    /** Remember a code for this cart, if some seller in the cart really offers it. */
    public function apply(string $code, array $items): string|true
    {
        $code = self::clean($code);
        if ($code === '') {
            return 'Enter a coupon code.';
        }
        $quote = $this->quote($items, $code);
        if ($quote['discount'] <= 0) {
            return $quote['reason'] ?: 'That code does not apply to the items in your cart.';
        }
        session([self::SESSION => $code]);

        return true;
    }

    public function remove(): void
    {
        session()->forget(self::SESSION);
    }

    /**
     * @param array $items cart lines
     * @return array{code: ?string, discount: float, bySeller: array<int,float>, reason: ?string}
     */
    public function quote(array $items, ?string $code = null): array
    {
        $code = self::clean($code ?? $this->code());
        $none = ['code' => null, 'discount' => 0.0, 'bySeller' => [], 'reason' => null];
        if ($code === '') {
            return $none;
        }

        $sellerOf = Product::whereIn('id', collect($items)->pluck('id')->filter())->pluck('seller_id', 'id');
        $subtotals = [];
        foreach ($items as $line) {
            $sid = empty($line['bundle_id']) ? $sellerOf[$line['id'] ?? 0] ?? null : null; // sets are not discounted
            if ($sid) {
                $subtotals[$sid] = ($subtotals[$sid] ?? 0) + (float) $line['price'] * (int) $line['qty'];
            }
        }

        $coupons = SellerCoupon::where('code', $code)->whereIn('seller_id', array_keys($subtotals))->get();
        if ($coupons->isEmpty()) {
            return $none + ['reason' => 'That code does not apply to the items in your cart.'];
        }

        $by = [];
        $reason = null;
        foreach ($coupons as $c) {
            $off = $c->discountOn($subtotals[$c->seller_id]);
            if ($off > 0) {
                $by[$c->seller_id] = $off;
            } else {
                $reason = ! $c->isUsable() ? 'That code has expired or been used up.' : 'Add $' . number_format((float) $c->min_order - $subtotals[$c->seller_id], 2) . ' more from that store to use this code.';
            }
        }

        return ['code' => $code, 'discount' => round(array_sum($by), 2), 'bySeller' => $by, 'reason' => $by ? null : $reason];
    }

    /** Counts a paid order against each coupon that discounted it, exactly once per order and seller. */
    public function recordUse(Order $order): void
    {
        if (! $order->coupon_code) {
            return;
        }
        $order->loadMissing('items');
        foreach ($order->items->where('coupon_discount', '>', 0)->pluck('seller_id')->unique() as $sellerId) {
            SellerCoupon::where('seller_id', $sellerId)->where('code', $order->coupon_code)->increment('uses');
        }
    }

    /** Splits a seller's discount over that seller's lines in proportion to what each costs; the last line takes the rounding. */
    public static function allocate(float $discount, array $lineTotals): array
    {
        $sum = array_sum($lineTotals);
        $out = [];
        $left = $discount;
        $n = count($lineTotals);
        $i = 0;
        foreach ($lineTotals as $k => $t) {
            $i++;
            $share = $i === $n ? $left : round($discount * $t / max(0.01, $sum), 2);
            $share = min($share, $left);
            $out[$k] = round($share, 2);
            $left = round($left - $share, 2);
        }

        return $out;
    }
}
