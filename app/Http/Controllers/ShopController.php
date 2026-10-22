<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display the home page with featured products and new arrivals.
     */
    /**
     * Display the home page with featured products and new arrivals.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get featured products that are active
        $featuredProducts = Product::query()
            ->with('category')
            ->where('is_featured', true)
            ->where('is_active', true)
            ->latest('created_at')
            ->take(8)
            ->get();

        // Get new arrivals that are active (products from the last 30 days)
        $newArrivals = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('created_at', '>=', now()->subDays(30))
            ->latest('created_at')
            ->take(8)
            ->get();

        // If no new arrivals in the last 30 days, get the 8 most recent active products
        if ($newArrivals->isEmpty()) {
            $newArrivals = Product::query()
                ->with('category')
                ->where('is_active', true)
                ->latest('created_at')
                ->take(8)
                ->get();
        }

        // Get categories with active products for the home page
        $categories = Category::query()
            ->whereHas('products', function ($query) {
                $query->where('is_active', true);
            })
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderByDesc('products_count')
            ->take(6)
            ->get();

        // Log the results
        \Log::info('Featured products count: '.$featuredProducts->count());
        \Log::info('New arrivals count: '.$newArrivals->count());
        \Log::info('Categories count: '.$categories->count());

        return view('shop.index', compact('featuredProducts', 'newArrivals', 'categories'));
    }

    /**
     * Display the specified product.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        // Get related products (products from the same category)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('shop.product', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    /**
     * Display all categories page.
     */
    public function categories()
    {
        $categories = Category::withCount(['products' => function ($query) {
            $query->where('is_active', true);
        }])
            ->whereHas('products')
            ->orderBy('name')
            ->get();

        return view('shop.categories', compact('categories'));
    }

    /**
     * Display new arrivals page.
     */
    public function newArrivals()
    {
        $products = Product::where('is_active', true)
            ->where('created_at', '>=', now()->subDays(30))
            ->latest('created_at')
            ->paginate(12);

        return view('shop.new-arrivals', compact('products'));
    }

    /**
     * Display deals/special offers page.
     */
    public function deals()
    {
        $products = Product::where('is_active', true)
            ->where('compare_at_price', '>', 0)
            ->whereColumn('price', '<', 'compare_at_price')
            ->latest()
            ->paginate(12);

        return view('shop.deals', compact('products'));
    }

    /**
     * Display about us page.
     */
    public function about()
    {
        return view('shop.about');
    }

    /**
     * Display contact us page.
     */
    public function contact()
    {
        return view('shop.contact');
    }

    /**
     * Handle product search
     *
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('details', 'like', "%{$query}%");
            })
            ->latest('created_at')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])
            ->orderBy('name')
            ->get();

        return view('shop.search', [
            'products' => $products,
            'categories' => $categories,
            'searchQuery' => $query,
        ]);
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
