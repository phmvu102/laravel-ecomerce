@extends('layouts.app')

@section('title', 'Trang chủ - ShopNova | Thiên Đường Mua Sắm Chuyên Nghiệp')

@push('styles')
<style>
    /* Hệ thống nền Liquid & Glassmorphism cao cấp - Đã tinh giản để tôn sản phẩm */
    .premium-bg {
        background:
            radial-gradient(circle at 10% 10%, rgba(249, 115, 22, 0.04), transparent 30%),
            radial-gradient(circle at 90% 80%, rgba(14, 165, 233, 0.04), transparent 40%),
            #f8fafc;
    }

    .glass-card-modern {
        background: rgba(255, 255, 255, 0.85);
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
        backdrop-filter: blur(8px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-card-modern:hover {
        transform: translateY(-4px);
        background: #ffffff;
        border-color: rgba(14, 165, 233, 0.3);
        box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 10px 10px -5px rgba(15, 23, 42, 0.04);
    }

    /* --- UPDATE: TỐI ƯU FLASH SALE - CHỮ TRẮNG & HỘP ĐẾM NGƯỢC HIỆU ỨNG KÍNH PHÁT SÁNG --- */
    .flash-sale-section {
        /* Tăng nhẹ độ sâu màu nền để chữ màu trắng nổi bần bật lên hẳn */
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.55) 0%, rgba(2, 132, 199, 0.45) 50%, rgba(3, 105, 161, 0.5) 100%);
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 25px 60px -10px rgba(14, 165, 233, 0.35), inset 0 1px 3px rgba(255, 255, 255, 0.4);
    }

    /* Đẩy mạnh luồng sáng Liquid chạy ngầm rực rỡ hơn */
    .glass-liquid-glow-1 {
        background: radial-gradient(circle, rgba(56, 189, 248, 0.65) 0%, transparent 70%);
    }
    .glass-liquid-glow-2 {
        background: radial-gradient(circle, rgba(14, 165, 233, 0.55) 0%, transparent 75%);
    }

    /* Hộp đếm ngược nâng cấp: Kính mờ phủ sương tuyết, chữ xanh đại dương sâu sắc nét */
    .countdown-glass-box {
        background: rgba(255, 255, 255, 0.92) !important;
        color: #0c4a6e !important; /* Xanh Deep Ocean cực kỳ sang trọng và dễ nhìn */
        border: 1px solid rgba(255, 255, 255, 0.8) !important;

        /* Hiệu ứng hào quang phát sáng dịu mắt (Soft Glow) thay vì viền thô */
        box-shadow:
            0 8px 20px rgba(14, 165, 233, 0.25),
            0 0 15px rgba(56, 189, 248, 0.4),
            inset 0 2px 4px rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        text-shadow: 0 1px 1px rgba(255, 255, 255, 0.5);
    }

    /* Badge "Đang diễn ra" tinh chỉnh chữ trắng nền Glassmorphism sang trọng */
    .flash-sale-badge {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
        backdrop-filter: blur(5px);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .dark-premium-section {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }

    .badge-chip {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(12px);
    }

    /* Banner carousel */
    .hero-carousel {
        position: relative;
        width: 100%;
        height: 560px;
        overflow: hidden;
    }
    @media (max-width: 1024px) { .hero-carousel { height: 450px; } }
    @media (max-width: 768px) { .hero-carousel { height: 350px; } }

    .carousel-item-custom {
        position: absolute;
        inset: 0;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.8s ease-in-out;
    }

    .carousel-item-custom.active {
        opacity: 1;
        visibility: visible;
    }

    .carousel-item-custom.active .banner-bg-img {
        transform: scale(1.03);
    }

    .banner-bg-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 6s cubic-bezier(0.1, 0.8, 0.3, 1);
    }

    .carousel-nav-btn {
        opacity: 0;
        transition: all 0.25s ease;
        z-index: 20;
    }
    .hero-carousel:hover .carousel-nav-btn {
        opacity: 1;
    }
</style>
@endpush

