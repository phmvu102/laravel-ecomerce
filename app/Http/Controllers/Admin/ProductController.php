<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand', 'variants'])
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        $attributes = Attribute::with('values')->get();

        return view('admin.products.create', compact(
            'categories',
            'brands',
            'attributes'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'status' => 'required|in:draft,active,inactive',

            'variants' => 'required|array|min:1',
            'variants.*.sku' => 'required|string|unique:product_variants,sku',
            'variants.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {

            $specifications = [];

            if ($request->has('spec_keys')) {

                foreach ($request->spec_keys as $index => $key) {

                    if (!empty($key)) {

                        $specifications[] = [
                            'key' => $key,
                            'value' => $request->spec_values[$index] ?? ''
                        ];
                    }
                }
            }

            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'status' => $request->status,
                'is_featured' => $request->has('is_featured'),
                'specifications' => $specifications,
                'view_count' => 0,
            ]);

            foreach ($request->variants as $variantData) {

                $variant = $product->variants()->create([
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'sale_price' => $variantData['sale_price'] ?? null,
                    'weight' => $variantData['weight'] ?? 0,
                    'length' => $variantData['length'] ?? 0,
                    'width' => $variantData['width'] ?? 0,
                    'height' => $variantData['height'] ?? 0,
                ]);

                if (!empty($variantData['attribute_values'])) {

                    $valueIds = explode(',', $variantData['attribute_values']);

                    $variant->attributeValues()->attach($valueIds);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Tạo sản phẩm thành công!');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage()
                ]);
        }
    }

    public function edit(Product $product)
    {
        $product->load([
            'variants.attributeValues.attribute'
        ]);

        $categories = Category::where('status', 'active')->get();

        $brands = Brand::where('status', 'active')->get();

        $attributes = Attribute::with('values')->get();

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'brands',
            'attributes'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'status' => 'required|in:draft,active,inactive',

            'variants.*.sku' => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.attribute_values' => 'nullable|string',

            'new_variants.*.sku' => 'required_with:new_variants.*.attribute_values|nullable|string|unique:product_variants,sku',
            'new_variants.*.price' => 'required_with:new_variants.*.sku|nullable|numeric|min:0',
            'new_variants.*.attribute_values' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {

            $specifications = [];

            if ($request->has('spec_keys')) {

                foreach ($request->spec_keys as $index => $key) {

                    if (!empty($key)) {

                        $specifications[] = [
                            'key' => $key,
                            'value' => $request->spec_values[$index] ?? ''
                        ];
                    }
                }
            }

            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'status' => $request->status,
                'is_featured' => $request->has('is_featured'),
                'specifications' => $specifications,
            ]);

            /*
            |--------------------------------------------------------------------------
            | DELETE VARIANTS
            |--------------------------------------------------------------------------
            */

            if ($request->filled('deleted_variants')) {

                $variantsToDelete = ProductVariant::where('product_id', $product->id)
                    ->whereIn('id', $request->deleted_variants)
                    ->get();

                foreach ($variantsToDelete as $variant) {

                    $variant->attributeValues()->detach();

                    $variant->delete();
                }
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE OLD VARIANTS
            |--------------------------------------------------------------------------
            */

            if ($request->has('variants')) {

                foreach ($request->variants as $variantId => $variantData) {

                    $variant = ProductVariant::where('product_id', $product->id)
                        ->find($variantId);

                    if (!$variant) {
                        continue;
                    }

                    $skuExists = ProductVariant::where('sku', $variantData['sku'])
                        ->where('id', '!=', $variantId)
                        ->exists();

                    if ($skuExists) {
                        throw new \Exception("SKU {$variantData['sku']} đã tồn tại.");
                    }

                    $variant->update([
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'],
                        'sale_price' => $variantData['sale_price'] ?? null,
                        'weight' => $variantData['weight'] ?? 0,
                        'length' => $variantData['length'] ?? 0,
                        'width' => $variantData['width'] ?? 0,
                        'height' => $variantData['height'] ?? 0,
                    ]);

                    if (array_key_exists('attribute_values', $variantData)) {
                        $valueIds = collect(explode(',', $variantData['attribute_values']))
                            ->filter()
                            ->map(fn ($id) => (int) $id)
                            ->values()
                            ->all();

                        $variant->attributeValues()->sync($valueIds);
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE NEW VARIANTS
            |--------------------------------------------------------------------------
            */

            if ($request->has('new_variants')) {

                foreach ($request->new_variants as $variantData) {

                    if (empty($variantData['sku'])) {
                        continue;
                    }

                    $variant = $product->variants()->create([
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'],
                        'sale_price' => $variantData['sale_price'] ?? null,
                        'weight' => $variantData['weight'] ?? 0,
                        'length' => $variantData['length'] ?? 0,
                        'width' => $variantData['width'] ?? 0,
                        'height' => $variantData['height'] ?? 0,
                    ]);

                    if (!empty($variantData['attribute_values'])) {

                        $valueIds = collect(explode(',', $variantData['attribute_values']))
                            ->filter()
                            ->map(fn ($id) => (int) $id)
                            ->values()
                            ->all();

                        $variant->attributeValues()->attach($valueIds);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage()
                ]);
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công!');
    }
}
