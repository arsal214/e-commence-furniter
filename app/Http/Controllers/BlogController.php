<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function show($title)
    {
        $blogs = [
            [
                'id' => 1,
                'img' => 'assets/img/shortcode/blog/blog-01.jpg', 
                'title' => 'Auctor sit elementum habitant vel tempor varius.', 
                'tag' => 'Interior', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 2,
                'img' => 'assets/img/shortcode/blog/blog-02.jpg', 
                'title' => 'Consectetur purus habitasse ut diam habitant varius.', 
                'tag' => 'Chair', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 3,
                'img' => 'assets/img/shortcode/blog/blog-03.jpg', 
                'title' => 'Far far away of furniture of this habitant vel tempor.', 
                'tag' => 'Vase', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 4,
                'img' => 'assets/img/shortcode/blog/blog-14.jpg', 
                'title' => 'The Auctor sit elementum habitant vel tempor varius.', 
                'tag' => 'Interior', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 5,
                'img' => 'assets/img/shortcode/blog/blog-15.jpg', 
                'title' => 'That Auctor sit elementum habitant vel tempor varius.', 
                'tag' => 'Vase', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 6,
                'img' => 'assets/img/home-v2/blog-03.jpg', 
                'title' => 'How to Choose the Perfect Furniture for Every Room in Your Home', 
                'tag' => 'Interior', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 7,
                'img' => 'assets/img/home-v2/blog-01.jpg', 
                'title' => '10 Furniture Shopping Tips for a Seamless Online Experience', 
                'tag' => 'Bedroom', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 8,
                'img' => 'assets/img/home-v2/blog-02.jpg', 
                'title' => 'Sustainable Furniture: Eco-Friendly Choices for a Greener Home', 
                'tag' => 'Sofa', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 9,
                'img' => 'assets/img/shortcode/blog/blog-05.jpg', 
                'title' => 'Transform Your Space: Top Furniture Trends for Modern Homes', 
                'tag' => 'Interior', 
                'date' => '27 Jan, 2025', 
            ],
            [
                'id' => 10,
                'img' => 'assets/img/shortcode/blog/blog-06.jpg', 
                'title' => 'The Art of Interior Design: Choosing the Perfect Furniture Online', 
                'tag' => 'Bedroom', 
                'date' => '20 Jan, 2025', 
            ],
            [
                'id' => 11,
                'img' => 'assets/img/home-v3/blog.jpg', 
                'title' => '5 Must-Have Features for an Outstanding Furniture', 
                'tag' => 'Interior', 
                'date' => '25 Jan, 2025', 
            ],
            [
                'id' => 12,
                'img' => 'assets/img/shortcode/blog/blog-07.jpg', 
                'title' => 'Maximizing Small Spaces: Smart Furniture Solutions', 
                'desc' => 'The finest collections from top furniture brands known for quality, style, and durability. . .', 
                'tag' => 'Living room', 
                'date' => '12 Jan, 2025', 
            ],
            [
                'id' => 13,
                'img' => 'assets/img/shortcode/blog/blog-08.jpg', 
                'title' => 'The Ultimate Guide to Styling Your Living Room Furniture', 
                'desc' => 'Shop from top brands and enjoy exclusive discounts on timeless designs. Elevate your living . . .', 
                'tag' => 'Table & Chair', 
                'date' => '23 Feb, 2025', 
            ],
            [
                'id' => 14,
                'img' => 'assets/img/shortcode/blog/blog-09.jpg', 
                'title' => 'Top Furniture Trends That Will Dominate This Year', 
                'desc' => 'Explore trending designs, space-saving solutions, and smart shopping advice! . . .', 
                'tag' => 'Interior', 
                'date' => '27 Jan, 2025', 
            ],
            [
                'id' => 15,
                'img' => 'assets/img/home-v4/blog.jpg', 
                'title' => 'How to Choose Furniture That Matches Your Interior Design', 
                'desc' => 'Discover expert tips and creative ideas to select and style furniture that elevates your home. . .', 
                'tag' => 'Sofa', 
                'date' => '29 Jan, 2025', 
            ],
            [
                'id' => 16,
                'img' => 'assets/img/home-v5/blog-01.jpg', 
                'title' => 'Home Office Storage Ideas to Boost Productivity in 2025.', 
                'tag' => 'Vase', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 17,
                'img' => 'assets/img/home-v5/blog-02.jpg', 
                'title' => 'The Consectetur purus habitasse ut diam habitant varius.', 
                'tag' => 'Interior', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 18,
                'img' => 'assets/img/home-v5/blog-03.jpg', 
                'title' => 'The Key Components of a Quality Sofa habitant vel tempor.', 
                'tag' => 'Sofa', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 19,
                'img' => 'assets/img/shortcode/blog/blog-10.jpg', 
                'title' => 'The Art of Interior Design: Choosing the Furniture Online', 
                'tag' => 'Home Decor', 
                'date' => '25 Jan, 2025', 
            ],
            [
                'id' => 20,
                'img' => 'assets/img/shortcode/blog/blog-11.jpg', 
                'title' => 'Transform Your Space: Top Furniture Trends for Homes', 
                'tag' => 'Interior', 
                'date' => '20 Jan, 2025', 
            ],
            [
                'id' => 21,
                'img' => 'assets/img/home-v6/blog.jpg', 
                'title' => 'How to Choose the Perfect Furniture for Every Room in Home', 
                'tag' => 'Interior', 
                'date' => '28 Jan, 2025', 
            ],
            [
                'id' => 22,
                'img' => 'assets/img/shortcode/blog/blog-04.jpg', 
                'title' => 'Design your apps in your own way', 
                'tag' => 'Interior', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 23,
                'img' => 'assets/img/shortcode/blog/blog-12.jpg', 
                'title' => 'How apps is changing the IT world', 
                'tag' => 'Vase', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 24,
                'img' => 'assets/img/shortcode/blog/blog-13.jpg', 
                'title' => 'The Key Components of a Quality Sofa.', 
                'tag' => 'Sofa', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 25,
                'img' => 'assets/img/shortcode/blog/blog-12.jpg', 
                'title' => 'Smartest Applications for Business', 
                'tag' => 'Vase', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 26,
                'img' => 'assets/img/shortcode/blog/blog-14.jpg', 
                'title' => 'Good Ideas to Update your Living Room.', 
                'tag' => 'Interior', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 27,
                'img' => 'assets/img/shortcode/blog/blog-15.jpg', 
                'title' => 'Tips and Tricks to Avoid the Stress of Clutter.', 
                'tag' => 'Vase', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 28,
                'img' => 'assets/img/shortcode/blog/blog-16.jpg', 
                'title' => "Name Brand Children's Bedroom Furniture Built to Last.", 
                'tag' => 'Sofa', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 29,
                'img' => 'assets/img/shortcode/blog/blog-17.jpg', 
                'title' => 'Stop Worrying About Deadlines! We Got You Covered', 
                'tag' => 'Chair', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 30,
                'img' => 'assets/img/shortcode/blog/blog-18.jpg', 
                'title' => 'Change Your Strategy: Find a Business Consultant', 
                'tag' => 'Vase', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 31,
                'img' => 'assets/img/shortcode/blog/blog-19.jpg', 
                'title' => 'How to Make a Small Bedroom Look Bigger.', 
                'tag' => 'Interior', 
                'date' => '6 Sep, 2025', 
            ],
            [
                'id' => 32,
                'img' => 'assets/img/shortcode/blog/blog-20.jpg', 
                'title' => '6 Tips to Warm Up Your Gray and White Decor.', 
                'tag' => 'Vase', 
                'date' => '6 Sep, 2025', 
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
        return view('blog-details-v1', compact('item'));
    }
}