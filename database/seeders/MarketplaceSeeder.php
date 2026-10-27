<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payout;
use App\Models\Seller;
use App\Models\Shipment;
use App\Models\User;
use App\Services\ShipmentService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Moves the existing single-store catalogue into the marketplace without deleting anything:
 *   MiniMart  -> every active product, and every existing order line
 *   E19 Deli  -> every inactive product
 * Both stores are approved, subscribed and email-verified. Safe to run again: it only
 * assigns rows that have no seller yet.
 *
 *   php artisan db:seed --class=MarketplaceSeeder
 *
 * Passwords come from SEED_SELLER_PASSWORD, else a random one is generated and printed once.
 */
class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('SEED_SELLER_PASSWORD') ?: Str::password(14, symbols: false);

        $miniMart = $this->seller('MiniMart', 'minimart@peytonghalib.com', $password);
        $deli = $this->seller('E19 Deli', 'e19deli@peytonghalib.com', $password);

        DB::transaction(function () use ($miniMart, $deli) {
            $active = DB::table('products')->whereNull('seller_id')->where('is_active', true)->update(['seller_id' => $miniMart->id]);
            $inactive = DB::table('products')->whereNull('seller_id')->where('is_active', false)->update(['seller_id' => $deli->id]);
            $lines = DB::table('order_items')->whereNull('seller_id')->update(['seller_id' => $miniMart->id]);

            $this->command?->info("MiniMart: {$active} products. E19 Deli: {$inactive} products. Order lines moved to MiniMart: {$lines}.");
        });

        $this->command?->info('Shipments created for existing orders: ' . $this->backfillShipments($miniMart));

        $this->command?->warn("Seller logins: minimart@peytonghalib.com and e19deli@peytonghalib.com, password: {$password}");
        $this->command?->warn('EIN and owner details are placeholders. Replace them before going live.');
    }

    /**
     * Gives every paid order a MiniMart shipment so Seller Center shows the history. These sales were
     * already paid to the owner before the marketplace existed, so release_at stays empty: the payout
     * run never picks them up and no money is sent twice. Safe to run again.
     */
    private function backfillShipments(Seller $seller): int
    {
        $made = 0;
        $service = app(ShipmentService::class);

        Order::where('payment_status', 'paid')->whereHas('items', fn ($q) => $q->where('seller_id', $seller->id))->each(function (Order $order) use ($service, $seller, &$made) {
            if (Shipment::where('order_id', $order->id)->where('seller_id', $seller->id)->exists()) {
                return;
            }
            $service->createForOrder($order);
            $shipment = Shipment::where('order_id', $order->id)->where('seller_id', $seller->id)->first();
            if (! $shipment) {
                return;
            }
            $made++;
            $at = $order->updated_at ?? $order->created_at;
            match ($order->status) {
                'delivered' => $shipment->update(['status' => 'delivered', 'shipped_at' => $at, 'delivered_at' => $at]),
                'shipped' => $shipment->update(['status' => 'shipped', 'shipped_at' => $at]),
                'cancelled' => $shipment->update(['status' => 'refunded']),
                default => null, // processing stays pending
            };
        });

        $this->settleHistory($seller);

        // Lifetime sales (and so the level) count what was delivered; no points are given for old orders.
        $sales = round((float) Shipment::where('seller_id', $seller->id)->where('status', 'delivered')->sum('gross'), 2);
        $seller->update(['delivered_sales' => $sales, 'level' => Seller::levelFor($sales)]);

        return $made;
    }

    /**
     * Delivered history was paid to the owner before the marketplace, so it goes into one "paid" payout
     * marked pre-marketplace. Payouts and the payout page rely on every delivered shipment having a
     * release date and being either waiting or paid out.
     */
    private function settleHistory(Seller $seller): void
    {
        $old = Shipment::where('seller_id', $seller->id)->where('status', 'delivered')->whereNull('payout_id')->whereNull('release_at')->get();
        if ($old->isEmpty()) {
            return;
        }

        $old->each(fn (Shipment $s) => $s->update(['release_at' => $s->delivered_at ?? $s->updated_at]));
        $payout = Payout::forceCreate(['seller_id' => $seller->id, 'amount' => round((float) $old->sum('net'), 2), 'status' => 'paid', 'release_at' => $old->max('release_at'),
            'paid_at' => $old->max('release_at'), 'stripe_transfer_id' => 'pre-marketplace']);
        Shipment::whereIn('id', $old->pluck('id'))->update(['payout_id' => $payout->id]);
    }

    private function seller(string $name, string $email, string $password): Seller
    {
        $user = User::firstOrNew(['email' => $email]);
        if (! $user->exists) {
            $user->forceFill(['name' => $name, 'password' => $password, 'role' => 'seller']);
        }
        $user->forceFill(['email_verified_at' => $user->email_verified_at ?? now()])->save();

        return Seller::updateOrCreate(['user_id' => $user->id], [
            'store_name' => $name,
            'slug' => Str::slug($name),
            'business_name' => $name . ' LLC',
            'business_type' => 'LLC',
            'ein' => '00-0000000',
            'state' => 'NJ',
            'address' => '200 Orient Ave STE 2B',
            'city' => 'Jersey City',
            'zip' => '07305',
            'owner_name' => $name . ' Owner',
            'owner_phone' => '+19294699864',
            'onboarding_step' => 'done',
            'submitted_at' => now(),
            'status' => 'approved',
            'approved_at' => now(),
            'subscription_status' => 'active',
        ]);
    }
}
