@extends('layouts.admin')

@section('page-title', 'Chỉnh sửa & Quản lý Biến thể')

@section('content')
@php
    $existingVariants = $product->variants->map(function($v) {
        return [
            'id' => $v->id,
            'unique_key' => 'old_' . $v->id,
            'label' => $v->attributeValues->isEmpty() ? 'Phiên bản mặc định' : $v->attributeValues->pluck('value')->implode(' / '),
            'ids' => $v->attributeValues->pluck('id')->toArray(),
            'sku' => $v->sku,
            'price' => (int)$v->price,
            'sale_price' => $v->sale_price ? (int)$v->sale_price : '',
            'stock' => (int)$v->stock,
            'imagePreview' => $v->image ? asset('storage/' . $v->image) : null,
            'weight' => (int)$v->weight,
            'length' => (int)$v->length,
            'width' => (int)$v->width,
            'height' => (int)$v->height,
            'is_new' => false
        ];
    });
@endphp

<div class="max-w-6xl mx-auto" x-data="productEditForm()">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.products.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h3 class="text-xl font-bold text-slate-800">Cấu hình nâng cao: <span class="text-indigo-600">{{ $product->name }}</span></h3>
    </div>

    {{-- LƯU Ý: Đã thêm enctype="multipart/form-data" để upload file ảnh --}}
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <template x-for="id in deletedVariants" :key="id">
            <input type="hidden" name="deleted_variants[]" :value="id">
        </template>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-600 p-4 rounded-xl text-sm">
                <p class="font-bold mb-1">Vui lòng kiểm tra lại dữ liệu nhập vào:</p>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Cột Trái --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Khối thông tin cơ bản --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i data-lucide="package" class="w-5 h-5 text-indigo-600"></i> Thông tin cơ bản
                    </h3>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tên sản phẩm <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Mô tả ngắn</label>
                        <textarea name="short_description" rows="2" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Mô tả chi tiết</label>
                        <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                {{-- Khối thông số kỹ thuật --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i data-lucide="cpu" class="w-5 h-5 text-indigo-600"></i> Thông số kỹ thuật
                    </h3>
                    <div class="space-y-2">
                        <template x-for="(spec, index) in specs" :key="index">
                            <div class="flex gap-2 items-center">
                                <input type="text" name="spec_keys[]" x-model="spec.key" placeholder="Tên thông số (Ví dụ: RAM)" class="flex-1 rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <input type="text" name="spec_values[]" x-model="spec.value" placeholder="Giá trị (Ví dụ: 8GB)" class="flex-1 rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <button type="button" @click="specs.splice(index, 1)" class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 rounded-xl">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="specs.push({ key: '', value: '' })" class="text-xs text-indigo-600 font-semibold flex items-center gap-1 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Thêm dòng thông số
                    </button>
                </div>

                {{-- Khối thuộc tính biến thể --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <i data-lucide="plus-circle" class="w-5 h-5 text-indigo-600"></i> Phát sinh thêm biến thể mới
                        </h3>
                        <button type="button" @click="generateNewCombinations()" class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3 py-1.5 rounded-xl transition shadow-xs flex items-center gap-1">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Sinh thêm biến thể mới
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                        @foreach($attributes as $attr)
                            <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-2xs">
                                <span class="block text-sm font-bold text-slate-800 mb-2">{{ $attr->name }}</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($attr->values as $val)
                                        <label class="inline-flex items-center gap-1.5 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-lg text-xs cursor-pointer hover:bg-slate-100 transition">
                                            <input type="checkbox" value="{{ $val->id }}" data-name="{{ $val->value }}" data-attr="{{ $attr->name }}" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 attribute-checkbox">
                                            <span>{{ $val->value }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Khối danh sách phiên bản --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i data-lucide="layers" class="w-5 h-5 text-indigo-600"></i> Danh sách cấu hình phiên bản
                    </h3>

                    <div class="space-y-4 overflow-x-auto">
                        <template x-for="(variant, index) in variants" :key="variant.unique_key">
                            <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-3 shadow-2xs relative min-w-[650px]">

                                <div class="flex justify-between items-center bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-800" x-text="variant.label"></span>
                                        <span x-show="!variant.is_new" class="bg-slate-200/80 text-slate-600 text-[10px] font-bold px-1.5 py-0.5 rounded-md">Hiện có</span>
                                        <span x-show="variant.is_new" class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-1.5 py-0.5 rounded-md">Mới thêm phát sinh</span>
                                    </div>

                                    <button type="button" @click="removeVariant(index, variant)" class="p-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Xóa phiên bản này">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>

                                <input type="hidden" :name="variant.is_new ? 'new_variants[' + index + '][attribute_values]' : 'variants[' + variant.id + '][attribute_values]'" :value="variant.ids.join(',')">

                                <div class="grid grid-cols-5 gap-3 items-end">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Ảnh biến thể</label>
                                        <div class="relative border border-slate-200 rounded-lg p-1 bg-slate-50 flex items-center justify-center h-9 cursor-pointer hover:bg-slate-100 transition"
                                             @click="$el.querySelector('.variant-file-input').click()">
                                            <template x-if="!variant.imagePreview">
                                                <div class="flex items-center gap-1 text-[10px] text-slate-400 font-medium">
                                                    <i data-lucide="image" class="w-3.5 h-3.5"></i> Chọn ảnh
                                                </div>
                                            </template>

                                            <template x-if="variant.imagePreview">
                                                <div class="w-full h-full flex items-center justify-between px-1">
                                                    <img :src="variant.imagePreview" class="h-full w-7 object-contain rounded">
                                                    <button type="button" @click.stop="variant.imagePreview = null; $el.closest('.relative').querySelector('.variant-file-input').value = ''" class="text-rose-500 hover:text-rose-700">
                                                        <i data-lucide="x" class="w-3 h-3"></i>
                                                    </button>
                                                </div>
                                            </template>

                                            <input type="file" :name="variant.is_new ? 'new_variants[' + index + '][image]' : 'variants[' + variant.id + '][image]'" accept="image/*" class="hidden variant-file-input"
                                                   @change="let file = $event.target.files[0]; if(file) variant.imagePreview = URL.createObjectURL(file)">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Mã SKU <span class="text-red-500">*</span></label>
                                        <input type="text" :name="variant.is_new ? 'new_variants[' + index + '][sku]' : 'variants[' + variant.id + '][sku]'" x-model="variant.sku" required class="w-full rounded-lg border-slate-200 text-xs py-1">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Giá bán lẻ (đ) <span class="text-red-500">*</span></label>
                                        <input type="number" :name="variant.is_new ? 'new_variants[' + index + '][price]' : 'variants[' + variant.id + '][price]'" x-model="variant.price" required class="w-full rounded-lg border-slate-200 text-xs py-1">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Giá Khuyến mãi (đ)</label>
                                        <input type="number" :name="variant.is_new ? 'new_variants[' + index + '][sale_price]' : 'variants[' + variant.id + '][sale_price]'" x-model="variant.sale_price" class="w-full rounded-lg border-slate-200 text-xs py-1">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Số lượng kho <span class="text-red-500">*</span></label>
                                        <input type="number" :name="variant.is_new ? 'new_variants[' + index + '][stock]' : 'variants[' + variant.id + '][stock]'" x-model="variant.stock" min="0" required class="w-full rounded-lg border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500 text-xs py-1 font-semibold text-slate-800 bg-indigo-50/30">
                                    </div>
                                </div>

                                <div class="grid grid-cols-4 gap-2 border-t border-dashed border-slate-100 pt-2 text-[11px]">
                                    <div>
                                        <label class="block text-slate-400 mb-0.5">Cân nặng (g)</label>
                                        <input type="number" :name="variant.is_new ? 'new_variants[' + index + '][weight]' : 'variants[' + variant.id + '][weight]'" x-model="variant.weight" class="w-full rounded-lg border-slate-200 text-xs py-1">
                                    </div>
                                    <div>
                                        <label class="block text-slate-400 mb-0.5">Dài (cm)</label>
                                        <input type="number" :name="variant.is_new ? 'new_variants[' + index + '][length]' : 'variants[' + variant.id + '][length]'" x-model="variant.length" class="w-full rounded-lg border-slate-200 text-xs py-1">
                                    </div>
                                    <div>
                                        <label class="block text-slate-400 mb-0.5">Rộng (cm)</label>
                                        <input type="number" :name="variant.is_new ? 'new_variants[' + index + '][width]' : 'variants[' + variant.id + '][width]'" x-model="variant.width" class="w-full rounded-lg border-slate-200 text-xs py-1">
                                    </div>
                                    <div>
                                        <label class="block text-slate-400 mb-0.5">Cao (cm)</label>
                                        <input type="number" :name="variant.is_new ? 'new_variants[' + index + '][height]' : 'variants[' + variant.id + '][height]'" x-model="variant.height" class="w-full rounded-lg border-slate-200 text-xs py-1">
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="variants.length === 0" class="text-center py-6 text-sm text-slate-400">
                            Cảnh báo: Bạn đã xóa sạch tất cả phiên bản. Vui lòng bấm sinh thêm biến thể mới ở trên.
                        </div>
                    </div>
                </div>

            </div>

            {{-- Cột Phải --}}
            <div class="space-y-6">

                {{-- MỚI BỔ SUNG: Khối Quản lý ảnh đại diện --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                        <i data-lucide="image" class="w-4 h-4 text-indigo-600"></i> Ảnh đại diện sản phẩm
                    </h3>

                    <div class="space-y-3">
                        {{-- Khu vực hiển thị preview ảnh --}}
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-2 flex items-center justify-center bg-slate-50/50 min-h-[160px]">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="max-h-40 rounded-lg object-contain shadow-2xs">
                            </template>
                            <template x-if="!imagePreview">
                                <div class="text-center p-4 text-slate-400 text-xs">
                                    <i data-lucide="image-plus" class="w-8 h-8 mx-auto mb-1.5 text-slate-300"></i>
                                    Sản phẩm chưa có ảnh minh họa
                                </div>
                            </template>
                        </div>

                        {{-- Nút chọn tệp ảnh mới --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Thay đổi hình ảnh mới</label>
                            <input type="file" name="thumbnail" @change="previewImage($event)" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-xl p-1">
                        </div>
                    </div>
                </div>

                {{-- Trạng thái phát hành --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <h3 class="text-sm font-bold text-slate-800">Trạng thái phát hành</h3>
                    <div>
                        <select name="status" class="w-full rounded-xl border-slate-200 text-sm">
                            <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Kích hoạt (Mở bán)</option>
                            <option value="draft" {{ $product->status == 'draft' ? 'selected' : '' }}>Bản nháp (Draft)</option>
                            <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Tạm ngưng (Inactive)</option>
                        </select>
                    </div>

                    <label class="flex items-center gap-2 bg-slate-50 p-3 rounded-xl border border-slate-100 cursor-pointer select-none">
                        <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700">Sản phẩm Nổi bật</span>
                    </label>

                    <div class="pt-2 flex gap-2">
                        <a href="{{ route('admin.products.index') }}" class="flex-1 bg-slate-100 text-slate-700 font-semibold py-2 rounded-xl text-center hover:bg-slate-200 text-sm transition">Hủy</a>
                        <button type="submit" class="flex-1 bg-indigo-600 text-white font-semibold py-2 rounded-xl hover:bg-indigo-700 text-sm transition shadow-sm">
                            Cập nhật
                        </button>
                    </div>
                </div>

                {{-- Phân loại mối quan hệ --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <h3 class="text-sm font-bold text-slate-800">Phân loại mối quan hệ</h3>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Danh mục sản phẩm</label>
                        <select name="category_id" required class="w-full rounded-xl border-slate-200 text-sm">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Thương hiệu hãng</label>
                        <select name="brand_id" required class="w-full rounded-xl border-slate-200 text-sm">
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function productEditForm() {
    return {
        specs: (() => {
            let rawSpecs = @json($product->specifications);
            if (typeof rawSpecs === 'string') {
                try { rawSpecs = JSON.parse(rawSpecs); } catch(e) { rawSpecs = []; }
            }
            return (Array.isArray(rawSpecs) && rawSpecs.length > 0) ? rawSpecs : [{ key: '', value: '' }];
        })(),

        variants: @json($existingVariants),
        deletedVariants: [],
        selectedValues: [],

        // MỚI BỔ SUNG: Khởi tạo đường dẫn ảnh cũ từ cơ sở dữ liệu nếu có
        imagePreview: "{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : '' }}",

        init() {
            this.selectedValues = [];
        },

        // MỚI BỔ SUNG: Hàm xử lý tạo URL xem trước ảnh tức thì khi upload file mới
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                this.imagePreview = URL.createObjectURL(file);
            }
        },

        removeVariant(index, variant) {
            if (!confirm('Bạn có chắc chắn muốn xóa biến thể này?')) return;
            if (!variant.is_new && variant.id) {
                this.deletedVariants.push(variant.id);
            }
            this.variants.splice(index, 1);
            this.$nextTick(() => { lucide.createIcons(); });
        },

        generateNewCombinations() {
            let checkboxes = document.querySelectorAll('.attribute-checkbox:checked');
            let groups = {};

            checkboxes.forEach(cb => {
                let attrName = cb.dataset.attr;
                if (!groups[attrName]) groups[attrName] = [];
                groups[attrName].push({
                    id: parseInt(cb.value),
                    name: cb.dataset.name
                });
            });

            let groupArrays = Object.values(groups);
            if (groupArrays.length === 0) {
                alert('Vui lòng chọn thuộc tính');
                return;
            }

            let combinations = cartesianProduct(groupArrays);

            combinations.forEach(combo => {
                let ids = combo.map(c => c.id);
                let comboIds = [...ids].sort((a, b) => a - b).join(',');

                let exists = this.variants.some(v => {
                    return [...v.ids].sort((a, b) => a - b).join(',') === comboIds;
                });

                if (exists) return;

                this.variants.push({
                    unique_key: 'new_' + Date.now() + '_' + Math.random().toString(36).substring(2, 10),
                    id: null,
                    label: combo.map(c => c.name).join(' / '),
                    ids: ids,
                    sku: 'SKU-' + ids.join('-') + '-' + Math.floor(Math.random() * 1000),
                    price: 0,
                    sale_price: '',
                    stock: 0,
                    imagePreview: null,
                    weight: 0,
                    length: 0,
                    width: 0,
                    height: 0,
                    is_new: true
                });
            });

            this.$nextTick(() => { lucide.createIcons(); });
        }
    }
}

function cartesianProduct(arrays) {
    if (arrays.length === 0) return [];
    return arrays.reduce((accumulator, currentArray) => {
        let result = [];
        accumulator.forEach(accumulatorItem => {
            currentArray.forEach(currentItem => {
                result.push([].concat(accumulatorItem, currentItem));
            });
        });
        return result;
    }, [[]]);
}
</script>
@endpush
