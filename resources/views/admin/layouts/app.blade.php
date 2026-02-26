<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel – @yield('title', 'PeytonGhalib')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-[#1a1a2e] text-white flex flex-col flex-shrink-0">
        <div class="px-6 py-5 border-b border-white/10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#bb976d] flex items-center justify-center text-white font-bold text-sm">P</div>
                <span class="font-semibold text-white text-lg leading-none">PeytonGhalib</span>
            </a>
            <p class="text-xs text-white/50 mt-1 ml-11">Admin Panel</p>
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
                      {{ request()->routeIs('admin.products.*') ? 'bg-[#bb976d] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <i class="mdi mdi-package-variant text-lg"></i>
                Products
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
            <a href="{{ route('admin.sliders.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.sliders.*') ? 'bg-[#bb976d] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <i class="mdi mdi-image-multiple text-lg"></i>
                Sliders
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
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-3 text-sm text-gray-500">
                <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-1 hover:text-[#bb976d] transition-colors">
                    <i class="mdi mdi-open-in-new"></i> View Store
                </a>
            </div>
        </header>

        <!-- Flash messages -->
        <div class="px-6 pt-4">
            @if (session('success'))
                <div class="flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm mb-0">
                    <i class="mdi mdi-check-circle text-green-600"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="flex items-center gap-3 p-3 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm mb-0">
                    <i class="mdi mdi-alert-circle text-red-600"></i>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto px-6 py-6">
            @yield('content')
        </main>
    </div>

</div>

@stack('scripts')
</body>
</html>
