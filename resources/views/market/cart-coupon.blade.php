{{-- Coupon and reward-points boxes in the cart summary. A coupon only discounts items from the store that made it. --}}
@if(! empty($cv['couponOn']))
  <form method="POST" action="{{ route('cart.coupon.remove') }}" style="display:flex;justify-content:space-between;align-items:center;gap:8px;padding:10px 12px;border-radius:12px;background:#F0FDF4;font-size:13px;color:#14532D">
    @csrf<span>Code <b>{{ $cv['couponCode'] }}</b> applied</span>
    <button type="submit" style="border:0;background:transparent;padding:0;font-weight:700;text-decoration:underline;cursor:pointer;color:#14532D">Remove</button>
  </form>
@else
  <form method="POST" action="{{ route('cart.coupon') }}" style="display:flex;gap:8px">
    @csrf
    <input name="code" maxlength="40" placeholder="Coupon code" aria-label="Coupon code" autocomplete="off" style="flex:1;min-width:0;height:44px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:14px;outline:0">
    <button type="submit" style="height:44px;padding:0 16px;border-radius:12px;border:1.5px solid #111827;background:#FFFFFF;font-weight:700;cursor:pointer">Apply</button>
  </form>
@endif
@if(! empty($cv['ptsAvail']) || ! empty($cv['ptsOn']))
  <form method="POST" action="{{ route('cart.points') }}">
    @csrf
    <button type="submit" style="width:100%;display:flex;align-items:center;gap:12px;border:1px solid #FED7AA;background:#FFF7ED;border-radius:12px;padding:12px 14px;text-align:left;cursor:pointer">
      <span style="width:22px;height:22px;border-radius:6px;border:1.5px solid {{ $cv['ptsOn'] ? '#C2410C' : '#9CA3AF' }};background:{{ $cv['ptsOn'] ? '#C2410C' : '#FFFFFF' }};color:#FFFFFF;font-weight:800;font-size:13px;display:flex;align-items:center;justify-content:center;flex:none">{{ $cv['ptsOn'] ? '✓' : '' }}</span>
      <span style="display:flex;flex-direction:column;font-size:13px"><b style="font-weight:700">Use my reward points ({{ $cv['ptsUse'] }} = {{ $cv['ptsWorth'] }} off)</b><span style="color:#9A3412">100 points = $1. Points you use here are taken off when you pay.</span></span>
    </button>
  </form>
@endif
