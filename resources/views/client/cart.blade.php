@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Shopping cart</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $cart->sum('quantity') }} item(s)</p>
        </div>
        <a href="{{ route('client.shop') }}" class="text-sm font-bold text-sky-600 hover:text-sky-700">Continue shopping</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    @if($cart->isEmpty())
        <div class="rounded-xl border border-slate-200 bg-white p-8 text-center">
            <p class="text-slate-600">Your cart is empty.</p>
            <a href="{{ route('client.shop') }}" class="mt-5 inline-flex rounded-lg bg-sky-600 px-5 py-3 text-sm font-bold text-white hover:bg-sky-700">Shop now</a>
        </div>
    @else
        <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <form action="{{ route('client.cart.update') }}" method="POST" class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                @csrf
                @method('PATCH')

                @foreach($cart as $item)
                    <div class="flex flex-col gap-4 border-b border-slate-100 p-4 sm:flex-row sm:items-center">
                        <div class="h-20 w-20 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                            @if($item['image'])
                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <h2 class="font-bold text-slate-900">{{ $item['name'] }}</h2>
                            <p class="text-xs text-slate-500 mt-1">SKU: {{ $item['sku'] }}</p>
                            <p class="text-sm font-bold text-slate-900 mt-2">{{ number_format($item['price'], 0, ',', '.') }} VND</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="number" name="items[{{ $item['variant_id'] }}]" value="{{ $item['quantity'] }}" min="0" max="99" class="w-20 rounded-lg border-slate-300 text-sm">
                            <p class="w-28 text-right text-sm font-black text-slate-900">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} VND</p>
                        </div>
                    </div>
                @endforeach

                <div class="flex items-center justify-between gap-3 p-4">
                    <button type="submit" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50">Update cart</button>
                </div>
            </form>

            <aside class="h-fit rounded-xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <span class="text-sm font-bold text-slate-600">Subtotal</span>
                    <span class="text-lg font-black text-slate-900">{{ number_format($subtotal, 0, ',', '.') }} VND</span>
                </div>

                <a href="{{ route('client.checkout') }}" class="mt-5 flex w-full justify-center rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white hover:bg-slate-800">Checkout</a>

                <form action="{{ route('client.cart.clear') }}" method="POST" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-lg px-5 py-2 text-sm font-bold text-red-600 hover:bg-red-50">Clear cart</button>
                </form>
            </aside>
        </div>
    @endif
</section>
@endsection
