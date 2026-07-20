@php
    /**
     * Shared order email shell. Rendered by OrderConfirmationMail and
     * OrderStatusUpdatedMail — the copy is passed in, the order data is not.
     *
     * Email-safe rules:
     *   - Layout is <table> only. Outlook's Word engine ignores flex/grid.
     *   - Styles are inline. The <style> block only carries media queries,
     *     which Outlook ignores and every other client honours.
     *   - Fonts are Georgia/Arial. Webfonts don't load in Outlook or Gmail
     *     and fall back to Times, which breaks the type hierarchy.
     *
     * Expected: $order, $eyebrow, $heading, $intro
     * Optional: $showTracking (bool), $noteTitle, $noteBody
     */
    $showTracking = $showTracking ?? true;
    $noteTitle    = $noteTitle ?? 'Need to change something?';
    $noteBody     = $noteBody ?? "Just reply to this email within 24 hours and we'll sort it out before your order ships.";

    $gold      = '#BB976D'; // brand gold — fills and text on dark only (2.7:1 on white)
    $goldText  = '#8A6A3F'; // AA-safe gold for text on light backgrounds (4.98:1)
    $ink       = '#1A1A1A';
    $body      = '#3D3A36';
    $muted     = '#6B6560';
    $line      = '#E8E1D7';
    $warm      = '#FAF7F3';

    $steps   = ['Placed', 'Processing', 'Shipped', 'Delivered'];
    $current = ['pending' => 1, 'processing' => 2, 'shipped' => 3, 'delivered' => 4][$order->status] ?? 1;

    $shippingLabel = ['free' => 'Free Shipping', 'fast' => 'Fast Shipping', 'local' => 'Local Pickup'][$order->shipping] ?? ucfirst($order->shipping);
    $isPaid        = $order->payment_status === 'paid';
    $paymentLabel  = $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Card';
    $trackUrl      = url('/track-order?tracking=' . $order->tracking_number);

    // Carrier handoff details only exist once an admin has shipped the order.
    $hasShipment = $order->status === 'shipped' && ($order->carrier || $order->supplier_tracking);
