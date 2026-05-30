@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')

<style>
    body{
        background:
            radial-gradient(circle at top left, rgba(14,165,233,.18), transparent 30%),
            radial-gradient(circle at bottom right, rgba(59,130,246,.14), transparent 35%),
            linear-gradient(to bottom, #f0f9ff, #e0f2fe, #f8fafc);
    }

    .glass-card{
        background: rgba(255,255,255,.45);
        backdrop-filter: blur(24px) saturate(180%);
        -webkit-backdrop-filter: blur(24px) saturate(180%);
        border: 1px solid rgba(255,255,255,.35);
        box-shadow:
            0 10px 30px rgba(15,23,42,.06),
            inset 0 1px 0 rgba(255,255,255,.45);
    }

    .status{
        padding: .45rem 1rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }

    .status.pending{
        background: rgba(251,191,36,.12);
        color: #b45309;
    }

    .status.processing{
        background: rgba(59,130,246,.12);
        color: #1d4ed8;
    }

    .status.completed{
        background: rgba(34,197,94,.12);
        color: #15803d;
    }

    .status.cancelled{
        background: rgba(239,68,68,.12);
        color: #b91c1c;
    }
</style>

<div class="min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- HEADER --}}
        <div class="glass-card rounded-[2rem] p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <p class="text-sky-500 text-xs uppercase tracking-[0.25em] font-bold mb-2">
                        Chi tiết đơn hàng
                    </p>

                    <h1 class="text-3xl font-black text-slate-800">
                        #{{ $order->order_code }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-4 mt-4 text-sm text-slate-500">

                        <span>
                            Ngày đặt:
                            <strong class="text-slate-700">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </strong>
                        </span>

                        <span class="status {{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('profile.edit') }}"
                       class="px-5 py-3 rounded-2xl bg-white/70 hover:bg-white text-slate-700 font-semibold border border-white/50 transition">
                        Quay lại
                    </a>

                    @if(in_array($order->status, ['pending', 'processing']))
                    <form
                        action="{{ route('client.orders.cancel', $order->id) }}"
                        method="POST">

                        @csrf
                        @method('PATCH')

                        <button
                            onclick="return confirm('Bạn chắc chắn muốn hủy đơn?')"
                            class="px-5 py-3 rounded-2xl bg-red-500 text-white font-semibold hover:bg-red-600 transition">

                            Hủy đơn
                        </button>

                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- LEFT --}}
            <div class="xl:col-span-8 space-y-8">
                {{-- PRODUCTS --}}
                <div class="glass-card rounded-[2rem] p-7">
                    <div class="mb-6">
                        <p class="text-sky-500 text-xs uppercase tracking-[0.25em] font-bold mb-2">
                            Sản phẩm
                        </p>

                        <h2 class="text-2xl font-black text-slate-800">
                            Danh sách sản phẩm
                        </h2>
                    </div>

                    <div class="space-y-5">
                        @foreach($order->items as $item)
                            @php
                                $variant = $item->productVariant;
                                $product = $variant?->product;
                            @endphp

                            <div class="flex flex-col sm:flex-row gap-5 p-5 rounded-3xl bg-white/40 border border-white/30">
                                {{-- IMAGE --}}
                                <div class="w-24 h-24 rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0">
                                    @if($variant?->image)
                                        <img
                                            src="{{ asset('storage/' . $variant->image) }}"
                                            class="w-full h-full object-cover"
                                            alt="{{ $product?->name }}">
                                    @endif
                                </div>

                                {{-- INFO --}}
                                <div class="flex-1">
                                    <h3 class="text-lg font-black text-slate-800">
                                        {{ $product?->name }}
                                    </h3>

                                    <p class="text-sm text-slate-500 mt-1">
                                        SKU: {{ $variant?->sku }}
                                    </p>

                                    <div class="flex flex-wrap gap-6 mt-4 text-sm">
                                        <div>
                                            <p class="text-slate-400 mb-1">
                                                Đơn giá
                                            </p>

                                            <p class="font-bold text-slate-800">
                                                {{ number_format($item->price, 0, ',', '.') }}đ
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-slate-400 mb-1">
                                                Số lượng
                                            </p>

                                            <p class="font-bold text-slate-800">
                                                x{{ $item->quantity }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-slate-400 mb-1">
                                                Thành tiền
                                            </p>

                                            <p class="font-black text-sky-600">
                                                {{ number_format($item->total, 0, ',', '.') }}đ
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="xl:col-span-4 space-y-8">
                {{-- SHIPPING --}}
                <div class="glass-card rounded-[2rem] p-7">
                    <div class="mb-6">
                        <p class="text-sky-500 text-xs uppercase tracking-[0.25em] font-bold mb-2">
                            Giao hàng
                        </p>

                        <h2 class="text-2xl font-black text-slate-800">
                            Thông tin nhận hàng
                        </h2>
                    </div>

                    <div class="space-y-5 text-sm">
                        <div>
                            <p class="text-slate-400 mb-1">
                                Người nhận
                            </p>

                            <p class="font-bold text-slate-800">
                                {{ $order->shipping_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1">
                                Số điện thoại
                            </p>

                            <p class="font-bold text-slate-800">
                                {{ $order->shipping_phone }}
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1">
                                Địa chỉ
                            </p>

                            <p class="font-bold text-slate-800 leading-relaxed">
                                {{ $order->shipping_address }}
                            </p>
                        </div>

                        @if($order->customer_note)
                        <div>
                            <p class="text-slate-400 mb-1">
                                Ghi chú
                            </p>

                            <p class="font-bold text-slate-800 leading-relaxed">
                                {{ $order->customer_note }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- PAYMENT --}}
                <div class="glass-card rounded-[2rem] p-7">
                    <div class="mb-6">
                        <p class="text-sky-500 text-xs uppercase tracking-[0.25em] font-bold mb-2">
                            Thanh toán
                        </p>

                        <h2 class="text-2xl font-black text-slate-800">
                            Tổng thanh toán
                        </h2>
                    </div>

                    <div class="space-y-4 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">
                                Tạm tính
                            </span>

                            <span class="font-bold text-slate-800">
                                {{ number_format($order->subtotal, 0, ',', '.') }}đ
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">
                                Phí vận chuyển
                            </span>

                            <span class="font-bold text-slate-800">
                                {{ number_format($order->shipping_fee, 0, ',', '.') }}đ
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">
                                Giảm giá
                            </span>

                            <span class="font-bold text-red-500">
                                -{{ number_format($order->discount_amount, 0, ',', '.') }}đ
                            </span>
                        </div>

                        <div class="border-t border-white/30 pt-4 flex items-center justify-between">
                            <span class="text-lg font-bold text-slate-800">
                                Tổng cộng
                            </span>

                            <span class="text-2xl font-black text-sky-600">
                                {{ number_format($order->total_amount, 0, ',', '.') }}đ
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
