<!doctype html>
<html>
<body style="margin:0;background:#F3F4F6;font-family:Arial,sans-serif;color:#111827">
  <div style="max-width:480px;margin:0 auto;padding:24px 16px">
    <div style="background:#fff;border-radius:16px;padding:28px">
      <b style="font-size:18px">Peyton Ghalib</b>
      <p style="font-size:15px;line-height:1.5">Hi {{ $user->name }}, use this code to verify your email and continue setting up your seller account:</p>
      <p style="font-size:34px;letter-spacing:8px;font-weight:800;margin:20px 0;color:#C2410C">{{ $code }}</p>
      <p style="font-size:13px;color:#4B5563">It expires in 10 minutes. If you didn't start a seller account, you can ignore this email.</p>
    </div>
    <p style="font-size:12px;color:#6B7280;text-align:center">Peyton Ghalib LLC · 200 Orient Ave STE 2B, Jersey City, NJ 07305 · info@peytonghalib.com</p>
  </div>
</body>
</html>
