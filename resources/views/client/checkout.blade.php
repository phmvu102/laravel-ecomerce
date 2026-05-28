@extends('layouts.app')

@section('title', 'Thanh toán - ShopNova')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-black text-slate-900 mb-8">Thanh toán</h1>

        <form action="{{ route('client.checkout.place') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf

            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-5 sm:p-6">
                <h2 class="text-lg font-black text-slate-900 mb-5">Thông tin nhận hàng</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Họ tên</label>
                        <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}"
                               class="w-full rounded-xl border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        @error('shipping_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Số điện thoại</label>
                        <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}"
                               class="w-full rounded-xl border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        @error('shipping_phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Địa chỉ giao hàng</label>
                    <textarea name="shipping_address" rows="4"
                              class="w-full rounded-xl border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">{{ old('shipping_address') }}</textarea>
                    @error('shipping_address')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Ghi chú</label>
                    <textarea name="customer_note" rows="3"
                              class="w-full rounded-xl border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">{{ old('customer_note') }}</textarea>
                    @error('customer_note')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="mt-5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Phương thức thanh toán</label>
                    <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 cursor-pointer">
                        <input type="radio" name="payment_method" value="COD" checked class="text-sky-500 focus:ring-sky-400">
                        <span class="text-sm font-bold text-slate-700">Thanh toán khi nhận hàng (COD)</span>
                    </label>
                    @error('payment_method')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <aside class="bg-white border border-slate-200 rounded-2xl p-5 h-fit sticky top-24">
                <h2 class="text-lg font-black text-slate-900 mb-5">Đơn hàng</h2>
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                        <div class="flex justify-between gap-3 text-sm">
                            <div>
                                <p class="font-bold text-slate-800">{{ $item['variant']->product->name }}</p>
                                <p class="text-xs text-slate-400">x{{ $item['quantity'] }}</p>
                            </div>
                            <p class="font-bold text-slate-900 whitespace-nowrap">{{ number_format($item['total'], 0, ',', '.') }}đ</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 border-t border-slate-100 pt-4 space-y-3 text-sm">
                    <div class="flex justify-between text-slate-500">
                        <span>Tạm tính</span>
                        <span class="font-bold text-slate-800">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Phí vận chuyển</span>
                        <span class="font-bold text-slate-800">{{ $shippingFee > 0 ? number_format($shippingFee, 0, ',', '.').'đ' : 'Miễn phí' }}</span>
                    </div>
                    <div class="flex justify-between border-t border-slate-100 pt-3">
                        <span class="font-black text-slate-900">Tổng cộng</span>
                        <span class="text-xl font-black text-slate-900">{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                </div>

                <button type="submit" class="mt-5 w-full rounded-xl bg-sky-500 px-5 py-3 text-sm font-bold text-white hover:bg-sky-600">
                    Đặt hàng
                </button>
            </aside>
        </form>
    </div>
</div>
@endsection
