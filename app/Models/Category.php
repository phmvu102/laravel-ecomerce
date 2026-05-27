<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id', 'image', 'sort_order', 'return_duration_days', 'status'];

    // Lấy danh mục cha của danh mục này
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Lấy tất cả danh mục con trực thuộc
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order', 'asc');
    }

    // Một danh mục có nhiều sản phẩm
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
