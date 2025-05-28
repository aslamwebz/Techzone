@extends('layouts.shop')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-8">
            <!-- Product images -->
            <div class="mb-8 lg:mb-0">
                <div class="bg-gray-50 rounded-lg overflow-hidden mb-4">
                    @if($product->image && file_exists(public_path('storage/' . $product->image)))
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-auto object-contain max-h-96 mx-auto"
                             loading="lazy">
                    @else
                        <img src="https://via.placeholder.com/800x800?text=No+Image" 
                             alt="{{ $product->name }}" 
                             class="w-full h-96 object-contain bg-white">
                    @endif
                </div>
            </div>

            <!-- Product info -->
            <div class="lg:pl-8">
                <div class="mb-4">
                    @if($product->category)
                        <a href="{{ route('categories.show', $product->category->slug) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            {{ $product->category->name }}
                        </a>
                    @endif
                    <h1 class="text-3xl font-extrabold text-gray-900 mt-2">{{ $product->name }}</h1>
                    
                    <!-- Price -->
                    <div class="mt-4">
                        @if($product->compare_price > $product->price)
                            <p class="text-3xl font-bold text-gray-900">
                                {{ number_format($product->price, 2) }}
                                <span class="text-lg text-gray-500 line-through ml-2">
                                    {{ number_format($product->compare_price, 2) }}
                                </span>
                            </p>
                            <span class="inline-block bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded mt-2">
                                {{ round(($product->compare_price - $product->price) / $product->compare_price * 100) }}% OFF
                            </span>
                        @else
                            <p class="text-3xl font-bold text-gray-900">
                                {{ number_format($product->price, 2) }}
                            </p>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-900">Description</h3>
                        <div class="mt-4 prose prose-sm text-gray-500">
                            {!! $product->description !!}
                        </div>
                    </div>

                    <!-- Add to cart -->
                    <div class="mt-8">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center border border-gray-300 rounded-md">
                                <button type="button" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 focus:outline-none">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="quantity" name="quantity" min="1" value="1" 
                                       class="w-16 text-center border-0 focus:ring-0">
                                <button type="button" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 focus:outline-none">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <button type="button" 
                                    class="flex-1 bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Add to cart
                            </button>
                        </div>
                    </div>

                    <!-- Additional info -->
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h3 class="text-sm font-medium text-gray-900">Additional Information</h3>
                        <div class="mt-4 space-y-4">
                            <div class="flex">
                                <dt class="w-32 flex-none text-sm text-gray-500">SKU</dt>
                                <dd class="text-sm text-gray-900">{{ $product->sku ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex">
                                <dt class="w-32 flex-none text-sm text-gray-500">Availability</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($product->stock > 0)
                                        <span class="text-green-600">In Stock ({{ $product->stock }} available)</span>
                                    @else
                                        <span class="text-red-600">Out of Stock</span>
                                    @endif
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-8">You may also like</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="group relative bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-300">
                            <div class="relative pt-[100%] bg-gray-50 overflow-hidden">
                                <a href="{{ route('product.show', $relatedProduct->slug) }}" class="absolute inset-0 flex items-center justify-center p-4">
                                    @if($relatedProduct->image && file_exists(public_path('storage/' . $relatedProduct->image)))
                                        <img src="{{ asset('storage/' . $relatedProduct->image) }}" 
                                             alt="{{ $relatedProduct->name }}" 
                                             class="max-w-full max-h-full w-auto h-auto object-contain transition-transform duration-500 group-hover:scale-110"
                                             loading="lazy">
                                    @else
                                        <img src="https://via.placeholder.com/400x400?text=No+Image" 
                                             alt="{{ $relatedProduct->name }}" 
                                             class="max-w-full max-h-full w-auto h-auto object-contain">
                                    @endif
                                </a>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm text-gray-700">
                                    <a href="{{ route('product.show', $relatedProduct->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $relatedProduct->name }}
                                    </a>
                                </h3>
                                <div class="mt-2">
                                    @if($relatedProduct->compare_price > $relatedProduct->price)
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ number_format($relatedProduct->price, 2) }}
                                            <span class="text-xs text-gray-500 line-through ml-1">
                                                {{ number_format($relatedProduct->compare_price, 2) }}
                                            </span>
                                        </p>
                                    @else
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ number_format($relatedProduct->price, 2) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Quantity controls
    document.addEventListener('DOMContentLoaded', function() {
        const minusBtn = document.querySelector('button:has(.fa-minus)');
        const plusBtn = document.querySelector('button:has(.fa-plus)');
        const quantityInput = document.getElementById('quantity');
        const addToCartBtn = document.querySelector('button:contains("Add to cart")');

        minusBtn?.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value) || 1;
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        plusBtn?.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value) || 1;
            quantityInput.value = currentValue + 1;
        });

        addToCartBtn?.addEventListener('click', function() {
            const quantity = parseInt(quantityInput.value) || 1;
            // Add to cart functionality here
            console.log('Add to cart:', {{ $product->id }}, 'Quantity:', quantity);
            
            // Show success message
            alert('Product added to cart!');
        });
    });
</script>
@endpush
