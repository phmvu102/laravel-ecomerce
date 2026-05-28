@extends('layouts.admin')

@section('page-title', 'Danh sách Sản phẩm')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-slate-800">Quản lý kho sản phẩm</h3>
        <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-indigo-700 transition shadow-sm flex items-center gap-1">
            <i data-lucide="plus" class="w-4 h-4"></i> Thêm sản phẩm
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-700 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs font-semibold uppercase">
                    <th class="p-4 w-20 text-center">Hình ảnh</th> {{-- MỚI BỔ SUNG --}}
                    <th class="p-4">Sản phẩm gốc</th>
                    <th class="p-4">Danh mục / Hãng</th>
                    <th class="p-4">Số lượng biến thể</th>
                    <th class="p-4">Tổng số lượng kho</th>
                    <th class="p-4">Trạng thái</th>
                    <th class="p-4 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @forelse($products as $prod)
                    @php
                        $totalStock = $prod->variants->sum('stock');
                        $primaryImage = $prod->thumbnail ?: optional($prod->variants->firstWhere('image', '!=', null))->image;
                    @endphp
                    <tr class="hover:bg-slate-50/50">
                        {{-- THỂ HIỆN ẢNH CHÍNH CỦA SẢN PHẨM --}}
                        <td class="p-4 text-center">
                            @if($primaryImage)
                                <img src="{{ asset('storage/' . $primaryImage) }}" alt="{{ $prod->name }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-2xs mx-auto">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center mx-auto text-slate-400" title="Chưa có ảnh">
                                    <i data-lucide="image" class="w-5 h-5"></i>
                                </div>
                            @endif
                        </td>

                        <td class="p-4 font-bold text-slate-900">{{ $prod->name }}</td>

                        <td class="p-4">
                            <span class="block font-medium">{{ $prod->category->name ?? 'N/A' }}</span>
                            <span class="text-xs text-slate-400">{{ $prod->brand->name ?? 'N/A' }}</span>
                        </td>

                        <td class="p-4 font-semibold text-indigo-600">{{ $prod->variants->count() }} phiên bản</td>

                        <td class="p-4">
                            @if($totalStock == 0)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span> Cháy hàng (0)
                                </span>
                            @elseif($totalStock <= 5)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-100">
                                    Sắp hết ({{ $totalStock }})
                                </span>
                            @else
                                <span class="font-bold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg text-xs">
                                    {{ $totalStock }} chiếc
                                </span>
                            @endif
                        </td>

                        <td class="p-4">
                            @if($prod->status === 'active')
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                                    Mở bán
                                </span>
                            @elseif($prod->status === 'draft')
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-slate-100 text-slate-600 border border-slate-200 uppercase">
                                    Bản nháp
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-rose-50 text-rose-700 border border-rose-200 uppercase">
                                    Tạm ngưng
                                </span>
                            @endif
                        </td>

                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $prod->id) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Sửa sản phẩm">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>

                                <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này và toàn bộ các biến thể của nó không? Action này không thể hoàn tác!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Xóa sản phẩm">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400">Chưa có sản phẩm nào trong kho dữ liệu.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
