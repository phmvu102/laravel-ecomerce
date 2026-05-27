<footer class="bg-slate-950 text-slate-400 mt-20 border-t border-slate-900 relative overflow-hidden">
    {{-- Điểm nhấn hiệu ứng ánh sáng ngầm (Liquid Light Base) ở góc phải footer --}}
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-sky-500/5 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute top-0 left-1/4 w-60 h-60 bg-blue-600/5 rounded-full blur-[100px] pointer-events-none"></div>

    {{-- Main Footer --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- Brand Column --}}
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center gap-2 mb-5 group">
                    <div class="w-9 h-9 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-center group-hover:bg-sky-500 shadow-md group-hover:shadow-sky-500/20 transition-all duration-300">
                        <svg class="w-5 h-5 text-white group-hover:scale-105 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25"/>
                        </svg>
                    </div>
                    <span class="text-xl font-black text-white tracking-tight">
                        Shop<span class="text-sky-500 group-hover:text-sky-400 transition-colors">Nova</span>
                    </span>
                </a>
                <p class="text-sm leading-relaxed text-slate-400 mb-6">
                    Mua sắm thông minh — trải nghiệm công nghệ vượt trội, giao hàng siêu tốc và dịch vụ bảo hành tận tâm.
                </p>
                
                {{-- Social Links - Đổi hover sang tone xanh dịu mắt --}}
                <div class="flex gap-3">
                    <a href="#" class="w-9 h-9 bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:bg-sky-500 hover:border-sky-500 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:bg-sky-500 hover:border-sky-500 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:bg-sky-500 hover:border-sky-500 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.34 6.34 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.27 8.27 0 004.84 1.55V6.79a4.85 4.85 0 01-1.07-.1z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="text-white font-extrabold text-xs uppercase tracking-widest mb-5 border-l-2 border-sky-500 pl-3">Mua sắm</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('client.shop') }}" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Tất cả sản phẩm</a></li>
                    <li><a href="#countdown" class="text-sm text-slate-400 hover:text-rose-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-rose-400 transition-all duration-200"></span> Flash Sale 🔥</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Sản phẩm mới</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Bán chạy nhất</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Khuyến mãi</a></li>
                </ul>
            </div>

            {{-- Support --}}
            <div>
                <h3 class="text-white font-extrabold text-xs uppercase tracking-widest mb-5 border-l-2 border-sky-500 pl-3">Hỗ trợ</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Chính sách đổi trả</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Chính sách bảo hành</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Hướng dẫn mua hàng</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Câu hỏi thường gặp</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-sky-400 transition-colors flex items-center gap-1 group"><span class="w-0 group-hover:w-1.5 h-0.5 bg-sky-400 transition-all duration-200"></span> Liên hệ</a></li>
                </ul>
            </div>

            {{-- Contact & Newsletter --}}
            <div>
                <h3 class="text-white font-extrabold text-xs uppercase tracking-widest mb-5 border-l-2 border-sky-500 pl-3">Liên hệ</h3>
                <ul class="space-y-3 mb-6">
                    <li class="flex items-start gap-2.5 text-sm text-slate-400">
                        <svg class="w-4 h-4 mt-0.5 text-sky-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.statusCode 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        123 Đường ABC, Thái Nguyên
                    </li>
                    <li class="flex items-center gap-2.5 text-sm text-slate-400">
                        <svg class="w-4 h-4 text-sky-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        0987 654 321
                    </li>
                    <li class="flex items-center gap-2.5 text-sm text-slate-400">
                        <svg class="w-4 h-4 text-sky-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        hello@shopnova.vn
                    </li>
                </ul>

                {{-- Newsletter --}}
                <div>
                    <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-3">Nhận thông tin ưu đãi</p>
                    <form class="flex gap-2">
                        <input type="email" placeholder="Email của bạn..."
                               class="flex-1 px-3 py-2.5 text-sm bg-slate-900 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors">
                        <button type="submit" class="px-4 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-bold rounded-xl shadow-md shadow-sky-500/10 transition-colors">
                            Gửi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-slate-900 bg-slate-950/80 backdrop-blur-md relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-500">
                © {{ date('Y') }} ShopNova. Tất cả quyền được bảo lưu.
            </p>
            
            {{-- Cổng thanh toán đã fix lỗi hiển thị, căn giữa và có độ sáng dịu cao cấp --}}
            <div class="flex items-center gap-4">
                
                {{-- Logo Mastercard --}}
                <div class="h-6 flex items-center justify-center opacity-60 hover:opacity-100 transition-opacity duration-300">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png" 
                         alt="Mastercard" class="h-4 w-auto object-contain">
                </div>
                
                {{-- Badge MOMO --}}
                <span class="text-[10px] text-slate-400 font-bold tracking-wider px-2 py-1 bg-slate-900 border border-slate-800/80 rounded-md hover:border-pink-500/30 hover:text-pink-400 transition-all duration-300 cursor-default">
                    MOMO
                </span>
                
                {{-- Badge PAYOS --}}
                <span class="text-[10px] text-slate-400 font-bold tracking-wider px-2 py-1 bg-slate-900 border border-slate-800/80 rounded-md hover:border-sky-500/30 hover:text-sky-400 transition-all duration-300 cursor-default">
                    PAYOS
                </span>
            </div>
        </div>
    </div>
</footer>
