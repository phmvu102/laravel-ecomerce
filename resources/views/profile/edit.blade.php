@extends('layouts.app')

@section('title', 'Tài Khoản Của Tôi')

@section('content')
<style>
    body {
        background:
            radial-gradient(circle at top left, rgba(14,165,233,.18), transparent 30%),
            radial-gradient(circle at bottom right, rgba(59,130,246,.14), transparent 35%),
            linear-gradient(to bottom, #f0f9ff, #e0f2fe, #f8fafc);
    }
    .glass-card {
        background: rgba(255,255,255,0.45);
        backdrop-filter: blur(24px) saturate(180%);
        -webkit-backdrop-filter: blur(24px) saturate(180%);
        border: 1px solid rgba(255,255,255,0.35);
        box-shadow:
            0 10px 30px rgba(15,23,42,.06),
            inset 0 1px 0 rgba(255,255,255,.45);
    }
    .glass-soft {
        background: rgba(255,255,255,0.28);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,.25);
    }
    .menu-link {
        transition: .25s ease;
    }
    .menu-link:hover {
        transform: translateX(4px);
    }
    .menu-link.active {
        background: linear-gradient(to right, #0ea5e9, #38bdf8);
        color: white;
        box-shadow: 0 10px 20px rgba(14,165,233,.25);
    }
    .stat-card {
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: "";
        position: absolute;
        width: 120px;
        height: 120px;
        background: rgba(255,255,255,.15);
        border-radius: 999px;
        top: -40px;
        right: -40px;
    }
    .order-row {
        transition: .25s ease;
    }
    .order-row:hover {
        background: rgba(255,255,255,.55);
    }
    .status {
        padding: .35rem .85rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
    }
    .status.pending {
        background: rgba(251,191,36,.12);
        color: #b45309;
    }
    .status.processing {
        background: rgba(59,130,246,.12);
        color: #1d4ed8;
    }
    .status.completed {
        background: rgba(34,197,94,.12);
        color: #15803d;
    }
    .status.cancelled {
        background: rgba(239,68,68,.12);
        color: #b91c1c;
    }
    .profile-avatar {
        background:
            linear-gradient(135deg, #0ea5e9, #2563eb);
    }
    .table-scroll::-webkit-scrollbar {
        height: 6px;
    }
    .table-scroll::-webkit-scrollbar-thumb {
        background: rgba(148,163,184,.4);
        border-radius: 999px;
    }
</style>
<div class="min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="glass-card rounded-[2rem] p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="profile-avatar w-20 h-20 rounded-3xl flex items-center justify-center text-white text-3xl font-black shadow-2xl shadow-sky-500/20">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div>
                        <p class="text-sky-500 font-bold uppercase tracking-[0.25em] text-xs mb-2">
                            Tài khoản khách hàng
                        </p>

                        <h1 class="text-3xl font-black text-slate-800">
                            Xin chào, {{ auth()->user()->name }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-slate-500">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m0 0l3-3m-3 3l3 3"/>
                                </svg>
                                {{ auth()->user()->email }}
                            </span>

                            @if(auth()->user()->phone)
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h2l3 7-1.5 1.5a16 16 0 007 7L15 19l7 3v2a2 2 0 01-2 2C10.059 26 0 15.941 0 3a2 2 0 012-2h1z"/>
                                </svg>
                                {{ auth()->user()->phone }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button
                        onclick="openProfileModal()"
                        class="px-5 py-3 rounded-2xl bg-white/70 hover:bg-white text-slate-700 font-semibold border border-white/50 transition">
                        Chỉnh sửa hồ sơ
                    </button>

                    <a href="{{ route('client.shop') }}"
                       class="px-5 py-3 rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 text-white font-semibold shadow-lg shadow-sky-500/20 hover:scale-[1.02] transition">
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- Sidebar --}}
            <aside class="xl:col-span-3">
                <div class="glass-card rounded-[2rem] p-5 sticky top-24">
                    <div class="space-y-2">
                        <a href="#dashboard"
                           class="menu-link flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10h16V10"/>
                            </svg>
                            Tổng quan
                        </a>

                        <a href="#orders"
                           class="menu-link flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4"/>
                            </svg>
                            Đơn hàng của tôi
                        </a>

                        <a href="#address"
                           class="menu-link flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Địa chỉ nhận hàng
                        </a>

                        <a href="#security"
                           class="menu-link flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2h-1V9a5 5 0 00-10 0v2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                            </svg>
                            Bảo mật
                        </a>
                    </div>

                    <div class="mt-8 p-5 rounded-3xl bg-gradient-to-br from-sky-500 to-blue-600 text-white relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-28 h-28 rounded-full bg-white/10"></div>

                        <p class="text-xs uppercase tracking-[0.25em] text-white/70 font-bold mb-2">
                            Thành viên
                        </p>

                        <h3 class="text-2xl font-black mb-2">
                            Premium
                        </h3>

                        <p class="text-sm text-white/80 leading-relaxed">
                            Nhận ưu đãi độc quyền và cập nhật sản phẩm mới nhanh nhất.
                        </p>
                    </div>
                </div>
            </aside>

            {{-- Main Content --}}
            <main class="xl:col-span-9 space-y-8">
                {{-- Stats --}}
                <section id="dashboard">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="glass-card stat-card rounded-[2rem] p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-slate-500 font-medium">
                                        Tổng đơn hàng
                                    </p>

                                    <h3 class="text-4xl font-black text-slate-800 mt-2">
                                        {{ $orders->count() ?? 0 }}
                                    </h3>
                                </div>

                                <div class="w-14 h-14 rounded-2xl bg-sky-500/10 flex items-center justify-center text-sky-600">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="glass-card stat-card rounded-[2rem] p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-slate-500 font-medium">
                                        Đơn hoàn thành
                                    </p>

                                    <h3 class="text-4xl font-black text-slate-800 mt-2">
                                        {{ $orders->where('status', 'completed')->count() }}
                                    </h3>
                                </div>

                                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="glass-card stat-card rounded-[2rem] p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-slate-500 font-medium">
                                        Tổng chi tiêu
                                    </p>

                                    <h3 class="text-2xl font-black text-slate-800 mt-2">
                                        {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}đ
                                    </h3>
                                </div>

                                <div class="w-14 h-14 rounded-2xl bg-violet-500/10 flex items-center justify-center text-violet-600">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Orders --}}
                <section id="orders" class="glass-card rounded-[2rem] p-7">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sky-500 text-xs uppercase tracking-[0.25em] font-bold mb-2">
                                Lịch sử mua hàng
                            </p>

                            <h2 class="text-2xl font-black text-slate-800">
                                Đơn hàng gần đây
                            </h2>
                        </div>

                        <a href="{{ route('client.orders.index') }}"
                           class="text-sm font-bold text-sky-600 hover:text-sky-700">
                            Xem tất cả
                        </a>
                    </div>

                    <div class="overflow-x-auto table-scroll">
                        <table class="w-full min-w-[900px]">
                            <thead>
                                <tr class="text-left border-b border-white/30">
                                    <th class="pb-4 text-xs uppercase tracking-wider text-slate-400 font-black">
                                        Mã đơn
                                    </th>

                                    <th class="pb-4 text-xs uppercase tracking-wider text-slate-400 font-black">
                                        Ngày đặt
                                    </th>

                                    <th class="pb-4 text-xs uppercase tracking-wider text-slate-400 font-black">
                                        Thanh toán
                                    </th>

                                    <th class="pb-4 text-xs uppercase tracking-wider text-slate-400 font-black">
                                        Trạng thái
                                    </th>

                                    <th class="pb-4 text-xs uppercase tracking-wider text-slate-400 font-black">
                                        Tổng tiền
                                    </th>

                                    <th class="pb-4 text-xs uppercase tracking-wider text-slate-400 font-black text-right">
                                        Hành động
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($orders as $order)
                                <tr class="order-row border-b border-white/20">
                                    <td class="py-5 font-bold text-slate-700">
                                        #{{ $order->order_code }}
                                    </td>

                                    <td class="py-5 text-slate-500">
                                        {{ $order->created_at->format('d/m/Y') }}
                                    </td>

                                    <td class="py-5">
                                        <span class="capitalize">
                                            {{ $order->payment_method }}
                                        </span>
                                    </td>

                                    <td class="py-5">
                                        @php
                                            $statusText = match($order->status){
                                                'pending' => 'Chờ xử lý',
                                                'processing' => 'Đang xử lý',
                                                'shipping' => 'Đang giao',
                                                'completed' => 'Hoàn thành',
                                                'cancelled' => 'Đã huỷ',
                                                default => ucfirst($order->status)
                                            };
                                        @endphp
                                        <span class="status {{ $order->status }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>

                                    <td class="py-5 font-black text-slate-800">
                                        {{ number_format($order->total_amount, 0, ',', '.') }}đ
                                    </td>

                                    <td class="py-5 text-right">
                                        <a href="{{ route('client.orders.show', $order->id) }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white hover:bg-slate-800 transition">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>

                                @empty

                                <tr>
                                    <td colspan="6" class="py-10 text-center text-slate-500">
                                        Bạn chưa có đơn hàng nào.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </section>

                {{-- Address --}}
                <section id="address" class="glass-card rounded-[2rem] p-7">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sky-500 text-xs uppercase tracking-[0.25em] font-bold mb-2">
                                Thông tin giao hàng
                            </p>

                            <h2 class="text-2xl font-black text-slate-800">
                                Địa chỉ mặc định
                            </h2>
                        </div>
                    </div>

                    <div class="glass-soft rounded-3xl p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                                    Họ và tên
                                </p>

                                <h3 class="text-lg font-bold text-slate-800">
                                    {{ auth()->user()->name }}
                                </h3>
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                                    Email
                                </p>

                                <h3 class="text-lg font-bold text-slate-800">
                                    {{ auth()->user()->email }}
                                </h3>
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                                    Số điện thoại
                                </p>

                                <h3 class="text-lg font-bold text-slate-800">
                                    {{ auth()->user()->phone ?? 'Chưa cập nhật' }}
                                </h3>
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                                    Địa chỉ
                                </p>

                                <h3 class="text-lg font-bold text-slate-800">
                                    {{ auth()->user()->address ?? 'Chưa cập nhật địa chỉ' }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Security --}}
                <section id="security" class="glass-card rounded-[2rem] p-7">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sky-500 text-xs uppercase tracking-[0.25em] font-bold mb-2">
                                Bảo mật tài khoản
                            </p>

                            <h2 class="text-2xl font-black text-slate-800">
                                Cài đặt bảo mật
                            </h2>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="glass-soft rounded-3xl p-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2h-1V9a5 5 0 00-10 0v2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-800 mb-2">
                                        Mật khẩu
                                    </h3>

                                    <p class="text-sm text-slate-500 leading-relaxed mb-5">
                                        Đổi mật khẩu định kỳ để bảo vệ tài khoản của bạn an toàn hơn.
                                    </p>

                                    <button
                                        onclick="openProfileModal()"
                                        class="inline-flex items-center gap-2 text-sky-600 font-bold hover:text-sky-700">
                                        Chỉnh sửa hồ sơ
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="glass-soft rounded-3xl p-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-800 mb-2">
                                        Xác minh email
                                    </h3>

                                    <p class="text-sm text-slate-500 leading-relaxed mb-5">
                                        Trạng thái xác minh email của tài khoản hiện tại.
                                    </p>

                                    @if(auth()->user()->email_verified_at)
                                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500/10 text-emerald-600 font-bold text-sm">
                                            Đã xác minh
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-500/10 text-amber-600 font-bold text-sm">
                                            Chưa xác minh
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</div>

