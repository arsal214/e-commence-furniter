<?php

namespace Database\Seeders;

use App\Models\Bundle;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * The four launch sets, seeded INACTIVE. Components are found by name in the live
 * catalogue; a set whose parts can't all be found, or whose price is not below the
 * parts' current total, is skipped (and reported) rather than created wrong.
 *
 *   php artisan db:seed --class=BundleSeeder
 */
class BundleSeeder extends Seeder
{
    /** name => [price, [name fragments that identify each component]] */
    protected array $sets = [
        'Little Builder STEM Set' => [52.99, ['10-in-1 Electric', 'GILOBABY']],
        'Cozy Night In'           => [53.99, ['Chunky Knit', '3D Moving Sand']],
        'Shelf Styling Set'       => [79.99, ['Vase', 'Bird Figurine', 'Thinker']],
        'Fall Travel Look'        => [95.99, ['Cicy Bell', 'Oversized']],
    ];

    public function run(): void
    {
        foreach ($this->sets as $name => [$price, $fragments]) {
            if (Bundle::where('name', $name)->exists()) {
                continue;
            }

            $products = [];
            foreach ($fragments as $fragment) {
                $matches = Product::where('name', 'like', "%{$fragment}%")->get();
                if ($matches->count() !== 1) {
                    $this->note("{$name}: skipped, {$matches->count()} products match \"{$fragment}\" (need exactly 1).");
                    continue 2;
                }
                $products[] = $matches->first();
            }

            $regular = round(collect($products)->sum(fn (Product $p) => $p->effective_price), 2);
            if ($price >= $regular) {
                $this->note("{$name}: skipped, set price \${$price} is not below the parts' total \${$regular}.");
                continue;
            }

            $bundle = Bundle::create(['name' => $name, 'price' => $price, 'is_active' => false]);
            foreach ($products as $product) {
                $bundle->items()->create(['product_id' => $product->id, 'qty' => 1]);
            }
        }
    }

    protected function note(string $message): void
    {
        $this->command?->warn($message);
    }
}
