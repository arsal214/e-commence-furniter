{{-- Free-shipping status in the cart summary, computed on the server from the live cart. --}}
@if (! $shippingEnabled)
  <div role="status" style="background:#F0FDF4;border-radius:14px;padding:12px 14px;display:flex;flex-direction:column;gap:6px">
    <b style="font-size:14px;color:#15803D">✓ Free shipping unlocked</b>
    <div aria-hidden="true" style="height:6px;border-radius:3px;background:#DCFCE7"><div style="height:6px;border-radius:3px;background:#16A34A;width:100%"></div></div>
    <span style="font-size:12px;color:#166534;line-height:1.45">Applies to every order — no minimum spend. Estimated {{ \App\Support\DeliveryEstimate::minDays() }}–{{ \App\Support\DeliveryEstimate::maxDays() }} business days.</span>
  </div>
@else
  <div role="status" style="background:#F0FDF4;border-radius:14px;padding:12px 14px;display:flex;flex-direction:column;gap:6px">
    <b style="font-size:14px;color:#15803D">{{ $shippingRemaining === null ? 'Nice! Your order ships free.' : "You're $" . number_format($shippingRemaining, 2) . ' away from free shipping.' }}</b>
    <div aria-hidden="true" style="height:6px;border-radius:3px;background:#DCFCE7"><div style="height:6px;border-radius:3px;background:#16A34A;width:{{ min(100, round($cartTotal / max(0.01, $shippingThreshold) * 100)) }}%"></div></div>
    <span style="font-size:12px;color:#166534;line-height:1.45">{{ $shippingPromo }}; ${{ number_format(config('checkout.flat_shipping_rate'), 2) }} flat rate below that. Estimated {{ \App\Support\DeliveryEstimate::minDays() }}–{{ \App\Support\DeliveryEstimate::maxDays() }} business days.</span>
  </div>
@endif
