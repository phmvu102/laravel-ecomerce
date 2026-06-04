@extends('layouts.app')

@section('title', 'Cart')

@section('content')

<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900">
                Giỏ hàng
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                {{ $cart->sum('quantity') }} sản phẩm
            </p>
        </div>

        <a href="{{ route('client.shop') }}"
           class="text-sm font-bold text-sky-600 hover:text-sky-700">
            Tiếp tục mua sắm
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($cart->isEmpty())

        <div class="rounded-xl border border-slate-200 bg-white p-8 text-center">

            <p class="text-slate-600">
                Giỏ hàng của bạn đang trống.
            </p>

            <a href="{{ route('client.shop') }}"
               class="mt-5 inline-flex rounded-lg bg-sky-600 px-5 py-3 text-sm font-bold text-white hover:bg-sky-700">

                Shop now
            </a>
        </div>

    @else

        <div class="grid gap-6 lg:grid-cols-[1fr_320px]">

            {{-- CART LIST --}}
            <form action="{{ route('client.cart.update') }}"
                  method="POST"
                  class="rounded-xl border border-slate-200 bg-white overflow-hidden">

                @csrf
                @method('PATCH')

                @foreach($cart as $item)

                    @php
                        $variant = $item->productVariant;
                        $product = $variant?->product;
                    @endphp

                    @if($variant && $product)

                    <div class="flex flex-col gap-4 border-b border-slate-100 p-4 sm:flex-row sm:items-center">

                        {{-- IMAGE --}}
                        <div class="h-20 w-20 shrink-0 overflow-hidden rounded-lg bg-slate-100">

                            @if($variant->image)
                                <img
                                    src="{{ asset('storage/' . $variant->image) }}"
                                    alt="{{ $product->name }}"
                                    class="h-full w-full object-cover"
                                >
                            @endif
                        </div>

                        {{-- INFO --}}
                        <div class="min-w-0 flex-1">

                            <a href="{{ route('client.product.show', $product->slug) }}">
                                <h2 class="font-bold text-slate-900 hover:text-sky-600 transition">
                                    {{ $product->name }}
                                </h2>
                            </a>

                            <p class="text-xs text-slate-500 mt-1">
                                SKU: {{ $variant->sku }}
                            </p>

                            <p class="text-sm font-bold text-slate-900 mt-2">
                                {{ number_format($item->price, 0, ',', '.') }} VND
                            </p>
                        </div>

                        {{-- QUANTITY --}}
                        <div class="flex items-center gap-3">

                            <input
                                type="number"
                                name="items[{{ $variant->id }}]"
                                value="{{ $item->quantity }}"
                                min="0"
                                max="99"
                                class="w-20 rounded-lg border-slate-300 text-sm"
                            >

                            <p class="w-28 text-right text-sm font-black text-slate-900">
                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }} VND
                            </p>

                            {{-- REMOVE BUTTON --}}
                            <button
                                type="button"
                                onclick="removeFromCart({{ $variant->id }})"
                                class="rounded-lg p-2 text-red-500 hover:bg-red-50"
                            >
                                <svg class="w-5 h-5"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>

                        </div>

                    </div>

                    @endif

                @endforeach

                <div class="flex items-center justify-between gap-3 p-4">

                    <button
                        type="submit"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50">

                        Cập nhật giỏ hàng
                    </button>

                </div>
            </form>

            {{-- SUMMARY --}}
            <aside class="h-fit rounded-xl border border-slate-200 bg-white p-5">

                <div class="flex items-center justify-between border-b border-slate-100 pb-4">

                    <span class="text-sm font-bold text-slate-600">
                        Tổng tiền
                    </span>

                    <span class="text-lg font-black text-slate-900">
                        {{ number_format($subtotal, 0, ',', '.') }} VND
                    </span>
                </div>

                <a href="{{ route('client.orders.checkout') }}"
                   class="mt-5 flex w-full justify-center rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white hover:bg-slate-800">

                    Thanh toán
                </a>

                <form action="{{ route('client.cart.clear') }}"
                      method="POST"
                      class="mt-3">

                    @csrf
                    @method('DELETE')

                    <button
                        type="button"
                        onclick="clearCart()"
                        class="w-full rounded-lg px-5 py-2 text-sm font-bold text-red-600 hover:bg-red-50">
                        Xóa giỏ hàng
                    </button>
                </form>
            </aside>
        </div>
    @endif
</section>
@endsection

@push('scripts')
<script>
    function removeFromCart(variantId) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
            return;
        }

        fetch("{{ url('cart/remove') }}/" + variantId, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(response => {
            if (!response.ok) return response.json().then(err => { throw err; });
            return response.json();
        })
        .then(data => {
            alert(data.message || 'Đã xóa sản phẩm.');
            location.reload();
        })
        .catch(error => {
            alert(error.message || 'Lỗi xóa sản phẩm, vui lòng tải lại trang.');
        });
    }

    function clearCart() {
        if (!confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
            return;
        }

        fetch("{{ route('client.cart.clear') }}", {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(response => {
            if (!response.ok) return response.json().then(err => { throw err; });
            return response.json();
        })
        .then(data => {
            alert(data.message || 'Đã xóa giỏ hàng.');
            location.reload();
        })
        .catch(error => {
            alert(error.message || 'Lỗi xóa giỏ hàng, vui lòng tải lại trang.');
        });
    }
</script>
@endpush

