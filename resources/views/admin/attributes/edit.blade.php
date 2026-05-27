@extends('layouts.admin')

@section('page-title', 'Chỉnh sửa Thuộc tính')

@section('content')
<div class="max-w-xl mx-auto">

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.attributes.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h3 class="text-lg font-bold text-slate-800">
                Sửa thuộc tính: <span class="text-indigo-600">{{ $attribute->name }}</span>
            </h3>
        </div>

        @if ($errors->any())
            <div class="bg-rose-50 text-rose-600 p-4 rounded-xl text-sm mb-5 border border-rose-100">
                <p class="font-semibold mb-1">Đã xảy ra lỗi vui lòng kiểm tra lại:</p>
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.attributes.update', $attribute->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT') <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tên thuộc tính</label>
                <input type="text" name="name" value="{{ old('name', $attribute->name) }}" required placeholder="Ví dụ: Màu sắc, Kích thước" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Mã định danh (Code)</label>
                <input type="text" name="code" value="{{ old('code', $attribute->code) }}" required placeholder="Ví dụ: color, size" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                <p class="text-xs text-slate-400 mt-1.5">Mã định danh dùng để hệ thống nhận diện lập trình (ví dụ hệ thống tự vẽ chấm màu tròn khi code là <code class="bg-slate-100 px-1 py-0.5 rounded text-slate-600 font-mono">color</code>).</p>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <a href="{{ route('admin.attributes.index') }}" class="flex-1 bg-slate-100 text-slate-700 font-medium py-2.5 rounded-xl hover:bg-slate-200 transition text-center text-sm">
                    Hủy bỏ
                </a>
                <button type="submit" class="flex-1 bg-indigo-600 text-white font-medium py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-sm text-sm flex items-center justify-center gap-1">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Cập nhật thay đổi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
