<!doctype html>
<html>
<body style="margin:0;background:#F3F4F6;font-family:Arial,sans-serif;color:#111827">
  <div style="max-width:480px;margin:0 auto;padding:24px 16px">
    <div style="background:#fff;border-radius:16px;padding:28px">
      <b style="font-size:18px">Peyton Ghalib</b>
      <p style="font-size:15px;line-height:1.5">Hi {{ $seller->owner_name ?: $seller->user->name }}, we couldn't charge your $19.99 seller plan{{ $seller->store_name ? ' for ' . $seller->store_name : '' }}.</p>
      <p style="font-size:15px;line-height:1.5"><b>Your store is hidden from shoppers</b> until the payment goes through. It comes back automatically as soon as it does. We'll retry the card; you can also update it now.</p>
      <p><a href="{{ url('/') }}" style="display:inline-block;background:#EA580C;color:#fff;text-decoration:none;font-weight:700;padding:12px 22px;border-radius:999px">Update payment method</a></p>
    </div>
    <p style="font-size:12px;color:#6B7280;text-align:center">Peyton Ghalib LLC · 200 Orient Ave STE 2B, Jersey City, NJ 07305 · info@peytonghalib.com</p>
  </div>
</body>
</html>
