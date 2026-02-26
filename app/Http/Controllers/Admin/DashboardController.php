<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts    = Product::count();
        $totalCategories  = Category::count();
        $totalUsers       = User::where('role', 'customer')->count();
        $featuredProducts = Product::where('is_featured', true)->count();
        $totalOrders      = Order::count();
        $pendingOrders    = Order::where('status', 'pending')->count();
        $totalRevenue     = Order::where('payment_status', 'paid')->sum('total');

        return view('admin.dashboard.index', compact(
            'totalProducts', 'totalCategories', 'totalUsers', 'featuredProducts',
            'totalOrders', 'pendingOrders', 'totalRevenue'
        ));
    }
}
