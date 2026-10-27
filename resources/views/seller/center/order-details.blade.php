{{-- One order's pick list and ship-to details for a seller. Never shows the buyer's email. --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(280px,100%),1fr));gap:12px">
  <div style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:14px;padding:12px 14px;display:flex;flex-direction:column;gap:10px">
    <b style="font-size:12px;letter-spacing:.06em;color:#6B7280">ITEMS ORDERED</b>
    @foreach($x['lines'] ?? [] as $l)
      <div style="display:flex;gap:10px;align-items:center">
        <span style="flex:none;width:52px;height:52px;border-radius:10px;background:#E5E7EB;overflow:hidden;display:block">@if(!empty($l['img']))<img src="{{ $l['img'] }}" alt="" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block">@endif</span>
        <span style="flex:1;min-width:0;display:flex;flex-direction:column;gap:2px">
          <b style="font-size:14px;line-height:1.3">{{ $l['name'] }}</b>
          @if($l['variant'] !== '')<span style="font-size:13px;color:#374151">{{ $l['variant'] }}</span>@endif
          @if($l['sku'] !== '')<span style="font-size:12px;color:#6B7280">SKU {{ $l['sku'] }}</span>@endif
        </span>
        <span style="flex:none;text-align:right;display:flex;flex-direction:column;gap:2px"><b style="font-size:14px">{{ $l['total'] }}</b><span style="font-size:12px;color:#6B7280">{{ $l['qty'] }} × {{ $l['unit'] }}</span></span>
      </div>
    @endforeach
  </div>
  <div style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:14px;padding:12px 14px;display:flex;flex-direction:column;gap:6px;font-size:14px;line-height:1.45">
    <b style="font-size:12px;letter-spacing:.06em;color:#6B7280">SHIP TO</b>
    <b>{{ $x['buyer'] ?? '' }}</b>
    @foreach($x['addrLines'] ?? [] as $a)<span>{{ $a }}</span>@endforeach
    @if(($x['phone'] ?? '') !== '')<span>Phone: <a href="tel:{{ preg_replace('/[^0-9+]/', '', $x['phone']) }}" style="font-weight:700">{{ $x['phone'] }}</a></span>@endif
    @if(($x['note'] ?? '') !== '')<span style="margin-top:4px;background:#FEF3C7;color:#92400E;border-radius:10px;padding:8px 10px;font-size:13px"><b>Customer note:</b> {{ $x['note'] }}</span>@endif
    @if(!empty($x['paid']))<span style="margin-top:4px;align-self:flex-start;background:#ECFDF3;color:#15803D;border-radius:999px;padding:4px 12px;font-size:12px;font-weight:800">Payment: Paid</span>@endif
  </div>
</div>
