#!/usr/bin/env python3
r"""Turns the claude.ai/design .dc.html templates (Marketplace, Admin Panel, Seller Center) into Blade partials.

usage: python tools/dc-transpile.py <file.dc.html> <out dir> [admin|seller]

The design is a client-rendered app (<sc-if>, <sc-for>, {{ path }}). Every binding in it is a plain dotted
path, so the markup converts mechanically and keeps the design's exact inline styles. Data comes from
App\Support\MarketViews, AdminViews and SellerViews; behaviour from public/js/market.js (data-act attributes
replace onClick) and from the small scripts in the admin and seller layouts (data-post).
"""
import re
import sys
import os

ROUTES = {'isHome': 'home', 'isList': 'list', 'isProduct': 'product', 'isStore': 'store', 'isCart': 'cart', 'isCheckout': 'checkout',
          'isConfirm': 'confirm', 'isLogin': 'login', 'isAccount': 'account', 'isHelp': 'help', 'isPage': 'page', 'isSitemap': 'sitemap'}
# Conditions that flip in the browser (menus, modals): rendered hidden and toggled by market.js.
CLIENT = {'menu', 'menuMain', 'menuSub', 'locOpen', 'hasLocErr', 'chatOpen', 'hasToast', 'canMore', 'subscribed', 'notSubscribed', 'list.sheet'}
VISIBLE_AT_START = {'menuMain', 'canMore', 'notSubscribed'}
# Layout conditions: both versions are rendered and CSS picks one by width.
LAYOUT = {'isDesk': 'dc-desk', 'isMobile': 'dc-mob', 'isWide': 'dc-wide', 'notWideHdr': 'dc-narrow'}

TAG = re.compile(r'<(/?)sc-(if|for)([^>]*)>')
BIND = re.compile(r'\{\{\s*([A-Za-z_]\w*(?:\.\w+)*)\s*\}\}')


def php(path):
    parts = path.strip().split('.')
    return '$' + parts[0] + ''.join("['%s']" % p for p in parts[1:])


# Where the design's fixed links lead on this site. Pages that are not built yet point at the nearest live page.
HREFS = {
    '': '/', 'home': '/', 'account': '/my-account', 'account/orders': '/order-history', 'account/wishlist': '/wishlist',
    'account/messages': '/my-account', 'account/rewards': '/my-account', 'account/giftcards': '/my-account', 'account/reviews': '/my-account',
    'account/returns': '/order-history', 'help': '/faq', 'page/terms': '/terms-and-conditions', 'page/privacy': '/privacy-policy',
    'page/returns': '/return-policy', 'page/contact': '/contact', 'page/buyer-protection': '/faq', 'page/rewards-terms': '/terms-and-conditions',
    'page/ccpa': '/privacy-policy',
}


def static_href(m):
    target = m.group(2)
    if m.group(1) and 'Seller' in m.group(1):
        return 'href="%s"' % {'sell': '/sell', 'pricing': '/sell/pricing', 'dashboard': '/seller'}.get(target, '/sell')
    return 'href="%s"' % HREFS.get(target, '/' + target)


def open_if(attrs):
    v = re.search(r'value="\{\{\s*([^}]+?)\s*\}\}"', attrs).group(1).strip()
    if v in LAYOUT:
        return '<div class="%s">' % LAYOUT[v], 'div'
    if v in ('showTabs', 'showBuyBar'):
        # Phone-only bars: shown on narrow screens by CSS, and only on pages that want them.
        return '<?php if(!empty($%s)): ?><div class="dc-mob">' % v, 'ifdiv'
    if v == 'menuSub':
        # One panel per top-level category; market.js shows the one that was tapped.
        return '<?php foreach($menuCats ?? [] as $__i => $subCat): ?><div data-if="menuSub" data-sub="{{ $__i }}" style="display:contents" hidden>', 'subdiv'
    if v in CLIENT:
        hidden = '' if v in VISIBLE_AT_START else ' hidden'
        return '<div data-if="%s" style="display:contents"%s>' % (v, hidden), 'div'
    # Plain PHP, not @if: Blade only sees a directive that follows a non-word character, and the design
    # puts blocks right next to each other ("</span>@endif@if(...)").
    return '<?php if(!empty(%s)): ?>' % php(v), 'if'


DIRECTIVES = {'if', 'else', 'elseif', 'endif', 'foreach', 'endforeach', 'for', 'endfor', 'while', 'php', 'endphp', 'include', 'section', 'endsection', 'yield',
              'json', 'csrf', 'method', 'error', 'auth', 'guest', 'can', 'props', 'class', 'style', 'checked', 'selected', 'disabled', 'required', 'push',
              'stack', 'extends', 'component', 'slot', 'verbatim', 'unless', 'isset', 'empty', 'switch', 'case', 'break', 'default', 'lang', 'once', 'vite',
              'env', 'production', 'session', 'use', 'inject', 'each', 'continue', 'parent', 'show', 'stop', 'prepend', 'endpush', 'endonce'}


