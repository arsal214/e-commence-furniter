
          <div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start">
            <div style="flex:2 1 460px;min-width:0;background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:14px">
              <b style="font-size:16px">Branding</b>
              <span style="font-size:13px;font-weight:600">Store banner · 1600 × 400</span>
              <div style="position:relative;height:160px;border-radius:14px;overflow:hidden;background:#F3F4F6">@if(!empty($c['img']))<img src="{{ $c['img'] }}" alt="" loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">@endif</div>
              <div style="display:flex;gap:14px;align-items:center;flex-wrap:wrap"><div style="position:relative;width:96px;height:96px;border-radius:24px;overflow:hidden;background:#F3F4F6;flex:none">@if(!empty($c['img']))<img src="{{ $c['img'] }}" alt="" loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">@endif</div><span style="font-size:13px;color:#4B5563">Square logo, at least 400 × 400. Shown on your store, products and messages.</span></div>
              <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Store name<input value="Harbor &amp; Hide Goods" style="height:46px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;outline:0"></label>
              <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">About your store<textarea rows="4" style="border-radius:12px;border:1px solid #D1D5DB;padding:12px 14px;font-size:15px;line-height:1.5;outline:0;resize:vertical">Small-batch leather goods, hand-cut and stitched in our Savannah, Georgia workshop since 2018.</textarea></label>
              <a href="/store/0" style="align-self:flex-start;height:44px;padding:0 18px;border-radius:999px;border:1.5px solid #111827;color:#111827;font-weight:700;font-size:14px;display:flex;align-items:center">Preview store</a>
            </div>
            <div style="flex:1 1 300px;min-width:0;background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:12px">
              <div style="display:flex;justify-content:space-between;align-items:center"><b style="font-size:16px">Vacation mode</b><button data-act="toggleVac" aria-label="Vacation mode" style="width:48px;height:28px;border-radius:999px;border:0;background:{{ $vacBg ?? '' }};position:relative;cursor:pointer"><span style="position:absolute;top:2px;left:{{ $vacX ?? '' }};width:24px;height:24px;border-radius:50%;background:#FFFFFF;transition:left .2s"></span></button></div>
              <span style="font-size:14px;color:#4B5563;line-height:1.5">Pauses new orders and shows shoppers a message. Your listings, reviews and level are kept.</span>
              <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Message to shoppers<textarea value="{{ $vacMsg ?? '' }}" rows="3" style="border-radius:12px;border:1px solid #D1D5DB;padding:10px 14px;font-size:14px;outline:0;resize:vertical"></textarea></label>
            </div>
          </div>
        