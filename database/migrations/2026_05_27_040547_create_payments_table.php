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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('transaction_id')->nullable(); // Mã giao dịch từ cổng thanh toán
            $table->string('payment_method');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->json('raw_response_data')->nullable(); // Lưu log phản hồi từ webhook cổng thanh toán
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
