<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShopNova - Mua sắm thông minh')</title>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShopNova - Mua sắm thông minh')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Be Vietnam Pro', sans-serif; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>

    @stack('styles')
</head>

<body class="bg-white text-gray-900 antialiased">
    <header x-data="{ mobileOpen: false, searchOpen: false, cartCount: 0 }" class="sticky top-0 z-50">
    {{-- Announcement Bar - Nâng cấp tone đen xanh sâu thẳm mang hơi hướng công nghệ --}}
    <div class="bg-slate-950 text-white text-xs text-center py-2.5 px-4 tracking-widest uppercase border-b border-white/5">
        🚚 Miễn phí vận chuyển cho đơn hàng trên 500.000đ &nbsp;|&nbsp; Nhập mã <span class="text-sky-400 font-extrabold animate-pulse">WELCOME10</span> giảm 10%
    </div>

    {{-- Main Header - Hiệu ứng Kính mờ Glassmorphism cao cấp --}}
    <div class="bg-white/80 backdrop-blur-xl border-b border-slate-200/50 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo ShopNova - Đổi sang phong cách Modern Tech/Vibe --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="w-9 h-9 bg-slate-900 rounded-xl flex items-center justify-center group-hover:bg-sky-500 shadow-md group-hover:shadow-sky-500/30 transition-all duration-300">
                        <svg class="w-5 h-5 text-white group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25"/>
                        </svg>
                    </div>
                    <span class="text-xl font-black tracking-tight text-slate-900 transition-colors">
                        Shop<span class="text-sky-500 group-hover:text-sky-600 transition-colors">Nova</span>
                    </span>
                </a>

                {{-- Desktop Navigation --}}
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}"
                       class="px-4 py-2 text-sm font-bold transition-all duration-200 rounded-xl {{ request()->routeIs('home') ? 'text-sky-600 bg-sky-50/80 border border-sky-100/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/60' }}">
                        Trang chủ
                    </a>
                    <a href="{{ route('client.shop') }}"
                       class="px-4 py-2 text-sm font-bold transition-all duration-200 rounded-xl {{ request()->routeIs('client.shop') ? 'text-sky-600 bg-sky-50/80 border border-sky-100/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/60' }}">
                        Sản phẩm
                    </a>

                    {{-- Categories Dropdown --}}
                    <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="flex items-center gap-1 px-4 py-2 text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100/60 rounded-xl transition-all duration-200">
                            Danh mục
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180 text-sky-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown Menu tinh chỉnh theo phong cách sương mờ tương đồng trang chủ --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute top-full left-0 mt-1 w-56 bg-white/95 backdrop-blur-xl rounded-xl shadow-xl border border-slate-100 py-2 z-50">
                            @php
                                $categories = \App\Models\Category::whereNull('parent_id')->take(8)->get();
                            @endphp
                            @foreach($categories as $cat)
                            <a href="{{ route('client.shop', $cat->slug) }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-sky-50 hover:text-sky-600 transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                                {{ $cat->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <a href="#countdown" class="px-4 py-2 text-sm font-bold text-rose-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all duration-200 flex items-center gap-1">
                        Flash Sale <span class="animate-bounce">🔥</span>
                    </a>
                </nav>

                {{-- Right Actions --}}
                <div class="flex items-center gap-2">

                    {{-- Search Icon Trigger --}}
                    <div x-show="!searchOpen">
                        <button @click="searchOpen = true"
                                class="p-2.5 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all duration-200">
                            <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Search Bar Expanded - Bo tròn hiện đại hơn --}}
                    <div x-show="searchOpen" x-transition class="flex items-center gap-2">
                        <form action="{{ route('client.shop') }}" method="GET" class="flex items-center">
                            <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..."
                                   class="w-52 px-4 py-2 text-sm bg-slate-100 rounded-xl border-0 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all"
                                   x-ref="searchInput" @click.outside="searchOpen = false" @keyup.escape="searchOpen = false"
                                   x-init="$watch('searchOpen', val => val && $nextTick(() => $refs.searchInput.focus()))">
                        </form>
                        <button @click="searchOpen = false" class="p-2 text-slate-400 hover:text-slate-700">
                            <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Cart Badge - Đồng bộ màu sắc thành màu xanh dương Neon phát sáng --}}
                    <a href="{{ route('client.cart.index') }}"
                       class="relative p-2.5 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all duration-200">
                        <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @auth
                        <span class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-sky-500 text-white text-[10px] font-black rounded-full flex items-center justify-center shadow-md shadow-sky-500/30 ring-2 ring-white">
                            {{ session('cart_count', 0) }}
                        </span>
                        @endauth
                    </a>

                    {{-- User Menu --}}
                    @auth
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                                class="flex items-center gap-2 px-2.5 py-1.5 text-sm font-bold text-slate-700 hover:bg-slate-100 rounded-xl transition-all duration-200">
                            <div class="w-7 h-7 bg-sky-600 rounded-xl flex items-center justify-center shadow-sm shadow-sky-600/20">
                                <span class="text-white text-xs font-black">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                            <span class="hidden sm:block max-w-24 truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Tài khoản
                            </a>
                            @if(Route::has('profile.orders'))
                            <a href="{{ route('profile.orders') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Đơn hàng
                            </a>
                            @endif
                            <hr class="my-2 border-slate-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    {{-- Nút Đăng nhập/Đăng ký tone Xanh Dương Cao Cấp --}}
                    <a href="{{ route('login') }}"
                       class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold text-slate-700 border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200">
                        Đăng nhập
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold text-white bg-sky-500 hover:bg-sky-600 rounded-xl shadow-md shadow-sky-500/20 transition-all duration-200">
                        Đăng ký
                    </a>
                    @endauth

                    {{-- Mobile menu button --}}
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2.5 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-xl">
                        <svg x-show="!mobileOpen" class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileOpen" class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden bg-white/95 backdrop-blur-md border-t border-slate-100 px-4 py-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 hover:text-sky-600 rounded-xl">Trang chủ</a>
            <a href="{{ route('client.shop') }}" class="block px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 hover:text-sky-600 rounded-xl">Sản phẩm</a>
            <a href="#countdown" @click="mobileOpen = false" class="block px-4 py-3 text-sm font-bold text-rose-500 hover:bg-rose-50 rounded-xl">Flash Sale 🔥</a>
            @guest
            <div class="flex gap-2 pt-4 border-t border-slate-100 mt-2">
                <a href="{{ route('login') }}" class="flex-1 text-center py-2.5 text-sm font-bold border border-slate-200 rounded-xl text-slate-700">Đăng nhập</a>
                <a href="{{ route('register') }}" class="flex-1 text-center py-2.5 text-sm font-bold bg-sky-500 text-white rounded-xl shadow-md">Đăng ký</a>
            </div>
            @endguest
        </div>
    </div>
