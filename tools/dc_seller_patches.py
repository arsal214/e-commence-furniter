r"""Seller Center design: swaps the prototype's made-up text and fake buttons for real data and real actions.

Each entry is (page, regex, replacement). 'top' and 'bottom' are the frame. A pattern that no longer matches
is reported by dc-transpile.py so a changed design is noticed instead of silently half-applied.
"""

NO_STYLE = r'( style="[^"]*")'

PATCHES = [
    ('top', r'(<a href="[^"]*" style="color:#FDBA74;font-weight:700">View marketplace →</a>)', r"""\g<1><a href="{{ url('/my-account/settings') }}" style="color:#D1D5DB">Account settings</a>"""),
    # ── frame ──────────────────────────────────────────────────────────────────
    ('top', r'Harbor &amp; Hide Goods', "{{ $storeName ?? '' }}"),
    ('top', r'>DB</span>', ">{{ $initials ?? '' }}</span>"),
    ('top', r'<button data-act="approveNow"[^>]*>Prototype: approve</button>', ''),
    ('top', r"We couldn't charge \$19\.99 to Visa •• 4242 on Oct 26\.", "We couldn't charge your $19.99 seller plan."),
    ('top', r'<button data-act="payNow"' + NO_STYLE + r'>(.*?)</button>', r'<a href="/seller/billing"\1>\2</a>'),
    ('top', r'<a href="/home"', '<a href="/"'),
    ('top', r'<a href="/sell" style="color:#9CA3AF">Sign out</a>',
     '<button data-post="/logout" style="color:#9CA3AF;background:none;border:0;padding:0;text-align:left;cursor:pointer;font:inherit">Sign out</button>'),
    ('top', r'href="/store/0"', "href=\"{{ $storeUrl ?? '/' }}\""),
    # ── dashboard ──────────────────────────────────────────────────────────────
    ('dashboard', r'<span>Sep 27</span><span>Oct 11</span><span>Today</span>', "<span>{{ $barLabels[0] ?? '' }}</span><span>{{ $barLabels[1] ?? '' }}</span><span>Today</span>"),
    ('dashboard', r"\{\{ \$lv\['need'\] \?\? '' \}\} more in sales to reach \{\{ \$lv\['next'\] \?\? '' \}\}", "{{ $lv['line'] ?? '' }}"),
    ('dashboard', r"of \$10,000 lifetime", "of {{ $lv['goal'] ?? '' }} lifetime"),
    ('dashboard', r'<button data-act="redeem"' + NO_STYLE + r'>Redeem \$8</button>', "<button data-post=\"/seller/points/redeem\"\\1>Redeem {{ $redeemAmount ?? '' }}</button>"),
    ('dashboard', r'Seller plan · \$19\.99/mo · next charge Nov 26', "Seller plan · $19.99/mo · {{ $planLine ?? '' }}"),
    ('dashboard', r'<button data-act="toggleFail"[^>]*>\{\{ \$failLabel \?\? \'\' \}\}</button>', ''),
    # ── orders ─────────────────────────────────────────────────────────────────
    # An order shows its full pick list and ship-to details (never the buyer's email); see seller/center/order-details.blade.php.
    ('orders', r""" · \{\{ \$x\['buyer'\] \?\? '' \}\}, \{\{ \$x\['city'\] \?\? '' \}\}</span>""", '</span>'),
    ('orders', r"""(\{\{ \$x\['due'\] \?\? '' \}\}</span></span></div>)""", r"""\g<1>@include('seller.center.order-details', ['x' => $x])"""),
    ('orders', r'<button data-act="x\.buyUsps"[^>]*>[^<]*</button>', ''),
    ('orders', r'<button data-act="x\.buyUps"[^>]*>[^<]*</button>', ''),
    ('orders', r'<a href="/account/messages"[^>]*>Message buyer</a>', ''),
    ('orders', r'<button data-act="x\.ownTrack"' + NO_STYLE + r'>I shipped it — add tracking</button>',
     '<form method="POST" action="{{ $x[\'shipUrl\'] ?? \'\' }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;width:100%">@csrf'
     '<select name="carrier" aria-label="Carrier" style="height:40px;border-radius:999px;border:1.5px solid #D1D5DB;padding:0 12px;background:#FFFFFF;font-size:14px">'
     '<option>USPS</option><option>UPS</option><option>FedEx</option><option>DHL</option><option>Other</option></select>'
     '<input name="tracking" required maxlength="60" placeholder="Tracking number" aria-label="Tracking number" style="height:40px;border-radius:999px;border:1.5px solid #D1D5DB;padding:0 14px;font-size:14px;flex:1 1 180px;min-width:0">'
     '<button type="submit"\\1>Mark as shipped</button></form>'),
    ('orders', r'<button data-act="t\.pick"(.*?)>(.*?)</button>', "<a href=\"{{ $t['href'] ?? '#' }}\"\\1>\\2</a>"),
    # ── payouts ────────────────────────────────────────────────────────────────
    ('payouts', r'NEXT PAYOUT · TODAY', "{{ $nextLabel ?? '' }}"),
    ('payouts', r'\$412\.60', "{{ $nextAmount ?? '' }}"),
    ('payouts', r'To Chase Business Checking •• 6789', "{{ $nextTo ?? '' }}"),
    ('payouts', r'Daily payouts · Rising Seller hold: 2 days after delivery', "{{ $holdLine ?? '' }}"),
    ('payouts', r'Harbor &amp; Hide Goods LLC · Chase •• 6789 <span style="color:#15803D;font-weight:700">✓ Verified</span>',
     "{{ $bankLine ?? '' }} @if(!empty($bankReady))<span style=\"color:#15803D;font-weight:700\">✓ Connected</span>@endif"),
    ('payouts', r'Routing 061000104 · Checking · via Stripe Connect', 'Payouts are sent to your bank account.'),
    ('payouts', r'<button' + NO_STYLE + r'>Change bank account</button>', r'<button data-post="/seller/payouts/bank"\1>Manage bank account</button>'),
    ('payouts', r'Changes require re-verification and pause payouts for up to 2 business days\.', 'A changed account is re-verified before paying out.'),
    ('payouts', r'<a href="/seller/payouts"( style="font-size:13px;font-weight:700">Export CSV)', r'<a href="/seller/payouts/export"\1'),
    # ── levels ─────────────────────────────────────────────────────────────────
    ('levels', r'Rising Seller · \$8,420 lifetime sales', "{{ $levelLine ?? '' }}"),
    ('levels', r'width:84%', "width:{{ $levelPct ?? '0%' }}"),
    ('levels', r'\$1,580 more to Top Seller: payouts 1 day after delivery, even higher search placement and priority support\.', "{{ $nextLine ?? '' }}"),
    # ── billing ────────────────────────────────────────────────────────────────
    ('billing', r'>Active</span>', ">{{ $planStatus ?? '' }}</span>"),
    ('billing', r'0% commission · next charge Nov 26, 2026 to Visa •• 4242', "0% commission · {{ $planLine ?? '' }}"),
    ('billing', r'<button' + NO_STYLE + r'>Update card</button>', r'<button data-post="/seller/billing/portal"\1>Update card or cancel</button>'),
    ('billing', r'<button data-act="toggleFail"[^>]*>\{\{ \$failLabel \?\? \'\' \}\}</button>', ''),
    ('billing', r'<div style="[^"]*"><span><b>2025 Form 1099-K</b>.*?</div>', ''),
    ('billing', r'<div style="[^"]*"><span><b>W-9 on file</b>.*?</div>', ''),
    ('orders', r"""(<a href="\{\{ \$t\['href'\] \?\? '#' \}\}" style=")""", r'\1display:inline-flex;align-items:center;'),
]
