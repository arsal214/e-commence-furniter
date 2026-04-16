<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Shipped</title>
    <style>
        body { margin: 0; padding: 0; background: #f5f5f5; font-family: 'Helvetica Neue', Arial, sans-serif; color: #333; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .header { background: #1a1a1a; padding: 32px 40px; text-align: center; }
        .header h1 { margin: 0; color: #bb976d; font-size: 24px; letter-spacing: 2px; font-weight: 600; }
        .header p { margin: 6px 0 0; color: #888; font-size: 12px; letter-spacing: 1px; text-transform: uppercase; }
        .hero { background: linear-gradient(135deg, #bb976d 0%, #a8845a 100%); padding: 36px 40px; text-align: center; }
        .hero-icon { font-size: 48px; margin-bottom: 12px; }
        .hero h2 { margin: 0; color: #fff; font-size: 22px; font-weight: 700; }
        .hero p { margin: 8px 0 0; color: rgba(255,255,255,0.85); font-size: 14px; }
        .body { padding: 40px; }
        .greeting { font-size: 16px; color: #444; margin-bottom: 24px; }
        .tracking-box { background: #fdf8f3; border: 2px solid #bb976d; border-radius: 8px; padding: 24px; text-align: center; margin: 24px 0; }
        .tracking-box .label { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-bottom: 8px; }
        .tracking-box .number { font-size: 28px; font-weight: 700; color: #bb976d; letter-spacing: 3px; }
        .track-btn { display: inline-block; margin-top: 16px; padding: 12px 32px; background: #bb976d; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600; }
        .section-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #bb976d; margin: 32px 0 12px; border-bottom: 1px solid #f0e8de; padding-bottom: 8px; }
        .status-steps { display: flex; justify-content: space-between; margin: 24px 0; position: relative; }
        .status-steps::before { content: ''; position: absolute; top: 14px; left: 10%; right: 10%; height: 2px; background: #bb976d; z-index: 0; }
        .step { text-align: center; flex: 1; position: relative; z-index: 1; }
        .step-dot { width: 28px; height: 28px; border-radius: 50%; background: #bb976d; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #fff; }
        .step-dot.inactive { background: #e5e5e5; color: #999; }
        .step-label { font-size: 11px; color: #bb976d; font-weight: 600; }
        .step-label.inactive { color: #999; font-weight: 400; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 13px; }
        .info-item .info-label { color: #888; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
        .info-item .info-value { color: #333; font-weight: 500; }
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
    <div class="hero">
        <div class="hero-icon">&#128666;</div>
        <h2>Your Order Is On Its Way!</h2>
        <p>Great news – your order has been shipped and is heading to you.</p>
    </div>
    <div class="body">
        <p class="greeting">Hi {{ $order->name }},<br>Your order has been dispatched. Use the tracking number below to monitor your delivery.</p>

        <div class="tracking-box">
            <div class="label">Your Order Tracking Number</div>
            <div class="number">{{ $order->tracking_number }}</div>
            <a href="{{ url('/track-order?tracking=' . $order->tracking_number) }}" class="track-btn">Track My Order</a>
        </div>

        {{-- Status Steps --}}
        <div class="status-steps">
            <div class="step">
                <div class="step-dot">&#10003;</div>
                <div class="step-label">Order Placed</div>
            </div>
            <div class="step">
                <div class="step-dot">&#10003;</div>
                <div class="step-label">Processing</div>
            </div>
            <div class="step">
                <div class="step-dot">&#10003;</div>
                <div class="step-label">Shipped</div>
            </div>
            <div class="step">
                <div class="step-dot inactive">4</div>
                <div class="step-label inactive">Delivered</div>
            </div>
        </div>

        <div class="section-title">Shipping Information</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Tracking Number</div>
                <div class="info-value">{{ $order->tracking_number }}</div>
            </div>
            @if($order->carrier)
            <div class="info-item">
                <div class="info-label">Carrier</div>
                <div class="info-value">{{ ucfirst($order->carrier) }}</div>
            </div>
            @endif
            @if($order->shipped_at)
            <div class="info-item">
                <div class="info-label">Shipped On</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($order->shipped_at)->format('d M Y') }}</div>
            </div>
            @endif
            <div class="info-item">
                <div class="info-label">Deliver To</div>
                <div class="info-value">{{ $order->address }}{{ $order->address2 ? ', '.$order->address2 : '' }}, {{ $order->city }}</div>
            </div>
        </div>

        <p style="font-size:13px; color:#666; margin-top:32px;">
            Track your order anytime at
            <a href="{{ url('/track-order') }}" style="color:#bb976d;">{{ url('/track-order') }}</a>
        </p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} PeytonGhalib. All rights reserved.<br>
        <a href="{{ url('/') }}">Visit our store</a>
    </div>
</div>
</body>
</html>
