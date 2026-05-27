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
        Schema::create('pos_held_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_session_id')->constrained('pos_sessions')->onDelete('cascade');
            $table->string('hold_reference')->nullable(); // Tên hoặc số phân biệt đơn chờ (ví dụ: "Khách áo xanh")
            $table->json('cart_data'); // Lưu toàn bộ giỏ hàng hiện tại gồm variant_id, số lượng dạng JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_held_orders');
    }
};
