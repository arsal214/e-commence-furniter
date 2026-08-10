<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel – @yield('title', 'PeytonGhalib')</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">

{{-- h-dvh, not h-screen: on phones 100vh is the toolbar-less height, which pushes
     the bottom of the panel under the browser chrome. --}}
<div class="flex h-dvh overflow-hidden">

    <!-- Drawer backdrop (mobile only) -->
    <div id="admin-backdrop" data-sidebar-close
         class="hidden fixed inset-0 z-40 bg-black/50 lg:hidden" aria-hidden="true"></div>

    <!-- Sidebar: a slide-over under lg, a fixed column from lg up -->
    <aside id="admin-sidebar" aria-label="Admin navigation"
           class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] -translate-x-full transition-transform duration-200 ease-out
                  lg:static lg:z-auto lg:w-64 lg:max-w-none lg:translate-x-0
                  bg-[#1a1a2e] text-white flex flex-col flex-shrink-0">
        <div class="px-6 py-5 border-b border-white/10 relative">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#bb976d] flex items-center justify-center text-white font-bold text-sm">P</div>
                <span class="font-semibold text-white text-lg leading-none">PeytonGhalib</span>
            </a>
            <p class="text-xs text-white/50 mt-1 ml-11">Admin Panel</p>
            <button type="button" data-sidebar-close
                    class="lg:hidden absolute top-4 right-3 p-2 rounded-lg text-white/60 hover:text-white hover:bg-white/10 transition-colors">
                <i class="mdi mdi-close text-xl leading-none"></i>
                <span class="sr-only">Close navigation</span>
            </button>
        </div>

        <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.dashboard') ? 'bg-[#bb976d] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <i class="mdi mdi-view-dashboard text-lg"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.categories.*') ? 'bg-[#bb976d] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <i class="mdi mdi-tag-multiple text-lg"></i>
                Categories
            </a>
            <a href="{{ route('admin.products.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.products.*') && !request()->routeIs('admin.products.import') ? 'bg-[#bb976d] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <i class="mdi mdi-package-variant text-lg"></i>
                Products
            </a>
            <a href="{{ route('admin.products.import') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.products.import') ? 'bg-[#bb976d] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <i class="mdi mdi-upload text-lg"></i>
                Import CSV
            </a>
            <a href="{{ route('admin.reviews.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.reviews.*') ? 'bg-[#bb976d] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <i class="mdi mdi-star text-lg"></i>
                Reviews
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.orders.*') ? 'bg-[#bb976d] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <i class="mdi mdi-clipboard-list text-lg"></i>
                Orders
                @php $pendingCount = \App\Models\Order::where('status','pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.email-logs.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.email-logs.*') ? 'bg-[#bb976d] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <i class="mdi mdi-email-outline text-lg"></i>
                Email Logs
                @php
                    // This renders on every admin page. Code reaches the server
                    // before migrations run on a zip deploy, so a missing table
                    // must not take the whole panel down with it.
                    try {
                        $failedEmails = \App\Models\EmailLog::where('status', 'failed')->count();
                    } catch (\Throwable $e) {
                        $failedEmails = 0;
                    }
                @endphp
                @if($failedEmails > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $failedEmails }}</span>
                @endif
            </a>
            <a href="{{ route('admin.sliders.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.sliders.*') ? 'bg-[#bb976d] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <i class="mdi mdi-image-multiple text-lg"></i>
                Sliders
            </a>
            <a href="{{ route('admin.flash-deal.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.flash-deal*') ? 'bg-[#bb976d] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <i class="mdi mdi-lightning-bolt text-lg"></i>
                Flash Deal
            </a>
        </nav>

        <div class="px-4 py-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-3 py-2 mb-2">
                <div class="w-8 h-8 rounded-full bg-[#bb976d]/30 flex items-center justify-center text-[#bb976d] font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm text-white font-medium truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-white/40 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/70 hover:bg-white/10 hover:text-white transition-colors">
                    <i class="mdi mdi-logout text-lg"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Top bar -->
        <header class="bg-white border-b border-gray-200 px-3 sm:px-6 py-3 sm:py-4 flex items-center gap-2 sm:gap-3 flex-shrink-0">
            <button type="button" data-sidebar-open aria-controls="admin-sidebar" aria-expanded="false"
                    class="lg:hidden p-2 -ml-1 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                <i class="mdi mdi-menu text-2xl leading-none"></i>
                <span class="sr-only">Open navigation</span>
            </button>
            <h1 class="flex-1 min-w-0 truncate text-lg sm:text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-3 text-sm text-gray-500">
                <a href="{{ url('/') }}" target="_blank"
                   class="flex items-center gap-1 whitespace-nowrap hover:text-[#bb976d] transition-colors">
                    <i class="mdi mdi-open-in-new text-lg sm:text-base"></i>
                    <span class="hidden sm:inline">View Store</span>
                    <span class="sr-only sm:hidden">View Store</span>
                </a>
            </div>
        </header>

        <!-- Flash messages -->
        @if (session('success') || session('error'))
        <div class="px-4 sm:px-6 pt-4">
            @if (session('success'))
                <div class="flex items-start gap-3 p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm mb-0">
                    <i class="mdi mdi-check-circle text-green-600 mt-0.5"></i>
                    <span class="min-w-0 break-words">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="flex items-start gap-3 p-3 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm mb-0">
                    <i class="mdi mdi-alert-circle text-red-600 mt-0.5"></i>
                    <span class="min-w-0 break-words">{{ session('error') }}</span>
                </div>
            @endif
        </div>
        @endif

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-5 sm:py-6">
            @yield('content')
        </main>
    </div>

</div>

<script>
/* Off-canvas admin navigation. Plain JS on purpose — the panel ships no JS
   framework, and the server has no npm to build one. */
(function () {
    var sidebar  = document.getElementById('admin-sidebar');
    var backdrop = document.getElementById('admin-backdrop');
    var opener   = document.querySelector('[data-sidebar-open]');

    if (!sidebar || !backdrop || !opener) return;

    function setOpen(open) {
        // The lg: variants keep the desktop column pinned regardless of this class.
        sidebar.classList.toggle('-translate-x-full', !open);
        backdrop.classList.toggle('hidden', !open);
        opener.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    opener.addEventListener('click', function () { setOpen(true); });

    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-sidebar-close]')) setOpen(false);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') setOpen(false);
    });

    // Crossing into desktop with the drawer open would otherwise leave the
    // backdrop's state out of step with the now-static sidebar.
    window.matchMedia('(min-width: 1024px)').addEventListener('change', function (e) {
        if (e.matches) setOpen(false);
    });
})();
</script>

@stack('scripts')
</body>
</html>
