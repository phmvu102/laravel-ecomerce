<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->whereNull('parent_id')
            ->take(6)
            ->get();

        $newProducts = Product::with(['brand', 'variants'])
            ->active()
            ->latest()
            ->take(8)
            ->get();

        $featuredProducts = Product::with(['brand', 'variants'])
            ->active()
            ->where('is_featured', true)
            ->take(4)
            ->get();

        return view('home', compact('categories', 'newProducts', 'featuredProducts'));
    }

    public function shop(Request $request, $category_slug = null)
    {
        $currentCategory = null;

        $products = Product::with(['brand', 'category', 'variants'])
            ->active()
            ->when($category_slug, function ($q) use ($category_slug, &$currentCategory) {
                $currentCategory = Category::where('slug', $category_slug)->firstOrFail();
                $q->byCategory($currentCategory->id);
            })
            ->when($request->filled('search'),    fn($q) => $q->search($request->search))
            ->when($request->filled('brands'),    fn($q) => $q->byBrands($request->brands))
            ->when($request->filled('min_price'), fn($q) => $q->minPrice($request->min_price))
            ->when($request->filled('max_price'), fn($q) => $q->maxPrice($request->max_price))
            ->sorted($request->get('sort', 'newest'))
            ->paginate(12)
            ->withQueryString();

        $categories = Category::with(['children' => fn($q) => $q->withCount('products')])
            ->whereNull('parent_id')
            ->get()
            ->each(function ($cat) {
                // Tổng = sản phẩm cha + tổng sản phẩm các con
                $cat->products_count = $cat->children->sum('products_count')
                    + $cat->products()->count();
            });

        $brands = Brand::whereHas('products', fn($q) => $q->active())->get();

        return view('client.shop', compact('products', 'categories', 'brands', 'currentCategory'));
    }

    public function show($slug)
    {
        $product = Product::with([
            'brand',
            'category',
            'variants.attributeValues.attribute',
        ])
        ->active()
        ->where('slug', $slug)
        ->firstOrFail();

        $relatedProducts = Product::with(['brand', 'variants'])
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(8)
            ->get();

        return view('client.show', compact('product', 'relatedProducts'));
    }
}