@section('content')
@php
    $saleProducts = ($flashSaleProducts ?? collect())->count() ? $flashSaleProducts : ($newProducts ?? collect());

    $categoryColors = [
        'bg-blue-50 text-blue-600 ring-blue-100/50',
        'bg-orange-50 text-orange-600 ring-orange-100/50',
        'bg-emerald-50 text-emerald-600 ring-emerald-100/50',
        'bg-purple-50 text-purple-600 ring-purple-100/50',
        'bg-rose-50 text-rose-600 ring-rose-100/50',
        'bg-amber-50 text-amber-600 ring-amber-100/50',
    ];

    $slides = [
        [
            'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1920&auto=format&fit=crop',
            'badge' => 'Bộ Sưu Tập Mới 2026',
            'badge_color' => 'bg-sky-500',
            'title' => 'Định hình phong cách.<br>Khẳng định chất riêng.',
            'desc' => 'Khám phá ngay những thiết kế dẫn đầu xu hướng tối giản với chất liệu organic cao cấp, ưu đãi đặc quyền giảm 15% cho đơn hàng đầu tiên.',
            'overlay' => 'from-slate-950/70 via-slate-950/40 to-transparent'
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=1920&auto=format&fit=crop',
            'badge' => 'Tuần Lễ Công Nghệ Smart-Living',
            'badge_color' => 'bg-amber-500',
            'title' => 'Công nghệ dẫn lối.<br>Cuộc sống thăng hoa.',
            'desc' => 'Hệ sinh thái phụ kiện âm thanh và thiết bị thông minh chính hãng. Trả góp 0% lãi suất, bảo hành 1 đổi 1 trong vòng 12 tháng.',
            'overlay' => 'from-slate-950/80 via-slate-950/40 to-transparent'
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=1920&auto=format&fit=crop',
            'badge' => 'Đại Tiệc Mua Sắm Cuối Tháng',
            'badge_color' => 'bg-rose-500',
            'title' => 'Săn Deal Thần Tốc.<br>Miễn Phí Giao Hàng.',
            'desc' => 'Hàng ngàn Voucher giảm thẳng 100K áp dụng toàn sàn. Miễn phí vận chuyển cho mọi đơn hàng giá trị từ 300K trên toàn quốc.',
            'overlay' => 'from-slate-950/70 via-slate-950/30 to-transparent'
        ]
    ];
@endphp

<section class="hero-carousel bg-slate-950">
    @foreach($slides as $index => $slide)
        <div class="carousel-item-custom {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
            <img src="{{ $slide['image'] }}" alt="ShopNova Banner {{ $index + 1 }}" class="banner-bg-img">
            <div class="absolute inset-0 bg-gradient-to-r {{ $slide['overlay'] }}"></div>

            <div class="absolute inset-0 flex items-center">
                <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="max-w-2xl lg:pl-16">
                        <div class="badge-chip inline-flex items-center gap-2 rounded-full px-3.5 py-1.5 text-[11px] font-bold uppercase tracking-wider text-white">
                            <span class="h-2 w-2 rounded-full {{ $slide['badge_color'] }} animate-pulse"></span>
                            {{ $slide['badge'] }}
                        </div>

                        <h1 class="mt-5 text-4xl font-extrabold leading-[1.15] tracking-tight text-white sm:text-5xl lg:text-6xl">
                            {!! $slide['title'] !!}
                        </h1>

                        <p class="mt-4 text-sm leading-relaxed text-slate-300 sm:text-base md:text-lg max-w-xl">
                            {{ $slide['desc'] }}
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="{{ route('client.shop') }}"
                               class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-7 py-4 text-sm font-bold text-slate-950 shadow-md transition-all duration-200 hover:-translate-y-0.5 hover:bg-slate-50 active:translate-y-0">
                                 Mua Ngay Bản Giới Hạn
                                 <svg class="h-4 w-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                            <a href="#categories"
                               class="badge-chip inline-flex items-center justify-center rounded-xl px-6 py-4 text-sm font-bold text-white transition-all duration-200 hover:bg-white/30 hover:-translate-y-0.5">
                                 Khám Phá Danh Mục
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <button onclick="prevSlide()" class="carousel-nav-btn absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 hidden md:flex h-12 w-12 items-center justify-center rounded-full bg-black/30 text-white backdrop-blur-md hover:bg-black/60 border border-white/10">
        <svg class="h-5 w-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button onclick="nextSlide()" class="carousel-nav-btn absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 hidden md:flex h-12 w-12 items-center justify-center rounded-full bg-black/30 text-white backdrop-blur-md hover:bg-black/60 border border-white/10">
        <svg class="h-5 w-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </button>

    <div class="absolute bottom-6 inset-x-0 z-10 flex justify-center gap-2">
        @foreach($slides as $index => $slide)
            <button onclick="goToSlide({{ $index }})" id="dot-{{ $index }}"
                    class="h-2 transition-all duration-300 rounded-full {{ $index === 0 ? 'w-7 bg-white' : 'w-2 bg-white/40' }}"></button>
        @endforeach
    </div>
</section>

