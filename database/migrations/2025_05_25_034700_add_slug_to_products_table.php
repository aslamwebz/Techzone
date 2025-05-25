<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // First add the slug as nullable
            $table->string('slug')->after('name')->nullable();
            $table->decimal('sale_price', 10, 2)->nullable()->after('price');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->boolean('is_new')->default(true)->after('is_featured');
            $table->text('short_description')->nullable()->after('description');
            $table->decimal('weight', 10, 2)->nullable()->after('short_description');
            $table->string('dimensions')->nullable()->after('weight');
            $table->string('sku')->nullable()->after('dimensions');
            $table->float('rating', 2, 1)->default(0)->after('sku');
            $table->unsignedInteger('reviews_count')->default(0)->after('rating');
        });

        // Update existing products with slugs
        \App\Models\Product::all()->each(function ($product) {
            $product->update([
                'slug' => \Illuminate\Support\Str::slug($product->name),
                'short_description' => $product->short_description ?? Str::limit($product->description, 100),
            ]);
        });

        // Now make the slug column non-nullable and unique
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'sale_price',
                'is_featured',
                'is_new',
                'short_description',
                'weight',
                'dimensions',
                'sku',
                'rating',
                'reviews_count'
            ]);
        });
    }
};
