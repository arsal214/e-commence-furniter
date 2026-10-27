@extends('seller.onboarding.layout', ['step' => 5])
@section('title', 'Add your bank')

@section('content')
<h1>Where should we send payouts?</h1>
<p class="pg-muted">A US bank account in your business's name. We pay out daily for delivered orders.</p>
<p class="pg-muted">You'll finish this on a secure page. Bank and ID details are handled by our payments partner. Peyton Ghalib never sees or stores your full account number.</p>

<form method="POST" action="{{ route('seller.bank.start') }}" data-once>
  @csrf
  <button class="pg-btn" type="submit">Connect your bank</button>
</form>
@endsection
