<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your PeytonGhalib account is ready</title>
    <style>
        body { margin: 0; padding: 0; background: #f5f5f5; font-family: 'Helvetica Neue', Arial, sans-serif; color: #333; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .header { background: #1a1a1a; padding: 32px 40px; text-align: center; }
        .header h1 { margin: 0; color: #bb976d; font-size: 26px; letter-spacing: 2px; font-weight: 600; }
        .header p { margin: 6px 0 0; color: #888; font-size: 12px; letter-spacing: 1px; text-transform: uppercase; }
        .body { padding: 40px; }
        .greeting { font-size: 22px; font-weight: 600; color: #1a1a1a; margin-bottom: 12px; }
        .intro { font-size: 15px; color: #555; line-height: 1.7; margin-bottom: 24px; }
        .creds { background: #fdf8f3; border: 1px solid #ecdcc8; border-radius: 6px; padding: 22px 24px; margin: 24px 0; }
        .creds .row { font-size: 15px; color: #333; line-height: 1.9; }
        .creds .label { color: #888; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; display: block; }
        .creds .val { font-weight: 700; color: #1a1a1a; font-size: 16px; word-break: break-all; }
        .creds .pw { font-family: 'Courier New', Courier, monospace; background: #fff; border: 1px dashed #bb976d; border-radius: 4px; padding: 4px 10px; display: inline-block; letter-spacing: 1px; }
        .notice { background: #fff8ec; border-left: 4px solid #e0a53a; border-radius: 4px; padding: 16px 20px; margin: 24px 0; font-size: 13.5px; color: #7a5a1e; line-height: 1.65; }
        .cta-wrap { text-align: center; margin: 32px 0 12px; }
        .cta-btn { display: inline-block; padding: 14px 40px; background: #bb976d; color: #fff; text-decoration: none; border-radius: 6px; font-size: 15px; font-weight: 600; letter-spacing: 0.5px; }
        .sign-off { font-size: 14px; color: #555; margin-top: 30px; line-height: 1.7; }
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
        <div class="greeting">Your account is ready, {{ $user->name }}!</div>
        <p class="intro">
            Thanks for your order. We've created an account for you so you can track your
            orders and check out faster next time. Here are your sign-in details:
        </p>

        <div class="creds">
            <div class="row">
                <span class="label">Email</span>
                <span class="val">{{ $user->email }}</span>
            </div>
            <div class="row" style="margin-top:14px;">
                <span class="label">Temporary password</span>
                <span class="pw">{{ $password }}</span>
            </div>
        </div>

        <div class="notice">
            <strong>For your security:</strong> this is a one-time temporary password.
            The first time you sign in, you'll be asked to choose your own new password
            before you can continue.
        </div>

        <div class="cta-wrap">
            <a href="{{ url('/login') }}" class="cta-btn">Sign in to your account</a>
        </div>

        <p class="sign-off">
            Didn't place this order? Please contact us right away at
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
