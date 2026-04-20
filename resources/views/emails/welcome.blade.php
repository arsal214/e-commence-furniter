<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to PeytonGhalib</title>
    <style>
        body { margin: 0; padding: 0; background: #f5f5f5; font-family: 'Helvetica Neue', Arial, sans-serif; color: #333; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .header { background: #1a1a1a; padding: 32px 40px; text-align: center; }
        .header h1 { margin: 0; color: #bb976d; font-size: 26px; letter-spacing: 2px; font-weight: 600; }
        .header p { margin: 6px 0 0; color: #888; font-size: 12px; letter-spacing: 1px; text-transform: uppercase; }
        .body { padding: 40px; }
        .greeting { font-size: 22px; font-weight: 600; color: #1a1a1a; margin-bottom: 12px; }
        .intro { font-size: 15px; color: #555; line-height: 1.7; margin-bottom: 32px; }
        .highlight-box { background: #fdf8f3; border-left: 4px solid #bb976d; border-radius: 4px; padding: 20px 24px; margin: 24px 0; }
        .highlight-box p { margin: 0; font-size: 14px; color: #555; line-height: 1.7; }
        .perks { margin: 32px 0; }
        .perk-item { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 18px; }
        .perk-icon { width: 36px; height: 36px; background: #bb976d; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px; flex-shrink: 0; line-height: 36px; text-align: center; }
        .perk-text .perk-title { font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 2px; }
        .perk-text .perk-desc { font-size: 13px; color: #777; }
        .cta-wrap { text-align: center; margin: 36px 0 20px; }
        .cta-btn { display: inline-block; padding: 14px 40px; background: #bb976d; color: #fff; text-decoration: none; border-radius: 6px; font-size: 15px; font-weight: 600; letter-spacing: 0.5px; }
        .sign-off { font-size: 14px; color: #555; margin-top: 32px; line-height: 1.7; }
        .footer { background: #f9f9f9; padding: 24px 40px; text-align: center; font-size: 12px; color: #aaa; border-top: 1px solid #eee; }
        .footer a { color: #bb976d; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>PeytonGhalib</h1>
        <p>Premium Furniture Store</p>
    </div>
    <div class="body">
        <div class="greeting">Welcome, {{ $user->name }}!</div>
        <p class="intro">
            We're thrilled to have you as part of the PeytonGhalib family. Your account has been created successfully and you're all set to explore our curated collection of premium furniture.
        </p>

        <div class="highlight-box">
            <p>Your account email: <strong>{{ $user->email }}</strong><br>
            You can log in anytime at <a href="{{ url('/login') }}" style="color:#bb976d;">{{ url('/login') }}</a></p>
        </div>

        <div class="perks">
            <div class="perk-item">
                <div class="perk-icon">&#9733;</div>
                <div class="perk-text">
                    <div class="perk-title">Exclusive Designs</div>
                    <div class="perk-desc">Browse hundreds of handpicked furniture pieces for every room and style.</div>
                </div>
            </div>
            <div class="perk-item">
                <div class="perk-icon">&#128666;</div>
                <div class="perk-text">
                    <div class="perk-title">Easy Order Tracking</div>
                    <div class="perk-desc">Track every order from placement to your doorstep in real time.</div>
                </div>
            </div>
            <div class="perk-item">
                <div class="perk-icon">&#128274;</div>
                <div class="perk-text">
                    <div class="perk-title">Secure Checkout</div>
                    <div class="perk-desc">Shop with confidence using our secure payment options including Stripe and Cash on Delivery.</div>
                </div>
            </div>
        </div>

        <div class="cta-wrap">
            <a href="{{ url('/shop-v1') }}" class="cta-btn">Start Shopping</a>
        </div>

        <p class="sign-off">
            If you have any questions, feel free to reach out to us at
            <a href="mailto:info@peytonghalib.com" style="color:#bb976d;">info@peytonghalib.com</a>.<br><br>
            Warm regards,<br>
            <strong>The PeytonGhalib Team</strong>
        </p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} PeytonGhalib. All rights reserved.<br>
        <a href="{{ url('/') }}">Visit our store</a>
    </div>
</div>
</body>
</html>
