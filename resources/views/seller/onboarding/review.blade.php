@extends('seller.onboarding.layout', ['step' => 6])
@section('title', 'Application submitted')

@section('content')
<div style="font-size:34px">⏳</div>
<h1>Application submitted</h1>
<p class="pg-muted">Our team reviews every seller by hand, usually within 1–2 business days. We'll email you the moment you're approved.</p>

<ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:8px;font-size:14px">
  @foreach([
    'Email verified' => true,
    'Seller plan, $19.99/month' => $seller->subscription_status === 'active',
    'Business details and registration certificate' => $seller->documents->contains('type', 'registration'),
    'Owner details and ID' => $seller->documents->contains('type', 'id_front'),
    'Bank account' => (bool) $seller->stripe_account_id,
  ] as $label => $ok)
    <li style="display:flex;justify-content:space-between;gap:12px"><span>{{ $label }}</span><b style="color:{{ $ok ? 'var(--pg-ok)' : 'var(--pg-warn)' }}">{{ $ok ? '✓ Done' : 'Missing' }}</b></li>
  @endforeach
</ul>
<p class="pg-muted">Status: <b>{{ str_replace('_', ' ', $seller->status) }}</b></p>
@endsection
