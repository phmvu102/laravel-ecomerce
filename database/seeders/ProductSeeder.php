<?php

namespace Database\Seeders;

use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy nhanh các ID cần thiết
        $apple = Brand::where('slug', 'apple')->first();
        $nike = Brand::where('slug', 'nike')->first();
        $phoneCat = Category::where('slug', 'dien-thoai-smartphone')->first();
        $shirtCat = Category::where('slug', 'ao-nam-nu')->first();

        // -------------------------------------------------------------
        // SẢN PHẨM 1: ĐỒ ĐIỆN TỬ (iPhone 15 Pro)
        // -------------------------------------------------------------
        $iphone = Product::create([
            'name' => 'iPhone 15 Pro Max',
            'slug' => Str::slug('iPhone 15 Pro Max'),
            'brand_id' => $apple->id,
            'category_id' => $phoneCat->id,
            'short_description' => 'Siêu phẩm độ phá với khung viền Titan nhẹ nhất.',
            'description' => '<p>Nội dung mô tả chi tiết về cấu hình, camera, hiệu năng của iPhone 15 Pro Max...</p>',
            'specifications' => [
                'Màn hình' => '6.7 inch Super Retina XDR',
                'Chipset' => 'Apple A17 Pro 3nm',
                'Camera sau' => 'Chính 48MP & Phụ 12MP, 12MP',
            ],
            'status' => 'active',
            'is_featured' => true
        ]);

        // Lấy các giá trị thuộc tính tương ứng của iPhone
        $colorBlack = AttributeValue::where('value', 'Đen Titan')->first();
        $colorWhite = AttributeValue::where('value', 'Trắng Trân Châu')->first();
        $storage128 = AttributeValue::where('value', '128GB')->first();
        $storage256 = AttributeValue::where('value', '256GB')->first();

        // Biến thể 1: iPhone Đen - 128GB
        $v1 = $iphone->variants()->create([
            'sku' => 'IP15PM-BLK-128',
            'price' => 29990000,
            'sale_price' => 28490000, // Có giảm giá
            'weight' => 221, // gram
        ]);
        // Gán thuộc tính động (Màu Đen + Bản 128GB) cho biến thể này
        $v1->attributeValues()->attach([$colorBlack->id, $storage128->id]);

        // Biến thể 2: iPhone Trắng - 256GB
        $v2 = $iphone->variants()->create([
            'sku' => 'IP15PM-WHT-256',
            'price' => 32990000,
            'sale_price' => null, // Không giảm giá
            'weight' => 221,
        ]);
        $v2->attributeValues()->attach([$colorWhite->id, $storage256->id]);


        // -------------------------------------------------------------
        // SẢN PHẨM 2: THỜI TRANG (Áo thể thao Nike)
        // -------------------------------------------------------------
        $shirt = Product::create([
            'name' => 'Áo Thun Thể Thao Nike Dri-FIT',
            'slug' => Str::slug('Ao Thun The Thao Nike Dri FIT'),
            'brand_id' => $nike->id,
            'category_id' => $shirtCat->id,
            'short_description' => 'Áo thun thấm hút mồ hôi, co giãn cực tốt.',
            'description' => '<p>Chất liệu 100% polyester tái chế. Phù hợp chạy bộ, tập gym...</p>',
            'specifications' => [
                'Chất liệu' => 'Dri-FIT Polyester',
                'Công nghệ' => 'Thấm hút nhanh',
            ],
            'status' => 'active',
        ]);

        // Lấy các thuộc tính thời trang
        $colorRed = AttributeValue::where('value', 'Đỏ Crimson')->first();
        $sizeM = AttributeValue::where('value', 'M')->first();
        $sizeL = AttributeValue::where('value', 'L')->first();

        // Biến thể 1: Áo Đỏ - Size M
        $v3 = $shirt->variants()->create([
            'sku' => 'NK-SHIRT-RED-M',
            'price' => 850000,
            'weight' => 150,
        ]);
        $v3->attributeValues()->attach([$colorRed->id, $sizeM->id]);

        // Biến thể 2: Áo Đỏ - Size L
        $v4 = $shirt->variants()->create([
            'sku' => 'NK-SHIRT-RED-L',
            'price' => 850000,
            'weight' => 160,
        ]);
        $v4->attributeValues()->attach([$colorRed->id, $sizeL->id]);
    }
}
