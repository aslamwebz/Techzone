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
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('price');
            }
            if (! Schema::hasColumn('products', 'short_description')) {
                $table->string('short_description')->nullable()->after('description');
            }
            if (! Schema::hasColumn('products', 'weight')) {
                $table->decimal('weight', 10, 2)->nullable()->after('short_description');
            }
            if (! Schema::hasColumn('products', 'dimensions')) {
                $table->string('dimensions')->nullable()->after('weight');
            }
            if (! Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->after('dimensions');
            }
            if (! Schema::hasColumn('products', 'rating')) {
                $table->float('rating', 2, 1)->nullable()->after('sku');
            }
            if (! Schema::hasColumn('products', 'reviews_count')) {
                $table->integer('reviews_count')->default(0)->after('rating');
            }
            if (! Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            }
            if (! Schema::hasColumn('products', 'is_new')) {
                $table->boolean('is_new')->default(true)->after('is_featured');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = [
                'sale_price',
                'short_description',
                'weight',
                'dimensions',
                'sku',
                'rating',
                'reviews_count',
                'is_featured',
                'is_new',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
