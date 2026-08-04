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
        // Sandbox orders are excluded from every figure here: a test payment is
        // marked paid like any other, so counting it would report money that was
        // never taken. They stay visible in the orders list, badged TEST.
        $totalOrders      = Order::excludingStripeTest()->count();
        $pendingOrders    = Order::excludingStripeTest()->where('status', 'pending')->count();
        $totalRevenue     = Order::excludingStripeTest()->where('payment_status', 'paid')->sum('total');
        $testOrders       = Order::stripeTest()->count();

        return view('admin.dashboard.index', compact(
            'totalProducts', 'totalCategories', 'totalUsers', 'featuredProducts',
            'totalOrders', 'pendingOrders', 'totalRevenue', 'testOrders'
        ));
    }
}
