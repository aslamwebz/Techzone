@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Search Results for "{{ $searchQuery }}"</h1>
        <p class="mt-2 text-sm text-gray-600">{{ $products->total() }} results found</p>
    </div>

    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <a href="{{ route('product.show', $product->slug) }}" class="block">
                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden">
                            <img src="{{ $product->getFirstMediaUrl('products') ?: asset('images/placeholder.jpg') }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-48 object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $product->name }}</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ $product->category->name }}</p>
                            <p class="mt-2 text-lg font-semibold text-gray-900">{{ $product->formatted_price }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
            <p class="mt-1 text-sm text-gray-500">We couldn't find any products matching your search for "{{ $searchQuery }}".</p>
            <div class="mt-6">
                <a href="{{ route('shop') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    View all products
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
