@extends('layouts.app')

@section('title', 'Chi Tiết Sản Phẩm - ShopNova')

@push('styles')
<style>
    .detail-bg {
        background: #f8fafc;
        background-image:
            radial-gradient(circle at 10% 15%, rgba(14, 165, 233, 0.05) 0%, transparent 40%),
            radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.04) 0%, transparent 40%);
        min-height: 100vh;
    }

    .thumb-list {
        display: flex;
        flex-direction: column;
        gap: 0.625rem;
    }
    .thumb-item {
        width: 72px;
        height: 72px;
        border-radius: 0.75rem;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.375rem;
    }
    .thumb-item:hover { border-color: #7dd3fc; }
    .thumb-item.active { border-color: #0ea5e9; box-shadow: 0 0 0 3px rgba(14,165,233,0.15); }
    .thumb-item img { width: 100%; height: 100%; object-fit: contain; }

    .main-img-wrap {
        background: linear-gradient(135deg, #f1f5f9 0%, #f8fafc 100%);
        border-radius: 1.5rem;
        border: 1px solid #e2e8f0;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .main-img-wrap img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 16px 32px rgba(15,23,42,0.14));
        transition: transform 0.4s ease;
    }
    .main-img-wrap:hover img { transform: scale(1.04); }

    .badge-sale {
        background: #fff1f2; color: #e11d48;
        border: 1px solid #fecdd3;
        font-size: 0.7rem; font-weight: 800;
        letter-spacing: 0.06em; text-transform: uppercase;
        padding: 0.25rem 0.65rem; border-radius: 0.5rem;
    }
    .badge-new {
        background: #eff6ff; color: #2563eb;
        border: 1px solid #bfdbfe;
        font-size: 0.7rem; font-weight: 800;
        letter-spacing: 0.06em; text-transform: uppercase;
        padding: 0.25rem 0.65rem; border-radius: 0.5rem;
    }
    .badge-hot {
        background: #fff7ed; color: #ea580c;
        border: 1px solid #fed7aa;
        font-size: 0.7rem; font-weight: 800;
        letter-spacing: 0.06em; text-transform: uppercase;
        padding: 0.25rem 0.65rem; border-radius: 0.5rem;
    }

    .variant-btn {
        padding: 0.4rem 1rem;
        border-radius: 0.65rem;
        border: 1.5px solid #e2e8f0;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.18s ease;
    }
    .variant-btn:hover { border-color: #7dd3fc; color: #0369a1; background: #f0f9ff; }
    .variant-btn.active {
        border-color: #0ea5e9;
        color: #0369a1;
        background: #e0f2fe;
        box-shadow: 0 0 0 3px rgba(14,165,233,0.12);
    }
    .variant-btn.disabled {
        opacity: 0.4; cursor: not-allowed;
        text-decoration: line-through;
    }

    .color-dot {
        width: 2rem; height: 2rem;
        border-radius: 50%;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.18s;
        position: relative;
    }
    .color-dot:hover { transform: scale(1.1); }
    .color-dot.active {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 3px rgba(14,165,233,0.2);
    }

    .qty-btn {
        width: 2.25rem; height: 2.25rem;
        border-radius: 0.65rem;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: all 0.18s;
        font-size: 1rem; font-weight: 700;
    }
    .qty-btn:hover { border-color: #0ea5e9; color: #0ea5e9; background: #f0f9ff; }

    .btn-primary {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        background: #0ea5e9;
        color: #ffffff;
        font-weight: 700; font-size: 0.9375rem;
        padding: 0.875rem 2rem;
        border-radius: 0.875rem;
        border: none; cursor: pointer;
        box-shadow: 0 4px 14px rgba(14,165,233,0.35);
        transition: all 0.22s ease;
        width: 100%;
    }
    .btn-primary:hover { background: #0284c7; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(14,165,233,0.4); }
    .btn-primary:active { transform: translateY(0); }

    .btn-secondary {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        background: #ffffff;
        color: #0ea5e9;
        font-weight: 700; font-size: 0.9375rem;
        padding: 0.875rem 2rem;
        border-radius: 0.875rem;
        border: 1.5px solid #0ea5e9; cursor: pointer;
        transition: all 0.22s ease;
        width: 100%;
    }
    .btn-secondary:hover { background: #f0f9ff; transform: translateY(-1px); }

    .info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 1.25rem 1.5rem;
    }

    .star { color: #f59e0b; }
    .star-empty { color: #e2e8f0; }

    .tab-btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 700;
        color: #94a3b8;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .tab-btn:hover { color: #475569; }
    .tab-btn.active { color: #0ea5e9; border-bottom-color: #0ea5e9; }

    .related-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .related-card:hover {
        border-color: #bae6fd;
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(14,165,233,0.08);
    }
    .related-img {
        background: linear-gradient(135deg, #f1f5f9, #f8fafc);
        aspect-ratio: 1;
        display: flex; align-items: center; justify-content: center;
        padding: 1.25rem;
    }
    .related-img img {
        max-width: 100%; max-height: 100%; object-fit: contain;
        filter: drop-shadow(0 4px 8px rgba(15,23,42,0.1));
        transition: transform 0.4s;
    }
    .related-card:hover .related-img img { transform: scale(1.06); }

    .trust-item {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.875rem 1rem;
        border-radius: 0.875rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
</style>
@endpush

@section('content')
@php
    $variants       = $product->variants ?? collect();
    $primaryVariant = $variants->sortBy(fn ($v) => $v->sale_price ?? $v->price)->first();
    $primaryValueIds = $primaryVariant ? $primaryVariant->attributeValues->pluck('id')->all() : [];
    $variantImages  = $variants->pluck('image')->filter()->unique()->values();
    $fallbackImage  = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80';
    $mainImage      = $variantImages->first();
    $currentPrice   = $primaryVariant ? ($primaryVariant->sale_price ?? $primaryVariant->price) : $product->min_price;
    $originalPrice  = $primaryVariant ? $primaryVariant->price : $product->original_price;
    $isOnSale       = $product->is_on_sale && $originalPrice > $currentPrice;
    $savingAmount   = max(0, $originalPrice - $currentPrice);

    $attributeGroups = $variants
        ->flatMap(fn ($v) => $v->attributeValues)
        ->unique('id')
        ->groupBy(fn ($value) => $value->attribute->name ?? $value->attribute->code ?? 'Thuộc tính');

    $specifications = $product->specifications ?? [];

    $variantPayload = $variants->map(fn ($v) => [
        'id'                  => $v->id,
        'sku'                 => $v->sku,
        'price'               => (float) $v->price,
        'sale_price'          => $v->sale_price ? (float) $v->sale_price : null,
        'final_price'         => (float) ($v->sale_price ?? $v->price),
        'image_url'           => $v->image ? asset('storage/' . $v->image) : null,
        'attribute_value_ids' => $v->attributeValues->pluck('id')->values()->all(),
        'stock'               => (int) ($v->stock ?? $v->quantity ?? 0),
    ])->values();
@endphp

<div class="detail-bg pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        {{-- Breadcrumb --}}
        <nav class="flex items-center flex-wrap gap-1.5 text-xs text-slate-400 uppercase tracking-wider mb-8">
            <a href="{{ route('home') }}" class="hover:text-sky-500 transition-colors">Trang chủ</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('client.shop') }}" class="hover:text-sky-500 transition-colors">Sản phẩm</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            @if($product->category)
                <a href="{{ route('client.shop', $product->category->slug) }}" class="hover:text-sky-500 transition-colors">{{ $product->category->name }}</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            @endif
            <span class="text-slate-600 font-semibold line-clamp-1">{{ $product->name }}</span>
        </nav>

        {{-- Product Main Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 xl:gap-14">

            {{-- Gallery --}}
            <div class="flex gap-4">
                <div class="thumb-list hidden sm:flex">
                    @forelse($variantImages as $i => $img)
                        <div class="thumb-item {{ $i === 0 ? 'active' : '' }}" onclick="switchImage(this, '{{ asset('storage/' . $img) }}')">
                            <img src="{{ asset('storage/' . $img) }}" alt="{{ $product->name }}">
                        </div>
                    @empty
                        <div class="thumb-item active" onclick="switchImage(this, '{{ $fallbackImage }}')">
                            <img src="{{ $fallbackImage }}" alt="{{ $product->name }}">
                        </div>
                    @endforelse
                </div>

                <div class="flex-1">
                    <div class="main-img-wrap">
                        <div class="absolute top-4 left-4 flex flex-col gap-1.5 z-10">
                            <span id="salePercentBadge" class="badge-sale {{ $isOnSale ? '' : 'hidden' }}">
                                -{{ $isOnSale ? round((1 - $currentPrice / $originalPrice) * 100) : 0 }}%
                            </span>
                            @if($product->is_featured)
                                <span class="badge-hot">Hot</span>
                            @endif
                        </div>

                        <button class="absolute top-4 right-4 z-10 w-10 h-10 bg-white rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-200 transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>

                        <img id="mainImage"
                             src="{{ $mainImage ? asset('storage/' . $mainImage) : $fallbackImage }}"
                             alt="{{ $product->name }}">
                    </div>

                    {{-- Mobile thumbs --}}
                    <div class="flex gap-2.5 mt-3 sm:hidden overflow-x-auto pb-1">
                        @forelse($variantImages as $i => $img)
                            <div class="thumb-item flex-shrink-0 {{ $i === 0 ? 'active' : '' }}" onclick="switchImage(this, '{{ asset('storage/' . $img) }}')">
                                <img src="{{ asset('storage/' . $img) }}" alt="{{ $product->name }}">
                            </div>
                        @empty
                            <div class="thumb-item flex-shrink-0 active" onclick="switchImage(this, '{{ $fallbackImage }}')">
                                <img src="{{ $fallbackImage }}" alt="{{ $product->name }}">
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Product Info --}}
            <div class="flex flex-col gap-5">

                {{-- Brand + Name --}}
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-black tracking-widest uppercase text-sky-600 bg-sky-50 border border-sky-100 px-2.5 py-1 rounded-lg">
                            {{ $product->brand->name ?? 'ShopNova' }}
                        </span>
                        @if($primaryVariant)
                            <span id="variantSku" class="text-xs text-slate-400 font-mono">SKU: {{ $primaryVariant->sku }}</span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight tracking-tight">
                        {{ $product->name }}
                    </h1>
                </div>

                {{-- Rating + Stats --}}
                <div class="flex items-center flex-wrap gap-4">
                    <div class="flex items-center gap-1.5">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 star-empty" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm font-bold text-slate-700">0</span>
                        <span class="text-sm text-slate-400">(0 đánh giá)</span>
                    </div>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <span class="text-sm text-slate-500">Lượt xem <span class="font-bold text-slate-700">{{ number_format($product->view_count ?? 0) }}</span></span>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <span class="text-sm text-emerald-600 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ $variants->count() }} phiên bản
                    </span>
                </div>

                {{-- Price --}}
                <div class="flex items-end gap-3">
                    <span id="variantCurrentPrice" class="text-3xl font-black text-slate-900">
                        {{ number_format($currentPrice, 0, ',', '.') }}đ
                    </span>
                    <span id="variantOriginalPrice" class="text-lg text-slate-400 line-through font-medium mb-0.5 {{ $isOnSale ? '' : 'hidden' }}">
                        {{ number_format($originalPrice, 0, ',', '.') }}đ
                    </span>
                    <span id="variantSavingBadge" class="badge-sale mb-1 {{ $isOnSale ? '' : 'hidden' }}">
                        Tiết kiệm {{ number_format($savingAmount, 0, ',', '.') }}đ
                    </span>
                </div>

                {{-- Attribute Groups --}}
                @foreach($attributeGroups as $attributeName => $values)
                    @php
                        $activeColorValue = $values->first(fn ($v) => in_array($v->id, $primaryValueIds)) ?? $values->first();
                    @endphp
                    <div class="info-card">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">{{ $attributeName }}</p>
                        <div class="flex items-center gap-3 flex-wrap">
                            @foreach($values as $value)
                                @if($value->extra_value)
                                    <button class="color-dot variant-option {{ in_array($value->id, $primaryValueIds) ? 'active' : '' }}"
                                            style="background-color: {{ $value->extra_value }}; {{ strtolower($value->extra_value) === '#ffffff' ? 'border: 2px solid #e2e8f0;' : '' }}"
                                            title="{{ $value->value }}"
                                            data-name="{{ $value->value }}"
                                            data-value-id="{{ $value->id }}"
                                            onclick="selectColor(this)">
                                    </button>
                                @else
                                    <button class="variant-btn variant-option {{ in_array($value->id, $primaryValueIds) ? 'active' : '' }}"
                                            data-value-id="{{ $value->id }}"
                                            onclick="selectVariant(this)">
                                        {{ $value->value }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                        @if($values->contains(fn ($v) => filled($v->extra_value)))
                            <p class="color-label text-xs text-slate-500 mt-2 font-medium">{{ $activeColorValue->value }}</p>
                        @endif
                    </div>
                @endforeach

                {{-- Quantity + Actions --}}
                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-3 flex-wrap">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Số lượng:</p>
                        <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl p-1">
                            <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                            <span id="qtyDisplay" class="w-10 text-center text-sm font-bold text-slate-800">1</span>
                            <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                        </div>
                        <span id="variantStockDisplay" class="text-xs font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg ml-2">
                            Đang kiểm tra kho...
                        </span>
                    </div>

                    <input type="hidden" id="selectedVariantId" value="{{ $primaryVariant ? $primaryVariant->id : '' }}">

                    <div class="grid grid-cols-2 gap-3 mt-1">
                        <button type="button" id="btnAddToCart" onclick="handleCartAction('add')" class="btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span class="btn-text">Thêm vào giỏ</span>
                        </button>
                        <button type="button" id="btnBuyNow" onclick="handleCartAction('buy')" class="btn-secondary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span class="btn-text">Mua ngay</span>
                        </button>
                    </div>
                </div>

                {{-- Trust Badges --}}
                <div class="grid grid-cols-2 gap-2.5">
                    @foreach([
                        ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'label' => 'Hàng chính hãng 100%', 'color' => 'text-emerald-500 bg-emerald-50'],
                        ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'label' => 'Đổi trả trong 30 ngày', 'color' => 'text-sky-500 bg-sky-50'],
                        ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Giao hàng nhanh 2h', 'color' => 'text-orange-500 bg-orange-50'],
                        ['icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Hỗ trợ 24/7', 'color' => 'text-purple-500 bg-purple-50'],
                    ] as $trust)
                        <div class="trust-item">
                            <div class="w-8 h-8 rounded-lg {{ $trust['color'] }} flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $trust['icon'] }}"/></svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-600">{{ $trust['label'] }}</span>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        {{-- Tabs --}}
        <div class="mt-14 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="flex border-b border-slate-100 overflow-x-auto">
                <button class="tab-btn active" onclick="switchTab(this, 'tab-desc')">Mô tả sản phẩm</button>
                <button class="tab-btn" onclick="switchTab(this, 'tab-specs')">Thông số kỹ thuật</button>
                <button class="tab-btn" onclick="switchTab(this, 'tab-reviews')">
                    Đánh giá
                    <span class="ml-1.5 text-xs bg-sky-100 text-sky-600 font-bold px-1.5 py-0.5 rounded-full">0</span>
                </button>
            </div>

            <div id="tab-desc" class="tab-content p-6 sm:p-8">
                <div class="prose prose-slate max-w-none text-sm sm:text-base leading-relaxed text-slate-600">
                    @if($product->short_description)
                        <div class="text-base font-semibold text-slate-800 mb-4">{{ $product->short_description }}</div>
                    @endif
                    @if($product->description)
                        {!! $product->description !!}
                    @else
                        <p>Chưa có mô tả cho sản phẩm này.</p>
                    @endif
                </div>
            </div>

            <div id="tab-specs" class="tab-content hidden p-6 sm:p-8">
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-sm">
                        <tbody>
                            @forelse($specifications as $key => $value)
                                @php
                                    $specName  = is_array($value) ? ($value['name']  ?? $value[0] ?? $key) : $key;
                                    $specValue = is_array($value) ? ($value['value'] ?? $value[1] ?? '')   : $value;
                                @endphp
                                <tr class="{{ $loop->index % 2 === 0 ? 'bg-slate-50/50' : 'bg-white' }}">
                                    <td class="py-3 px-4 font-semibold text-slate-500 w-2/5 border-b border-slate-100">{{ $specName }}</td>
                                    <td class="py-3 px-4 text-slate-700 border-b border-slate-100">{{ $specValue }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-4 px-4 text-slate-500 text-center">Chưa có thông số kỹ thuật.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="tab-reviews" class="tab-content hidden p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row gap-8 mb-8 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="text-center flex-shrink-0">
                        <div class="text-6xl font-black text-slate-900">0</div>
                        <div class="flex justify-center gap-0.5 mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 star-empty" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-sm text-slate-400 mt-1">0 đánh giá</p>
                    </div>
                    <div class="flex-1 space-y-2">
                        @foreach([5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0] as $stars => $count)
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-slate-500 w-3">{{ $stars }}</span>
                                <svg class="w-3.5 h-3.5 star flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <div class="flex-1 h-2 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-400 rounded-full" style="width: 0%"></div>
                                </div>
                                <span class="text-xs text-slate-400 w-6 text-right">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="p-5 bg-white border border-slate-100 rounded-2xl text-sm text-slate-500">
                        Chưa có đánh giá cho sản phẩm này.
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        <div class="mt-14">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-sky-600">Có thể bạn thích</span>
                    <h2 class="text-xl font-black text-slate-900 mt-1">Sản phẩm liên quan</h2>
                </div>
                <a href="{{ route('client.shop') }}" class="text-sm font-semibold text-sky-600 hover:text-sky-700 flex items-center gap-1 transition-colors">
                    Xem thêm
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @forelse($relatedProducts ?? [] as $related)
                    @php $relatedImage = $related->variants->pluck('image')->filter()->first(); @endphp
                    <a href="{{ route('client.product.show', $related->slug) }}" class="related-card block">
                        <div class="related-img">
                            <img src="{{ $relatedImage ? asset('storage/' . $relatedImage) : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&auto=format&fit=crop&q=60' }}"
                                 alt="{{ $related->name }}">
                        </div>
                        <div class="p-3.5">
                            <span class="text-[10px] font-black tracking-widest uppercase text-slate-400">{{ $related->brand->name ?? '' }}</span>
                            <h4 class="text-sm font-bold text-slate-800 line-clamp-2 leading-snug mt-0.5">{{ $related->name }}</h4>
                            <p class="text-sm font-black text-slate-900 mt-2">{{ number_format($related->min_price, 0, ',', '.') }}đ</p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full p-5 bg-white border border-slate-100 rounded-2xl text-sm text-slate-500">
                        Chưa có sản phẩm liên quan.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const productVariants = @json($variantPayload);
    let currentQuantity = 1;
    let maxAvailableStock = 99;

    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
    }

    function changeQty(amount) {
        currentQuantity += amount;
        if (currentQuantity < 1) currentQuantity = 1;
        if (currentQuantity > maxAvailableStock) {
            currentQuantity = maxAvailableStock;
            alert(`Sản phẩm này chỉ còn tối đa ${maxAvailableStock} sản phẩm trong kho!`);
        }
        document.getElementById('qtyDisplay').textContent = currentQuantity;
    }

    function switchImage(element, imgUrl) {
        document.getElementById('mainImage').src = imgUrl;
        document.querySelectorAll('.thumb-item').forEach(item => item.classList.remove('active'));
        element.classList.add('active');
    }

    function selectColor(element) {
        const card = element.closest('.info-card');
        card.querySelectorAll('.color-dot').forEach(dot => dot.classList.remove('active'));
        element.classList.add('active');

        const label = card.querySelector('.color-label');
        if (label) label.textContent = element.getAttribute('data-name');

        syncVariantDetails();
    }

    function selectVariant(element) {
        const card = element.closest('.info-card');
        card.querySelectorAll('.variant-btn').forEach(btn => btn.classList.remove('active'));
        element.classList.add('active');

        syncVariantDetails();
    }

    function syncVariantDetails() {
        const activeOptionIds = Array.from(document.querySelectorAll('.variant-option.active'))
            .map(el => parseInt(el.getAttribute('data-value-id')));

        const matchVariant = productVariants.find(variant =>
            variant.attribute_value_ids.length === activeOptionIds.length &&
            variant.attribute_value_ids.every(id => activeOptionIds.includes(id))
        );

        const currentPriceEl    = document.getElementById('variantCurrentPrice');
        const originalPriceEl   = document.getElementById('variantOriginalPrice');
        const savingBadgeEl     = document.getElementById('variantSavingBadge');
        const salePercentBadgeEl = document.getElementById('salePercentBadge');
        const skuEl             = document.getElementById('variantSku');
        const stockDisplayEl    = document.getElementById('variantStockDisplay');
        const btnAdd            = document.getElementById('btnAddToCart');
        const btnBuy            = document.getElementById('btnBuyNow');

        if (matchVariant) {
            document.getElementById('selectedVariantId').value = matchVariant.id;
            maxAvailableStock = matchVariant.stock;

            currentPriceEl.textContent = formatVND(matchVariant.final_price);

            if (matchVariant.sale_price && matchVariant.price > matchVariant.sale_price) {
                originalPriceEl.textContent = formatVND(matchVariant.price);
                originalPriceEl.classList.remove('hidden');

                savingBadgeEl.textContent = 'Tiết kiệm ' + formatVND(matchVariant.price - matchVariant.sale_price);
                savingBadgeEl.classList.remove('hidden');

                const pct = Math.round((1 - matchVariant.sale_price / matchVariant.price) * 100);
                if (salePercentBadgeEl) {
                    salePercentBadgeEl.textContent = '-' + pct + '%';
                    salePercentBadgeEl.classList.remove('hidden');
                }
            } else {
                originalPriceEl.classList.add('hidden');
                savingBadgeEl.classList.add('hidden');
                if (salePercentBadgeEl) salePercentBadgeEl.classList.add('hidden');
            }

            if (skuEl) skuEl.textContent = 'SKU: ' + matchVariant.sku;
            if (matchVariant.image_url) document.getElementById('mainImage').src = matchVariant.image_url;

            if (matchVariant.stock > 0) {
                stockDisplayEl.textContent = `Kho: Còn ${matchVariant.stock} sản phẩm`;
                stockDisplayEl.className = 'text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg ml-2';

                btnAdd.disabled = false; btnAdd.style.opacity = '1'; btnAdd.querySelector('.btn-text').textContent = 'Thêm vào giỏ';
                btnBuy.disabled = false; btnBuy.style.opacity = '1'; btnBuy.querySelector('.btn-text').textContent = 'Mua ngay';
            } else {
                stockDisplayEl.textContent = 'Hết hàng';
                stockDisplayEl.className = 'text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg ml-2';
                disablePurchaseButtons('Hết hàng');
            }

            if (currentQuantity > maxAvailableStock) {
                currentQuantity = maxAvailableStock > 0 ? maxAvailableStock : 1;
                document.getElementById('qtyDisplay').textContent = currentQuantity;
            }

        } else {
            document.getElementById('selectedVariantId').value = '';
            maxAvailableStock = 0;
            currentQuantity = 1;
            document.getElementById('qtyDisplay').textContent = '1';

            currentPriceEl.textContent = 'Không sẵn có';
            originalPriceEl.classList.add('hidden');
            savingBadgeEl.classList.add('hidden');
            if (salePercentBadgeEl) salePercentBadgeEl.classList.add('hidden');
            if (skuEl) skuEl.textContent = 'SKU: --';

            stockDisplayEl.textContent = 'Không có sẵn combo này';
            stockDisplayEl.className = 'text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg ml-2';

            disablePurchaseButtons('Không sẵn có');
        }
    }

    function disablePurchaseButtons(text) {
        const btnAdd = document.getElementById('btnAddToCart');
        const btnBuy = document.getElementById('btnBuyNow');
        btnAdd.disabled = true; btnAdd.style.opacity = '0.5'; btnAdd.querySelector('.btn-text').textContent = text;
        btnBuy.disabled = true; btnBuy.style.opacity = '0.5'; btnBuy.querySelector('.btn-text').textContent = text;
    }

    function handleCartAction(actionType) {
        const variantId = document.getElementById('selectedVariantId').value;
        if (!variantId) {
            alert('Vui lòng chọn đầy đủ các tùy chọn phiên bản hợp lệ trước!');
            return;
        }

        fetch("{{ route('client.cart.add') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ variant_id: variantId, quantity: currentQuantity })
        })
        .then(response => {
            if (!response.ok) return response.json().then(err => { throw err; });
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (actionType === 'add') {
                    alert(data.message || 'Đã thêm sản phẩm vào giỏ thành công!');
                    const cartBadge = document.getElementById('cart-count-badge');
                    if (cartBadge && data.cart_count !== undefined) cartBadge.textContent = data.cart_count;
                } else if (actionType === 'buy') {
                    window.location.href = '{{ route('client.orders.checkout') }}';
                }
            }
        })
        .catch(error => {
            alert(error.message || 'Lỗi thêm sản phẩm, vui lòng tải lại trang.');
        });
    }

    function switchTab(element, tabId) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
        element.classList.add('active');
        document.getElementById(tabId).classList.remove('hidden');
    }

    window.addEventListener('DOMContentLoaded', () => {
        syncVariantDetails();
    });
</script>
@endpush
