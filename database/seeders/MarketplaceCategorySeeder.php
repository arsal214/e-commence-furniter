<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** The marketplace category tree from the design. Adds only; never touches existing categories. */
class MarketplaceCategorySeeder extends Seeder
{
    public const TREE = [
        'Fashion' => ['Bags', 'Outerwear', 'Shoes', 'Tops', 'Accessories'],
        'Home & Kitchen' => ['Dinnerware', 'Bedding', 'Cookware', 'Décor', 'Storage'],
        'Beauty & Health' => ['Skincare', 'Hair', 'Makeup', 'Wellness'],
        'Toys & Games' => ['Learning', 'STEM', 'Puzzles', 'Outdoor play'],
        'Pet Supplies' => ['Beds', 'Toys', 'Grooming', 'Feeding'],
        'Sports & Outdoors' => ['Hydration', 'Camping', 'Fitness', 'Cycling'],
        'Automotive' => ['Interior', 'Exterior', 'Tools'],
        'Jewelry' => ['Necklaces', 'Earrings', 'Rings', 'Bracelets'],
        'Baby' => ['Nursery', 'Feeding', 'Clothing', 'Gear'],
        'Seasonal & Holiday' => ['Fall Décor', 'Halloween', 'Holiday'],
        'Handmade' => ['Ceramics', 'Woodwork', 'Textiles'],
    ];

    public function run(): void
    {
        foreach (self::TREE as $parentName => $subs) {
            $parent = Category::firstOrCreate(['slug' => Str::slug($parentName)], ['name' => $parentName, 'is_active' => true]);

            foreach ($subs as $sub) {
                // Slug is prefixed with the parent so "Toys" under Pet Supplies and Toys & Games stay distinct.
                $slug = Str::slug($parentName . ' ' . $sub);
                Category::firstOrCreate(['slug' => $slug], ['name' => $sub, 'parent_id' => $parent->id, 'is_active' => true]);
            }
        }
    }
}
