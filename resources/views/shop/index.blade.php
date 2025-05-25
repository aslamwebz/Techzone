@extends('layouts.shop')

@section('title', 'TechZone - Your One-Stop Tech Shop')

@push('styles')
<style>
    /* Hero Section */
    .hero {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 80vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    
    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, rgba(79, 70, 229, 0.8), rgba(124, 58, 237, 0.8));
        z-index: 1;
        opacity: 0.7;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
        padding: 2rem;
        animation: fadeInUp 1s ease-out;
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
<section class="hero relative">
    <div class="hero-content">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Welcome to TechZone</h1>
        <p class="text-xl text-white mb-8">Discover the latest tech gadgets and electronics at unbeatable prices.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('shop') }}" class="bg-white text-indigo-600 hover:bg-gray-100 px-8 py-3 rounded-full font-semibold text-lg transition duration-300 transform hover:scale-105 inline-block text-center">
                Shop Now
            </a>
            <a href="#featured" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-indigo-600 px-8 py-3 rounded-full font-semibold text-lg transition duration-300 transform hover:scale-105 inline-block text-center">
                Featured Products
            </a>
        </div>
    </div>
</section>

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
                <a href="{{ route('shop.category', $category['slug']) }}" class="category-card group">
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
            @foreach($featuredProducts as $index => $product)
                <div class="product-card group" style="animation: fadeInUp 0.5s ease-out {{ $index * 0.1 }}s both;">
                    <div class="relative">
                        @if($product->sale_price)
                            <span class="product-badge">Sale</span>
                        @elseif($product->is_new)
                            <span class="product-badge" style="background: #3B82F6;">New</span>
                        @endif
                        <div class="product-image">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}" alt="{{ $product->name }}">
                        </div>
                        <div class="p-4">
                            <div class="text-sm text-gray-500 mb-1">{{ $product->category->name ?? 'Uncategorized' }}</div>
                            <h3 class="font-semibold text-lg mb-2">
                                <a href="{{ route('product.show', $product->slug) }}" class="hover:text-indigo-600 transition">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($product->sale_price)
                                        <span class="text-lg font-bold text-indigo-600">₦{{ number_format($product->sale_price, 2) }}</span>
                                        <span class="text-sm text-gray-500 line-through ml-2">₦{{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="text-lg font-bold text-indigo-600">₦{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <button class="add-to-cart bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition" 
                                        data-product-id="{{ $product->id }}">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
            @foreach($newArrivals as $index => $product)
                <div class="product-card group" style="animation: fadeInUp 0.5s ease-out {{ $index * 0.1 + 0.2 }}s both;">
                    <div class="relative">
                        @if($product->sale_price)
                            <span class="product-badge">Sale</span>
                        @elseif($product->is_new)
                            <span class="product-badge" style="background: #3B82F6;">New</span>
                        @endif
                        <div class="product-image">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}" alt="{{ $product->name }}">
                        </div>
                        <div class="p-4">
                            <div class="text-sm text-gray-500 mb-1">{{ $product->category->name ?? 'Uncategorized' }}</div>
                            <h3 class="font-semibold text-lg mb-2">
                                <a href="{{ route('product.show', $product->slug) }}" class="hover:text-indigo-600 transition">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($product->sale_price)
                                        <span class="text-lg font-bold text-indigo-600">₦{{ number_format($product->sale_price, 2) }}</span>
                                        <span class="text-sm text-gray-500 line-through ml-2">₦{{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="text-lg font-bold text-indigo-600">₦{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <button class="add-to-cart bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition" 
                                        data-product-id="{{ $product->id }}">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
    // Add to cart functionality
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
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
