@extends('shop.layout')

@section('shop-content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">All Categories</h1>
    
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($categories as $category)
            <a href="{{ route('shop.category', $category) }}" class="group relative block bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    @if($category->image)
                        <img src="{{ $category->image }}" alt="{{ $category->name }}" class="h-16 w-16 object-cover rounded-md mr-4">
                    @endif
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 group-hover:text-indigo-600">
                            {{ $category->name }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                        </p>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-500">No categories found.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
