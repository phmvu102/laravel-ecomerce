@extends('layouts.admin')

@section('page-title', 'Thuộc tính sản phẩm')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ activeAttribute: null }">

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 h-fit">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i data-lucide="sliders" class="w-5 h-5 text-indigo-600"></i>
                Thêm thuộc tính mới
            </h3>

            <form action="{{ route('admin.attributes.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tên thuộc tính</label>
                    <input type="text" name="name" required placeholder="Ví dụ: Màu sắc, Kích thước, Dung lượng" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Mã định danh (Code - Tùy chọn)</label>
                    <input type="text" name="code" placeholder="Ví dụ: color, size, ram" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <p class="text-xs text-slate-400 mt-1">Bỏ trống hệ thống sẽ tự sinh tự động theo tên.</p>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white font-medium py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-sm flex items-center justify-center gap-2 text-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Tạo thuộc tính
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i data-lucide="list" class="w-5 h-5 text-indigo-600"></i>
                        Các thuộc tính hiện có
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 text-xs font-semibold uppercase tracking-wider">
                                <th class="p-4">Tên thuộc tính</th>
                                <th class="p-4">Mã (Code)</th>
                                <th class="p-4">Các giá trị hiện tại</th>
                                <th class="p-4 text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            @forelse($attributes as $attr)
                                <tr class="hover:bg-slate-50/80 transition" :class="activeAttribute === {{ $attr->id }} ? 'bg-indigo-50/40 hover:bg-indigo-50/60' : ''">
                                    <td class="p-4 font-semibold text-slate-900">{{ $attr->name }}</td>
                                    <td class="p-4"><code class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-mono">{{ $attr->code }}</code></td>
                                    <td class="p-4">
                                        <div class="flex flex-wrap gap-1.5 max-w-xs">
                                            @forelse($attr->values as $val)
                                                <span class="inline-flex items-center gap-1 bg-white border border-slate-200 text-slate-600 text-xs px-2 py-0.5 rounded-md shadow-xs">
                                                    @if($attr->code == 'color' || $attr->code == 'mau-sac')
                                                        <span class="w-2.5 h-2.5 rounded-full border border-slate-300" style="background-color: {{ $val->extra_value ?? '#ccc' }}"></span>
                                                    @endif
                                                    {{ $val->value }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-slate-400 italic">Chưa có giá trị</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                @click="activeAttribute = (activeAttribute === {{ $attr->id }} ? null : {{ $attr->id }})"
                                                class="px-3 py-1.5 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-xl hover:bg-indigo-100 transition text-xs font-medium flex items-center gap-1"
                                            >
                                                <i data-lucide="settings-2" class="w-3.5 h-3.5"></i>
                                                Giá trị ({{ $attr->values->count() }})
                                            </button>

                                            <a href="{{ route('admin.attributes.edit', $attr->id) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Sửa tên thuộc tính">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </a>

                                            <form action="{{ route('admin.attributes.destroy', $attr->id) }}" method="POST" onsubmit="return confirm('Xóa thuộc tính này sẽ xóa sạch tất cả các giá trị con của nó! Bạn chắc chắn chứ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <tr x-show="activeAttribute === {{ $attr->id }}" x-transition style="display: none;">
                                    <td colspan="4" class="p-4 bg-slate-50 border-t border-b border-indigo-100">
                                        <div class="bg-white p-4 rounded-xl border border-indigo-100 shadow-xs grid grid-cols-1 md:grid-cols-3 gap-6">

                                            <div class="border-b md:border-b-0 md:border-r border-slate-100 pb-4 md:pb-0 md:pr-6">
                                                <h4 class="text-xs font-bold uppercase text-slate-500 tracking-wider mb-3">Thêm giá trị cho "{{ $attr->name }}"</h4>
                                                <form action="{{ route('admin.attributes.storeValue', $attr->id) }}" method="POST" class="space-y-3">
                                                    @csrf
                                                    <div>
                                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tên giá trị</label>
                                                        <input type="text" name="value" required placeholder="Ví dụ: Đỏ, 128GB, XL" class="w-full rounded-lg border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 shadow-xs">
                                                    </div>

                                                    @if($attr->code == 'color' || $attr->code == 'mau-sac')
                                                    <div>
                                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Mã màu Hex (Tùy chọn)</label>
                                                        <div class="flex gap-2">
                                                            <input type="text" name="extra_value" placeholder="#FF0000" class="w-full rounded-lg border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500 shadow-xs">
                                                        </div>
                                                    </div>
                                                    @endif

                                                    <button type="submit" class="w-full bg-slate-800 text-white font-medium py-1.5 rounded-lg hover:bg-slate-900 transition text-xs flex items-center justify-center gap-1">
                                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                        Lưu giá trị
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="md:col-span-2">
                                                <h4 class="text-xs font-bold uppercase text-slate-500 tracking-wider mb-3">Danh sách giá trị hiện tại</h4>
                                                <div class="flex flex-wrap gap-2">
                                                    @forelse($attr->values as $val)
                                                        <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-200 pl-2.5 pr-1 py-1 rounded-xl text-xs text-slate-700 shadow-xs">
                                                            @if($attr->code == 'color' || $attr->code == 'mau-sac')
                                                                <span class="w-3 h-3 rounded-full border border-slate-300 shadow-xs" style="background-color: {{ $val->extra_value ?? '#ccc' }}"></span>
                                                            @endif
                                                            <span class="font-medium">{{ $val->value }}</span>

                                                            <form action="{{ route('admin.attributes.destroyValue', $val->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="w-5 h-5 rounded-md text-slate-400 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition">
                                                                    <i data-lucide="x" class="w-3 h-3"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @empty
                                                        <p class="text-xs text-slate-400 italic py-2">Thuộc tính này chưa được thiết lập giá trị nào.</p>
                                                    @endforelse
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-sm text-slate-400 text-center py-6">
                                        Chưa có thuộc tính nào được tạo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
