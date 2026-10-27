
  <div style="max-width:1320px;margin:0 auto;padding:20px clamp(12px,4vw,40px) 0;display:flex;flex-direction:column;gap:18px">
    <div style="font-size:13px;color:#6B7280;display:flex;gap:8px;flex-wrap:wrap"><a href="/" style="color:#4B5563">Home</a><span>/</span><span style="color:#111827;font-weight:600">{{ $list['crumb'] ?? '' }}</span></div>
    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:16px;flex-wrap:wrap">
      <div style="display:flex;flex-direction:column;gap:4px"><span style="font-size:12px;font-weight:800;letter-spacing:.14em;color:#C2410C">{{ $list['kicker'] ?? '' }}</span><h1 style="margin:0;font-size:clamp(26px,3.4vw,38px);font-weight:800;letter-spacing:-.03em">{{ $list['title'] ?? '' }}</h1><span style="font-size:14px;color:#4B5563">{{ $list['count'] ?? '' }}</span></div>
      <div class="dc-desk"><label style="display:flex;align-items:center;gap:10px;font-size:14px;font-weight:600">Sort by<select data-nav aria-label="Sort by" style="height:44px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:14px;background:#FFFFFF"><?php foreach($list['sorts'] ?? [] as $__k => $o): ?><option value="{{ $o['url'] }}" {{ $o['selected'] ? 'selected' : '' }}>{{ $o['label'] }}</option><?php endforeach; ?></select></label></div>
    </div>
    <?php if(!empty($list['hasSubs'])): ?><div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:2px"><?php foreach($list['subs'] ?? [] as $__k => $s): ?><a href="{{ $s['href'] ?? '' }}" style="flex:none;height:40px;padding:0 18px;border-radius:999px;border:1px solid {{ $s['bd'] ?? '' }};background:{{ $s['bg'] ?? '' }};color:{{ $s['fg'] ?? '' }};font-size:14px;font-weight:600;display:flex;align-items:center;white-space:nowrap">{{ $s['name'] ?? '' }}</a><?php endforeach; ?></div><?php endif; ?>
    <div class="dc-mob"><div style="display:grid;grid-template-columns:1fr 1fr;gap:8px"><button data-act="openSheet" style="height:44px;border-radius:12px;border:1px solid #D1D5DB;background:#FFFFFF;font-weight:700;font-size:14px">Filters</button><select data-nav aria-label="Sort by" style="height:44px;border-radius:12px;border:1px solid #D1D5DB;padding:0 12px;font-size:14px;font-weight:600;background:#FFFFFF"><?php foreach($list['sorts'] ?? [] as $__k => $o): ?><option value="{{ $o['url'] }}" {{ $o['selected'] ? 'selected' : '' }}>{{ $o['label'] }}</option><?php endforeach; ?></select></div></div>
    <div style="display:flex;gap:32px;align-items:flex-start">
      <div class="dc-desk">
        <aside style="flex:0 0 240px;display:flex;flex-direction:column;gap:16px;position:sticky;top:140px">
          <b style="font-size:15px;font-weight:800">Filters</b>
          <?php foreach($list['filters'] ?? [] as $__k => $f): ?><a href="{{ $f["href"] }}" style="display:flex;align-items:center;gap:12px;border:0;background:transparent;padding:0;font-size:14px;cursor:pointer;text-align:left;min-height:32px"><span style="width:22px;height:22px;border-radius:6px;border:1.5px solid {{ $f['bd'] ?? '' }};background:{{ $f['bg'] ?? '' }};color:#FFFFFF;font-size:13px;font-weight:800;display:flex;align-items:center;justify-content:center;flex:none">{{ $f['mark'] ?? '' }}</span>{{ $f['label'] ?? '' }}</a><?php endforeach; ?>
          <div style="border-top:1px solid #E5E7EB;padding-top:16px;display:flex;flex-direction:column;gap:10px"><b style="font-size:14px;font-weight:700">Ships to</b><span style="font-size:14px;color:#4B5563">{{ $locLabel ?? '' }} · <button data-act="openLoc" style="border:0;background:transparent;padding:0;color:#C2410C;font-weight:700;font-size:14px;cursor:pointer">Change</button></span></div>
          <div style="border-top:1px solid #E5E7EB;padding-top:16px;display:flex;flex-direction:column;gap:6px"><span style="display:flex;gap:8px;align-items:center;font-size:13px;font-weight:700"><span aria-hidden="true" style="display:inline-block;flex:none;width:18px;height:18px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['shield'] ?? '' }}"></span>Buyer Protection</span><span style="font-size:13px;color:#4B5563;line-height:1.45">Every item here is covered if it doesn't arrive or isn't as described.</span></div>
          <a href="{{ $list["clear"] }}" style="align-self:flex-start;border:0;background:transparent;padding:0;font-size:14px;font-weight:700;color:#C2410C;cursor:pointer;display:flex;align-items:center;justify-content:center">Clear filters</a>
        </aside>
      </div>
      <div style="flex:1;min-width:0">
        <?php if(!empty($list['some'])): ?>
          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(min(176px,44%),1fr));gap:28px 16px">
            <?php foreach($list['items'] ?? [] as $__k => $p): ?>
              <a href="{{ $p['href'] ?? '' }}" style="display:flex;flex-direction:column;gap:8px;color:#111827;min-width:0">
                <div style="position:relative;aspect-ratio:1;border-radius:16px;background:{{ $p['tint'] ?? '' }};display:flex;align-items:center;justify-content:center">
                  @if(!empty($p['img']))<img src="{{ $p['img'] }}" alt="" loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:inherit">@else<span style="font-size:12px;font-weight:600;color:#6B7280">{{ $p['sub'] ?? '' }}</span>@endif
                  <?php if(!empty($p['hasOff'])): ?><span style="position:absolute;top:10px;left:10px;height:24px;padding:0 8px;border-radius:6px;background:#DC2626;color:#FFFFFF;font-size:12px;font-weight:800;display:flex;align-items:center">{{ $p['off'] ?? '' }}</span><?php endif; ?>
                  <button data-act="wish" data-id="{{ $p['id'] ?? '' }}" aria-label="Save" style="position:absolute;top:8px;right:8px;width:38px;height:38px;border-radius:50%;border:0;background:#FFFFFF;box-shadow:0 1px 4px rgba(0,0,0,.12);font-size:18px;color:{{ $p['wishColor'] ?? '' }};cursor:pointer;display:flex;align-items:center;justify-content:center">{{ $p['wishIcon'] ?? '' }}</button>
                  
                </div>
                <span style="font-size:14px;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:38px">{{ $p['name'] ?? '' }}</span>
                <div style="display:flex;align-items:baseline;gap:6px;flex-wrap:wrap"><b style="font-size:18px;font-weight:800">{{ $p['price'] ?? '' }}</b><?php if(!empty($p['hasOff'])): ?><span style="font-size:13px;color:#6B7280;text-decoration:line-through">{{ $p['was'] ?? '' }}</span><?php endif; ?></div>
                <span style="font-size:12px;color:#4B5563"><span style="color:#F59E0B">★</span> {{ $p['rating'] ?? '' }} ({{ $p['reviews'] ?? '' }})</span>
                <span style="font-size:12px;color:#4B5563;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $p['store'] ?? '' }}</span><?php if(!empty($p["sold"])): ?><span style="font-size:12px;color:#4B5563">{{ $p["sold"] }}</span><?php endif; ?><?php if(!empty($p["sponsored"])): ?><span style="font-size:11px;font-weight:700;color:#6B7280">Sponsored</span><?php endif; ?>
              </a>
            <?php endforeach; ?>
          </div>
{!! $list["pager"] !!}
        <?php endif; ?>
        <?php if(!empty($list['none'])): ?>
          <div style="border-radius:20px;background:#F9FAFB;padding:48px 24px;display:flex;flex-direction:column;align-items:center;gap:12px;text-align:center">
            <b style="font-size:22px;font-weight:800">No matches yet</b>
            <span style="font-size:15px;color:#4B5563;max-width:420px;line-height:1.5">Try removing a filter or searching a broader term. New sellers join every week.</span>
            <a href="{{ $list["clear"] }}" style="height:46px;padding:0 24px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center">Clear filters</a>
          </div>
          <h2 style="margin:36px 0 16px;font-size:20px;font-weight:800">Popular right now</h2>
          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(min(170px,44%),1fr));gap:20px 16px"><?php foreach($list['suggestions'] ?? [] as $__k => $p): ?><a href="{{ $p['href'] ?? '' }}" style="display:flex;flex-direction:column;gap:6px;color:#111827"><div style="aspect-ratio:1;border-radius:16px;background:{{ $p['tint'] ?? '' }};display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:#6B7280">{{ $p['sub'] ?? '' }}</div><span style="font-size:14px;line-height:1.35">{{ $p['name'] ?? '' }}</span><b style="font-size:16px;font-weight:800">{{ $p['price'] ?? '' }}</b></a><?php endforeach; ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div data-if="list.sheet" style="display:contents" hidden>
    <div style="position:fixed;inset:0;z-index:70;background:rgba(17,24,39,.5);display:flex;align-items:flex-end">
      <div style="width:100%;background:#FFFFFF;border-radius:20px 20px 0 0;padding:20px 16px 24px;display:flex;flex-direction:column;gap:16px">
        <div style="display:flex;justify-content:space-between;align-items:center"><b style="font-size:18px;font-weight:800">Filters</b><button data-act="closeSheet" aria-label="Close" style="width:44px;height:44px;border:0;background:#F3F4F6;border-radius:50%;display:flex;align-items:center;justify-content:center"><span aria-hidden="true" style="display:inline-block;flex:none;width:20px;height:20px;background-position:center;background-size:contain;background-repeat:no-repeat;background-image:{{ $ic['close'] ?? '' }}"></span></button></div>
        <?php foreach($list['filters'] ?? [] as $__k => $f): ?><a href="{{ $f["href"] }}" style="display:flex;align-items:center;gap:12px;border:0;background:transparent;padding:0;font-size:15px;min-height:44px;text-align:left"><span style="width:24px;height:24px;border-radius:6px;border:1.5px solid {{ $f['bd'] ?? '' }};background:{{ $f['bg'] ?? '' }};color:#FFFFFF;font-weight:800;display:flex;align-items:center;justify-content:center">{{ $f['mark'] ?? '' }}</span>{{ $f['label'] ?? '' }}</a><?php endforeach; ?>
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:8px"><a href="{{ $list["clear"] }}" style="height:50px;border-radius:999px;border:1.5px solid #111827;background:#FFFFFF;font-weight:700;display:flex;align-items:center;justify-content:center">Clear</a><button data-act="closeSheet" style="height:50px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:800">Show {{ $list['count'] ?? '' }}</button></div>
      </div>
    </div>
  </div>
