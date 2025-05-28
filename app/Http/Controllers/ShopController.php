<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
                                 ->with('category')
                                 ->take(8)
                                 ->get();

        $newArrivals = Product::latest()
                            ->with('category')
                            ->take(8)
                            ->get();

        $categories = Category::withCount('products')
                            ->orderBy('products_count', 'desc')
                            ->take(6)
                            ->get();

        return view('shop.index', compact('featuredProducts', 'newArrivals', 'categories'));
    }

    public function shop()
    {
        $products = Product::with('category')
                         ->paginate(12)
                         ->withQueryString();

        $categories = Category::withCount('products')
                            ->orderBy('name')
                            ->get();

        return view('shop.shop', compact('products', 'categories'));
    }

    public function category(Category $category)
    {
        $products = $category->products()
                          ->with('category')
                          ->paginate(12)
                          ->withQueryString();

        $categories = Category::withCount('products')
                            ->orderBy('name')
                            ->get();

        return view('shop.category', compact('products', 'categories', 'category'));
    }

    public function show(Product $product)
    {
        $relatedProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $product->id)
                                ->inRandomOrder()
                                ->take(4)
                                ->get();

        return view('shop.product', compact('product', 'relatedProducts'));
    }
    
    public function showCategory(Category $category)
    {
        $products = $category->products()
                          ->with('category')
                          ->paginate(12)
                          ->withQueryString();

        return view('shop.category', compact('category', 'products'));
    }
}

// Add this to your Product model:
/*
public function scopeFilter($query, array $filters)
{
    $query->when($filters['search'] ?? false, fn($query, $search) =>
        $query->where(fn($query) =>
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
        )
    );

    $query->when($filters['category'] ?? false, fn($query, $category) =>
        $query->whereHas('category', fn($query) =>
            $query->where('slug', $category)
        )
    );

    $query->when($filters['sort'] ?? false, function($query, $sort) {
        if ($sort === 'price_asc') {
            return $query->orderBy('price');
        } elseif ($sort === 'price_desc') {
            return $query->orderBy('price', 'desc');
        } elseif ($sort === 'newest') {
            return $query->latest();
        } elseif ($sort === 'popular') {
            return $query->orderBy('views', 'desc');
        }
    });
}
*/
