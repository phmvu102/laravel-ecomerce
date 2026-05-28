@extends('layouts.app')

@section('title', 'Đặt hàng thành công - ShopNova')

@section('content')
<div class="min-h-screen bg-slate-50 py-14">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-10 text-center">
            <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="text-3xl font-black text-slate-900">Đặt hàng thành công</h1>
            <p class="mt-3 text-sm text-slate-500">
                Mã đơn hàng của bạn là <span class="font-black text-slate-900">{{ $order->order_code }}</span>.
            </p>

            <div class="mt-8 rounded-2xl border border-slate-100 bg-slate-50 p-5 text-left">
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex justify-between gap-4 text-sm">
                            <div>
                                <p class="font-bold text-slate-800">{{ $item->productVariant->product->name }}</p>
                                <p class="text-xs text-slate-400">x{{ $item->quantity }}</p>
                            </div>
                            <p class="font-bold text-slate-900 whitespace-nowrap">{{ number_format($item->total, 0, ',', '.') }}đ</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 border-t border-slate-200 pt-4 flex justify-between">
                    <span class="font-black text-slate-900">Tổng thanh toán</span>
                    <span class="text-xl font-black text-slate-900">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('client.shop') }}" class="rounded-xl bg-sky-500 px-5 py-3 text-sm font-bold text-white hover:bg-sky-600">
                    Tiếp tục mua sắm
                </a>
                <a href="{{ route('home') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">
                    Về trang chủ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
