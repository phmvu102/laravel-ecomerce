<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    // 1. Hiển thị danh sách Thuộc tính kèm các Giá trị của nó
    public function index()
    {
        // Eager loading 'values' để tránh lỗi N+1 query
        $attributes = Attribute::with('values')->latest()->get();
        return view('admin.attributes.index', compact('attributes'));
    }

    // 2. Lưu thuộc tính mới (ví dụ: Màu sắc, Kích thước)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
            'code' => 'nullable|string|max:255|unique:attributes,code',
        ]);

        // Nếu admin không nhập code, tự tạo code dựa trên tên (Ví dụ: Màu sắc -> mau-sac)
        $code = $request->code ? Str::slug($request->code) : Str::slug($request->name);

        Attribute::create([
            'name' => $request->name,
            'code' => $code,
        ]);

        return redirect()->back()->with('success', 'Thêm thuộc tính thành công!');
    }

    // 3. Xử lý Xóa thuộc tính (Sẽ xóa luôn tất cả các giá trị thuộc về nó nhờ CASCADE trong DB)
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->back()->with('success', 'Xóa thuộc tính và các giá trị liên quan thành công!');
    }

    // ==========================================
    // PHẦN LOGIC XỬ LÝ GIÁ TRỊ THUỘC TÍNH (VALUES)
    // ==========================================

    // 4. Thêm giá trị cho một thuộc tính cụ thể
    public function storeValue(Request $request, Attribute $attribute)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'extra_value' => 'nullable|string|max:255', // Phù hợp để lưu mã màu hex như #FFFFFF nếu là màu sắc
        ]);

        $attribute->values()->create([
            'value' => $request->value,
            'extra_value' => $request->extra_value,
        ]);

        return redirect()->back()->with('success', 'Thêm giá trị "' . $request->value . '" thành công!');
    }

    // 5. Xóa một giá trị thuộc tính
    public function destroyValue(AttributeValue $value)
    {
        $valueName = $value->value;
        $value->delete();
        return redirect()->back()->with('success', 'Đã xóa giá trị "' . $valueName . '"!');
    }

    // 6. Giao diện chỉnh sửa thuộc tính
    public function edit(Attribute $attribute)
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    // 7. Xử lý lưu cập nhật thuộc tính vào Database
    public function update(Request $request, Attribute $attribute)
    {
        // Validation: Cho phép giữ nguyên tên/code cũ của chính nó, nhưng không được trùng với thuộc tính khác
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name,' . $attribute->id,
            'code' => 'required|string|max:255|unique:attributes,code,' . $attribute->id,
        ]);

        $attribute->update([
            'name' => $request->name,
            'code' => Str::slug($request->code), // Tự động đưa về dạng slug chuẩn hóa cho lập trình (ví dụ: Mau Sac -> mau-sac)
        ]);

        return redirect()->route('admin.attributes.index')->with('success', 'Cập nhật thuộc tính thành công!');
    }
}
