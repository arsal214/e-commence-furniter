@php
  $card = 'border:1px solid #E5E7EB;border-radius:20px;padding:18px;display:flex;flex-direction:column;gap:12px';
  $label = 'display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600';
  $input = 'height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:16px;outline:0;min-width:0';
@endphp
@if($errors->any())<span role="alert" style="font-size:13px;font-weight:600;color:#B91C1C;background:#FEE2E2;border-radius:12px;padding:10px 12px">{{ $errors->first() }}</span>@endif
<div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start">
  <div style="flex:1 1 320px;min-width:0;{{ $card }}">
    <b style="font-size:16px">Gift card balance</b>
    <b style="font-size:32px;font-weight:800">{{ $gcBalance }}</b>
    <span style="font-size:13px;color:#4B5563">Your balance is used automatically at checkout. Gift cards never expire.</span>
    <form method="POST" action="{{ route('giftcards.redeem') }}" data-once style="display:flex;gap:8px;flex-wrap:wrap">
      @csrf
      <input name="code" required maxlength="40" placeholder="Enter gift card code" autocomplete="off" style="{{ $input }};flex:1 1 200px;text-transform:uppercase">
      <button type="submit" style="height:48px;padding:0 20px;border-radius:12px;border:1.5px solid #111827;background:#fff;font-weight:700;cursor:pointer">Redeem</button>
    </form>
    @foreach($gcOwned as $c)
      <div style="display:flex;justify-content:space-between;font-size:14px;border-top:1px solid #F3F4F6;padding-top:8px"><span>Card {{ $c['code'] }} <span style="color:#6B7280">({{ $c['amount'] }})</span></span><b>{{ $c['balance'] }} left</b></div>
    @endforeach
  </div>

  <form method="POST" action="{{ route('giftcards.buy') }}" data-once style="flex:1 1 360px;min-width:0;{{ $card }}">
    @csrf
    <b style="font-size:16px">Send a digital gift card</b>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      @foreach($gcAmounts as $a)
        <label style="height:44px;padding:0 18px;border-radius:999px;border:1.5px solid #111827;display:flex;align-items:center;font-weight:700;cursor:pointer"><input type="radio" name="amount" value="{{ $a }}" @checked((int) old('amount', 25) === $a) style="margin-right:8px;accent-color:#C2410C">${{ $a }}</label>
      @endforeach
    </div>
    <label style="{{ $label }}">Or another amount ($10–$500)<input name="amount_other" type="number" min="10" max="500" step="1" inputmode="numeric" placeholder="e.g. 75" style="{{ $input }}" oninput="if(this.value){var r=this.closest('form').querySelector('input[name=amount]:checked');if(r)r.checked=false;this.name='amount'}else{this.name='amount_other'}"></label>
    <label style="{{ $label }}">Recipient email (optional)<input name="recipient_email" type="email" value="{{ old('recipient_email') }}" maxlength="255" placeholder="Leave empty to get the code yourself" style="{{ $input }}"></label>
    <label style="{{ $label }}">Message (optional)<input name="message" value="{{ old('message') }}" maxlength="300" style="{{ $input }}"></label>
    <button type="submit" style="align-self:flex-start;height:46px;padding:0 22px;border-radius:999px;border:0;background:#C2410C;color:#fff;font-weight:800;cursor:pointer">Continue to payment</button>
    <span style="font-size:12px;color:#6B7280">Digital gift cards are emailed right after payment and can't be refunded for cash. <a href="{{ url('/rewards-terms') }}" style="font-weight:700">Gift card terms</a></span>
  </form>
</div>

@if(count($gcSent))
  <div style="border:1px solid #E5E7EB;border-radius:20px;overflow:hidden">
    <div style="padding:14px 18px;border-bottom:1px solid #F3F4F6"><b>Gift cards you bought</b></div>
    @foreach($gcSent as $c)
      <div style="display:flex;justify-content:space-between;gap:12px;padding:12px 18px;border-bottom:1px solid #F3F4F6;font-size:14px"><span>{{ $c['amount'] }} to {{ $c['to'] }}<br><span style="font-size:12px;color:#6B7280">{{ $c['when'] }}</span></span><span style="font-family:monospace">{{ $c['code'] }}</span></div>
    @endforeach
  </div>
@endif
