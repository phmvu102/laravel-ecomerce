@extends('layouts.admin')

@section('page-title', 'Thêm Sản phẩm Mới')

@section('content')
<div class="max-w-6xl mx-auto" x-data="productForm()">

    <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-6">
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

            <div class="lg:col-span-2 space-y-6">

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
                                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs space-y-3 min-w-[500px]">
                                    <div class="flex justify-between items-center bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                        <span class="text-xs font-bold text-indigo-700" x-text="variant.label || 'Mặc định'"></span>
                                        <input type="hidden" :name="`variants[${vIdx}][attribute_values]`" :value="variant.ids.join(',')">
                                    </div>

                                    <div class="grid grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Mã SKU <span class="text-red-500">*</span></label>
                                            <input type="text" :name="`variants[${vIdx}][sku]`" x-model="variant.sku" required class="w-full rounded-lg border-slate-200 text-xs py-1">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Giá bán lẻ (đ) <span class="text-red-500">*</span></label>
                                            <input type="number" :name="`variants[${vIdx}][price]`" x-model="variant.price" required class="w-full rounded-lg border-slate-200 text-xs py-1">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Giá Sale (đ - Tùy chọn)</label>
                                            <input type="number" :name="`variants[${vIdx}][sale_price]`" x-model="variant.sale_price" class="w-full rounded-lg border-slate-200 text-xs py-1">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-4 gap-2 border-t border-dashed border-slate-100 pt-2 text-[11px]">
                                        <div>
                                            <label class="block text-slate-400 mb-0.5">Cân nặng (g)</label>
                                            <input type="number" :name="`variants[${vIdx}][weight]`" x-model="variant.weight" placeholder="gram" class="w-full rounded-lg border-slate-200 text-xs py-1">
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
        productType: 'single', // Mặc định là sản phẩm đơn giản
        specs: [{ key: '', value: '' }], // Cấu hình thông số kỹ thuật ban đầu
        generatedVariants: [], // Chứa danh sách các dòng biến thể được sinh ra tự động

        init() {
            // Lần đầu chạy, tự tạo sẵn 1 dòng biến thể mặc định cho sản phẩm đơn giản
            this.updateCombinations();
            // Theo dõi sự thay đổi của loại sản phẩm để tính toán lại tổ hợp giá trị
            this.$watch('productType', value => this.updateCombinations());
        },

        addSpec() {
            this.specs.push({ key: '', value: '' });
        },

        removeSpec(index) {
            this.specs.splice(index, 1);
        },

        updateCombinations() {
            // NẾU LÀ SẢN PHẨM ĐƠN GIẢN
            if (this.productType === 'single') {
                this.generatedVariants = [{
                    label: 'Phiên bản mặc định',
                    ids: [],
                    sku: 'SKU-' + Math.floor(Math.random() * 100000),
                    price: 0,
                    sale_price: '',
                    weight: 0,
                    length: 0,
                    width: 0,
                    height: 0
                }];
                return;
            }

            // NẾU LÀ SẢN PHẨM BIẾN THỂ: Gom nhóm dữ liệu các ô checkbox đã tích chọn
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

            // Thuật toán tích Descartes tính toán tất cả tổ hợp có thể xảy ra
            let combinations = cartesianProduct(groupArrays);

            // Đổ danh sách tổ hợp tính được ra màn hình
            this.generatedVariants = combinations.map(combo => {
                let label = combo.map(c => c.name).join(' / ');
                let ids = combo.map(c => c.id);
                // Đối với sản phẩm Biến thể (Variable) nằm ở đoạn map ma trận Descartes
                return {
                    label: label,
                    ids: ids,
                    sku: 'SKU-' + ids.join('-') + '-' + Math.floor(Math.random() * 1000),
                    price: 0,
                    sale_price: '',
                    weight: 0,
                    length: 0,
                    width: 0,
                    height: 0
                };
            });
        }
    }
}

// Hàm hỗ trợ tính toán ma trận Tích Descartes (Cartesian Product)
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
