@php $card = 'border:1px solid #E5E7EB;border-radius:20px;padding:18px;display:flex;flex-direction:column;gap:14px'; @endphp
<form method="POST" action="{{ url('/my-account/notifications') }}" data-once style="{{ $card }}">
  @csrf
  <b style="font-size:16px">Email me about</b>
  <label style="display:flex;gap:12px;align-items:flex-start;font-size:14px"><input type="checkbox" checked disabled style="width:20px;height:20px;margin-top:2px"><span><b>Orders and delivery</b><br><span style="color:#4B5563">Receipts, tracking and claim updates. These always go to you.</span></span></label>
  <label style="display:flex;gap:12px;align-items:flex-start;font-size:14px"><input type="checkbox" name="marketing" value="1" @checked($marketingOn) style="width:20px;height:20px;margin-top:2px;accent-color:#C2410C"><span><b>Deals and offers</b><br><span style="color:#4B5563">New products and sales from Peyton Ghalib. You can switch this off any time.</span></span></label>
  <button type="submit" style="align-self:flex-start;height:46px;padding:0 22px;border-radius:999px;border:0;background:#C2410C;color:#fff;font-weight:800;cursor:pointer">Save</button>
</form>
