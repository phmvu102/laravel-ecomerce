<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ngành Điện Tử (Cha) - Cho phép đổi trả 7 ngày
        $electronics = Category::firstOrCreate([
            'slug' => Str::slug('Điện Tử'),
        ], [
            'name' => 'Điện Tử',
            'return_duration_days' => 7,
            'status' => 'active'
        ]);

        // Con của Điện Tử
        Category::firstOrCreate([
            'slug' => Str::slug('Điện thoại Smartphone'),
        ], [
            'name' => 'Điện thoại Smartphone',
            'parent_id' => $electronics->id,
            'return_duration_days' => 7,
        ]);
        Category::firstOrCreate([
            'slug' => Str::slug('Laptop & Máy Tính'),
        ], [
            'name' => 'Laptop & Máy Tính',
            'parent_id' => $electronics->id,
            'return_duration_days' => 15, // Riêng laptop cho test 15 ngày
        ]);

        // 2. Ngành Thời Trang (Cha) - Cho phép đổi size trong 30 ngày
        $fashion = Category::firstOrCreate([
            'slug' => Str::slug('Thời Trang'),
        ], [
            'name' => 'Thời Trang',
            'return_duration_days' => 30,
            'status' => 'active'
        ]);

        // Con của Thời Trang
        Category::firstOrCreate([
            'slug' => Str::slug('Áo Nam Nữ'),
        ], [
            'name' => 'Áo Nam & Nữ',
            'parent_id' => $fashion->id,
            'return_duration_days' => 30,
        ]);
        Category::firstOrCreate([
            'slug' => Str::slug('Giày Thể Thao'),
        ], [
            'name' => 'Giày Thể Thao',
            'parent_id' => $fashion->id,
            'return_duration_days' => 30,
        ]);

    }
}
