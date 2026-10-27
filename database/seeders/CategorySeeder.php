<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // No images here: upload one per category from Admin > Categories.
            ['name' => 'Puzzles & Building Sets', 'description' => 'Jigsaw puzzles, blocks and construction sets that build focus and creativity.'],
            ['name' => 'Plush & Dolls',           'description' => 'Soft plush toys, teddy bears and dolls for cuddles and imaginative play.'],
            ['name' => 'Board & Card Games',      'description' => 'Family game-night favourites for every age and group size.'],
            ['name' => 'Outdoor Play',            'description' => 'Ride-ons, sports and outdoor toys that get kids moving.'],
            ['name' => 'Baby & Toddler Toys',     'description' => 'Safe, soft and stimulating first toys for babies and toddlers.'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
