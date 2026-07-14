<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdatedMail;
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

        $previousStatus = $order->status;
        $newStatus      = $data['status'] ?? $previousStatus;
        $statusChanged  = $newStatus !== $previousStatus;

        // Stamp the ship date on the first transition into 'shipped'.
        if ($statusChanged && $newStatus === 'shipped') {
            $data['shipped_at'] = now();
        }

        $order->update($data);

        // Notify the customer on any status change they'd care about. Carrier and
        // tracking fields are saved above, so the shipped email can include them.
        $notified = false;

        if ($statusChanged && OrderStatusUpdatedMail::shouldNotify($newStatus)) {
            try {
                Mail::to($order->email)->send(new OrderStatusUpdatedMail($order));
                $notified = true;
            } catch (\Exception $e) {
                \Log::warning("Order status email ({$newStatus}) failed for order #{$order->id}: " . $e->getMessage());
            }
        }

        $message = 'Order #' . $order->id . ' updated successfully.';

        if ($statusChanged) {
            $message .= $notified
                ? ' Customer notified by email.'
                : (OrderStatusUpdatedMail::shouldNotify($newStatus)
                    ? ' Customer email could not be sent — check the logs.'
                    : '');
        }

        return back()->with('success', $message);
    }
}
