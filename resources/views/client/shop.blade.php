@extends('layouts.app')

@section('title', 'Cửa Hàng - ShopNova')

@push('styles')
<style>
    .shop-bg {
        background: #f8fafc;
        background-image:
            radial-gradient(circle at 0% 0%, rgba(14, 165, 233, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 100% 100%, rgba(99, 102, 241, 0.04) 0%, transparent 50%);
    }

    .sidebar-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.7);
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.02), 0 2px 8px -1px rgba(15, 23, 42, 0.02);
        border-radius: 1.5rem;
    }

    .filter-btn {
        width: 100%;
        text-align: left;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.625rem 1rem;
        border-radius: 0.875rem;
        border: 1px solid #f1f5f9;
        color: #64748b;
        background: #f8fafc;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .filter-btn:hover {
        border-color: #e2e8f0;
        color: #0f172a;
        background: #f1f5f9;
    }
    .filter-btn.active {
        border-color: #38bdf8;
        color: #0284c7;
        background: #f0f9ff;
        font-weight: 600;
        box-shadow: inset 0 1px 2px rgba(14, 165, 233, 0.05);
    }

    .product-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 1.5rem;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .product-card:hover {
        border-color: rgba(14, 165, 233, 0.25);
        box-shadow:
            0 20px 25px -5px rgba(14, 165, 233, 0.05),
            0 10px 10px -5px rgba(14, 165, 233, 0.02),
            0 0 0 1px rgba(14, 165, 233, 0.05);
        transform: translateY(-5px);
    }

    .product-img-wrap {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.75rem;
        position: relative;
        overflow: hidden;
    }
    .product-img-wrap img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        filter: drop-shadow(0 10px 20px rgba(15, 23, 42, 0.08));
    }
    .product-card:hover .product-img-wrap img {
        transform: scale(1.06) translateY(-2px);
    }

    .quick-view-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        opacity: 0;
        transition: all 0.35s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-card:hover .quick-view-overlay {
        opacity: 1;
    }

    .badge-premium {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }
    .badge-new {
        background: #f0f9ff;
        color: #0284c7;
        border: 1px solid #e0f2fe;
    }
    .badge-sale {
        background: #fff1f2;
        color: #f43f5e;
        border: 1px solid #ffe4e6;
    }

    .add-cart-btn {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
    }
    .add-cart-btn:hover {
        background: #0ea5e9;
        border-color: #0ea5e9;
        color: #ffffff;
        transform: scale(1.05);
        box-shadow: 0 8px 20px -4px rgba(14, 165, 233, 0.45);
    }

    .page-btn {
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        transition: all 0.2s ease;
    }
    .page-btn:hover:not(.active) { background: #f1f5f9; color: #0f172a; }
    .page-btn.active { background: #0ea5e9; color: #fff; box-shadow: 0 6px 20px -4px rgba(14, 165, 233, 0.4); }

    .sort-select {
        font-size: 0.85rem;
        font-weight: 500;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 0.625rem 2.5rem 0.625rem 1rem;
        color: #334155;
        outline: none;
        cursor: pointer;
        transition: all 0.2s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 0.75rem;
    }
    .sort-select:focus { border-color: #0ea5e9; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1); }

    .cat-link { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
</style>
@endpush

@section('content')
<div class="shop-bg min-h-screen pt-10 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb & Title --}}
        <div class="mb-10">
            <nav class="flex items-center space-x-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-4">
                <a href="{{ route('home') }}" class="hover:text-sky-500 transition-colors">Trang chủ</a>
                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                <span class="text-slate-600">Sản phẩm</span>
            </nav>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3.5">
                    <span class="w-2 h-8 bg-gradient-to-b from-sky-400 to-sky-500 rounded-full shadow-sm shadow-sky-500/20"></span>
                    Khám Phá Sản Phẩm
                </h1>
                <span class="text-sm font-medium text-slate-400 bg-white border border-slate-100 px-4 py-2 rounded-2xl shadow-sm">
                    Tìm thấy <span class="font-bold text-slate-700">{{ $products->total() ?? 0 }}</span> sản phẩm tương thích
                </span>
            </div>
        </div>

        <form action="{{ URL::current() }}" method="GET" id="filter-form">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <input type="hidden" name="min_price" id="min_price_input" value="{{ request('min_price') }}">
            <input type="hidden" name="max_price" id="max_price_input" value="{{ request('max_price') }}">

            <div class="lg:flex lg:gap-8">

                {{-- Sidebar --}}
                <aside class="w-full lg:w-64 flex-shrink-0 mb-8 lg:mb-0">
                    <div class="sidebar-card p-6 sticky top-24 space-y-7">

                        {{-- Danh mục --}}
                        <div>
                            <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                Danh mục
                            </h3>
                            <ul class="space-y-0.5">
                                <li>
                                    <a href="{{ route('client.shop') }}"
                                       class="cat-link flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold
                                              {{ !request()->route('category_slug') ? 'bg-sky-50/70 text-sky-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                                        <span>Tất cả sản phẩm</span>
                                        <span class="text-xs px-2.5 py-0.5 rounded-full font-bold
                                                     {{ !request()->route('category_slug') ? 'bg-sky-100 text-sky-600' : 'bg-slate-100 text-slate-400' }}">
                                            {{ $products->total() }}
                                        </span>
                                    </a>
                                </li>

                                @foreach($categories as $cat)
                                    @php
                                        $isParentActive = request()->route('category_slug') == $cat->slug;
                                        $isChildActive  = $cat->children->contains('slug', request()->route('category_slug'));
                                        $isOpen         = $isParentActive || $isChildActive;
                                    @endphp
                                    <li x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }">

                                        <div class="flex items-center gap-1">
                                            <a href="{{ route('client.shop', $cat->slug) }}"
                                               class="cat-link flex-1 flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm
                                                      {{ $isParentActive ? 'font-bold bg-sky-50/70 text-sky-600' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                                                <span>{{ $cat->name }}</span>
                                                <span class="text-xs px-2 py-0.5 rounded-full font-bold
                                                             {{ $isParentActive ? 'bg-sky-100 text-sky-600' : 'bg-slate-100 text-slate-400' }}">
                                                    {{ $cat->products_count }}
                                                </span>
                                            </a>

                                            @if($cat->children->isNotEmpty())
                                            <button @click="open = !open"
                                                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all flex-shrink-0">
                                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-90' : ''"
                                                     fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </button>
                                            @endif
                                        </div>

                                        @if($cat->children->isNotEmpty())
                                        <ul x-show="open" x-cloak
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 -translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            class="ml-4 mt-0.5 space-y-0.5 border-l-2 border-slate-100 pl-3">
                                            @foreach($cat->children as $child)
                                                @php $isChildItemActive = request()->route('category_slug') == $child->slug; @endphp
                                                <li>
                                                    <a href="{{ route('client.shop', $child->slug) }}"
                                                       class="cat-link flex items-center justify-between px-3 py-2 rounded-xl text-sm
                                                              {{ $isChildItemActive ? 'font-bold text-sky-600 bg-sky-50/60' : 'font-medium text-slate-400 hover:bg-slate-50 hover:text-slate-700' }}">
                                                        <span>{{ $child->name }}</span>
                                                        <span class="text-xs px-2 py-0.5 rounded-full font-bold
                                                                     {{ $isChildItemActive ? 'bg-sky-100 text-sky-600' : 'bg-slate-100 text-slate-400' }}">
                                                            {{ $child->products_count }}
                                                        </span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        @endif

                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <hr class="border-slate-100">

                        {{-- Thương hiệu --}}
                        <div>
                            <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                Thương hiệu
                            </h3>
                            <div class="space-y-1.5">
                                @forelse($brands ?? [] as $brand)
                                    <label class="flex items-center gap-3 px-2 py-1.5 rounded-xl cursor-pointer group hover:bg-slate-50 transition-colors">
                                        <input type="checkbox" name="brands[]" value="{{ $brand->slug }}"
                                               {{ in_array($brand->slug, (array)request('brands')) ? 'checked' : '' }}
                                               onchange="this.form.submit()"
                                               class="w-4.5 h-4.5 rounded-md border-slate-300 text-sky-500 focus:ring-sky-400/30 focus:ring-offset-0">
                                        <span class="text-sm font-medium text-slate-600 group-hover:text-slate-900 transition-colors">{{ $brand->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-xs text-slate-400 italic px-1">Không có thương hiệu</p>
                                @endforelse
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        {{-- Khoảng giá --}}
                        <div>
                            <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Khoảng giá
                            </h3>
                            <div class="space-y-2">
                                @foreach([
                                    ['label' => 'Tất cả giá',       'min' => '',         'max' => ''],
                                    ['label' => 'Dưới 5 triệu',     'min' => '',         'max' => '5000000'],
                                    ['label' => '5 triệu – 15 triệu','min' => '5000000', 'max' => '15000000'],
                                    ['label' => 'Trên 15 triệu',    'min' => '15000000', 'max' => ''],
                                ] as $range)
                                    <button type="button"
                                            onclick="setPriceRange('{{ $range['min'] }}', '{{ $range['max'] }}')"
                                            class="filter-btn {{ request('max_price') == $range['max'] && request('min_price') == $range['min'] ? 'active' : '' }}">
                                        {{ $range['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </aside>

                {{-- Product Area --}}
                <main class="flex-1 min-w-0">

                    {{-- Sort bar --}}
                    <div class="bg-white border border-slate-200/70 rounded-2xl px-6 py-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm shadow-slate-100/40">
                        <p class="text-sm text-slate-500 font-medium">
                            Hiển thị từ <span class="font-bold text-slate-800">{{ $products->firstItem() ?? 0 }}</span>
                            đến <span class="font-bold text-slate-800">{{ $products->lastItem() ?? 0 }}</span> sản phẩm
                        </p>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sắp xếp:</span>
                            <select class="sort-select" name="sort" onchange="this.form.submit()">
                                <option value="newest"      {{ request('sort') == 'newest'      ? 'selected' : '' }}>Mới nhất đầu tiên</option>
                                <option value="price_asc"   {{ request('sort') == 'price_asc'   ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                                <option value="price_desc"  {{ request('sort') == 'price_desc'  ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                                <option value="best_selling"{{ request('sort') == 'best_selling'? 'selected' : '' }}>Bán chạy hàng đầu</option>
                            </select>
                        </div>
                    </div>

                    {{-- Product Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @forelse($products ?? [] as $product)
                        <div class="product-card relative">

                            {{-- Badge --}}
                            <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                                @if($product->is_on_sale)
                                    <span class="badge-premium badge-sale">-{{ $product->max_discount }}%</span>
                                @endif
                            </div>

                            {{-- Image --}}
                            <div class="product-img-wrap bg-gradient-to-br from-slate-50 to-slate-100/40">
                                @if(!empty($product->thumbnail))
                                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}">
                                @elseif(!empty($product->image))
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @elseif($product->variants->isNotEmpty() && !empty($product->variants->first()->image))
                                    <img src="{{ asset('storage/' . $product->variants->first()->image) }}" alt="{{ $product->name }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop&q=60" alt="{{ $product->name }}">
                                @endif

                                <div class="quick-view-overlay">
                                    <a href="{{ route('client.product.show', $product->slug) }}"
                                       class="w-11 h-11 bg-white/90 rounded-2xl flex items-center justify-center shadow-md text-slate-700 hover:bg-sky-500 hover:text-white hover:scale-105 transition-all duration-300 border border-slate-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-5">
                                <span class="text-[10px] font-black tracking-widest uppercase text-sky-500/80">
                                    {{ $product->brand->name ?? 'ShopNova' }}
                                </span>
                                <h3 class="text-sm font-bold text-slate-800 mt-1 line-clamp-2 leading-snug hover:text-sky-600 transition-colors">
                                    <a href="{{ route('client.product.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <div class="mt-4 flex items-center justify-between gap-2">
                                    <div class="flex flex-col">
                                        <span class="text-base font-black text-slate-900 tracking-tight">
                                            {{ number_format($product->min_price, 0, ',', '.') }}đ
                                        </span>
                                        @if($product->is_on_sale)
                                        <span class="text-xs text-slate-400 line-through mt-0.5">
                                            {{ number_format($product->original_price, 0, ',', '.') }}đ
                                        </span>
                                        @endif
                                    </div>
                                    <button type="button" onclick="addToCart({{ $product->id }})" class="add-cart-btn" title="Thêm vào giỏ">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    </button>
                                </div>
                            </div>

                        </div>
                        @empty
                        <div class="col-span-full py-20 text-center bg-white border border-dashed border-slate-200 rounded-3xl">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18a2.25 2.25 0 012.25 2.25v4.25A2.25 2.25 0 0121.75 21H2.25A2.25 2.25 0 010 18.75v-4.25A2.25 2.25 0 012.25 13.5z"/></svg>
                            <p class="text-slate-400 text-sm font-medium">Không tìm thấy sản phẩm nào phù hợp bộ lọc của bạn.</p>
                        </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if(isset($products) && $products->hasPages())
                    <div class="mt-12 flex justify-center">
                        <div class="inline-flex items-center gap-1.5 p-2 bg-white border border-slate-200/80 rounded-2xl shadow-sm">

                            @if($products->onFirstPage())
                                <span class="page-btn opacity-30 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                </span>
                            @else
                                <a href="{{ $products->appends(request()->all())->previousPageUrl() }}" class="page-btn">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                </a>
                            @endif

                            @if(method_exists($products, 'renderableProvider'))
                                @foreach($products->renderableProvider()->elements() as $element)
                                    @if(is_string($element))
                                        <span class="page-btn opacity-50 cursor-not-allowed">{{ $element }}</span>
                                    @endif
                                    @if(is_array($element))
                                        @foreach($element as $page => $url)
                                            @if($page == $products->currentPage())
                                                <span class="page-btn active">{{ $page }}</span>
                                            @else
                                                <a href="{{ $products->appends(request()->all())->url($page) }}" class="page-btn">{{ $page }}</a>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @else
                                @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                    @if($page == $products->currentPage())
                                        <span class="page-btn active">{{ $page }}</span>
                                    @else
                                        <a href="{{ $products->appends(request()->all())->url($page) }}" class="page-btn">{{ $page }}</a>
                                    @endif
                                @endforeach
                            @endif

                            @if($products->hasMorePages())
                                <a href="{{ $products->appends(request()->all())->nextPageUrl() }}" class="page-btn">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @else
                                <span class="page-btn opacity-30 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            @endif

                        </div>
                    </div>
                    @endif

                </main>
            </div>
        </form>
    </div>
</div>

<form id="add-to-cart-form" action="{{ route('client.cart.add') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="product_id" id="cart_product_id">
    <input type="hidden" name="quantity" value="1">
</form>

<script>
    function setPriceRange(min, max) {
        document.getElementById('min_price_input').value = min;
        document.getElementById('max_price_input').value = max;
        document.getElementById('filter-form').submit();
    }

    function addToCart(productId) {
        document.getElementById('cart_product_id').value = productId;
        document.getElementById('add-to-cart-form').submit();
    }
</script>
@endsection
