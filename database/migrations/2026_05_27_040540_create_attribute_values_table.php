<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('attribute_values')) {
            if (! $this->hasForeignKey('attribute_values_attribute_id_foreign')) {
                Schema::table('attribute_values', function (Blueprint $table) {
                    $table->foreign('attribute_id')
                        ->references('id')
                        ->on('attributes')
                        ->onDelete('cascade');
                });
            }

            return;
        }

        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade');
            $table->string('value'); // Ví dụ: Đỏ, 64GB
            $table->string('extra_value')->nullable(); // Lưu mã màu Hex (#FFF) nếu là thuộc tính màu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }

    private function hasForeignKey(string $constraint): bool
    {
        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'attribute_values')
            ->where('CONSTRAINT_NAME', $constraint)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();
    }
};