def convert(t):
    # A literal "@" in the design (an email, "pkg@master") must not be read as a Blade directive.
    t = t.replace('@', '\x00')
    t = re.sub(r'\s+hint-placeholder-(?:count|val)="[^"]*"', '', t)

    out, stack, pos = [], [], 0
    for m in TAG.finditer(t):
        out.append(t[pos:m.start()])
        pos = m.end()
        if m.group(1) == '/':
            out.append({'div': '</div>', 'if': '<?php endif; ?>', 'for': '<?php endforeach; ?>', 'subdiv': '</div><?php endforeach; ?>', 'ifdiv': '</div><?php endif; ?>'}[stack.pop()])
        elif m.group(2) == 'if':
            s, kind = open_if(m.group(3))
            stack.append(kind)
            out.append(s)
        else:
            lst = re.search(r'list="\{\{\s*([^}]+?)\s*\}\}"', m.group(3)).group(1)
            var = re.search(r'as="(\w+)"', m.group(3)).group(1)
            stack.append('for')
            out.append('<?php foreach(%s ?? [] as $__k => $%s): ?>' % (php(lst), var))
    out.append(t[pos:])
    t = ''.join(out)

    # Handlers first: they are bindings too, and must not be turned into {{ $var }} output.
    t = re.sub(r'\s+onClick="\{\{\s*([\w.]+)\s*\}\}"', lambda m: ' data-act="%s"' % m.group(1), t)
    t = re.sub(r'\s+on(?:Change|KeyDown)="\{\{[^}]*\}\}"', '', t)
    # *Pager bindings hold ready-made pagination markup.
    t = BIND.sub(lambda m: ("{!! %s ?? '' !!}" if m.group(1).endswith('Pager') else "{{ %s ?? '' }}") % php(m.group(1)), t)
    t = re.sub(r'href="((?:[A-Za-z ]+\.dc\.html)?)#/([^"{}]*)"', static_href, t)

    # Social buttons: real links, opened in a new tab.
    social = 'style="height:36px;padding:0 12px;border-radius:999px;border:1px solid #334155'
    t = t.replace('<a href="#" ' + social, "<a href=\"{{ $s['href'] ?? '#' }}\" target=\"_blank\" rel=\"noopener\" " + social)
    t = t.replace("align-items:center\">{{ $s ?? '' }}</a>", "align-items:center\">{{ $s['t'] ?? '' }}</a>")
    t = t.replace('Hello, Jessica', "{{ $menuHello ?? 'Hello, Sign in' }}")

    # Behaviour hooks market.js understands.
    t = t.replace('data-act="c.open"', 'data-act="openSub" data-i="{{ $__k }}"')
    t = t.replace('data-act="p.onWish"', "data-act=\"wish\" data-id=\"{{ $p['id'] ?? '' }}\"")
    t = t.replace('data-act="pdp.onWish"', "data-act=\"wish\" data-id=\"{{ $pdp['id'] ?? '' }}\"")
    # Product pictures: real photo when there is one, the design's tinted label otherwise.
    label = re.compile(r"<span style=\"font-size:12px;font-weight:600;color:#6B7280\">\{\{ \$(\w+)\['sub'\] \?\? '' \}\}</span>")
    image = ("@if(!empty($V['img']))<img src=\"{{ $V['img'] }}\" alt=\"\" loading=\"lazy\" "
             "style=\"position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:inherit\">@else LABEL @endif")
    t = label.sub(lambda m: image.replace('$V', '$' + m.group(1)).replace(' LABEL ', m.group(0)), t)
    # Promo tiles: the design shows an upload slot; here it is a photo only when one is supplied.
    t = re.sub(r'<image-slot[^>]*></image-slot>', "@if(!empty($c['img']))<img src=\"{{ $c['img'] }}\" alt=\"\" loading=\"lazy\" style=\"position:absolute;inset:0;width:100%;height:100%;object-fit:cover\">@endif", t)
    t = t.replace("ref=\"{{ $cardsRef ?? '' }}\" data-rail=\"1\"", 'data-cards data-rail="1"')
    t = t.replace('<span style="display:flex;gap:4px"><?php foreach($cd', '<span data-cd style="display:flex;gap:4px"><?php foreach($cd')
    tile = re.compile(r"<span style=\"flex:1;min-height:40px;border-radius:10px;background:\{\{ \$t\['tint'\] \?\? '' \}\};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;color:#6B7280\">\{\{ \$t\['sub'\] \?\? '' \}\}</span>")
    t = tile.sub(lambda m: "@if(!empty($t['img']))<img src=\"{{ $t['img'] }}\" alt=\"\" loading=\"lazy\" style=\"flex:1;min-height:40px;width:100%;border-radius:10px;object-fit:cover;min-width:0\">@else" + m.group(0) + "@endif", t)
    t = t.replace('repeat(auto-fill,minmax(min(176px,44%),1fr));gap:28px 16px"', 'repeat(auto-fill,minmax(min(176px,44%),1fr));gap:28px 16px" data-rec')
    return re.sub(r'\x00(\w*)', lambda m: ('@@' if m.group(1) in DIRECTIVES else '@') + m.group(1), t)


