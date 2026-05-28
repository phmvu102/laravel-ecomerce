@extends('layouts.app')

@section('title', 'Cửa Hàng - ShopNova')

@push('styles')
<style>
    .shop-bg {
        background: #f8fafc;
        background-image:
            radial-gradient(circle at 15% 20%, rgba(14, 165, 233, 0.06) 0%, transparent 40%),
            radial-gradient(circle at 85% 70%, rgba(99, 102, 241, 0.05) 0%, transparent 40%);
    }

    .sidebar-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04), 0 1px 2px rgba(15, 23, 42, 0.03);
        border-radius: 1.25rem;
    }

    .filter-btn {
        width: 100%;
        text-align: left;
        font-size: 0.8125rem;
        padding: 0.5rem 0.875rem;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        color: #64748b;
        background: #f8fafc;
        transition: all 0.2s ease;
    }
    .filter-btn:hover, .filter-btn.active {
        border-color: #38bdf8;
        color: #0369a1;
        background: #f0f9ff;
    }

    /* Product card */
    .product-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .product-card:hover {
        border-color: #bae6fd;
        box-shadow: 0 20px 25px -5px rgba(14, 165, 233, 0.08), 0 8px 10px -6px rgba(14, 165, 233, 0.05);
        transform: translateY(-3px);
    }

    .product-img-wrap {
        background: linear-gradient(135deg, #f1f5f9 0%, #f8fafc 100%);
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .product-img-wrap img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        filter: drop-shadow(0 8px 16px rgba(15, 23, 42, 0.12));
    }
    .product-card:hover .product-img-wrap img {
        transform: scale(1.07);
    }

    .quick-view-overlay {
        position: absolute;
        inset: 0;
        background: rgba(248, 250, 252, 0.7);
        backdrop-filter: blur(4px);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .product-card:hover .quick-view-overlay {
        opacity: 1;
    }

    .badge-new {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        padding: 0.2rem 0.55rem;
        border-radius: 0.4rem;
    }
    .badge-sale {
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #fecdd3;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        padding: 0.2rem 0.55rem;
        border-radius: 0.4rem;
    }

    .add-cart-btn {
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 0.65rem;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .add-cart-btn:hover {
        background: #0ea5e9;
        border-color: #0ea5e9;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.35);
    }

    /* Pagination */
    .page-btn {
        width: 2.25rem;
        height: 2.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.65rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        transition: all 0.2s ease;
    }
    .page-btn:hover { background: #f1f5f9; color: #0f172a; }
    .page-btn.active { background: #0ea5e9; color: #fff; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.35); }

    /* Checkbox custom */
    input[type="checkbox"] {
        accent-color: #0ea5e9;
    }

    /* Sort select */
    .sort-select {
        font-size: 0.8125rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        color: #374151;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s;
    }
    .sort-select:focus { border-color: #38bdf8; }

    /* Category active link */
    .cat-link { transition: all 0.15s; }
    .cat-link:hover, .cat-link.active { color: #0369a1; }
</style>
@endpush

@section('content')
<div class="shop-bg min-h-screen pt-6 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb & Title --}}
        <div class="mb-8">
            <nav class="flex items-center space-x-2 text-xs text-slate-400 uppercase tracking-wider mb-3">
                <a href="{{ route('home') }}" class="hover:text-sky-500 transition-colors">Trang chủ</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-slate-600 font-semibold">Sản phẩm</span>
            </nav>
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    <span class="w-1.5 h-7 bg-sky-500 rounded-full"></span>
                    Khám Phá Sản Phẩm
                </h1>
                <span class="text-sm text-slate-400 hidden sm:block">
                    Tìm thấy <span class="font-bold text-slate-700">{{ $products->total() ?? 48 }}</span> sản phẩm
                </span>
            </div>
        </div>

        {{-- Main Layout --}}
        <div class="lg:flex lg:gap-7">

            {{-- ===== SIDEBAR ===== --}}
            <aside class="w-full lg:w-60 flex-shrink-0 mb-7 lg:mb-0">
                <div class="sidebar-card p-5 sticky top-24 space-y-6">

                    {{-- Danh mục --}}
                    <div>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            Danh mục
                        </h3>
                        <ul class="space-y-0.5">
                            <li>
                                <a href="{{ route('client.shop') }}"
                                   class="cat-link flex items-center justify-between px-3 py-2 rounded-xl text-sm font-semibold {{ !request('category') ? 'bg-sky-50 text-sky-600' : 'text-slate-500 hover:bg-slate-50' }}">
                                    <span>Tất cả sản phẩm</span>
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ !request('category') ? 'bg-sky-100 text-sky-600' : 'bg-slate-100 text-slate-400' }}">
                                        {{ $products->total() ?? '—' }}
                                    </span>
                                </a>
                            </li>
                            @forelse($categories ?? [] as $cat)
                            <li>
                                <a href="{{ route('client.shop', $cat->slug) }}"
                                   class="cat-link flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium {{ request()->route('category_slug') == $cat->slug ? 'bg-sky-50 text-sky-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                                    <span>{{ $cat->name }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-400">{{ $cat->products_count ?? 0 }}</span>
                                </a>
                            </li>
                            @empty
                            @foreach(['Smartphone', 'Laptop & MacBook', 'Tai nghe & Âm thanh', 'Phụ kiện cao cấp'] as $cat)
                            <li>
                                <a href="#" class="cat-link flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-700">
                                    <span>{{ $cat }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-400">24</span>
                                </a>
                            </li>
                            @endforeach
                            @endforelse
                        </ul>
                    </div>

                    <hr class="border-slate-100">

                    {{-- Thương hiệu --}}
                    <div>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            Thương hiệu
                        </h3>
                        <div class="space-y-2">
                            @forelse($brands ?? [] as $brand)
                            <label class="flex items-center gap-2.5 px-1 py-1 rounded-lg cursor-pointer group hover:bg-slate-50 transition-colors">
                                <input type="checkbox" name="brand[]" value="{{ $brand->id }}"
                                    {{ in_array($brand->id, (array)request('brand')) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded">
                                <span class="text-sm font-medium text-slate-600 group-hover:text-slate-900 transition-colors">{{ $brand->name }}</span>
                            </label>
                            @empty
                            @foreach(['Apple', 'Samsung', 'Sony', 'Marshall'] as $brand)
                            <label class="flex items-center gap-2.5 px-1 py-1 rounded-lg cursor-pointer group hover:bg-slate-50 transition-colors">
                                <input type="checkbox" class="w-4 h-4 rounded">
                                <span class="text-sm font-medium text-slate-600 group-hover:text-slate-900 transition-colors">{{ $brand }}</span>
                            </label>
                            @endforeach
                            @endforelse
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- Khoảng giá --}}
                    <div>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Khoảng giá
                        </h3>
                        <div class="space-y-1.5">
                            @foreach([
                                ['label' => 'Dưới 5 triệu', 'max' => 5000000],
                                ['label' => '5 triệu – 15 triệu', 'min' => 5000000, 'max' => 15000000],
                                ['label' => 'Trên 15 triệu', 'min' => 15000000],
                            ] as $range)
                            <button class="filter-btn {{ request('max_price') == ($range['max'] ?? '') && request('min_price') == ($range['min'] ?? '') ? 'active' : '' }}">
                                {{ $range['label'] }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                </div>
            </aside>

            {{-- ===== PRODUCT AREA ===== --}}
            <main class="flex-1 min-w-0">

                {{-- Top bar --}}
                <div class="bg-white border border-slate-200 rounded-2xl px-5 py-3.5 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                    <p class="text-sm text-slate-500">
                        Hiển thị
                        <span class="font-bold text-slate-800">{{ $products->firstItem() ?? 1 }} – {{ $products->lastItem() ?? 12 }}</span>
                        trong
                        <span class="font-bold text-slate-800">{{ $products->total() ?? 48 }}</span>
                        sản phẩm
                    </p>
                    <div class="flex items-center gap-2.5">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sắp xếp:</span>
                        <select class="sort-select" name="sort" onchange="this.form && this.form.submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                            <option value="best_selling" {{ request('sort') == 'best_selling' ? 'selected' : '' }}>Bán chạy</option>
                        </select>
                    </div>
                </div>

                {{-- Product Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    @forelse($products ?? [] as $product)
                    <div class="product-card">

                        {{-- Badge --}}
                        <div class="absolute top-3 left-3 z-10">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="badge-sale">-{{ round((1 - $product->sale_price / $product->price) * 100) }}%</span>
                            @else
                                <span class="badge-new">Mới</span>
                            @endif
                        </div>

                        {{-- Image --}}
                        <div class="product-img-wrap">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop&q=60" alt="{{ $product->name }}">
                            @endif
                            <div class="quick-view-overlay">
                                <a href="{{ route('client.product.show', $product->slug) }}"
                                   class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg text-slate-700 hover:bg-sky-500 hover:text-white transition-colors border border-slate-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="p-4">
                            <span class="text-[10px] font-black tracking-widest uppercase text-slate-400">{{ $product->brand->name ?? 'ShopNova' }}</span>
                            <h3 class="text-sm font-bold text-slate-800 mt-0.5 line-clamp-2 leading-snug hover:text-sky-600 transition-colors">
                                <a href="{{ route('client.product.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>

                            <div class="mt-3 flex items-center justify-between">
                                <div>
                                    <span class="text-base font-black text-slate-900">
                                        {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}đ
                                    </span>
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="text-xs text-slate-400 line-through block">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                    @endif
                                </div>
                                <form action="{{ route('client.cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="add-cart-btn" title="Thêm vào giỏ">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                    @empty
                    {{-- Placeholder skeleton khi chưa có sản phẩm --}}
                    @for($i = 0; $i < 6; $i++)
                    <div class="product-card animate-pulse">
                        <div class="product-img-wrap bg-slate-100">
                            <div class="w-full h-full bg-slate-200 rounded-xl"></div>
                        </div>
                        <div class="p-4 space-y-2">
                            <div class="h-3 bg-slate-100 rounded w-1/3"></div>
                            <div class="h-4 bg-slate-100 rounded w-4/5"></div>
                            <div class="h-4 bg-slate-100 rounded w-2/3"></div>
                            <div class="flex justify-between items-center pt-1">
                                <div class="h-5 bg-slate-100 rounded w-1/3"></div>
                                <div class="w-9 h-9 bg-slate-100 rounded-xl"></div>
                            </div>
                        </div>
                    </div>
                    @endfor
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if(isset($products) && $products->hasPages())
                <div class="mt-10 flex justify-center">
                    <div class="inline-flex items-center gap-1 p-1.5 bg-white border border-slate-200 rounded-2xl shadow-sm">
                        {{-- Prev --}}
                        @if($products->onFirstPage())
                            <span class="page-btn opacity-30 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="page-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        @endif

                        {{-- Pages --}}
                        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if($page == $products->currentPage())
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="page-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @else
                            <span class="page-btn opacity-30 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        @endif
                    </div>
                </div>
                @else
                {{-- Static pagination fallback --}}
                <div class="mt-10 flex justify-center">
                    <div class="inline-flex items-center gap-1 p-1.5 bg-white border border-slate-200 rounded-2xl shadow-sm">
                        <span class="page-btn opacity-30"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></span>
                        <span class="page-btn active">1</span>
                        <a href="#" class="page-btn">2</a>
                        <a href="#" class="page-btn">3</a>
                        <a href="#" class="page-btn"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>
                @endif

            </main>
        </div>
    </div>
</div>
@endsection