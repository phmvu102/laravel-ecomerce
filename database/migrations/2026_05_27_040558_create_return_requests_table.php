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
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->string('return_code')->unique();
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('type', ['refund', 'exchange']); // Hoàn tiền hoặc Đổi sản phẩm mới
            $table->string('reason');
            $table->text('reason_detail')->nullable();
            $table->json('evidence_images')->nullable(); // Array chứa links hình ảnh/video lỗi
            $table->decimal('refund_amount', 15, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected', 'shipping_back', 'received', 'completed'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_requests');
    }
};
