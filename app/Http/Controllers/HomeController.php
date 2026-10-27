<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

/** What is left of the original storefront controller: the post-purchase pages and the password-reset form. */
class HomeController extends Controller
{
    public function forgerPassword()
    {
        return view('auth.forgot');
    }

    public function thankYou()
    {
        $order = null;

        if ($tracking = session('recent_order')) {
            $order = Order::with('items.product', 'items.seller')
                ->where('tracking_number', $tracking)
                ->first();
        }

        // No order in session (direct visit, expired session) still renders —
        // the view falls back to a generic thank-you with a way to find the order.
        return view('thank-you', compact('order'));
    }

    /**
     * Optional account after a guest purchase. The order in the session is the
     * credential: the account takes that order's email, and only guest orders
     * (user_id null) on that email are attached to it.
     */
    public function createAccountFromOrder(Request $request)
    {
        $data  = $request->validate(['password' => ['required', 'string', 'min:8', 'confirmed']]);
        $order = Order::where('tracking_number', session('recent_order'))->whereNull('user_id')->first();

        if (! $order || \App\Models\User::where('email', $order->email)->exists()) {
            return redirect()->route('thank-you');
        }

        $user = \App\Models\User::create([
            'name'     => $order->name,
            'email'    => $order->email,
            'role'     => 'customer',
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
        ]);
        Order::whereNull('user_id')->where('email', $order->email)->update(['user_id' => $user->id]);
        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('thank-you')->with('account_created', true);
    }

    public function trackOrder(Request $request)
    {
        $order = null;
        $raw = $request->query('tracking');
        $tracking = is_string($raw) ? strtoupper(trim(mb_substr($raw, 0, 40))) : ''; // tracking[]=x is not a tracking number

        if ($tracking !== '') {
            $order = Order::with('items.product', 'items.seller')->where('tracking_number', $tracking)->first();
        }

        $shipments = $order ? \App\Models\Shipment::with('seller')->where('order_id', $order->id)->get()->keyBy('seller_id') : collect();
        $claims = $order ? \App\Models\Dispute::where('order_id', $order->id)->get()->keyBy('seller_id') : collect();

        return view('track-order', compact('order', 'tracking', 'shipments', 'claims'));
    }
}
