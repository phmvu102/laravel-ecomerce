@extends('layouts.app')

@section('title', 'Giỏ hàng - ShopNova')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">
                <a href="{{ route('home') }}" class="hover:text-sky-500">Trang chủ</a>
                <span>/</span>
                <span class="text-slate-700">Giỏ hàng</span>
            </nav>
            <h1 class="text-3xl font-black text-slate-900">Giỏ hàng</h1>
        </div>

        @if(session('success'))
            <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white py-20 text-center">
                <svg class="w-14 h-14 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <p class="text-slate-500 font-semibold mb-5">Giỏ hàng của bạn đang trống.</p>
                <a href="{{ route('client.shop') }}" class="inline-flex items-center justify-center rounded-xl bg-sky-500 px-5 py-3 text-sm font-bold text-white hover:bg-sky-600">
                    Tiếp tục mua sắm
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        @php
                            $variant = $item['variant'];
                            $product = $variant->product;
                            $image = $variant->image;
                            $attributes = $variant->attributeValues
                                ->map(fn ($value) => ($value->attribute->name ?? 'Thuộc tính').': '.$value->value)
                                ->implode(' / ');
                        @endphp
                        <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5">
                            <div class="flex gap-4">
                                <a href="{{ route('client.product.show', $product->slug) }}" class="w-24 h-24 rounded-xl bg-slate-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if($image)
                                        <img src="{{ asset('storage/'.$image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
                                    @else
                                        <span class="text-xs text-slate-400">No image</span>
                                    @endif
                                </a>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-xs font-black uppercase tracking-widest text-sky-600">{{ $product->brand->name ?? 'ShopNova' }}</p>
                                            <a href="{{ route('client.product.show', $product->slug) }}" class="mt-1 block text-base font-black text-slate-900 hover:text-sky-600">
                                                {{ $product->name }}
                                            </a>
                                            @if($attributes)
                                                <p class="mt-1 text-xs font-medium text-slate-500">{{ $attributes }}</p>
                                            @endif
                                            <p class="mt-3 text-sm font-black text-slate-900">{{ number_format($item['price'], 0, ',', '.') }}đ</p>
                                        </div>

                                        <form action="{{ route('client.cart.remove', $variant->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-bold text-slate-400 hover:text-rose-600">Xóa</button>
                                        </form>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between gap-3">
                                        <form action="{{ route('client.cart.update') }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="product_variant_id" value="{{ $variant->id }}">
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99"
                                                   class="w-20 rounded-xl border-slate-200 text-sm font-bold text-slate-800 focus:border-sky-400 focus:ring-sky-400">
                                            <button type="submit" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50">
                                                Cập nhật
                                            </button>
                                        </form>
                                        <p class="text-base font-black text-slate-900">{{ number_format($item['total'], 0, ',', '.') }}đ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <aside class="bg-white border border-slate-200 rounded-2xl p-5 h-fit sticky top-24">
                    <h2 class="text-lg font-black text-slate-900 mb-5">Tổng đơn hàng</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-slate-500">
                            <span>Tạm tính</span>
                            <span class="font-bold text-slate-800">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Phí vận chuyển</span>
                            <span class="font-bold text-slate-800">{{ $shippingFee > 0 ? number_format($shippingFee, 0, ',', '.').'đ' : 'Miễn phí' }}</span>
                        </div>
                        <div class="border-t border-slate-100 pt-3 flex justify-between">
                            <span class="font-black text-slate-900">Tổng cộng</span>
                            <span class="text-xl font-black text-slate-900">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                    </div>

                    <a href="{{ route('client.checkout') }}" class="mt-5 flex w-full items-center justify-center rounded-xl bg-sky-500 px-5 py-3 text-sm font-bold text-white hover:bg-sky-600">
                        Thanh toán
                    </a>

                    <form action="{{ route('client.cart.clear') }}" method="POST" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50">
                            Xóa toàn bộ giỏ
                        </button>
                    </form>
                </aside>
            </div>
        @endif
    </div>
</div>
@endsection
