@php
    $showTracking  = $showTracking ?? true;
    $noteTitle     = $noteTitle ?? 'Need to change something?';
    $noteBody      = $noteBody ?? "Just reply to this email within 24 hours and we'll sort it out before your order ships.";
    $shippingLabel = ['free' => 'Free Shipping', 'fast' => 'Fast Shipping', 'local' => 'Local Pickup'][$order->shipping] ?? ucfirst($order->shipping);
    $paymentLabel  = $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Card';
@endphp
PEYTONGHALIB — Premium Furniture

{{ strtoupper($eyebrow) }}

{{ $heading }}

{{ $intro }}
@if ($showTracking)

Tracking number: {{ $order->tracking_number }}
Track your order: {{ url('/track-order?tracking=' . $order->tracking_number) }}
@if ($order->status === 'shipped' && $order->carrier)
Carrier: {{ $order->carrier }}
@endif
@if ($order->status === 'shipped' && $order->supplier_tracking)
Carrier tracking: {{ $order->supplier_tracking }}
@endif
@endif

Order date: {{ $order->created_at->format('d M Y') }}
Payment: {{ $paymentLabel }} ({{ $order->payment_status === 'paid' ? 'Paid' : 'Due on delivery' }})

------------------------------------------------------------
YOUR ORDER
------------------------------------------------------------
@foreach ($order->items as $item)
{{ $item->name }}
  Qty {{ $item->qty }} x ${{ number_format($item->price, 2) }} = ${{ number_format($item->total, 2) }}
@endforeach

Subtotal: ${{ number_format($order->subtotal, 2) }}
{{ $shippingLabel }}: {{ $order->shipping_cost > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free' }}
TOTAL: ${{ number_format($order->total, 2) }}

------------------------------------------------------------
DELIVERING TO
------------------------------------------------------------
{{ $order->name }}
{{ $order->address }}
@if ($order->address2){{ $order->address2 }}
@endif
@if ($order->city || $order->zip){{ trim(collect([$order->city, $order->zip])->filter()->implode(', ')) }}
@endif
{{ $order->phone }}

Method: {{ $shippingLabel }}
@if ($order->notes)
Notes: {{ $order->notes }}
@endif

------------------------------------------------------------
{{ $noteTitle }}
{{ $noteBody }}

Visit store: {{ url('/') }}
Track order: {{ url('/track-order') }}

(c) {{ date('Y') }} PeytonGhalib. All rights reserved.
This is a transactional message for order {{ $order->tracking_number }}.
