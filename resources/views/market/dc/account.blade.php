
  <div style="max-width:1320px;margin:0 auto;padding:24px clamp(12px,4vw,40px) 0">
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;flex-wrap:wrap"><span style="width:52px;height:52px;border-radius:50%;background:#111827;color:#FFFFFF;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px">{{ $acctInitial ?? '' }}</span><span style="display:flex;flex-direction:column"><b style="font-size:20px;font-weight:800">{{ $acctName ?? '' }}</b><span style="font-size:13px;color:#4B5563">{{ $acctSince ?? '' }}</span></span></div>
    <div class="dc-mob"><div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:12px"><?php foreach($acctTabs ?? [] as $__k => $t): ?><a href="{{ $t['href'] ?? '' }}" style="flex:none;height:40px;padding:0 16px;border-radius:999px;background:{{ $t['mbg'] ?? '' }};color:{{ $t['mfg'] ?? '' }};font-size:13px;font-weight:700;display:flex;align-items:center;white-space:nowrap">{{ $t['label'] ?? '' }}</a><?php endforeach; ?></div></div>
    <div style="display:flex;gap:32px;align-items:flex-start">
      <div class="dc-desk"><nav style="flex:0 0 230px;display:flex;flex-direction:column;gap:2px;position:sticky;top:140px"><?php foreach($acctTabs ?? [] as $__k => $t): ?><a href="{{ $t['href'] ?? '' }}" style="height:44px;padding:0 14px;border-radius:12px;background:{{ $t['bg'] ?? '' }};color:{{ $t['fg'] ?? '' }};font-weight:{{ $t['fw'] ?? '' }};font-size:14px;display:flex;align-items:center">{{ $t['label'] ?? '' }}</a><?php endforeach; ?><button data-post="/logout" style="height:44px;padding:0 14px;color:#4B5563;font-size:14px;display:flex;align-items:center;border-top:1px solid #E5E7EB;margin-top:8px;background:none;border:0;border-top:1px solid #E5E7EB;cursor:pointer;width:100%;text-align:left;font:inherit">Sign out</button></nav></div>
      <div style="flex:1;min-width:0;display:flex;flex-direction:column;gap:16px">
        <h1 style="margin:0;font-size:clamp(22px,3vw,30px);font-weight:800;letter-spacing:-.03em">{{ $acctTitle ?? '' }}</h1>

        <?php if(!empty($tab_overview)): ?>
          <div style="display:flex;flex-wrap:wrap;gap:16px">
            <div style="flex:2 1 420px;min-width:0;border:1px solid #E5E7EB;border-radius:20px;padding:18px;display:flex;flex-direction:column;gap:12px">
              <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:center"><span style="display:flex;flex-direction:column;gap:2px"><span style="font-size:12px;font-weight:800;letter-spacing:.12em;color:#9A3412">{{ $ovLabel ?? '' }}</span><b style="font-size:18px;font-weight:800">{{ $ovActive['eta'] ?? '' }}</b><span style="font-size:13px;color:#4B5563">{{ $ovActive['line'] ?? '' }}</span></span><?php if(!empty($ovActive['trackUrl'])): ?><a href="{{ $ovActive['trackUrl'] }}" style="height:42px;padding:0 18px;border-radius:999px;background:#C2410C;color:#FFFFFF;font-weight:800;font-size:14px;display:flex;align-items:center">Track package</a><?php endif; ?></div>
              <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:6px"><?php foreach($ovActive['steps'] ?? [] as $__k => $x): ?><div style="display:flex;flex-direction:column;gap:6px"><span style="height:6px;border-radius:3px;background:{{ $x['bg'] ?? '' }}"></span><span style="font-size:11px;color:{{ $x['fg'] ?? '' }};font-weight:{{ $x['fw'] ?? '' }};line-height:1.25">{{ $x['t'] ?? '' }}</span></div><?php endforeach; ?></div>
            </div>
            
          </div>
          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(min(230px,100%),1fr));gap:12px">
            <?php foreach($ovTiles ?? [] as $__k => $t): ?><a href="{{ $t['href'] ?? '' }}" style="border:1px solid #E5E7EB;border-radius:18px;padding:16px;display:flex;gap:14px;align-items:flex-start;color:#111827;min-height:88px" style-hover="border-color:#111827;color:#111827"><span style="width:44px;height:44px;border-radius:12px;background:{{ $t['bg'] ?? '' }};color:{{ $t['fg'] ?? '' }};display:flex;align-items:center;justify-content:center;font-weight:800;font-size:16px;flex:none">{{ $t['ini'] ?? '' }}</span><span style="display:flex;flex-direction:column;gap:3px;min-width:0;flex:1"><span style="display:flex;justify-content:space-between;gap:8px"><b style="font-size:15px">{{ $t['t'] ?? '' }}</b><?php if(!empty($t['hasBadge'])): ?><span style="height:22px;padding:0 8px;border-radius:999px;background:{{ $t['bg'] ?? '' }};color:{{ $t['fg'] ?? '' }};font-size:11px;font-weight:800;display:flex;align-items:center;white-space:nowrap">{{ $t['badge'] ?? '' }}</span><?php endif; ?></span><span style="font-size:13px;color:#4B5563;line-height:1.4">{{ $t['d'] ?? '' }}</span></span></a><?php endforeach; ?>
          </div>
          <div style="display:flex;flex-direction:column;gap:12px"><b style="font-size:18px;font-weight:800">Buy again</b><div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(min(150px,44%),1fr));gap:14px"><?php foreach($buyAgain ?? [] as $__k => $p): ?><a href="{{ $p['href'] ?? '' }}" style="display:flex;flex-direction:column;gap:6px;color:#111827"><div style="aspect-ratio:1;border-radius:14px;background:{{ $p['tint'] ?? '' }};display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:#6B7280">{{ $p['sub'] ?? '' }}</div><span style="font-size:13px;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $p['name'] ?? '' }}</span><b style="font-size:15px;font-weight:800">{{ $p['price'] ?? '' }}</b></a><?php endforeach; ?></div></div>
        <?php endif; ?>

        <?php if(!empty($tab_security)): ?>@include('account.tabs.settings')<?php endif; ?>

        <?php if(!empty($tab_orders)): ?>
          <?php foreach($orders ?? [] as $__k => $o): ?>
            <div style="border:1px solid #E5E7EB;border-radius:20px;overflow:hidden">
              <div style="display:flex;flex-wrap:wrap;gap:8px 24px;padding:14px 18px;background:#F9FAFB;font-size:13px;color:#4B5563;align-items:center"><span>Order <b style="color:#111827">{{ $o['no'] ?? '' }}</b></span><span>Placed {{ $o['date'] ?? '' }}</span><span>Total <b style="color:#111827">{{ $o['total'] ?? '' }}</b></span><span>Sold by <a href="{{ $o['storeHref'] ?? '' }}">{{ $o['storeName'] ?? '' }}</a></span><span style="margin-left:auto;height:26px;padding:0 10px;border-radius:999px;background:{{ $o['sb'] ?? '' }};color:{{ $o['sc'] ?? '' }};font-weight:800;font-size:12px;display:flex;align-items:center">{{ $o['status'] ?? '' }}</span></div>
              <div style="padding:16px 18px;display:flex;flex-direction:column;gap:14px">
                <b style="font-size:16px">{{ $o['eta'] ?? '' }}</b>
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:6px"><?php foreach($o['steps'] ?? [] as $__k => $s): ?><div style="display:flex;flex-direction:column;gap:6px"><span style="height:6px;border-radius:3px;background:{{ $s['bg'] ?? '' }}"></span><span style="font-size:11px;color:{{ $s['fg'] ?? '' }};font-weight:{{ $s['fw'] ?? '' }};line-height:1.25">{{ $s['t'] ?? '' }}</span></div><?php endforeach; ?></div>
                <span style="font-size:12px;color:#4B5563">{{ $o['carrier'] ?? '' }}</span>
                <?php foreach($o['items'] ?? [] as $__k => $it): ?><a href="{{ $it['href'] ?? '' }}" style="display:flex;gap:12px;align-items:center;color:#111827"><span style="width:64px;height:64px;border-radius:12px;background:{{ $it['tint'] ?? '' }};flex:none;display:flex;align-items:center;justify-content:center;font-size:10px;color:#6B7280;font-weight:600">{{ $it['sub'] ?? '' }}</span><span style="font-size:14px;font-weight:600">{{ $it['name'] ?? '' }}</span></a><?php endforeach; ?>
                <div style="display:flex;gap:8px;flex-wrap:wrap"><a href="{{ $o['trackUrl'] ?? '/track-order' }}" style="height:40px;padding:0 16px;border-radius:999px;background:#C2410C;color:#FFFFFF;display:flex;align-items:center;font-weight:700;font-size:13px">Track package</a><?php if(!empty($o['delivered'])): ?><a href="{{ $o['trackUrl'] ?? '/track-order' }}" style="height:40px;padding:0 16px;border-radius:999px;border:1.5px solid #D1D5DB;color:#111827;display:flex;align-items:center;font-weight:700;font-size:13px">Return or report a problem</a><a href="/my-account/reviews" style="height:40px;padding:0 16px;border-radius:999px;border:1.5px solid #D1D5DB;color:#111827;display:flex;align-items:center;font-weight:700;font-size:13px">Write a review</a><?php endif; ?><a href="{{ $o['trackUrl'] ?? '/track-order' }}" style="height:40px;padding:0 12px;color:#4B5563;display:flex;align-items:center;font-size:13px;text-decoration:underline">Problem with order?</a></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <?php if(!empty($tab_returns)): ?>@include('account.tabs.returns')<?php endif; ?>

        <?php if(!empty($tab_reviews)): ?>@include('account.tabs.reviews')<?php endif; ?>

        <?php if(!empty($tab_wishlist)): ?>
          <?php if(!empty($wishEmpty)): ?><span style="font-size:15px;color:#4B5563">Nothing saved yet. Tap ♡ on any product to save it and get price-drop alerts.</span><?php endif; ?>
          <?php if(!empty($wishSome)): ?><div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(min(190px,44%),1fr));gap:24px 16px"><?php foreach($wishItems ?? [] as $__k => $p): ?><div style="display:flex;flex-direction:column;gap:8px"><a href="{{ $p['href'] ?? '' }}" style="position:relative;aspect-ratio:1;border-radius:16px;background:{{ $p['tint'] ?? '' }};display:flex;align-items:center;justify-content:center;font-size:12px;color:#6B7280;font-weight:600">{{ $p['sub'] ?? '' }}<button data-act="wish" data-id="{{ $p['id'] ?? '' }}" aria-label="Remove" style="position:absolute;top:8px;right:8px;width:38px;height:38px;border-radius:50%;border:0;background:#FFFFFF;color:#DC2626;font-size:18px;cursor:pointer">♥</button></a><span style="font-size:14px;line-height:1.35">{{ $p['name'] ?? '' }}</span><b style="font-size:17px;font-weight:800">{{ $p['price'] ?? '' }}</b><?php if(!empty($p['hasDrop'])): ?><span style="font-size:12px;font-weight:800;color:#15803D">↓ {{ $p['drop'] ?? '' }}</span><?php endif; ?><a href="{{ $p['href'] ?? '#' }}" style="height:42px;border-radius:999px;border:1.5px solid #111827;background:#FFFFFF;font-weight:700;font-size:13px;cursor:pointer">View product</a></div><?php endforeach; ?></div><?php endif; ?>
        <?php endif; ?>

        <?php if(!empty($tab_rewards)): ?>@include('account.tabs.rewards')<?php endif; ?>

        <?php if(!empty($tab_giftcards)): ?>@include('account.tabs.giftcards')<?php endif; ?>

        <?php if(!empty($tab_wallet)): ?>@include('account.tabs.wallet')<?php endif; ?>

        <?php if(!empty($tab_messages)): ?>@include('account.tabs.messages')<?php endif; ?>

        <?php if(!empty($tab_following)): ?>@include('account.tabs.following')<?php endif; ?>

        <?php if(!empty($tab_notifications)): ?>@include('account.tabs.notifications')<?php endif; ?>
      </div>
    </div>
  </div>
