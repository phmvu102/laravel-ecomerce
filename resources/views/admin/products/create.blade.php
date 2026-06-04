@extends('layouts.admin')

@section('page-title', 'Thêm Sản phẩm Mới')

@section('content')
<div class="max-w-6xl mx-auto" x-data="productForm()">

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-600 p-4 rounded-xl text-sm">
                <p class="font-bold mb-1">Vui lòng kiểm tra lại dữ liệu nhập vào:</p>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- CỘT TRÁI (THÔNG TIN CHÍNH) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Khối 1: Thông tin chung --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i data-lucide="package" class="w-5 h-5 text-indigo-600"></i> Thông tin chung
                    </h3>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tên sản phẩm <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-2xs">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Mô tả ngắn</label>
                        <textarea name="short_description" rows="2" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-2xs" placeholder="Tóm tắt ngắn gọn về sản phẩm..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Mô tả chi tiết</label>
                        <textarea name="description" rows="6" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-2xs" placeholder="Nội dung giới thiệu chi tiết sản phẩm..."></textarea>
                    </div>
                </div>

                {{-- Khối 2: QUẢN LÝ HÌNH ẢNH SẢN PHẨM --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-6">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i data-lucide="image" class="w-5 h-5 text-indigo-600"></i> Hình ảnh sản phẩm
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- A. Ảnh đại diện chính (Thumbnail) --}}
                        <div class="md:col-span-1 border-r border-slate-100 pr-0 md:pr-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Ảnh đại diện <span class="text-red-500">*</span></label>

                            <div class="relative group border-2 border-dashed border-slate-200 rounded-xl p-2 flex flex-col items-center justify-center min-h-[160px] bg-slate-50 hover:bg-slate-100/50 transition">
                                <template x-if="!thumbnailUrl">
                                    <div class="text-center space-y-1 p-4 cursor-pointer" @click="$refs.thumbnailInput.click()">
                                        <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-400 mx-auto"></i>
                                        <p class="text-xs font-medium text-slate-600">Chọn ảnh chính</p>
                                        <p class="text-[10px] text-slate-400">JPEG, PNG, WEBP (Max 2MB)</p>
                                    </div>
                                </template>

                                <template x-if="thumbnailUrl">
                                    <div class="relative w-full h-full min-h-[140px] flex items-center justify-center">
                                        <img :src="thumbnailUrl" class="max-h-[140px] rounded-lg object-contain shadow-2xs">
                                        <button type="button" @click="removeThumbnail()" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full p-1 shadow-md hover:bg-rose-600 transition">
                                            <i data-lucide="x" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                </template>

                                <input type="file" x-ref="thumbnailInput" name="thumbnail" accept="image/*" class="hidden" @change="previewThumbnail">
                            </div>
                        </div>

                        {{-- B. Thư viện ảnh chi tiết (Gallery) --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Thư viện ảnh phụ (Gallery)</label>

                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                <div @click="$refs.galleryInput.click()" class="border-2 border-dashed border-slate-200 rounded-xl flex flex-col items-center justify-center p-4 bg-slate-50 hover:bg-slate-100 transition cursor-pointer min-h-[90px]">
                                    <i data-lucide="plus" class="w-5 h-5 text-slate-400 mb-1"></i>
                                    <span class="text-[11px] font-medium text-slate-500">Thêm ảnh</span>
                                    <input type="file" x-ref="galleryInput" name="images[]" accept="image/*" multiple class="hidden" @change="previewGallery">
                                </div>

                                <template x-for="(img, index) in galleryUrls" :key="index">
                                    <div class="relative border border-slate-200 rounded-xl p-1 bg-white flex items-center justify-center aspect-square group">
                                        <img :src="img" class="max-h-full max-w-full rounded-lg object-contain">
                                        <button type="button" @click="removeGalleryItem(index)" class="absolute -top-1.5 -right-1.5 bg-slate-800/80 text-white rounded-full p-1 shadow-xs opacity-90 hover:bg-rose-600 transition">
                                            <i data-lucide="x" class="w-2.5 h-2.5"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Khối 3: Thông số kỹ thuật --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i data-lucide="cpu" class="w-5 h-5 text-indigo-600"></i> Thông số kỹ thuật (Specifications)
                    </h3>

                    <div class="space-y-2">
                        <template x-for="(spec, index) in specs" :key="index">
                            <div class="flex gap-2 items-center">
                                <input type="text" name="spec_keys[]" x-model="spec.key" placeholder="Tên thông số (e.g., Chip, RAM)" class="flex-1 rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <input type="text" name="spec_values[]" x-model="spec.value" placeholder="Giá trị (e.g., Apple A17, 8GB)" class="flex-1 rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <button type="button" @click="removeSpec(index)" class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-xl transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="addSpec()" class="text-xs text-indigo-600 hover:text-indigo-700 font-semibold flex items-center gap-1 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100 transition">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Thêm dòng thông số
                    </button>
                </div>

                {{-- Khối 4: Phân loại & Cấu hình giá --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <i data-lucide="layers" class="w-5 h-5 text-indigo-600"></i> Phân loại & Cấu hình giá
                        </h3>

                        <select x-model="productType" class="rounded-xl border-slate-200 text-xs font-semibold bg-slate-50 text-slate-700 shadow-2xs">
                            <option value="single">Sản phẩm đơn giản</option>
                            <option value="variable">Sản phẩm có biến thể</option>
                        </select>
                    </div>

                    <div x-show="productType === 'variable'" class="space-y-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bước 1: Chọn các giá trị thuộc tính áp dụng</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($attributes as $attr)
                                <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-2xs">
                                    <span class="block text-sm font-bold text-slate-800 mb-2">{{ $attr->name }}</span>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($attr->values as $val)
                                            <label class="inline-flex items-center gap-1.5 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-lg text-xs cursor-pointer select-none hover:bg-slate-100 transition">
                                                <input type="checkbox" value="{{ $val->id }}" data-name="{{ $val->value }}" data-attr="{{ $attr->name }}" @change="updateCombinations()" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 attribute-checkbox">
                                                <span>{{ $val->value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider" x-text="productType === 'variable' ? 'Bước 2: Cấu hình chi tiết từng biến thể' : 'Cấu hình giá & thông số kho hàng'"></p>

                        <div class="space-y-3 overflow-x-auto">
                            <template x-for="(variant, vIdx) in generatedVariants" :key="vIdx">
                                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs space-y-3 min-w-[650px]">
                                    <div class="flex justify-between items-center bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                        <span class="text-xs font-bold text-indigo-700" x-text="variant.label || 'Mặc định'"></span>
                                        <input type="hidden" :name="`variants[${vIdx}][attribute_values]`" :value="variant.ids.join(',')">
                                    </div>

                                    {{-- Bố cục Grid nâng lên thành 5 cột để chèn Số lượng kho --}}
                                    <div class="grid grid-cols-5 gap-3 items-end">
                                        {{-- Ô 1: Ảnh biến thể --}}
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

                                                <input type="file" :name="`variants[${vIdx}][image]`" accept="image/*" class="hidden variant-file-input"
                                                       @change="let file = $event.target.files[0]; if(file) variant.imagePreview = URL.createObjectURL(file)">
                                            </div>
                                        </div>

                                        {{-- Ô 2: Mã SKU --}}
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Mã SKU <span class="text-red-500">*</span></label>
                                            <input type="text" :name="`variants[${vIdx}][sku]`" x-model="variant.sku" required class="w-full rounded-lg border-slate-200 text-xs py-1">
                                        </div>

                                        {{-- Ô 3: Giá bán lẻ --}}
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Giá bán (đ) <span class="text-red-500">*</span></label>
                                            <input type="number" :name="`variants[${vIdx}][price]`" x-model="variant.price" required class="w-full rounded-lg border-slate-200 text-xs py-1">
                                        </div>

                                        {{-- Ô 4: Giá Sale --}}
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Giá Sale (đ)</label>
                                            <input type="number" :name="`variants[${vIdx}][sale_price]`" x-model="variant.sale_price" class="w-full rounded-lg border-slate-200 text-xs py-1">
                                        </div>

                                        {{-- Ô 5: MỚI BỔ SUNG - Số lượng kho hàng --}}
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Số lượng kho <span class="text-red-500">*</span></label>
                                            <input type="number" :name="`variants[${vIdx}][stock]`" x-model="variant.stock" min="0" required class="w-full rounded-lg border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500 text-xs py-1 font-semibold text-slate-800 bg-indigo-50/30">
                                        </div>
                                    </div>

                                    {{-- Phần kích thước vận chuyển --}}
                                    <div class="grid grid-cols-4 gap-2 border-t border-dashed border-slate-100 pt-2 text-[11px]">
                                        <div>
                                            <label class="block text-slate-400 mb-0.5">Cân nặng (g)</label>
                                            <input type="number" :name="`variants[${vIdx}][weight]`" x-model="variant.weight" class="w-full rounded-lg border-slate-200 text-xs py-1">
                                        </div>
                                        <div>
                                            <label class="block text-slate-400 mb-0.5">Dài (cm)</label>
                                            <input type="number" :name="`variants[${vIdx}][length]`" x-model="variant.length" class="w-full rounded-lg border-slate-200 text-xs py-1">
                                        </div>
                                        <div>
                                            <label class="block text-slate-400 mb-0.5">Rộng (cm)</label>
                                            <input type="number" :name="`variants[${vIdx}][width]`" x-model="variant.width" class="w-full rounded-lg border-slate-200 text-xs py-1">
                                        </div>
                                        <div>
                                            <label class="block text-slate-400 mb-0.5">Cao (cm)</label>
                                            <input type="number" :name="`variants[${vIdx}][height]`" x-model="variant.height" class="w-full rounded-lg border-slate-200 text-xs py-1">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>

            {{-- CỘT PHẢI --}}
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <h3 class="text-sm font-bold text-slate-800">Trạng thái đăng tải</h3>
                    <div>
                        <select name="status" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-2xs">
                            <option value="active">Kích hoạt (Mở bán)</option>
                            <option value="draft">Bản nháp (Draft)</option>
                            <option value="inactive">Tạm ngưng (Inactive)</option>
                        </select>
                    </div>

                    <label class="flex items-center gap-2 bg-slate-50 p-3 rounded-xl border border-slate-100 select-none cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700">Đánh dấu Sản phẩm Nổi bật</span>
                    </label>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-sm flex items-center justify-center gap-2 text-sm">
                            <i data-lucide="save" class="w-4 h-4"></i> Lưu sản phẩm vào hệ thống
                        </button>

                        <a href="{{ route('admin.products.index') }}">Quay lại</a>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                    <h3 class="text-sm font-bold text-slate-800">Liên kết phân loại</h3>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Danh mục sản phẩm <span class="text-red-500">*</span></label>
                        <select name="category_id" required class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Thương hiệu hãng <span class="text-red-500">*</span></label>
                        <select name="brand_id" required class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Chọn thương hiệu --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function productForm() {
    return {
        productType: 'single',
        specs: [{ key: '', value: '' }],
        generatedVariants: [],
        thumbnailUrl: null,
        galleryUrls: [],

        init() {
            this.updateCombinations();
            this.$watch('productType', value => this.updateCombinations());
        },

        previewThumbnail(event) {
            const file = event.target.files[0];
            if (file) {
                this.thumbnailUrl = URL.createObjectURL(file);
            }
        },
        removeThumbnail() {
            this.thumbnailUrl = null;
            this.$refs.thumbnailInput.value = '';
        },

        previewGallery(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                this.galleryUrls.push(URL.createObjectURL(file));
            });
        },
        removeGalleryItem(index) {
            this.galleryUrls.splice(index, 1);
        },

        addSpec() {
            this.specs.push({ key: '', value: '' });
        },
        removeSpec(index) {
            this.specs.splice(index, 1);
        },

        updateCombinations() {
            if (this.productType === 'single') {
                this.generatedVariants = [{
                    label: 'Phiên bản mặc định',
                    ids: [],
                    sku: 'SKU-' + Math.floor(Math.random() * 100000),
                    price: 0,
                    sale_price: '',
                    stock: 0, // Bổ sung mặc định sản phẩm đơn giản
                    imagePreview: null,
                    weight: 0,
                    length: 0,
                    width: 0,
                    height: 0
                }];
                return;
            }

            let checkboxes = document.querySelectorAll('.attribute-checkbox:checked');
            let groups = {};

            checkboxes.forEach(cb => {
                let attrName = cb.getAttribute('data-attr');
                if (!groups[attrName]) groups[attrName] = [];
                groups[attrName].push({
                    id: cb.value,
                    name: cb.getAttribute('data-name')
                });
            });

            let groupArrays = Object.values(groups);

            if (groupArrays.length === 0) {
                this.generatedVariants = [];
                return;
            }

            let combinations = cartesianProduct(groupArrays);
            let oldVariants = this.generatedVariants;

            this.generatedVariants = combinations.map(combo => {
                let label = combo.map(c => c.name).join(' / ');
                let ids = combo.map(c => c.id);
                let currentComboIdStr = ids.join(',');

                let existing = oldVariants.find(v => v.ids.join(',') === currentComboIdStr);

                if (existing) {
                    return existing; // Giữ lại số lượng đã nhập nếu biến thể đã có sẵn trước đó
                }

                return {
                    label: label,
                    ids: ids,
                    sku: 'SKU-' + ids.join('-') + '-' + Math.floor(Math.random() * 1000),
                    price: 0,
                    sale_price: '',
                    stock: 0, // Khởi tạo số lượng mặc định bằng 0 cho biến thể mới
                    imagePreview: null,
                    weight: 0,
                    length: 0,
                    width: 0,
                    height: 0
                };
            });
        }
    }
}

function cartesianProduct(arrays) {
    return arrays.reduce((a, b) => {
        let ret = [];
        a.forEach(a_item => {
            b.forEach(b_item => {
                ret.push((Array.isArray(a_item) ? a_item : [a_item]).concat([b_item]));
            });
        });
        return ret;
    });
}
</script>
@endsection
