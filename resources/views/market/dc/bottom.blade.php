

<footer style="background:#0F172A;color:#CBD5E1;margin-top:64px">
  <div style="max-width:1320px;margin:0 auto;padding:48px clamp(16px,4vw,40px) 24px">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(190px,100%),1fr));gap:32px">
      <?php foreach($footCols ?? [] as $__k => $col): ?><div style="display:flex;flex-direction:column;gap:12px"><b style="color:#FFFFFF;font-size:15px;font-weight:700">{{ $col['h'] ?? '' }}</b><?php foreach($col['links'] ?? [] as $__k => $l): ?><a href="{{ $l['href'] ?? '' }}" style="color:#CBD5E1;font-size:14px">{{ $l['t'] ?? '' }}</a><?php endforeach; ?></div><?php endforeach; ?>
      <div style="display:flex;flex-direction:column;gap:12px">
        <b style="color:#FFFFFF;font-size:15px;font-weight:700">Get deals first</b>
        <span style="font-size:13px;line-height:1.5">Weekly deals and new stores. Unsubscribe any time.</span>
        <div data-if="subscribed" style="display:contents" hidden><span style="font-size:14px;font-weight:700;color:#86EFAC">✓ You're subscribed</span></div>
        <div data-if="notSubscribed" style="display:contents"><div style="display:flex;gap:8px"><input placeholder="Email address" style="flex:1;min-width:0;height:44px;border-radius:999px;border:1px solid #334155;background:#1E293B;color:#FFFFFF;padding:0 16px;font-size:14px;outline:0"><button data-act="subscribe" style="height:44px;padding:0 18px;border-radius:999px;border:0;background:#EA580C;color:#111827;font-weight:800;font-size:14px;cursor:pointer">Join</button></div></div>
        <b style="color:#FFFFFF;font-size:15px;font-weight:700;margin-top:12px">Connect with us</b>
        <div style="display:flex;gap:8px;flex-wrap:wrap"><?php foreach($socials ?? [] as $__k => $s): ?><a href="{{ $s['href'] ?? '#' }}" target="_blank" rel="noopener" style="height:36px;padding:0 12px;border-radius:999px;border:1px solid #334155;color:#E2E8F0;font-size:13px;font-weight:600;display:flex;align-items:center">{{ $s['t'] ?? '' }}</a><?php endforeach; ?></div>
      </div>
    </div>
    <div style="border-top:1px solid #1E293B;margin-top:40px;padding-top:24px;display:flex;flex-wrap:wrap;gap:16px;align-items:center;justify-content:space-between">
      <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap"><span style="font-size:13px;font-weight:700;color:#FFFFFF;margin-right:4px">We accept</span><img src="https://cdn.jsdelivr.net/gh/datatrans/payment-logos@master/assets/cards/visa.svg" alt="Visa" title="Visa" width="60" height="40" style="display:block;width:60px;height:40px;border-radius:6px;background:#FFFFFF;flex:none"><img src="https://cdn.jsdelivr.net/gh/datatrans/payment-logos@master/assets/cards/mastercard.svg" alt="Mastercard" title="Mastercard" width="60" height="40" style="display:block;width:60px;height:40px;border-radius:6px;background:#FFFFFF;flex:none"><img src="https://cdn.jsdelivr.net/gh/datatrans/payment-logos@master/assets/cards/american-express.svg" alt="American Express" title="American Express" width="60" height="40" style="display:block;width:60px;height:40px;border-radius:6px;background:#FFFFFF;flex:none"><img src="https://cdn.jsdelivr.net/gh/datatrans/payment-logos@master/assets/cards/discover.svg" alt="Discover" title="Discover" width="60" height="40" style="display:block;width:60px;height:40px;border-radius:6px;background:#FFFFFF;flex:none"><img src="https://cdn.jsdelivr.net/gh/datatrans/payment-logos@master/assets/cards/maestro.svg" alt="Maestro" title="Maestro" width="60" height="40" style="display:block;width:60px;height:40px;border-radius:6px;background:#FFFFFF;flex:none"><img src="https://cdn.jsdelivr.net/gh/datatrans/payment-logos@master/assets/cards/diners.svg" alt="Diners Club" title="Diners Club" width="60" height="40" style="display:block;width:60px;height:40px;border-radius:6px;background:#FFFFFF;flex:none"><img src="https://cdn.jsdelivr.net/gh/datatrans/payment-logos@master/assets/cards/jcb.svg" alt="JCB" title="JCB" width="60" height="40" style="display:block;width:60px;height:40px;border-radius:6px;background:#FFFFFF;flex:none"><img src="https://cdn.jsdelivr.net/gh/datatrans/payment-logos@master/assets/cards/unionpay.svg" alt="UnionPay" title="UnionPay" width="60" height="40" style="display:block;width:60px;height:40px;border-radius:6px;background:#FFFFFF;flex:none"><img src="https://cdn.jsdelivr.net/gh/datatrans/payment-logos@master/assets/apm/paypal.svg" alt="PayPal" title="PayPal" width="60" height="40" style="display:block;width:60px;height:40px;border-radius:6px;background:#FFFFFF;flex:none"><img src="https://cdn.jsdelivr.net/gh/datatrans/payment-logos@master/assets/wallets/apple-pay.svg" alt="Apple Pay" title="Apple Pay" width="60" height="40" style="display:block;width:60px;height:40px;border-radius:6px;background:#FFFFFF;flex:none"><img src="https://cdn.jsdelivr.net/gh/datatrans/payment-logos@master/assets/wallets/google-pay.svg" alt="Google Pay" title="Google Pay" width="60" height="40" style="display:block;width:60px;height:40px;border-radius:6px;background:#FFFFFF;flex:none"></div>
    </div>
    <div style="margin-top:20px;display:flex;flex-wrap:wrap;gap:8px 18px;font-size:12px;color:#94A3B8;align-items:center">
      <span>© 2026 Peyton Ghalib LLC. All rights reserved.</span>
      <?php foreach($legalLinks ?? [] as $__k => $l): ?><a href="{{ $l['href'] ?? '' }}" style="color:#94A3B8">{{ $l['t'] ?? '' }}</a><?php endforeach; ?>
    </div>
  </div>
