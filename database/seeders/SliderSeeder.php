<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            [
                'title'       => 'Brand-New Arrival Alert Your Next Favorite is Here!',
                'description' => 'Discover the latest must-have arrivals! Elevate your style with our newest collection of trendsetting furniture.',
                'button_text' => 'Shop Now',
                'button_url'  => '/shop-v1',
                'year_text'   => '2026',
                'badge_price' => '$140',
                'badge_label' => 'Aurora Flexible Sofa',
                'badge_color' => '#BB976D',
                'is_active'   => true,
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Timeless Designs for Every Living Space',
                'description' => 'Transform your home with our curated collection of premium furniture crafted for comfort and style.',
                'button_text' => 'Explore Now',
                'button_url'  => '/shop-v1',
                'year_text'   => '2026',
                'badge_price' => '$220',
                'badge_label' => 'Luxe Dining Set',
                'badge_color' => '#BB976D',
                'is_active'   => true,
                'sort_order'  => 2,
            ],
            [
                'title'       => 'Modern Comfort Meets Classic Elegance',
                'description' => 'Shop our premium bedroom and living room collections and bring warmth to every corner of your home.',
                'button_text' => 'View Collection',
                'button_url'  => '/shop-v1',
                'year_text'   => '2026',
                'badge_price' => null,
                'badge_label' => null,
                'badge_color' => '#BB976D',
                'is_active'   => true,
                'sort_order'  => 3,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::firstOrCreate(['title' => $slider['title']], $slider);
        }
    }
}
