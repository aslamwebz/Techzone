@extends('layouts.shop')

@section('title', 'TechZone - Your One-Stop Tech Shop')

@push('styles')
<style>
    /* Custom styles can be added here */
    .product-card {
        transition: all 0.3s ease;
        overflow: hidden;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        background: white;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .product-image {
        height: 200px;
        object-fit: contain;
        background: #f8fafc;
        padding: 1rem;
    }
    
    .product-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 3.5rem;
    }
    
    /* Category Cards */
    .category-card {
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
    
    .category-card img {
        transition: transform 0.5s ease;
    }
    
    .category-card:hover img {
        transform: scale(1.05);
    }
    
    .category-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
        padding: 1.5rem 1rem;
        color: white;
        text-align: center;
    }
    
    /* Product Cards */
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
    }
    
    .product-image {
        height: 200px;
        overflow: hidden;
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
    
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-delay-100 {
        animation-delay: 0.1s;
    }
    
    .animate-delay-200 {
        animation-delay: 0.2s;
    }
    
    .animate-delay-300 {
        animation-delay: 0.3s;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .hero {
            min-height: 60vh;
            background-attachment: scroll;
        }
        
        .hero-content h1 {
            font-size: 2rem;
        }
        
        .hero-content p {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<x-hero-slideshow />

<!-- Categories Section -->
<section id="categories" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Shop by Category</h2>
            <div class="w-20 h-1 bg-indigo-600 mx-auto"></div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $categories = [
                    ['name' => 'Smartphones', 'slug' => 'smartphones', 'image' => 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1050&q=80'],
                    ['name' => 'Laptops', 'slug' => 'laptops', 'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=1050&q=80'],
                    ['name' => 'Accessories', 'slug' => 'accessories', 'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1050&q=80'],
                    ['name' => 'Audio', 'slug' => 'audio', 'image' => 'https://images.unsplash.com/photo-1505742011227-a30811ffe4b2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1050&q=80'],
                    ['name' => 'Wearables', 'slug' => 'wearables', 'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&auto=format&fit=crop&w=1050&q=80'],
                    ['name' => 'Gaming', 'slug' => 'gaming', 'image' => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1050&q=80'],
                ];
            @endphp
            
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category['slug']) }}" class="category-card group" wire:navigate>
                    <div class="relative overflow-hidden rounded-lg h-64">
                        <img src="{{ $category['image'] }}" alt="{{ $category['name'] }}" class="w-full h-full object-cover">
                        <div class="category-overlay">
                            <h3 class="text-xl font-bold">{{ $category['name'] }}</h3>
                            <span class="text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">Shop Now <i class="fas fa-arrow-right ml-1"></i></span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
<section id="featured" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Products</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Check out our handpicked selection of premium tech products</p>
            <div class="w-20 h-1 bg-indigo-600 mx-auto mt-4"></div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($featuredProducts as $product)
                <div class="product-card group flex flex-col h-full overflow-hidden bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <!-- Product Image -->
                    <div class="product-image-container">
                        <a href="{{ route('product.show', $product->slug) }}" class="block h-full w-full" wire:navigate>
                            <img 
                                src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x600/e5e7eb/9ca3af?text=No+Image' }}" 
                                alt="{{ $product->name }}" 
                                class="product-image"
                                loading="lazy"
                                onerror="this.onerror=null; this.src='https://placehold.co/600x600/e5e7eb/9ca3af?text=Image+Not+Available'"
                            >
                        </a>
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex flex-col space-y-2">
                            @if($product->is_new)
                                <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md transform transition-transform group-hover:scale-110">
                                    New Arrival
                                </span>
                            @endif
                            @if($product->on_sale)
                                <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md transform transition-transform group-hover:scale-110">
                                    {{ round((1 - $product->price / $product->compare_price) * 100) }}% OFF
                                </span>
                            @endif
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 space-x-3 p-4">
                            <button class="quick-action-btn" data-tooltip="Add to Wishlist">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="quick-action-btn" data-tooltip="Quick View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <div class="inline-block" data-tooltip="Add to Cart">
                                <livewire:add-to-cart :productId="$product->id" :showQuantity="false" />
                            </div>
                        </div>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-4 flex flex-col flex-grow">
                        <!-- Category -->
                        @if($product->category)
                            <a href="{{ route('categories.show', $product->category->slug) }}" class="text-indigo-600 text-sm font-medium hover:text-indigo-800 transition-colors mb-1">
                                {{ $product->category->name }}
                            </a>
                        @endif
                        
                        <!-- Title -->
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2" title="{{ $product->name }}">
                            <a href="{{ route('product.show', $product->slug) }}" class="hover:text-indigo-600 transition-colors">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <!-- Price -->
                        <div class="mt-auto pt-2">
                            @if($product->on_sale && $product->compare_price > $product->price)
                                <div class="flex items-baseline">
                                    <span class="text-lg font-bold text-red-600">${{ number_format($product->price, 2) }}</span>
                                    <span class="ml-2 text-sm text-gray-500 line-through">${{ number_format($product->compare_price, 2) }}</span>
                                </div>
                            @else
                                <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        
                        <!-- Rating -->
                        <div class="mt-2 flex items-center">
                            <div class="flex text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->rating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i == ceil($product->rating) && $product->rating - floor($product->rating) >= 0.5)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-xs text-gray-500 ml-1">({{ $product->reviews_count }})</span>
                        </div>
                        
                        <!-- Price & Add to Cart -->
                        <div class="flex items-center justify-between mt-4">
                            <div>
                                @if($product->compare_price > $product->price)
                                    <span class="text-gray-500 line-through text-sm mr-2">₦{{ number_format($product->compare_price, 2) }}</span>
                                @endif
                                <span class="text-lg font-bold text-gray-900">
                                    ₦{{ number_format($product->price, 2) }}
                                </span>
                            </div>
                            <livewire:add-to-cart :productId="$product->id" :showQuantity="false" />
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-500 text-lg">No products found.</div>
                    <a href="{{ route('shop') }}" class="inline-block mt-4 text-indigo-600 hover:text-indigo-800 font-medium">
                        Clear filters
                    </a>
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('shop') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-indigo-700 transition duration-300 transform hover:scale-105">
                View All Products
            </a>
        </div>
    </div>
</section>

<!-- New Arrivals -->
<section id="new-arrivals" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">New Arrivals</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Discover our latest additions to the collection</p>
            <div class="w-20 h-1 bg-indigo-600 mx-auto mt-4"></div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($newArrivals as $product)
                <div class="product-card group flex flex-col h-full overflow-hidden bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <!-- Product Image -->
                    <div class="product-image-container">
                        <a href="{{ route('product.show', $product->slug) }}" class="block h-full w-full" wire:navigate>
                            <img 
                                src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x600/e5e7eb/9ca3af?text=No+Image' }}" 
                                alt="{{ $product->name }}" 
                                class="product-image"
                                loading="lazy"
                                onerror="this.onerror=null; this.src='https://placehold.co/600x600/e5e7eb/9ca3af?text=Image+Not+Available'"
                            >
                        </a>
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex flex-col space-y-2">
                            @if($product->is_new)
                                <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md transform transition-transform group-hover:scale-110">
                                    New Arrival
                                </span>
                            @endif
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md transform transition-transform group-hover:scale-110">
                                    Sale
                                </span>
                            @endif
                        </div>

                        <!-- Quick Actions -->
                        <div class="absolute top-3 right-3 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="quick-action-btn hover:bg-indigo-100" title="Add to Wishlist">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="quick-action-btn hover:bg-indigo-100" title="Quick View">
                                <i class="far fa-eye"></i>
                            </button>
                            <button class="quick-action-btn hover:bg-indigo-100" title="Add to Compare">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-4 flex flex-col flex-grow">
                        <!-- Category -->
                        @if($product->category)
                            <a href="{{ route('categories.show', $product->category->slug) }}" class="text-indigo-600 text-sm font-medium hover:text-indigo-800 transition-colors mb-1">
                                {{ $product->category->name }}
                            </a>
                        @endif
                        
                        <!-- Title -->
                        <h3 class="text-gray-900 font-semibold text-lg mb-2 line-clamp-2">
                            <a href="{{ route('product.show', $product->slug) }}" class="hover:text-indigo-600 transition-colors">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <!-- Price -->
                        <div class="mt-auto">
                            <div class="flex items-center">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="text-lg font-bold text-gray-900">₦{{ number_format($product->sale_price, 2) }}</span>
                                    <span class="ml-2 text-sm text-gray-500 line-through">₦{{ number_format($product->price, 2) }}</span>
                                    @php
                                        $discount = (($product->price - $product->sale_price) / $product->price) * 100;
                                    @endphp
                                    <span class="ml-2 text-xs font-semibold bg-red-100 text-red-800 px-2 py-0.5 rounded-full">
                                        -{{ round($discount) }}%
                                    </span>
                                @else
                                    <span class="text-lg font-bold text-gray-900">₦{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            
                            <!-- Rating -->
                            <div class="flex items-center mt-2">
                                <div class="flex text-yellow-400">
                                    @php $rating = $product->rating ?: 0; @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($rating))
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @elseif($i == ceil($rating) && $rating - floor($rating) >= 0.5)
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                <defs>
                                                    <linearGradient id="half-star-{{ $i }}" x1="0" x2="50%" y1="0" y2="0">
                                                        <stop offset="50%" stop-color="#F59E0B"/>
                                                        <stop offset="50%" stop-color="#E5E7EB"/>
                                                    </linearGradient>
                                                </defs>
                                                <path fill="url(#half-star-{{ $i }})" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-500 ml-1">({{ $product->reviews_count ?? 0 }})</span>
                            </div>
                            
                            <!-- Add to Cart Button -->
                            <button class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md transition-colors duration-300 flex items-center justify-center space-x-2">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Add to Cart</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-500 text-lg">No new arrivals found.</div>
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('shop') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-indigo-700 transition duration-300 transform hover:scale-105">
                View All New Arrivals
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6 hover:bg-gray-50 rounded-lg transition">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-truck text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Free Shipping</h3>
                <p class="text-gray-600">Free shipping on all orders over ₦50,000</p>
            </div>
            <div class="text-center p-6 hover:bg-gray-50 rounded-lg transition">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exchange-alt text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">30-Day Returns</h3>
                <p class="text-gray-600">30-day return policy for all products</p>
            </div>
            <div class="text-center p-6 hover:bg-gray-50 rounded-lg transition">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">24/7 Support</h3>
                <p class="text-gray-600">Dedicated support team available round the clock</p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-16 bg-indigo-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Subscribe to Our Newsletter</h2>
        <p class="text-indigo-100 max-w-2xl mx-auto mb-8">Get the latest updates on new products and upcoming sales</p>
        <form class="max-w-md mx-auto flex">
            <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-900">
            <button type="submit" class="bg-indigo-800 hover:bg-indigo-900 px-6 rounded-r-lg font-semibold transition">
                Subscribe
            </button>
        </form>
    </div>
</section>

<!-- Contact -->
<section id="contact" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Contact Us</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Have questions? We'd love to hear from you</p>
            <div class="w-20 h-1 bg-indigo-600 mx-auto mt-4"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <h3 class="text-xl font-semibold mb-4">Get in Touch</h3>
                <p class="text-gray-600 mb-6">We're here to help and answer any question you might have. We look forward to hearing from you.</p>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="bg-indigo-100 p-3 rounded-full mr-4">
                            <i class="fas fa-map-marker-alt text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Address</h4>
                            <p class="text-gray-600">123 Tech Street, Silicon Valley, CA 94025, USA</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-indigo-100 p-3 rounded-full mr-4">
                            <i class="fas fa-phone-alt text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Phone</h4>
                            <p class="text-gray-600">+1 234 567 8900</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-indigo-100 p-3 rounded-full mr-4">
                            <i class="fas fa-envelope text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Email</h4>
                            <p class="text-gray-600">support@techzone.com</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-xl font-semibold mb-4">Send Us a Message</h3>
                <form class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <input type="text" id="subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea id="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition w-full">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
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

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize quantity controls
        document.querySelectorAll('.quantity-controls').forEach(control => {
            const input = control.querySelector('.quantity-input');
            const decrementBtn = control.querySelector('.decrement');
            const incrementBtn = control.querySelector('.increment');
            
            // Decrement quantity
            if (decrementBtn) {
                decrementBtn.addEventListener('click', () => {
                    const currentValue = parseInt(input.value);
                    if (currentValue > 1) {
                        input.value = currentValue - 1;
                    }
                });
            }
            
            // Increment quantity
            if (incrementBtn) {
                incrementBtn.addEventListener('click', () => {
                    const currentValue = parseInt(input.value);
                    if (currentValue < 99) {
                        input.value = currentValue + 1;
                    }
                });
            }
        });

        // Add to cart functionality
        document.querySelectorAll('.add-to-cart, [data-product-id]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-product-id');
                const card = this.closest('.product-card');
                let quantity = 1;
                
                // Get quantity from quantity input if available
                if (card) {
                    const quantityInput = card.querySelector('.quantity-input');
                    if (quantityInput) {
                        quantity = parseInt(quantityInput.value) || 1;
                    }
                }
                
                // Show loading state
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;
                
                // Use our fetchWithCsrf helper
                fetchWithCsrf(`/cart/add/${productId}`, {
                    method: 'POST',
                    body: JSON.stringify({ quantity: quantity })
                })
                .then(data => {
                    // Update cart count
                    if (data.success) {
                        // Show success message with product name if available
                        const productName = card ? card.querySelector('.product-title')?.textContent?.trim() || 'Product' : 'Product';
                        showNotification(`${quantity} × ${productName} added to cart!`, 'success');
                        
                        // Update cart count in the header
                        updateCartCount(data.cart_count);
                        
                        // Update cart dropdown if it exists
                        if (window.updateCartDropdown) {
                            window.updateCartDropdown(data.cart);
                        }
                        
                        // Dispatch event for other components to listen to
                        document.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
                    } else {
                        showNotification(data.message || 'Error adding product to cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const message = error.message || 'An error occurred. Please try again.';
                    showNotification(message, 'error');
                })
                .finally(() => {
                    // Restore button state
                    if (this) {
                        this.innerHTML = originalHTML;
                        this.disabled = false;
                    }
                });
            });
        });
        
        // Notification function
        function showNotification(message, type = 'success') {
            // Remove any existing notifications first
            document.querySelectorAll('.notification-toast').forEach(el => el.remove());
            
            const notification = document.createElement('div');
            notification.className = `notification-toast fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white font-medium z-50 transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } opacity-0 -translate-y-2`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('opacity-0', '-translate-y-2');
                notification.classList.add('opacity-100', 'translate-y-0');
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.remove('opacity-100', 'translate-y-0');
                notification.classList.add('opacity-0', 'translate-y-2');
                
                // Remove from DOM after animation
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
        
        // Function to update cart count in the UI
        function updateCartCount(count) {
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(el => {
                el.textContent = count;
                if (count > 0) {
                    el.closest('.relative').classList.remove('hidden');
                } else {
                    el.closest('.relative').classList.add('hidden');
                }
            });
        }

        // Initialize cart count on page load
        fetchWithCsrf('/cart/count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(data => {
            if (data.count > 0) {
                updateCartCount(data.count);
            }
        })
        .catch(error => console.error('Error fetching cart count:', error));
    });
</script>
@endpush