</footer>

<div style="position:sticky;bottom:0;z-index:40">
  <div data-if="hasToast" style="display:contents" hidden><div style="position:absolute;left:50%;bottom:{{ $toastBottom ?? '' }};transform:translateX(-50%);background:#111827;color:#FFFFFF;border-radius:999px;padding:12px 18px;font-size:14px;font-weight:600;display:flex;gap:14px;align-items:center;box-shadow:0 8px 24px rgba(0,0,0,.25);white-space:nowrap">{{ $toast ?? '' }}<a href="/cart" style="color:#FDBA74;font-weight:800">View cart</a></div></div>
  <button data-act="toggleChat" aria-label="Chat with support" style="position:absolute;right:16px;bottom:{{ $chatBottom ?? '' }};height:52px;padding:0 18px;border-radius:999px;border:0;background:#111827;color:#FFFFFF;font-weight:700;font-size:14px;display:flex;align-items:center;gap:8px;box-shadow:0 8px 24px rgba(0,0,0,.25);cursor:pointer"><span aria-hidden="true" style="display:inline-block;flex:none;width:20px;height:20px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['chatW'] ?? '' }}"></span>{{ $chatLabel ?? '' }}</button>
  <div data-if="chatOpen" style="display:contents" hidden>
    <div style="position:absolute;right:16px;bottom:{{ $chatPanelBottom ?? '' }};width:min(360px,calc(100vw - 32px));height:440px;background:#FFFFFF;border-radius:20px;box-shadow:0 16px 48px rgba(0,0,0,.25);display:flex;flex-direction:column;overflow:hidden">
      <div style="background:#111827;color:#FFFFFF;padding:16px 18px;display:flex;flex-direction:column;gap:2px"><b style="font-size:15px">Peyton Ghalib Support</b><span style="font-size:12px;color:#86EFAC">● Online · usually replies in minutes</span></div>
      <div style="flex:1;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:10px;background:#F9FAFB">
        <?php foreach($supportMsgs ?? [] as $__k => $m): ?><div style="align-self:{{ $m['align'] ?? '' }};max-width:80%;background:{{ $m['bg'] ?? '' }};color:{{ $m['fg'] ?? '' }};padding:10px 14px;border-radius:16px;font-size:14px;line-height:1.45">{{ $m['t'] ?? '' }}</div><?php endforeach; ?>
        <div style="display:flex;flex-wrap:wrap;gap:6px"><?php foreach($quickQs ?? [] as $__k => $q): ?><button data-act="q.send" style="height:32px;padding:0 12px;border-radius:999px;border:1px solid #E5E7EB;background:#FFFFFF;font-size:12px;font-weight:600;cursor:pointer">{{ $q['t'] ?? '' }}</button><?php endforeach; ?></div>
      </div>
      <div style="display:flex;gap:8px;padding:12px;border-top:1px solid #E5E7EB"><input value="{{ $supportInput ?? '' }}" placeholder="Type a message" style="flex:1;min-width:0;height:44px;border-radius:999px;border:1px solid #E5E7EB;padding:0 16px;font-size:14px;outline:0"><button data-act="sendSupport" style="height:44px;padding:0 16px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:700;cursor:pointer">Send</button></div>
    </div>
  </div>
  <?php if(!empty($showTabs)): ?><div class="dc-mob">
    <div style="display:grid;grid-template-columns:repeat(5,1fr);background:#FFFFFF;border-top:1px solid #E5E7EB;padding:6px 4px 10px">
      <?php foreach($tabs ?? [] as $__k => $t): ?><a href="{{ $t['href'] ?? '' }}" style="display:flex;flex-direction:column;align-items:center;gap:3px;min-height:48px;justify-content:center;color:{{ $t['color'] ?? '' }};font-size:11px;font-weight:700;position:relative"><span aria-hidden="true" style="display:inline-block;flex:none;width:22px;height:22px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $t['icon'] ?? '' }}"></span>{{ $t['label'] ?? '' }}</a><?php endforeach; ?>
    </div>
  </div><?php endif; ?>
  <?php if(!empty($showBuyBar)): ?><div class="dc-mob">
    <div style="display:flex;gap:10px;align-items:center;background:#FFFFFF;border-top:1px solid #E5E7EB;padding:10px 12px 14px">
      <div style="display:flex;flex-direction:column;flex:none"><b style="font-size:18px;font-weight:800">{{ $pdp['priceStr'] ?? '' }}</b><span style="font-size:11px;color:#4B5563"></span></div>
      <button type="submit" form="buy" style="flex:1;height:50px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:800;font-size:15px;cursor:pointer">Add to cart</button>
    </div>
  </div><?php endif; ?>
</div>
</div>
