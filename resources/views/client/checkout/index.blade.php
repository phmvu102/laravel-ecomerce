{{-- resources/views/client/checkout/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')

<section class="relative overflow-hidden min-h-screen bg-gradient-to-br from-sky-50 via-blue-50 to-cyan-100 py-12">

    {{-- BACKGROUND --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-32 -left-32 h-96 w-96 rounded-full bg-sky-400/20 blur-3xl"></div>
        <div class="absolute top-1/3 -right-24 h-[28rem] w-[28rem] rounded-full bg-blue-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-cyan-300/20 blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="mb-10">
            <h1 class="mt-4 text-4xl font-black tracking-tight text-slate-900">
                Thanh toán đơn hàng
            </h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                Hoàn tất thông tin giao hàng và xác nhận phương thức thanh toán.
            </p>
        </div>

        <div class="grid gap-8 lg:grid-cols-[1fr_400px]">

            {{-- FORM --}}
            <form
                action="{{ route('client.orders.place') }}"
                method="POST"
                class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60"
            >
                @csrf

                <div class="grid gap-6 sm:grid-cols-2">

                    {{-- NAME --}}
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Họ và tên
                        </label>
                        <input
                            type="text"
                            name="shipping_name"
                            value="{{ old('receiver_name', auth()->user()->name) }}"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-5 py-3.5 text-slate-900 placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition"
                            placeholder="Nguyễn Văn A"
                        >
                        @error('shipping_name')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PHONE --}}
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Số điện thoại
                        </label>
                        <input
                            type="text"
                            name="shipping_phone"
                            value="{{ old('shipping_phone') }}"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-5 py-3.5 text-slate-900 placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition"
                            placeholder="0123456789"
                        >
                        @error('shipping_phone')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- EMAIL --}}
                <div class="mt-6">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Email
                    </label>
                    <input
                        type="email"
                        name="shipping_email"
                        value="{{ old('shipping_email', auth()->user()->email) }}"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-5 py-3.5 text-slate-900 placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition"
                        placeholder="example@gmail.com"
                    >
                    @error('shipping_email')
                        <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ADDRESS --}}
                <div class="mt-6">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Địa chỉ giao hàng
                    </label>
                    <textarea
                        name="shipping_address"
                        rows="4"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-5 py-3.5 text-slate-900 placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition"
                        placeholder="Nhập địa chỉ giao hàng..."
                    >{{ old('shipping_address') }}</textarea>
                    @error('shipping_address')
                        <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NOTE --}}
                <div class="mt-6">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Ghi chú
                    </label>
                    <textarea
                        name="customer_note"
                        rows="3"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-5 py-3.5 text-slate-900 placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition"
                        placeholder="Ghi chú cho đơn hàng..."
                    >{{ old('customer_note') }}</textarea>
                </div>

                {{-- PAYMENT --}}
                <div class="mt-8">
                    <h2 class="mb-4 text-sm font-bold uppercase tracking-widest text-slate-600">
                        Phương thức thanh toán
                    </h2>

                    <div class="space-y-3">
                        <label class="flex cursor-pointer items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 hover:border-sky-200 hover:bg-sky-50 transition">
                            <input type="radio" name="payment_method" value="cod" checked class="h-5 w-5 text-sky-600">
                            <div>
                                <p class="font-semibold text-slate-800">Thanh toán khi nhận hàng</p>
                                <p class="text-sm text-slate-500">COD / Cash On Delivery</p>
                            </div>
                        </label>

                        <label class="flex cursor-pointer items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 hover:border-sky-200 hover:bg-sky-50 transition">
                            <input type="radio" name="payment_method" value="bank" class="h-5 w-5 text-sky-600">
                            <div>
                                <p class="font-semibold text-slate-800">Ngân hàng</p>
                                <p class="text-sm text-slate-500">Thanh toán bằng tài khoản ngân hàng</p>
                            </div>
                        </label>

                        <label class="flex cursor-not-allowed items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-5 opacity-60">
                            <input type="radio" name="payment_method" value="momo" disabled class="h-5 w-5 text-sky-600">
                            <div>
                                <p class="font-semibold text-slate-800">Momo</p>
                                <p class="text-sm text-slate-500">Ví điện tử Momo</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- BUTTON --}}
                <button
                    type="submit"
                    class="mt-10 w-full rounded-2xl bg-gradient-to-r from-sky-600 to-blue-600 py-4 text-base font-bold text-white shadow-lg shadow-sky-500/30 hover:brightness-105 transition"
                >
                    Đặt hàng ngay • Thanh toán
                </button>

            </form>

            {{-- ORDER SUMMARY --}}
            <aside class="h-fit rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60">
                <h2 class="text-2xl font-bold text-slate-900">Tóm tắt đơn hàng</h2>

                <div class="mt-6 space-y-6">
                    @foreach($cart as $item)
                    @php
                        $variant = $item->productVariant;
                        $product = $variant?->product;
                        $image = $variant?->image;
                        $name = $product?->name ?? $item->product_name ?? 'Sản phẩm';
                    @endphp

                    <div class="flex gap-4">
                        <div class="h-20 w-20 overflow-hidden rounded-2xl bg-slate-100 flex-shrink-0">
                            @if($image)
                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $name }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-xs text-slate-400 font-medium">No Image</div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-800 leading-tight">{{ $name }}</h3>
                            <p class="text-sm text-slate-500 mt-1">Số lượng: {{ $item->quantity }}</p>
                            <p class="mt-2 font-bold text-slate-900">
                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ
                            </p>
                        </div>
                    </div>
                @endforeach
                </div>

                <div class="mt-8 border-t border-slate-200 pt-6">
                    <div class="flex justify-between items-baseline">
                        <span class=" font-medium text-slate-600">Tổng thanh toán</span>
                        <span class="text-2xl font-black text-slate-900">
                            {{ number_format($subtotal, 0, ',', '.') }} VNĐ
                        </span>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

@endsection
