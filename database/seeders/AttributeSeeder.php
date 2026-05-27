<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        // Thuộc tính Màu Sắc
        $color = Attribute::firstOrCreate(
            ['code' => 'color'],
            ['name' => 'Màu sắc']
        );

        $color->values()->firstOrCreate(['value' => 'Đen Titan'], ['extra_value' => '#1c1d21']);
        $color->values()->firstOrCreate(['value' => 'Trắng Trân Châu'], ['extra_value' => '#f5f5f7']);
        $color->values()->firstOrCreate(['value' => 'Đỏ Crimson'], ['extra_value' => '#990000']);

        // Thuộc tính Dung Lượng (Cho đồ điện tử)
        $storage = Attribute::firstOrCreate(
            ['code' => 'storage'],
            ['name' => 'Dung lượng']
        );

        $storage->values()->firstOrCreate(['value' => '128GB']);
        $storage->values()->firstOrCreate(['value' => '256GB']);
        $storage->values()->firstOrCreate(['value' => '512GB']);

        // Thuộc tính Kích Cỡ (Cho thời trang)
        $size = Attribute::firstOrCreate(
            ['code' => 'size'],
            ['name' => 'Kích cỡ']
        );

        $size->values()->firstOrCreate(['value' => 'S']);
        $size->values()->firstOrCreate(['value' => 'M']);
        $size->values()->firstOrCreate(['value' => 'L']);
        $size->values()->firstOrCreate(['value' => 'XL']);

    }
}
