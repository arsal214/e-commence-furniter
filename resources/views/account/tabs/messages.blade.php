@php
  $box = 'border:1px solid #E5E7EB;border-radius:20px;overflow:hidden';
  $area = 'border-radius:12px;border:1px solid #D1D5DB;padding:12px 14px;font-size:16px;font-family:inherit;resize:vertical;min-width:0';
@endphp
@if($errors->any())<span role="alert" style="font-size:13px;font-weight:600;color:#B91C1C;background:#FEE2E2;border-radius:12px;padding:10px 12px">{{ $errors->first() }}</span>@endif
<div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start">
  <div style="flex:1 1 240px;min-width:0;{{ $box }}">
    @forelse($threads as $t)
      <a href="{{ url('/my-account/messages?c=' . $t['id']) }}" style="display:flex;gap:10px;padding:14px;border-bottom:1px solid #F3F4F6;background:{{ $t['id'] === ($openThread['id'] ?? 0) ? '#FFF1E8' : '#fff' }}">
        <span style="width:38px;height:38px;border-radius:50%;background:#111827;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;flex:none">{{ $t['initial'] }}</span>
        <span style="display:flex;flex-direction:column;min-width:0;flex:1"><span style="display:flex;justify-content:space-between;gap:8px"><b style="font-size:14px">{{ $t['name'] }}</b><span style="font-size:11px;color:#6B7280;white-space:nowrap">{{ $t['time'] }}</span></span>
          <span style="font-size:13px;color:#4B5563;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $t['last'] }}</span></span>
        @if($t['unread'])<span style="min-width:20px;height:20px;border-radius:999px;background:#C2410C;color:#fff;font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center;padding:0 6px">{{ $t['unread'] }}</span>@endif
      </a>
    @empty
      <p style="margin:0;padding:18px;font-size:14px;color:#4B5563">No messages yet. Open a store or a product and choose Message to ask the seller a question.</p>
    @endforelse
  </div>

  <div style="flex:2 1 380px;min-width:0;{{ $box }}">
    @if($composeTo)
      <div style="padding:14px 18px;border-bottom:1px solid #F3F4F6"><b>New message to {{ $composeTo['name'] }}</b></div>
      <form method="POST" action="{{ url('/my-account/messages') }}" data-once style="padding:16px;display:flex;flex-direction:column;gap:10px">
        @csrf<input type="hidden" name="to" value="{{ $composeTo['slug'] }}">
        <textarea name="body" rows="4" required maxlength="2000" placeholder="Ask about a product, an order or shipping…" style="{{ $area }}">{{ old('body') }}</textarea>
        <button type="submit" style="align-self:flex-start;height:46px;padding:0 22px;border-radius:999px;border:0;background:#C2410C;color:#fff;font-weight:800;cursor:pointer">Send</button>
      </form>
    @elseif($openThread)
      <div style="padding:14px 18px;border-bottom:1px solid #F3F4F6;display:flex;flex-direction:column"><a href="{{ $openThread['href'] }}"><b>{{ $openThread['name'] }}</b></a><span style="font-size:12px;color:#6B7280">Reply from your account so everything stays in one place.</span></div>
      <div style="padding:16px;display:flex;flex-direction:column;gap:10px;max-height:460px;overflow-y:auto">
        @foreach($openThread['messages'] as $m)
          <div style="max-width:80%;align-self:{{ $m['mine'] ? 'flex-end' : 'flex-start' }};background:{{ $m['mine'] ? '#111827' : '#F3F4F6' }};color:{{ $m['mine'] ? '#fff' : '#111827' }};border-radius:16px;padding:10px 14px;font-size:14px;line-height:1.5;white-space:pre-line;word-break:break-word">{{ $m['body'] }}<br><span style="font-size:11px;opacity:.7">{{ $m['time'] }}</span></div>
        @endforeach
      </div>
      <form method="POST" action="{{ url('/my-account/messages/' . $openThread['id']) }}" data-once style="padding:12px 16px 16px;display:flex;gap:8px;flex-wrap:wrap;border-top:1px solid #F3F4F6">
        @csrf
        <textarea name="body" rows="2" required maxlength="2000" placeholder="Write a message…" style="{{ $area }};flex:1 1 240px"></textarea>
        <button type="submit" style="height:46px;padding:0 22px;border-radius:999px;border:0;background:#C2410C;color:#fff;font-weight:800;cursor:pointer;align-self:flex-end">Send</button>
      </form>
    @else
      <p style="margin:0;padding:24px;font-size:14px;color:#4B5563">Pick a conversation, or message a store from its page.</p>
    @endif
  </div>
</div>
