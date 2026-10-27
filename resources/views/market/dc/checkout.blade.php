
  <div style="max-width:1180px;margin:0 auto;padding:24px clamp(12px,4vw,40px) 0">
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px"><h1 style="margin:0;font-size:clamp(26px,3.4vw,34px);font-weight:800;letter-spacing:-.03em">Checkout</h1><span style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:#15803D"><span aria-hidden="true" style="display:inline-block;flex:none;width:16px;height:16px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['lock'] ?? '' }}"></span>Secure checkout</span></div>
    <div style="display:flex;flex-wrap:wrap;gap:28px;align-items:flex-start">
      <div style="flex:1 1 560px;min-width:0;display:flex;flex-direction:column;gap:16px">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:8px"><button style="height:50px;border-radius:12px;border:0;background:#000000;color:#FFFFFF;font-weight:700;font-size:15px;cursor:pointer">Apple Pay</button><button style="height:50px;border-radius:12px;border:0;background:#FFC439;color:#111827;font-weight:800;font-size:15px;cursor:pointer">PayPal</button><button style="height:50px;border-radius:12px;border:1.5px solid #111827;background:#FFFFFF;font-weight:700;font-size:15px;cursor:pointer">Google Pay</button></div>
        <div style="display:flex;align-items:center;gap:12px;font-size:12px;color:#6B7280"><span style="flex:1;height:1px;background:#E5E7EB"></span>or pay with card<span style="flex:1;height:1px;background:#E5E7EB"></span></div>
        <div style="border:1px solid #E5E7EB;border-radius:20px;padding:20px;display:flex;flex-direction:column;gap:14px">
          <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap"><b style="font-size:17px;font-weight:800">1. Contact</b><span style="font-size:13px;color:#4B5563">Checking out as guest · <a href="/login">Sign in</a></span></div>
          <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Email<input value="jessica.m@example.com" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0"></label>
          <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Mobile phone<input value="(212) 555-0148" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0"></label>
          <button data-act="toggleSms" style="display:flex;align-items:center;gap:10px;border:0;background:transparent;padding:0;font-size:14px;text-align:left;cursor:pointer;min-height:32px"><span style="width:22px;height:22px;border-radius:6px;border:1.5px solid {{ $smsBd ?? '' }};background:{{ $smsBg ?? '' }};color:#FFFFFF;font-weight:800;font-size:13px;display:flex;align-items:center;justify-content:center;flex:none">{{ $smsMark ?? '' }}</span>Text me order and delivery updates (msg &amp; data rates may apply)</button>
        </div>
        <div style="border:1px solid #E5E7EB;border-radius:20px;padding:20px;display:flex;flex-direction:column;gap:14px">
          <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap"><b style="font-size:17px;font-weight:800">2. Shipping address</b><span style="font-size:13px;color:#4B5563">We ship within the US only</span></div>
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(220px,100%),1fr));gap:12px">
            <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">First name<input value="Jessica" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0"></label>
            <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Last name<input value="Martinez" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0"></label>
          </div>
          <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Street address<input value="245 W 25th St" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0"></label>
          <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Apt, suite (optional)<input value="Apt 4B" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0"></label>
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(140px,100%),1fr));gap:12px">
            <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">City<input value="New York" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0"></label>
            <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">State<select value="{{ $stateSel ?? '' }}" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 12px;font-size:15px;background:#FFFFFF"><?php foreach($states ?? [] as $__k => $s): ?><option value="{{ $s['v'] ?? '' }}">{{ $s['v'] ?? '' }}</option><?php endforeach; ?></select></label>
            <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">ZIP code<input value="10001" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0"></label>
          </div>
        </div>
        <div style="border:1px solid #E5E7EB;border-radius:20px;padding:20px;display:flex;flex-direction:column;gap:12px">
          <b style="font-size:17px;font-weight:800">3. Delivery</b>
          <?php foreach($cv['list'] ?? [] as $__k => $g): ?><div style="display:flex;justify-content:space-between;gap:12px;padding:14px;border-radius:14px;background:#F9FAFB;flex-wrap:wrap"><span style="display:flex;flex-direction:column;gap:2px;font-size:14px"><b>{{ $g['store'] ?? '' }}</b><span style="color:#4B5563">Standard, tracked · Arrives {{ $g['arrive'] ?? '' }}</span></span><b style="font-size:14px">{{ $g['shipStr'] ?? '' }}</b></div><?php endforeach; ?>
        </div>
        <div style="border:1px solid #E5E7EB;border-radius:20px;padding:20px;display:flex;flex-direction:column;gap:12px">
          <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap"><b style="font-size:17px;font-weight:800">4. Payment</b><span style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:#15803D"><span aria-hidden="true" style="display:inline-block;flex:none;width:14px;height:14px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['lock'] ?? '' }}"></span>Encrypted · processed securely</span></div>
          <?php foreach($payOpts ?? [] as $__k => $o): ?><button data-act="o.pick" style="display:flex;align-items:center;gap:12px;padding:14px;border-radius:14px;border:1.5px solid {{ $o['bd'] ?? '' }};background:#FFFFFF;font-size:15px;font-weight:600;text-align:left;cursor:pointer"><span style="width:20px;height:20px;border-radius:50%;border:{{ $o['ring'] ?? '' }};flex:none"></span>{{ $o['label'] ?? '' }}</button><?php endforeach; ?>
          <?php if(!empty($payCard)): ?><div style="display:flex;flex-direction:column;gap:12px;padding-top:4px">
            <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Card number<input value="4242 4242 4242 4242" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0;font-variant-numeric:tabular-nums"></label>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px"><label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Expiry<input placeholder="MM / YY" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0"></label><label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Security code<input placeholder="CVC" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0"></label></div>
          </div><?php endif; ?>
          <?php if(!empty($gcNot)): ?><button data-act="redeemGc" style="align-self:flex-start;border:0;background:transparent;padding:0;font-size:14px;font-weight:700;color:#C2410C;cursor:pointer;min-height:32px">+ Apply gift card (demo: $25 balance)</button><?php endif; ?>
          <?php if(!empty($gcRedeemed)): ?><span style="font-size:14px;font-weight:700;color:#15803D">✓ Gift card applied</span><?php endif; ?>
        </div>
      </div>
      <aside style="flex:1 1 340px;max-width:440px;min-width:0;position:sticky;top:140px;display:flex;flex-direction:column;gap:14px">
        <div style="border:1px solid #E5E7EB;border-radius:20px;padding:20px;display:flex;flex-direction:column;gap:12px;font-size:14px">
          <b style="font-size:18px;font-weight:800">Order summary</b>
          <?php foreach($cv['list'] ?? [] as $__k => $g): ?><?php foreach($g['items'] ?? [] as $__k => $it): ?><div style="display:flex;gap:12px;align-items:center"><span style="position:relative;flex:none;width:56px;height:56px;border-radius:12px;background:{{ $it['tint'] ?? '' }}"><span style="position:absolute;top:-6px;right:-6px;min-width:20px;height:20px;border-radius:999px;background:#111827;color:#FFFFFF;font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center">{{ $it['qty'] ?? '' }}</span></span><span style="flex:1;min-width:0;display:flex;flex-direction:column"><span style="font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $it['name'] ?? '' }}</span><span style="font-size:12px;color:#4B5563">{{ $it['variant'] ?? '' }}</span></span><b>{{ $it['line'] ?? '' }}</b></div><?php endforeach; ?><?php endforeach; ?>
          <div style="border-top:1px solid #E5E7EB;padding-top:12px;display:flex;flex-direction:column;gap:8px">
            <div style="display:flex;justify-content:space-between"><span style="color:#4B5563">Subtotal</span><span>{{ $cv['subtotal'] ?? '' }}</span></div>
            <?php if(!empty($cv['hasBundle'])): ?><div style="display:flex;justify-content:space-between;color:#15803D"><span>Bundle savings</span><span>{{ $cv['bundle'] ?? '' }}</span></div><?php endif; ?>
            <?php if(!empty($cv['hasCoupon'])): ?><div style="display:flex;justify-content:space-between;color:#15803D"><span>Coupon</span><span>{{ $cv['coupon'] ?? '' }}</span></div><?php endif; ?>
            <?php if(!empty($cv['hasPts'])): ?><div style="display:flex;justify-content:space-between;color:#15803D"><span>Points</span><span>{{ $cv['pts'] ?? '' }}</span></div><?php endif; ?>
            <?php if(!empty($cv['hasGc'])): ?><div style="display:flex;justify-content:space-between;color:#15803D"><span>Gift card</span><span>{{ $cv['gc'] ?? '' }}</span></div><?php endif; ?>
            <div style="display:flex;justify-content:space-between"><span style="color:#4B5563">Shipping</span><span>{{ $cv['shipping'] ?? '' }}</span></div>
            <div style="display:flex;justify-content:space-between"><span style="color:#4B5563">Sales tax ({{ $stateSel ?? '' }} {{ $cv['rate'] ?? '' }})</span><span>{{ $cv['tax'] ?? '' }}</span></div>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:baseline;border-top:1px solid #E5E7EB;padding-top:12px"><b style="font-size:16px">Total (USD)</b><b style="font-size:26px;font-weight:800">{{ $cv['total'] ?? '' }}</b></div>
          <button data-act="placeOrder" style="height:56px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:800;font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px">Place order · {{ $cv['total'] ?? '' }}</button>
          <span style="font-size:12px;color:#4B5563;line-height:1.5;text-align:center">By placing your order you agree to our <a href="/terms-and-conditions">Terms</a> and each seller's return policy. You'll earn {{ $cv['earn'] ?? '' }} points.</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;padding:16px;border-radius:16px;background:#F9FAFB;font-size:13px;color:#374151">
          <span style="display:flex;gap:10px;align-items:center"><span aria-hidden="true" style="display:inline-block;flex:none;width:18px;height:18px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['shield'] ?? '' }}"></span>Buyer Protection on every item</span>
          <span style="display:flex;gap:10px;align-items:center"><span aria-hidden="true" style="display:inline-block;flex:none;width:18px;height:18px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['ret'] ?? '' }}"></span>Easy returns from your orders page</span>
          <span style="display:flex;gap:10px;align-items:center"><span aria-hidden="true" style="display:inline-block;flex:none;width:18px;height:18px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['truck'] ?? '' }}"></span>Tracking by email &amp; text</span>
        </div>
      </aside>
    </div>
  </div>
