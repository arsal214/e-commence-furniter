r"""Marketplace design: makes the login and account pages real (forms, this shopper's data, no prototype text).

Entries are (page, regex, replacement[, count]). A pattern '@block:flag' swaps a whole
<?php if(!empty($flag)): ?> ... <?php endif; ?> block. A pattern that stops matching is reported by dc-transpile.py.
"""

NO_STYLE = r'( style="[^"]*")'
BTN = 'display:flex;align-items:center;justify-content:center;color:#111827;font-size:13.3px;'

PATCHES = [
    # ── the payment processor is never named to shoppers ──
    ('checkout', r'Encrypted · processed by Stripe', 'Encrypted · processed securely'),
    ('cart', r'Secure payments by Stripe', 'Secure payments'),
    # ── header: a store owner sees Seller Center where a shopper sees Account and "Sell on Peyton Ghalib" ──
    ('top', r'<a href="/my-account" (style="[^"]*" aria-label="Account")>', r"""<a href="{{ $acctHref ?? '/my-account' }}" \g<1>>"""),
    ('top', r'Account &amp; orders', r"""{{ $acctLabel ?? 'Account & orders' }}"""),
    ('top', r'<a href="/sell" (style="[^"]*"(?: style-hover="[^"]*")?)>Sell on Peyton Ghalib</a>', r"""<a href="{{ $sellHref ?? '/sell' }}" \g<1>>{{ $sellLabel ?? 'Sell on Peyton Ghalib' }}</a>"""),
    ('top', r'(<a href=")/sell(" [^>]*>)Sell on Peyton Ghalib(<span)', r"""\g<1>{{ $sellHref ?? '/sell' }}\g<2>{{ $sellLabel ?? 'Sell on Peyton Ghalib' }}\g<3>"""),
    # ── home: store cards show the city and level, never how many products a store has published ──
    ('home', r"""\{\{ \$s\['city'\] \?\? '' \}\} · ★ \{\{ \$s\['rating'\] \?\? '' \}\} · \{\{ \$s\['followers'\] \?\? '' \}\} followers</span>""",
     """{{ $s['city'] ?? '' }} · {{ $s['level'] ?? '' }}</span>"""),
    # ── home: the hero rail shows ready-made picture cards (see MarketViews::promos) ──
    ('home', r'<\?php foreach\(\$promos \?\? \[\] as \$__k => \$c\): \?>', """<?php foreach($promos ?? [] as $__k => $c): ?><?php if(!empty($c['isPicture'])): ?><a href="{{ $c['href'] }}" style="scroll-snap-align:start;display:block;height:clamp(440px,42vw,580px);border-radius:24px;overflow:hidden;background:{{ $c['bg'] }}"><img src="{{ $c['img'] }}" alt="{{ $c['alt'] }}" width="600" height="1160"<?php if($__k >= 4): ?> loading="lazy"<?php endif; ?> decoding="async" style="width:100%;height:100%;object-fit:cover;display:block"></a><?php continue; endif; ?>"""),
    # ── login / create account ─────────────────────────────────────────────────
    ('login', r'<button data-act="tabIn" style="([^"]*)">(.*?)</button>', "<a href=\"{{ route('login') }}\" style=\"" + BTN + r'\1">\2</a>'),
    ('login', r'<button data-act="tabUp" style="([^"]*)">(.*?)</button>', "<a href=\"{{ route('register') }}\" style=\"" + BTN + r'\1">\2</a>'),
    ('login', r'Full name<input placeholder=', "Full name<input name=\"name\" value=\"{{ old('name') }}\" required maxlength=\"255\" autocomplete=\"name\" placeholder="),
    ('login', r'<input value="jessica\.m@example\.com"', "<input type=\"email\" name=\"email\" value=\"{{ old('email') }}\" required maxlength=\"255\" autocomplete=\"email\" inputmode=\"email\""),
    ('login', r'<input type="password" value="password"',
     "<input type=\"password\" name=\"password\" required minlength=\"8\" autocomplete=\"{{ !empty($loginUp) ? 'new-password' : 'current-password' }}\""),
    ('login', r"<\?php if\(!empty\(\$loginUp\)\): \?><span style=\"font-size:13px;color:#4B5563\">We'll send a 6-digit code.*?</span><\?php endif; \?>",
     "<?php if(!empty($loginUp)): ?><span style=\"font-size:13px;color:#4B5563\">At least 8 characters. An account lets you track orders, save items and check out faster.</span>"
     "<?php else: ?><a href=\"{{ route('password.request') }}\" style=\"font-size:13px;font-weight:600\">Forgot your password?</a><?php endif; ?>"
     "<?php if($errors->any()): ?><span role=\"alert\" style=\"font-size:13px;font-weight:600;color:#B91C1C;background:#FEE2E2;border-radius:12px;padding:10px 12px\">{{ $errors->first() }}</span><?php endif; ?>"
     "<?php if(session('status')): ?><span role=\"status\" style=\"font-size:13px;font-weight:600;color:#15803D;background:#ECFDF3;border-radius:12px;padding:10px 12px\">{{ session('status') }}</span><?php endif; ?>"),
    ('login', r'<button data-act="signIn"' + NO_STYLE + r'>Continue</button>', "<button type=\"submit\"\\1>{{ !empty($loginUp) ? 'Create account' : 'Sign in' }}</button>"),
    ('login', r'<div style="display:flex;align-items:center;gap:12px;font-size:12px;color:#6B7280">.*?</div>', ''),
    ('login', r'<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px"><button[^>]*>Apple</button>.*?</div>', ''),

    # ── account: header and navigation ─────────────────────────────────────────
    ('account', r'>J</span>', ">{{ $acctInitial ?? '' }}</span>"),
    ('account', r'Jessica Martinez', "{{ $acctName ?? '' }}"),
    ('account', r'Member since 2024 · 1,240 points', "{{ $acctSince ?? '' }}"),
    ('account', r'<a href="/login" style="([^"]*)">Sign out</a>',
     '<button data-post="/logout" style="\\1;background:none;border:0;border-top:1px solid #E5E7EB;cursor:pointer;width:100%;text-align:left;font:inherit">Sign out</button>'),
    ('account', r'repeat\(5,1fr\);gap:6px', 'repeat(4,1fr);gap:6px'),
    # ── account: overview ──────────────────────────────────────────────────────
    ('account', r'ARRIVING TODAY', "{{ $ovLabel ?? '' }}"),
    ('account', r"Order \{\{ \$ovActive\['no'\] \?\? '' \}\} · \{\{ \$ovActive\['storeName'\] \?\? '' \}\}", "{{ $ovActive['line'] ?? '' }}"),
    ('account', r'<a href="/order-history"( style="[^"]*")>Track package</a>', "<?php if(!empty($ovActive['trackUrl'])): ?><a href=\"{{ $ovActive['trackUrl'] }}\"\\1>Track package</a><?php endif; ?>", 1),
    ('account', r'<a href="/my-account" style="flex:1 1 240px[^>]*>.*?</a>', ''),
    # ── account: orders ────────────────────────────────────────────────────────
    ('account', r'<a href="/order-history"( style="[^"]*")>Track package</a>', "<a href=\"{{ $o['trackUrl'] ?? '/track-order' }}\"\\1>Track package</a>"),
    ('account', r'<a href="/my-account"( style="[^"]*")>Message seller</a>', ''),
    ('account', r'<a href="/order-history"( style="[^"]*")>Return item</a>', "<a href=\"{{ $o['trackUrl'] ?? '/track-order' }}\"\\1>Return or report a problem</a>"),
    ('account', r'<a href="/my-account"( style="[^"]*")>Write a review</a>', '<a href="/my-account/reviews"\\1>Write a review</a>'),
    ('account', r'<a href="/faq"( style="[^"]*")>Problem with order\?</a>', "<a href=\"{{ $o['trackUrl'] ?? '/track-order' }}\"\\1>Problem with order?</a>"),
    # ── account: wishlist ──────────────────────────────────────────────────────
    ('account', r'<span style="font-size:12px;color:#4B5563">Price alert on · email</span>', ''),
    ('account', r'<button data-act="p\.addCart"' + NO_STYLE + r'>Add to cart</button>',
     "<a href=\"{{ $p['href'] ?? '#' }}\"\\1>View product</a>"),
    # ── account: tabs with forms are written by hand, in the same card style ───
    ('account', '@block:tab_reviews', "<?php if(!empty($tab_reviews)): ?>@include('account.tabs.reviews')<?php endif; ?>"),
    ('account', '@block:tab_security', "<?php if(!empty($tab_security)): ?>@include('account.tabs.settings')<?php endif; ?>"),

    # ── listing (category / shop / search / deals) ─────────────────────────────
    ('list', r' data-rec>', '>'),
    # sort: a select that opens the sorted page
    ('list', r"<select value=\"\{\{ \$list\['sort'\] \?\? '' \}\}\"", '<select data-nav aria-label="Sort by"'),
    ('list', r"<option value=\"\{\{ \$o \?\? '' \}\}\">\{\{ \$o \?\? '' \}\}</option>",
     "<option value=\"{{ $o['url'] }}\" {{ $o['selected'] ? 'selected' : '' }}>{{ $o['label'] }}</option>"),
    # filters are links, so they work without script and survive a slow connection
    ('list', r'<button data-act="f\.toggle" style="([^"]*)">(.*?)</button>', r'<a href="{{ $f["href"] }}" style="\1">\2</a>'),
    ('list', r'<button data-act="list\.clear" style="([^"]*)">(.*?)</button>', r'<a href="{{ $list["clear"] }}" style="\1;display:flex;align-items:center;justify-content:center">\2</a>'),
    ('list', r'(</div>)(\n        <\?php endif; \?>\n        <\?php if\(!empty\(\$list\[.none.\]\)\): \?>)', r'\1\n{!! $list["pager"] !!}\2'),
    ('list', r"(\{\{ \$p\['store'\] \?\? '' \}\}</span>)", r'\1<?php if(!empty($p["sold"])): ?><span style="font-size:12px;color:#4B5563">{{ $p["sold"] }}</span><?php endif; ?>'),
    ('store', r"(\{\{ \$p\['reviews'\] \?\? '' \}\}\)</span>)", r'\1<?php if(!empty($p["sold"])): ?><span style="font-size:12px;color:#4B5563">{{ $p["sold"] }}</span><?php endif; ?>'),
    ('list', r'data-act="list\.openSheet"', 'data-act="openSheet"'),
    ('list', r'data-act="list\.closeSheet"', 'data-act="closeSheet"'),

    # ── store page ─────────────────────────────────────────────────────────────
    ('store', r' data-rec>', '>'),
    ('store', r"@if\(!empty\(\$c\['img'\]\)\)<img[^>]*>@endif", ''),
    ('store', r'background:\{\{ \$store\[\'lvBg\'\] \?\? \'\' \}\};color:\{\{ \$store\[\'lvFg\'\] \?\? \'\' \}\}', 'background:#FFF1E8;color:#9A3412'),
    ('store', r'<div style="display:flex;gap:8px;padding-bottom:4px"><button data-act="store\.onFollow".*?</a></div>',
     '<div style="display:flex;gap:8px;padding-bottom:4px"><?php if(auth()->check()): ?><form method="POST" action="{{ route("store.follow", $store["slug"]) }}">@csrf<button type="submit" style="height:46px;padding:0 24px;border-radius:999px;border:1.5px solid #111827;background:{{ $store["fBg"] }};color:{{ $store["fFg"] }};font-weight:800;cursor:pointer">{{ $store["fLabel"] }}</button></form><?php else: ?><a href="{{ route("login") }}" style="height:46px;padding:0 24px;border-radius:999px;border:1.5px solid #111827;color:#111827;display:flex;align-items:center;font-weight:800">Follow</a><?php endif; ?></div>'),
    ('store', r"On Peyton Ghalib since \{\{ \$store\['since'\] \?\? '' \}\}", "On Peyton Ghalib since {{ $store['since'] ?? '' }} · {{ $store['followers'] }}"),
    # The followers tile goes: the count is already in the header line, and how many products a store has published is never shown.
    ('store', r"""\s*<div style="[^"]*"><b style="[^"]*">\{\{ \$store\['followers'\] \?\? '' \}\}</b><span[^>]*>Followers</span></div>""", ''),
    ('store', r"\{\{ \$store\['resp'\] \?\? '' \}\}</b><span([^>]*)>Typical reply time", r"{{ $store['ships'] ?? '' }}</b><span\1>Ships within"),
    ('store', r"Over \{\{ \$store\['freeOver'\] \?\? '' \}\}</b><span([^>]*)>Free shipping", r"{{ $store['returns'] ?? '' }}</b><span\1>Return window"),
    ('store', r'<\?php if\(!empty\(\$store\[\'hasSale\'\]\)\): \?>.*?<\?php endif; \?>', ''),
    ('store', r'Returns accepted within 30 days\..*?with tracking\.</span>', "{{ $store['policy'] ?? '' }}</span>"),
    ('store', r'An independent American business verified by Peyton Ghalib\..*?before the store opened\.</span><button data-act="store\.report".*?</button>', "{{ $store['about'] ?? '' }}</span>"),
    ('store', r'(</div>\n    <div style="display:flex;flex-wrap:wrap;gap:16px;margin-top:40px">)', r"{!! $store['pager'] ?? '' !!}\1"),

    # ── cart ───────────────────────────────────────────────────────────────────
    ('cart', r'<div style="display:flex;align-items:center;height:40px;border:1\.5px solid #D1D5DB;border-radius:999px">.*?data-act="it\.remove"[^>]*>Remove</button>',
     '<form method="POST" action="{{ route("cart.update") }}" style="display:flex;align-items:center;height:40px;border:1.5px solid #D1D5DB;border-radius:999px">@csrf<input type="hidden" name="cart_key" value="{{ $it["key"] }}">'
     '<button type="submit" name="qty" value="{{ $it["less"] }}" aria-label="Decrease" style="width:40px;height:100%;border:0;background:transparent;font-size:18px;cursor:pointer">−</button>'
     '<b style="width:24px;text-align:center">{{ $it["qty"] }}</b>'
     '<button type="submit" name="qty" value="{{ $it["more"] }}" aria-label="Increase" style="width:40px;height:100%;border:0;background:transparent;font-size:18px;cursor:pointer">+</button></form>'
     '<form method="POST" action="{{ route("cart.remove") }}">@csrf<input type="hidden" name="cart_key" value="{{ $it["key"] }}">'
     '<button type="submit" style="border:0;background:transparent;padding:0;font-size:13px;font-weight:600;color:#374151;text-decoration:underline;cursor:pointer;min-height:32px">Remove</button></form>'),
    ('cart', r"(font-weight:600;color:#6B7280\">)\{\{ \$it\['sub'\] \?\? '' \}\}</a>",
     r'\1@if(!empty($it["img"]))<img src="{{ $it["img"] }}" alt="" loading="lazy" style="width:100%;height:100%;object-fit:cover;border-radius:inherit">@else{{ $it["sub"] ?? "" }}@endif</a>'),
    ('cart', r'<div style="display:flex;gap:8px"><input value="\{\{ \$coupon.*?Apply</button></div>\s*<\?php if\(!empty\(\$hasCouponMsg\)\).*?<\?php endif; \?>\s*<button data-act="togglePoints".*?</button>', ''),
    ('cart', r'Coupon WELCOME10', 'Welcome deal'),
    ('cart', r'(<b style="font-size:18px;font-weight:800">Order summary</b>)',
     r'\1@include("market.cart-ship")<div data-welcome-cart class="pgw-cart" hidden></div>'),
    ('cart', r'<a href="/checkout"( style="[^"]*")>Checkout</a>',
     r'<?php if(!empty($addonBlocked)): ?><span role="alert" style="font-size:13px;font-weight:600;color:#B45309">Add-on items ship free with orders of ${{ rtrim(rtrim(number_format($shippingThreshold, 2), "0"), ".") }} or more. Add another item to check out.</span><span aria-disabled="true"\1 class="dc-disabled">Checkout</span><?php else: ?><a href="{{ url("/checkout") }}"\1>Checkout</a><?php endif; ?>'),

    # ── product page ───────────────────────────────────────────────────────────
    ('product', r"@if\(!empty\(\$c\['img'\]\)\)<img src=\"\{\{ \$c\['img'\] \}\}\" alt=\"\" loading=\"lazy\" (style=\"[^\"]*\")>@endif",
     r'@if(!empty($pdp["img"]))<img id="mainImg" src="{{ $pdp["img"] }}" alt="{{ $pdp["name"] }}" width="800" height="800" \1>@endif'),
    ('product', r"<button data-act=\"t\.pick\" style=\"([^\"]*)\">\{\{ \$t\['label'\] \?\? '' \}\}</button>",
     r'<button type="button" data-thumb="{{ $t["img"] }}" aria-label="{{ $t["label"] }}" style="\1;overflow:hidden;padding:0"><img src="{{ $t["img"] }}" alt="" loading="lazy" style="width:100%;height:100%;object-fit:cover"></button>'),
    ('product', r'>★★★★★<', r'>{{ $pdp["stars"] }}<'),
    ('product', r"<span style=\"font-size:13px;color:#9A3412;font-weight:700;margin-top:-8px\">Earn \{\{ \$pdp\['points'\] \?\? '' \}\} reward points</span>",
     r'<?php if(!empty($pdp["sold"])): ?><span style="font-size:13px;color:#4B5563;margin-top:-8px">{{ $pdp["sold"] }}</span><?php endif; ?>'),
    ('product', r"<\?php if\(!empty\(\$pdp\['hasSale'\]\)\): \?>.*?<\?php endif; \?>", ''),
    ('product', r"(<b [^>]*)(>\{\{ \$pdp\['priceStr'\] \?\? '' \}\}</b>)", r'\1 id="price"\2'),
    ('product', r"(<span style=\"[^\"]*text-decoration:line-through[^\"]*\")(>\{\{ \$pdp\['wasStr'\] \?\? '' \}\}</span>)", r'\1 id="list"\2'),
    ('product', r"(\{\{ \$pdp\['off'\] \?\? '' \}\}</span><\?php endif; \?></div>)",
     r'\1<div class="pgw-pline" data-welcome-product data-price="{{ $pdp["priceNum"] }}" hidden></div>'),
    ('product', r"(<\?php if\(!empty\(\$pdp\['hasColors'\]\)\): \?><div)",
     r'<form method="POST" action="{{ route("cart.add") }}" id="buy" data-track="{{ json_encode(["id" => $pdp["tid"], "name" => $pdp["name"], "price" => (float) $pdp["priceNum"]]) }}" style="display:contents">@csrf'
     r'<input type="hidden" name="product_id" value="{{ $pdp["id"] }}">'
     r'<?php if(!empty($pdp["hasColors"])): ?><input type="hidden" name="color" id="f-color" value="{{ $pdp["colorName"] }}"><?php endif; ?>'
     r'<?php if(!empty($pdp["hasSizes"])): ?><input type="hidden" name="size" id="f-size" value="{{ $pdp["sizeName"] }}"><?php endif; ?>\1'),
    ('product', r"<button data-act=\"c\.pick\" aria-label=\"\{\{ \$c\['name'\] \?\? '' \}\}\" style=\"([^\"]*)\">",
     r'<button type="button" data-pick="color" data-value="{{ $c["name"] }}" aria-label="{{ $c["name"] }}" {{ $c["out"] ? "disabled" : "" }} style="opacity:{{ $c["out"] ? ".35" : "1" }};\1">'),
    ('product', r"<button data-act=\"z\.pick\" style=\"([^\"]*)\">", r'<button type="button" data-pick="size" data-value="{{ $z["label"] }}" {{ $z["out"] ? "disabled" : "" }} style="opacity:{{ $z["out"] ? ".35" : "1" }};\1">'),
    ('product', r"(<span style=\"font-size:14px;font-weight:700;color:\{\{ \$pdp\['stockColor'\] \?\? '' \}\}\")(>\{\{ \$pdp\['stockText'\] \?\? '' \}\}</span>)", r'\1 id="stock"\2'),
    ('product', r'<button data-act="pdp\.dec"', '<button type="button" data-act="pdp.dec"'),
    ('product', r'<button data-act="pdp\.inc"', '<button type="button" data-act="pdp.inc"'),
    ('product', r"(<b )(style=\"[^\"]*\">)\{\{ \$pdp\['qty'\] \?\? '' \}\}</b>", r'\1data-qty \g<2>1</b><input type="hidden" name="qty" value="1" id="f-qty">'),
    ('product', r'<button data-act="pdp\.add" style="([^"]*)">Add to cart</button>',
     r'<button type="submit" id="addBtn" {{ $pdp["soldOut"] && ! $pdp["hasColors"] && ! $pdp["hasSizes"] ? "disabled" : "" }} style="\1">Add to cart</button>'),
    ('product', r'<button data-act="pdp\.buy" style="([^"]*)">Buy now</button>', r'<button type="submit" name="buy" value="1" style="\1">Buy now</button></div></form>'),
    ('product', r"(</form>)\s*</div>(\s*<button data-act=\"wish\")", r'\1\2'),
    ('product', r" · get price-drop alerts", ''),
    ('product', r" · Free shipping on \{\{ \$pdp\['store'\] \?\? '' \}\} orders over \{\{ \$pdp\['freeOver'\] \?\? '' \}\}", ''),
    ('product', r'<b style="font-weight:700">30-day returns</b><span style="color:#4B5563">Set by this seller · ',
     r'<b style="font-weight:700">{{ $pdp["returns"] }}</b><span style="color:#4B5563">{{ $pdp["returnsNote"] }} · '),
    ('product', r"★ \{\{ \$pdp\['storeRating'\] \?\? '' \}\} · \{\{ \$pdp\['followers'\] \?\? '' \}\} followers · Since \{\{ \$pdp\['since'\] \?\? '' \}\}", r'{{ $pdp["storeMeta"] }}'),
    ('product', r"Usually replies <b style=\"[^\"]*\">\{\{ \$pdp\['resp'\] \?\? '' \}\}</b> · ", 'Ships from '),
    ('product', r'grid-template-columns:1fr 1fr 1fr;gap:8px', 'grid-template-columns:1fr;gap:8px'),
    # complete the set + frequently bought together
    ('product', r"<\?php if\(!empty\(\$pdp\['hasBundle'\]\)\): \?>.*?</section>\s*<\?php endif; \?>",
     r'''<?php if(!empty($pdp["set"])): ?><section style="margin-top:40px;border:1px solid #FED7AA;background:#FFF7ED;border-radius:20px;padding:20px;display:flex;flex-direction:column;gap:8px;max-width:820px"><h2 style="margin:0;font-size:20px;font-weight:800">Complete the set &mdash; save {{ $pdp["set"]["saves"] }}</h2><p style="margin:0;font-size:14px;line-height:1.5;color:#374151">{{ $pdp["set"]["name"] }}: {{ $pdp["set"]["parts"] }}. Regular {{ $pdp["set"]["regular"] }}, set price <b>{{ $pdp["set"]["price"] }}</b>.</p><form method="POST" action="{{ route("cart.add-bundle") }}">@csrf<input type="hidden" name="bundle_id" value="{{ $pdp["set"]["id"] }}"><button type="submit" style="height:46px;padding:0 24px;border-radius:999px;border:0;background:#C2410C;color:#fff;font-weight:800;cursor:pointer">Add the set to cart</button></form></section><?php endif; ?>'''
     r'''<?php if(!empty($pdp["fbt"])): ?><section style="margin-top:48px;display:flex;flex-direction:column;gap:14px"><h2 style="margin:0;font-size:22px;font-weight:800;letter-spacing:-.02em">Frequently bought together</h2><form method="POST" action="{{ route("cart.add-many") }}" id="fbt-form" style="border:1px solid #E5E7EB;border-radius:20px;padding:20px;display:flex;flex-direction:column;gap:12px;max-width:820px">@csrf<label style="display:flex;gap:12px;align-items:center"><input type="checkbox" checked disabled style="width:20px;height:20px"><input type="hidden" name="product_ids[]" value="{{ $pdp["id"] }}"><span><b>{{ $pdp["name"] }}</b> · {{ $pdp["priceStr"] }}</span></label><?php foreach($pdp["fbt"] as $b): ?><label style="display:flex;gap:12px;align-items:center"><input type="checkbox" name="product_ids[]" value="{{ $b["id"] }}" checked style="width:20px;height:20px"><span><a href="{{ $b["href"] }}">{{ $b["name"] }}</a> · {{ $b["price"] }}</span></label><?php endforeach; ?><button type="submit" style="align-self:flex-start;height:48px;padding:0 24px;border-radius:999px;border:0;background:#C2410C;color:#fff;font-weight:800;cursor:pointer">Add selected to cart</button></form></section><?php endif; ?>'''),
    # product details: only when there is something to show
    ('product', r'<h2 style="([^"]*)">Product details</h2>', r'<h2 style="\1">About this item</h2>'),
    ('product', r'(<p style="[^"]*)">\{\{ \$pdp\[\'desc\'\] \?\? \'\' \}\}</p>', r'\1;white-space:pre-line">{{ $pdp["desc"] }}</p>'),
    # reviews
    ('product', r'<a href="/my-account"( style="[^"]*")>Write a review</a>', r'<a href="/my-account/reviews"\1>Write a review</a>'),
    ('product', r"Verified purchase · \{\{ \$r\['variant'\] \?\? '' \}\}", r"{{ $r['badge'] }}"),
    ('product', r'<button data-act="r\.onHelpful".*?</button>', ''),
    ('product', r'<section style="margin-top:48px;display:flex;flex-direction:column;gap:14px;max-width:820px">\s*<div[^>]*><h2[^>]*>Questions &amp; answers</h2>.*?</section>', ''),
    ('product', r'<section style="margin-top:40px">\s*<h2[^>]*>You may also like</h2>.*?</section>', ''),
    ('product', r'(<section style="margin-top:48px">\s*<h2[^>]*>)More from \{\{[^}]*\}\}(.*?</section>)', r'<?php if(!empty($pdp["related"])): ?>\1Related Products\2<?php endif; ?>'),
    ('product', r'<div style="margin-top:32px;display:flex;align-items:center;gap:8px;font-size:13px;color:#6B7280">.*?pdp\.report.*?</div>', ''),
    # ── sticky buy bar on phones: submits the product form ─────────────────────
    ('bottom', r'<button data-act="pdp\.add" style="([^"]*)">Add to cart</button>', r'<button type="submit" form="buy" style="\1">Add to cart</button>'),
    ('bottom', r"\{\{ \$pdp\['variantLabel'\] \?\? '' \}\}", ''),

    # ── company / help / legal pages ───────────────────────────────────────────
    ('page', r'<\?php if\(!empty\(\$doc\[\'legal\'\]\)\): \?>(<span style="[^"]*">)Last updated October 26, 2026</span><div.*?</div><\?php endif; \?>',
     r'<?php if(!empty($doc["legal"])): ?>\1Last updated {{ $doc["updated"] }}</span><?php endif; ?>'),
    ('page', r'<\?php if\(!empty\(\$contactNot\)\): \?>', '<form data-contact method="POST" action="{{ route("contact.send") }}" style="display:contents">@csrf<span data-contact-msg role="alert" hidden style="font-size:14px;font-weight:700"></span>'),
    ('page', r'<label([^>]*)>Name<input style=', r'<label\1>Name<input name="name" required maxlength="255" autocomplete="name" style='),
    ('page', r'<label([^>]*)>Email<input style=', r'<label\1>Email<input name="email" type="email" required maxlength="255" autocomplete="email" style='),
    ('page', r'<label([^>]*)>Topic<select style=', r'<label\1>Topic<select name="subject" style='),
    ('page', r'<label([^>]*)>Message<textarea rows="4" style=', r'<label\1>Message<textarea name="Message" rows="4" required maxlength="5000" style='),
    ('page', r'<button data-act="sendContact" style="([^"]*)">Send message</button>\s*<\?php endif; \?>\s*<\?php if\(!empty\(\$contactSent\)\).*?<\?php endif; \?>',
     r'<button type="submit" style="\1">Send message</button></form>'),
    ('page', r'<button data-act="toggleCcpa"', '<button type="button" data-act="toggleCcpa" aria-pressed="false"'),
    ('page', r'\{\{ \$ccpaLabel \?\? \'\' \}\}', 'Off: advertising cookies may be used. On: they are switched off for this browser.'),
    ('page', r'background:\{\{ \$ccpaBg \?\? \'\' \}\}', 'background:#D1D5DB'),
    ('page', r'left:\{\{ \$ccpaX \?\? \'\' \}\}', 'left:3px'),

    ('help', r'<input placeholder="Search help articles"', '<input data-faq-search type="search" aria-label="Search help articles" placeholder="Search help articles"'),
    ('help', r'<button data-act="f\.toggle" style="([^"]*)">', r'<button type="button" data-faq style="\1" aria-expanded="false">'),
    ('help', r'<\?php if\(!empty\(\$f\[\'open\'\]\)\): \?>(<p style="[^"]*")>(.*?)</p><\?php endif; \?>', r'\1 hidden>\2</p>'),
    ('product', r"(<p style=\"[^\"]*\">\{\{ \$r\['text'\] \?\? '' \}\}</p>)",
     r'\1<?php if(!empty($r["reply"])): ?><div style="padding:10px 12px;border-radius:12px;background:#F9FAFB;font-size:14px;line-height:1.5;color:#374151"><b>Reply from {{ $r["replyBy"] }}:</b> {{ $r["reply"] }}</div><?php endif; ?>'),
    ('cart', r'(<div style="display:flex;justify-content:space-between;align-items:baseline;border-top:1px solid #E5E7EB;padding-top:14px">)', r'@include("market.cart-coupon")\1'),
    ('cart', r'(<div style="display:flex;justify-content:space-between"><span style="color:#4B5563">Shipping</span>)',
     r'<?php if(!empty($cv["couponOn"])): ?><div style="display:flex;justify-content:space-between;color:#15803D"><span>Coupon {{ $cv["couponCode"] }}</span><span>{{ $cv["couponOff"] }}</span></div><?php endif; ?>\1'),
    ('list', r'(<\?php if\(!empty\(\$p\["sold"\]\)\): \?><span style="[^"]*">\{\{ \$p\["sold"\] \}\}</span><\?php endif; \?>)',
     r'\1<?php if(!empty($p["sponsored"])): ?><span style="font-size:11px;font-weight:700;color:#6B7280">Sponsored</span><?php endif; ?>'),
    ('account', '@block:tab_returns', "<?php if(!empty($tab_returns)): ?>@include('account.tabs.returns')<?php endif; ?>"),
    ('account', '@block:tab_wallet', "<?php if(!empty($tab_wallet)): ?>@include('account.tabs.wallet')<?php endif; ?>"),
    ('account', '@block:tab_following', "<?php if(!empty($tab_following)): ?>@include('account.tabs.following')<?php endif; ?>"),
    ('account', '@block:tab_notifications', "<?php if(!empty($tab_notifications)): ?>@include('account.tabs.notifications')<?php endif; ?>"),
    ('account', '@block:tab_rewards', "<?php if(!empty($tab_rewards)): ?>@include('account.tabs.rewards')<?php endif; ?>"),
    ('account', '@block:tab_giftcards', "<?php if(!empty($tab_giftcards)): ?>@include('account.tabs.giftcards')<?php endif; ?>"),
    ('account', '@block:tab_messages', "<?php if(!empty($tab_messages)): ?>@include('account.tabs.messages')<?php endif; ?>"),
    ('cart', r'(<div style="display:flex;justify-content:space-between"><span style="color:#4B5563">Shipping</span>)',
     r'<?php if(!empty($cv["ptsOn"])): ?><div style="display:flex;justify-content:space-between;color:#15803D"><span>Reward points</span><span>{{ $cv["ptsOff"] }}</span></div><?php endif; ?>\1'),
    ('product', r'(<div class="pgw-pline")', r'<span style="font-size:13px;color:#9A3412;font-weight:700">Earn {{ $pdp["earn"] }} reward points when it is delivered</span>\1'),
    ('cart', r'(<div style="display:flex;justify-content:space-between"><span style="color:#4B5563">Shipping</span>)',
     r'<?php if(!empty($cv["giftOn"])): ?><div style="display:flex;justify-content:space-between;color:#15803D"><span>Gift card balance</span><span>{{ $cv["giftOff"] }}</span></div><?php endif; ?>\1'),
    ('product', r'<button data-act="pdp\.onFollow".*?</button><a href="/my-account"[^>]*>Message</a>',
     '<a href="{{ url("/my-account/messages?to=" . $pdp["storeSlug"]) }}" style="height:42px;border-radius:999px;border:1.5px solid #111827;background:#FFFFFF;color:#111827;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px">Message seller</a>'),
    ('store', r'(<\?php else: \?><a href="\{\{ route\("login"\) \}\}"[^>]*>Follow</a><\?php endif; \?>)',
     r'\1<a href="{{ url("/my-account/messages?to=" . $store["slug"]) }}" style="height:46px;padding:0 20px;border-radius:999px;background:#F3F4F6;color:#111827;display:flex;align-items:center;font-weight:700">Message</a>'),
]
