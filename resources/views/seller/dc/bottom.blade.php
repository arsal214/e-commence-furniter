
      </main>
    </div>
  </div>
  <div data-if="menu" style="display:contents" hidden>
    <div style="position:fixed;inset:0;z-index:60;display:flex">
      <div style="width:min(300px,85vw);height:100%;background:#111827;padding:16px 12px;display:flex;flex-direction:column;gap:4px;overflow-y:auto">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:0 6px 14px"><b style="color:#FFFFFF">Seller Center</b><button data-act="closeMenu" aria-label="Close" style="width:44px;height:44px;border:0;background:transparent;color:#FFFFFF;font-size:22px">×</button></div>
        <?php foreach($nav ?? [] as $__k => $n): ?><a href="{{ $n['href'] ?? '' }}" style="height:46px;padding:0 12px;border-radius:10px;background:{{ $n['bg'] ?? '' }};color:{{ $n['fg'] ?? '' }};font-weight:{{ $n['fw'] ?? '' }};font-size:15px;display:flex;align-items:center;justify-content:space-between">{{ $n['label'] ?? '' }}<?php if(!empty($n['hasBadge'])): ?><span style="min-width:22px;height:22px;border-radius:999px;background:#EA580C;color:#111827;font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center">{{ $n['badge'] ?? '' }}</span><?php endif; ?></a><?php endforeach; ?>
        <a href="/store/0" style="height:46px;padding:0 12px;color:#FDBA74;font-weight:700;display:flex;align-items:center">View my store →</a>
      </div>
      <div data-act="closeMenu" style="flex:1;background:rgba(17,24,39,.5)"></div>
    </div>
  </div>
  <div data-if="hasToast" style="display:contents" hidden><div style="position:fixed;left:50%;bottom:24px;transform:translateX(-50%);z-index:80;background:#111827;color:#FFFFFF;border-radius:999px;padding:12px 18px;font-size:14px;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,.25);max-width:calc(100vw - 32px)">{{ $toast ?? '' }}</div></div>
