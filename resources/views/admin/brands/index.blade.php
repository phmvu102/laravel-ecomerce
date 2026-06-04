@extends('layouts.admin')

@section('title', 'Quản lý thương hiệu')
@section('page-title', 'Quản lý thương hiệu')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Danh sách thương hiệu
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Quản lý toàn bộ thương hiệu trong hệ thống
            </p>
        </div>

        <button
            type="button"
            onclick="openCreateModal()"
            class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-3 rounded-2xl transition shadow-sm"
        >
            <i data-lucide="plus" class="w-5 h-5"></i>
            Thêm thương hiệu
        </button>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">

        {{-- Top Bar --}}
        <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">

            <div>
                <h3 class="text-lg font-bold text-slate-800">
                    Danh sách dữ liệu
                </h3>

                <p class="text-sm text-slate-500">
                    Tổng cộng: {{ $brands->total() }} thương hiệu
                </p>
            </div>

            <form
                action="{{ route('admin.brands.index') }}"
                method="GET"
                class="flex items-center gap-2"
            >
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Tìm thương hiệu..."
                        class="w-72 pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    >
                </div>

                <button
                    type="submit"
                    class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition"
                >
                    Tìm kiếm
                </button>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            ID
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Logo
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Tên thương hiệu
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Slug
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Trạng thái
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                            Hành động
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($brands as $brand)
                        <tr class="hover:bg-slate-50 transition">

                            {{-- ID --}}
                            <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                                #{{ $brand->id }}
                            </td>

                            {{-- Logo --}}
                            <td class="px-6 py-4">
                                @if($brand->logo)
                                    <img
                                        src="{{ asset('storage/' . $brand->logo) }}"
                                        alt="{{ $brand->name }}"
                                        class="w-16 h-16 rounded-2xl object-contain border border-slate-200 bg-white p-2"
                                    >
                                @else
                                    <div class="w-16 h-16 rounded-2xl border border-dashed border-slate-300 flex items-center justify-center bg-slate-50">
                                        <i data-lucide="image" class="w-6 h-6 text-slate-300"></i>
                                    </div>
                                @endif
                            </td>

                            {{-- Name --}}
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">
                                    {{ $brand->name }}
                                </div>

                                <div class="text-xs text-slate-400 mt-1">
                                    {{ Str::limit($brand->description, 50) }}
                                </div>
                            </td>

                            {{-- Slug --}}
                            <td class="px-6 py-4">
                                <code class="bg-slate-100 text-slate-700 text-xs px-2 py-1 rounded-lg">
                                    {{ $brand->slug }}
                                </code>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                @if($brand->status === 'active')
                                    <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-full">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        Hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-rose-100 text-rose-700 text-xs font-bold px-3 py-1.5 rounded-full">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        Tạm khóa
                                    </span>
                                @endif
                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    <button
                                        type="button"
                                        onclick='openEditModal(@json($brand))'
                                        class="w-10 h-10 rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition flex items-center justify-center"
                                    >
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>

                                    <form
                                        action="{{ route('admin.brands.destroy', $brand->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa thương hiệu này?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="w-10 h-10 rounded-xl border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100 transition flex items-center justify-center"
                                        >
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                        <i data-lucide="database" class="w-8 h-8 text-slate-400"></i>
                                    </div>

                                    <h3 class="text-base font-bold text-slate-700">
                                        Không có dữ liệu
                                    </h3>

                                    <p class="text-sm text-slate-400 mt-1">
                                        Chưa có thương hiệu nào trong hệ thống
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($brands->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $brands->appends(request()->query())->links() }}
            </div>
        @endif

    </div>
</div>

{{-- MODAL --}}
<div
    id="brandModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4"
>
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
            <div>
                <h3 id="modalTitle" class="text-xl font-bold text-slate-800">
                    Thêm thương hiệu
                </h3>

                <p class="text-sm text-slate-500 mt-1">
                    Cấu hình thông tin thương hiệu
                </p>
            </div>

            <button
                type="button"
                onclick="closeModal()"
                class="w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center transition"
            >
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        {{-- Form --}}
        <form
            id="brandForm"
            action="{{ route('admin.brands.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6 space-y-5" style="max-height: 80vh; overflow-y: auto;"
        >
            @csrf

            <div id="methodInput"></div>

            {{-- Name --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Tên thương hiệu <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    id="brand_name"
                    required
                    class="w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    placeholder="Ví dụ: Apple, Samsung..."
                >
            </div>

            {{-- Slug --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Slug
                </label>

                <input
                    type="text"
                    name="slug"
                    id="brand_slug"
                    class="w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    placeholder="Tự động tạo nếu bỏ trống"
                >
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Mô tả
                </label>

                <textarea
                    name="description"
                    id="brand_description"
                    rows="4"
                    class="w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    placeholder="Nhập mô tả thương hiệu..."
                ></textarea>
            </div>

            {{-- Logo --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Logo thương hiệu
                </label>

                <input
                    type="file"
                    name="logo"
                    id="brand_logo"
                    accept="image/*"
                    onchange="previewImage(this)"
                    class="w-full border-slate-200 text-sm"
                >

                <div
                    id="logoPreviewContainer"
                    class="mt-4 hidden" style="width: 150px; height: 150px;"
                >
                    <img
                        id="logoPreview"
                        src=""
                        class="w-24 h-24 rounded-2xl border border-slate-200 object-contain p-2 bg-white"
                    >
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Trạng thái
                </label>

                <select
                    name="status"
                    id="brand_status"
                    class="w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                >
                    <option value="active">
                        Hoạt động
                    </option>

                    <option value="inactive">
                        Tạm khóa
                    </option>
                </select>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button
                    type="button"
                    onclick="closeModal()"
                    class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition"
                >
                    Hủy
                </button>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition shadow-sm"
                >
                    Lưu dữ liệu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('brandModal');
    const form = document.getElementById('brandForm');
    const methodInput = document.getElementById('methodInput');

    function openCreateModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('modalTitle').innerText = 'Thêm thương hiệu';

        form.reset();
        form.action = "{{ route('admin.brands.store') }}";

        methodInput.innerHTML = '';

        document.getElementById('logoPreviewContainer').classList.add('hidden');

        setTimeout(() => lucide.createIcons(), 10);
    }

    function openEditModal(brand) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('modalTitle').innerText = 'Chỉnh sửa thương hiệu';

        form.action = `/admin/brands/${brand.id}`;

        methodInput.innerHTML = `
            <input type="hidden" name="_method" value="PUT">
        `;

        document.getElementById('brand_name').value = brand.name;
        document.getElementById('brand_slug').value = brand.slug ?? '';
        document.getElementById('brand_description').value = brand.description ?? '';
        document.getElementById('brand_status').value = brand.status;

        const previewContainer = document.getElementById('logoPreviewContainer');
        const previewImg = document.getElementById('logoPreview');

        if (brand.logo) {
            previewImg.src = `/storage/${brand.logo}`;
            previewContainer.classList.remove('hidden');
        } else {
            previewImg.src = '';
            previewContainer.classList.add('hidden');
        }

        setTimeout(() => lucide.createIcons(), 10);
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function previewImage(input) {
        const file = input.files[0];

        if (!file) return;

        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('logoPreview').src = e.target.result;
            document.getElementById('logoPreviewContainer').classList.remove('hidden');
        };

        reader.readAsDataURL(file);
    }

    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    lucide.createIcons();
</script>
@endpush
