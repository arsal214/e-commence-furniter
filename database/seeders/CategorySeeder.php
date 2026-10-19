<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Placeholder images are template art; replace per category from Admin > Categories.
            ['name' => 'Puzzles & Building Sets', 'description' => 'Jigsaw puzzles, blocks and construction sets that build focus and creativity.', 'image' => 'assets/img/home-v1/pdct-cgry-01.jpg'],
            ['name' => 'Plush & Dolls',           'description' => 'Soft plush toys, teddy bears and dolls for cuddles and imaginative play.',     'image' => 'assets/img/home-v1/pdct-cgry-02.jpg'],
            ['name' => 'Board & Card Games',      'description' => 'Family game-night favourites for every age and group size.',                  'image' => 'assets/img/home-v1/pdct-cgry-03.jpg'],
            ['name' => 'Outdoor Play',            'description' => 'Ride-ons, sports and outdoor toys that get kids moving.',                    'image' => 'assets/img/gallery/shop-01/shop-10.jpg'],
            ['name' => 'Baby & Toddler Toys',     'description' => 'Safe, soft and stimulating first toys for babies and toddlers.',             'image' => 'assets/img/home-v6/latest-pdct-01.jpg'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
