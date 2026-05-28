@extends('layouts.app')

@section('title', 'Giới thiệu - ShopNova')

@section('content')
<div class="min-h-screen bg-slate-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-8">
            <a href="{{ route('home') }}" class="hover:text-sky-500 transition-colors">Trang chủ</a>
            <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-slate-600">Giới thiệu</span>
        </nav>

        <article class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-10 shadow-sm">
            <p class="text-xs font-black uppercase tracking-widest text-sky-600 mb-3">Về ShopNova</p>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-6">
                Giới thiệu ShopNova
            </h1>

            <div class="space-y-5 text-sm sm:text-base leading-8 text-slate-600">
                <p>
                    ShopNova là cửa hàng thương mại điện tử tập trung vào trải nghiệm mua sắm rõ ràng, nhanh gọn và đáng tin cậy. Chúng tôi xây dựng danh mục sản phẩm theo hướng dễ tìm kiếm, dễ so sánh và dễ lựa chọn.
                </p>

                <p>
                    Mỗi sản phẩm được trình bày với thông tin cần thiết như thương hiệu, phiên bản, giá bán, mô tả và thông số kỹ thuật. Mục tiêu là giúp khách hàng hiểu đúng sản phẩm trước khi đặt mua.
                </p>

                <p>
                    ShopNova ưu tiên chất lượng dịch vụ sau bán hàng, quy trình đặt hàng minh bạch và hỗ trợ khách hàng khi cần đổi trả, bảo hành hoặc tư vấn thêm về sản phẩm.
                </p>

                <p>
                    Chúng tôi sẽ tiếp tục hoàn thiện hệ thống để mang lại trải nghiệm mua sắm ổn định hơn, từ khâu tìm kiếm sản phẩm, thêm vào giỏ hàng, thanh toán cho đến theo dõi đơn hàng.
                </p>
            </div>
        </article>
    </div>
</div>
@endsection
