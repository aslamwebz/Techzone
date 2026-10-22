<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Search for products
     *
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $query = $request->input('q');

        // Search in products table
        $products = Product::query()
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%");
            })
            ->with(['category', 'media'])
            ->orderBy('name')
            ->take(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'image_url' => $product->getFirstMediaUrl('products', 'thumb'),
                    'category' => $product->category ? $product->category->name : null,
                ];
            });

        return response()->json([
            'data' => $products,
            'message' => 'Search results retrieved successfully.',
        ]);
    }

    /**
     * Get search suggestions
     *
     * @return JsonResponse
     */
    public function suggestions(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $query = $request->input('q');

        // Get product name suggestions
        $productNames = Product::query()
            ->select('name')
            ->where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->groupBy('name')
            ->orderByRaw('COUNT(*) DESC')
            ->take(5)
            ->pluck('name');

        // Get category suggestions
        $categoryNames = DB::table('categories')
            ->select('name')
            ->where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->take(3)
            ->pluck('name');

        $suggestions = $productNames->merge($categoryNames)
            ->map(function ($item) use ($query) {
                // Highlight the matching part of the text
                $highlighted = preg_replace('/('.preg_quote($query, '/').')/i', '<span class="font-semibold text-indigo-600">$1</span>', $item);

                return [
                    'text' => $item,
                    'highlighted' => $highlighted,
                    'type' => Str::contains(strtolower($item), strtolower($query)) ? 'exact' : 'partial',
                ];
            });

        return response()->json([
            'data' => $suggestions,
            'message' => 'Search suggestions retrieved successfully.',
        ]);
    }
}
