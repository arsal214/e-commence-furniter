<?php

namespace App\Services;

use App\Mail\OrderShippedMail;
use App\Models\Order;
use App\Models\PointEntry;
use App\Models\Seller;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/** The life of one seller's parcel in an order: created when paid, shipped, delivered, then earning. */
class ShipmentService
{
    /**
     * One shipment per seller in a paid order, with what the seller earns after card processing.
     * Safe to call twice: a seller's shipment for an order is created once.
     */
    public function createForOrder(Order $order): void
    {
        $order->loadMissing('items');
        $total = round((float) $order->items->sum('total') - (float) $order->items->sum('coupon_discount'), 2);
        if ($total <= 0) {
            return;
        }

        // Stripe charges one fee on the whole payment; each seller carries their share of it.
        $orderFee = round((float) $order->total * config('marketplace.fee_percent') / 100 + config('marketplace.fee_fixed'), 2);

        foreach ($order->items->whereNotNull('seller_id')->groupBy('seller_id') as $sellerId => $lines) {
            $gross = round((float) $lines->sum('total') - (float) $lines->sum('coupon_discount'), 2); // what the buyer paid for these items
            $fee = min($gross, round($orderFee * $gross / $total, 2));

            Shipment::firstOrCreate(['order_id' => $order->id, 'seller_id' => $sellerId], ['status' => 'pending', 'gross' => $gross, 'fee' => $fee, 'net' => round($gross - $fee, 2)]);
        }
    }

    public function markShipped(Shipment $shipment, string $carrier, string $tracking): bool
    {
        return DB::transaction(function () use ($shipment, $carrier, $tracking) {
            $s = Shipment::whereKey($shipment->id)->lockForUpdate()->first();
            if ($s->status !== 'pending') {
                return false;
            }
            $s->update(['status' => 'shipped', 'carrier' => $carrier, 'tracking_number' => $tracking, 'shipped_at' => now(),
                'auto_deliver_at' => now()->addDays(config('marketplace.auto_deliver_days'))]);

            $this->refreshOrder($s->order);
            $this->emailBuyer($s->fresh());

            return true;
        });
    }

    /** Idempotent: points and lifetime sales are added once, by the call that flips the status. */
    public function markDelivered(Shipment $shipment): bool
    {
        return DB::transaction(function () use ($shipment) {
            $s = Shipment::whereKey($shipment->id)->lockForUpdate()->first();
            if ($s->status !== 'shipped') {
                return false;
            }
            $seller = Seller::whereKey($s->seller_id)->lockForUpdate()->first();

            $s->update(['status' => 'delivered', 'delivered_at' => now()]);

            $sales = round((float) $seller->delivered_sales + (float) $s->gross, 2);
            $earned = (int) floor((float) $s->gross * config('marketplace.seller_points_per_dollar'));
            $seller->update(['delivered_sales' => $sales, 'level' => Seller::levelFor($sales), 'points' => $seller->points + $earned]);
            if ($earned > 0) {
                PointEntry::create(['seller_id' => $seller->id, 'delta' => $earned, 'reason' => 'Order ' . $s->order->tracking_number . ' delivered ($' . number_format((float) $s->gross, 2) . ')']);
            }

            // The hold is the level the seller has when the parcel arrives: it never gets longer afterwards.
            $s->update(['release_at' => now()->addDays($seller->fresh()->holdDays())]);

            $this->refreshOrder($s->order);
            app(RewardsService::class)->earnForDelivery($s->fresh());

            return true;
        });
    }

    /** Parcels the carrier would have delivered by now, left alone while the buyer has an open claim. */
    public function autoDeliverDue(): int
    {
        $n = 0;
        Shipment::where('status', 'shipped')->where('auto_deliver_at', '<=', now())->each(function (Shipment $s) use (&$n) {
            if (! $s->openDispute() && $this->markDelivered($s)) {
                $n++;
            }
        });

        return $n;
    }

    /** The order is shipped / delivered once every seller parcel is (house items are handled by staff). */
    protected function refreshOrder(Order $order): void
    {
        $order->refresh();
        if ($order->items()->whereNull('seller_id')->exists() || in_array($order->status, ['cancelled'], true)) {
            return;
        }
        $statuses = Shipment::where('order_id', $order->id)->where('status', '!=', 'refunded')->pluck('status');
        if ($statuses->isEmpty()) {
            return;
        }
        if ($statuses->every(fn ($s) => $s === 'delivered')) {
            $order->update(['status' => 'delivered']);
        } elseif ($statuses->every(fn ($s) => in_array($s, ['shipped', 'delivered'], true))) {
            $order->update(['status' => 'shipped', 'shipped_at' => $order->shipped_at ?? now()]);
        }
    }

    protected function emailBuyer(Shipment $s): void
    {
        $order = $s->order;
        $order->forceFill(['carrier' => $s->carrier, 'supplier_tracking' => $s->tracking_number])->save();
        try {
            Mail::to($order->email)->send(new OrderShippedMail($order));
        } catch (\Throwable $e) {
            Log::warning("Shipped email for order {$order->id} failed: " . $e->getMessage());
        }
    }
}
