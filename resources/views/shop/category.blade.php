@extends('layouts.shop')

@section('title', $category->name . ' - ' . config('app.name'))

@section('content')
<div class="py-8 bg-white">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
            <p class="text-gray-600 mt-2">{{ $category->description ?? 'Browse our selection of ' . $category->name }}</p>
        </div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div class="mb-4 md:mb-0">
                <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ $products->firstItem() }}</span>
                    to <span class="font-medium">{{ $products->lastItem() }}</span>
                    of <span class="font-medium">{{ $products->total() }}</span> results
                </p>
            </div>
            
            <div class="flex-shrink-0">
                <select id="sort" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                </select>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="product-card group flex flex-col h-full bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                        <!-- Product Image -->
                        <div class="relative pt-[100%] bg-gray-50 overflow-hidden">
                            <a href="{{ route('product.show', $product->slug) }}" class="absolute inset-0 flex items-center justify-center p-4">
                                @if($product->image && file_exists(public_path('storage/' . $product->image)))
                                    <img 
                                        src="{{ asset('storage/' . $product->image) }}" 
                                        alt="{{ $product->name }}" 
                                        class="max-w-full max-h-full w-auto h-auto object-contain transition-transform duration-500 group-hover:scale-110"
                                        loading="lazy"
                                        onerror="this.onerror=null; this.src='https://via.placeholder.com/600x600?text=Image+Not+Available'"
                                    >
                                @else
                                    <img 
                                        src="https://via.placeholder.com/600x600?text=No+Image" 
                                        alt="{{ $product->name }}" 
                                        class="max-w-full max-h-full w-auto h-auto object-contain"
                                        loading="lazy"
                                    >
                                @endif
                            </a>
                            
                            <!-- Badges -->
                            <div class="absolute top-0 left-0 right-0 flex justify-between p-2">
                                @if($product->is_featured)
                                    <span class="bg-yellow-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded">Featured</span>
                                @else
                                    <span class="invisible">Placeholder</span>
                                @endif
                                
                                @if($product->created_at->diffInDays(now()) <= 7)
                                    <span class="bg-green-500 text-white text-xs font-semibold px-2 py-0.5 rounded">New</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="p-4 flex flex-col flex-grow">
                            <!-- Category -->
                            <div class="mb-1 min-h-[20px]">
                                <a href="{{ route('categories.show', $category->slug) }}" wire:navigate class="text-indigo-600 text-sm font-medium hover:text-indigo-800 transition-colors">
                                    {{ $category->name }}
                                </a>
                            </div>
                            
                            <!-- Product Name -->
                            <h3 class="text-gray-900 font-semibold text-lg mb-3 line-clamp-2 h-[3rem]">
                                <a href="{{ route('product.show', $product->slug) }}" wire:navigate class="hover:text-indigo-600 transition-colors">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            
                            <!-- Price and Add to Cart -->
                            <div class="mt-auto pt-2">
                                <div class="flex items-center justify-between">
                                    <div class="min-h-[40px] flex items-center">
                                        @if($product->compare_price > $product->price)
                                            <div>
                                                <span class="text-lg font-bold text-gray-900">₦{{ number_format($product->price, 2) }}</span>
                                                <span class="text-sm text-gray-500 line-through ml-2">₦{{ number_format($product->compare_price, 2) }}</span>
                                            </div>
                                        @else
                                            <span class="text-lg font-bold text-indigo-600">₦{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                    
                                    <button class="add-to-cart bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition flex-shrink-0" 
                                            data-product-id="{{ $product->id }}"
                                            aria-label="Add to cart">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 text-lg mb-4">No products found in this category.</div>
                <a href="{{ route('shop') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-md font-medium hover:bg-indigo-700 transition">
                    Continue Shopping
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Handle sort select change
    document.getElementById('sort').addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', this.value);
        window.location.href = url.toString();
    });
    
    // Add to cart functionality
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-product-id');
                
                // Show loading state
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;
                
                // Make AJAX request to add to cart
                fetch(`/cart/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count in the UI
                        const cartCountElements = document.querySelectorAll('.cart-count');
                        cartCountElements.forEach(el => {
                            el.textContent = data.cart_count;
                            el.classList.remove('hidden');
                            
                            // Add animation
                            el.classList.add('animate-ping');
                            setTimeout(() => {
                                el.classList.remove('animate-ping');
                            }, 500);
                        });
                        
                        // Show success message
                        showNotification('Product added to cart!', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to add product to cart', 'error');
                })
                .finally(() => {
                    // Restore button state
                    this.innerHTML = originalHTML;
                    this.disabled = false;
                });
            });
        });
        
        // Notification function
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white font-medium z-50 transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.add('translate-x-0', 'opacity-100');
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.remove('translate-x-0', 'opacity-100');
                notification.classList.add('translate-x-full', 'opacity-0');
                
                // Remove from DOM after animation
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    });
</script>
@endpush
@endsection
