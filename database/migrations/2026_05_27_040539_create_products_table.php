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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('brand_id')->constrained('brands');
            $table->foreignId('category_id')->constrained('categories');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->json('specifications')->nullable(); // Lưu thông số kỹ thuật động dạng JSON
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
