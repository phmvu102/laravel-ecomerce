<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] { display: none !important; }

        i[data-lucide] {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800 antialiased">
<div
    x-data="{
        sidebarOpen: localStorage.getItem('sidebar_open') !== 'false',
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebar_open', this.sidebarOpen);
            // Cập nhật lại icon sau khi toggle
            setTimeout(() => lucide.createIcons(), 10);
        }
    }"
    class="min-h-screen flex"
    >
    <!-- Sidebar -->
    <aside
        :class="sidebarOpen ? 'w-72' : 'w-20'"
        class="flex-shrink-0 bg-slate-900 text-white transition-all duration-300 flex flex-col shadow-2xl"
    >
        <!-- Header Sidebar -->
        <div class="h-20 flex items-center justify-between px-5 border-b border-slate-800">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-lg flex-shrink-0">
                    V
                </div>

                <div x-show="sidebarOpen" x-cloak>
                    <h1 class="font-bold text-lg tracking-wide whitespace-nowrap">
                        VUCCI ADMIN
                    </h1>
                    <p class="text-xs text-slate-400 whitespace-nowrap">
                        Management System
                    </p>
                </div>
            </div>

            <button
                type="button"
                @click="toggleSidebar"
                class="hover:bg-slate-800 p-2 rounded-lg transition text-slate-400 hover:text-white"
            >
                <i :data-lucide="sidebarOpen ? 'chevron-left' : 'menu'"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition
               {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                <i data-lucide="layout-dashboard"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Dashboard</span>
            </a>

            @php
                $productMenuActive = request()->routeIs('admin.products.*') ||
                                   request()->routeIs('admin.categories.*') ||
                                   request()->routeIs('admin.brands.*') ||
                                   request()->routeIs('admin.attributes.*') ||
                                   request()->routeIs('admin.variants.*');
            @endphp

            <div x-data="{ open: {{ $productMenuActive ? 'true' : 'false' }} }">
                <button
                    type="button"
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition
                    {{ $productMenuActive ? 'bg-slate-800 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shopping-bag"></i>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Sản phẩm</span>
                    </div>
                    <i
                        x-show="sidebarOpen"
                        x-cloak
                        data-lucide="chevron-down"
                        class="w-4 h-4 transition-transform duration-300"
                        :class="open ? 'rotate-180' : ''"
                    ></i>
                </button>

                <div
                    x-show="open && sidebarOpen"
                    x-cloak
                    x-transition
                    class="ml-6 mt-2 space-y-1"
                >
                    <a href="{{ route('admin.products.index') }}" class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">Sản phẩm</a>
                    <a href="{{ route('admin.attributes.index') }}" class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.attributes.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">Thuộc tính & Biến thể</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">Danh mục</a>
                    <a href="{{ route('admin.brands.index') }}" class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.brands.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">Thương hiệu</a>
                </div>
            </div>

            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                {{ request()->routeIs('admin.orders.*')
                ? 'bg-indigo-600 text-white'
                : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                <i data-lucide="receipt-text"></i>
                <span x-show="sidebarOpen">Đơn hàng</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white transition">
                <i data-lucide="users"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Khách hàng</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white transition">
                <i data-lucide="warehouse"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Kho hàng</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white transition">
                <i data-lucide="monitor-smartphone"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">POS</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white transition">
                <i data-lucide="bar-chart-3"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Thống kê</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white transition">
                <i data-lucide="settings"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Cài đặt</span>
            </a>
        </nav>

        <!-- User Info -->
        <div class="border-t border-slate-800 p-4">
            <div class="flex items-center gap-3 overflow-hidden">
                <img src="https://i.pravatar.cc/100" alt="avatar"
                     class="w-12 h-12 rounded-full object-cover border-2 border-slate-700 flex-shrink-0">
                <div x-show="sidebarOpen" x-cloak class="whitespace-nowrap">
                    <h3 class="font-semibold text-sm truncate max-w-[150px]">
                        {{ Auth::user()->name }}
                    </h3>

                    <p class="text-xs text-slate-400 capitalize">
                        {{ Auth::user()->role }} Account
                    </p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">
        <header class="bg-white shadow-sm border-b border-slate-200 h-20 px-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h2 class="text-2xl font-bold">@yield('page-title', 'Dashboard')</h2>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden lg:flex items-center bg-slate-100 rounded-xl px-4 h-11 w-80">
                    <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
                    <input type="text" placeholder="Tìm kiếm..."
                           class="bg-transparent outline-none border-none w-full px-3 text-sm focus:ring-0">
                </div>

                <button class="relative w-11 h-11 rounded-xl bg-slate-100 hover:bg-slate-200 transition flex items-center justify-center">
                    <i data-lucide="bell" class="w-5 h-5 text-slate-600"></i>
                    <span class="absolute top-3 right-3 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <button class="w-11 h-11 rounded-xl bg-slate-100 hover:bg-slate-200 transition flex items-center justify-center">
                    <i data-lucide="moon" class="w-5 h-5 text-slate-600"></i>
                </button>

                <div x-data="{ openProfile: false }" class="relative">
                    <button @click="openProfile = !openProfile"
                            @click.outside="openProfile = false"
                            class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-slate-50 transition text-left focus:outline-none">
                        <img src="https://i.pravatar.cc/100"
                             class="w-11 h-11 rounded-full object-cover border border-slate-200">
                        <div class="hidden md:block pr-1">
                            <h4 class="font-semibold text-slate-700 leading-tight">{{ Auth::user()->name }}</h4>
                            <p class="text-xs text-slate-400 font-medium capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 hidden md:block transition-transform duration-200"
                           :class="openProfile ? 'rotate-180' : ''"></i>
                    </button>

                    <div
                        x-show="openProfile"
                        x-cloak
                        x-transition
                        class="absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50"
                    >

                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="font-semibold text-slate-800">
                                {{ Auth::user()->name }}
                            </p>

                            <p class="text-xs text-slate-500 truncate">
                                {{ Auth::user()->email }}
                            </p>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition">

                            <i data-lucide="user-circle" class="w-4 h-4"></i>
                            Hồ sơ cá nhân
                        </a>

                        <a href="{{ route('home') }}"
                        target="_blank"
                        class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition">

                            <i data-lucide="store" class="w-4 h-4"></i>
                            Xem website
                        </a>

                        <a href="{{ route('admin.users.show', Auth::id()) }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition">

                            <i data-lucide="badge-info" class="w-4 h-4"></i>
                            Thông tin tài khoản
                        </a>

                        <div class="border-t border-slate-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button
                                type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition"
                            >
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6">
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
                <span>/</span>
                <span class="text-slate-800 font-medium">@yield('page-title')</span>
            </div>
            @yield('content')
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>

@stack('scripts')
</body>
</html>
