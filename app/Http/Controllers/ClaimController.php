<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\DisputeService;
use Illuminate\Http\Request;

/** A buyer opens a Buyer Protection claim from the tracking page. Order number plus email is the credential. */
class ClaimController extends Controller
{
    public function store(Request $request, string $tracking, DisputeService $disputes)
    {
        $data = $request->validate([
            'seller_id' => 'required|integer', 'email' => 'required|email|max:255',
            'reason' => 'required|in:' . implode(',', array_keys(DisputeService::REASONS)), 'details' => 'required|string|min:10|max:1500',
        ], ['details.min' => 'Tell us a little more about what went wrong (at least 10 characters).']);

        $order = Order::where('tracking_number', strtoupper(trim($tracking)))->first();
        // Same answer whether the order exists or the email is wrong, so this cannot be used to probe for orders.
        $result = $order ? $disputes->open($order, (int) $data['seller_id'], $data['email'], $data['reason'], $data['details']) : 'That email does not match this order.';

        return is_string($result)
            ? back()->withInput()->with('error', $result)
            : redirect()->route('track-order', ['tracking' => $tracking])->with('success', 'Your claim was sent. We will review it within 3 business days.');
    }
}
