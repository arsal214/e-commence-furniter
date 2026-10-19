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
                'description' => 'Discover the latest must-have toys! Fresh puzzles, plush and games for every age, just landed.',
                'button_text' => 'Shop Now',
                'button_url'  => '/shop',
                'year_text'   => '2026',
                'badge_price' => '$140',
                'badge_label' => 'Rainbow Building Blocks',
                'badge_color' => '#BB976D',
                'is_active'   => true,
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Toys They Will Treasure for Years',
                'description' => 'Hand-picked, well-made toys that spark imagination and make playtime brilliant.',
                'button_text' => 'Explore Now',
                'button_url'  => '/shop',
                'year_text'   => '2026',
                'badge_price' => '$220',
                'badge_label' => 'Wooden Train Set',
                'badge_color' => '#BB976D',
                'is_active'   => true,
                'sort_order'  => 2,
            ],
            [
                'title'       => 'Game Night Starts Here',
                'description' => 'Board games, card games and puzzles the whole family can enjoy together.',
                'button_text' => 'View Collection',
                'button_url'  => '/shop',
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
