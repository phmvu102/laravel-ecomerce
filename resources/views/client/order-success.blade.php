@extends('layouts.app')

@section('title', 'Order placed')

@section('content')
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-6">
        <p class="text-sm font-bold text-emerald-700">Order placed successfully</p>
        <h1 class="mt-2 text-2xl font-black text-slate-900">Order {{ $order->order_code }}</h1>
        <p class="mt-2 text-sm text-slate-600">We received your order and will process it soon.</p>
    </div>

    <div class="mt-6 rounded-xl border border-slate-200 bg-white p-5">
        <h2 class="font-black text-slate-900 mb-4">Items</h2>

        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-4 last:border-b-0 last:pb-0">
                    <div>
                        <p class="font-bold text-slate-900">{{ $item->productVariant->product->name ?? 'Product' }}</p>
                        <p class="text-xs text-slate-500">Qty: {{ $item->quantity }}</p>
                    </div>
                    <p class="font-bold text-slate-900">{{ number_format($item->total, 0, ',', '.') }} VND</p>
                </div>
            @endforeach
        </div>

        <div class="mt-5 flex justify-between border-t border-slate-100 pt-4">
            <span class="font-bold text-slate-600">Total</span>
            <span class="text-lg font-black text-slate-900">{{ number_format($order->total_amount, 0, ',', '.') }} VND</span>
        </div>
    </div>

    <a href="{{ route('client.shop') }}" class="mt-6 inline-flex rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white hover:bg-slate-800">Continue shopping</a>
</section>
@endsection
