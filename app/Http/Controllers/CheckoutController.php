<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        if ($this->cart->count() === 0) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $shippingOptions = [
            'free'  => ['label' => 'Free Shipping',  'cost' => 0],
            'fast'  => ['label' => 'Fast Shipping',   'cost' => 10],
            'local' => ['label' => 'Local Pickup',    'cost' => 15],
        ];

        return view('checkout', [
            'cartItems'       => $this->cart->items(),
            'cartTotal'       => $this->cart->total(),
            'shippingOptions' => $shippingOptions,
            'stripeKey'       => config('services.stripe.key'),
        ]);
    }

    public function store(Request $request)
    {
        if ($this->cart->count() === 0) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:30',
            'city'           => 'nullable|string|max:100',
            'zip'            => 'nullable|string|max:20',
            'address'        => 'required|string|max:255',
            'address2'       => 'nullable|string|max:255',
            'notes'          => 'nullable|string',
            'payment_method' => 'required|in:cod,stripe',
            'shipping'       => 'required|in:free,fast,local',
            'agree'          => 'accepted',
        ]);

        $shippingCosts = ['free' => 0, 'fast' => 10, 'local' => 15];
        $shippingCost  = $shippingCosts[$data['shipping']];
        $subtotal      = $this->cart->total();
        $total         = $subtotal + $shippingCost;

        // Create the order
        $order = Order::create([
            'user_id'        => auth()->id(),
            'name'           => $data['name'],
            'email'          => $data['email'],
            'phone'          => $data['phone'],
            'city'           => $data['city'] ?? null,
            'zip'            => $data['zip'] ?? null,
            'address'        => $data['address'],
            'address2'       => $data['address2'] ?? null,
            'notes'          => $data['notes'] ?? null,
            'payment_method' => $data['payment_method'],
            'payment_status' => 'pending',
            'shipping'       => $data['shipping'],
            'subtotal'       => $subtotal,
            'shipping_cost'  => $shippingCost,
            'total'          => $total,
            'status'         => 'pending',
        ]);

        // Save order items
        foreach ($this->cart->items() as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['id'],
                'name'       => $item['name'],
                'price'      => $item['price'],
                'qty'        => $item['qty'],
                'total'      => $item['price'] * $item['qty'],
            ]);
        }

        if ($data['payment_method'] === 'cod') {
            $this->cart->clear();
            return redirect()->route('thank-you')->with('success', 'Order placed successfully! We will contact you soon.');
        }

        // Stripe payment
        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount'   => (int) round($total * 100), // cents
            'currency' => 'usd',
            'metadata' => ['order_id' => $order->id],
        ]);

        $order->update(['stripe_payment_intent' => $intent->id]);

        return view('checkout-stripe', [
            'order'        => $order,
            'clientSecret' => $intent->client_secret,
            'stripeKey'    => config('services.stripe.key'),
        ]);
    }

    public function stripeSuccess(Request $request)
    {
        $request->validate(['payment_intent' => 'required|string']);

        Stripe::setApiKey(config('services.stripe.secret'));
        $intent = PaymentIntent::retrieve($request->payment_intent);

        if ($intent->status === 'succeeded') {
            $order = Order::where('stripe_payment_intent', $intent->id)->first();
            if ($order) {
                $order->update(['payment_status' => 'paid', 'status' => 'processing']);
            }
            $this->cart->clear();
            return redirect()->route('thank-you')->with('success', 'Payment successful! Your order is confirmed.');
        }

        return redirect()->route('checkout')->with('error', 'Payment was not completed. Please try again.');
    }
}
