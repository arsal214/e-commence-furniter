
  <div style="max-width:1320px;margin:0 auto;padding:24px clamp(12px,4vw,40px) 0">
    <h1 style="margin:0 0 4px;font-size:clamp(26px,3.4vw,36px);font-weight:800;letter-spacing:-.03em">Your cart</h1>
    <?php if(!empty($cv['hasItems'])): ?><span style="font-size:14px;color:#4B5563">{{ $cv['count'] ?? '' }} items from {{ $cv['sellers'] ?? '' }} sellers · each seller ships separately</span><?php endif; ?>
    <?php if(!empty($cv['empty'])): ?>
      <div style="margin-top:24px;border-radius:22px;background:#F9FAFB;padding:56px 24px;display:flex;flex-direction:column;align-items:center;gap:12px;text-align:center"><b style="font-size:22px;font-weight:800">Your cart is empty</b><span style="font-size:15px;color:#4B5563">Find something you love from independent US sellers.</span><div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center"><a href="/category/deals" style="height:48px;padding:0 24px;border-radius:999px;background:#C2410C;color:#FFFFFF;display:flex;align-items:center;font-weight:800">Shop today's deals</a><a href="/wishlist" style="height:48px;padding:0 24px;border-radius:999px;border:1.5px solid #111827;color:#111827;display:flex;align-items:center;font-weight:700">View saved items</a></div></div>
    <?php endif; ?>
    <?php if(!empty($cv['hasItems'])): ?>
      <div style="display:flex;flex-wrap:wrap;gap:28px;align-items:flex-start;margin-top:20px">
        <div style="flex:1 1 560px;min-width:0;display:flex;flex-direction:column;gap:16px">
          <?php foreach($cv['list'] ?? [] as $__k => $g): ?>
            <div style="border:1px solid #E5E7EB;border-radius:20px;overflow:hidden">
              <div style="padding:16px 18px;background:#F9FAFB;display:flex;flex-direction:column;gap:10px;border-bottom:1px solid #E5E7EB">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap"><a href="{{ $g['href'] ?? '' }}" style="font-size:16px;font-weight:800;color:#111827">{{ $g['store'] ?? '' }} <span style="color:#15803D;font-size:13px">✓</span></a><span style="height:22px;padding:0 8px;border-radius:999px;background:{{ $g['lvBg'] ?? '' }};color:{{ $g['lvFg'] ?? '' }};font-size:11px;font-weight:800;display:flex;align-items:center">{{ $g['level'] ?? '' }}</span><span style="margin-left:auto;font-size:13px;color:#4B5563">Ships from {{ $g['city'] ?? '' }}</span></div>
                <?php if(!empty($g['notFree'])): ?><div style="display:flex;flex-direction:column;gap:6px"><span style="font-size:13px">Add <b>{{ $g['need'] ?? '' }}</b> from this store for <b>free shipping</b></span><div style="height:6px;border-radius:3px;background:#E5E7EB"><div style="height:6px;border-radius:3px;background:#EA580C;width:{{ $g['pct'] ?? '' }}"></div></div></div><?php endif; ?>
                <?php if(!empty($g['free'])): ?><span style="font-size:13px;font-weight:700;color:#15803D">✓ You've unlocked free shipping from this store</span><?php endif; ?>
                <?php if(!empty($g['hasBundle'])): ?><span style="font-size:13px;font-weight:700;color:#15803D">✓ Buy 2, save 10% applied ({{ $g['bundleStr'] ?? '' }})</span><?php endif; ?>
              </div>
              <?php foreach($g['items'] ?? [] as $__k => $it): ?>
                <div style="display:flex;gap:14px;padding:16px 18px;border-bottom:1px solid #F3F4F6">
                  <a href="{{ $it['href'] ?? '' }}" style="flex:none;width:96px;height:96px;border-radius:14px;background:{{ $it['tint'] ?? '' }};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;color:#6B7280">@if(!empty($it["img"]))<img src="{{ $it["img"] }}" alt="" loading="lazy" style="width:100%;height:100%;object-fit:cover;border-radius:inherit">@else{{ $it["sub"] ?? "" }}@endif</a>
                  <div style="flex:1;min-width:0;display:flex;flex-direction:column;gap:6px">
                    <div style="display:flex;justify-content:space-between;gap:12px"><a href="{{ $it['href'] ?? '' }}" style="font-size:15px;font-weight:600;color:#111827;line-height:1.35">{{ $it['name'] ?? '' }}</a><b style="font-size:16px;font-weight:800;white-space:nowrap">{{ $it['line'] ?? '' }}</b></div>
                    <span style="font-size:13px;color:#4B5563">{{ $it['variant'] ?? '' }} · {{ $it['unitStr'] ?? '' }} each</span>
                    <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin-top:auto">
                      <form method="POST" action="{{ route("cart.update") }}" style="display:flex;align-items:center;height:40px;border:1.5px solid #D1D5DB;border-radius:999px">@csrf<input type="hidden" name="cart_key" value="{{ $it["key"] }}"><button type="submit" name="qty" value="{{ $it["less"] }}" aria-label="Decrease" style="width:40px;height:100%;border:0;background:transparent;font-size:18px;cursor:pointer">−</button><b style="width:24px;text-align:center">{{ $it["qty"] }}</b><button type="submit" name="qty" value="{{ $it["more"] }}" aria-label="Increase" style="width:40px;height:100%;border:0;background:transparent;font-size:18px;cursor:pointer">+</button></form><form method="POST" action="{{ route("cart.remove") }}">@csrf<input type="hidden" name="cart_key" value="{{ $it["key"] }}"><button type="submit" style="border:0;background:transparent;padding:0;font-size:13px;font-weight:600;color:#374151;text-decoration:underline;cursor:pointer;min-height:32px">Remove</button></form>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
              <div style="display:flex;justify-content:space-between;padding:12px 18px;font-size:13px;color:#4B5563;flex-wrap:wrap;gap:8px"><span>Arrives {{ $g['arrive'] ?? '' }}</span><span>Shipping: <b style="color:#111827">{{ $g['shipStr'] ?? '' }}</b></span></div>
            </div>
          <?php endforeach; ?>
        </div>
        <aside style="flex:1 1 340px;max-width:440px;min-width:0;display:flex;flex-direction:column;gap:14px;position:sticky;top:140px">
          <div style="border:1px solid #E5E7EB;border-radius:20px;padding:20px;display:flex;flex-direction:column;gap:14px">
            <b style="font-size:18px;font-weight:800">Order summary</b>@include("market.cart-ship")<div data-welcome-cart class="pgw-cart" hidden></div>
            <div style="display:flex;flex-direction:column;gap:10px;font-size:14px">
              <div style="display:flex;justify-content:space-between"><span style="color:#4B5563">Subtotal ({{ $cv['count'] ?? '' }} items)</span><span>{{ $cv['subtotal'] ?? '' }}</span></div>
              <?php if(!empty($cv['hasBundle'])): ?><div style="display:flex;justify-content:space-between;color:#15803D"><span>Bundle savings</span><span>{{ $cv['bundle'] ?? '' }}</span></div><?php endif; ?>
              <?php if(!empty($cv['hasCoupon'])): ?><div style="display:flex;justify-content:space-between;color:#15803D"><span>Welcome deal</span><span>{{ $cv['coupon'] ?? '' }}</span></div><?php endif; ?>
              <?php if(!empty($cv['hasPts'])): ?><div style="display:flex;justify-content:space-between;color:#15803D"><span>Reward points</span><span>{{ $cv['pts'] ?? '' }}</span></div><?php endif; ?>
              <?php if(!empty($cv["couponOn"])): ?><div style="display:flex;justify-content:space-between;color:#15803D"><span>Coupon {{ $cv["couponCode"] }}</span><span>{{ $cv["couponOff"] }}</span></div><?php endif; ?><?php if(!empty($cv["ptsOn"])): ?><div style="display:flex;justify-content:space-between;color:#15803D"><span>Reward points</span><span>{{ $cv["ptsOff"] }}</span></div><?php endif; ?><?php if(!empty($cv["giftOn"])): ?><div style="display:flex;justify-content:space-between;color:#15803D"><span>Gift card balance</span><span>{{ $cv["giftOff"] }}</span></div><?php endif; ?><div style="display:flex;justify-content:space-between"><span style="color:#4B5563">Shipping</span><span>{{ $cv['shipping'] ?? '' }}</span></div>
              <div style="display:flex;justify-content:space-between"><span style="color:#4B5563">Estimated tax</span><span style="color:#4B5563">At checkout</span></div>
            </div>
            
            @include("market.cart-coupon")<div style="display:flex;justify-content:space-between;align-items:baseline;border-top:1px solid #E5E7EB;padding-top:14px"><b style="font-size:16px">Total before tax</b><b style="font-size:24px;font-weight:800">{{ $cv['total'] ?? '' }}</b></div>
            <?php if(!empty($addonBlocked)): ?><span role="alert" style="font-size:13px;font-weight:600;color:#B45309">Add-on items ship free with orders of ${{ rtrim(rtrim(number_format($shippingThreshold, 2), "0"), ".") }} or more. Add another item to check out.</span><span aria-disabled="true" style="height:54px;border-radius:999px;background:#C2410C;color:#FFFFFF;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:16px" class="dc-disabled">Checkout</span><?php else: ?><a href="{{ url("/checkout") }}" style="height:54px;border-radius:999px;background:#C2410C;color:#FFFFFF;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:16px">Checkout</a><?php endif; ?>
            <span style="font-size:12px;color:#4B5563;text-align:center">Guest checkout available · Secure payments</span>
          </div>
          <div style="display:flex;gap:12px;align-items:flex-start;padding:16px;border-radius:16px;background:#F0FDF4"><span aria-hidden="true" style="display:inline-block;flex:none;width:22px;height:22px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['shield'] ?? '' }}"></span><span style="font-size:13px;line-height:1.5;color:#14532D"><b>Buyer Protection</b> — full refund if any item doesn't arrive or isn't as described.</span></div>
        </aside>
      </div>
    <?php endif; ?>
  </div>