<div class="premium-bg">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 -mt-8 relative z-20">
        <div class="grid gap-4 grid-cols-2 lg:grid-cols-4 bg-white rounded-2xl p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
            @foreach([
                ['title' => 'Vận Chuyển Toàn Quốc', 'sub' => 'Freeship đơn từ 500K', 'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 5H7a2 2 0 00-2 2v9h14v-4h-6V5z', 'color' => 'text-blue-500 bg-blue-50'],
                ['title' => 'Hàng Chính Hãng 100%', 'sub' => 'Hoàn tiền x10 nếu phát hiện lỗi', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-2.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'text-emerald-500 bg-emerald-50'],
                ['title' => 'Đổi Trả Linh Hoạt', 'sub' => 'Đổi mới miễn phí trong 30 ngày', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 15.07M9 11l3-3m0 0l3 3m-3-3v8', 'color' => 'text-orange-500 bg-orange-50'],
                ['title' => 'Tư Vấn Tận Tâm 24/7', 'sub' => 'Đội ngũ hỗ trợ chuyên nghiệp', 'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'text-purple-500 bg-purple-50'],
            ] as $item)
                <div class="flex items-center gap-4 p-2 rounded-xl transition-colors hover:bg-slate-50">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $item['color'] }}">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">{{ $item['title'] }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $item['sub'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="categories" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-end justify-between border-b border-slate-200 pb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-sky-600">Khám Phá Xu Hướng</span>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Danh Mục Mua Sắm</h2>
            </div>
            <a href="{{ route('client.shop') }}" class="group inline-flex items-center gap-1.5 text-sm font-semibold text-sky-600 hover:text-sky-700 transition">
                Xem tất cả danh mục
                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @forelse(($categories ?? collect()) as $index => $category)
                <a href="{{ route('client.shop', $category->slug) }}" class="glass-card-modern group rounded-2xl p-5 text-center flex flex-col items-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl ring-1 mb-4 transition-transform group-hover:scale-110 duration-300 {{ $categoryColors[$index % count($categoryColors)] }}">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-8 w-8 object-contain">
                        @else
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                        @endif
                    </div>
                    <h3 class="line-clamp-1 text-sm font-bold text-slate-900 group-hover:text-sky-600 transition">{{ $category->name }}</h3>
                    <span class="mt-1.5 text-xs font-medium text-slate-400">{{ $category->products_count ?? 0 }} sản phẩm</span>
                </a>
            @empty
                @foreach(['Thiết Bị Điện Tử', 'Thời Trang Nam/Nữ', 'Gia Dụng Thông Minh', 'Mỹ Phẩm Làm Đẹp', 'Dụng Cụ Thể Thao', 'Phụ Kiện Cao Cấp'] as $index => $name)
                    <a href="{{ route('client.shop') }}" class="glass-card-modern group rounded-2xl p-5 text-center flex flex-col items-center">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl ring-1 mb-4 {{ $categoryColors[$index % count($categoryColors)] }}">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">{{ $name }}</h3>
                        <span class="mt-1.5 text-xs font-medium text-slate-400">Đang cập nhật</span>
                    </a>
                @endforeach
            @endforelse
        </div>
    </section>
</div>

<div class="relative max-w-[95%] mx-auto my-12 rounded-3xl overflow-hidden shadow-2xl shadow-sky-900/20">
    <div class="absolute inset-0 bg-slate-900 -z-20"></div>
    <div class="absolute -top-20 -right-20 h-96 w-96 rounded-full glass-liquid-glow-1 -z-10 pointer-events-none animate-pulse"></div>
    <div class="absolute -bottom-20 -left-20 h-96 w-96 rounded-full glass-liquid-glow-2 -z-10 pointer-events-none"></div>

    <section class="flash-sale-section py-16 text-white relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between border-b border-white/30 pb-8">
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-400/20 border border-amber-400/60 shadow-[0_0_15px_rgba(251,191,36,0.5)]">
                        <svg class="h-7 w-7 text-amber-300 animate-bounce" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>

                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-amber-300 bg-white/20 px-3 py-1 rounded-full border border-white/40 shadow-sm">Đang diễn ra</span>
                        <h2 class="text-3xl font-black tracking-tight sm:text-4xl mt-2 text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.4)]">
                            Săn Deal Chớp Nhoáng, <span class="text-amber-300">Giá Rẻ Sập Sàn!</span>
                        </h2>
                    </div>
                </div>

                <div class="flex items-center gap-2" id="countdown">
                    <span class="text-base font-extrabold text-white mr-2 drop-shadow">KẾT THÚC SAU:</span>
                    @foreach(['hours' => 'Giờ', 'minutes' => 'Phút', 'seconds' => 'Giây'] as $id => $label)
                        <div class="flex items-center">
                            <div id="{{ $id }}" class="countdown-glass-box min-w-[3rem] font-mono text-2xl font-black rounded-xl p-2 text-center">00</div>
                            @if(!$loop->last) <span class="mx-1.5 text-2xl font-black text-amber-300 animate-pulse">:</span> @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-12 grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-5">
                @forelse($saleProducts->take(5) as $product)
                    @include('layouts.product-card', [
                        'product' => $product,
                        'badge' => $product->sale_price && $product->price && $product->sale_price < $product->price ? 'sale' : 'new'
                    ])
                @empty
                    @for($i = 0; $i < 5; $i++)
                        <div class="rounded-2xl border border-white/20 bg-white/10 p-5 space-y-4 animate-pulse">
                            <div class="aspect-square w-full rounded-xl bg-white/20"></div>
                            <div class="h-4 bg-white/20 rounded w-3/4"></div>
                            <div class="h-3 bg-white/20 rounded w-1/2"></div>
                            <div class="flex justify-between items-center pt-2">
                                <div class="h-5 bg-white/20 rounded w-1/3"></div>
                                <div class="h-8 w-8 bg-white/20 rounded-lg"></div>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>
</div>

<section class="premium-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-sky-600">Hàng Lên Kệ Mới Nhất</span>
            <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Bộ Sưu Tập Vừa Cập Nhật</h2>
            <div class="mt-3 h-1 w-12 bg-sky-500 mx-auto rounded-full"></div>
            <p class="mt-4 text-sm text-slate-500 leading-relaxed">Đón đầu phong cách với những dòng sản phẩm mới cập bến, cam kết chất lượng chuẩn đầu ra kèm chính sách dùng thử đặc quyền.</p>
        </div>

        <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
            @forelse(($newProducts ?? collect())->take(8) as $product)
                @include('layouts.product-card', ['product' => $product, 'badge' => 'new'])
            @empty
                @for($i = 0; $i < 8; $i++)
                    <div class="rounded-2xl border border-slate-100 bg-white p-4 space-y-4 animate-pulse">
                        <div class="aspect-square w-full rounded-xl bg-slate-100"></div>
                        <div class="h-4 bg-slate-100 rounded w-3/4"></div>
                        <div class="h-3 bg-slate-100 rounded w-1/2"></div>
                        <div class="flex justify-between items-center pt-2">
                            <div class="h-5 bg-slate-100 rounded w-1/3"></div>
                            <div class="h-8 w-8 bg-slate-100 rounded-lg"></div>
                        </div>
                    </div>
                @endfor
            @endforelse
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('client.shop') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-8 py-3.5 text-sm font-bold text-white shadow-lg shadow-slate-900/10 transition duration-200 hover:bg-slate-800 hover:-translate-y-0.5">
                Xem Tất Cả Sản Phẩm Cửa Hàng
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</section>

@if(isset($brands) && $brands->count())
    <section class="bg-white py-12 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-center text-xs font-bold uppercase tracking-widest text-slate-400">Thương Hiệu Đồng Hành Chiến Lược</h3>
            <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-8">
                @foreach($brands as $brand)
                    <a href="{{ route('client.shop', ['brand' => $brand->id]) }}" class="flex min-h-[64px] items-center justify-center rounded-xl border border-slate-100 bg-slate-50/50 px-4 text-center text-xs font-bold text-slate-500 transition-all duration-200 hover:bg-white hover:border-sky-300 hover:text-sky-600 hover:shadow-md">
                        {{ $brand->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection

@push('scripts')
<script>
// SLIDER BANNER LOGIC
let currentSlide = 0;
const totalSlides = {{ count($slides) }};
let slideInterval = setInterval(nextSlide, 6000);

function updateSliderViews() {
    document.querySelectorAll('.carousel-item-custom').forEach((slide, idx) => {
        if (idx === currentSlide) {
            slide.classList.add('active');
        } else {
            slide.classList.remove('active');
        }
    });

    for (let i = 0; i < totalSlides; i++) {
        const dot = document.getElementById(`dot-${i}`);
        if (dot) {
            if (i === currentSlide) {
                dot.className = "h-2 transition-all duration-300 rounded-full w-7 bg-white";
            } else {
                dot.className = "h-2 transition-all duration-300 rounded-full w-2 bg-white/40";
            }
        }
    }
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    updateSliderViews();
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    updateSliderViews();
    resetInterval();
}

function goToSlide(slideIndex) {
    currentSlide = slideIndex;
    updateSliderViews();
    resetInterval();
}

function resetInterval() {
    clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, 6000);
}

// COUNTDOWN FLASH SALE LOGIC
function updateCountdown() {
    const now = new Date();
    const midnight = new Date();
    midnight.setHours(24, 0, 0, 0);
    const diff = Math.max(midnight - now, 0);

    const hours = Math.floor(diff / 3 warm00000);
    const minutes = Math.floor((diff % 3600000) / 60000);
    const seconds = Math.floor((diff % 60000) / 1000);
    const pad = value => String(value).padStart(2, '0');

    const hourEl = document.getElementById('hours');
    const minuteEl = document.getElementById('minutes');
    const secondEl = document.getElementById('seconds');

    if (hourEl && minuteEl && secondEl) {
        hourEl.textContent = pad(hours);
        minuteEl.textContent = pad(minutes);
        secondEl.textContent = pad(seconds);
    }
}

updateCountdown();
setInterval(updateCountdown, 1000);
</script>
@endpush
