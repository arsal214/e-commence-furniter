<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioV2Controller extends Controller
{
    public function show($title)
    {
        $portfolios = [
            [
                'id' => 1,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-01.jpg', 
                'title' => 'Sofa & Chair', 
                'tag' => 'Design', 
                'style' => 'portfolio1-item Sofa', 
            ],
            [
                'id' => 2,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-02.jpg', 
                'title' => 'Full Interior Set', 
                'tag' => 'Art', 
                'style' => 'portfolio1-item Interior', 
            ],
            [
                'id' => 3,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-03.jpg', 
                'title' => 'Classic Vase & Chair', 
                'tag' => 'Vase', 
                'style' => 'portfolio1-item Vase Sofa', 
            ],
            [
                'id' => 4,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-04.jpg', 
                'title' => 'Classic Vase', 
                'tag' => 'Art Table', 
                'style' => 'portfolio1-item Table', 
            ],
            [
                'id' => 5,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-05.jpg', 
                'title' => 'Art Design', 
                'tag' => 'Vase', 
                'style' => 'portfolio1-item Design', 
            ],
            [
                'id' => 6,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-06.jpg', 
                'title' => 'Full Interior', 
                'tag' => 'Design', 
                'style' => 'portfolio1-item Interior', 
            ],
            [
                'id' => 7,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-05.jpg', 
                'title' => 'Classic Chair', 
                'tag' => 'Art Design', 
                'style' => 'block portfolio2-item Design', 
            ],
            [
                'id' => 8,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-03.jpg', 
                'title' => 'New Tools', 
                'tag' => 'Lamp & Vase', 
                'style' => 'block portfolio2-item Vase', 
            ],
            [
                'id' => 9,
                'img' => 'assets/img/gallery/portfolio-03/portfolio-01.jpg', 
                'title' => 'Premium Sofa', 
                'tag' => 'Table', 
                'style' => 'portfolio2-item big-portfolio Interior hidden lg:block', 
            ],
            [
                'id' => 10,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-01.jpg', 
                'title' => 'Premium Lamp', 
                'tag' => 'Art Design', 
            ],
            [
                'id' => 11,
                'img' => 'assets/img/gallery/portfolio-03/portfolio-02.jpg', 
                'title' => 'Classic Wall', 
                'tag' => 'Art Design', 
            ],
            [
                'id' => 12,
                'img' => 'assets/img/gallery/portfolio-03/portfolio-03.jpg', 
                'title' => 'White Vas', 
                'tag' => 'Art Design', 
            ],
            [
                'id' => 13,
                'img' => 'assets/img/gallery/portfolio-03/portfolio-04.jpg', 
                'title' => 'Classic Stand', 
                'tag' => 'Art Design', 
            ],
            [
                'id' => 14,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-06.jpg', 
                'title' => 'Book For Read', 
                'tag' => 'Art Design', 
                'style' => 'block portfolio2-item Design', 
            ],
            [
                'id' => 15,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-02.jpg', 
                'title' => 'The Red Vases', 
                'tag' => 'Lamp & Vase', 
                'style' => 'block portfolio2-item Vase', 
            ],
            [
                'id' => 16,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-04.jpg', 
                'title' => 'Luxury Collection', 
                'tag' => 'Lamp & Vase', 
                'style' => 'block portfolio2-item Vase Design', 
            ],
            [
                'id' => 17,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-01.jpg', 
                'title' => 'Luxury Set', 
                'tag' => 'Art Design', 
            ],
            [
                'id' => 18,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-03.jpg', 
                'title' => 'Shopping Tips', 
                'tag' => 'Art Design', 
            ],
            [
                'id' => 19,
                'img' => 'assets/img/gallery/portfolio-02/portfolio-02.jpg', 
                'title' => 'Shopping Time', 
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
        return view('portfolio-details-v2', compact('item'));
    }
}