{{-- Product Card Component --}}
{{-- Usage: @include('client.components.product-card', ['product' => $product, 'badge' => 'new'|'sale']) --}}

@php
    $variantImage = optional($product->variants->firstWhere('image', '!=', null))->image;
    $image = $product->thumbnail ?? $variantImage;
    $regularPrice = $product->price ?? optional($product->variants)->min('price');
    $salePrice = $product->sale_price ?? optional($product->variants->whereNotNull('sale_price'))->min('sale_price');
@endphp

<a href="{{ route('client.product.show', $product->slug) }}"
   class="group rounded-2xl overflow-hidden border border-white/60 bg-white/58 backdrop-blur-2xl shadow-[0_18px_50px_rgba(15,23,42,0.10),inset_0_1px_0_rgba(255,255,255,0.72)] hover:border-white/90 hover:shadow-[0_26px_80px_rgba(15,23,42,0.18),inset_0_1px_0_rgba(255,255,255,0.84)] transition-all duration-300 hover:-translate-y-1 flex flex-col">

    {{-- Image --}}
    <div class="relative aspect-square overflow-hidden bg-white/35">
        @if($image)
        <img src="{{ asset('storage/' . $image) }}"
             alt="{{ $product->name }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
        @else
        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-white/30">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        @endif

        {{-- Badge --}}
        @if(isset($badge) && $badge === 'new')
        <span class="absolute top-2.5 left-2.5 bg-emerald-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">MỚI</span>
        @elseif(isset($badge) && $badge === 'sale')
        <span class="absolute top-2.5 left-2.5 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">SALE</span>
        @endif

        {{-- Quick Add to Cart --}}
        @auth
        <div class="absolute bottom-0 left-0 right-0 bg-gray-950/72 backdrop-blur-xl py-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300 border-t border-white/10">
            <button onclick="event.preventDefault(); addToCart({{ $product->id }})"
                    class="w-full text-white text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Thêm vào giỏ
            </button>
        </div>
        @endauth
    </div>

    {{-- Info --}}
    <div class="p-4 flex flex-col flex-1">
        @if($product->brand)
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">{{ $product->brand->name }}</p>
        @endif

        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-gray-900 transition-colors flex-1">
            {{ $product->name }}
        </h3>

        {{-- Rating --}}
        @if(isset($product->reviews_avg_rating) && $product->reviews_avg_rating)
        <div class="flex items-center gap-1.5 mb-2">
            <div class="flex">
                @for($i = 1; $i <= 5; $i++)
                <svg class="w-3.5 h-3.5 {{ $i <= round($product->reviews_avg_rating) ? 'text-yellow-400 fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                @endfor
            </div>
            <span class="text-xs text-gray-400">({{ $product->reviews_count ?? 0 }})</span>
        </div>
        @endif

        {{-- Price --}}
        <div class="flex items-center justify-between mt-auto">
            <div>
                @if($salePrice && $regularPrice && $salePrice < $regularPrice)
                <div class="flex items-center gap-1.5">
                    <span class="text-base font-black text-gray-900">{{ number_format($salePrice) }}đ</span>
                    <span class="text-xs text-gray-400 line-through">{{ number_format($regularPrice) }}đ</span>
                </div>
                @elseif($regularPrice)
                <span class="text-base font-black text-gray-900">{{ number_format($regularPrice) }}đ</span>
                @else
                <span class="text-base font-black text-gray-900">Liên hệ</span>
                @endif
            </div>
            @if($salePrice && $regularPrice && $salePrice < $regularPrice)
                <span class="text-xs font-bold text-red-600 bg-white/65 border border-white/70 px-2 py-0.5 rounded-full backdrop-blur">
                -{{ round((1 - $salePrice / $regularPrice) * 100) }}%
            </span>
            @endif
        </div>
    </div>
</a>

@push('scripts')
<script>
function addToCart(productId) {
    fetch('{{ route("client.cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
    .then(r => r.json())
    .then(data => {
        // Toast notification
        showToast(data.message || 'Đã thêm vào giỏ hàng!');
    });
}

function showToast(msg) {
    const t = document.createElement('div');
    t.className = 'fixed bottom-6 right-6 bg-gray-900 text-white px-5 py-3 rounded-2xl text-sm font-medium shadow-xl z-50 flex items-center gap-2 animate-bounce';
    t.innerHTML = `<svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> ${msg}`;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}
</script>
@endpush
