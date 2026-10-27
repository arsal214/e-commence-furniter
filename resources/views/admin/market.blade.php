@extends('admin.layouts.app')

@section('title', $heading)
@section('page-title', $heading)

@section('content')
<div style="display:flex;flex-direction:column;gap:18px;font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:#111827">
  @include('admin.dc.' . $page)
</div>

<script>
  // Buttons in the design carry data-post (where to send) and data-field-* (what to send); a note comes from the
  // textarea beside them or from a question, and risky ones ask first.
  document.addEventListener('click', function (ev) {
    var b = ev.target.closest('[data-post]'); if (!b) return;
    ev.preventDefault();
    if (b.dataset.confirm && !window.confirm(b.dataset.confirm)) return;
    var fields = {};
    Object.keys(b.dataset).forEach(function (k) { if (k.indexOf('field') === 0 && k.length > 5) fields[k.charAt(5).toLowerCase() + k.slice(6)] = b.dataset[k]; });
    var card = b.closest('div[style*="border-radius:18px"]') || document;
    var area = card.querySelector('textarea[name=note]');
    if (area && area.value.trim()) fields.note = area.value.trim();
    if (b.dataset.ask) { var n = window.prompt(b.dataset.ask); if (n === null) return; if (n.trim()) fields.note = n.trim(); }
    var f = document.createElement('form'); f.method = 'POST'; f.action = b.dataset.post; f.style.display = 'none';
    var add = function (k, v) { var i = document.createElement('input'); i.type = 'hidden'; i.name = k; i.value = v; f.appendChild(i); };
    add('_token', document.querySelector('meta[name="csrf-token"]').content);
    Object.keys(fields).forEach(function (k) { add(k, fields[k]); });
    document.body.appendChild(f); b.disabled = true; f.submit();
  });
</script>
@endsection
