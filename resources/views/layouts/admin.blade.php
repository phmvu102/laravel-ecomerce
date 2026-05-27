<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- AlpineJS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-slate-100 text-slate-800">
<div
    x-data="{ sidebarOpen: true }"
    class="min-h-screen flex"
>
    <aside
        :class="sidebarOpen ? 'w-72' : 'w-20'"
        class="bg-slate-900 text-white transition-all duration-300 flex flex-col shadow-2xl"
    >
        <div class="h-20 flex items-center justify-between px-5 border-b border-slate-800">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-lg">
                    V
                </div>

                <div x-show="sidebarOpen" x-transition>
                    <h1 class="font-bold text-lg tracking-wide">
                        VUCCI ADMIN
                    </h1>

                    <p class="text-xs text-slate-400">
                        Management System
                    </p>
                </div>
            </div>

            <button
                @click="sidebarOpen = !sidebarOpen"
                class="hover:bg-slate-800 p-2 rounded-lg transition"
            >
                <i data-lucide="menu"></i>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-2">
            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                {{ request()->routeIs('admin.dashboard')
                    ? 'bg-indigo-600 text-white shadow-lg'
                    : 'hover:bg-slate-800'
                }}"
            >
                <i data-lucide="layout-dashboard"></i>

                <span x-show="sidebarOpen">
                    Dashboard
                </span>
            </a>

            @php
                $productMenuActive =
                    request()->routeIs('admin.products.*') ||
                    request()->routeIs('admin.categories.*') ||
                    request()->routeIs('admin.brands.*') ||
                    request()->routeIs('admin.variants.*');
            @endphp

            <div x-data="{ open: {{ $productMenuActive ? 'true' : 'false' }} }">
                <button
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition
                    {{ $productMenuActive
                        ? 'bg-slate-800 text-white'
                        : 'hover:bg-slate-800'
                    }}"
                >
                    <div class="flex items-center gap-3">
                        <i data-lucide="shopping-bag"></i>

                        <span x-show="sidebarOpen">
                            Sản phẩm
                        </span>
                    </div>

                    <i
                        x-show="sidebarOpen"
                        data-lucide="chevron-down"
                        class="w-4 h-4"
                    ></i>
                </button>

                <div
                    x-show="open && sidebarOpen"
                    x-transition
                    class="ml-6 mt-2 space-y-1"
                >
                    <a
                        href="{{ route('admin.products.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('admin.products.*')
                            ? 'bg-indigo-600 text-white'
                            : 'hover:bg-slate-800'
                        }}"
                    >
                        Sản phẩm
                    </a>

                    <a
                        href="{{ route('admin.attributes.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('admin.attributes.*')
                            ? 'bg-indigo-600 text-white'
                            : 'hover:bg-slate-800'
                        }}"
                    >
                        Thuộc tính & Biến thể
                    </a>

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('admin.categories.*')
                            ? 'bg-indigo-600 text-white'
                            : 'hover:bg-slate-800'
                        }}"
                    >
                        Danh mục
                    </a>


                    <a
                        href="{{ route('admin.brands.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('admin.brands.*')
                            ? 'bg-indigo-600 text-white'
                            : 'hover:bg-slate-800'
                        }}"
                    >
                        Thương hiệu
                    </a>
                </div>
            </div>

            <a
                href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition"
            >
                <i data-lucide="receipt"></i>

                <span x-show="sidebarOpen">
                    Đơn hàng
                </span>
            </a>

            <a
                href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition"
            >
                <i data-lucide="users"></i>

                <span x-show="sidebarOpen">
                    Khách hàng
                </span>
            </a>

            <a
                href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition"
            >
                <i data-lucide="warehouse"></i>

                <span x-show="sidebarOpen">
                    Kho hàng
                </span>
            </a>

            <a
                href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition"
            >
                <i data-lucide="monitor-smartphone"></i>

                <span x-show="sidebarOpen">
                    POS
                </span>
            </a>

            <a
                href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition"
            >
                <i data-lucide="bar-chart-3"></i>

                <span x-show="sidebarOpen">
                    Thống kê
                </span>
            </a>

            <a
                href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition"
            >
                <i data-lucide="settings"></i>

                <span x-show="sidebarOpen">
                    Cài đặt
                </span>
            </a>
        </nav>

        <div class="border-t border-slate-800 p-4">
            <div class="flex items-center gap-3">
                <img
                    src="https://i.pravatar.cc/100"
                    alt="avatar"
                    class="w-12 h-12 rounded-full object-cover border-2 border-slate-700"
                >
                <div x-show="sidebarOpen">
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

    <div class="flex-1 flex flex-col min-h-screen">
        <header class="bg-white shadow-sm border-b border-slate-200 h-20 px-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h2 class="text-2xl font-bold">
                    @yield('page-title', 'Dashboard')
                </h2>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden lg:flex items-center bg-slate-100 rounded-xl px-4 h-11 w-80">
                    <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>

                    <input
                        type="text"
                        placeholder="Tìm kiếm..."
                        class="bg-transparent outline-none border-none w-full px-3 text-sm"
                    >
                </div>

                <button class="relative w-11 h-11 rounded-xl bg-slate-100 hover:bg-slate-200 transition">
                    <i data-lucide="bell" class="w-5 h-5 m-auto"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <button class="w-11 h-11 rounded-xl bg-slate-100 hover:bg-slate-200 transition">
                    <i data-lucide="moon" class="w-5 h-5 m-auto"></i>
                </button>

                <div x-data="{ openProfile: false }" class="relative">
                    <button
                        @click="openProfile = !openProfile"
                        @click.outside="openProfile = false"
                        class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-slate-50 transition text-left focus:outline-none"
                    >
                        <img
                            src="https://i.pravatar.cc/100"
                            class="w-11 h-11 rounded-full object-cover border border-slate-200"
                        >

                        <div class="hidden md:block pr-1">
                            <h4 class="font-semibold text-slate-700 leading-tight">
                                {{ Auth::user()->name }}
                            </h4>
                            <p class="text-xs text-slate-400 font-medium capitalize">
                                {{ Auth::user()->role }}
                            </p>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 hidden md:block transition-transform duration-200" :class="openProfile ? 'rotate-180' : ''"></i>
                    </button>

                    <div
                        x-show="openProfile"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50"
                        style="display: none;"
                    >
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            Hồ sơ cá nhân
                        </a>

                        <a href="#" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            Cài đặt cá nhân
                        </a>

                        <div class="border-t border-slate-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition text-left font-medium"
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
                <a href="#" class="hover:text-indigo-600">
                    Dashboard
                </a>

                <span>/</span>

                <span class="text-slate-800 font-medium">
                    @yield('page-title')
                </span>
            </div>

            @yield('content')
        </main>
    </div>
</div>
<script>
    // Khởi tạo Lucide Icons để render đúng icon dạng thẻ <i data-lucide="...">
    lucide.createIcons();
</script>
@yield('scripts')
</body>
</html>
