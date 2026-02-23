<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogV2Controller extends Controller
{
    public function show($title)
    {
        $blogs = [
            [
                'id' => 1,
                'img' => 'assets/img/shortcode/blog/blog-05.jpg', 
                'title' => 'The Key Components of a Quality Sofa habitant vel tempor varius.', 
                'tag' => 'Sofa', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 2,
                'img' => 'assets/img/shortcode/blog/blog-06.jpg', 
                'title' => 'Elevate Your Space: 10 Stunning Room Decor Ideas from Furnixar', 
                'tag' => 'Interior', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 3,
                'img' => 'assets/img/shortcode/blog/blog-21.jpg', 
                'title' => 'Transform Your Home: Room Decor Tips and Trends with Furnixar', 
                'tag' => 'Vase', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 4,
                'img' => 'assets/img/shortcode/blog/blog-27.jpg', 
                'title' => 'Creating Your Dream Sanctuary: Inspirational Room Decor with Furnixar', 
                'tag' => 'Chair', 
                'date' => '6 Sep, 2025', 
                'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
            ],
            [
                'id' => 5,
                'img' => 'assets/img/shortcode/blog/blog-28.jpg', 
                'title' => 'From Drab to Fab: Room Makeover Inspiration by Furnixar', 
                'tag' => 'Sofa', 
                'date' => '6 Sep, 2025', 
                'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
            ],
            [
                'id' => 6,
                'img' => 'assets/img/shortcode/blog/blog-29.jpg', 
                'title' => 'Small Space, Big Style: Room Decor Solutions from Furnixar', 
                'tag' => 'Vases', 
                'date' => '6 Sep, 2025', 
                'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
            ],
            [
                'id' => 7,
                'img' => 'assets/img/shortcode/blog/blog-22.jpg', 
                'title' => 'Innovative Room Decor: Unleashing Creativity with Furnixar', 
                'tag' => 'Interior', 
                'date' => '6 Sep, 2025', 
                'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
            ],
            [
                'id' => 8,
                'img' => 'assets/img/shortcode/blog/blog-23.jpg', 
                'title' => 'Timeless Elegance: Classic Room Decor Ideas from Furnixar', 
                'tag' => 'Chair', 
                'date' => '6 Sep, 2025', 
                'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
            ],
            [
                'id' => 9,
                'img' => 'assets/img/shortcode/blog/blog-24.jpg', 
                'title' => 'Budget-Friendly Brilliance: Room Decor Hacks by Furnixar', 
                'tag' => 'Sofa', 
                'date' => '6 Sep, 2025', 
                'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
            ],
            [
                'id' => 10,
                'img' => 'assets/img/shortcode/blog/blog-25.jpg', 
                'title' => 'Personalize Your Space: Custom Room Decor Options with Furnixar', 
                'tag' => 'Vases', 
                'date' => '6 Sep, 2025', 
                'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
            ],
            [
                'id' => 11,
                'img' => 'assets/img/shortcode/blog/blog-26.jpg', 
                'title' => 'ransform Your Home: Room Decor Tips and Trends with Furnixar', 
                'tag' => 'Lamp', 
                'date' => '6 Sep, 2025', 
                'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
            ]
        ];

        // Find the blog by ID
        $item = collect($blogs)->first(function ($blog) use ($title) {
            return Str::slug($blog['title']) === $title;
        });

        // If blog not found, return 404 error
        if (!$item) {
            abort(404);
        }

        // Return the view and pass the blog data to the view
        return view('blog-details-v2', compact('item'));
    }
}