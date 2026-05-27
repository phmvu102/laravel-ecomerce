@extends('client.layouts.app')

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

    /* Thumbnail gallery */
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

    /* Main image */
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

    /* Badges */
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

    /* Variant buttons */
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

    /* Color dots */
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

    /* Qty control */
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

    /* CTA buttons */
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

    /* Info card */
    .info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 1.25rem 1.5rem;
    }

    /* Rating stars */
    .star { color: #f59e0b; }
    .star-empty { color: #e2e8f0; }

    /* Tab buttons */
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

    /* Related product card */
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

    /* Trust badges */
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
    // Demo data — thay bằng $product thật từ controller
    $demoProduct = [
        'name' => 'iPhone 15 Pro Max Titanium Edition',
        'brand' => 'Apple',
        'sku' => 'APL-IP15PM-256',
        'price' => 34990000,
        'sale_price' => 28490000,
        'rating' => 4.8,
        'review_count' => 142,
        'sold' => 1287,
        'stock' => 12,
        'description' => 'iPhone 15 Pro Max được trang bị chip A17 Pro mạnh mẽ nhất từ trước đến nay, thiết kế titan siêu nhẹ bền bỉ, hệ thống camera Pro nâng tầm nhiếp ảnh di động với khả năng quay video ProRes 4K cùng cổng USB-C tốc độ cao.',
    ];
@endphp

<div class="detail-bg pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        {{-- Breadcrumb --}}
        <nav class="flex items-center flex-wrap gap-1.5 text-xs text-slate-400 uppercase tracking-wider mb-8">
            <a href="{{ route('home') }}" class="hover:text-sky-500 transition-colors">Trang chủ</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('client.shop') }}" class="hover:text-sky-500 transition-colors">Sản phẩm</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-600 font-semibold line-clamp-1">{{ $product->name ?? $demoProduct['name'] }}</span>
        </nav>

        {{-- ===== PRODUCT MAIN SECTION ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 xl:gap-14">

            {{-- LEFT: Gallery --}}
            <div class="flex gap-4">
                {{-- Thumbnail list --}}
                <div class="thumb-list hidden sm:flex">
                    @php $images = $product->images ?? []; @endphp
                    @forelse($images as $i => $img)
                        <div class="thumb-item {{ $i === 0 ? 'active' : '' }}" onclick="switchImage(this, '{{ asset('storage/'.$img) }}')">
                            <img src="{{ asset('storage/'.$img) }}" alt="thumb">
                        </div>
                    @empty
                        @foreach([
                            'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1565849904461-04a58ad377e0?w=400&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=400&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=400&auto=format&fit=crop&q=80',
                        ] as $i => $url)
                        <div class="thumb-item {{ $i === 0 ? 'active' : '' }}" onclick="switchImage(this, '{{ $url }}')">
                            <img src="{{ $url }}" alt="thumb {{ $i+1 }}">
                        </div>
                        @endforeach
                    @endforelse
                </div>

                {{-- Main image --}}
                <div class="flex-1">
                    <div class="main-img-wrap">
                        {{-- Badge --}}
                        <div class="absolute top-4 left-4 flex flex-col gap-1.5 z-10">
                            <span class="badge-sale">-18%</span>
                            <span class="badge-hot">🔥 Hot</span>
                        </div>
                        {{-- Wishlist --}}
                        <button class="absolute top-4 right-4 z-10 w-10 h-10 bg-white rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-200 transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>

                        <img id="mainImage"
                             src="{{ $product->image ? asset('storage/'.$product->image) : 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80' }}"
                             alt="{{ $product->name ?? $demoProduct['name'] }}">
                    </div>

                    {{-- Mobile thumbs --}}
                    <div class="flex gap-2.5 mt-3 sm:hidden overflow-x-auto pb-1">
                        @foreach([
                            'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1565849904461-04a58ad377e0?w=400&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=400&auto=format&fit=crop&q=80',
                        ] as $i => $url)
                        <div class="thumb-item flex-shrink-0 {{ $i === 0 ? 'active' : '' }}" onclick="switchImage(this, '{{ $url }}')">
                            <img src="{{ $url }}" alt="">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT: Info --}}
            <div class="flex flex-col gap-5">

                {{-- Brand + Name --}}
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-black tracking-widest uppercase text-sky-600 bg-sky-50 border border-sky-100 px-2.5 py-1 rounded-lg">
                            {{ $product->brand->name ?? $demoProduct['brand'] }}
                        </span>
                        <span class="text-xs text-slate-400 font-mono">SKU: {{ $product->sku ?? $demoProduct['sku'] }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight tracking-tight">
                        {{ $product->name ?? $demoProduct['name'] }}
                    </h1>
                </div>

                {{-- Rating + Stats --}}
                <div class="flex items-center flex-wrap gap-4">
                    <div class="flex items-center gap-1.5">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($product->rating ?? $demoProduct['rating']) ? 'star' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm font-bold text-slate-700">{{ $product->rating ?? $demoProduct['rating'] }}</span>
                        <span class="text-sm text-slate-400">({{ $product->review_count ?? $demoProduct['review_count'] }} đánh giá)</span>
                    </div>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <span class="text-sm text-slate-500">Đã bán <span class="font-bold text-slate-700">{{ number_format($product->sold ?? $demoProduct['sold']) }}</span></span>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <span class="text-sm text-emerald-600 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Còn {{ $product->stock ?? $demoProduct['stock'] }} sản phẩm
                    </span>
                </div>

                {{-- Price --}}
                <div class="flex items-end gap-3">
                    <span class="text-3xl font-black text-slate-900">
                        {{ number_format($product->sale_price ?? $demoProduct['sale_price'], 0, ',', '.') }}đ
                    </span>
                    <span class="text-lg text-slate-400 line-through font-medium mb-0.5">
                        {{ number_format($product->price ?? $demoProduct['price'], 0, ',', '.') }}đ
                    </span>
                    <span class="badge-sale mb-1">Tiết kiệm {{ number_format(($demoProduct['price'] - $demoProduct['sale_price']), 0, ',', '.') }}đ</span>
                </div>

                {{-- Variants: Color --}}
                <div class="info-card">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Màu sắc</p>
                    <div class="flex items-center gap-3 flex-wrap">
                        @foreach([
                            ['name' => 'Titan Đen', 'color' => '#1c1917'],
                            ['name' => 'Titan Trắng', 'color' => '#f5f0eb'],
                            ['name' => 'Titan Xanh', 'color' => '#2d6a9f'],
                            ['name' => 'Titan Vàng', 'color' => '#d4a853'],
                        ] as $i => $color)
                        <button class="color-dot {{ $i === 0 ? 'active' : '' }}"
                                style="background-color: {{ $color['color'] }}; {{ $color['color'] === '#f5f0eb' ? 'border: 2px solid #e2e8f0;' : '' }}"
                                title="{{ $color['name'] }}"
                                onclick="selectColor(this)">
                        </button>
                        @endforeach
                    </div>
                    <p id="colorLabel" class="text-xs text-slate-500 mt-2 font-medium">Titan Đen</p>
                </div>

                {{-- Variants: Storage --}}
                <div class="info-card">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Dung lượng</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['256GB', '512GB', '1TB'] as $i => $storage)
                        <button class="variant-btn {{ $i === 0 ? 'active' : '' }}" onclick="selectVariant(this)">
                            {{ $storage }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Qty + Add to cart --}}
                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Số lượng:</p>
                        <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl p-1">
                            <button class="qty-btn" onclick="changeQty(-1)">−</button>
                            <span id="qtyDisplay" class="w-10 text-center text-sm font-bold text-slate-800">1</span>
                            <button class="qty-btn" onclick="changeQty(1)">+</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-1">
                        <button class="btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Thêm vào giỏ
                        </button>
                        <button class="btn-secondary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Mua ngay
                        </button>
                    </div>
                </div>

                {{-- Trust badges --}}
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

        {{-- ===== TABS: Description / Specs / Reviews ===== --}}
        <div class="mt-14 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            {{-- Tab header --}}
            <div class="flex border-b border-slate-100 overflow-x-auto">
                <button class="tab-btn active" onclick="switchTab(this, 'tab-desc')">Mô tả sản phẩm</button>
                <button class="tab-btn" onclick="switchTab(this, 'tab-specs')">Thông số kỹ thuật</button>
                <button class="tab-btn" onclick="switchTab(this, 'tab-reviews')">
                    Đánh giá
                    <span class="ml-1.5 text-xs bg-sky-100 text-sky-600 font-bold px-1.5 py-0.5 rounded-full">142</span>
                </button>
            </div>

            {{-- Tab: Description --}}
            <div id="tab-desc" class="tab-content p-6 sm:p-8">
                <div class="prose prose-slate max-w-none text-sm sm:text-base leading-relaxed text-slate-600">
                    <p class="text-base font-semibold text-slate-800 mb-4">{{ $product->description ?? $demoProduct['description'] }}</p>
                    <p>iPhone 15 Pro Max là đỉnh cao công nghệ của Apple năm 2024. Với thiết kế khung titan Grade 5 lần đầu xuất hiện trên iPhone, máy vừa nhẹ hơn thế hệ trước lại bền bỉ vượt trội. Chip A17 Pro được sản xuất trên tiến trình 3nm thế hệ 2 mang đến hiệu năng CPU tăng 10% và GPU tăng 20% so với A16 Bionic.</p>
                    <ul class="mt-4 space-y-2 list-none pl-0">
                        @foreach([
                            'Chip A17 Pro — GPU 6 nhân mạnh nhất lịch sử iPhone',
                            'Màn hình Super Retina XDR 6.7" ProMotion 120Hz — sáng tới 2000 nit',
                            'Hệ thống camera Pro: Telephoto 5x, Ultra Wide 48MP, Chính 48MP',
                            'Quay video ProRes 4K/60fps lưu thẳng lên iCloud',
                            'USB-C với tốc độ USB 3 lên đến 20Gb/s',
                            'Pin 4422mAh — sạc MagSafe 15W, sạc nhanh 27W',
                        ] as $feature)
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-sky-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-slate-600">{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Tab: Specs --}}
            <div id="tab-specs" class="tab-content hidden p-6 sm:p-8">
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-sm">
                        <tbody>
                            @foreach([
                                ['Màn hình', 'Super Retina XDR OLED 6.7", 2796 x 1290 px, 460 ppi'],
                                ['Chip', 'Apple A17 Pro (3nm thế hệ 2)'],
                                ['RAM', '8GB LPDDR5'],
                                ['Bộ nhớ trong', '256GB / 512GB / 1TB NVMe'],
                                ['Camera sau', '48MP (main) + 12MP (ultra wide) + 12MP (5x telephoto)'],
                                ['Camera trước', '12MP TrueDepth, Face ID'],
                                ['Pin', '4422mAh — sạc nhanh 27W, MagSafe 15W'],
                                ['Hệ điều hành', 'iOS 17 (nâng cấp lên iOS 18)'],
                                ['Kết nối', 'USB-C USB 3, Wi-Fi 6E, Bluetooth 5.3, NFC, 5G'],
                                ['Kháng nước', 'IP68 — 6 mét trong 30 phút'],
                                ['Khung máy', 'Titan Grade 5'],
                                ['Kích thước', '159.9 × 76.7 × 8.25 mm — 221g'],
                            ] as $i => $spec)
                            <tr class="{{ $i % 2 === 0 ? 'bg-slate-50/50' : 'bg-white' }}">
                                <td class="py-3 px-4 font-semibold text-slate-500 w-2/5 border-b border-slate-100">{{ $spec[0] }}</td>
                                <td class="py-3 px-4 text-slate-700 border-b border-slate-100">{{ $spec[1] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tab: Reviews --}}
            <div id="tab-reviews" class="tab-content hidden p-6 sm:p-8">
                {{-- Summary --}}
                <div class="flex flex-col sm:flex-row gap-8 mb-8 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="text-center flex-shrink-0">
                        <div class="text-6xl font-black text-slate-900">4.8</div>
                        <div class="flex justify-center gap-0.5 mt-2">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= 5 ? 'star' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-sm text-slate-400 mt-1">142 đánh giá</p>
                    </div>
                    <div class="flex-1 space-y-2">
                        @foreach([5 => 78, 4 => 42, 3 => 14, 2 => 5, 1 => 3] as $stars => $count)
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-500 w-3">{{ $stars }}</span>
                            <svg class="w-3.5 h-3.5 star flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <div class="flex-1 h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: {{ round($count/142*100) }}%"></div>
                            </div>
                            <span class="text-xs text-slate-400 w-6 text-right">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Review list --}}
                <div class="space-y-5">
                    @foreach([
                        ['name' => 'Nguyễn Minh Tuấn', 'stars' => 5, 'date' => '20/05/2026', 'text' => 'Sản phẩm tuyệt vời, đóng gói cẩn thận, giao hàng siêu nhanh. Camera chụp đẹp hơn hẳn điện thoại cũ, đặc biệt ảnh chụp ban đêm rất nét. Rất hài lòng với lần mua này!', 'verified' => true],
                        ['name' => 'Trần Thị Lan Anh', 'stars' => 5, 'date' => '18/05/2026', 'text' => 'Mua lần 2 rồi vẫn rất hài lòng. Shop tư vấn nhiệt tình, máy chính hãng có seal, hiệu năng mượt mà không chê vào đâu được.', 'verified' => true],
                        ['name' => 'Phạm Hoàng Nam', 'stars' => 4, 'date' => '15/05/2026', 'text' => 'Máy đẹp, pin trâu hơn mong đợi. Trừ 1 sao vì thời gian giao hơi lâu nhưng nhìn chung vẫn ổn.', 'verified' => false],
                    ] as $review)
                    <div class="p-5 bg-white border border-slate-100 rounded-2xl">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-sky-100 flex items-center justify-center text-sky-600 font-black text-sm flex-shrink-0">
                                    {{ mb_substr($review['name'], 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                        {{ $review['name'] }}
                                        @if($review['verified'])
                                        <span class="text-[10px] text-emerald-600 bg-emerald-50 border border-emerald-100 px-1.5 py-0.5 rounded font-bold">✓ Đã mua</span>
                                        @endif
                                    </p>
                                    <div class="flex gap-0.5 mt-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review['stars'] ? 'star' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <span class="text-xs text-slate-400 flex-shrink-0">{{ $review['date'] }}</span>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $review['text'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ===== RELATED PRODUCTS ===== --}}
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
                <a href="{{ route('client.product.show', $related->slug) }}" class="related-card block">
                    <div class="related-img">
                        <img src="{{ $related->image ? asset('storage/'.$related->image) : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&auto=format&fit=crop&q=60' }}"
                             alt="{{ $related->name }}">
                    </div>
                    <div class="p-3.5">
                        <span class="text-[10px] font-black tracking-widest uppercase text-slate-400">{{ $related->brand->name ?? '' }}</span>
                        <h4 class="text-sm font-bold text-slate-800 line-clamp-2 leading-snug mt-0.5">{{ $related->name }}</h4>
                        <p class="text-sm font-black text-slate-900 mt-2">{{ number_format($related->sale_price ?? $related->price, 0, ',', '.') }}đ</p>
                    </div>
                </a>
                @empty
                @foreach([
                    ['name' => 'iPhone 15 Pro 256GB', 'price' => '26,490,000đ', 'img' => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=400&auto=format&fit=crop&q=60'],
                    ['name' => 'AirPods Pro 2nd Gen', 'price' => '5,990,000đ', 'img' => 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=400&auto=format&fit=crop&q=60'],
                    ['name' => 'Apple Watch Ultra 2', 'price' => '19,990,000đ', 'img' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=400&auto=format&fit=crop&q=60'],
                    ['name' => 'MacBook Air M3', 'price' => '29,990,000đ', 'img' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&auto=format&fit=crop&q=60'],
                    ['name' => 'iPad Pro M4 11"', 'price' => '23,990,000đ', 'img' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=400&auto=format&fit=crop&q=60'],
                ] as $demo)
                <a href="{{ route('client.shop') }}" class="related-card block">
                    <div class="related-img">
                        <img src="{{ $demo['img'] }}" alt="{{ $demo['name'] }}">
                    </div>
                    <div class="p-3.5">
                        <span class="text-[10px] font-black tracking-widest uppercase text-slate-400">Apple</span>
                        <h4 class="text-sm font-bold text-slate-800 line-clamp-2 leading-snug mt-0.5">{{ $demo['name'] }}</h4>
                        <p class="text-sm font-black text-slate-900 mt-2">{{ $demo['price'] }}</p>
                    </div>
                </a>
                @endforeach
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
// Switch main image
function switchImage(thumb, src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}

// Qty
let qty = 1;
function changeQty(delta) {
    qty = Math.max(1, qty + delta);
    document.getElementById('qtyDisplay').textContent = qty;
}

// Variant select
function selectVariant(btn) {
    btn.closest('.info-card').querySelectorAll('.variant-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

// Color select
const colorNames = ['Titan Đen', 'Titan Trắng', 'Titan Xanh', 'Titan Vàng'];
function selectColor(dot) {
    const dots = document.querySelectorAll('.color-dot');
    dots.forEach((d, i) => {
        d.classList.remove('active');
    });
    dot.classList.add('active');
    const idx = Array.from(dots).indexOf(dot);
    document.getElementById('colorLabel').textContent = colorNames[idx] ?? '';
}

// Tabs
function switchTab(btn, targetId) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
    btn.classList.add('active');
    document.getElementById(targetId).classList.remove('hidden');
}
</script>
@endpush