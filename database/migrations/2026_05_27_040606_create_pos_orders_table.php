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
        Schema::create('pos_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_session_id')->constrained('pos_sessions');
            $table->foreignId('order_id')->constrained('orders'); // Liên kết trực tiếp sang bảng Order tổng
            $table->decimal('amount_received', 15, 2); // Tiền khách đưa
            $table->decimal('amount_change', 15, 2)->default(0); // Tiền thối lại
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_orders');
    }
};
