<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'brand_id', 'category_id',
        'short_description', 'description',
        'specifications', 'status', 'is_featured', 'view_count',
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_featured'    => 'boolean',
    ];

    // ===== RELATIONSHIPS =====

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ===== ACCESSORS =====

    public function getMinPriceAttribute(): float
    {
        return $this->variants->min(fn($v) => $v->sale_price ?? $v->price) ?? 0;
    }

    public function getOriginalPriceAttribute(): float
    {
        return $this->variants->min('price') ?? 0;
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->variants->contains(
            fn($v) => $v->sale_price && $v->sale_price < $v->price
        );
    }

    public function getMaxDiscountAttribute(): int
    {
        return $this->variants->max(function ($v) {
            if (!$v->sale_price || $v->sale_price >= $v->price) return 0;
            return round((1 - $v->sale_price / $v->price) * 100);
        }) ?? 0;
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    public function scopeByBrands($query, array $slugs)
    {
        return $query->whereHas('brand', fn($q) => $q->whereIn('slug', $slugs));
    }

    public function scopeByCategory($query, int $categoryId)
    {
        $ids = Category::where('parent_id', $categoryId)
            ->pluck('id')
            ->push($categoryId);

        return $query->whereIn('category_id', $ids);
    }

    public function scopeMinPrice($query, $min)
    {
        return $query->whereHas('variants', fn($q) =>
            $q->where(fn($q2) =>
                $q2->where('sale_price', '>=', $min)
                   ->orWhere(fn($q3) =>
                       $q3->whereNull('sale_price')->where('price', '>=', $min)
                   )
            )
        );
    }

    public function scopeMaxPrice($query, $max)
    {
        return $query->whereHas('variants', fn($q) =>
            $q->where(fn($q2) =>
                $q2->where('sale_price', '<=', $max)
                   ->orWhere(fn($q3) =>
                       $q3->whereNull('sale_price')->where('price', '<=', $max)
                   )
            )
        );
    }

    public function scopeSorted($query, string $sort)
    {
        return match ($sort) {
            'price_asc'    => $query->orderBy(
                ProductVariant::selectRaw('MIN(COALESCE(sale_price, price))')
                    ->whereColumn('product_id', 'products.id')
                    ->limit(1), 'asc'
            ),
            'price_desc'   => $query->orderBy(
                ProductVariant::selectRaw('MIN(COALESCE(sale_price, price))')
                    ->whereColumn('product_id', 'products.id')
                    ->limit(1), 'desc'
            ),
            'best_selling' => $query->withCount('orderItems')->orderBy('order_items_count', 'desc'),
            default        => $query->latest(),
        };
    }
}