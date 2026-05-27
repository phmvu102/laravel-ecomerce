<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Trang chủ
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->whereNull('parent_id')
            ->take(6)
            ->get();

        $newProducts = Product::with(['brand', 'variants'])
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();

        $featuredProducts = Product::with(['brand'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->take(4)
            ->get();

        return view('client.home', compact(
            'categories',
            'newProducts',
            'featuredProducts'
        ));
    }

    /**
     * Trang danh sách sản phẩm / lọc theo danh mục
     */
    public function shop(Request $request, $category_slug = null)
    {
        $query = Product::with(['brand', 'category', 'variants'])
            ->where('status', 'active');

        // Lọc theo danh mục
        $currentCategory = null;
        if ($category_slug) {
            $currentCategory = Category::where('slug', $category_slug)->firstOrFail();
            // Lấy cả sản phẩm của danh mục con
            $categoryIds = Category::where('parent_id', $currentCategory->id)
                ->pluck('id')
                ->push($currentCategory->id);
            $query->whereIn('category_id', $categoryIds);
        }

        // Tìm kiếm
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo thương hiệu
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Lọc theo giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sắp xếp
        switch ($request->get('sort', 'latest')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'popular':
                $query->withCount('orderItems')->orderBy('order_items_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $products  = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->whereNull('parent_id')->get();
        $brands     = Brand::whereHas('products', fn($q) => $q->where('status', 'active'))->get();

        return view('client.shop', compact(
            'products',
            'categories',
            'brands',
            'currentCategory'
        ));
    }

    /**
     * Trang chi tiết sản phẩm
     */
    public function show($slug)
    {
        $product = Product::with([
            'brand',
            'category',
            'variants.attributeValues.attribute',
        ])
        ->where('slug', $slug)
        ->where('status', 'active')
        ->firstOrFail();

        // Sản phẩm liên quan (cùng danh mục, trừ sản phẩm hiện tại)
        $relatedProducts = Product::with(['brand', 'variants'])
            ->where('status', 'active')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(8)
            ->get();

        return view('client.show', compact('product', 'relatedProducts'));
    }
}
