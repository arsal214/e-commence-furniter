<!-- Topbar Start -->
<style>
#pg-topbar{background:#0F1E2E;height:46px;width:100%;overflow:hidden;position:relative;z-index:60}
.pg-tb-wrap{max-width:1720px;margin:0 auto;padding:0 24px;height:46px;display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:0}
/* Left */
.pg-tb-left{display:flex;align-items:center;gap:8px}
.pg-tb-left a{display:flex;align-items:center;gap:9px;text-decoration:none;color:rgba(255,255,255,.88);font-size:13.5px;font-weight:500;letter-spacing:.05em;white-space:nowrap;transition:color .2s}
.pg-tb-left a:hover{color:#bb976d}
/* Center */
.pg-tb-center{height:46px;overflow:hidden;position:relative;min-width:260px}
.pg-tb-track{display:flex;flex-direction:column;will-change:transform}
.pg-tb-slide{height:46px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.88);font-size:16px;font-weight:500;letter-spacing:.05em;white-space:nowrap;flex-shrink:0;gap:6px}
/* Right */
.pg-tb-right{display:flex;align-items:center;justify-content:flex-end;gap:8px}
.pg-tb-right a{display:flex;align-items:center;gap:9px;text-decoration:none;color:rgba(255,255,255,.88);font-size:13.5px;font-weight:500;letter-spacing:.05em;white-space:nowrap;transition:color .2s}
.pg-tb-right a:hover{color:#bb976d}
/* Dividers */
.pg-tb-div{width:1px;height:16px;background:rgba(255,255,255,.14);flex-shrink:0;margin:0 20px}
/* Responsive */
@media(max-width:900px){.pg-tb-div{margin:0 12px}.pg-tb-left a span,.pg-tb-right a span{font-size:13px}}
@media(max-width:640px){
  .pg-tb-left,.pg-tb-right,.pg-tb-div{display:none}
  .pg-tb-wrap{grid-template-columns:1fr;justify-items:center}
}
</style>

<div id="pg-topbar">
    <div class="pg-tb-wrap">

        {{-- ── Left: Free Shipping ── --}}
        <div class="pg-tb-left">
            <a href="{{ route('shipping-policy') }}">
                <svg width="24" height="17" viewBox="0 0 22 15" fill="none" stroke="rgba(255,255,255,.88)" stroke-width="1.55" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0">
                    <rect x=".75" y=".75" width="13.5" height="10.5" rx="1.4"/>
                    <path d="M14.25 4H18.6L21.25 8v3.25H14.25V4z"/>
                    <circle cx="4.75" cy="13.25" r="1.65"/>
                    <circle cx="17.5" cy="13.25" r="1.65"/>
                </svg>
                <span>Free Shipping</span>
            </a>
        </div>

        {{-- ── Center: Vertical Slider ── --}}
        <div class="pg-tb-center" id="pgTopCenter">
            <div class="pg-tb-track" id="pgTopTrack">
                <div class="pg-tb-slide">👥&nbsp; 2,000+ Happy Customers</div>
                <div class="pg-tb-slide">💬&nbsp; 24/7 Live Chat</div>
                <div class="pg-tb-slide">⭐&nbsp; Trusted by Thousands</div>
                {{-- clone of first for seamless loop --}}
                <div class="pg-tb-slide">👥&nbsp; 2,000+ Happy Customers</div>
            </div>
        </div>

        {{-- ── Right: Secure Payments ── --}}
        <div class="pg-tb-right">
            <a href="{{ route('return-policy') }}">
                <svg width="16" height="18" viewBox="0 0 24 27" fill="rgba(255,255,255,.88)" style="flex-shrink:0">
                    <path d="M12 .6 1.2 5.4v6C1.2 17.7 5.9 23.5 12 26c6.1-2.5 10.8-8.3 10.8-14.6v-6L12 .6Zm-1.8 17 -4.2-4.2 1.8-1.8 2.4 2.4 5.4-5.4 1.8 1.8-7.2 7.2Z"/>
                </svg>
                <span>Secure Payments</span>
            </a>
        </div>

    </div>
</div>

<script>
(function () {
    var track  = document.getElementById('pgTopTrack');
    var center = document.getElementById('pgTopCenter');
    if (!track || !center) return;

    var H      = 46;
    var REAL   = 3;
    var idx    = 0;
    var busy   = false;
    var paused = false;

    function tick() {
        if (busy || paused) return;
        busy = true;
        idx++;
        track.style.transition = 'transform .52s cubic-bezier(.4,0,.2,1)';
        track.style.transform  = 'translateY(-' + (idx * H) + 'px)';
    }

    track.addEventListener('transitionend', function () {
        if (idx >= REAL) {
            track.style.transition = 'none'; idx = 0;
            track.style.transform  = 'translateY(0)';
            void track.offsetWidth;
        }
        busy = false;
    });

    center.addEventListener('mouseenter', function () { paused = true;  });
    center.addEventListener('mouseleave', function () { paused = false; });

    setInterval(tick, 3000);
}());
</script>
<!-- Topbar End -->

@php
    $navCategories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
    $cart          = app(\App\Services\CartService::class);
    $navWishlistCount = auth()->check()
        ? \App\Models\Wishlist::where('user_id', auth()->id())->count()
        : 0;
@endphp

<!-- ═══════════════════════════════════════════════
     PREMIUM HEADER
═══════════════════════════════════════════════ -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* ── Base ── */
#pg-hdr {
    position: sticky; top: 0; z-index: 200;
    width: 100%; background: #fff;
    border-bottom: 1px solid transparent;
    transition: border-color .3s, box-shadow .3s;
    font-family: 'Poppins', sans-serif;
}
#pg-hdr.scrolled {
    border-color: #ede8e1;
    box-shadow: 0 2px 28px rgba(23,36,48,.07);
}
.dark #pg-hdr { background: #172430; }
.dark #pg-hdr.scrolled { border-color: rgba(255,255,255,.08); }

/* ── Inner layout ── */
.pg-hdr-inner {
    max-width: 1440px; margin: 0 auto; padding: 0 40px;
    height: 76px;
    display: grid;
    grid-template-columns: 200px 1fr auto;
    align-items: center;
}

/* ── Logo ── */
.pg-hdr-logo { display: flex; align-items: center; text-decoration: none; }
.pg-hdr-logo img { height: 42px; width: auto; }

