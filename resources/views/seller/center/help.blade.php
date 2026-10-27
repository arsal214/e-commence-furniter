@extends('seller.center.layout')
@section('title', 'Help')

@section('content')
@php
  $articles = [
    ['Seller Agreement', 'Fees, payouts, levels and what we expect from sellers', '/seller-agreement'],
    ['Prohibited Items', 'What can and cannot be listed', '/prohibited-items'],
    ['Return & Refund Policy', 'How returns work for your buyers', '/return-policy'],
    ['Buyer Protection', 'When we step in and refund a buyer', '/buyer-protection'],
    ['Shipping Policy', 'What shoppers are promised about delivery', '/shipping-policy'],
    ['Referral program terms', 'Invite a seller, earn a free month', '/referral-terms'],
    ['Intellectual property & DMCA', 'Reporting and answering infringement claims', '/ip-policy'],
  ];
@endphp
<div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start">
  <div style="flex:2 1 400px;background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;overflow:hidden">
    <div style="padding:14px 18px;border-bottom:1px solid #F3F4F6"><b>Seller guides and policies</b></div>
    @foreach($articles as $a)
      <a href="{{ url($a[2]) }}" style="display:flex;justify-content:space-between;gap:12px;padding:14px 18px;border-bottom:1px solid #F3F4F6;font-size:14px;color:#111827"><span><b>{{ $a[0] }}</b><br><span style="font-size:12px;color:#6B7280">{{ $a[1] }}</span></span><span style="color:#C2410C">→</span></a>
    @endforeach
  </div>
  <div style="flex:1 1 280px;background:#111827;color:#FFFFFF;border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:10px">
    <b style="font-size:18px">Seller Support</b>
    <span style="font-size:14px;color:#D1D5DB;line-height:1.5">We usually reply within a few hours. Top and Elite sellers get priority.</span>
    <a href="mailto:info@peytonghalib.com" style="color:#FDBA74;font-weight:700">info@peytonghalib.com</a>
    <a href="tel:+19294699864" style="color:#FDBA74;font-weight:700">+1 (929) 469-9864</a>
    <span style="font-size:12px;color:#9CA3AF">Monday–Friday, 9 AM–6 PM ET</span>
    <a href="{{ url('/contact') }}" style="margin-top:4px;height:46px;border-radius:999px;background:#C2410C;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800">Contact us</a>
  </div>
</div>
@endsection
