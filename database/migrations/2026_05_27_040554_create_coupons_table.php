<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['fixed', 'percentage']); // Giảm tiền mặt hoặc %
            $table->decimal('value', 15, 2);
            $table->decimal('max_discount_amount', 15, 2)->nullable(); // Giới hạn nếu chọn loại %
            $table->decimal('min_order_value', 15, 2)->default(0);
            $table->unsignedInteger('usage_limit')->nullable(); // Tổng số lượt mã có thể dùng
            $table->unsignedInteger('used_count')->default(0);
            $table->unsignedInteger('limit_per_user')->default(1); // Một user dùng tối đa mấy lần
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