@endphp
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>{{ $heading }}</title>
    <!--[if mso]>
    <xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml>
    <![endif]-->
    <style>
        /* Client resets */
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        table { border-collapse: collapse; }
        img { border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        a { text-decoration: none; }
        /* iOS/Gmail auto-linking of order refs and addresses */
        a[x-apple-data-detectors], .unstyle-auto-detected-links a, .aBn {
            color: inherit !important; text-decoration: none !important; font-size: inherit !important;
            font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important;
            border-bottom: 0 !important;
        }

        @media screen and (max-width: 600px) {
            .sp-x     { padding-left: 24px !important; padding-right: 24px !important; }
            .stack    { display: block !important; width: 100% !important; max-width: 100% !important; }
            .stack-pb { padding-bottom: 20px !important; }
            .h1       { font-size: 24px !important; line-height: 30px !important; }
            .track-no { font-size: 22px !important; letter-spacing: 2px !important; }
            .btn      { display: block !important; width: 100% !important; }
            .thumb    { width: 56px !important; height: 56px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#F4F0EA;">

{{-- Inbox preview line. Without this, clients scrape the first markup they find. --}}
<div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px; color:#F4F0EA; opacity:0;">
    {{ $intro }}
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
                <tr>
                    <td class="sp-x" style="padding:40px 40px 28px 40px;">
                        <div style="font-family:Arial,Helvetica,sans-serif; font-size:11px; line-height:16px; letter-spacing:2px; font-weight:bold; color:{{ $goldText }}; text-transform:uppercase;">{{ $eyebrow }}</div>
                        <h1 class="h1" style="margin:12px 0 0 0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:normal; color:{{ $ink }}; mso-line-height-rule:exactly;">{{ $heading }}</h1>
                        <p style="margin:14px 0 0 0; font-family:Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:{{ $body }}; mso-line-height-rule:exactly;">{{ $intro }}</p>
                    </td>
                </tr>

                {{-- ── Order facts ──────────────────────────────────────── --}}
                <tr>
                    <td class="sp-x" style="padding:0 40px;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="{{ $warm }}" style="background-color:{{ $warm }}; border:1px solid {{ $line }};">
                            <tr>
                                <td style="padding:18px 20px;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td class="stack stack-pb" width="33%" valign="top" style="font-family:Arial,Helvetica,sans-serif;">
                                                <div style="font-size:10px; line-height:14px; letter-spacing:1px; color:{{ $muted }}; text-transform:uppercase;">Order Date</div>
                                                <div style="font-size:14px; line-height:20px; font-weight:bold; color:{{ $ink }}; padding-top:4px;">{{ $order->created_at->format('d M Y') }}</div>
                                            </td>
                                            <td class="stack stack-pb" width="33%" valign="top" style="font-family:Arial,Helvetica,sans-serif;">
                                                <div style="font-size:10px; line-height:14px; letter-spacing:1px; color:{{ $muted }}; text-transform:uppercase;">Payment</div>
                                                <div style="font-size:14px; line-height:20px; font-weight:bold; color:{{ $ink }}; padding-top:4px;">{{ $paymentLabel }}</div>
                                            </td>
                                            <td class="stack" width="34%" valign="top" style="font-family:Arial,Helvetica,sans-serif;">
                                                <div style="font-size:10px; line-height:14px; letter-spacing:1px; color:{{ $muted }}; text-transform:uppercase;">Status</div>
                                                <div style="font-size:14px; line-height:20px; font-weight:bold; color:{{ $isPaid ? '#1F7A4C' : $goldText }}; padding-top:4px;">{{ $isPaid ? 'Paid' : 'Due on delivery' }}</div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- ── Tracking ─────────────────────────────────────────── --}}
                @if ($showTracking)
                <tr>
                    <td class="sp-x" style="padding:24px 40px 0 40px;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="{{ $ink }}" style="background-color:{{ $ink }};">
                            <tr>
                                <td align="center" style="padding:28px 24px;">
                                    <div style="font-family:Arial,Helvetica,sans-serif; font-size:10px; line-height:14px; letter-spacing:2px; color:#A9A29B; text-transform:uppercase;">Tracking Number</div>
                                    <div class="track-no unstyle-auto-detected-links" style="font-family:Georgia,'Times New Roman',serif; font-size:26px; line-height:34px; letter-spacing:3px; font-weight:bold; color:{{ $gold }}; padding:8px 0 20px 0; mso-line-height-rule:exactly;">{{ $order->tracking_number }}</div>

                                    {{-- Bulletproof button: VML for Outlook, anchor everywhere else --}}
                                    <!--[if mso]>
                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $trackUrl }}" style="height:46px; v-text-anchor:middle; width:200px;" arcsize="9%" stroke="f" fillcolor="{{ $gold }}">
                                        <w:anchorlock/>
                                        <center style="color:{{ $ink }}; font-family:Arial,sans-serif; font-size:14px; font-weight:bold;">Track My Order</center>
                                    </v:roundrect>
                                    <![endif]-->
                                    <!--[if !mso]><!-- -->
                                    <a class="btn" href="{{ $trackUrl }}" style="display:inline-block; background-color:{{ $gold }}; color:{{ $ink }}; font-family:Arial,Helvetica,sans-serif; font-size:14px; font-weight:bold; line-height:46px; text-align:center; text-decoration:none; padding:0 34px; border-radius:4px; mso-hide:all;">Track My Order</a>
                                    <!--<![endif]-->

                                    @if ($hasShipment)
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:22px; border-top:1px solid #3A3632;">
                                            <tr>
                                                @if ($order->carrier)
                                                    <td class="stack stack-pb" valign="top" align="center" style="padding-top:18px; font-family:Arial,Helvetica,sans-serif;">
                                                        <div style="font-size:10px; line-height:14px; letter-spacing:1px; color:#A9A29B; text-transform:uppercase;">Carrier</div>
                                                        <div style="font-size:14px; line-height:20px; font-weight:bold; color:#FFFFFF; padding-top:4px;">{{ $order->carrier }}</div>
                                                    </td>
                                                @endif
                                                @if ($order->supplier_tracking)
                                                    <td class="stack unstyle-auto-detected-links" valign="top" align="center" style="padding-top:18px; font-family:Arial,Helvetica,sans-serif;">
                                                        <div style="font-size:10px; line-height:14px; letter-spacing:1px; color:#A9A29B; text-transform:uppercase;">Carrier Tracking</div>
                                                        <div style="font-size:14px; line-height:20px; font-weight:bold; color:#FFFFFF; padding-top:4px;">{{ $order->supplier_tracking }}</div>
                                                    </td>
                                                @endif
                                            </tr>
                                        </table>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @endif

                {{-- ── Progress ─────────────────────────────────────────── --}}
                @if ($order->status !== 'cancelled')
                <tr>
                    <td class="sp-x" style="padding:28px 40px 0 40px;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                @foreach ($steps as $i => $step)
                                    @php $done = $i + 1 <= $current; @endphp
                                    <td width="25%" bgcolor="{{ $done ? $gold : $line }}" style="background-color:{{ $done ? $gold : $line }}; height:4px; line-height:4px; font-size:4px; border-right:2px solid #FFFFFF;">&nbsp;</td>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($steps as $i => $step)
                                    @php $done = $i + 1 <= $current; @endphp
                                    <td width="25%" valign="top" style="padding:8px 6px 0 0; font-family:Arial,Helvetica,sans-serif; font-size:11px; line-height:16px; font-weight:{{ $done ? 'bold' : 'normal' }}; color:{{ $done ? $goldText : $muted }};">
                                        {{-- Weight + a text mark carry the state, so it isn't colour-only --}}
                                        {!! $done ? '&#10003; ' : '' !!}{{ $step }}
                                    </td>
                                @endforeach
                            </tr>
                        </table>
                    </td>
                </tr>
                @endif

                {{-- ── Items ────────────────────────────────────────────── --}}
                <tr>
                    <td class="sp-x" style="padding:34px 40px 0 40px;">
                        <div style="font-family:Arial,Helvetica,sans-serif; font-size:11px; line-height:16px; letter-spacing:2px; font-weight:bold; color:{{ $goldText }}; text-transform:uppercase; padding-bottom:14px; border-bottom:2px solid {{ $ink }};">Your Order</div>

                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                            @foreach ($order->items as $item)
                                @php
                                    $raw = optional($item->product)->image;
                                    $img = $raw
                                        ? (\Str::startsWith($raw, ['http://', 'https://'])
                                            ? $raw
                                            : (\Str::startsWith($raw, 'assets/') ? asset($raw) : url(\Storage::url($raw))))
                                        : null;
                                @endphp
                                <tr>
                                    <td valign="top" style="padding:16px 0; border-bottom:1px solid {{ $line }};">
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="72" valign="top" style="padding-right:16px;">
                                                    @if ($img)
                                                        <img class="thumb" src="{{ $img }}" width="72" height="72" alt="{{ $item->name }}" style="display:block; width:72px; height:72px; border:1px solid {{ $line }}; background-color:{{ $warm }};">
                                                    @else
                                                        <div class="thumb" style="width:72px; height:72px; background-color:{{ $warm }}; border:1px solid {{ $line }};">&nbsp;</div>
                                                    @endif
                                                </td>
                                                <td valign="top" style="font-family:Arial,Helvetica,sans-serif;">
                                                    <div style="font-size:15px; line-height:21px; font-weight:bold; color:{{ $ink }};">{{ $item->name }}</div>
                                                    @if($item->color || $item->size)
                                                    <div style="font-size:13px; line-height:19px; color:{{ $muted }}; padding-top:4px;">{{ collect([$item->color ? 'Colour: '.$item->color : null, $item->size ? 'Size: '.$item->size : null])->filter()->implode(' · ') }}</div>
                                                    @endif
                                                    <div style="font-size:13px; line-height:19px; color:{{ $muted }}; padding-top:4px;">Qty {{ $item->qty }} &middot; ${{ number_format($item->price, 2) }} each</div>
                                                </td>
                                                <td width="90" valign="top" align="right" style="font-family:Arial,Helvetica,sans-serif; font-size:15px; line-height:21px; font-weight:bold; color:{{ $ink }};">
                                                    ${{ number_format($item->total, 2) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                        {{-- Totals: half-width, right-aligned --}}
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:8px;">
                            <tr>
                                <td width="45%" style="font-size:1px; line-height:1px;">&nbsp;</td>
                                <td width="55%">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="font-family:Arial,Helvetica,sans-serif;">
                                        <tr>
                                            <td style="padding:8px 0; font-size:14px; line-height:20px; color:{{ $body }};">Subtotal</td>
                                            <td align="right" style="padding:8px 0; font-size:14px; line-height:20px; color:{{ $body }};">${{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:0 0 12px 0; font-size:14px; line-height:20px; color:{{ $body }};">{{ $shippingLabel }}</td>
                                            <td align="right" style="padding:0 0 12px 0; font-size:14px; line-height:20px; color:{{ $order->shipping_cost > 0 ? $body : '#1F7A4C' }};">{{ $order->shipping_cost > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:12px 0 0 0; border-top:2px solid {{ $ink }}; font-family:Georgia,'Times New Roman',serif; font-size:17px; line-height:24px; color:{{ $ink }};">Total</td>
                                            <td align="right" style="padding:12px 0 0 0; border-top:2px solid {{ $ink }}; font-family:Georgia,'Times New Roman',serif; font-size:19px; line-height:24px; font-weight:bold; color:{{ $ink }};">${{ number_format($order->total, 2) }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- ── Delivery ─────────────────────────────────────────── --}}
                <tr>
                    <td class="sp-x" style="padding:34px 40px 0 40px;">
                        <div style="font-family:Arial,Helvetica,sans-serif; font-size:11px; line-height:16px; letter-spacing:2px; font-weight:bold; color:{{ $goldText }}; text-transform:uppercase; padding-bottom:14px; border-bottom:2px solid {{ $ink }};">Delivering To</div>

                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:18px;">
                            <tr>
                                <td class="stack stack-pb unstyle-auto-detected-links" width="50%" valign="top" style="font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:{{ $body }};">
                                    <strong style="color:{{ $ink }};">{{ $order->name }}</strong><br>
                                    {{ $order->address }}@if ($order->address2)<br>{{ $order->address2 }}@endif
                                    @if ($order->city || $order->zip)<br>{{ trim(collect([$order->city, $order->zip])->filter()->implode(', ')) }}@endif
                                    <br>{{ $order->phone }}
                                </td>
                                <td class="stack" width="50%" valign="top" style="font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:{{ $body }};">
                                    <div style="font-size:10px; line-height:14px; letter-spacing:1px; color:{{ $muted }}; text-transform:uppercase;">Method</div>
                                    <div style="padding-top:3px;">{{ $shippingLabel }}</div>
                                    @if ($order->notes)
                                        <div style="font-size:10px; line-height:14px; letter-spacing:1px; color:{{ $muted }}; text-transform:uppercase; padding-top:14px;">Notes</div>
                                        <div style="padding-top:3px;">{{ $order->notes }}</div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- ── Help ─────────────────────────────────────────────── --}}
                <tr>
                    <td class="sp-x" style="padding:34px 40px 40px 40px;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="{{ $warm }}" style="background-color:{{ $warm }}; border-left:3px solid {{ $gold }};">
                            <tr>
                                <td style="padding:18px 20px; font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:{{ $body }};">
                                    <strong style="color:{{ $ink }};">{{ $noteTitle }}</strong><br>
                                    {{ $noteBody }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- ── Footer ───────────────────────────────────────────── --}}
                <tr>
                    <td class="sp-x" align="center" bgcolor="{{ $ink }}" style="background-color:{{ $ink }}; padding:28px 40px;">
                        <a href="{{ url('/') }}" style="font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:{{ $gold }}; text-decoration:none;">Visit Store</a>
                        <span style="font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:#5A544E; padding:0 8px;">|</span>
                        <a href="{{ url('/track-order') }}" style="font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:{{ $gold }}; text-decoration:none;">Track Order</a>
                        <span style="font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:#5A544E; padding:0 8px;">|</span>
                        <a href="{{ url('/faq') }}" style="font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:{{ $gold }}; text-decoration:none;">FAQ</a>

                        <div style="font-family:Arial,Helvetica,sans-serif; font-size:11px; line-height:18px; color:#8A847E; padding-top:16px;">
                            &copy; {{ date('Y') }} PeytonGhalib. All rights reserved.<br>
                            This is a transactional message for order {{ $order->tracking_number }}.
                        </div>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
