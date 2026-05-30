@extends('layouts.app')

@section('title', 'Đơn Hàng Của Tôi')

@section('content')

<style>
    .glass-card{
        background: rgba(255,255,255,0.55);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border:1px solid rgba(255,255,255,0.45);
        box-shadow:
            0 10px 30px rgba(15,23,42,.06),
            inset 0 1px 0 rgba(255,255,255,.5);
    }

    .status-badge{
        padding: .45rem .8rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 700;
    }

    .status-pending{
        background: #fef3c7;
        color:#92400e;
    }

    .status-processing{
        background: #dbeafe;
        color:#1d4ed8;
    }

    .status-completed{
        background: #dcfce7;
        color:#166534;
    }

    .status-cancelled{
        background: #fee2e2;
        color:#dc2626;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-blue-100 py-10">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="mb-8">
            <div class="glass-card rounded-3xl p-6">

                <div class="flex items-center gap-4">

                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 8L5.4 5M7 13l-1.293 1.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-3xl font-black text-slate-800">
                            Đơn Hàng Của Tôi
                        </h1>

                        <p class="text-slate-500 mt-1">
                            Theo dõi lịch sử mua hàng và trạng thái đơn hàng
                        </p>
                    </div>

                </div>

            </div>
        </div>

        {{-- ORDERS --}}
        <div class="space-y-6">

            @forelse($orders as $order)

                <div class="glass-card rounded-3xl p-6">

                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6">

                        {{-- LEFT --}}
                        <div class="space-y-4 flex-1">

                            <div class="flex flex-wrap items-center gap-3">

                                <h2 class="text-xl font-black text-slate-800">
                                    #{{ $order->order_code ?? $order->id }}
                                </h2>

                                @php
                                    $statusClass = match($order->status){
                                        'pending' => 'status-pending',
                                        'processing' => 'status-processing',
                                        'completed' => 'status-completed',
                                        'cancelled' => 'status-cancelled',
                                        default => 'status-processing'
                                    };
                                @endphp

                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst($order->status) }}
                                </span>

                            </div>

                            <div class="grid sm:grid-cols-3 gap-4">

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                                        Ngày đặt
                                    </p>

                                    <p class="font-semibold text-slate-700">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                                        Thanh toán
                                    </p>

                                    <p class="font-semibold text-slate-700">
                                        {{ $order->payment_method ?? 'COD' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                                        Tổng tiền
                                    </p>

                                    <p class="font-black text-sky-600 text-lg">
                                        {{ number_format($order->total_amount, 0, ',', '.') }}đ
                                    </p>
                                </div>

                            </div>

                            {{-- ITEMS --}}
                            <div class="space-y-3 pt-2">

                                @foreach($order->items->take(2) as $item)

                                    <div class="flex items-center gap-4">

                                        <div class="w-16 h-16 rounded-2xl bg-white border border-slate-100 overflow-hidden flex items-center justify-center">

                                            @if($item->product?->thumbnail)
                                                <img
                                                    src="{{ asset('storage/' . $item->product->thumbnail) }}"
                                                    class="w-full h-full object-cover"
                                                >
                                            @endif

                                        </div>

                                        <div class="flex-1 min-w-0">

                                            <h3 class="font-bold text-slate-800 line-clamp-1">
                                                {{ $item->product_name }}
                                            </h3>

                                            <p class="text-sm text-slate-400">
                                                SL: {{ $item->quantity }}
                                            </p>

                                        </div>

                                        <div class="font-bold text-slate-700">
                                            {{ number_format($item->price, 0, ',', '.') }}đ
                                        </div>

                                    </div>

                                @endforeach

                                @if($order->items->count() > 2)
                                    <p class="text-sm text-slate-400">
                                        +{{ $order->items->count() - 2 }} sản phẩm khác
                                    </p>
                                @endif

                            </div>

                        </div>

                        {{-- RIGHT --}}
                        <div class="flex flex-col gap-3 xl:w-48">

                            <a href="{{ route('client.orders.show', $order->id) }}"
                               class="h-12 rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold flex items-center justify-center hover:scale-[1.02] transition-all shadow-lg shadow-sky-200">

                                Xem Chi Tiết

                            </a>

                            <a href="{{ route('client.shop') }}"
                               class="h-12 rounded-2xl border border-slate-200 bg-white/60 text-slate-700 font-bold flex items-center justify-center hover:bg-white transition-all">

                                Mua Lại

                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="glass-card rounded-3xl p-14 text-center">

                    <div class="w-24 h-24 mx-auto rounded-full bg-sky-100 flex items-center justify-center mb-6">

                        <svg class="w-12 h-12 text-sky-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 8L5.4 5M7 13l-1.293 1.293c-.63.63-.184 1.707.707 1.707H17"/>
                        </svg>

                    </div>

                    <h3 class="text-2xl font-black text-slate-800 mb-2">
                        Chưa có đơn hàng nào
                    </h3>

                    <p class="text-slate-500 mb-8">
                        Hãy khám phá cửa hàng và mua sắm ngay hôm nay.
                    </p>

                    <a href="{{ route('client.shop') }}"
                       class="inline-flex items-center justify-center h-12 px-8 rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold shadow-lg shadow-sky-200 hover:scale-[1.02] transition-all">

                        Mua Sắm Ngay

                    </a>

                </div>

            @endforelse

        </div>

        {{-- PAGINATION --}}
        <div class="mt-10">
            {{ $orders->links() }}
        </div>

    </div>

</div>

@endsection
