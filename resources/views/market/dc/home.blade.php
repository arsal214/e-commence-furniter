
  <div style="max-width:1320px;margin:0 auto;padding:20px clamp(12px,4vw,40px) 0;position:relative">
    <div data-cards data-rail="1" style="display:grid;grid-auto-flow:column;grid-auto-columns:minmax(min(280px,80%),calc((100% - 48px) / 4));gap:16px;overflow-x:auto;scroll-snap-type:x mandatory;scrollbar-width:none;padding-bottom:4px">
      <?php foreach($promos ?? [] as $__k => $c): ?><?php if(!empty($c['isPicture'])): ?><a href="{{ $c['href'] }}" style="scroll-snap-align:start;display:block;height:clamp(440px,42vw,580px);border-radius:24px;overflow:hidden;background:{{ $c['bg'] }}"><img src="{{ $c['img'] }}" alt="{{ $c['alt'] }}" width="600" height="1160"<?php if($__k >= 4): ?> loading="lazy"<?php endif; ?> decoding="async" style="width:100%;height:100%;object-fit:cover;display:block"></a><?php continue; endif; ?>
        <div style="scroll-snap-align:start;position:relative;height:clamp(440px,42vw,580px);border-radius:24px;overflow:hidden;background:{{ $c['bg'] ?? '' }};color:{{ $c['fg'] ?? '' }};display:flex;flex-direction:column">
          <div style="padding:22px 22px 0;display:flex;flex-direction:column;gap:6px;position:relative;z-index:1">
            <?php if(!empty($c['hasKicker'])): ?><span style="font-size:clamp(15px,1.3vw,19px);font-weight:600">{{ $c['kicker'] ?? '' }}</span><?php endif; ?>
            <a href="{{ $c['href'] ?? '' }}" style="color:{{ $c['fg'] ?? '' }}"><h2 style="margin:0;font-size:clamp(28px,2.8vw,42px);line-height:1.02;font-weight:800;letter-spacing:-.035em;text-wrap:balance">{{ $c['title'] ?? '' }}</h2></a>
            <?php if(!empty($c['hasCta'])): ?><a href="{{ $c['href'] ?? '' }}" style="align-self:flex-start;margin-top:10px;height:44px;padding:0 20px;border-radius:999px;background:{{ $c['cb'] ?? '' }};color:{{ $c['cf'] ?? '' }};font-weight:700;font-size:15px;display:flex;align-items:center;white-space:nowrap">{{ $c['cta'] ?? '' }}</a><?php endif; ?>
          </div>
          <?php if(!empty($c['isImage'])): ?><div style="flex:1;position:relative;margin-top:12px">@if(!empty($c['img']))<img src="{{ $c['img'] }}" alt="" loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">@endif</div><?php endif; ?>
          <?php if(!empty($c['isGrid'])): ?><div style="flex:1;min-height:0;display:grid;grid-template-columns:1fr 1fr;grid-template-rows:1fr 1fr;gap:10px;padding:16px"><?php foreach($c['tiles'] ?? [] as $__k => $t): ?><a href="{{ $t['href'] ?? '' }}" style="background:#FFFFFF;border-radius:16px;padding:8px;display:flex;flex-direction:column;gap:6px;color:#111827;min-height:0">@if(!empty($t['img']))<img src="{{ $t['img'] }}" alt="" loading="lazy" style="flex:1;min-height:40px;width:100%;border-radius:10px;object-fit:cover;min-width:0">@else<span style="flex:1;min-height:40px;border-radius:10px;background:{{ $t['tint'] ?? '' }};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;color:#6B7280">{{ $t['sub'] ?? '' }}</span>@endif<b style="font-size:13px;font-weight:800">{{ $t['price'] ?? '' }}</b></a><?php endforeach; ?></div><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="dc-desk">
      <button data-act="cardsPrev" aria-label="Previous" style="position:absolute;left:clamp(4px,2.4vw,24px);top:50%;margin-top:-30px;width:48px;height:60px;border-radius:12px;border:1px solid #E5E7EB;background:#FFFFFF;box-shadow:0 4px 14px rgba(0,0,0,.15);display:flex;align-items:center;justify-content:center;cursor:pointer"><span aria-hidden="true" style="display:inline-block;width:24px;height:24px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['left'] ?? '' }}"></span></button>
      <button data-act="cardsNext" aria-label="Next" style="position:absolute;right:clamp(4px,2.4vw,24px);top:50%;margin-top:-30px;width:48px;height:60px;border-radius:12px;border:1px solid #E5E7EB;background:#FFFFFF;box-shadow:0 4px 14px rgba(0,0,0,.15);display:flex;align-items:center;justify-content:center;cursor:pointer"><span aria-hidden="true" style="display:inline-block;width:24px;height:24px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['right'] ?? '' }}"></span></button>
    </div>
  </div>

  <section style="max-width:1320px;margin:0 auto;padding:40px clamp(12px,4vw,40px) 0">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:18px"><h2 style="margin:0;font-size:clamp(20px,2.4vw,26px);font-weight:800;letter-spacing:-.025em">Shop by category</h2><a href="/sitemap" style="font-size:14px;font-weight:700">All categories →</a></div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(92px,1fr));gap:20px 12px">
      <?php foreach($catTiles ?? [] as $__k => $c): ?><a href="{{ $c['href'] ?? '' }}" style="display:flex;flex-direction:column;align-items:center;gap:10px;color:#111827;text-align:center"><span style="width:100%;max-width:112px;aspect-ratio:1;border-radius:50%;position:relative;overflow:hidden;background:{{ $c['tint'] ?? '' }};display:flex;align-items:center;justify-content:center;font-size:30px;font-weight:800;color:rgba(17,24,39,.28)">{{ $c['initial'] ?? '' }}<?php if(!empty($c['img'])): ?><img src="{{ $c['img'] }}" alt="" loading="lazy" onerror="this.remove()" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover"><?php endif; ?></span><span style="font-size:13px;font-weight:700;line-height:1.3">{{ $c['name'] ?? '' }}</span></a><?php endforeach; ?>
    </div>
  </section>

  <section style="max-width:1320px;margin:0 auto;padding:40px clamp(12px,4vw,40px) 0">
    <div style="border-radius:22px;background:#FFF1E8;padding:clamp(16px,3vw,28px)">
      <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin-bottom:18px">
        <h2 style="margin:0;font-size:clamp(20px,2.4vw,26px);font-weight:800;letter-spacing:-.025em">Flash deals</h2>
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700;color:#7C2D12">Ends in<span data-cd style="display:flex;gap:4px"><?php foreach($cd ?? [] as $__k => $t): ?><span style="min-width:36px;height:30px;padding:0 6px;border-radius:8px;background:#111827;color:#FFFFFF;display:flex;align-items:center;justify-content:center;font-weight:800;font-variant-numeric:tabular-nums">{{ $t ?? '' }}</span><?php endforeach; ?></span></div>
        <a href="/category/deals" style="margin-left:auto;font-size:14px;font-weight:700">See all deals →</a>
      </div>
      <div data-rail-wrap="1" style="position:relative"><div data-rail="1" style="display:grid;grid-auto-flow:column;grid-auto-columns:minmax(160px,200px);gap:14px;overflow-x:auto;scroll-behavior:smooth;scrollbar-width:none;padding:2px 2px 6px">
        <?php foreach($deals ?? [] as $__k => $p): ?>
          <a href="{{ $p['href'] ?? '' }}" style="background:#FFFFFF;border-radius:16px;padding:10px;display:flex;flex-direction:column;gap:8px;color:#111827">
            <div style="position:relative;aspect-ratio:1;border-radius:12px;background:{{ $p['tint'] ?? '' }};display:flex;align-items:center;justify-content:center">@if(!empty($p['img']))<img src="{{ $p['img'] }}" alt="" loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:inherit">@else<span style="font-size:12px;font-weight:600;color:#6B7280">{{ $p['sub'] ?? '' }}</span>@endif<span style="position:absolute;top:8px;left:8px;height:24px;padding:0 8px;border-radius:6px;background:#DC2626;color:#FFFFFF;font-size:12px;font-weight:800;display:flex;align-items:center">{{ $p['off'] ?? '' }}</span></div>
            <span style="font-size:13px;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:35px">{{ $p['name'] ?? '' }}</span>
            <div style="display:flex;align-items:baseline;gap:6px"><b style="font-size:18px;font-weight:800;color:#B91C1C">{{ $p['price'] ?? '' }}</b><span style="font-size:12px;color:#6B7280;text-decoration:line-through">{{ $p['was'] ?? '' }}</span></div>
            <div style="height:6px;border-radius:3px;background:#FED7AA"><div style="height:6px;border-radius:3px;background:#EA580C;width:{{ $p['claimed'] ?? '' }}"></div></div>
            <span style="font-size:12px;color:#9A3412;font-weight:700">{{ $p['claimed'] ?? '' }} claimed</span>
          </a>
        <?php endforeach; ?>
      </div><div class="dc-desk"><button data-act="railPrev" aria-label="Scroll left" style="position:absolute;left:-14px;top:calc(50% - 40px);width:44px;height:44px;border-radius:50%;border:1px solid #E5E7EB;background:#FFFFFF;box-shadow:0 4px 14px rgba(17,24,39,.15);display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:2" style-hover="background:#F9FAFB"><span aria-hidden="true" style="display:inline-block;width:20px;height:20px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['left'] ?? '' }}"></span></button><button data-act="railNext" aria-label="Scroll right" style="position:absolute;right:-14px;top:calc(50% - 40px);width:44px;height:44px;border-radius:50%;border:1px solid #E5E7EB;background:#FFFFFF;box-shadow:0 4px 14px rgba(17,24,39,.15);display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:2" style-hover="background:#F9FAFB"><span aria-hidden="true" style="display:inline-block;width:20px;height:20px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['right'] ?? '' }}"></span></button></div></div>
    </div>
  </section>

  <section style="max-width:1320px;margin:0 auto;padding:28px clamp(12px,4vw,40px) 0">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(240px,100%),1fr));gap:12px">
      <?php foreach($trust ?? [] as $__k => $t): ?><div style="display:flex;gap:14px;align-items:flex-start;padding:18px;border-radius:16px;background:#F9FAFB"><span style="width:44px;height:44px;border-radius:12px;background:#FFFFFF;border:1px solid #E5E7EB;display:flex;align-items:center;justify-content:center;flex:none"><span aria-hidden="true" style="display:inline-block;flex:none;width:22px;height:22px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $t['icon'] ?? '' }}"></span></span><span style="display:flex;flex-direction:column;gap:3px"><b style="font-size:15px;font-weight:700">{{ $t['h'] ?? '' }}</b><span style="font-size:13px;color:#4B5563;line-height:1.45">{{ $t['p'] ?? '' }}</span></span></div><?php endforeach; ?>
    </div>
  </section>

  <section style="max-width:1320px;margin:0 auto;padding:44px clamp(12px,4vw,40px) 0">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:18px"><h2 style="margin:0;font-size:clamp(20px,2.4vw,26px);font-weight:800;letter-spacing:-.025em">Recommended for you</h2></div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(min(176px,44%),1fr));gap:28px 16px" data-rec>
      <?php foreach($recommended ?? [] as $__k => $p): ?>
        <a href="{{ $p['href'] ?? '' }}" style="display:flex;flex-direction:column;gap:8px;color:#111827;min-width:0">
          <div style="position:relative;aspect-ratio:1;border-radius:16px;background:{{ $p['tint'] ?? '' }};display:flex;align-items:center;justify-content:center">
            @if(!empty($p['img']))<img src="{{ $p['img'] }}" alt="" loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:inherit">@else<span style="font-size:12px;font-weight:600;color:#6B7280">{{ $p['sub'] ?? '' }}</span>@endif
            <?php if(!empty($p['hasOff'])): ?><span style="position:absolute;top:10px;left:10px;height:24px;padding:0 8px;border-radius:6px;background:#DC2626;color:#FFFFFF;font-size:12px;font-weight:800;display:flex;align-items:center">{{ $p['off'] ?? '' }}</span><?php endif; ?>
            <button data-act="wish" data-id="{{ $p['id'] ?? '' }}" aria-label="Save" style="position:absolute;top:8px;right:8px;width:38px;height:38px;border-radius:50%;border:0;background:#FFFFFF;box-shadow:0 1px 4px rgba(0,0,0,.12);font-size:18px;color:{{ $p['wishColor'] ?? '' }};cursor:pointer;display:flex;align-items:center;justify-content:center">{{ $p['wishIcon'] ?? '' }}</button>
            
          </div>
          <span style="font-size:14px;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:38px">{{ $p['name'] ?? '' }}</span>
          <div style="display:flex;align-items:baseline;gap:6px;flex-wrap:wrap"><b style="font-size:18px;font-weight:800">{{ $p['price'] ?? '' }}</b><?php if(!empty($p['hasOff'])): ?><span style="font-size:13px;color:#6B7280;text-decoration:line-through">{{ $p['was'] ?? '' }}</span><?php endif; ?></div>
          <span style="font-size:12px;color:#4B5563"><span style="color:#F59E0B">★</span> {{ $p['rating'] ?? '' }} ({{ $p['reviews'] ?? '' }})</span>
          <span style="font-size:12px;color:#4B5563;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $p['store'] ?? '' }}</span>
        </a>
      <?php endforeach; ?>
    </div>
    <div data-if="canMore" style="display:contents"><div style="display:flex;justify-content:center;margin-top:28px"><button data-act="showMore" style="height:48px;padding:0 32px;border-radius:999px;border:1.5px solid #111827;background:#FFFFFF;font-weight:700;font-size:15px;cursor:pointer">Show more</button></div></div>
  </section>

  <section style="max-width:1320px;margin:0 auto;padding:48px clamp(12px,4vw,40px) 0">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:18px"><h2 style="margin:0;font-size:clamp(20px,2.4vw,26px);font-weight:800;letter-spacing:-.025em">Top-rated stores</h2></div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(min(280px,100%),1fr));gap:16px">
      <?php foreach($storeCards ?? [] as $__k => $s): ?>
        <div style="border:1px solid #E5E7EB;border-radius:18px;overflow:hidden;display:flex;flex-direction:column">
          <div style="height:86px;background:{{ $s['tint'] ?? '' }}"></div>
          <div style="padding:0 18px 18px;display:flex;flex-direction:column;gap:10px">
            <div style="display:flex;align-items:flex-end;gap:12px;margin-top:-28px"><span style="width:60px;height:60px;border-radius:16px;background:#111827;color:#FFFFFF;border:3px solid #FFFFFF;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px">{{ $s['initial'] ?? '' }}</span><span style="height:26px;padding:0 10px;border-radius:999px;background:{{ $s['lvBg'] ?? '' }};color:{{ $s['lvFg'] ?? '' }};font-size:12px;font-weight:800;display:flex;align-items:center;margin-bottom:4px">{{ $s['level'] ?? '' }}</span></div>
            <a href="{{ $s['href'] ?? '' }}" style="color:#111827;display:flex;flex-direction:column;gap:3px"><b style="font-size:17px;font-weight:800">{{ $s['name'] ?? '' }} <span style="color:#15803D;font-size:14px">✓</span></b><span style="font-size:13px;color:#4B5563">{{ $s['city'] ?? '' }} · {{ $s['level'] ?? '' }}</span></a>
            <?php if(!empty($s['hasSale'])): ?><span style="font-size:13px;font-weight:700;color:#B91C1C">{{ $s['sale'] ?? '' }}</span><?php endif; ?>
            <div style="display:flex;gap:8px"><button data-act="s.onFollow" style="flex:1;height:42px;border-radius:999px;border:1.5px solid #111827;background:{{ $s['fBg'] ?? '' }};color:{{ $s['fFg'] ?? '' }};font-weight:700;font-size:14px;cursor:pointer">{{ $s['fLabel'] ?? '' }}</button><a href="{{ $s['href'] ?? '' }}" style="flex:1;height:42px;border-radius:999px;background:#F3F4F6;color:#111827;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px">Visit store</a></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>


  <section style="max-width:1320px;margin:0 auto;padding:48px clamp(12px,4vw,40px) 0">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(280px,100%),1fr));gap:16px">
      <a href="/my-account" style="border-radius:22px;background:#FDF2F8;padding:28px;display:flex;flex-direction:column;gap:8px;color:#111827;min-height:170px"><span style="font-size:12px;font-weight:800;letter-spacing:.14em;color:#9D174D">GIFT CARDS</span><b style="font-size:22px;font-weight:800;letter-spacing:-.02em;line-height:1.2">Give the gift of choice</b><span style="font-size:14px;color:#4B5563">Digital cards from $10, delivered by email.</span><span style="margin-top:auto;font-weight:700;color:#9D174D">Buy a gift card →</span></a>
      <a href="/my-account" style="border-radius:22px;background:#FFF7ED;padding:28px;display:flex;flex-direction:column;gap:8px;color:#111827;min-height:170px"><span style="font-size:12px;font-weight:800;letter-spacing:.14em;color:#9A3412">REWARDS</span><b style="font-size:22px;font-weight:800;letter-spacing:-.02em;line-height:1.2">Earn on every order</b><span style="font-size:14px;color:#4B5563">1 point per $1. Redeem 100 points for $1 off.</span><span style="margin-top:auto;font-weight:700;color:#9A3412">See your points →</span></a>
      <a href="/sell" style="border-radius:22px;background:#111827;padding:28px;display:flex;flex-direction:column;gap:8px;color:#FFFFFF;min-height:170px"><span style="font-size:12px;font-weight:800;letter-spacing:.14em;color:#FDBA74">SELL WITH US</span><b style="font-size:22px;font-weight:800;letter-spacing:-.02em;line-height:1.2">Keep 100% of every sale</b><span style="font-size:14px;color:#D1D5DB">0% commission · one plan · $19.99/month.</span><span style="margin-top:auto;font-weight:700;color:#FDBA74">Open your store →</span></a>
    </div>
  </section>

  <section style="max-width:1320px;margin:0 auto;padding:48px clamp(12px,4vw,40px) 0">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:18px"><h2 style="margin:0;font-size:clamp(20px,2.4vw,26px);font-weight:800;letter-spacing:-.025em">Recently viewed</h2></div>
    <div data-rail-wrap="1" style="position:relative"><div data-rail="1" style="display:grid;grid-auto-flow:column;grid-auto-columns:minmax(140px,170px);gap:14px;overflow-x:auto;scroll-behavior:smooth;scrollbar-width:none;padding:2px 2px 6px">
      <?php foreach($recentItems ?? [] as $__k => $p): ?><a href="{{ $p['href'] ?? '' }}" style="display:flex;flex-direction:column;gap:6px;color:#111827"><div style="aspect-ratio:1;border-radius:14px;background:{{ $p['tint'] ?? '' }};display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:#6B7280">{{ $p['sub'] ?? '' }}</div><span style="font-size:13px;line-height:1.35;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $p['name'] ?? '' }}</span><b style="font-size:15px;font-weight:800">{{ $p['price'] ?? '' }}</b></a><?php endforeach; ?>
    </div><div class="dc-desk"><button data-act="railPrev" aria-label="Scroll left" style="position:absolute;left:-14px;top:calc(50% - 40px);width:44px;height:44px;border-radius:50%;border:1px solid #E5E7EB;background:#FFFFFF;box-shadow:0 4px 14px rgba(17,24,39,.15);display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:2" style-hover="background:#F9FAFB"><span aria-hidden="true" style="display:inline-block;width:20px;height:20px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['left'] ?? '' }}"></span></button><button data-act="railNext" aria-label="Scroll right" style="position:absolute;right:-14px;top:calc(50% - 40px);width:44px;height:44px;border-radius:50%;border:1px solid #E5E7EB;background:#FFFFFF;box-shadow:0 4px 14px rgba(17,24,39,.15);display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:2" style-hover="background:#F9FAFB"><span aria-hidden="true" style="display:inline-block;width:20px;height:20px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['right'] ?? '' }}"></span></button></div></div>
  </section>
