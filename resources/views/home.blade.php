@extends('layouts.app')

@section('title', 'Trang chủ - ShopNova | Thiên Đường Mua Sắm Chuyên Nghiệp')

@push('styles')
<style>
    /* Hệ thống nền & Hiệu ứng chuyển động mượt mà */
    .premium-bg {
        background:
            radial-gradient(circle at 5% 5%, rgba(14, 165, 233, 0.03), transparent 25%),
            radial-gradient(circle at 95% 95%, rgba(249, 115, 22, 0.03), transparent 30%),
            #f8fafc;
    }

    .glass-card-modern {
        background: rgba(255, 255, 255, 0.85);
        border: 1px solid rgba(226, 232, 240, 0.8);
        backdrop-filter: blur(8px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-card-modern:hover {
        transform: translateY(-4px);
        background: #ffffff;
        border-color: rgba(14, 165, 233, 0.3);
        box-shadow: 0 15px 30px -5px rgba(14, 165, 233, 0.08);
    }

    /* Flash Sale Section với dải màu Gradient thời thượng */
    .flash-sale-section {
        background: linear-gradient(135deg, #0f172a 0%, #0369a1 50%, #0c4a6e 100%);
    }

    .countdown-glass-box {
        background: #ffffff !important;
        color: #0369a1 !important;
        box-shadow: 0 0 20px rgba(56, 189, 248, 0.5);
    }

    /* Hiệu ứng chạy chữ vô tận (Ticker Marquee) */
    @keyframes marquee {
        0% { transform: translateX(0%); }
        100% { transform: translateX(-50%); }
    }
    .animate-marquee {
        display: flex;
        width: 200%;
        animation: marquee 25s linear infinite;
    }
    .animate-marquee:hover {
        animation-play-state: paused;
    }

    /* Slide Banner */
    .hero-carousel {
        position: relative;
        width: 100%;
        height: 420px;
    }
    @media (max-width: 768px) { .hero-carousel { height: 260px; } }

    .carousel-item-custom {
        position: absolute;
        inset: 0;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.6s ease-in-out, visibility 0.6s ease-in-out;
    }

    .carousel-item-custom.active {
        opacity: 1;
        visibility: visible;
    }

    .banner-bg-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush

@section('content')
@php
    $saleProducts = ($flashSaleProducts ?? collect())->count() ? $flashSaleProducts : ($newProducts ?? collect());

    $categoryColors = [
        'bg-blue-50 text-blue-600 ring-blue-100',
        'bg-orange-50 text-orange-600 ring-orange-100',
        'bg-emerald-50 text-emerald-600 ring-emerald-100',
        'bg-purple-50 text-purple-600 ring-purple-100',
        'bg-rose-50 text-rose-600 ring-rose-100',
        'bg-amber-50 text-amber-600 ring-amber-100',
    ];

    $slides = [
        [
            'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1200&auto=format&fit=crop',
            'badge' => 'Bộ Sưu Tập Mới 2026',
            'title' => 'Định hình phong cách riêng',
            'desc' => 'Ưu đãi đặc quyền giảm 15% cho đơn hàng đầu tiên.'
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=1200&auto=format&fit=crop',
            'badge' => 'Tuần Lễ Smart-Living',
            'title' => 'Công nghệ dẫn lối cuộc sống',
            'desc' => 'Hệ sinh thái phụ kiện chính hãng trả góp 0%.'
        ]
    ];
@endphp

<div class="premium-bg pb-16">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 hero-carousel rounded-2xl overflow-hidden shadow-lg group">
                @foreach($slides as $index => $slide)
                    <div class="carousel-item-custom {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                        <img src="{{ $slide['image'] }}" alt="Banner {{ $index + 1 }}" class="banner-bg-img">
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-950/40 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center px-8 sm:px-12">
                            <div class="max-w-md">
                                <span class="bg-sky-500 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md">{{ $slide['badge'] }}</span>
                                <h1 class="mt-3 text-2xl sm:text-4xl font-black text-white leading-tight">{{ $slide['title'] }}</h1>
                                <p class="mt-2 text-xs sm:text-sm text-slate-300">{{ $slide['desc'] }}</p>
                                <a href="{{ route('client.shop') }}" class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-white px-4 py-2 text-xs font-bold text-slate-950 hover:bg-slate-100 transition">Mua ngay</a>
                            </div>
                        </div>
                    </div>
                @endforeach
                <button onclick="prevSlide()" class="absolute left-3 top-1/2 -translate-y-1/2 h-8 w-8 hidden group-hover:flex items-center justify-center rounded-full bg-black/30 text-white backdrop-blur-sm hover:bg-black/50">❮</button>
                <button onclick="nextSlide()" class="absolute right-3 top-1/2 -translate-y-1/2 h-8 w-8 hidden group-hover:flex items-center justify-center rounded-full bg-black/30 text-white backdrop-blur-sm hover:bg-black/50">❯</button>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-1 gap-4 h-full">
                <div class="relative rounded-2xl overflow-hidden shadow-md h-[202px] lg:h-[202px]">
                    <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=600&auto=format&fit=crop" class="w-full height-full object-cover" alt="Sub Banner 1">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-4">
                        <span class="text-amber-400 text-[10px] font-bold uppercase">Xả kho cuối tháng</span>
                        <h3 class="text-white font-bold text-sm sm:text-base">Voucher Giảm Toàn Sàn 100K</h3>
                    </div>
                </div>
                <div class="relative rounded-2xl overflow-hidden shadow-md h-[202px] lg:h-[202px]">
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=600&auto=format&fit=crop" class="w-full height-full object-cover" alt="Sub Banner 2">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-4">
                        <span class="text-emerald-400 text-[10px] font-bold uppercase">Xu hướng mới</span>
                        <h3 class="text-white font-bold text-sm sm:text-base">Bộ Sưu Tập Giày Thể Thao</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 bg-white rounded-xl p-4 shadow-sm border border-slate-100">
            @foreach([
                ['title' => 'Vận Chuyển Miễn Phí', 'sub' => 'Đơn hàng từ 300K', 'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 5H7a2 2 0 00-2 2v9h14v-4h-6V5z', 'bg' => 'bg-blue-50 text-blue-500'],
                ['title' => 'Chính Hãng 100%', 'sub' => 'Hoàn tiền x10 nếu phát hiện lỗi', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-2.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-emerald-50 text-emerald-500'],
                ['title' => 'Đổi Trả 30 Ngày', 'sub' => 'Thủ tục nhanh gọn trong ngày', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 15.07M9 11l3-3m0 0l3 3m-3-3v8', 'bg' => 'bg-orange-50 text-orange-500'],
                ['title' => 'Hỗ Trợ 24/7', 'sub' => 'Hotline tư vấn tận tâm', 'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536', 'bg' => 'bg-purple-50 text-purple-500'],
            ] as $item)
                <div class="flex items-center gap-3 p-2 rounded-xl">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $item['bg'] }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 leading-tight">{{ $item['title'] }}</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">{{ $item['sub'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
            <h2 class="text-lg font-black text-slate-900 tracking-tight uppercase border-b-2 border-sky-500 pb-3 -mb-[14px]">Danh mục nổi bật</h2>
            <a href="{{ route('client.shop') }}" class="text-xs font-bold text-sky-600 hover:underline">Tất cả danh mục →</a>
        </div>
        <div class="mt-6 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
            @forelse(($categories ?? collect()) as $index => $category)
                <a href="{{ route('client.shop', $category->slug) }}" class="glass-card-modern group rounded-xl p-4 text-center flex flex-col items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl mb-2 group-hover:scale-110 transition-transform duration-300 {{ $categoryColors[$index % count($categoryColors)] }}">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-6 w-6 object-contain">
                        @else
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                        @endif
                    </div>
                    <h3 class="line-clamp-1 text-xs font-bold text-slate-700 group-hover:text-sky-600 transition">{{ $category->name }}</h3>
                </a>
            @empty
                @foreach(['Thiết Bị Điện Tử', 'Thời Trang', 'Gia Dụng', 'Mỹ Phẩm', 'Thể Thao', 'Phụ Kiện'] as $index => $name)
                    <a href="#" class="glass-card-modern group rounded-xl p-4 text-center flex flex-col items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl mb-2 {{ $categoryColors[$index % count($categoryColors)] }}">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700">{{ $name }}</h3>
                    </a>
                @endforeach
            @endforelse
        </div>
    </section>

    <div class="w-full bg-amber-400 text-slate-950 font-bold text-xs py-2 overflow-hidden my-12 shadow-inner">
        <div class="animate-marquee whitespace-nowrap gap-12">
            @for ($i = 0; $i < 3; $i++)
                <span>🔥 ĐẠI TIỆC FLASH SALE ĐANG DIỄN RA - GIẢM GIÁ LÊN ĐẾN 50% TOÀN BỘ SẢN PHẨM CHÍNH HÃNG</span>
                <span>⚡ NHẬP MÃ "NOVANEW" GIẢM NGAY 15% CHO ĐƠN HÀNG ĐẦU TIÊN CỦA BẠN</span>
                <span>📦 MIỄN PHÍ VẬN CHUYỂN TOÀN QUỐC CHO MỌI ĐƠN HÀNG TỪ 300.000 VNĐ</span>
            @endfor
        </div>
    </div>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flash-sale-section rounded-2xl p-6 md:p-8 shadow-xl text-white">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-white/20 pb-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-400 text-slate-950 font-black shadow-lg">⚡</span>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black uppercase tracking-tight text-white">Giờ Vàng Giá Sốc</h2>
                        <p class="text-xs text-slate-200 mt-0.5">Số lượng giới hạn, làm mới sau mỗi khung giờ</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 bg-black/20 p-2 rounded-xl border border-white/10" id="countdown">
                    <span class="text-[10px] font-bold text-amber-300 px-2 uppercase tracking-wider">Kết thúc sau</span>
                    <div id="hours" class="countdown-glass-box min-w-[2.2rem] text-center font-mono font-bold text-sm rounded-md py-1 px-1.5">00</div>
                    <span class="text-amber-300 font-bold">:</span>
                    <div id="minutes" class="countdown-glass-box min-w-[2.2rem] text-center font-mono font-bold text-sm rounded-md py-1 px-1.5">00</div>
                    <span class="text-amber-300 font-bold">:</span>
                    <div id="seconds" class="countdown-glass-box min-w-[2.2rem] text-center font-mono font-bold text-sm rounded-md py-1 px-1.5">00</div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                @forelse($saleProducts->take(5) as $product)
                    <div class="bg-white rounded-xl overflow-hidden p-3 shadow-md group relative flex flex-col justify-between">
                        @include('components.product-card', [
                            'product' => $product,
                            'badge' => 'sale'
                        ])
                    </div>
                @empty
                    @for($i = 0; $i < 5; $i++)
                        <div class="rounded-xl bg-white/10 p-4 space-y-3 animate-pulse">
                            <div class="aspect-square w-full rounded-lg bg-white/20"></div>
                            <div class="h-3 bg-white/20 rounded w-3/4"></div>
                            <div class="h-4 bg-white/20 rounded w-1/2"></div>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-6">
            <div class="flex gap-4">
                <button class="text-base font-black uppercase border-b-2 border-sky-500 pb-3 -mb-[14px] text-slate-900">Gợi ý cho bạn</button>
                <button class="text-base font-bold uppercase pb-3 -mb-[14px] text-slate-400 hover:text-slate-600">Sản phẩm mới</button>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @forelse(($newProducts ?? collect())->take(8) as $product)
                <div class="bg-white rounded-xl p-3 border border-slate-100 shadow-sm hover:shadow-md transition duration-200">
                    @include('components.product-card', ['product' => $product, 'badge' => 'new'])
                </div>
            @empty
                @for($i = 0; $i < 8; $i++)
                    <div class="rounded-xl border border-slate-100 bg-white p-4 space-y-3 animate-pulse">
                        <div class="aspect-square w-full rounded-lg bg-slate-100"></div>
                        <div class="h-3 bg-slate-100 rounded w-3/4"></div>
                        <div class="h-4 bg-slate-100 rounded w-1/2"></div>
                    </div>
                @endfor
            @endforelse
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
// LOGIC CAROUSEL BANNER CHÍNH KHÔNG ĐỔI
let currentSlide = 0;
const totalSlides = {{ count($slides) }};
let slideInterval = setInterval(nextSlide, 5000);

function updateSliderViews() {
    document.querySelectorAll('.carousel-item-custom').forEach((slide, idx) => {
        slide.classList.toggle('active', idx === currentSlide);
    });
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

function resetInterval() {
    clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, 5000);
}

// FIX LỖI TÍNH TOÁN THỜI GIAN ĐẾM NGƯỢC (FIXED '3 warm00000' -> MATH LOGIC)
function updateCountdown() {
    const now = new Date();
    const midnight = new Date();
    midnight.setHours(24, 0, 0, 0);
    const diff = Math.max(midnight - now, 0);

    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
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
