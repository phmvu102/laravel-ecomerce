<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple', 'description' => 'Thương hiệu công nghệ hàng đầu vĩ đại.'],
            ['name' => 'Samsung', 'description' => 'Gã khổng lồ công nghệ đến từ Hàn Quốc.'],
            ['name' => 'Nike', 'description' => 'Just Do It - Thương hiệu thể thao toàn cầu.'],
            ['name' => 'Adidas', 'description' => 'Thương hiệu thời trang thể thao Đức.'],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['slug' => Str::slug($brand['name'])],
                [
                    'name' => $brand['name'],
                    'description' => $brand['description'],
                    'status' => 'active',
                ]
            );
        }

    }
}
