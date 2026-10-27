/* Product page: gallery, colour / size pickers, quantity, price and stock for the picked variant. */
(function () {
  'use strict';
  var data = window.PDP || { colors: {}, sizes: {}, base: { price: 0, list: 0, stock: 0 } };
  var $ = function (s) { return document.querySelector(s); };
  var $$ = function (s) { return Array.prototype.slice.call(document.querySelectorAll(s)); };
  var money = function (n) { return '$' + n.toFixed(2); };
  var form = $('#buy'); if (!form) return;
  var picked = { color: ($('#f-color') || {}).value || null, size: ($('#f-size') || {}).value || null };
  var hasVariants = Object.keys(data.colors).length || Object.keys(data.sizes).length;

  function mark(type) {
    $$('[data-pick="' + type + '"]').forEach(function (b) {
      var on = b.dataset.value === picked[type];
      b.setAttribute('aria-pressed', on ? 'true' : 'false');
      if (type === 'color') { b.style.borderColor = on ? '#C2410C' : '#E5E7EB'; }
      else { b.style.borderColor = on ? '#111827' : '#D1D5DB'; b.style.background = on ? '#111827' : '#FFFFFF'; b.style.color = on ? '#FFFFFF' : '#111827'; }
    });
  }

  // Same rules as the server: a priced size wins over a priced colour; stock is the smaller of the two.
  function update() {
    var c = data.colors[picked.color], s = data.sizes[picked.size];
    var v = (s && s.price > 0) ? s : (c && c.price > 0) ? c : null;
    var price = v ? v.price : data.base.price, list = v ? v.list : data.base.list;
    var stocks = [c, s].filter(Boolean).map(function (x) { return x.stock; });
    var stock = stocks.length ? Math.min.apply(null, stocks) : data.base.stock;
    if ($('#price')) $('#price').textContent = money(price);
    if ($('#list')) { $('#list').textContent = money(list); $('#list').hidden = !(list > price); }
    var st = $('#stock');
    if (st) {
      st.textContent = stock <= 0 ? 'Out of stock' : stock <= 5 ? 'Only ' + stock + ' left' : 'In stock';
      st.style.color = stock <= 0 ? '#B91C1C' : stock <= 5 ? '#92400E' : '#15803D';
    }
    $$('#buy button[type=submit]').forEach(function (b) { b.disabled = stock <= 0; b.style.opacity = stock <= 0 ? '.5' : ''; });
    var img = (c && c.image) || (s && s.image); if (img && $('#mainImg')) $('#mainImg').src = img;
  }

  document.addEventListener('click', function (ev) {
    var pick = ev.target.closest('[data-pick]');
    if (pick) {
      var type = pick.dataset.pick; picked[type] = pick.dataset.value;
      var input = $('#f-' + type); if (input) input.value = picked[type];
      var label = pick.closest('div[style*="flex-direction:column"]'); var b = label && label.querySelector('span > b'); if (b) b.textContent = picked[type];
      mark(type); update(); return;
    }
    var thumb = ev.target.closest('[data-thumb]');
    if (thumb && $('#mainImg')) { $('#mainImg').src = thumb.dataset.thumb; $$('[data-thumb]').forEach(function (t) { t.style.borderColor = t === thumb ? '#111827' : '#E5E7EB'; }); return; }
    var act = ev.target.closest('[data-act="pdp.dec"],[data-act="pdp.inc"]');
    if (act) {
      var q = $('#f-qty'), shown = $('[data-qty]'); var n = Math.min(99, Math.max(1, (parseInt(q.value, 10) || 1) + (act.dataset.act === 'pdp.inc' ? 1 : -1)));
      q.value = n; if (shown) shown.textContent = n;
    }
  });

  if (hasVariants) { mark('color'); mark('size'); update(); }
})();
