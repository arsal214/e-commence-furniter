
          <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center"><span style="font-size:14px;color:#4B5563">{{ $liveCount ?? '' }} live · {{ $pendingCount ?? '' }} in review · new listings are reviewed before going live</span><div style="margin-left:auto;display:flex;gap:8px"><button data-act="toggleCsv" style="height:42px;padding:0 16px;border-radius:999px;border:1.5px solid #111827;background:#FFFFFF;font-weight:700;font-size:13px;cursor:pointer">Bulk upload (CSV)</button><a href="/seller/products/create" style="height:42px;padding:0 16px;border-radius:999px;background:#C2410C;color:#FFFFFF;font-weight:800;font-size:13px;display:flex;align-items:center">+ Add product</a></div></div>
          <?php if(!empty($csv)): ?>
            <div style="background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:18px;display:flex;flex-direction:column;gap:12px">
              <b style="font-size:16px">Bulk upload products</b>
              <span style="font-size:14px;color:#4B5563;line-height:1.5">1. Download the template · 2. Fill one row per variant (title, category, price, stock, SKU, option values, weight, image URLs) · 3. Upload. Up to 5,000 rows per file.</span>
              <div style="display:flex;gap:10px;flex-wrap:wrap"><a href="/seller/products" style="height:42px;padding:0 16px;border-radius:999px;border:1.5px solid #D1D5DB;color:#111827;font-weight:700;font-size:13px;display:flex;align-items:center">Download CSV template</a></div>
              <?php if(!empty($csvNot)): ?><button data-act="runCsv" style="min-height:90px;border-radius:16px;border:1.5px dashed #9CA3AF;background:#F9FAFB;font-weight:700;color:#374151;cursor:pointer">Drop your CSV here or click to upload</button><?php endif; ?>
              <?php if(!empty($csvDone)): ?><div style="padding:14px;border-radius:14px;background:#F0FDF4;color:#14532D;font-size:14px;line-height:1.5"><b>harbor-hide-fall.csv</b> — 48 rows checked: 46 ready, 2 need attention (missing weight on rows 12 and 31). <a href="/seller/products">Review &amp; import 46</a></div><?php endif; ?>
            </div>
          <?php endif; ?>
          <div style="background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;overflow:hidden">
            <?php foreach($rows ?? [] as $__k => $p): ?>
              <div style="display:flex;gap:14px;padding:14px 18px;border-bottom:1px solid #F3F4F6;align-items:center;flex-wrap:wrap">
                <span style="width:56px;height:56px;border-radius:12px;background:#F6EBDD;flex:none"></span>
                <span style="flex:1 1 220px;display:flex;flex-direction:column;gap:2px;min-width:0"><b style="font-size:14px">{{ $p['name'] ?? '' }}</b><span style="font-size:12px;color:#6B7280">{{ $p['sku'] ?? '' }} · {{ $p['variants'] ?? '' }}</span><?php if(!empty($p['hasReason'])): ?><span style="font-size:12px;color:#B91C1C;font-weight:600">{{ $p['reason'] ?? '' }}</span><?php endif; ?></span>
                <span style="flex:0 0 100px;font-size:14px;font-weight:700">{{ $p['price'] ?? '' }}</span>
                <span style="flex:0 0 110px;font-size:13px;font-weight:600;color:{{ $p['stockColor'] ?? '' }}">{{ $p['stock'] ?? '' }}</span>
                <span style="flex:0 0 90px;font-size:13px;color:#4B5563">{{ $p['sold'] ?? '' }} sold</span>
                <span style="flex:0 0 auto;height:26px;padding:0 10px;border-radius:999px;background:{{ $p['sBg'] ?? '' }};color:{{ $p['sFg'] ?? '' }};font-size:12px;font-weight:800;display:flex;align-items:center">{{ $p['status'] ?? '' }}</span>
                <a href="/seller/products/create" style="font-size:13px;font-weight:700">Edit</a>
              </div>
            <?php endforeach; ?>
          </div>
        