@extends('seller.center.layout')
@section('title', 'Messages')

@section('content')
@php
  $box = 'background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;overflow:hidden';
  $area = 'border-radius:12px;border:1px solid #D1D5DB;padding:12px 14px;font-size:16px;font-family:inherit;resize:vertical;min-width:0';
@endphp
<div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start">
  <div style="flex:1 1 260px;min-width:0;{{ $box }}">
    @forelse($threads as $t)
      <a href="{{ url('/seller/messages?c=' . $t->id) }}" style="display:flex;gap:10px;padding:14px;border-bottom:1px solid #F3F4F6;background:{{ $open && $open->id === $t->id ? '#FFF1E8' : '#fff' }};color:#111827">
        <span style="width:38px;height:38px;border-radius:50%;background:#111827;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;flex:none">{{ mb_strtoupper(mb_substr($t->buyer->name, 0, 1)) }}</span>
        <span style="display:flex;flex-direction:column;min-width:0;flex:1"><span style="display:flex;justify-content:space-between;gap:8px"><b style="font-size:14px">{{ $t->buyer->name }}</b><span style="font-size:11px;color:#6B7280;white-space:nowrap">{{ $t->last_message_at?->diffForHumans(short: true) }}</span></span>
          <span style="font-size:13px;color:#4B5563;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $t->lastMessage?->body }}</span></span>
        @if($unread[$t->id])<span style="min-width:20px;height:20px;border-radius:999px;background:#C2410C;color:#fff;font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center;padding:0 6px">{{ $unread[$t->id] }}</span>@endif
      </a>
    @empty
      <p style="margin:0;padding:18px;font-size:14px;color:#4B5563">No messages yet. Shoppers can write to you from your store page and product pages.</p>
    @endforelse
  </div>

  <div style="flex:2 1 400px;min-width:0;{{ $box }}">
    @if($open)
      <div style="padding:14px 18px;border-bottom:1px solid #F3F4F6"><b>{{ $open->buyer->name }}</b><br><span style="font-size:12px;color:#6B7280">Quick replies build your store's reputation. Never ask a shopper to pay or share card details by message.</span></div>
      <div style="padding:16px;display:flex;flex-direction:column;gap:10px;max-height:460px;overflow-y:auto">
        @foreach($open->messages as $m)
          <div style="max-width:80%;align-self:{{ $m->from === 'seller' ? 'flex-end' : 'flex-start' }};background:{{ $m->from === 'seller' ? '#111827' : '#F3F4F6' }};color:{{ $m->from === 'seller' ? '#fff' : '#111827' }};border-radius:16px;padding:10px 14px;font-size:14px;line-height:1.5;white-space:pre-line;word-break:break-word">{{ $m->body }}<br><span style="font-size:11px;opacity:.7">{{ $m->created_at->format('M j, g:i A') }}</span></div>
        @endforeach
      </div>
      <form method="POST" action="{{ url('/seller/messages/' . $open->id) }}" data-once style="padding:12px 16px 16px;display:flex;gap:8px;flex-wrap:wrap;border-top:1px solid #F3F4F6">
        @csrf
        <textarea name="body" rows="2" required maxlength="2000" placeholder="Write a reply…" style="{{ $area }};flex:1 1 240px"></textarea>
        <button type="submit" style="height:46px;padding:0 22px;border-radius:999px;border:0;background:#C2410C;color:#fff;font-weight:800;cursor:pointer;align-self:flex-end">Send</button>
      </form>
    @else
      <p style="margin:0;padding:24px;font-size:14px;color:#4B5563">Pick a conversation to read and reply.</p>
    @endif
  </div>
</div>
@endsection
