<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\User;
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

        $this->command?->warn("Seller logins: minimart@peytonghalib.com and e19deli@peytonghalib.com, password: {$password}");
        $this->command?->warn('EIN and owner details are placeholders. Replace them before going live.');
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
            'status' => 'approved',
            'approved_at' => now(),
            'subscription_status' => 'active',
        ]);
    }
}
