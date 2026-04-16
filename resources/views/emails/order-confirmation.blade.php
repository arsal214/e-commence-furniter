<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed</title>
    <style>
        body { margin: 0; padding: 0; background: #f5f5f5; font-family: 'Helvetica Neue', Arial, sans-serif; color: #333; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .header { background: #1a1a1a; padding: 32px 40px; text-align: center; }
        .header h1 { margin: 0; color: #bb976d; font-size: 24px; letter-spacing: 2px; font-weight: 600; }
        .header p { margin: 6px 0 0; color: #888; font-size: 12px; letter-spacing: 1px; text-transform: uppercase; }
        .body { padding: 40px; }
        .greeting { font-size: 16px; color: #444; margin-bottom: 24px; }
        .tracking-box { background: #fdf8f3; border: 2px solid #bb976d; border-radius: 8px; padding: 24px; text-align: center; margin: 24px 0; }
        .tracking-box .label { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-bottom: 8px; }
        .tracking-box .number { font-size: 28px; font-weight: 700; color: #bb976d; letter-spacing: 3px; }
        .track-btn { display: inline-block; margin-top: 16px; padding: 12px 32px; background: #bb976d; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600; }
        .section-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #bb976d; margin: 32px 0 12px; border-bottom: 1px solid #f0e8de; padding-bottom: 8px; }
        .item-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #f5f5f5; font-size: 14px; }
        .item-row:last-child { border-bottom: none; }
        .item-name { color: #333; font-weight: 500; }
        .item-qty { color: #888; font-size: 12px; margin-top: 2px; }
        .item-price { font-weight: 600; color: #333; }
        .totals { margin-top: 16px; border-top: 2px solid #f0e8de; padding-top: 12px; }
        .total-row { display: flex; justify-content: space-between; font-size: 14px; padding: 4px 0; color: #555; }
        .total-row.grand { font-size: 16px; font-weight: 700; color: #1a1a1a; padding-top: 8px; border-top: 1px solid #e5e5e5; margin-top: 4px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 13px; }
        .info-item .info-label { color: #888; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
        .info-item .info-value { color: #333; font-weight: 500; }
        .status-steps { display: flex; justify-content: space-between; margin: 24px 0; position: relative; }
        .status-steps::before { content: ''; position: absolute; top: 14px; left: 10%; right: 10%; height: 2px; background: #e5e5e5; z-index: 0; }
        .step { text-align: center; flex: 1; position: relative; z-index: 1; }
        .step-dot { width: 28px; height: 28px; border-radius: 50%; background: #e5e5e5; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-size: 12px; }
        .step-dot.active { background: #bb976d; color: #fff; }
        .step-label { font-size: 11px; color: #888; }
        .step-label.active { color: #bb976d; font-weight: 600; }
        .footer { background: #f9f9f9; padding: 24px 40px; text-align: center; font-size: 12px; color: #aaa; border-top: 1px solid #eee; }
        .footer a { color: #bb976d; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>PeytonGhalib</h1>
        <p>Premium Furniture Store</p>
    </div>
    <div class="body">
        <p class="greeting">Hi {{ $order->name }},<br>Thank you for your order! We've received it and will begin processing soon.</p>

        <div class="tracking-box">
            <div class="label">Your Order Tracking Number</div>
            <div class="number">{{ $order->tracking_number }}</div>
            <a href="{{ url('/track-order?tracking=' . $order->tracking_number) }}" class="track-btn">Track My Order</a>
        </div>

        {{-- Status Steps --}}
        <div class="status-steps">
            <div class="step">
                <div class="step-dot active">&#10003;</div>
                <div class="step-label active">Order Placed</div>
            </div>
            <div class="step">
                <div class="step-dot">2</div>
                <div class="step-label">Processing</div>
            </div>
            <div class="step">
                <div class="step-dot">3</div>
                <div class="step-label">Shipped</div>
            </div>
            <div class="step">
                <div class="step-dot">4</div>
                <div class="step-label">Delivered</div>
            </div>
        </div>

        <div class="section-title">Order Summary</div>
        @foreach ($order->items as $item)
        <div class="item-row">
            <div>
                <div class="item-name">{{ $item->name }}</div>
                <div class="item-qty">Qty: {{ $item->qty }}</div>
            </div>
            <div class="item-price">${{ number_format($item->total, 2) }}</div>
        </div>
        @endforeach
        <div class="totals">
            <div class="total-row"><span>Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
            <div class="total-row"><span>Shipping ({{ ucfirst($order->shipping) }})</span><span>${{ number_format($order->shipping_cost, 2) }}</span></div>
            <div class="total-row grand"><span>Total</span><span>${{ number_format($order->total, 2) }}</span></div>
        </div>

        <div class="section-title">Shipping Details</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Name</div>
                <div class="info-value">{{ $order->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Phone</div>
                <div class="info-value">{{ $order->phone }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Address</div>
                <div class="info-value">{{ $order->address }}{{ $order->address2 ? ', '.$order->address2 : '' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">City / Zip</div>
                <div class="info-value">{{ $order->city ?? '—' }}{{ $order->zip ? ' · '.$order->zip : '' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Payment</div>
                <div class="info-value">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Stripe' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Order Date</div>
                <div class="info-value">{{ $order->created_at->format('d M Y') }}</div>
            </div>
        </div>

        <p style="font-size:13px; color:#666; margin-top:32px;">
            You can track your order anytime at
            <a href="{{ url('/track-order') }}" style="color:#bb976d;">{{ url('/track-order') }}</a>
            using your tracking number: <strong>{{ $order->tracking_number }}</strong>
        </p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} PeytonGhalib. All rights reserved.<br>
        <a href="{{ url('/') }}">Visit our store</a>
    </div>
</div>
</body>
</html>
