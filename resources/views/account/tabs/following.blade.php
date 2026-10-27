@forelse($follows as $s)
  <div style="border:1px solid #E5E7EB;border-radius:20px;padding:16px;display:flex;flex-direction:column;gap:12px">
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
      <span style="width:48px;height:48px;border-radius:14px;background:#111827;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;flex:none">{{ $s['initial'] }}</span>
      <a href="{{ $s['href'] }}" style="flex:1;display:flex;flex-direction:column;min-width:0"><b>{{ $s['name'] }}</b><span style="font-size:13px;color:#4B5563">{{ $s['city'] }}</span></a>
      <form method="POST" action="{{ route('store.follow', $s['slug']) }}">@csrf<button type="submit" style="height:40px;padding:0 18px;border-radius:999px;border:1.5px solid #111827;background:#fff;font-weight:700;font-size:13px;cursor:pointer">Unfollow</button></form>
    </div>
    @if($s['items'])
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px">
        @foreach($s['items'] as $p)<a href="{{ $p['href'] }}" style="display:flex;flex-direction:column;gap:2px;font-size:13px"><span style="line-height:1.3">{{ $p['name'] }}</span><b>{{ $p['price'] }}</b></a>@endforeach
      </div>
    @endif
  </div>
@empty
  <span style="font-size:14px;color:#4B5563">You're not following any stores yet. Open a store and choose Follow to see its new products here.</span>
@endforelse
