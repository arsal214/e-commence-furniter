
        <a href="/admin/sellers" style="font-size:13px;font-weight:700">← Approval queue</a>
        <div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start">
          <div style="flex:2 1 460px;min-width:0;display:flex;flex-direction:column;gap:16px">
            <div style="background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:12px">
              <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:center"><b style="font-size:20px;font-weight:800">{{ $app['name'] ?? '' }}</b><span style="height:28px;padding:0 12px;border-radius:999px;background:{{ $app['bg'] ?? '' }};color:{{ $app['fg'] ?? '' }};font-size:12px;font-weight:800;display:flex;align-items:center">{{ $app['status'] ?? '' }}</span></div>
              <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(220px,100%),1fr));gap:2px 20px"><?php foreach($app['fields'] ?? [] as $__k => $f): ?><div style="display:flex;flex-direction:column;padding:10px 0;border-bottom:1px solid #F3F4F6"><span style="font-size:12px;color:#6B7280">{{ $f['k'] ?? '' }}</span><b style="font-size:14px;font-weight:600">{{ $f['v'] ?? '' }}</b></div><?php endforeach; ?></div>
            </div>
            <div style="background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:12px"><b style="font-size:16px">Documents</b><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(180px,100%),1fr));gap:12px"><?php foreach($app['docs'] ?? [] as $__k => $d): ?><div style="display:flex;flex-direction:column;gap:6px"><a href="{{ $d['href'] ?? '' }}" target="_blank" rel="noopener" style="position:relative;aspect-ratio:4/3;border-radius:12px;overflow:hidden;background:#F3F4F6;display:flex;align-items:center;justify-content:center;color:#374151;font-size:13px;font-weight:700">{{ $d['open'] ?? '' }}</a><span style="font-size:13px;font-weight:700">{{ $d['t'] ?? '' }}</span><span style="font-size:12px;color:{{ $d['c'] ?? '' }};font-weight:600">{{ $d['s'] ?? '' }}</span></div><?php endforeach; ?></div></div>
          </div>
          <div style="flex:1 1 300px;min-width:0;display:flex;flex-direction:column;gap:16px;position:sticky;top:84px">
            <div style="background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:18px;display:flex;flex-direction:column;gap:10px"><b>Automated checks</b><?php foreach($app['checks'] ?? [] as $__k => $c): ?><span style="display:flex;gap:10px;font-size:14px"><b style="color:{{ $c['c'] ?? '' }};width:16px">{{ $c['m'] ?? '' }}</b>{{ $c['t'] ?? '' }}</span><?php endforeach; ?></div>
            <?php if(!empty($app['canDecide'])): ?><div style="background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:18px;display:flex;flex-direction:column;gap:10px">
              <b>Decision</b>
              <?php if(!empty($app['blockers'])): ?><span style="font-size:12px;color:#92400E;background:#FEF3C7;border-radius:10px;padding:8px 10px">Cannot approve yet: {{ $app['blockers'] ?? '' }}.</span><?php endif; ?>
              <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Note to seller (sent by email)<textarea name="note" rows="3" placeholder="Required when rejecting or requesting info" style="border-radius:12px;border:1px solid #D1D5DB;padding:10px 12px;font-size:14px;outline:0;resize:vertical"></textarea></label>
              <button data-post="{{ $app['postUrl'] ?? '' }}" data-field-action="approve" style="height:48px;border-radius:999px;border:0;background:#15803D;color:#FFFFFF;font-weight:800;cursor:pointer">Approve seller</button>
              <button data-post="{{ $app['postUrl'] ?? '' }}" data-field-action="request_info" style="height:44px;border-radius:999px;border:1.5px solid #111827;background:#FFFFFF;font-weight:700;cursor:pointer">Request more info</button>
              <button data-post="{{ $app['postUrl'] ?? '' }}" data-field-action="reject" style="height:44px;border-radius:999px;border:1.5px solid #B91C1C;background:#FFFFFF;color:#B91C1C;font-weight:700;cursor:pointer">Reject</button>
            </div><?php endif; ?>
            <?php if(!empty($app['decided'])): ?><div style="background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:18px;display:flex;flex-direction:column;gap:6px;font-size:14px"><b>Decided: {{ $app['status'] ?? '' }}</b><span style="color:#4B5563">{{ $app['note'] ?? '' }}</span></div><?php endif; ?>
          </div>
        </div>
      