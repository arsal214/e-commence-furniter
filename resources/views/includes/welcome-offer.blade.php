{{-- New-customer welcome offer UI. Display only: the server decides eligibility, tiers,
     expiry and the discount (WelcomeOfferService). Loads after the page has painted and
     renders nothing unless /welcome/status says an offer is live for this visitor. --}}
<style>
    .pgw-bar{position:fixed;left:0;right:0;bottom:0;z-index:60;display:flex;gap:12px;align-items:center;justify-content:center;flex-wrap:wrap;padding:10px 44px 10px 16px;background:#172430;color:#fff;font-size:14px;line-height:1.35}
    .pgw-bar[hidden],.pgw-modal[hidden],.pgw-cart[hidden],.pgw-pline[hidden]{display:none}
    .pgw-bar button.pgw-cta{background:#bb976d;color:#fff;border:0;border-radius:8px;padding:8px 16px;font-weight:700;cursor:pointer}
    .pgw-bar .pgw-x{position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:0;color:#fff;font-size:22px;line-height:1;padding:8px;cursor:pointer}
    .pgw-count{font-variant-numeric:tabular-nums;font-weight:700}
    body.pgw-has-bar #back-to-top{bottom:4.75rem !important}   /* keep the back-to-top button clear of the bar */
    .pgw-modal{position:fixed;inset:0;z-index:100000;display:flex;align-items:center;justify-content:center;padding:16px}
    .pgw-scrim{position:absolute;inset:0;background:rgba(15,23,32,.6)}
    .pgw-box{position:relative;background:#fff;color:#172430;border-radius:14px;max-width:440px;width:100%;padding:28px 24px;max-height:92vh;overflow:auto}
    .pgw-box h2{margin:0 0 6px;font-size:22px}
    .pgw-close{position:absolute;right:10px;top:10px;background:none;border:0;font-size:26px;line-height:1;padding:8px;cursor:pointer;color:#172430}
    .pgw-tickets{list-style:none;margin:16px 0;padding:0;display:grid;gap:8px}
    .pgw-tickets li{border:2px dashed #bb976d;border-radius:10px;padding:10px 14px;font-weight:700;background:#faf5ee}
    .pgw-box input[type=email]{width:100%;border:1px solid #d5dbe0;border-radius:8px;padding:11px 12px;font-size:16px}
    .pgw-box .pgw-go{width:100%;margin-top:10px;background:#bb976d;color:#fff;border:0;border-radius:8px;padding:12px;font-weight:700;font-size:16px;cursor:pointer}
    .pgw-box .pgw-go[disabled]{opacity:.6;cursor:wait}
    .pgw-sms{display:flex;gap:8px;align-items:flex-start;margin-top:10px;font-size:12px;color:#556270}
    .pgw-err{color:#b42318;font-size:13px;margin-top:8px}
    .pgw-fine{font-size:12px;color:#6b7680;margin-top:10px}
    .pgw-cart{border:1px solid #e5d9c8;background:#faf5ee;border-radius:10px;padding:10px 12px;margin-bottom:12px;font-size:14px}
    .pgw-track{height:6px;background:#eadfce;border-radius:99px;margin-top:8px;overflow:hidden}
    .pgw-track i{display:block;height:100%;background:#bb976d}
    .pgw-pline{margin:10px 0;padding:10px 12px;border:1px dashed #bb976d;border-radius:10px;background:#faf5ee;font-size:14px}
    .pgw-pline button{background:none;border:0;color:#8a6a40;font-weight:700;text-decoration:underline;cursor:pointer;padding:0 0 0 6px}
</style>

<div id="pgw-bar" class="pgw-bar" role="region" aria-label="Welcome offer" hidden>
    <span id="pgw-bar-text"></span>
    <button type="button" class="pgw-cta" id="pgw-bar-btn"></button>
    <button type="button" class="pgw-x" id="pgw-bar-x" aria-label="Dismiss welcome offer">&times;</button>
</div>

<div id="pgw-modal" class="pgw-modal" role="dialog" aria-modal="true" aria-labelledby="pgw-title" hidden>
    <div class="pgw-scrim" data-pgw-close></div>
    <div class="pgw-box">
        <button type="button" class="pgw-close" data-pgw-close aria-label="Close">&times;</button>
        <h2 id="pgw-title"></h2>
        <div id="pgw-claim-view">
            <ul class="pgw-tickets" id="pgw-tickets"></ul>
            <form id="pgw-form" novalidate>
                <label for="pgw-email" class="sr-only">Email address</label>
                <input id="pgw-email" type="email" name="email" autocomplete="email" inputmode="email" placeholder="Your email" required>
                <label class="pgw-sms"><input type="checkbox" id="pgw-sms"> <span id="pgw-sms-text"></span></label>
                <div class="pgw-err" id="pgw-err" role="alert"></div>
                <button type="submit" class="pgw-go" id="pgw-go"></button>
                <p class="pgw-fine">For first orders only. By claiming you agree to receive this offer by email. <a href="{{ route('privacy-policy') }}">Privacy policy</a>.</p>
            </form>
        </div>
        <div id="pgw-done-view" hidden>
            <p>Your deal is saved and applies automatically when you check out with <strong id="pgw-done-email"></strong>.</p>
            <p>Code: <strong id="pgw-done-code"></strong><br>Time left: <span class="pgw-count" data-pgw-count></span></p>
            <button type="button" class="pgw-go" data-pgw-close>Start shopping</button>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';
    var path = location.pathname;
    if (/^\/(checkout|thank-you|pay\/|admin|login|register)/.test(path)) return;

    var $ = function (id) { return document.getElementById(id); };
    var state = null, shownAt = Date.now(), lastFocus = null, timer = null;
    var store = function (fn) { try { return fn(); } catch (e) { return null; } };

    function money(n) { return '$' + (Math.round(n * 100) / 100).toFixed(2).replace(/\.00$/, ''); }
    function csrf() { var m = document.querySelector('meta[name="csrf-token"]'); return m ? m.content : ''; }

    function remaining() {
        if (!state || !state.claim) return 0;
        return Math.max(0, Math.floor((new Date(state.claim.expires_at).getTime() - Date.now()) / 1000));
    }
    function fmt(s) {
        var h = Math.floor(s / 3600), m = Math.floor(s % 3600 / 60), x = s % 60;
        return (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m + ':' + (x < 10 ? '0' : '') + x;
    }

    function tick() {
        var left = remaining();
        document.querySelectorAll('[data-pgw-count]').forEach(function (el) { el.textContent = fmt(left); });
        if (state && state.claim && state.claim.active && left === 0) { load(); }   // real deadline reached: ask the server
    }

    function setBar(hidden) {
        $('pgw-bar').hidden = hidden;
        document.body.classList.toggle('pgw-has-bar', !hidden);
    }

    function render() {
        var bar = $('pgw-bar');
        if (!state || !state.enabled) { setBar(true); document.querySelectorAll('[data-welcome-product],[data-welcome-cart]').forEach(function (e) { e.hidden = true; }); return; }
        var c = state.claim, active = c && c.active;

        if (active) {
            $('pgw-bar-text').textContent = 'Welcome deal active: ';
            var span = document.createElement('span'); span.className = 'pgw-count'; span.setAttribute('data-pgw-count', '');
            $('pgw-bar-text').appendChild(span);
            $('pgw-bar-btn').hidden = true;
            setBar(false);
        } else if (!c && !store(function () { return sessionStorage.getItem('pgw_bar_x'); })) {
            $('pgw-bar-text').textContent = state.copy.bar_text;
            $('pgw-bar-btn').textContent = state.copy.bar_button; $('pgw-bar-btn').hidden = false;
            setBar(false);
        } else { setBar(true); }

        $('pgw-title').textContent = state.copy.modal_title;
        $('pgw-go').textContent = state.copy.modal_button;
        $('pgw-sms-text').textContent = state.copy.sms_text;
        var ul = $('pgw-tickets'); ul.textContent = '';
        state.tiers.forEach(function (t) { var li = document.createElement('li'); li.textContent = t.label; ul.appendChild(li); });

        renderProduct(); renderCart();
        clearInterval(timer); timer = setInterval(tick, 1000); tick();
    }

    /* Product page: only claims what is true for this item alone. */
    function renderProduct() {
        document.querySelectorAll('[data-welcome-product]').forEach(function (el) {
            var price = parseFloat(el.dataset.price), c = state.claim, best = null, next = null;
            state.tiers.forEach(function (t) {
                if (t.type !== 'fixed_amount') return;
                if (price >= t.min && (!best || t.amount > best.amount)) best = t;
                if (price < t.min && (!next || t.min < next.min)) next = t;
            });
            el.textContent = '';
            if (c && c.active) {
                if (best) el.textContent = 'New customer price: ' + money(price - best.amount) + ' with your welcome deal (' + best.label + ') ✓ applied';
                else if (next) el.textContent = 'Welcome deal active. Add ' + money(next.min - price) + ' more to unlock ' + money(next.amount) + ' off.';
                else { el.hidden = true; return; }
            } else if (!c) {
                el.textContent = best ? 'New customer price: ' + money(price - best.amount) + ' with the welcome deal on orders ' + money(best.min) + '+.' : 'New customer deal: up to ' + money(Math.max.apply(null, state.tiers.map(function (t) { return t.amount; }))) + ' off your first order.';
                var b = document.createElement('button'); b.type = 'button'; b.textContent = 'Unlock'; b.addEventListener('click', openModal); el.appendChild(b);
            } else { el.hidden = true; return; }
            el.hidden = false;
        });
    }

    /* Cart: progress toward the next tier, from server-computed numbers. */
    function renderCart() {
        document.querySelectorAll('[data-welcome-cart]').forEach(function (el) {
            var c = state.claim, k = state.cart; el.textContent = '';
            if (!(c && c.active)) { el.hidden = true; return; }
            var msg = k.discount > 0 ? 'You unlocked ' + money(k.discount) + ' off.' : '';
            if (k.next) msg += (msg ? ' ' : '') + 'Add ' + money(k.next.need) + ' more to ' + (k.discount > 0 ? 'get ' + money(k.next.discount) + ' off' : 'unlock ' + money(k.next.discount) + ' off') + '.';
            if (!msg) { el.hidden = true; return; }
            el.append(document.createTextNode(msg));
            var tr = document.createElement('div'); tr.className = 'pgw-track';
            var next = k.next ? k.subtotal + k.next.need : k.subtotal;
            var i = document.createElement('i'); i.style.width = Math.min(100, Math.round(k.subtotal / next * 100)) + '%'; tr.appendChild(i); el.appendChild(tr);
            el.hidden = false;
        });
    }

    /* Modal with focus trap, Escape, visible close button. */
    function openModal() {
        if (!state || !state.enabled || state.claim) { return; }
        store(function () { sessionStorage.setItem('pgw_modal', '1'); });
        lastFocus = document.activeElement; $('pgw-modal').hidden = false; $('pgw-email').focus();
    }
    function closeModal() { $('pgw-modal').hidden = true; if (lastFocus && lastFocus.focus) lastFocus.focus(); }
    function autoOpen() {
        if (Date.now() - shownAt < 8000 || (state && state.claim) || store(function () { return sessionStorage.getItem('pgw_modal'); })) return;
        openModal();
    }
    document.addEventListener('keydown', function (e) {
        var m = $('pgw-modal'); if (m.hidden) return;
        if (e.key === 'Escape') { closeModal(); return; }
        if (e.key !== 'Tab') return;
        var f = m.querySelectorAll('button:not([hidden]),input,a[href]'); if (!f.length) return;
        var first = f[0], last = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    });
    document.querySelectorAll('[data-pgw-close]').forEach(function (el) { el.addEventListener('click', closeModal); });
    $('pgw-bar-btn').addEventListener('click', openModal);
    $('pgw-bar-x').addEventListener('click', function () { store(function () { sessionStorage.setItem('pgw_bar_x', '1'); }); setBar(true); });

    $('pgw-form').addEventListener('submit', function (e) {
        e.preventDefault();
        var email = $('pgw-email').value.trim(), err = $('pgw-err'), btn = $('pgw-go');
        err.textContent = '';
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { err.textContent = 'Please enter a valid email address.'; return; }
        btn.disabled = true;
        fetch('/welcome/claim', {
            method: 'POST', credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() },
            body: JSON.stringify({ email: email, sms_opt_in: $('pgw-sms').checked })
        }).then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); }).then(function (res) {
            btn.disabled = false;
            if (!res.ok) { err.textContent = (res.j && res.j.message) || 'Sorry, we could not save your deal. Please try again.'; return; }
            state = res.j; render();
            $('pgw-claim-view').hidden = true; $('pgw-done-view').hidden = false;
            $('pgw-done-email').textContent = state.claim.email; $('pgw-done-code').textContent = state.claim.code; tick();
            if (res.j.created) {   // Lead fires once, with an id Meta/CAPI can dedupe on
                if (window.fbq) fbq('track', 'Lead', {}, { eventID: res.j.lead_event_id });
                if (window.gtag) gtag('event', 'generate_lead', { currency: 'USD', value: 0 });
            }
        }).catch(function () { btn.disabled = false; err.textContent = 'Network problem. Please try again.'; });
    });

    function load() {
        fetch('/welcome/status', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); }).then(function (j) { state = j; window.__pgWelcomeLive = !!j.enabled; render(); }).catch(function () {});
    }

    /* Start after first paint; scroll-depth and exit-intent are secondary triggers. */
    function start() {
        load();
        window.addEventListener('scroll', function onScroll() {
            var h = document.documentElement;
            if ((h.scrollTop + innerHeight) / h.scrollHeight >= 0.5) { window.removeEventListener('scroll', onScroll); autoOpen(); }
        }, { passive: true });
        document.addEventListener('mouseout', function (e) { if (!e.relatedTarget && e.clientY <= 0) autoOpen(); });
    }
    if (document.readyState === 'complete') { setTimeout(start, 0); } else { addEventListener('load', function () { setTimeout(start, 0); }); }
})();
</script>
