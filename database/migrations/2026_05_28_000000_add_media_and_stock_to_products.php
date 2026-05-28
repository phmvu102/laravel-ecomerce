<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('description');
            }

            if (! Schema::hasColumn('products', 'images')) {
                $table->json('images')->nullable()->after('thumbnail');
            }
        });

        Schema::table('product_variants', function (Blueprint $table) {
            if (! Schema::hasColumn('product_variants', 'stock')) {
                $table->unsignedInteger('stock')->default(0)->after('sale_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'stock')) {
                $table->dropColumn('stock');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'images')) {
                $table->dropColumn('images');
            }

            if (Schema::hasColumn('products', 'thumbnail')) {
                $table->dropColumn('thumbnail');
            }
        });
    }
};
