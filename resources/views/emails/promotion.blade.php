@php
    /**
     * Shell for staff-written promotional email (deals & offers).
     *
     * Same email-safe rules as emails/order-mail.blade.php: table layout, inline
     * styles, Georgia/Arial only. The one difference is $bodyHtml — arbitrary
     * markup from the admin's editor, which cannot be inlined ahead of time. It
     * is wrapped in a cell carrying the base type styles (inherited by <p>/<li>
     * in every client including Outlook) and the .rte rules below cover headings
     * and links wherever embedded CSS is honoured.
     *
     * Expected: $subjectLine, $bodyHtml, $recipientName
     * Optional: $eyebrow, $heading, $ctaLabel, $ctaUrl, $promoCode, $promoNote,
     *           $unsubscribeUrl, $plainText
     */
    $gold     = '#BB976D'; // brand gold — fills and text on dark only
    $goldText = '#8A6A3F'; // AA-safe gold for text on light backgrounds
    $ink      = '#1A1A1A';
    $body     = '#3D3A36';
    $muted    = '#6B6560';
    $line     = '#E8E1D7';
    $warm     = '#FAF7F3';

    $preheader   = \Illuminate\Support\Str::limit($plainText ?? '', 140);
    $hasHeadline = filled($eyebrow ?? null) || filled($heading ?? null);
    $hasCta      = filled($ctaLabel ?? null) && filled($ctaUrl ?? null);
    $bodyPadTop  = $hasHeadline ? '22px' : '40px';