</header>

    <main>
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-20 right-4 z-50 bg-emerald-500 text-white px-5 py-3 rounded-2xl shadow-xl flex items-center gap-2 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-20 right-4 z-50 bg-red-500 text-white px-5 py-3 rounded-2xl shadow-xl flex items-center gap-2 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-slate-950 text-slate-400 mt-20 border-t border-slate-900 relative overflow-hidden">
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-sky-500/5 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute top-0 left-1/4 w-60 h-60 bg-blue-600/5 rounded-full blur-[100px] pointer-events-none"></div>

            {{-- Main Footer --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

                    {{-- Brand Column --}}
                    <div class="lg:col-span-1">
                        <a href="{{ route('home') }}" class="flex items-center gap-2 mb-5 group">
                            <div class="w-9 h-9 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-center group-hover:bg-sky-500 shadow-md group-hover:shadow-sky-500/20 transition-all duration-300">
                                <svg class="w-5 h-5 text-white group-hover:scale-105 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25"/>
                                </svg>
                            </div>
                            <span class="text-xl font-black text-white tracking-tight">
                                Shop<span class="text-sky-500 group-hover:text-sky-400 transition-colors">Nova</span>
                            </span>
                        </a>
                        <p class="text-sm leading-relaxed text-slate-400 mb-6">
                            Mua sắm thông minh — trải nghiệm công nghệ vượt trội, giao hàng siêu tốc và dịch vụ bảo hành tận tâm.
                        </p>

                        {{-- Social Links - Đổi hover sang tone xanh dịu mắt --}}
                        <div class="flex gap-3">
                            <a href="#" class="w-9 h-9 bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:bg-sky-500 hover:border-sky-500 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="w-9 h-9 bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:bg-sky-500 hover:border-sky-500 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                            <a href="#" class="w-9 h-9 bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:bg-sky-500 hover:border-sky-500 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.34 6.34 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.27 8.27 0 004.84 1.55V6.79a4.85 4.85 0 01-1.07-.1z"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Quick Links --}}
                    <div>
                        <h3 class="text-white font-extrabold text-xs uppercase tracking-widest mb-5 border-l-2 border-sky-500 pl-3">Mua sắm</h3>
                        <ul class="space-y-3">
                            <li><a href="{{ route('client.shop') }}" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Tất cả sản phẩm</a></li>
                            <li><a href="#countdown" class="text-sm text-slate-400 hover:text-rose-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-rose-400 transition-all duration-200"></span> Flash Sale 🔥</a></li>
                            <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Sản phẩm mới</a></li>
                            <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Bán chạy nhất</a></li>
                            <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Khuyến mãi</a></li>
                        </ul>
                    </div>

                    {{-- Support --}}
                    <div>
                        <h3 class="text-white font-extrabold text-xs uppercase tracking-widest mb-5 border-l-2 border-sky-500 pl-3">Hỗ trợ</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Chính sách đổi trả</a></li>
                            <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Chính sách bảo hành</a></li>
                            <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Hướng dẫn mua hàng</a></li>
                            <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Câu hỏi thường gặp</a></li>
                            <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Liên hệ</a></li>
                        </ul>
                    </div>

                    {{-- Contact & Newsletter --}}
                    <div>
                        <h3 class="text-white font-extrabold text-xs uppercase tracking-widest mb-5 border-l-2 border-sky-500 pl-3">Liên hệ</h3>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start gap-2.5 text-sm text-slate-400">
                                <svg class="w-4 h-4 mt-0.5 text-sky-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.statusCode 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                123 Đường ABC, Thái Nguyên
                            </li>
                            <li class="flex items-center gap-2.5 text-sm text-slate-400">
                                <svg class="w-4 h-4 text-sky-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                0987 654 321
                            </li>
                            <li class="flex items-center gap-2.5 text-sm text-slate-400">
                                <svg class="w-4 h-4 text-sky-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                hello@shopnova.vn
                            </li>
                        </ul>

                        {{-- Newsletter --}}
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-3">Nhận thông tin ưu đãi</p>
                            <form class="flex gap-2">
                                <input type="email" placeholder="Email của bạn..."
                                    class="flex-1 px-3 py-2.5 text-sm bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors">
                                <button type="submit" class="px-4 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-bold rounded-xl shadow-md shadow-sky-500/10 transition-colors">
                                    Gửi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="border-t border-slate-900 bg-slate-950/80 backdrop-blur-md relative z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500">
                        © {{ date('Y') }} ShopNova. Tất cả quyền được bảo lưu.
                    </p>

                    {{-- Cổng thanh toán đã fix lỗi hiển thị, căn giữa và có độ sáng dịu cao cấp --}}
                    <div class="flex items-center gap-4">

                        {{-- Logo Mastercard --}}
                        <div class="h-6 flex items-center justify-center opacity-60 hover:opacity-100 transition-opacity duration-300">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png"
                                alt="Mastercard" class="h-4 w-auto object-contain">
                        </div>

                        {{-- Badge MOMO --}}
                        <span class="text-[10px] text-slate-400 font-bold tracking-wider px-2 py-1 bg-slate-900 border border-slate-800/80 rounded-md hover:border-pink-500/30 hover:text-pink-400 transition-all duration-300 cursor-default">
                            MOMO
                        </span>

                        {{-- Badge PAYOS --}}
                        <span class="text-[10px] text-slate-400 font-bold tracking-wider px-2 py-1 bg-slate-900 border border-slate-800/80 rounded-md hover:border-sky-500/30 hover:text-sky-400 transition-all duration-300 cursor-default">
                            PAYOS
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    @stack('scripts')
</body>
</html>
