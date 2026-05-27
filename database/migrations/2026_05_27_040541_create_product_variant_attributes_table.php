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
        Schema::create('product_variant_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants', 'id', 'p_variant_attr_variant_id_foreign')->onDelete('cascade');
            $table->foreignId('attribute_value_id')->constrained('attribute_values', 'id', 'p_variant_attr_value_id_foreign')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_attributes');
    }
};
