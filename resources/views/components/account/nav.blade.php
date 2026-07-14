{{--
    Account sidebar. Pass the current section so it highlights itself:
    <x-account.nav active="dashboard" />

    Logout is deliberately separated from the navigation links — it ends the
    session, so it shouldn't sit in the same tap-target run as "Wishlist".
--}}
@props(['active' => 'dashboard'])

@php
    $links = [
        'dashboard' => ['url' => url('/my-account'),    'label' => 'Dashboard'],
        'edit'      => ['url' => url('/edit-account'),  'label' => 'Edit Account'],
        'orders'    => ['url' => url('/order-history'), 'label' => 'Order History'],
        'wishlist'  => ['url' => url('/wishlist'),      'label' => 'Wishlist'],
    ];
@endphp

<nav class="acc-nav" aria-label="Account">
    <ul class="acc-nav__list">
        @foreach ($links as $key => $link)
            <li>
                <a
                    class="acc-nav__link @if ($active === $key) is-active @endif"
                    href="{{ $link['url'] }}"
                    @if ($active === $key) aria-current="page" @endif
                >
                    <span class="acc-nav__icon" aria-hidden="true">
                        @switch($key)
                            @case('dashboard')
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                                @break
                            @case('edit')
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                @break
                            @case('orders')
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
                                @break
                            @case('wishlist')
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1L12 21l7.7-7.7 1.1-1a5.5 5.5 0 0 0 0-7.7z"/></svg>
                                @break
                        @endswitch
                    </span>
                    <span>{{ $link['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <form class="acc-nav__logout" action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="acc-nav__link acc-nav__link--danger" type="submit">
            <span class="acc-nav__icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5M21 12H9"/></svg>
            </span>
            <span>Log out</span>
        </button>
    </form>
</nav>
