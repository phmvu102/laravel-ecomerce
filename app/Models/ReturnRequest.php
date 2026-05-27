<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnRequest extends Model
{
    // Chỉ định chính xác tên bảng trong DB vì ta đặt tên migration là return_requests thay vì returns
    protected $table = 'return_requests';

    protected $fillable = [
        'return_code', 'order_id', 'user_id', 'type',
        'reason', 'reason_detail', 'evidence_images',
        'refund_amount', 'status', 'admin_note'
    ];

    protected $casts = [
        'evidence_images' => 'array', // Ép kiểu để lưu mảng URL ảnh bằng chứng dạng JSON
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReturnRequestItem::class);
    }
}
