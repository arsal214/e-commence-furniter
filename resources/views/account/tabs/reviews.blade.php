@php
  $card = 'border:1px solid #E5E7EB;border-radius:20px;padding:18px;display:flex;flex-direction:column;gap:12px';
  $input = 'border-radius:12px;border:1px solid #D1D5DB;padding:12px 14px;font-size:15px;outline:0;font-family:inherit';
@endphp
<b style="font-size:16px">Waiting for your review</b>
@if(empty($toReview))
  <span style="font-size:14px;color:#4B5563">You are all caught up. Thanks for helping other shoppers!</span>
@endif
@foreach($toReview as $r)
  <form method="POST" action="{{ route('product.review.store', $r['slug']) }}" data-once style="{{ $card }}">
    @csrf
    <span style="display:flex;flex-direction:column"><b style="font-size:15px">{{ $r['name'] }}</b><span style="font-size:12px;color:#4B5563">{{ $r['when'] }} · {{ $r['store'] }} · Order {{ $r['order'] }}</span></span>
    <fieldset style="border:0;padding:0;margin:0;display:flex;gap:6px;align-items:center;flex-wrap:wrap"><legend style="font-size:13px;font-weight:700;padding:0;margin-bottom:6px">Overall rating</legend>
      @foreach(range(1, 5) as $n)
        <label style="cursor:pointer;position:relative"><input type="radio" name="rating" value="{{ $n }}" required style="position:absolute;opacity:0"><span style="display:inline-flex;align-items:center;gap:4px;height:40px;padding:0 14px;border-radius:999px;border:1.5px solid #D1D5DB;font-weight:700;font-size:14px"><span style="color:#F59E0B">★</span>{{ $n }}</span></label>
      @endforeach
    </fieldset>
    <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Your review<textarea name="comment" rows="3" maxlength="1000" placeholder="What did you like or dislike? How was the quality and delivery?" style="{{ $input }};resize:vertical"></textarea></label>
    <button type="submit" style="align-self:flex-start;height:44px;padding:0 20px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:800;cursor:pointer">Submit review</button>
    <span style="font-size:12px;color:#4B5563">Reviews are public and show "Verified purchase" when you bought the item here. No personal info or links, please.</span>
  </form>
@endforeach

@if(!empty($myRevs))
  <b style="font-size:16px;margin-top:8px">Your reviews</b>
  @foreach($myRevs as $r)
    <div style="{{ $card }};gap:6px">
      <a href="{{ $r['href'] }}" style="font-weight:700;color:#111827">{{ $r['name'] }}</a>
      <div style="display:flex;gap:8px;align-items:center;font-size:13px"><span style="color:#F59E0B">{{ str_repeat('★', $r['rating']) }}{{ str_repeat('☆', 5 - $r['rating']) }}</span><span style="color:#6B7280">{{ $r['date'] }}</span></div>
      @if($r['text'])<p style="margin:0;font-size:14px;line-height:1.5;color:#374151">{{ $r['text'] }}</p>@endif
    </div>
  @endforeach
@endif
