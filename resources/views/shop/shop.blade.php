@extends('layouts.shop')

@section('title', 'Shop - ' . config('app.name'))

@push('styles')
<style>
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .product-card {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        overflow: hidden;
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .product-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: #EF4444;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 10;
    }
    
    .product-image {
        height: 200px;
        overflow: hidden;
        position: relative;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    
    .category-item {
        display: block;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        color: #4B5563;
        transition: all 0.2s ease;
    }
    
    .category-item:hover, .category-item.active {
        background-color: #EEF2FF;
        color: #4F46E5;
        font-weight: 500;
    }
    
    .filter-section {
        transition: all 0.3s ease;
    }
    
    .filter-section.collapsed {
        max-height: 3rem;
        overflow: hidden;
    }
    
    .filter-toggle {
        transition: transform 0.3s ease;
    }
    
    .filter-toggle.rotated {
        transform: rotate(180deg);
    }
    
    /* Custom checkbox */
    .custom-checkbox {
        position: relative;
        padding-left: 1.75rem;
        cursor: pointer;
        display: block;
        user-select: none;
    }
    
    .custom-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }
    
    .checkmark {
        position: absolute;
        top: 0.25rem;
        left: 0;
        height: 1.25rem;
        width: 1.25rem;
        background-color: #F3F4F6;
        border: 1px solid #D1D5DB;
        border-radius: 0.25rem;
        transition: all 0.2s ease;
    }
    
    .custom-checkbox:hover input ~ .checkmark {
        background-color: #E5E7EB;
    }
    
    .custom-checkbox input:checked ~ .checkmark {
        background-color: #4F46E5;
        border-color: #4F46E5;
    }
    
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }
    
    .custom-checkbox input:checked ~ .checkmark:after {
        display: block;
    }
    
    .custom-checkbox .checkmark:after {
        left: 7px;
        top: 3px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    
    /* Price range slider */
    .price-slider {
        -webkit-appearance: none;
        width: 100%;
        height: 6px;
        border-radius: 3px;
        background: #E5E7EB;
        outline: none;
    }
    
    .price-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #4F46E5;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .price-slider::-webkit-slider-thumb:hover {
        transform: scale(1.1);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="bg-indigo-700 text-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Shop</h1>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-indigo-200 hover:text-white">
                        <i class="fas fa-home mr-2"></i>
                        Home
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-indigo-300 mx-2"></i>
                        <span class="text-sm font-medium text-white ml-2">Shop</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main Content -->
<div class="py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="w-full md:w-64 flex-shrink-0">
                <!-- Categories -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4 flex justify-between items-center">
                        <span>Categories</span>
                        <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                    </h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('shop') }}" class="category-item {{ !request('category') ? 'active' : '' }}">
                                All Categories
                                <span class="float-right text-sm text-gray-500">
                                    {{ \App\Models\Product::count() }}
                                </span>
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('shop', ['category' => $category->slug]) }}" 
                                   class="category-item {{ request('category') === $category->slug ? 'active' : '' }}">
                                    {{ $category->name }}
                                    <span class="float-right text-sm text-gray-500">
                                        {{ $category->products_count }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Price Range -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Price Range</h3>
                    <div class="mb-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-600">₦0</span>
                            <span class="text-sm text-gray-600">₦{{ number_format(100000, 0, '.', ',') }}</span>
                        </div>
                        <input type="range" min="0" max="100000" value="50000" class="price-slider" id="priceRange">
                        <div class="flex justify-between mt-2">
                            <span class="text-sm font-medium">Price: <span id="priceValue">₦0 - ₦50,000</span></span>
                        </div>
                    </div>
                    <button class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition">
                        Filter
                    </button>
                </div>

                <!-- Brands -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Brands</h3>
                    <div class="space-y-2">
                        @php
                            $brands = [
                                'Apple', 'Samsung', 'Sony', 'Microsoft', 'Dell',
                                'HP', 'Lenovo', 'Asus', 'Acer', 'LG'
                            ];
                        @endphp
                        @foreach($brands as $brand)
                            <label class="custom-checkbox flex items-center">
                                {{ $brand }}
                                <input type="checkbox" name="brands[]" value="{{ strtolower($brand) }}" class="mr-2">
                                <span class="checkmark"></span>
                                <span class="ml-auto text-sm text-gray-500">
                                    {{ rand(5, 50) }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Rating -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Customer Review</h3>
                    <div class="space-y-2">
                        @for($i = 5; $i >= 1; $i--)
                            <label class="custom-checkbox flex items-center">
                                <div class="flex items-center">
                                    @for($j = 1; $j <= 5; $j++)
                                        @if($j <= $i)
                                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                                        @else
                                            <i class="far fa-star text-yellow-400 text-sm"></i>
                                        @endif
                                    @endfor
                                </div>
                                <input type="checkbox" name="ratings[]" value="{{ $i }}" class="mr-2">
                                <span class="checkmark"></span>
                                <span class="ml-auto text-sm text-gray-500">
                                    &amp; Up ({{ rand(10, 100) }})
                                </span>
                            </label>
                        @endfor
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1">
                <!-- Sorting and View Options -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="text-sm text-gray-600">
                        Showing <span class="font-medium">{{ $products->firstItem() }}</span> to 
                        <span class="font-medium">{{ $products->lastItem() }}</span> of 
                        <span class="font-medium">{{ $products->total() }}</span> results
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <span class="mr-2 text-sm text-gray-600">Sort by:</span>
                            <div class="relative">
                                <select id="sortBy" class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                                    <option value="top_rated" {{ request('sort') === 'top_rated' ? 'selected' : '' }}>Top Rated</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="hidden sm:flex items-center space-x-2">
                            <button class="p-2 rounded-md bg-indigo-100 text-indigo-600">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($products as $product)
                        <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                            <!-- Product Card -->
                            <div class="relative group">
                                <!-- Product Image Link -->
                                <a href="{{ route('product.show', $product->slug) }}" class="block relative group">
                                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center overflow-hidden">
                                        @if($product->image && file_exists(public_path('storage/' . $product->image)))
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                             onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden')">
                                        @endif
                                        <div class="absolute inset-0 flex items-center justify-center p-4 text-center {{ $product->image ? 'hidden' : '' }}">
                                            <div class="text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-sm font-medium">No Image Available</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                
                                <!-- Product Info -->
                                <div class="p-4">
                                    <div class="text-sm text-gray-500 mb-1">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </div>
                                    
                                    <h3 class="font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('product.show', $product->slug) }}" class="hover:text-indigo-600">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <div class="flex items-center justify-between mt-2">
                                        <div>
                                            @if($product->sale_price)
                                                <span class="text-lg font-bold text-indigo-600">₦{{ number_format($product->sale_price, 2) }}</span>
                                                <span class="text-sm text-gray-400 line-through ml-1">₦{{ number_format($product->price, 2) }}</span>
                                            @else
                                                <span class="text-lg font-bold text-indigo-600">₦{{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>

                                        <button type="button" 
                                                class="add-to-cart p-2 text-indigo-600 hover:bg-indigo-50 rounded-full"
                                                data-product-id="{{ $product->id }}"
                                                title="Add to Cart">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>

                                    @if($product->rating > 0)
                                    <div class="mt-2 flex items-center">
                                        <div class="flex text-yellow-400 text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($product->rating))
                                                    <i class="fas fa-star"></i>
                                                @elseif($i - 0.5 <= $product->rating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <span class="ml-1 text-gray-500 text-xs">({{ $product->reviews_count ?? 0 }})</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                @if($product->sale_price)
                                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                        Sale
                                    </span>
                                @elseif($product->is_new)
                                    <span class="absolute top-2 right-2 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">
                                        New
                                    </span>
                                @endif
                            </a>

                            <!-- Product Info -->
                            <div class="p-4">
                                <div class="text-sm text-gray-500 mb-1">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </div>
                                
                                <h3 class="font-semibold text-gray-900 mb-2">
                                    <a href="{{ route('product.show', $product->slug) }}" class="hover:text-indigo-600">
                                        {{ $product->name }}
                                    </a>
                                </h3>

                                <div class="flex items-center justify-between mt-2">
                                    <div>
                                        @if($product->sale_price)
                                            <span class="text-lg font-bold text-indigo-600">₦{{ number_format($product->sale_price, 2) }}</span>
                                            <span class="text-sm text-gray-400 line-through ml-1">₦{{ number_format($product->price, 2) }}</span>
                                        @else
                                            <span class="text-lg font-bold text-indigo-600">₦{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>

                                    <button type="button" 
                                            class="add-to-cart p-2 text-indigo-600 hover:bg-indigo-50 rounded-full"
                                            data-product-id="{{ $product->id }}"
                                            title="Add to Cart">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </div>

                                @if($product->rating > 0)
                                <div class="mt-2 flex items-center">
                                    <div class="flex text-yellow-400 text-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($product->rating))
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $product->rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="ml-1 text-gray-500 text-xs">({{ $product->reviews_count ?? 0 }})</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                        <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-indigo-600 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No products found</h3>
                        <p class="text-gray-600 mb-6">We couldn't find any products matching your criteria.</p>
                        <a href="{{ route('shop') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-md font-medium hover:bg-indigo-700 transition">
                            Clear Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Features -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-truck text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Free Shipping</h3>
                <p class="text-gray-600">Free shipping on all orders over ₦50,000</p>
            </div>
            <div class="text-center p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-undo-alt text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Easy Returns</h3>
                <p class="text-gray-600">30-day return policy for all products</p>
            </div>
            <div class="text-center p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Secure Payment</h3>
                <p class="text-gray-600">We ensure secure payment with PEV</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Price range slider
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    
    if (priceRange && priceValue) {
        priceRange.addEventListener('input', function() {
            const maxPrice = parseInt(this.value).toLocaleString();
            priceValue.textContent = `₦0 - ₦${maxPrice}`;
        });
    }
    
    // Function to get CSRF token
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    // Function to make fetch requests with CSRF token
    async function fetchWithCsrf(url, options = {}) {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            ...options.headers
        };

        const response = await fetch(url, {
            ...options,
            headers
        });

        return response.json();
    }
    
    // Add to cart functionality
    document.addEventListener('click', function(e) {
        // Handle Add to Cart
        if (e.target.closest('.add-to-cart')) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = e.target.closest('.add-to-cart');
            const productId = button.getAttribute('data-product-id');
            const originalHTML = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;
            
            // Make AJAX request using our helper
            fetchWithCsrf(`/cart/add/${productId}`, {
                method: 'POST'
            })
            .then(data => {
                if (data.success) {
                    // Update cart count
                    const cartCountElements = document.querySelectorAll('.cart-count');
                    cartCountElements.forEach(el => {
                        el.textContent = data.cart_count;
                        el.classList.remove('hidden');
                        el.classList.add('animate-ping');
                        setTimeout(() => el.classList.remove('animate-ping'), 500);
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
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        }
        
        // Handle Add to Wishlist
        if (e.target.closest('.add-to-wishlist')) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = e.target.closest('.add-to-wishlist');
            const productId = button.getAttribute('data-product-id');
            
            // Toggle heart icon
            const icon = button.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas', 'text-red-500');
                showNotification('Added to wishlist', 'success');
            } else {
                icon.classList.remove('fas', 'text-red-500');
                icon.classList.add('far');
                showNotification('Removed from wishlist', 'info');
            }
            
            // Here you would typically make an AJAX call to update the wishlist
            // For now, we'll just show a notification
        }
        
        // Handle Quick View
        if (e.target.closest('.quick-view')) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = e.target.closest('.quick-view');
            const productId = button.getAttribute('data-product-id');
            
            // Here you would typically fetch and show a quick view modal
            // For now, we'll just navigate to the product page
            window.location.href = `/product/${productId}`;
        }
    });
    
    // Notification function
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white font-medium z-50 transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.add('opacity-100', 'translate-x-0');
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.remove('opacity-100', 'translate-x-0');
            notification.classList.add('opacity-0', 'translate-x-full');
            
            // Remove from DOM after animation
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
    
    // Sort by functionality
    const sortBy = document.getElementById('sortBy');
    if (sortBy) {
        sortBy.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', this.value);
            window.location.href = url.toString();
        });
    }
    
    // Add to cart functionality
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const productId = this.getAttribute('data-product-id');
                
                // Show loading state
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;
                
                // Make AJAX request using our helper
                fetchWithCsrf(`/cart/add/${productId}`, {
                    method: 'POST'
                })
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
