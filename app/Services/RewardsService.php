<?php

namespace App\Services;

use App\Models\Order;
use App\Models\RewardEntry;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Shopper rewards: 1 point per $1 of product spend (after discounts), posted when the parcel is delivered.
 * 100 points = $1 off at checkout, in 100-point steps. Points expire after 12 months without activity.
 */
class RewardsService
{
    public const STEP = 100;
    public const SESSION = 'use_points';
    public const EXPIRY_MONTHS = 12;

    public function balance(User $user): int
    {
        return (int) RewardEntry::where('user_id', $user->id)->sum('delta');
    }

    /** Points not already promised to an order still waiting for its payment. */
    public function available(User $user): int
    {
        $promised = (int) Order::where('user_id', $user->id)->where('payment_status', 'pending')->where('created_at', '>=', now()->subMinutes(30))->sum('points_used');

        return max(0, intdiv(max(0, $this->balance($user) - $promised), self::STEP) * self::STEP);
    }

    public static function worth(int $points): float
    {
        return round($points / self::STEP, 2);
    }

    public function wanted(): bool
    {
        return (bool) session(self::SESSION);
    }

    /**
     * What the shopper's points take off an order whose products (after coupons and offers) cost $payable.
     * Never more than the shopper has, and never more than the products cost.
     *
     * @return array{points: int, discount: float}
     */
    public function quote(?User $user, float $payable): array
    {
        if (! $user || ! $this->wanted() || $payable <= 0) {
            return ['points' => 0, 'discount' => 0.0];
        }
        $points = min($this->available($user), intdiv((int) floor($payable * 100), self::STEP) * self::STEP);

        return ['points' => $points, 'discount' => self::worth($points)];
    }

    /** Credits a delivered parcel to the shopper. Once per parcel. */
    public function earnForDelivery(Shipment $shipment): void
    {
        $order = $shipment->order;
        if (! $order?->user_id) {
            return;
        }
        $order->loadMissing('items');
        $itemsNet = (float) $order->items->sum('total') - (float) $order->items->sum('coupon_discount');
        $offPlatform = ((float) $order->discount + (float) $order->points_discount) * ($itemsNet > 0 ? (float) $shipment->gross / $itemsNet : 0);
        $points = (int) floor(max(0, (float) $shipment->gross - $offPlatform) * 1);
        if ($points <= 0) {
            return;
        }

        RewardEntry::firstOrCreate(['shipment_id' => $shipment->id, 'kind' => 'earn'],
            ['user_id' => $order->user_id, 'delta' => $points, 'order_id' => $order->id, 'reason' => 'Order ' . $order->tracking_number . ' delivered']);
    }

    /** Points the order used are taken off the balance once it is paid. */
    public function recordSpend(Order $order): void
    {
        if (! $order->user_id || $order->points_used <= 0) {
            return;
        }
        DB::transaction(function () use ($order) {
            if (! RewardEntry::where('order_id', $order->id)->where('kind', 'spend')->lockForUpdate()->exists()) {
                RewardEntry::create(['user_id' => $order->user_id, 'delta' => -$order->points_used, 'order_id' => $order->id, 'kind' => 'spend', 'reason' => 'Used on order ' . $order->tracking_number]);
            }
        });
    }

    /** A refunded parcel: the points it earned go back, and its share of the points the buyer spent is returned. */
    public function onRefund(Shipment $shipment): void
    {
        $order = $shipment->order;
        if (! $order?->user_id) {
            return;
        }
        $earned = RewardEntry::where('shipment_id', $shipment->id)->where('kind', 'earn')->first();
        if ($earned) {
            RewardEntry::firstOrCreate(['shipment_id' => $shipment->id, 'kind' => 'remove'],
                ['user_id' => $order->user_id, 'delta' => -$earned->delta, 'order_id' => $order->id, 'reason' => 'Refund on order ' . $order->tracking_number]);
        }

        $order->loadMissing('items');
        $itemsNet = (float) $order->items->sum('total') - (float) $order->items->sum('coupon_discount');
        $back = $order->points_used > 0 && $itemsNet > 0 ? (int) (floor($order->points_used * (float) $shipment->gross / $itemsNet / self::STEP) * self::STEP) : 0;
        if ($back > 0 && RewardEntry::where('order_id', $order->id)->where('kind', 'spend')->exists()) {
            RewardEntry::firstOrCreate(['shipment_id' => $shipment->id, 'kind' => 'restore'],
                ['user_id' => $order->user_id, 'delta' => $back, 'order_id' => $order->id, 'reason' => 'Points returned for order ' . $order->tracking_number]);
        }
    }

    /** Clears the balance of shoppers with no points activity for twelve months. */
    public function expireInactive(): int
    {
        $n = 0;
        $cutoff = now()->subMonths(self::EXPIRY_MONTHS);
        RewardEntry::select('user_id')->groupBy('user_id')->havingRaw('MAX(created_at) < ?', [$cutoff])->pluck('user_id')->each(function ($id) use (&$n) {
            $balance = (int) RewardEntry::where('user_id', $id)->sum('delta');
            if ($balance > 0) {
                RewardEntry::create(['user_id' => $id, 'delta' => -$balance, 'kind' => 'expire', 'reason' => 'Points expired after 12 months without activity']);
                $n++;
            }
        });

        return $n;
    }
}