# Other design files. 'wrap' names the block whose frame (sidebar, top bar, drawer) is kept around the page bodies.
PROFILES = {
    'admin': {'routes': {'isOverview': 'overview', 'isSellers': 'sellers', 'isSeller': 'seller', 'isProducts': 'products', 'isDisputes': 'disputes',
                         'isPayouts': 'payouts', 'isSubs': 'subs', 'isMarketing': 'marketing', 'isReports': 'reports'},
              'hrefs': {'sellers': '/admin/sellers', 'products': '/admin/moderation', 'disputes': '/admin/disputes', 'payouts': '/admin/payouts',
                        'subscriptions': '/admin/subscriptions', 'marketing': '/admin/marketing', 'reports': '/admin/reports', 'overview': '/admin'},
              'wrap': None},
    'seller': {'routes': {'is_dashboard': 'dashboard', 'is_products': 'products', 'is_product_new': 'product-new', 'is_orders': 'orders', 'is_performance': 'performance',
                          'is_payouts': 'payouts', 'is_marketing': 'marketing', 'is_storefront': 'storefront', 'is_levels': 'levels', 'is_billing': 'billing',
                          'is_referrals': 'referrals', 'is_settings': 'settings', 'is_help': 'help'},
               'hrefs': {'dashboard': '/seller', 'products': '/seller/products', 'product-new': '/seller/products/create', 'orders': '/seller/orders',
                         'performance': '/seller', 'payouts': '/seller/payouts', 'marketing': '/seller', 'storefront': '/seller/settings', 'levels': '/seller/levels',
                         'billing': '/seller/billing', 'referrals': '/seller', 'settings': '/seller/settings', 'help': '/contact', 'sell': '/sell'},
               'wrap': 'isApp'},
}


def main(src, out, profile=None):
    global ROUTES, HREFS
    wrap = None
    if profile:
        ROUTES, HREFS, wrap = PROFILES[profile]['routes'], PROFILES[profile]['hrefs'], PROFILES[profile]['wrap']
    s = open(src, encoding='utf-8').read()
    t = s[s.index('<x-dc>') + 6:s.index('</x-dc>')]
    t = re.sub(r'<helmet>.*?</helmet>', '', t, flags=re.S)

    blocks, stack = [], []
    for m in TAG.finditer(t):
        if m.group(1) == '':
            v = re.search(r'(?:value|list)="\{\{\s*([^}]+?)\s*\}\}"', m.group(3)).group(1)
            rec = [m.start(), None, v, len(stack)]
            stack.append(rec)
            blocks.append(rec)
        else:
            rec = stack.pop()
            rec[1] = m.end()

    os.makedirs(out, exist_ok=True)

    sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
    patches = []
    if profile == 'seller':
        from dc_seller_patches import PATCHES as patches
    elif not profile:
        from dc_market_patches import PATCHES as patches

    def replace_block(body, flag, replacement):
        """Swaps a whole <?php if(!empty($flag)): ?> ... <?php endif; ?> block, counting the nested ones."""
        start = body.find('<?php if(!empty($%s)): ?>' % flag)
        if start < 0:
            return body, False
        depth = 0
        for m in re.finditer(r'<\?php if\(|<\?php endif; \?>', body[start:]):
            depth += 1 if m.group(0).startswith('<?php if(') else -1
            if depth == 0:
                return body[:start] + replacement + body[start + m.end():], True
        return body, False

    def write(name, body):
        for entry in patches:
            page, pattern, replacement = entry[:3]
            if page != name:
                continue
            if pattern.startswith('@block:'):
                body, ok = replace_block(body, pattern[7:], replacement)
                n = 1 if ok else 0
            else:
                body, n = re.subn(pattern, replacement, body, count=entry[3] if len(entry) > 3 else 0, flags=re.S)
            if not n:
                print('WARNING: patch matched nothing on', name, ':', pattern[:70])
        with open(os.path.join(out, name + '.blade.php'), 'w', encoding='utf-8', newline='\n') as f:
            f.write(body)

    def inner(a, b):
        body = re.sub(r'^<sc-if[^>]*>', '', t[a:b])
        return re.sub(r'</sc-if>$', '', body)

    depth = 1 if wrap else 0
    routes = [(a, b, v) for a, b, v, d in blocks if v in ROUTES and d == depth]
    for a, b, v in routes:
        write(ROUTES[v], convert(inner(a, b)))

    first, last = min(a for a, b, v in routes), max(b for a, b, v in routes)
    if wrap:
        w = next(r for r in blocks if r[2] == wrap and r[3] == 0)
        write('top', convert(re.sub(r'^<sc-if[^>]*>', '', t[w[0]:first])))
        write('bottom', convert(re.sub(r'</sc-if>$', '', t[last:w[1]])))
    elif not profile:
        write('top', convert(t[:first]).replace("{{ $frameW ?? '' }}", '100%'))
        write('bottom', convert(t[last:]))
    print('wrote', out)


main(sys.argv[1], sys.argv[2], sys.argv[3] if len(sys.argv) > 3 else None)
