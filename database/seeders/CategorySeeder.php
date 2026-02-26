<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Sofa & Chair',   'description' => 'Comfortable sofas and chairs for your living space.'],
            ['name' => 'Full Interior',  'description' => 'Complete interior furniture sets and collections.'],
            ['name' => 'Lamp & Vase',    'description' => 'Decorative lamps and vases for home decor.'],
            ['name' => 'Table',          'description' => 'Dining, coffee, and side tables for every room.'],
            ['name' => 'Wood Design',    'description' => 'Premium wood furniture crafted with care.'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