{{-- PROFILE MODAL --}}
<div
    id="profileModal"
    class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/40 backdrop-blur-sm p-4">

    <div
        class="relative w-full max-w-4xl rounded-[2rem] bg-white shadow-2xl overflow-hidden">

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-8 py-6 border-b border-slate-100">
            <div>
                <h2 class="text-2xl font-black text-slate-800">
                    Chỉnh sửa tài khoản
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Cập nhật thông tin hồ sơ và bảo mật.
                </p>
            </div>

            <button
                onclick="closeProfileModal()"
                class="w-11 h-11 rounded-2xl hover:bg-slate-100 flex items-center justify-center text-slate-500">
                ✕
            </button>
        </div>

        {{-- CONTENT --}}
        <div class="max-h-[80vh] overflow-y-auto p-8 space-y-8 bg-slate-50">
            <div class="glass-card rounded-[2rem] p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="glass-card rounded-[2rem] p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="glass-card rounded-[2rem] p-6 border border-red-100">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>

<script>
    function openProfileModal() {
        const modal = document.getElementById('profileModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.style.overflow = 'hidden';
    }

    function closeProfileModal() {
        const modal = document.getElementById('profileModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');

        document.body.style.overflow = '';
    }

    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeProfileModal();
        }
    });
</script>
@endsection
