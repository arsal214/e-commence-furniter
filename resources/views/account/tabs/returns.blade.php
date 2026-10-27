@php $card = 'border:1px solid #E5E7EB;border-radius:20px;padding:18px;display:flex;flex-direction:column;gap:10px'; @endphp
@if($errors->any())<span role="alert" style="font-size:13px;font-weight:600;color:#B91C1C;background:#FEE2E2;border-radius:12px;padding:10px 12px">{{ $errors->first() }}</span>@endif
<div style="{{ $card }}">
  <b style="font-size:16px">Request a refund</b>
  <span style="font-size:14px;color:#4B5563;line-height:1.5">Ask the seller to refund an order. They can approve it right away; if they decline, our team reviews it and emails you the decision. Each seller's return window is shown on their product pages.</span>
  @forelse($claimOrders as $o)
    <div style="border-top:1px solid #F3F4F6;padding-top:8px;display:flex;flex-direction:column;gap:8px">
      <div style="display:flex;justify-content:space-between;gap:10px;align-items:center;font-size:14px"><span><b>{{ $o['store'] }}</b> · {{ $o['no'] }} <span style="color:#6B7280">· {{ $o['status'] }}</span></span><a href="{{ $o['url'] }}" style="font-weight:700">Track</a></div>
      @if(!empty($o['canRefund']) && !empty($o['sellerRef']))
        <details>
          <summary style="cursor:pointer;font-weight:700;font-size:13px;color:#C2410C">Request a refund for this order</summary>
          <form method="POST" action="{{ route('account.refund') }}" style="display:flex;flex-direction:column;gap:10px;margin-top:10px">@csrf
            <input type="hidden" name="order" value="{{ $o['no'] }}"><input type="hidden" name="seller" value="{{ $o['sellerRef'] }}">
            <select name="reason" required aria-label="Reason" style="height:44px;border-radius:12px;border:1.5px solid #D1D5DB;padding:0 12px;background:#FFFFFF;font-size:14px">@foreach(\App\Services\DisputeService::REASONS as $k => $l)<option value="{{ $k }}">{{ $l }}</option>@endforeach</select>
            <textarea name="details" required minlength="10" maxlength="1500" rows="3" placeholder="Tell the seller what happened" aria-label="What happened" style="border-radius:12px;border:1.5px solid #D1D5DB;padding:10px 12px;font-size:14px;font-family:inherit"></textarea>
            <button type="submit" style="align-self:flex-start;height:44px;padding:0 20px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:800;cursor:pointer">Send refund request</button>
          </form>
        </details>
      @endif
    </div>
  @empty
    <span style="font-size:14px;color:#4B5563">You have no orders yet.</span>
  @endforelse
</div>
@forelse($claims as $c)
  <div style="{{ $card }}">
    <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap"><b>{{ $c['reason'] }} · {{ $c['store'] }}</b><span style="height:26px;padding:0 10px;border-radius:999px;font-size:12px;font-weight:800;display:flex;align-items:center;background:{{ $c['done'] ? '#ECFDF3' : '#FEF3C7' }};color:{{ $c['done'] ? '#15803D' : '#92400E' }}">{{ $c['status'] }}</span></div>
    <span style="font-size:13px;color:#4B5563">Order {{ $c['no'] }} · {{ $c['amount'] }} · opened {{ $c['when'] }}</span>
    @if($c['sellerNote'])<span style="font-size:13px;color:#374151;line-height:1.5"><b>The seller replied:</b> {{ $c['sellerNote'] }}</span>@endif
    @if($c['note'])<span style="font-size:13px;color:#374151;line-height:1.5">{{ $c['note'] }}</span>@endif
  </div>
@empty
  <span style="font-size:14px;color:#4B5563">You haven't asked for any refunds.</span>
@endforelse
