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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Cho phép mua không cần login

            // Tiền bạc
            $table->decimal('subtotal', 15, 2);
            $table->decimal('shipping_fee', 15, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);

            // Giao hàng
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->text('shipping_address');
            $table->text('customer_note')->nullable();

            // Trạng thái đơn và thanh toán
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipping', 'delivered', 'cancelled', 'returned'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'partially_refunded', 'refunded'])->default('unpaid');
            $table->string('payment_method')->default('COD'); // COD, VNPAY, MOMO, POS

            $table->enum('source', ['web', 'pos'])->default('web'); // Phân biệt đơn từ web hay quầy
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
