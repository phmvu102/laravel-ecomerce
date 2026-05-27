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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('province_id'); // Code tỉnh/thành
            $table->string('province_name');
            $table->string('district_id'); // Code quận/huyện
            $table->string('district_name');
            $table->string('ward_id');     // Code phường/xã
            $table->string('ward_name');
            $table->string('specific_address'); // Số nhà, tên đường
            $table->enum('type', ['home', 'office'])->default('home');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
