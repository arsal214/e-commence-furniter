
  <div style="max-width:1180px;margin:0 auto;padding:32px clamp(16px,4vw,40px) 0;display:flex;gap:48px;align-items:flex-start">
    <div class="dc-desk"><nav style="flex:0 0 240px;display:flex;flex-direction:column;gap:16px;position:sticky;top:140px;max-height:calc(100vh - 160px);overflow-y:auto"><?php foreach($docNav ?? [] as $__k => $g): ?><div style="display:flex;flex-direction:column;gap:2px"><span style="font-size:11px;font-weight:800;letter-spacing:.12em;color:#6B7280;padding:0 10px 4px">{{ $g['g'] ?? '' }}</span><?php foreach($g['items'] ?? [] as $__k => $i): ?><a href="{{ $i['href'] ?? '' }}" style="padding:8px 10px;border-radius:10px;background:{{ $i['bg'] ?? '' }};color:#111827;font-size:14px;font-weight:{{ $i['fw'] ?? '' }}">{{ $i['t'] ?? '' }}</a><?php endforeach; ?></div><?php endforeach; ?></nav></div>
    <article style="flex:1;min-width:0;max-width:760px;display:flex;flex-direction:column;gap:18px">
      <span style="font-size:12px;font-weight:800;letter-spacing:.14em;color:#C2410C">{{ $doc['group'] ?? '' }}</span>
      <h1 style="margin:0;font-size:clamp(28px,4vw,42px);font-weight:800;letter-spacing:-.03em;line-height:1.1">{{ $doc['title'] ?? '' }}</h1>
      <?php if(!empty($doc["legal"])): ?><span style="font-size:13px;color:#4B5563">Last updated {{ $doc["updated"] }}</span><?php endif; ?>
      <p style="margin:0;font-size:17px;line-height:1.65;color:#374151;text-wrap:pretty">{{ $doc['intro'] ?? '' }}</p>
      <?php foreach($doc['sections'] ?? [] as $__k => $s): ?><section style="display:flex;flex-direction:column;gap:8px;padding-top:8px"><h2 style="margin:0;font-size:19px;font-weight:800;letter-spacing:-.01em">{{ $s['h'] ?? '' }}</h2><p style="margin:0;font-size:15px;line-height:1.7;color:#374151;text-wrap:pretty">{{ $s['p'] ?? '' }}</p></section><?php endforeach; ?>
      <?php if(!empty($doc['contact'])): ?>
        <div style="border:1px solid #E5E7EB;border-radius:20px;padding:20px;display:flex;flex-direction:column;gap:12px;margin-top:8px">
          <b style="font-size:18px;font-weight:800">Send us a message</b>
          <form data-contact method="POST" action="{{ route("contact.send") }}" style="display:contents">@csrf<span data-contact-msg role="alert" hidden style="font-size:14px;font-weight:700"></span>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(220px,100%),1fr));gap:12px"><label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Name<input name="name" required maxlength="255" autocomplete="name" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0"></label><label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Email<input name="email" type="email" required maxlength="255" autocomplete="email" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0"></label></div>
            <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Topic<select name="subject" style="height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 12px;font-size:15px;background:#FFFFFF"><option>An order</option><option>A return or refund</option><option>My account</option><option>Selling on Peyton Ghalib</option><option>Something else</option></select></label>
            <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Message<textarea name="Message" rows="4" required maxlength="5000" style="border-radius:12px;border:1px solid #D1D5DB;padding:12px 14px;font-size:15px;outline:0;resize:vertical"></textarea></label>
            <button type="submit" style="align-self:flex-start;height:48px;padding:0 24px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:800;cursor:pointer">Send message</button></form>
        </div>
      <?php endif; ?>
      <?php if(!empty($doc['ccpa'])): ?>
        <div style="border:1px solid #E5E7EB;border-radius:20px;padding:20px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap"><span style="display:flex;flex-direction:column;gap:2px"><b style="font-size:16px">Opt out of sale/sharing</b><span style="font-size:13px;color:#4B5563">Off: advertising cookies may be used. On: they are switched off for this browser.</span></span><button type="button" data-act="toggleCcpa" aria-pressed="false" aria-label="Toggle opt-out" style="width:52px;height:30px;border-radius:999px;border:0;background:#D1D5DB;position:relative;cursor:pointer"><span style="position:absolute;top:3px;left:3px;width:24px;height:24px;border-radius:50%;background:#FFFFFF;transition:left .2s"></span></button></div>
      <?php endif; ?>
    </article>
  </div>
