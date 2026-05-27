<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'sku', 'price', 'sale_price', 'image', 'weight', 'length', 'width', 'height'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Quan hệ nhiều-nhiều: Một biến thể có nhiều giá trị thuộc tính (Màu: Đỏ, Bản: 64GB)
    // Liên kết qua bảng trung gian 'product_variant_attributes'
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_variant_attributes',
            'product_variant_id',
            'attribute_value_id'
        );
    }
}
