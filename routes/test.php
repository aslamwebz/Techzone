<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;

Route::get('/test-db', function () {
    try {
        // Test database connection
        DB::connection()->getPdo();
        
        // Get counts
        $productCount = Product::count();
        $categoryCount = Category::count();
        
        // Get first product if exists
        $firstProduct = Product::first();
        
        return [
            'database_connection' => 'OK',
            'product_count' => $productCount,
            'category_count' => $categoryCount,
            'first_product' => $firstProduct ? $firstProduct->toArray() : null,
        ];
    } catch (\Exception $e) {
        return [
            'error' => $e->getMessage(),
            'database_connection' => 'FAILED',
        ];
    }
});
