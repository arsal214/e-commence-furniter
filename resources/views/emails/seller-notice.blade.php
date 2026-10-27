<!doctype html>
<html>
<body style="margin:0;background:#F3F4F6;font-family:Arial,sans-serif;color:#111827">
  <div style="max-width:480px;margin:0 auto;padding:24px 16px">
    <div style="background:#fff;border-radius:16px;padding:28px">
      <b style="font-size:18px">Peyton Ghalib</b>
      <h1 style="font-size:20px;margin:16px 0 8px">{{ $headline }}</h1>
      <p style="font-size:15px;line-height:1.5">{{ $body }}</p>
      @if($note)
        <p style="font-size:14px;line-height:1.5;background:#F9FAFB;border-left:4px solid #EA580C;padding:12px 14px;margin:16px 0"><b>Note from our team:</b><br>{{ $note }}</p>
      @endif
      @if($buttonLabel && $buttonUrl)
        <p><a href="{{ $buttonUrl }}" style="display:inline-block;background:#EA580C;color:#fff;text-decoration:none;font-weight:700;padding:12px 22px;border-radius:999px">{{ $buttonLabel }}</a></p>
      @endif
    </div>
    <p style="font-size:12px;color:#6B7280;text-align:center">Peyton Ghalib LLC · 200 Orient Ave STE 2B, Jersey City, NJ 07305 · info@peytonghalib.com</p>
  </div>
</body>
</html>
