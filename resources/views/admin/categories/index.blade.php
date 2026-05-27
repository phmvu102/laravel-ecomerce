@extends('layouts.admin')

@section('page-title', 'Quản lý Danh mục')

@section('content')
<div class="max-w-7xl mx-auto">

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2 shadow-sm">
            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 h-fit">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-5 h-5 text-indigo-600"></i>
                Thêm danh mục mới
            </h3>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tên danh mục</label>
                    <input type="text" name="name" required placeholder="Ví dụ: Laptop Gaming" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Thuộc danh mục cha</label>
                    <select name="parent_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">-- Không có (Là danh mục gốc) --</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Thời gian đổi trả hàng (ngày)</label>
                    <input type="number" name="return_duration_days" value="7" min="0" required class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <p class="text-xs text-slate-400 mt-1">Chính sách bảo vệ người mua đổi size/lỗi.</p>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white font-medium py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-sm flex items-center justify-center gap-2 text-sm">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Lưu danh mục
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 lg:col-span-2">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i data-lucide="git-merge" class="w-5 h-5 text-indigo-600"></i>
                Cấu trúc hình cây danh mục
            </h3>

            <div class="divide-y divide-slate-100">
                @forelse($categories as $category)
                    <div class="py-3">
                        <div class="flex justify-between items-center bg-slate-50 border border-slate-100 p-3 rounded-xl hover:bg-slate-100/80 transition">
                            <div class="flex items-center gap-2">
                                <i data-lucide="folder" class="w-5 h-5 text-amber-500 fill-amber-100"></i>
                                <span class="font-semibold text-slate-800">{{ $category->name }}</span>
                                <span class="text-xs bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full">Đổi trả: {{ $category->return_duration_days }} ngày</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="p-1.5 bg-white text-blue-600 border border-slate-200 rounded-lg hover:bg-blue-50 transition" title="Sửa">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục cha này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-white text-red-600 border border-slate-200 rounded-lg hover:bg-red-50 transition" title="Xóa">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if($category->children->count() > 0)
                            <div class="ml-8 mt-2 space-y-2 border-l border-slate-200 pl-4">
                                @foreach($category->children as $child)
                                    <div class="flex justify-between items-center bg-white border border-slate-100 p-2.5 rounded-xl hover:border-slate-300 transition">
                                        <div class="flex items-center gap-2 text-sm text-slate-600">
                                            <i data-lucide="file-text" class="w-4 h-4 text-slate-400"></i>
                                            <span>{{ $child->name }}</span>
                                            <span class="text-[11px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">Đổi trả: {{ $child->return_duration_days }} ngày</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.categories.edit', $child->id) }}" class="p-1 text-blue-500 hover:text-blue-700 transition" title="Sửa">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Xóa danh mục con này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-red-500 hover:text-red-700 transition" title="Xóa">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-400 text-center py-6">Chưa có danh mục nào được tạo.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
