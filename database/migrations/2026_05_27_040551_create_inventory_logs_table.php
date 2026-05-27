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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants');
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->integer('quantity_changed'); // Số lượng thay đổi (+10 hoặc -5)
            $table->enum('change_type', ['import', 'export', 'order_hold', 'order_release', 'return', 'stock_audit']);
            $table->nullableMorphs('reference'); // Đơn hàng hoặc đơn hoàn trả gây ra thay đổi này
            $table->integer('current_stock_after'); // Lưu lại tồn thực tế sau khi cộng/trừ để đối soát
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
