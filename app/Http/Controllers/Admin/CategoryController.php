<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // [Hàm index và store giữ nguyên như cũ, tôi viết tiếp các hàm mới bên dưới]

    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'return_duration_days' => 'required|integer|min:0',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'parent_id' => $request->parent_id,
            'return_duration_days' => $request->return_duration_days,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Thêm danh mục thành công!');
    }

    // 3. Giao diện chỉnh sửa danh mục
    public function edit(Category $category)
    {
        // Lấy danh sách danh mục cha làm gợi ý (Loại trừ chính nó để tránh chọn chính mình làm cha)
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    // 4. Xử lý cập nhật dữ liệu vào DB
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'return_duration_days' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        // Logic an toàn: Ngăn chặn việc biến danh mục cha thành con của một danh mục con khác (gây vòng lặp vô hạn)
        if ($request->parent_id == $category->id) {
            return redirect()->back()->withErrors(['parent_id' => 'Danh mục không thể làm cha của chính nó!']);
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $category->id, // cập nhật lại slug theo tên mới
            'parent_id' => $request->parent_id,
            'return_duration_days' => $request->return_duration_days,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    // 5. Xử lý xóa danh mục
    public function destroy(Category $category)
    {
        // Kiểm tra an toàn bảo mật dữ liệu:
        // Nếu danh mục này đang có danh mục con, bắt người dùng phải xóa danh mục con trước.
        if ($category->children()->count() > 0) {
            return redirect()->back()->with('error', 'Không thể xóa danh mục này vì nó đang chứa các danh mục con!');
        }

        // (Sau này phát triển thêm): Kiểm tra nếu danh mục đang có Sản phẩm gắn vào thì cũng không cho xóa.
        // if ($category->products()->count() > 0) { ... }

        $category->delete();

        return redirect()->back()->with('success', 'Xóa danh mục thành công!');
    }
}
