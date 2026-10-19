<!DOCTYPE html>
<html lang="en">
<body style="margin:0;padding:24px;background:#f6f4f1;font-family:Arial,Helvetica,sans-serif;color:#172430;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:12px;padding:32px;">
    <tr><td>
        <h1 style="margin:0 0 12px;font-size:22px;">Your welcome deal is ready</h1>
        <p style="margin:0 0 16px;font-size:15px;line-height:22px;">
            Thanks for joining PeytonGhalib. Your deal is saved to <strong>{{ $claim->email }}</strong>
            and applies automatically at checkout when you order with this email.
        </p>
        <ul style="margin:0 0 16px;padding-left:18px;font-size:15px;line-height:24px;">
            @foreach ($claim->offer->tiers as $tier)
                @continue($tier->type === \App\Models\WelcomeOfferTier::FREE_SHIPPING && ! config('checkout.shipping_fee_enabled'))
                <li>{{ $tier->label() }}</li>
            @endforeach
        </ul>
        <p style="margin:0 0 6px;font-size:15px;">Your code (for your records): <strong>{{ $claim->code }}</strong></p>
        <p style="margin:0 0 20px;font-size:15px;">Expires: <strong>{{ $claim->expires_at->format('l, M j \a\t g:i A T') }}</strong></p>
        <a href="{{ url('/shop') }}" style="display:inline-block;background:#bb976d;color:#ffffff;text-decoration:none;font-weight:bold;padding:12px 24px;border-radius:8px;">Start shopping</a>
        <p style="margin:24px 0 0;font-size:12px;line-height:18px;color:#6b7680;">
            One welcome deal per customer, for a first order only. It cannot be combined with other offers.
            Questions? Reply to this email.
        </p>
    </td></tr>
</table>
</body>
</html>
