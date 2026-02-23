<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function show($title)
    {
        $portfolios = [
            [
                'id' => 1,
                'img' => 'assets/img/gallery/portfolio-01/portfolio-01.jpg', 
                'title' => 'Classic Vase & Chair', 
                'tag' => 'Classic Chair', 
            ],
            [
                'id' => 2,
                'img' => 'assets/img/gallery/portfolio-01/portfolio-02.jpg', 
                'title' => 'Classic Vase', 
                'tag' => 'Art Design', 
            ],
            [
                'id' => 3,
                'img' => 'assets/img/gallery/portfolio-01/portfolio-03.jpg', 
                'title' => 'Classic Chair', 
                'tag' => 'Design', 
            ],
            [
                'id' => 4,
                'img' => 'assets/img/gallery/portfolio-01/portfolio-02.jpg', 
                'title' => 'New Tools', 
                'tag' => 'Vase', 
            ],
            [
                'id' => 5,
                'img' => 'assets/img/gallery/portfolio-01/portfolio-03.jpg', 
                'title' => 'Premium Sofa', 
                'tag' => 'Art', 
            ],
            [
                'id' => 6,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-01.jpg', 
                'title' => 'Premium Lamp', 
                'tag' => 'Art Design', 
            ],
            [
                'id' => 7,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-03.jpg', 
                'title' => 'Classic Wall', 
                'tag' => 'Art Design', 
            ],
            [
                'id' => 8,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-02.jpg', 
                'title' => 'White Vas', 
                'tag' => 'Art Design', 
            ]
        ];

        // Find the portfolio by ID
        $item = collect($portfolios)->first(function ($portfolio) use ($title) {
            return Str::slug($portfolio['title']) === $title;
        });

        // If portfolio not found, return 404 error
        if (!$item) {
            abort(404);
        }

        // Return the view and pass the portfolio data to the view
        return view('portfolio-details-v1', compact('item'));
    }
}