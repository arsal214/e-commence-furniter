
  <div style="display:flex;min-height:100vh;background:#F9FAFB">
    <div class="dc-desk">
      <aside style="flex:0 0 248px;background:#111827;color:#D1D5DB;position:sticky;top:0;height:100vh;overflow-y:auto;display:flex;flex-direction:column;padding:18px 14px;gap:4px">
        <a href="/seller" style="display:flex;align-items:center;gap:10px;color:#FFFFFF;padding:0 8px 18px"><span style="width:36px;height:36px;border-radius:10px;background:#EA580C;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:14px">PG</span><span style="display:flex;flex-direction:column;line-height:1.1"><b style="font-size:15px">Seller Center</b><span style="font-size:12px;color:#9CA3AF">{{ $storeName ?? '' }}</span></span></a>
        <?php foreach($nav ?? [] as $__k => $n): ?><a href="{{ $n['href'] ?? '' }}" style="height:42px;padding:0 12px;border-radius:10px;background:{{ $n['bg'] ?? '' }};color:{{ $n['fg'] ?? '' }};font-weight:{{ $n['fw'] ?? '' }};font-size:14px;display:flex;align-items:center;justify-content:space-between">{{ $n['label'] ?? '' }}<?php if(!empty($n['hasBadge'])): ?><span style="min-width:22px;height:22px;border-radius:999px;background:#EA580C;color:#111827;font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center">{{ $n['badge'] ?? '' }}</span><?php endif; ?></a><?php endforeach; ?>
        <div style="margin-top:auto;padding:14px 10px 0;border-top:1px solid #1F2937;display:flex;flex-direction:column;gap:10px;font-size:13px"><a href="/" style="color:#FDBA74;font-weight:700">View marketplace →</a><a href="{{ url('/my-account/settings') }}" style="color:#D1D5DB">Account settings</a><button data-post="/logout" style="color:#9CA3AF;background:none;border:0;padding:0;text-align:left;cursor:pointer;font:inherit">Sign out</button></div>
      </aside>
    </div>
    <div style="flex:1;min-width:0;display:flex;flex-direction:column">
      <div style="position:sticky;top:0;z-index:20;background:#FFFFFF;border-bottom:1px solid #E5E7EB;padding:10px clamp(12px,3vw,32px);display:flex;align-items:center;gap:12px;min-height:64px">
        <div class="dc-mob"><button data-act="openMenu" aria-label="Menu" style="width:44px;height:44px;border-radius:12px;border:1px solid #E5E7EB;background:#FFFFFF;font-size:18px;cursor:pointer">☰</button></div>
        <b style="font-size:18px;font-weight:800;letter-spacing:-.02em">{{ $pageTitle ?? '' }}</b>
        <div style="margin-left:auto;display:flex;align-items:center;gap:8px">
          <div class="dc-desk"><a href="{{ $storeUrl ?? '/' }}" style="height:40px;padding:0 14px;border-radius:999px;border:1px solid #E5E7EB;color:#111827;font-size:13px;font-weight:700;display:flex;align-items:center;white-space:nowrap;flex:none">View my store</a></div>
          <a href="/seller/products/create" style="height:40px;padding:0 16px;border-radius:999px;background:#C2410C;color:#FFFFFF;font-size:13px;font-weight:800;display:flex;align-items:center;white-space:nowrap;flex:none">+ Add product</a>
          <span style="width:40px;height:40px;border-radius:50%;background:#111827;color:#FFFFFF;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:14px">{{ $initials ?? '' }}</span>
        </div>
      </div>
      <?php if(!empty($pending)): ?><div style="margin:16px clamp(12px,3vw,32px) 0;padding:14px 16px;border-radius:14px;background:#FEF3C7;color:#78350F;font-size:14px;display:flex;gap:12px;align-items:center;flex-wrap:wrap"><span style="flex:1 1 260px"><b>Your application is in review.</b> Your store and products go live as soon as we approve your documents (usually 1–2 business days).</span></div><?php endif; ?>
      <?php if(!empty($billingFailed)): ?><div style="margin:16px clamp(12px,3vw,32px) 0;padding:14px 16px;border-radius:14px;background:#FEE2E2;color:#7F1D1D;font-size:14px;display:flex;gap:12px;align-items:center;flex-wrap:wrap"><span style="flex:1 1 260px"><b>Your store is hidden.</b> We couldn't charge your $19.99 seller plan. Shoppers can't see your products until the payment goes through.</span><a href="/seller/billing" style="height:40px;padding:0 18px;border-radius:999px;border:0;background:#B91C1C;color:#FFFFFF;font-weight:800;cursor:pointer">Update card &amp; pay</a></div><?php endif; ?>
      <?php if(!empty($vacation)): ?><div style="margin:16px clamp(12px,3vw,32px) 0;padding:14px 16px;border-radius:14px;background:#DBEAFE;color:#1E3A8A;font-size:14px"><b>Vacation mode is on.</b> Shoppers see: "{{ $vacMsg ?? '' }}" <a href="/seller/settings" style="color:#1E3A8A;font-weight:700">Turn off</a></div><?php endif; ?>
      <main style="padding:20px clamp(12px,3vw,32px) 48px;display:flex;flex-direction:column;gap:20px">

        