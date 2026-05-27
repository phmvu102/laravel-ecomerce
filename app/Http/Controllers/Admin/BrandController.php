<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Http\Requests\BrandRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Hiển thị danh sách thương hiệu (kèm tìm kiếm & phân trang)
     */
    public function index(Request $request)
    {
        $query = Brand::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $brands = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Lưu thương hiệu mới vào database
     */
    public function store(BrandRequest $request)
    {
        $data = $request->validated();

        // Tự động tạo slug nếu người dùng không nhập
        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);

        // Xử lý upload ảnh logo
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Thêm thương hiệu thành công!');
    }

    /**
     * Cập nhật thông tin thương hiệu
     */
    public function update(BrandRequest $request, Brand $brand)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);

        // Xử lý cập nhật ảnh logo mới
        if ($request->hasFile('logo')) {
            // Xóa logo cũ nếu có
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Cập nhật thương hiệu thành công!');
    }

    /**
     * Xóa thương hiệu
     */
    public function destroy(Brand $brand)
    {
        // RẤT QUAN TRỌNG: Kiểm tra ràng buộc khóa ngoại trước khi xóa
        if ($brand->products()->exists()) {
            return redirect()->route('admin.brands.index')->with('error', 'Không thể xóa! Thương hiệu này đang có sản phẩm hoạt động.');
        }

        // Xóa file ảnh logo trong ổ đĩa
        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Xóa thương hiệu thành công!');
    }
}
