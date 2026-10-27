
          <div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start">
            <div style="flex:1 1 380px;min-width:0;background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:12px">
              <b style="font-size:16px">Create a coupon</b>
              <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(140px,100%),1fr));gap:10px">
                <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Code<input value="{{ $cCode ?? '' }}" style="height:46px;border-radius:12px;border:1px solid #D1D5DB;padding:0 12px;font-size:15px;font-weight:700;outline:0;letter-spacing:.04em"></label>
                <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Discount<select value="{{ $cType ?? '' }}" style="height:46px;border-radius:12px;border:1px solid #D1D5DB;padding:0 10px;font-size:15px;background:#FFFFFF"><option>% off</option><option>$ off</option><option>Free shipping</option></select></label>
                <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Amount<input value="{{ $cVal ?? '' }}" style="height:46px;border-radius:12px;border:1px solid #D1D5DB;padding:0 12px;font-size:15px;outline:0"></label>
                <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Min. order ($)<input value="{{ $cMin ?? '' }}" style="height:46px;border-radius:12px;border:1px solid #D1D5DB;padding:0 12px;font-size:15px;outline:0"></label>
              </div>
              <button data-act="createCoupon" style="align-self:flex-start;height:46px;padding:0 22px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:800;cursor:pointer">Create coupon</button>
              <div style="border:1px solid #E5E7EB;border-radius:14px;overflow:hidden"><?php foreach($coupons ?? [] as $__k => $c): ?><div style="display:flex;justify-content:space-between;gap:12px;padding:12px 14px;border-bottom:1px solid #F3F4F6;flex-wrap:wrap;font-size:14px"><span style="display:flex;flex-direction:column"><b style="letter-spacing:.04em">{{ $c['code'] ?? '' }}</b><span style="font-size:12px;color:#4B5563">{{ $c['type'] ?? '' }} · {{ $c['min'] ?? '' }} · ends {{ $c['ends'] ?? '' }}</span></span><span style="font-size:13px;color:#4B5563">{{ $c['uses'] ?? '' }}</span></div><?php endforeach; ?></div>
            </div>
            <div style="flex:1 1 320px;min-width:0;display:flex;flex-direction:column;gap:16px">
              <div style="background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:12px">
                <div style="display:flex;justify-content:space-between;align-items:center"><b style="font-size:16px">Promoted listings</b><button data-act="togglePromo" aria-label="Toggle ads" style="width:48px;height:28px;border-radius:999px;border:0;background:{{ $promoBg ?? '' }};position:relative;cursor:pointer"><span style="position:absolute;top:2px;left:{{ $promoX ?? '' }};width:24px;height:24px;border-radius:50%;background:#FFFFFF;transition:left .2s"></span></button></div>
                <span style="font-size:14px;color:#4B5563;line-height:1.5">Show your products at the top of search and category pages. You only pay when a shopper clicks.</span>
                <label style="display:flex;flex-direction:column;gap:8px;font-size:13px;font-weight:600">Daily budget: <b style="font-size:20px">{{ $promoBudget ?? '' }}</b><input type="range" min="1" max="50" value="{{ $promoRaw ?? '' }}" style="accent-color:#C2410C"></label>
                <span style="font-size:13px;color:#374151">Estimated reach: ~{{ $promoReach ?? '' }} shoppers / day</span>
              </div>
              <div style="background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:12px">
                <div style="display:flex;justify-content:space-between;align-items:center"><b style="font-size:16px">Store-wide sale</b><button data-act="toggleSale" aria-label="Toggle sale" style="width:48px;height:28px;border-radius:999px;border:0;background:{{ $saleBg ?? '' }};position:relative;cursor:pointer"><span style="position:absolute;top:2px;left:{{ $saleX ?? '' }};width:24px;height:24px;border-radius:50%;background:#FFFFFF;transition:left .2s"></span></button></div>
                <label style="display:flex;align-items:center;gap:10px;font-size:14px;font-weight:600">Discount<input value="{{ $salePct ?? '' }}" style="width:70px;height:42px;border-radius:10px;border:1px solid #D1D5DB;padding:0 10px;font-size:15px;outline:0">% off everything</label>
                <span style="font-size:13px;font-weight:700;color:#15803D">{{ $saleText ?? '' }}</span>
                <span style="font-size:12px;color:#6B7280">Bundle deals (e.g. "Buy 2, save 10%") can be set per product.</span>
              </div>
            </div>
          </div>
        