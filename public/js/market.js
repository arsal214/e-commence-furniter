/* Behaviour for the design's pages. Markup carries data-act / data-if hooks; this file wires them. */
(function () {
  'use strict';
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

  // data-if blocks: shown or hidden by name.
  function show(name, on, sel) { $$('[data-if="' + name + '"]' + (sel || '')).forEach(function (e) { e.hidden = !on; }); }
  function post(url, data) {
    var body = new URLSearchParams(data || {});
    return fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, body: body, credentials: 'same-origin' });
  }

  // ── menu ───────────────────────────────────────────────────────────────────────
  function openMenu() { show('menu', true); show('menuMain', true); show('menuSub', false); document.body.style.overflow = 'hidden'; }
  function closeMenu() { show('menu', false); document.body.style.overflow = ''; }

  // ── delivery ZIP ───────────────────────────────────────────────────────────────
  function applyZip() {
    var box = $('[data-if="locOpen"]'); var input = box && $('input', box);
    var zip = input ? input.value.replace(/\D/g, '').slice(0, 5) : '';
    var err = $('[data-if="hasLocErr"]', box);
    if (zip.length !== 5) { if (err) { err.hidden = false; err.textContent = 'Enter a 5-digit US ZIP code.'; } return; }
    post('/location', { zip: zip }).then(function (r) { return r.ok ? location.reload() : Promise.reject(); })
      .catch(function () { if (err) { err.hidden = false; err.textContent = 'Could not save your ZIP. Try again.'; } });
  }

  // First visit with no ZIP: ask the server for an IP-based estimate once per tab session, then reload to show it.
  (function detectZip() {
    var box = $('[data-if="locOpen"]'); var input = box && $('input', box);
    if (!input || input.value) return;
    try { if (sessionStorage.getItem('pg_zip_asked')) return; sessionStorage.setItem('pg_zip_asked', '1'); } catch (e) { return; }
    post('/location/detect').then(function (r) { return r.ok ? r.json() : null; })
      .then(function (d) { if (d && d.zip) location.reload(); }).catch(function () {});
  })();

  // ── rails (flash deals, recently viewed, promo cards)─────────────────────────
  function rail(btn, dir) {
    var wrap = btn.closest('[data-rail-wrap]') || btn.parentElement.parentElement;
    var r = (wrap && $('[data-rail]', wrap)) || $('[data-cards]');
    if (r) r.scrollBy({ left: dir * r.clientWidth * 0.8, behavior: 'smooth' });
  }

  // ── wishlist heart ─────────────────────────────────────────────────────────────
  function wish(btn, ev) {
    ev.preventDefault(); ev.stopPropagation();
    post('/wishlist/toggle', { product_id: btn.dataset.id }).then(function (r) {
      if (r.status === 401 || r.status === 419 || r.redirected) { location.href = '/login'; return null; }
      return r.json();
    }).then(function (j) {
      if (!j) return;
      var on = !!(j.wished || j.added || j.status === 'added');
      if (location.pathname.indexOf('/my-account/wishlist') === 0) { location.reload(); return; }
      btn.textContent = on ? '♥' : '♡'; btn.style.color = on ? '#DC2626' : '#111827';
    }).catch(function () { location.href = '/login'; });
  }

  // ── search ─────────────────────────────────────────────────────────────────────
  function search(from) {
    var input = from && from.closest('div') && $('input', from.closest('div'));
    var v = input && input.value.trim();
    if (v) location.href = '/search?q=' + encodeURIComponent(v);
  }

  // data-post="/url": a button that sends a normal POST (sign out, redeem points...), CSRF included.
  document.addEventListener('click', function (ev) {
    var b = ev.target.closest('[data-post]'); if (!b || b.closest('[data-admin-post]')) return;
    ev.preventDefault();
    var f = document.createElement('form'); f.method = 'POST'; f.action = b.dataset.post; f.style.display = 'none';
    var t = document.createElement('input'); t.type = 'hidden'; t.name = '_token'; t.value = csrf; f.appendChild(t);
    document.body.appendChild(f); b.disabled = true; f.submit();
  });

  var actions = {
    openMenu: openMenu, closeMenu: closeMenu,
    openSub: function (b) { show('menuMain', false); show('menuSub', false); show('menuSub', true, '[data-sub="' + b.dataset.i + '"]'); },
    menuBack: function () { show('menuSub', false); show('menuMain', true); },
    openLoc: function () { show('locOpen', true); }, closeLoc: function () { show('locOpen', false); },
    openLocFromMenu: function () { closeMenu(); show('locOpen', true); }, stopProp: function () {}, applyZip: applyZip,
    railPrev: function (b) { rail(b, -1); }, railNext: function (b) { rail(b, 1); }, cardsPrev: function (b) { rail(b, -1); }, cardsNext: function (b) { rail(b, 1); },
    showMore: function () { $$('[data-rec] > :nth-child(n+16)').forEach(function (e) { e.style.display = ''; }); show('canMore', false); },
    toggleChat: function () { var o = $('[data-if="chatOpen"]'); show('chatOpen', o && o.hidden); }, openChat: function () { show('chatOpen', true); },
    openSheet: function () { show('list.sheet', true); document.body.style.overflow = 'hidden'; },
    closeSheet: function () { show('list.sheet', false); document.body.style.overflow = ''; },
    doSearch: search, wish: wish,
    subscribe: function (b) {
      var input = $('input[type=email]', b.closest('div[data-if]') || document); var v = input && input.value.trim();
      if (!v) return;
      post('/newsletter', { email: v }).then(function () { show('notSubscribed', false); show('subscribed', true); });
    }
  };

  document.addEventListener('click', function (ev) {
    var el = ev.target.closest('[data-act]'); if (!el) return;
    var fn = actions[el.dataset.act];
    if (fn) { if (el.tagName === 'A' || el.tagName === 'BUTTON') { if (el.dataset.act !== 'wish') ev.preventDefault(); } fn(el, ev); }
  });
  document.addEventListener('change', function (ev) { var s = ev.target.closest('select[data-nav]'); if (s) location.href = s.value; });
  document.addEventListener('keydown', function (ev) {
    if (ev.key === 'Enter' && ev.target.matches('input[placeholder^="Search products"]')) { ev.preventDefault(); search(ev.target); }
    if (ev.key === 'Escape') { closeMenu(); show('locOpen', false); }
  });

  // style-hover="…" is the design's hover shorthand.
  var saved = new WeakMap();
  document.addEventListener('mouseover', function (ev) {
    var el = ev.target.closest('[style-hover]'); if (!el || saved.has(el)) return;
    saved.set(el, el.getAttribute('style') || ''); el.setAttribute('style', (el.getAttribute('style') || '') + ';' + el.getAttribute('style-hover'));
  });
  document.addEventListener('mouseout', function (ev) {
    var el = ev.target.closest('[style-hover]'); if (!el || !saved.has(el) || el.contains(ev.relatedTarget)) return;
    el.setAttribute('style', saved.get(el)); saved.delete(el);
  });

  // ── help center: accordion + search ──────────────────────────────────────────
  document.addEventListener('click', function (ev) {
    var q = ev.target.closest('[data-faq]'); if (!q) return;
    var a = q.nextElementSibling, open = a && a.hidden; if (a) a.hidden = !open;
    q.setAttribute('aria-expanded', open ? 'true' : 'false'); var sign = q.querySelector('span'); if (sign) sign.textContent = open ? '−' : '+';
  });
  var faqSearch = $('[data-faq-search]');
  if (faqSearch) {
    var filter = function () {
      var v = faqSearch.value.trim().toLowerCase();
      $$('[data-faq]').forEach(function (b) { var row = b.parentElement, a = b.nextElementSibling; row.hidden = !!v && (b.textContent + ' ' + (a ? a.textContent : '')).toLowerCase().indexOf(v) < 0; });
    };
    faqSearch.addEventListener('input', filter);
    faqSearch.parentElement.addEventListener('click', function (ev) { if (ev.target.tagName === 'BUTTON') filter(); });
  }

  // ── contact form (JSON reply) ────────────────────────────────────────────────
  document.addEventListener('submit', function (ev) {
    var f = ev.target.closest('form[data-contact]'); if (!f) return;
    ev.preventDefault();
    var msg = $('[data-contact-msg]', f), btn = $('button[type=submit]', f); btn.disabled = true;
    function say(t, ok) { msg.hidden = false; msg.textContent = t; msg.style.color = ok ? '#15803D' : '#B91C1C'; }
    fetch(f.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: new FormData(f), credentials: 'same-origin' })
      .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
      .then(function (x) {
        if (x.ok) { f.reset(); say('✓ ' + (x.j.message || "Thanks — we'll reply by email within a few hours."), true); }
        else { var first = x.j.errors ? Object.values(x.j.errors)[0][0] : x.j.message; say(first || 'Could not send. Please try again.', false); btn.disabled = false; }
      }).catch(function () { say('Could not send. Check your connection and try again.', false); btn.disabled = false; });
  });

  // ── Do Not Sell or Share: a cookie the layout reads to skip advertising tags ─
  var optOut = /(?:^|; )pg_optout=1/.test(document.cookie) || navigator.globalPrivacyControl === true;
  function paintOptOut() {
    $$('[data-act="toggleCcpa"]').forEach(function (b) {
      b.style.background = optOut ? '#15803D' : '#D1D5DB'; b.setAttribute('aria-pressed', optOut ? 'true' : 'false');
      var knob = b.querySelector('span'); if (knob) knob.style.left = optOut ? '25px' : '3px';
    });
  }
  actions.copyRef = function (b) {
    var text = b.dataset.copy, done = function () { b.textContent = 'Copied'; setTimeout(function () { b.textContent = 'Copy link'; }, 2000); };
    if (navigator.clipboard && window.isSecureContext) { navigator.clipboard.writeText(text).then(done); return; }
    var f = document.createElement('textarea'); f.value = text; f.style.position = 'fixed'; f.style.opacity = '0'; document.body.appendChild(f); f.select();
    try { document.execCommand('copy'); done(); } catch (e) { /* the link stays selectable above */ } document.body.removeChild(f);
  };
  actions.toggleCcpa = function () { optOut = !optOut; document.cookie = 'pg_optout=' + (optOut ? '1' : '0') + '; path=/; max-age=31536000; SameSite=Lax'; paintOptOut(); };
  paintOptOut();

  // ── page setup ─────────────────────────────────────────────────────────────────
  $$('[data-act="useGps"]').forEach(function (b) { b.hidden = true; }); // no reverse-geocoding service is connected
  $$('[data-rec] > :nth-child(n+16)').forEach(function (e) { e.style.display = 'none'; });
  var cd = $('[data-cd]');
  if (cd) {
    var cells = cd.children;
    setInterval(function () {
      var now = new Date(), end = new Date(now); end.setHours(24, 0, 0, 0);
      var s = Math.max(0, Math.floor((end - now) / 1000)), p = function (n) { return String(n).padStart(2, '0'); };
      cells[0].textContent = p(Math.floor(s / 3600)); cells[1].textContent = p(Math.floor(s % 3600 / 60)); cells[2].textContent = p(s % 60);
    }, 1000);
  }
  setTimeout(function () { $$('.dc-flash').forEach(function (e) { e.remove(); }); }, 4500);
})();
