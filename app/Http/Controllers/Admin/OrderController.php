<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }

        $orders = $query->paginate(20)->withQueryString();

        $counts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
            'delivered'  => Order::where('status', 'delivered')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'            => 'sometimes|in:pending,processing,shipped,delivered,cancelled',
            'payment_status'    => 'sometimes|in:pending,paid',
            'supplier_name'     => 'nullable|string|max:100',
            'supplier_order_id' => 'nullable|string|max:200',
            'supplier_tracking' => 'nullable|string|max:200',
            'carrier'           => 'nullable|string|max:100',
        ]);

        $wasShipped = $order->status !== 'shipped';

        // Set shipped_at timestamp when status changes to shipped
        if (isset($data['status']) && $data['status'] === 'shipped' && $wasShipped) {
            $data['shipped_at'] = now();
        }

        $order->update($data);

        // Send shipped email when status becomes shipped
        if (isset($data['status']) && $data['status'] === 'shipped' && $wasShipped) {
            Mail::to($order->email)->send(new OrderShippedMail($order));
        }

        return back()->with('success', 'Order #' . $order->id . ' updated successfully.');
    }
}