/* ── Nav ── */
.pg-hdr-nav { display: flex; align-items: center; justify-content: center; list-style: none; margin: 0; padding: 0; gap: 0; }
.pg-hdr-nav > li { position: relative; }
.pg-hdr-nav > li > a {
    display: flex; align-items: center; gap: 5px;
    height: 76px; padding: 0 16px;
    font-size: 12px; font-weight: 600;
    letter-spacing: .09em; text-transform: uppercase;
    color: #172430; text-decoration: none;
    position: relative; transition: color .2s; white-space: nowrap;
}
.dark .pg-hdr-nav > li > a { color: rgba(255,255,255,.88); }
.pg-hdr-nav > li > a::after {
    content: ''; position: absolute;
    bottom: 14px; left: 16px; right: 16px;
    height: 1.5px; background: #bb976d;
    transform: scaleX(0); transform-origin: left;
    transition: transform .26s cubic-bezier(.4,0,.2,1);
}
.pg-hdr-nav > li > a:hover,
.pg-hdr-nav > li > a.pg-active { color: #bb976d; }
.pg-hdr-nav > li > a:hover::after,
.pg-hdr-nav > li > a.pg-active::after { transform: scaleX(1); }
.pg-chevron { flex-shrink: 0; transition: transform .24s; }
.pg-has-mega.mega-open > a .pg-chevron { transform: rotate(180deg); }

/* ── Mega Menu ── */
.pg-mega {
    position: fixed; left: 0; width: 100%;
    background: #fff;
    border-top: 2px solid #bb976d;
    box-shadow: 0 24px 64px rgba(23,36,48,.11);
    z-index: 190;
    opacity: 0; visibility: hidden;
    transform: translateY(-6px);
    transition: opacity .22s, visibility .22s, transform .22s cubic-bezier(.4,0,.2,1);
    pointer-events: none;
}
.dark .pg-mega { background: #1c2d3e; }
.pg-mega.mega-open { opacity: 1; visibility: visible; transform: translateY(0); pointer-events: auto; }
.pg-mega-wrap { max-width: 1440px; margin: 0 auto; padding: 36px 40px 44px; display: grid; gap: 40px; }
.pg-mega-col-label {
    font-size: 9.5px; font-weight: 700; letter-spacing: .15em;
    text-transform: uppercase; color: #bb976d; margin-bottom: 16px;
}
.pg-mega-links { display: grid; grid-template-columns: repeat(auto-fill, minmax(148px, 1fr)); gap: 6px; }
.pg-mega-link {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 13px; border: 1px solid #f0ebe3;
    font-size: 12.5px; font-weight: 500; color: #172430;
    text-decoration: none; background: #fdfaf6;
    transition: all .18s;
}
.dark .pg-mega-link { color: #ddd; background: rgba(255,255,255,.04); border-color: rgba(255,255,255,.07); }
.pg-mega-link:hover { background: #bb976d; color: #fff; border-color: #bb976d; transform: translateY(-2px); box-shadow: 0 4px 14px rgba(187,151,109,.22); }
.pg-mega-dot { width: 5px; height: 5px; border-radius: 50%; background: #bb976d; flex-shrink: 0; transition: background .18s; }
.pg-mega-link:hover .pg-mega-dot { background: rgba(255,255,255,.6); }
.pg-mega-feat { border-left: 1px solid #ede8e1; padding-left: 36px; }
.dark .pg-mega-feat { border-color: rgba(255,255,255,.08); }
.pg-mega-feat-img { width: 100%; aspect-ratio: 4/3; object-fit: cover; display: block; margin-bottom: 14px; }
.pg-mega-feat-title { font-size: 14px; font-weight: 700; color: #172430; margin-bottom: 5px; }
.dark .pg-mega-feat-title { color: #fff; }
.pg-mega-feat-desc { font-size: 12px; color: #888; line-height: 1.55; margin-bottom: 14px; }
.pg-mega-feat-cta {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
    color: #bb976d; text-decoration: none;
    border-bottom: 1.5px solid #bb976d; padding-bottom: 2px;
    transition: gap .2s, color .2s;
}
.pg-mega-feat-cta:hover { gap: 12px; }

/* Backdrop */
#pg-mega-bd {
    position: fixed; inset: 0; z-index: 185;
    background: rgba(23,36,48,.38); backdrop-filter: blur(2px);
    opacity: 0; visibility: hidden;
    transition: opacity .22s, visibility .22s;
}
#pg-mega-bd.mega-open { opacity: 1; visibility: visible; }

/* ── Right actions ── */
.pg-hdr-actions { display: flex; align-items: center; gap: 2px; }
.pg-act-btn {
    width: 42px; height: 42px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: transparent; border: none; cursor: pointer;
    color: #172430; position: relative;
    transition: background .18s, color .18s;
    font-family: 'Poppins', sans-serif;
}
.dark .pg-act-btn { color: rgba(255,255,255,.85); }
.pg-act-btn:hover { background: #f5f0e8; color: #bb976d; }
.dark .pg-act-btn:hover { background: rgba(255,255,255,.08); color: #bb976d; }
.pg-act-btn svg { width: 19px; height: 19px; flex-shrink: 0; }
.pg-badge {
    position: absolute; top: 3px; right: 2px;
    min-width: 17px; height: 17px; padding: 0 3px;
    background: #bb976d; border-radius: 20px;
    font-size: 9px; font-weight: 700; color: #fff;
    display: flex; align-items: center; justify-content: center;
    line-height: 1; pointer-events: none; box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
.pg-hdr-vdiv { width: 1px; height: 22px; background: #e8e2da; margin: 0 8px; flex-shrink: 0; }
.dark .pg-hdr-vdiv { background: rgba(255,255,255,.12); }

/* Account dropdown */
.pg-acct-wrap { position: relative; display: flex; align-items: center; }
.pg-acct-dd {
    position: absolute; top: calc(100% + 8px); right: -8px;
    min-width: 192px; background: #fff;
    border: 1px solid #ede8e1;
    box-shadow: 0 16px 48px rgba(23,36,48,.10);
    padding: 6px 0;
    opacity: 0; visibility: hidden;
    transform: translateY(6px);
    transition: all .2s cubic-bezier(.4,0,.2,1);
    z-index: 210; font-family: 'Poppins', sans-serif;
}
.dark .pg-acct-dd { background: #1c2d3e; border-color: rgba(255,255,255,.08); }
.pg-acct-wrap:hover .pg-acct-dd { opacity: 1; visibility: visible; transform: translateY(0); }
.pg-acct-dd-top { padding: 12px 18px 10px; border-bottom: 1px solid #f0ebe3; margin-bottom: 4px; }
.dark .pg-acct-dd-top { border-color: rgba(255,255,255,.07); }
.pg-acct-dd-greeting { font-size: 9.5px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #bb976d; margin: 0 0 3px; }
.pg-acct-dd-name { font-size: 13px; font-weight: 600; color: #172430; margin: 0; }
.dark .pg-acct-dd-name { color: #fff; }
.pg-acct-dd a, .pg-acct-dd button {
    display: block; width: 100%; padding: 10px 18px;
    font-size: 12.5px; font-weight: 500; color: #555;
    text-decoration: none; background: transparent; border: none;
    text-align: left; cursor: pointer;
    font-family: 'Poppins', sans-serif;
    transition: color .15s, background .15s; box-sizing: border-box;
}
.dark .pg-acct-dd a, .dark .pg-acct-dd button { color: rgba(255,255,255,.7); }
.pg-acct-dd a:hover, .pg-acct-dd button:hover { color: #bb976d; background: #fdf8f2; }
.dark .pg-acct-dd a:hover, .dark .pg-acct-dd button:hover { background: rgba(255,255,255,.05); }
.pg-acct-dd-sep { height: 1px; background: #f0ebe3; margin: 4px 0; }
.dark .pg-acct-dd-sep { background: rgba(255,255,255,.07); }
.pg-acct-dd .logout-btn { color: #d94f4f; }
.pg-acct-dd .logout-btn:hover { color: #c03a3a; background: #fff5f5; }

/* ── Cart Drawer ── */
#pg-cart-drw {
    position: fixed; top: 0; right: 0;
    width: min(420px, 100vw); height: 100%;
    background: #fff; z-index: 300;
    display: flex; flex-direction: column;
    transform: translateX(100%);
    transition: transform .34s cubic-bezier(.4,0,.2,1);
    box-shadow: -8px 0 52px rgba(23,36,48,.13);
    font-family: 'Poppins', sans-serif;
}
.dark #pg-cart-drw { background: #172430; }
#pg-cart-drw.drw-open { transform: translateX(0); }
#pg-cart-ovl {
    position: fixed; inset: 0; z-index: 295;
    background: rgba(23,36,48,.52); backdrop-filter: blur(3px);
    opacity: 0; visibility: hidden;
    transition: opacity .34s, visibility .34s;
}
#pg-cart-ovl.drw-open { opacity: 1; visibility: visible; }
.pg-drw-hd {
    display: flex; align-items: center; justify-content: space-between;
    padding: 22px 26px; border-bottom: 1px solid #f0ebe3; flex-shrink: 0;
}
.dark .pg-drw-hd { border-color: rgba(255,255,255,.08); }
.pg-drw-title { font-size: 15px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #172430; margin: 0; }
.dark .pg-drw-title { color: #fff; }
.pg-drw-title span { font-size: 11.5px; font-weight: 400; color: #bb976d; text-transform: none; letter-spacing: 0; margin-left: 5px; }
.pg-drw-close-btn {
    width: 36px; height: 36px; border-radius: 50%;
    border: 1.5px solid #e8e2da; background: transparent; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #555; transition: all .18s;
}
.dark .pg-drw-close-btn { border-color: rgba(255,255,255,.15); color: #aaa; }
.pg-drw-close-btn:hover { background: #bb976d; border-color: #bb976d; color: #fff; }
.pg-drw-body { flex: 1; overflow-y: auto; padding: 8px 26px 16px; }
.pg-drw-body::-webkit-scrollbar { width: 3px; }
.pg-drw-body::-webkit-scrollbar-thumb { background: #ddd3c5; border-radius: 3px; }
.pg-ci {
    display: flex; gap: 14px; padding: 18px 0;
    border-bottom: 1px solid #f5f0e8; position: relative;
}
.dark .pg-ci { border-color: rgba(255,255,255,.06); }
.pg-ci-img { width: 74px; height: 74px; object-fit: cover; flex-shrink: 0; background: #f5f0e8; display: block; text-decoration: none; }
.pg-ci-body { flex: 1; min-width: 0; }
.pg-ci-cat { font-size: 9.5px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #bb976d; margin-bottom: 5px; }
.pg-ci-name { font-size: 13px; font-weight: 600; color: #172430; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-decoration: none; display: block; margin-bottom: 6px; }
.dark .pg-ci-name { color: #fff; }
.pg-ci-price { font-size: 13px; font-weight: 700; color: #172430; }
.dark .pg-ci-price { color: #fff; }
.pg-ci-qty { font-size: 11.5px; color: #aaa; margin-left: 4px; }
.pg-ci-rm {
    position: absolute; top: 18px; right: 0;
    width: 24px; height: 24px; border-radius: 50%;
    border: 1px solid #e8e2da; background: transparent; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #aaa; transition: all .18s;
}
.pg-ci-rm:hover { background: #E13939; border-color: #E13939; color: #fff; }
.pg-drw-empty {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    height: 100%; text-align: center; padding: 40px 20px;
}
.pg-drw-empty svg { color: #ddd3c5; margin-bottom: 14px; }
.pg-drw-empty p { font-size: 14px; color: #bbb; margin: 0; }
.pg-drw-ft {
    padding: 20px 26px; border-top: 1px solid #f0ebe3;
    background: #fdfaf6; flex-shrink: 0;
}
.dark .pg-drw-ft { background: rgba(255,255,255,.03); border-color: rgba(255,255,255,.08); }
.pg-drw-subtotal { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.pg-drw-sub-lbl { font-size: 10px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #aaa; }
.pg-drw-sub-val { font-size: 20px; font-weight: 700; color: #172430; }
.dark .pg-drw-sub-val { color: #fff; }
.pg-drw-btns { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.pg-drw-btn {
    height: 48px; display: flex; align-items: center; justify-content: center;
    font-family: 'Poppins', sans-serif; font-size: 11px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    text-decoration: none; border: none; cursor: pointer; transition: all .2s;
}
.pg-drw-btn-outline { background: transparent; border: 1.5px solid #172430; color: #172430; }
.dark .pg-drw-btn-outline { border-color: rgba(255,255,255,.3); color: #fff; }
.pg-drw-btn-outline:hover { background: #172430; color: #fff; }
.dark .pg-drw-btn-outline:hover { background: rgba(255,255,255,.12); }
.pg-drw-btn-fill { background: #bb976d; color: #fff; }
.pg-drw-btn-fill:hover { background: #a8845a; }

/* ── Search Modal ── */
#pg-srch-modal {
    position: fixed; inset: 0; z-index: 300;
    background: rgba(23,36,48,.75); backdrop-filter: blur(8px);
    display: flex; align-items: flex-start; padding-top: 80px;
    opacity: 0; visibility: hidden;
    transition: opacity .24s, visibility .24s;
    font-family: 'Poppins', sans-serif;
}
#pg-srch-modal.srch-open { opacity: 1; visibility: visible; }
.pg-srch-box {
    width: min(680px, calc(100% - 48px)); margin: 0 auto;
    background: #fff; padding: 32px 36px 36px;
    transform: translateY(-14px);
    transition: transform .28s cubic-bezier(.4,0,.2,1);
    position: relative;
}
.dark .pg-srch-box { background: #1c2d3e; }
#pg-srch-modal.srch-open .pg-srch-box { transform: translateY(0); }
.pg-srch-label { font-size: 9.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #bb976d; margin-bottom: 14px; }
.pg-srch-row { display: flex; align-items: center; gap: 12px; border-bottom: 2px solid #172430; padding-bottom: 10px; }
.dark .pg-srch-row { border-color: rgba(255,255,255,.3); }
.pg-srch-input {
    flex: 1; background: transparent; border: none; outline: none;
    font-family: 'Poppins', sans-serif; font-size: 22px; font-weight: 300;
    color: #172430; caret-color: #bb976d;
}
.dark .pg-srch-input { color: #fff; }
.pg-srch-input::placeholder { color: #ccc; }
.pg-srch-submit { background: transparent; border: none; cursor: pointer; color: #172430; transition: color .2s; flex-shrink: 0; }
.dark .pg-srch-submit { color: #fff; }
.pg-srch-submit:hover { color: #bb976d; }
.pg-srch-close {
    position: absolute; top: -52px; right: 0;
    width: 40px; height: 40px; border-radius: 50%;
    background: rgba(255,255,255,.12); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #fff; transition: background .2s;
}
.pg-srch-close:hover { background: rgba(255,255,255,.22); }
.pg-srch-tags { margin-top: 20px; display: flex; flex-wrap: wrap; align-items: center; gap: 8px; }
.pg-srch-tag-lbl { font-size: 9.5px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #bbb; white-space: nowrap; }
.pg-srch-tag {
    padding: 6px 13px; font-size: 11.5px; font-weight: 500; color: #666;
    background: #f5f0e8; text-decoration: none;
    border: 1px solid transparent; transition: all .17s;
}
.dark .pg-srch-tag { background: rgba(255,255,255,.07); color: #ccc; }
.pg-srch-tag:hover { background: #bb976d; color: #fff; }

/* ── Search Suggestions ── */
#pg-srch-results {
    display: none; margin-top: 0;
    border: 1px solid #e8e0d4; border-top: none;
    background: #fff; overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,.09);
}
#pg-srch-results.pg-srch-results-open { display: block; }
.pg-srch-ri {
    display: flex; align-items: center; gap: 14px;
    padding: 10px 14px; text-decoration: none; color: inherit;
    transition: background .15s; cursor: pointer;
    border-bottom: 1px solid #f5f0e8;
}
.pg-srch-ri:last-of-type { border-bottom: none; }
.pg-srch-ri:hover, .pg-srch-ri.pg-sri-active { background: #faf7f2; }
.pg-srch-ri-img { width: 46px; height: 46px; object-fit: cover; flex-shrink: 0; border: 1px solid #e8e0d4; }
.pg-srch-ri-body { flex: 1; min-width: 0; }
.pg-srch-ri-name { font-size: 13px; font-weight: 500; color: #172430; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; }
.pg-srch-ri-cat  { font-size: 10px; color: #aaa; margin-top: 2px; text-transform: uppercase; letter-spacing: .08em; display: block; }
.pg-srch-ri-price { font-size: 13px; font-weight: 600; color: #bb976d; white-space: nowrap; flex-shrink: 0; }
.pg-srch-ri-price s { color: #ccc; font-weight: 400; font-size: 11px; margin-right: 3px; text-decoration: line-through; }
.pg-srch-view-all {
    display: block; padding: 9px 14px; text-align: center;
    font-size: 11px; font-weight: 700; color: #bb976d;
    letter-spacing: .08em; text-transform: uppercase; text-decoration: none;
    background: #faf7f2; border-top: 1px solid #e8e0d4;
    transition: background .15s;
}
.pg-srch-view-all:hover { background: #f0e8d8; color: #8a6d4a; }
.pg-srch-spinner {
    display: flex; align-items: center; justify-content: center; padding: 18px; color: #bb976d;
}
.pg-srch-spinner svg { animation: srch-spin .7s linear infinite; }
@keyframes srch-spin { to { transform: rotate(360deg); } }
.pg-srch-empty { padding: 18px 14px; text-align: center; font-size: 13px; color: #aaa; }

/* ── Mobile header ── */
#pg-mob-hdr {
    display: none; position: sticky; top: 0; z-index: 200;
    width: 100%; height: 62px; background: #fff;
    border-bottom: 1px solid #ede8e1;
    box-shadow: 0 2px 14px rgba(23,36,48,.06);
    align-items: center; padding: 0 18px; gap: 10px;
    box-sizing: border-box; font-family: 'Poppins', sans-serif;
}
.dark #pg-mob-hdr { background: #172430; border-color: rgba(255,255,255,.08); }

/* ── Mobile nav drawer ── */
#pg-mob-ovl {
    position: fixed; inset: 0; z-index: 305;
    background: rgba(23,36,48,.52); backdrop-filter: blur(3px);
    opacity: 0; visibility: hidden;
    transition: opacity .32s, visibility .32s;
}
#pg-mob-ovl.drw-open { opacity: 1; visibility: visible; }
#pg-mob-drw {
    position: fixed; top: 0; left: 0;
    width: min(330px, 100vw); height: 100%;
    background: #fff; z-index: 310;
    display: flex; flex-direction: column; overflow-y: auto;
    transform: translateX(-100%);
    transition: transform .32s cubic-bezier(.4,0,.2,1);
    box-shadow: 8px 0 52px rgba(23,36,48,.13);
    font-family: 'Poppins', sans-serif;
}
.dark #pg-mob-drw { background: #172430; }
#pg-mob-drw.drw-open { transform: translateX(0); }
.pg-mob-drw-hd {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 22px; border-bottom: 1px solid #f0ebe3; flex-shrink: 0;
}
.dark .pg-mob-drw-hd { border-color: rgba(255,255,255,.08); }
.pg-mob-nav-link, .pg-mob-acc-btn {
    display: flex; align-items: center; justify-content: space-between;
    width: 100%; padding: 14px 22px;
    font-size: 12px; font-weight: 600; letter-spacing: .09em; text-transform: uppercase;
    color: #172430; text-decoration: none;
    background: transparent; border: none; border-bottom: 1px solid #f7f3ee;
    cursor: pointer; box-sizing: border-box;
    font-family: 'Poppins', sans-serif; transition: color .18s, background .18s;
}
.dark .pg-mob-nav-link, .dark .pg-mob-acc-btn { color: rgba(255,255,255,.85); border-color: rgba(255,255,255,.06); }
.pg-mob-nav-link:hover, .pg-mob-acc-btn:hover { color: #bb976d; background: #fdf8f2; }
.dark .pg-mob-nav-link:hover, .dark .pg-mob-acc-btn:hover { background: rgba(255,255,255,.04); }
.pg-mob-sub {
    max-height: 0; overflow: hidden; background: #fdfaf6;
    transition: max-height .3s cubic-bezier(.4,0,.2,1);
}
.dark .pg-mob-sub { background: rgba(0,0,0,.12); }
.pg-mob-sub.sub-open { max-height: 480px; }
.pg-mob-sub a {
    display: block; padding: 11px 22px 11px 34px;
    font-size: 12.5px; font-weight: 500; color: #666;
    text-decoration: none; border-bottom: 1px solid #f0ebe3;
    letter-spacing: .02em; transition: color .18s;
}
.dark .pg-mob-sub a { color: rgba(255,255,255,.6); border-color: rgba(255,255,255,.05); }
.pg-mob-sub a:hover { color: #bb976d; }
.pg-mob-acc-icon { flex-shrink: 0; transition: transform .24s; }

/* ── Responsive breakpoints ── */
@media (max-width: 1024px) {
    #pg-hdr { display: none !important; }
    #pg-mob-hdr { display: flex; }
}
@media (min-width: 1025px) {
    #pg-mob-hdr, #pg-mob-drw, #pg-mob-ovl { display: none !important; }
}
@media (max-width: 1200px) {
    .pg-hdr-inner { padding: 0 24px; }
    .pg-hdr-nav > li > a { padding: 0 12px; font-size: 11.5px; }
}
</style>

<!-- Mega backdrop -->
<div id="pg-mega-bd"></div>

<!-- ════════ DESKTOP HEADER ════════ -->
<header id="pg-hdr" role="banner">
    <div class="pg-hdr-inner">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="pg-hdr-logo" aria-label="PeytonGhalib">
            <img src="{{ asset('assets/img/logo.svg') }}" alt="PeytonGhalib" width="180" height="42">
        </a>

        {{-- Navigation --}}
        <nav aria-label="Main navigation">
            <ul class="pg-hdr-nav">

                <li><a href="{{ url('/') }}" class="pg-nav-home">Home</a></li>

                <li class="pg-has-mega" data-mega="pg-mega-shop">
                    <a href="{{ url('/shop') }}">
                        Shop
                        <svg class="pg-chevron" width="10" height="6" viewBox="0 0 10 6" fill="none">
                            <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </li>

                <li class="pg-has-mega" data-mega="pg-mega-cols">
                    <a href="{{ url('/categories') }}">
                        Collections
                        <svg class="pg-chevron" width="10" height="6" viewBox="0 0 10 6" fill="none">
                            <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </li>

                <li><a href="{{ url('/about') }}">About Us</a></li>
                <li><a href="{{ url('/contact') }}">Contact</a></li>
                <li style="margin-left:8px;">
                    <a href="{{ route('track-order') }}"
                       style="display:inline-flex;align-items:center;gap:7px;
                              height:36px;padding:0 16px;
                              background:#bb976d;border:1.5px solid #bb976d;
                              font-size:11.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;
                              color:#fff;text-decoration:none;white-space:nowrap;
                              transition:background .2s,border-color .2s;line-height:1;"
                       onmouseover="this.style.background='#a8845a';this.style.borderColor='#a8845a'"
                       onmouseout="this.style.background='#bb976d';this.style.borderColor='#bb976d'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                            <path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/>
                            <polyline points="16.5 9.4 7.55 4.24"/>
                            <polyline points="3.29 7 12 12 20.71 7"/>
                            <line x1="12" y1="22" x2="12" y2="12"/>
                            <circle cx="18.5" cy="15.5" r="2.5"/>
                            <path d="M20.27 17.27 22 19"/>
                        </svg>
                        Track Order
                    </a>
                </li>

            </ul>
        </nav>

        {{-- Right actions --}}
        <div class="pg-hdr-actions">

            {{-- Search --}}
            <button class="pg-act-btn" id="pg-srch-open-btn" aria-label="Search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
            </button>

            {{-- Account --}}
            @auth
            <div class="pg-acct-wrap">
                <button class="pg-act-btn" aria-label="My Account">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </button>
                <div class="pg-acct-dd">
                    <div class="pg-acct-dd-top">
                        <p class="pg-acct-dd-greeting">Welcome back</p>
                        <p class="pg-acct-dd-name">{{ Auth::user()->name }}</p>
                    </div>
                    <a href="{{ url('/my-account') }}">My Account</a>
                    <a href="{{ url('/order-history') }}">Order History</a>
                    <a href="{{ url('/wishlist') }}">Wishlist</a>
                    <div class="pg-acct-dd-sep"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ url('/login') }}" class="pg-act-btn" aria-label="Login">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
            </a>
            @endauth

            {{-- Wishlist --}}
            <a href="{{ url('/wishlist') }}" class="pg-act-btn" aria-label="Wishlist">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <span class="pg-badge" id="nav-wishlist-count" @if($navWishlistCount < 1) style="display:none" @endif>{{ $navWishlistCount }}</span>
            </a>

            {{-- Cart --}}
            <button class="pg-act-btn" id="pg-cart-open-btn" aria-label="Shopping cart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>
                </svg>
                <span class="pg-badge" id="pg-cart-badge">{{ $cart->count() }}</span>
            </button>

            <div class="pg-hdr-vdiv"></div>

            {{-- Dark mode --}}
            <label class="switcher cursor-pointer" style="display:flex;align-items:center;">
                <input class="hidden" type="checkbox">
                <img class="moon" style="width:22px;" src="{{ asset('assets/img/icon/simple-sun.svg') }}" alt="Light mode">
                <img class="sun"  style="width:22px;" src="{{ asset('assets/img/icon/simple-light.svg') }}" alt="Dark mode">
            </label>

        </div>
    </div>
</header>

<!-- ════════ MEGA: Shop ════════ -->
<div id="pg-mega-shop" class="pg-mega" role="region" aria-label="Shop categories">
    <div class="pg-mega-wrap" style="grid-template-columns: 1fr 220px;">
        <div>
            <p class="pg-mega-col-label">Browse by Category</p>
            <div class="pg-mega-links">
                @foreach($navCategories as $nCat)
                <a href="{{ route('category.landing', $nCat->slug) }}" class="pg-mega-link">
                    <span class="pg-mega-dot"></span>{{ $nCat->name }}
                </a>
                @endforeach
                <a href="{{ url('/shop') }}" class="pg-mega-link" style="background:#172430;color:#fff;border-color:#172430;">
                    <span class="pg-mega-dot" style="background:rgba(255,255,255,.5);"></span>All Products
                </a>
            </div>
        </div>
        <div class="pg-mega-feat">
            <p class="pg-mega-col-label">Featured</p>
            <img src="{{ asset('assets/img/home-v1/pdct-cgry-01.jpg') }}" alt="New Arrivals" class="pg-mega-feat-img">
            <p class="pg-mega-feat-title">New Arrivals</p>
            <p class="pg-mega-feat-desc">Discover the latest pieces crafted for modern living spaces.</p>
            <a href="{{ url('/shop') }}?sort=newest" class="pg-mega-feat-cta">
                Shop Now
                <svg width="12" height="8" viewBox="0 0 16 10" fill="none"><path d="M1 5H15M15 5L11 1M15 5L11 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </div>
</div>

<!-- ════════ MEGA: Collections ════════ -->
<div id="pg-mega-cols" class="pg-mega" role="region" aria-label="Collections">
    <div class="pg-mega-wrap" style="grid-template-columns: 1fr 1fr 220px;">
        <div>
            <p class="pg-mega-col-label">Shop by Room</p>
            <div class="pg-mega-links">
                <a href="{{ url('/shop') }}?room=living-room"  class="pg-mega-link"><span class="pg-mega-dot"></span>Living Room</a>
                <a href="{{ url('/shop') }}?room=bedroom"      class="pg-mega-link"><span class="pg-mega-dot"></span>Bedroom</a>
                <a href="{{ url('/shop') }}?room=dining"       class="pg-mega-link"><span class="pg-mega-dot"></span>Dining Room</a>
                <a href="{{ url('/shop') }}?room=office"       class="pg-mega-link"><span class="pg-mega-dot"></span>Home Office</a>
                <a href="{{ url('/shop') }}?room=outdoor"      class="pg-mega-link"><span class="pg-mega-dot"></span>Outdoor</a>
                <a href="{{ url('/shop') }}?room=bathroom"     class="pg-mega-link"><span class="pg-mega-dot"></span>Bathroom</a>
            </div>
        </div>
        <div>
            <p class="pg-mega-col-label">Curated Collections</p>
            <div class="pg-mega-links">
                <a href="{{ url('/shop') }}?collection=modern"        class="pg-mega-link"><span class="pg-mega-dot"></span>Modern Luxury</a>
                <a href="{{ url('/shop') }}?collection=scandinavian"  class="pg-mega-link"><span class="pg-mega-dot"></span>Scandinavian</a>
                <a href="{{ url('/shop') }}?collection=industrial"    class="pg-mega-link"><span class="pg-mega-dot"></span>Industrial</a>
                <a href="{{ url('/shop') }}?collection=bohemian"      class="pg-mega-link"><span class="pg-mega-dot"></span>Bohemian</a>
                <a href="{{ url('/shop') }}?collection=minimalist"    class="pg-mega-link"><span class="pg-mega-dot"></span>Minimalist</a>
                <a href="{{ url('/shop') }}?collection=classic"       class="pg-mega-link"><span class="pg-mega-dot"></span>Classic</a>
            </div>
        </div>
        <div class="pg-mega-feat">
            <p class="pg-mega-col-label">Spotlight</p>
            <img src="{{ asset('assets/img/home-v1/pdct-cgry-02.jpg') }}" alt="Modern Luxury" class="pg-mega-feat-img">
            <p class="pg-mega-feat-title">Modern Luxury</p>
            <p class="pg-mega-feat-desc">Timeless pieces that define contemporary elegance.</p>
            <a href="{{ url('/shop') }}?collection=modern" class="pg-mega-feat-cta">
                Explore
                <svg width="12" height="8" viewBox="0 0 16 10" fill="none"><path d="M1 5H15M15 5L11 1M15 5L11 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </div>
</div>

<!-- ════════ MOBILE HEADER ════════ -->
<div id="pg-mob-hdr" role="banner">
    <button id="pg-mob-menu-btn" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:transparent;border:none;cursor:pointer;color:#172430;flex-shrink:0;" aria-label="Open menu" class="dark:text-white">
        <svg width="22" height="15" viewBox="0 0 22 15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="0" y1="1.5" x2="22" y2="1.5"/><line x1="0" y1="7.5" x2="22" y2="7.5"/><line x1="0" y1="13.5" x2="22" y2="13.5"/>
        </svg>
    </button>
    <a href="{{ url('/') }}" style="flex:1;display:flex;justify-content:center;" aria-label="PeytonGhalib">
        <img src="{{ asset('assets/img/logo.svg') }}" alt="PeytonGhalib" height="34">
    </a>
    <div style="display:flex;align-items:center;gap:2px;flex-shrink:0;">
        <button class="pg-act-btn" id="pg-mob-srch-btn" aria-label="Search" style="width:38px;height:38px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </button>
        <button class="pg-act-btn" id="pg-mob-cart-btn" aria-label="Cart" style="width:38px;height:38px;position:relative;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            <span class="pg-badge" style="width:15px;height:15px;font-size:8px;top:2px;right:1px;">{{ $cart->count() }}</span>
        </button>
        <label class="switcher cursor-pointer" style="display:flex;align-items:center;margin-left:4px;">
            <input class="hidden" type="checkbox">
            <img class="moon" style="width:20px;" src="{{ asset('assets/img/icon/simple-sun.svg') }}" alt="">
            <img class="sun"  style="width:20px;" src="{{ asset('assets/img/icon/simple-light.svg') }}" alt="">
        </label>
    </div>
</div>

<!-- Mobile nav overlay + drawer -->
<div id="pg-mob-ovl"></div>
<div id="pg-mob-drw" role="dialog" aria-modal="true" aria-label="Navigation">
    <div class="pg-mob-drw-hd">
        <a href="{{ url('/') }}"><img src="{{ asset('assets/img/logo.svg') }}" alt="PeytonGhalib" height="30"></a>
        <button id="pg-mob-drw-close" style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;border-radius:50%;border:1.5px solid #e8e2da;background:transparent;cursor:pointer;color:#555;transition:all .18s;" onmouseover="this.style.background='#bb976d';this.style.borderColor='#bb976d';this.style.color='#fff'" onmouseout="this.style.background='transparent';this.style.borderColor='#e8e2da';this.style.color='#555'" aria-label="Close">
            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="1" y1="1" x2="12" y2="12"/><line x1="12" y1="1" x2="1" y2="12"/></svg>
        </button>
    </div>
    <nav style="flex:1;">
        <a href="{{ url('/') }}" class="pg-mob-nav-link">Home</a>

        <button class="pg-mob-acc-btn" data-target="pg-mob-sub-shop">
            Shop
            <svg class="pg-mob-acc-icon" width="11" height="7" viewBox="0 0 11 7" fill="none" stroke="#172430" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 1L5.5 6L10 1"/></svg>
        </button>
        <div id="pg-mob-sub-shop" class="pg-mob-sub">
            @foreach($navCategories as $nCat)
            <a href="{{ route('category.landing', $nCat->slug) }}">{{ $nCat->name }}</a>
            @endforeach
            <a href="{{ url('/shop') }}" style="font-weight:700;color:#bb976d;">View All Products →</a>
        </div>

        <button class="pg-mob-acc-btn" data-target="pg-mob-sub-cols">
            Collections
            <svg class="pg-mob-acc-icon" width="11" height="7" viewBox="0 0 11 7" fill="none" stroke="#172430" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 1L5.5 6L10 1"/></svg>
        </button>
        <div id="pg-mob-sub-cols" class="pg-mob-sub">
            <a href="{{ url('/shop') }}?room=living-room">Living Room</a>
            <a href="{{ url('/shop') }}?room=bedroom">Bedroom</a>
            <a href="{{ url('/shop') }}?room=dining">Dining Room</a>
            <a href="{{ url('/shop') }}?collection=modern">Modern Luxury</a>
            <a href="{{ url('/shop') }}?collection=scandinavian">Scandinavian</a>
            <a href="{{ url('/categories') }}" style="font-weight:700;color:#bb976d;">View All Collections →</a>
        </div>

        <a href="{{ url('/about') }}"   class="pg-mob-nav-link">About Us</a>
        <a href="{{ url('/contact') }}" class="pg-mob-nav-link">Contact</a>

        @auth
        <a href="{{ url('/my-account') }}"  class="pg-mob-nav-link">My Account</a>
        <a href="{{ url('/order-history') }}" class="pg-mob-nav-link">Orders</a>
        @else
        <a href="{{ url('/login') }}" class="pg-mob-nav-link">Login / Register</a>
        @endauth
        <div style="padding:16px 22px 4px;">
            <a href="{{ route('track-order') }}"
               style="display:flex;align-items:center;justify-content:center;gap:8px;
                      height:44px;background:#bb976d;border:1.5px solid #bb976d;
                      font-family:'Poppins',sans-serif;font-size:12px;font-weight:700;
                      letter-spacing:.09em;text-transform:uppercase;
                      color:#fff;text-decoration:none;
                      transition:background .2s;"
               onmouseover="this.style.background='#a8845a'"
               onmouseout="this.style.background='#bb976d'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/>
                    <polyline points="16.5 9.4 7.55 4.24"/>
                    <polyline points="3.29 7 12 12 20.71 7"/>
                    <line x1="12" y1="22" x2="12" y2="12"/>
                    <circle cx="18.5" cy="15.5" r="2.5"/>
                    <path d="M20.27 17.27 22 19"/>
                </svg>
                Track Order
            </a>
        </div>
    </nav>
    <div style="padding:18px 22px;border-top:1px solid #f0ebe3;">
        <a href="{{ route('track-order') }}" style="display:flex;align-items:center;gap:12px;padding:13px 16px;background:#fdf6ee;border:1.5px solid #e8c99a;text-decoration:none;">
            <div style="width:34px;height:34px;border-radius:50%;background:#bb976d;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/><polyline points="16.5 9.4 7.55 4.24"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" y1="22" x2="12" y2="12"/><circle cx="18.5" cy="15.5" r="2.5"/><path d="M20.27 17.27 22 19"/></svg>
            </div>
            <div>
                <div style="font-size:10px;color:#aaa;font-family:'Poppins',sans-serif;">Where's my package?</div>
                <div style="font-size:13px;font-weight:700;color:#172430;font-family:'Poppins',sans-serif;">Track Your Order</div>
            </div>
        </a>
    </div>
</div>

<!-- ════════ CART DRAWER ════════ -->
<div id="pg-cart-ovl"></div>
<div id="pg-cart-drw" role="dialog" aria-modal="true" aria-label="Shopping cart">
    <div class="pg-drw-hd">
        <h2 class="pg-drw-title">Your Cart <span>({{ $cart->count() }} {{ $cart->count() === 1 ? 'item' : 'items' }})</span></h2>
        <button id="pg-cart-close-btn" class="pg-drw-close-btn" aria-label="Close cart">
            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="1" y1="1" x2="12" y2="12"/><line x1="12" y1="1" x2="1" y2="12"/></svg>
        </button>
    </div>
    <div class="pg-drw-body">
        @forelse($cart->items() as $ci)
        <div class="pg-ci">
            <a href="{{ route('product-details', $ci['slug']) }}">
                @if(!empty($ci['image']) && str_starts_with($ci['image'], 'assets/'))
                    <img class="pg-ci-img" src="{{ asset($ci['image']) }}" alt="{{ $ci['name'] }}">
                @elseif(!empty($ci['image']))
                    <img class="pg-ci-img" src="{{ Storage::url($ci['image']) }}" alt="{{ $ci['name'] }}">
                @else
                    <img class="pg-ci-img" src="{{ asset('assets/img/gallery/wishList-01.jpg') }}" alt="{{ $ci['name'] }}">
                @endif
            </a>
            <div class="pg-ci-body">
                <div class="pg-ci-cat">{{ $ci['category'] ?? 'Furniture' }}</div>
                <a href="{{ route('product-details', $ci['slug']) }}" class="pg-ci-name">{{ $ci['name'] }}</a>
                <div class="pg-ci-price">${{ number_format($ci['price'], 2) }}<span class="pg-ci-qty">× {{ $ci['qty'] }}</span></div>
            </div>
            <form action="{{ route('cart.remove') }}" method="POST" style="margin:0;">
                @csrf
                <input type="hidden" name="cart_key" value="{{ $ci['key'] }}">
                <button type="submit" class="pg-ci-rm" aria-label="Remove">
                    <svg width="9" height="9" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="1" y1="1" x2="11" y2="11"/><line x1="11" y1="1" x2="1" y2="11"/></svg>
                </button>
            </form>
        </div>
        @empty
        <div class="pg-drw-empty">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            <p>Your cart is empty</p>
        </div>
        @endforelse
    </div>
    @if($cart->count() > 0)
    <div class="pg-drw-ft">
        <div class="pg-drw-subtotal">
            <span class="pg-drw-sub-lbl">Subtotal</span>
            <span class="pg-drw-sub-val">${{ number_format($cart->total(), 2) }}</span>
        </div>
        <div class="pg-drw-btns">
            <a href="{{ route('cart') }}" class="pg-drw-btn pg-drw-btn-outline">View Cart</a>
            @auth
                <a href="{{ url('/checkout') }}" class="pg-drw-btn pg-drw-btn-fill">Checkout</a>
            @else
                <a href="{{ url('/login') }}?redirect={{ url('/checkout') }}" class="pg-drw-btn pg-drw-btn-fill">Checkout</a>
            @endauth
        </div>
    </div>
    @endif
</div>

<!-- ════════ SEARCH MODAL ════════ -->
<div id="pg-srch-modal" role="dialog" aria-modal="true" aria-label="Search">
    <div class="pg-srch-box">
        <button id="pg-srch-close-btn" class="pg-srch-close" aria-label="Close search">
            <svg width="15" height="15" viewBox="0 0 13 13" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="1" y1="1" x2="12" y2="12"/><line x1="12" y1="1" x2="1" y2="12"/></svg>
        </button>
        <p class="pg-srch-label">What are you looking for?</p>
        <form action="{{ url('/shop') }}" method="GET">
            <div class="pg-srch-row">
                <input type="text" name="search" id="pg-srch-input" class="pg-srch-input" placeholder="Search products, styles, rooms…" autocomplete="off">
                <button type="submit" class="pg-srch-submit" aria-label="Search">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </button>
            </div>
        </form>
        <div id="pg-srch-results" role="listbox" aria-label="Search suggestions"></div>
        <div class="pg-srch-tags" id="pg-srch-tags">
            <span class="pg-srch-tag-lbl">Popular:</span>
            @foreach($navCategories->take(5) as $popularCat)
            <a href="{{ url('/shop') }}?search={{ urlencode($popularCat->name) }}" class="pg-srch-tag">{{ $popularCat->name }}</a>
            @endforeach
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    /* ── Sticky scroll shadow ── */
    var hdr = document.getElementById('pg-hdr');
    if (hdr) {
        window.addEventListener('scroll', function () {
            hdr.classList.toggle('scrolled', window.scrollY > 8);
        }, { passive: true });
    }

    /* ── Mega menus ── */
    var megaBd  = document.getElementById('pg-mega-bd');
    var megaTimer;

    function closeMegas() {
        document.querySelectorAll('.pg-mega.mega-open').forEach(function (m) { m.classList.remove('mega-open'); });
        document.querySelectorAll('.pg-has-mega.mega-open').forEach(function (l) { l.classList.remove('mega-open'); });
        megaBd.classList.remove('mega-open');
    }

    document.querySelectorAll('.pg-has-mega').forEach(function (li) {
        var mega = document.getElementById(li.getAttribute('data-mega'));
        if (!mega || !hdr) return;

        function openIt() {
            clearTimeout(megaTimer);
            mega.style.top = hdr.getBoundingClientRect().bottom + 'px';
            document.querySelectorAll('.pg-mega.mega-open').forEach(function (m) { if (m !== mega) m.classList.remove('mega-open'); });
            document.querySelectorAll('.pg-has-mega.mega-open').forEach(function (l) { if (l !== li) l.classList.remove('mega-open'); });
            mega.classList.add('mega-open');
            megaBd.classList.add('mega-open');
            li.classList.add('mega-open');
        }
        function schedClose() {
            megaTimer = setTimeout(closeMegas, 110);
        }

        li.addEventListener('mouseenter', openIt);
        li.addEventListener('mouseleave', schedClose);
        mega.addEventListener('mouseenter', function () { clearTimeout(megaTimer); });
        mega.addEventListener('mouseleave', schedClose);
    });

    megaBd.addEventListener('click', closeMegas);

    /* ── Cart Drawer ── */
    var cartDrw  = document.getElementById('pg-cart-drw');
    var cartOvl  = document.getElementById('pg-cart-ovl');

    function openCart()  { cartDrw.classList.add('drw-open'); cartOvl.classList.add('drw-open'); document.body.style.overflow = 'hidden'; }
    function closeCart() { cartDrw.classList.remove('drw-open'); cartOvl.classList.remove('drw-open'); document.body.style.overflow = ''; }

    ['pg-cart-open-btn','pg-mob-cart-btn'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', openCart);
    });
    var cartClose = document.getElementById('pg-cart-close-btn');
    if (cartClose) cartClose.addEventListener('click', closeCart);
    if (cartOvl)   cartOvl.addEventListener('click', closeCart);
    if (cartDrw) {
        var cTx = 0;
        cartDrw.addEventListener('touchstart', function (e) { cTx = e.changedTouches[0].clientX; }, { passive: true });
        cartDrw.addEventListener('touchend',   function (e) { if (e.changedTouches[0].clientX - cTx > 60) closeCart(); }, { passive: true });
    }

    /* ── Search Modal ── */
    var srchModal   = document.getElementById('pg-srch-modal');
    var srchInput   = document.getElementById('pg-srch-input');
    var srchResults = document.getElementById('pg-srch-results');
    var srchTagsEl  = document.getElementById('pg-srch-tags');

    function hideSuggestions() {
        srchResults.classList.remove('pg-srch-results-open');
        srchResults.innerHTML = '';
        if (srchTagsEl) srchTagsEl.style.display = '';
    }

    function openSearch()  { srchModal.classList.add('srch-open'); document.body.style.overflow = 'hidden'; setTimeout(function () { srchInput && srchInput.focus(); }, 200); }
    function closeSearch() { srchModal.classList.remove('srch-open'); document.body.style.overflow = ''; hideSuggestions(); }

    ['pg-srch-open-btn','pg-mob-srch-btn'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', openSearch);
    });
    var srchClose = document.getElementById('pg-srch-close-btn');
    if (srchClose) srchClose.addEventListener('click', closeSearch);
    srchModal.addEventListener('click', function (e) { if (e.target === srchModal) closeSearch(); });

    /* ── Live Search Suggestions ── */
    var srchDebounce, srchAbort, srchCursor = -1;
    var suggestUrl = '{{ route("search.suggestions") }}';

    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function renderSpinner() {
        srchResults.innerHTML = '<div class="pg-srch-spinner"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg></div>';
        srchResults.classList.add('pg-srch-results-open');
        if (srchTagsEl) srchTagsEl.style.display = 'none';
    }

    function renderResults(items, q) {
        if (!items.length) {
            srchResults.innerHTML = '<p class="pg-srch-empty">No results for &ldquo;' + escHtml(q) + '&rdquo;</p>';
            return;
        }
        var html = '';
        items.forEach(function (p, i) {
            var priceHtml = p.sale_price
                ? '<s>$' + escHtml(p.price) + '</s>$' + escHtml(p.sale_price)
                : '$' + escHtml(p.price);
            html += '<a href="' + escHtml(p.url) + '" class="pg-srch-ri" role="option" data-idx="' + i + '">'
                  + '<img class="pg-srch-ri-img" src="' + escHtml(p.image) + '" alt="" loading="lazy">'
                  + '<span class="pg-srch-ri-body">'
                  +   '<span class="pg-srch-ri-name">' + escHtml(p.name) + '</span>'
                  +   (p.category ? '<span class="pg-srch-ri-cat">' + escHtml(p.category) + '</span>' : '')
                  + '</span>'
                  + '<span class="pg-srch-ri-price">' + priceHtml + '</span>'
                  + '</a>';
        });
        html += '<a href="/shop?search=' + encodeURIComponent(q) + '" class="pg-srch-view-all">View all results &rarr;</a>';
        srchResults.innerHTML = html;
    }

    function fetchSuggestions(q) {
        if (srchAbort) { try { srchAbort.abort(); } catch(e){} }
        srchAbort = new AbortController();
        renderSpinner();
        fetch(suggestUrl + '?q=' + encodeURIComponent(q), { signal: srchAbort.signal })
            .then(function (r) { return r.ok ? r.json() : []; })
            .then(function (data) { renderResults(data, q); })
            .catch(function () {});
    }

    if (srchInput) {
        srchInput.addEventListener('input', function () {
            clearTimeout(srchDebounce);
            srchCursor = -1;
            var q = srchInput.value.trim();
            if (q.length < 2) { hideSuggestions(); return; }
            srchDebounce = setTimeout(function () { fetchSuggestions(q); }, 300);
        });

        srchInput.addEventListener('keydown', function (e) {
            if (!srchResults.classList.contains('pg-srch-results-open')) return;
            var items = srchResults.querySelectorAll('.pg-srch-ri');
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                srchCursor = Math.min(srchCursor + 1, items.length - 1);
                items.forEach(function (el, i) { el.classList.toggle('pg-sri-active', i === srchCursor); });
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                srchCursor = Math.max(srchCursor - 1, -1);
                items.forEach(function (el, i) { el.classList.toggle('pg-sri-active', i === srchCursor); });
            } else if (e.key === 'Enter' && srchCursor >= 0 && items[srchCursor]) {
                e.preventDefault();
                window.location.href = items[srchCursor].href;
            } else if (e.key === 'Escape') {
                hideSuggestions();
            }
        });
    }

    /* ── Mobile Nav Drawer ── */
    var mobDrw  = document.getElementById('pg-mob-drw');
    var mobOvl  = document.getElementById('pg-mob-ovl');

    function openMobNav()  { mobDrw.classList.add('drw-open'); mobOvl.classList.add('drw-open'); document.body.style.overflow = 'hidden'; }
    function closeMobNav() { mobDrw.classList.remove('drw-open'); mobOvl.classList.remove('drw-open'); document.body.style.overflow = ''; }

    var mobMenuBtn  = document.getElementById('pg-mob-menu-btn');
    var mobDrwClose = document.getElementById('pg-mob-drw-close');
    if (mobMenuBtn)  mobMenuBtn.addEventListener('click', openMobNav);
    if (mobDrwClose) mobDrwClose.addEventListener('click', closeMobNav);
    if (mobOvl)      mobOvl.addEventListener('click', closeMobNav);
    if (mobDrw) {
        var nTx = 0;
        mobDrw.addEventListener('touchstart', function (e) { nTx = e.changedTouches[0].clientX; }, { passive: true });
        mobDrw.addEventListener('touchend',   function (e) { if (nTx - e.changedTouches[0].clientX > 60) closeMobNav(); }, { passive: true });
    }

    /* ── Mobile accordions ── */
    document.querySelectorAll('.pg-mob-acc-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var sub  = document.getElementById(btn.getAttribute('data-target'));
            var icon = btn.querySelector('.pg-mob-acc-icon');
            if (!sub) return;
            var open = sub.classList.toggle('sub-open');
            if (icon) icon.style.transform = open ? 'rotate(180deg)' : '';
        });
    });

    /* ── Escape key ── */
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        hideSuggestions(); closeCart(); closeSearch(); closeMobNav(); closeMegas();
    });

    /* ── Active nav link ── */
    var cp = window.location.pathname.replace(/\/$/, '');
    document.querySelectorAll('.pg-hdr-nav > li > a').forEach(function (a) {
        try {
            var lp = new URL(a.href).pathname.replace(/\/$/, '');
            if (lp && lp === cp) a.classList.add('pg-active');
        } catch (e) {}
    });

}());
</script>
