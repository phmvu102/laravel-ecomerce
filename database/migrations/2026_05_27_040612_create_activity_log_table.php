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
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject'); // Đối tượng bị tác động (Ví dụ: Product ID 1)
            $table->nullableMorphs('causer');  // Người thực hiện hành động (Ví dụ: User ID 2)
            $table->json('properties')->nullable(); // Dữ liệu cũ và mới để đối chiếu rollback nếu cần
            $table->timestamps();

            $table->index('log_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};
