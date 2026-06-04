<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_code', 'user_id', 'subtotal', 'shipping_fee',
        'coupon_code', 'discount_amount', 'total_amount',
        'shipping_name', 'shipping_phone', 'shipping_address',
        'customer_note', 'status', 'payment_status', 'payment_method',
        'source', 'delivered_at'
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    // Hàm hỗ trợ kiểm tra thời hạn đổi trả hàng dựa theo danh mục sản phẩm (bài toán thực tế)
    public function canRequestReturn(): bool
    {
        if ($this->status !== 'delivered' || !$this->delivered_at) {
            return false;
        }

        // Lấy số ngày đổi trả lớn nhất được cấu hình trong các sản phẩm của đơn hàng này
        $maxReturnDays = 7; // Mặc định hệ thống là 7 ngày

        foreach ($this->items as $item) {
            $categoryReturnDays = $item->productVariant->product->category->return_duration_days ?? 7;
            if ($categoryReturnDays > $maxReturnDays) {
                $maxReturnDays = $categoryReturnDays;
            }
        }

        return now()->diffInDays($this->delivered_at) <= $maxReturnDays;
    }
}
