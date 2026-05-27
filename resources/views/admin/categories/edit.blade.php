@extends('layouts.admin')

@section('page-title', 'Chỉnh sửa Danh mục')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i data-lucide="edit-3" class="w-5 h-5 text-indigo-600"></i>
            Cập nhật danh mục: <span class="text-indigo-600">{{ $category->name }}</span>
        </h3>

        @if ($errors->any())
            <div class="bg-rose-50 text-rose-600 p-3 rounded-xl text-sm mb-4">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT') <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tên danh mục</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Thuộc danh mục cha</label>
                <select name="parent_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <option value="">-- Không có (Là danh mục gốc) --</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Thời gian đổi trả hàng (ngày)</label>
                <input type="number" name="return_duration_days" value="{{ old('return_duration_days', $category->return_duration_days) }}" min="0" required class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Trạng thái hoạt động</label>
                <select name="status" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Đang kích hoạt (Bật hiển thị)</option>
                    <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>Tạm khóa (Ẩn khỏi web)</option>
                </select>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('admin.categories.index') }}" class="flex-1 bg-slate-100 text-slate-700 font-medium py-2.5 rounded-xl hover:bg-slate-200 transition text-center text-sm">
                    Hủy bỏ
                </a>
                <button type="submit" class="flex-1 bg-indigo-600 text-white font-medium py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-sm text-sm">
                    Cập nhật ngay
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
