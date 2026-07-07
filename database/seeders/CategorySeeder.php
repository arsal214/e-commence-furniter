<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Sofa & Chair',   'description' => 'Comfortable sofas and chairs for your living space.',        'image' => 'assets/img/home-v1/pdct-cgry-01.jpg'],
            ['name' => 'Full Interior',  'description' => 'Complete interior furniture sets and collections.',            'image' => 'assets/img/home-v1/pdct-cgry-03.jpg'],
            ['name' => 'Lamp & Vase',    'description' => 'Decorative lamps and vases for home decor.',                  'image' => 'assets/img/home-v1/pdct-cgry-02.jpg'],
            ['name' => 'Table',          'description' => 'Dining, coffee, and side tables for every room.',              'image' => 'assets/img/gallery/shop-01/shop-10.jpg'],
            ['name' => 'Wood Design',    'description' => 'Premium wood furniture crafted with care.',                    'image' => 'assets/img/home-v6/latest-pdct-01.jpg'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
