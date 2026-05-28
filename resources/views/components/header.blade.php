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
                            <a href="{{ route('client.profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Tài khoản
                            </a>
                            <a href="{{ route('client.profile.orders') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Đơn hàng
                            </a>
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