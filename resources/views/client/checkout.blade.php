@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-black text-slate-900 mb-8">Checkout</h1>

    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
        <form action="{{ route('client.checkout.place') }}" method="POST" class="rounded-xl border border-slate-200 bg-white p-5">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="shipping_name" class="block text-sm font-bold text-slate-700 mb-1">Full name</label>
                    <input id="shipping_name" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required class="w-full rounded-lg border-slate-300 text-sm">
                    @error('shipping_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="shipping_phone" class="block text-sm font-bold text-slate-700 mb-1">Phone</label>
                    <input id="shipping_phone" name="shipping_phone" value="{{ old('shipping_phone', auth()->user()->phone) }}" required class="w-full rounded-lg border-slate-300 text-sm">
                    @error('shipping_phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4">
                <label for="shipping_address" class="block text-sm font-bold text-slate-700 mb-1">Shipping address</label>
                <textarea id="shipping_address" name="shipping_address" rows="4" required class="w-full rounded-lg border-slate-300 text-sm">{{ old('shipping_address') }}</textarea>
                @error('shipping_address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mt-4">
                <label for="customer_note" class="block text-sm font-bold text-slate-700 mb-1">Note</label>
                <textarea id="customer_note" name="customer_note" rows="3" class="w-full rounded-lg border-slate-300 text-sm">{{ old('customer_note') }}</textarea>
                @error('customer_note') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <input type="hidden" name="payment_method" value="COD">

            <button type="submit" class="mt-6 rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white hover:bg-slate-800">Place order</button>
        </form>

        <aside class="h-fit rounded-xl border border-slate-200 bg-white p-5">
            <h2 class="font-black text-slate-900 mb-4">Order summary</h2>

            <div class="space-y-4">
                @foreach($cart as $item)
                    <div class="flex justify-between gap-4 text-sm">
                        <div>
                            <p class="font-bold text-slate-800">{{ $item['name'] }}</p>
                            <p class="text-xs text-slate-500">Qty: {{ $item['quantity'] }}</p>
                        </div>
                        <p class="font-bold text-slate-900">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} VND</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-5 border-t border-slate-100 pt-4 flex justify-between">
                <span class="font-bold text-slate-600">Total</span>
                <span class="text-lg font-black text-slate-900">{{ number_format($subtotal, 0, ',', '.') }} VND</span>
            </div>
        </aside>
    </div>
</section>
@endsection