@endphp
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>{{ $heading ?: $subjectLine }}</title>
    <!--[if mso]>
    <xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml>
    <![endif]-->
    <style>
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        table { border-collapse: collapse; }
        img { border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; max-width: 100%; height: auto; }
        a { text-decoration: none; }
        a[x-apple-data-detectors], .unstyle-auto-detected-links a, .aBn {
            color: inherit !important; text-decoration: none !important; font-size: inherit !important;
            font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important;
            border-bottom: 0 !important;
        }

        /* Editor-authored body */
        .rte p            { margin: 0 0 14px 0; }
        .rte p:last-child { margin-bottom: 0; }
        .rte h1, .rte h2, .rte h3, .rte h4 {
            margin: 24px 0 10px 0; font-family: Georgia,'Times New Roman',serif;
            font-weight: normal; color: {{ $ink }};
        }
        .rte h1 { font-size: 26px; line-height: 32px; }
        .rte h2 { font-size: 22px; line-height: 28px; }
        .rte h3 { font-size: 18px; line-height: 24px; }
        .rte h4 { font-size: 16px; line-height: 22px; }
        .rte ul, .rte ol { margin: 0 0 14px 0; padding-left: 22px; }
        .rte li     { margin: 0 0 7px 0; }
        .rte a      { color: {{ $goldText }}; text-decoration: underline; }
        .rte strong { color: {{ $ink }}; }
        .rte img    { display: block; margin: 18px 0; }
        .rte table  { width: 100% !important; }
        .rte td     { padding: 6px 8px; border: 1px solid {{ $line }}; }
        .rte hr     { border: 0; border-top: 1px solid {{ $line }}; margin: 24px 0; }
        .rte blockquote {
            margin: 18px 0; padding: 2px 0 2px 18px;
            border-left: 3px solid {{ $gold }}; color: {{ $muted }}; font-style: italic;
        }

        @media screen and (max-width: 600px) {
            .sp-x { padding-left: 24px !important; padding-right: 24px !important; }
            .h1   { font-size: 24px !important; line-height: 30px !important; }
            .btn  { display: block !important; width: 100% !important; }
            .code { font-size: 22px !important; letter-spacing: 3px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#F4F0EA;">

{{-- Inbox preview line. Without this, clients scrape the first markup they find. --}}
<div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px; color:#F4F0EA; opacity:0;">
    {{ $preheader }}
    &#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847;
</div>

<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#F4F0EA;">
    <tr>
        <td align="center" style="padding:32px 12px;">

            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="width:600px; max-width:600px; background-color:#FFFFFF;">

                {{-- ── Masthead ─────────────────────────────────────────── --}}
                <tr>
                    <td class="sp-x" align="center" bgcolor="{{ $ink }}" style="background-color:{{ $ink }}; padding:30px 40px;">
                        <div style="font-family:Georgia,'Times New Roman',serif; font-size:22px; line-height:26px; letter-spacing:4px; color:#FFFFFF; text-transform:uppercase;">PeytonGhalib</div>
                        <div style="font-family:Arial,Helvetica,sans-serif; font-size:10px; line-height:14px; letter-spacing:2px; color:{{ $gold }}; text-transform:uppercase; padding-top:7px;">Premium Furniture</div>
                    </td>
                </tr>

                {{-- ── Headline ─────────────────────────────────────────── --}}
                @if ($hasHeadline)
                <tr>
                    <td class="sp-x" style="padding:40px 40px 0 40px;">
                        @if (filled($eyebrow ?? null))
                            <div style="font-family:Arial,Helvetica,sans-serif; font-size:11px; line-height:16px; letter-spacing:2px; font-weight:bold; color:{{ $goldText }}; text-transform:uppercase;">{{ $eyebrow }}</div>
                        @endif
                        @if (filled($heading ?? null))
                            <h1 class="h1" style="margin:12px 0 0 0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:normal; color:{{ $ink }}; mso-line-height-rule:exactly;">{{ $heading }}</h1>
                        @endif
                    </td>
                </tr>
                @endif

                {{-- ── Greeting + editor body ───────────────────────────── --}}
                <tr>
                    <td class="rte sp-x" style="padding:{{ $bodyPadTop }} 40px 8px 40px; font-family:Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:{{ $body }}; mso-line-height-rule:exactly;">
                        @if (filled($recipientName))
                            <p style="margin:0 0 14px 0;">Hi {{ $recipientName }},</p>
                        @endif
                        {!! $bodyHtml !!}
                    </td>
                </tr>

                {{-- ── Promo code ───────────────────────────────────────── --}}
                @if (filled($promoCode ?? null))
                <tr>
                    <td class="sp-x" style="padding:24px 40px 0 40px;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="{{ $warm }}" style="background-color:{{ $warm }}; border:1px dashed {{ $gold }};">
                            <tr>
                                <td align="center" style="padding:22px 20px;">
                                    <div style="font-family:Arial,Helvetica,sans-serif; font-size:10px; line-height:14px; letter-spacing:2px; color:{{ $muted }}; text-transform:uppercase;">Your Code</div>
                                    <div class="code" style="font-family:Arial,Helvetica,sans-serif; font-size:26px; line-height:32px; letter-spacing:5px; font-weight:bold; color:{{ $ink }}; padding-top:8px;">{{ $promoCode }}</div>
                                    @if (filled($promoNote ?? null))
                                        <div style="font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:{{ $muted }}; padding-top:10px;">{{ $promoNote }}</div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @endif

                {{-- ── Call to action ───────────────────────────────────── --}}
                @if ($hasCta)
                <tr>
                    <td class="sp-x" align="center" style="padding:28px 40px 8px 40px;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center" bgcolor="{{ $ink }}" style="background-color:{{ $ink }};">
                                    <a class="btn" href="{{ $ctaUrl }}" target="_blank"
                                       style="display:inline-block; padding:15px 40px; font-family:Arial,Helvetica,sans-serif; font-size:13px; line-height:16px; letter-spacing:2px; font-weight:bold; color:#FFFFFF; text-transform:uppercase; text-decoration:none;">{{ $ctaLabel }}</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @endif

                {{-- ── Sign-off ─────────────────────────────────────────── --}}
                <tr>
                    <td class="sp-x" style="padding:28px 40px 36px 40px;">
                        <div style="border-top:1px solid {{ $line }}; padding-top:20px; font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:{{ $muted }};">
                            Questions about this offer? Just reply to this email — it reaches our team directly.
                        </div>
                    </td>
                </tr>

                {{-- ── Footer ───────────────────────────────────────────── --}}
                <tr>
                    <td class="sp-x" align="center" bgcolor="{{ $ink }}" style="background-color:{{ $ink }}; padding:28px 40px;">
                        <a href="{{ url('/shop') }}" style="font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:{{ $gold }}; text-decoration:none;">Shop</a>
                        <span style="font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:#5A544E; padding:0 8px;">|</span>
                        <a href="{{ url('/track-order') }}" style="font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:{{ $gold }}; text-decoration:none;">Track Order</a>
                        <span style="font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:#5A544E; padding:0 8px;">|</span>
                        <a href="{{ url('/faq') }}" style="font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:{{ $gold }}; text-decoration:none;">FAQ</a>

                        <div style="font-family:Arial,Helvetica,sans-serif; font-size:11px; line-height:18px; color:#8A847E; padding-top:16px;">
                            &copy; {{ date('Y') }} PeytonGhalib. All rights reserved.<br>
                            You are receiving this offer because you have an account or subscribed with us.
                            @if (filled($unsubscribeUrl ?? null))
                                <br><a href="{{ $unsubscribeUrl }}" style="color:#8A847E; text-decoration:underline;">Unsubscribe from offers</a>
                            @endif
                        </div>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
