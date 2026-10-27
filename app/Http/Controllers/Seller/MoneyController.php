<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\Shipment;
use App\Services\PointsService;
use App\Services\SellerBilling;
use App\Services\ShipmentService;
use App\Support\SellerViews;
use Illuminate\Http\Request;

/** Seller Center: orders to ship, payouts, levels and points, billing. */
class MoneyController extends Controller
{
    public function orders(Request $request)
    {
        $seller = $request->user()->seller;

        return view('seller.center.page', ['seller' => $seller, 'page' => 'orders'] + SellerViews::orders($seller, $request->query('tab')));
    }

    public function ship(Request $request, int $shipment, ShipmentService $shipments)
    {
        $seller = $request->user()->seller;
        $s = Shipment::where('seller_id', $seller->id)->whereHas('order', fn ($o) => $o->where('payment_status', 'paid'))->findOrFail($shipment);
        $data = $request->validate(['carrier' => 'required|in:USPS,UPS,FedEx,DHL,Other', 'tracking' => ['required', 'string', 'min:6', 'max:60', 'regex:/^[A-Za-z0-9 \-]+$/']],
            ['tracking.regex' => 'A tracking number has only letters, numbers, spaces and dashes.', 'tracking.min' => 'That tracking number looks too short.']);

        return $shipments->markShipped($s, $data['carrier'], trim($data['tracking']))
            ? redirect()->route('seller.orders', ['tab' => 'shipped'])->with('status', 'Marked as shipped. The buyer has the tracking number.')
            : back()->with('error', 'This order was already marked as shipped.');
    }

    public function payouts(Request $request)
    {
        $seller = $request->user()->seller;

        return view('seller.center.page', ['seller' => $seller, 'page' => 'payouts'] + SellerViews::payouts($seller));
    }

    public function payoutsCsv(Request $request)
    {
        $rows = Payout::where('seller_id', $request->user()->seller->id)->latest('id')->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Amount (USD)', 'Status', 'Transfer reference']);
            foreach ($rows as $p) {
                fputcsv($out, [$p->created_at->toDateString(), $p->amount, $p->status, $p->stripe_transfer_id]);
            }
            fclose($out);
        }, 'payouts.csv', ['Content-Type' => 'text/csv']);
    }

    public function bank(Request $request, SellerBilling $billing)
    {
        $seller = $request->user()->seller;
        if (! $seller->stripe_account_id) {
            return redirect()->route('seller.bank')->with('error', 'Connect your bank first.');
        }

        try {
            return redirect()->away($billing->bankLoginUrl($seller));
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'We could not open your payout account just now. Please try again in a moment.');
        }
    }

    public function levels(Request $request)
    {
        $seller = $request->user()->seller;

        return view('seller.center.page', ['seller' => $seller, 'page' => 'levels'] + SellerViews::levels($seller));
    }

    public function redeem(Request $request, PointsService $points)
    {
        $result = $points->redeem($request->user()->seller);

        return is_int($result)
            ? back()->with('status', "\${$result} will come off your next monthly bill.")
            : back()->with('error', $result);
    }

    public function billing(Request $request)
    {
        $seller = $request->user()->seller;

        return view('seller.center.page', ['seller' => $seller, 'page' => 'billing'] + SellerViews::billing($seller));
    }

    public function portal(Request $request, SellerBilling $billing)
    {
        $seller = $request->user()->seller;
        if (! $seller->stripe_customer_id) {
            return back()->with('error', 'You have not subscribed yet.');
        }

        try {
            return redirect()->away($billing->portalUrl($seller));
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'We could not open the billing page just now. Please try again in a moment.');
        }
    }
}
