@extends('layouts.shop')

@section('title', $product->name . ' - ' . config('app.name'))

@push('styles')
<style>
    .product-gallery {
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
        background: #f9fafb;
    }
    
    .main-image {
        width: 100%;
        height: 400px;
        object-fit: contain;
        background: white;
        padding: 2rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }
    
    .thumbnail-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .thumbnail {
        height: 80px;
        border: 2px solid transparent;
        border-radius: 0.375rem;
        overflow: hidden;
        cursor: pointer;
        background: white;
        padding: 0.5rem;
    }
    
    .thumbnail:hover, .thumbnail.active {
        border-color: #4F46E5;
    }
    
    .product-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: #EF4444;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        z-index: 10;
    }
    
    .product-actions {
        display: flex;
        gap: 0.75rem;
        margin: 1.5rem 0;
    }
    
    .quantity-selector {
        display: flex;
        align-items: center;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        overflow: hidden;
    }
    
    .quantity-btn {
        width: 2.5rem;
        height: 2.5rem;
        background: #f3f4f6;
        border: none;
        cursor: pointer;
    }
    
    .quantity-input {
        width: 3.5rem;
        height: 2.5rem;
        text-align: center;
        border: none;
        border-left: 1px solid #d1d5db;
        border-right: 1px solid #d1d5db;
    }
    
    .add-to-cart-btn, .buy-now-btn {
        flex: 1;
        height: 2.5rem;
        border: none;
        border-radius: 0.375rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
    }
    
    .add-to-cart-btn {
        background: #4F46E5;
        color: white;
    }
    
    .buy-now-btn {
        background: #111827;
        color: white;
    }
    
    .tabs {
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        margin: 2rem 0 1.5rem;
    }
    
    .tab {
        padding: 0.75rem 1.5rem;
        cursor: pointer;
        font-weight: 500;
        color: #6b7280;
        border-bottom: 2px solid transparent;
    }
    
    .tab.active {
        color: #4F46E5;
        border-bottom-color: #4F46E5;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .related-product {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        overflow: hidden;
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .related-product-image {
        height: 200px;
        overflow: hidden;
    }
    
    @media (max-width: 768px) {
        .main-image {
            height: 300px;
        }
        
        .product-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-50 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <i class="fas fa-home mr-2"></i>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                        <a href="{{ route('shop') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Shop</a>
                    </div>
                </li>
                @if($product->category)
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                        <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">
                            {{ $product->category->name }}
                        </a>
                    </div>
                </li>
                @endif
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                        <span class="text-sm font-medium text-gray-500">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Product Details -->
<div class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Gallery -->
            <div class="product-gallery">
                @if($product->sale_price)
                    <span class="product-badge">Sale</span>
                @elseif($product->is_new)
                    <span class="product-badge" style="background: #3B82F6;">New</span>
                @endif
                
                <div class="main-image-container">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/600x500?text=No+Image' }}" 
                         alt="{{ $product->name }}" 
                         class="main-image"
                         id="mainImage">
                </div>
                
                <div class="thumbnail-container">
                    @php
                        $productImages = [
                            $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=No+Image',
                            'https://via.placeholder.com/300x300/4F46E5/FFFFFF?text=Product+1',
                            'https://via.placeholder.com/300x300/3B82F6/FFFFFF?text=Product+2',
                            'https://via.placeholder.com/300x300/10B981/FFFFFF?text=Product+3',
                        ];
                    @endphp
                    
                    @foreach($productImages as $index => $image)
                        <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" 
                             data-image="{{ $image }}"
                             onclick="changeMainImage(this, '{{ $image }}')">
                            <img src="{{ $image }}" alt="Thumbnail {{ $index + 1 }}" class="w-full h-full object-contain">
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="product-info">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                <div class="flex items-center mb-4">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $product->rating)
                                <i class="fas fa-star text-yellow-400"></i>
                            @elseif($i - 0.5 <= $product->rating)
                                <i class="fas fa-star-half-alt text-yellow-400"></i>
                            @else
                                <i class="far fa-star text-yellow-400"></i>
                            @endif
                        @endfor
                        <span class="text-gray-600 ml-2">({{ $product->reviews_count }} reviews)</span>
                    </div>
                    <span class="mx-2 text-gray-300">|</span>
                    <span class="text-green-600 text-sm font-medium">In Stock</span>
                </div>
                
                <div class="mb-6">
                    @if($product->sale_price)
                        <div class="flex items-center">
                            <span class="text-3xl font-bold text-gray-900">₦{{ number_format($product->sale_price, 2) }}</span>
                            <span class="ml-2 text-lg text-gray-500 line-through">₦{{ number_format($product->price, 2) }}</span>
                            <span class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                            </span>
                        </div>
                    @else
                        <div class="text-3xl font-bold text-gray-900">₦{{ number_format($product->price, 2) }}</div>
                    @endif
                </div>
                
                <p class="text-gray-600 mb-6">{{ $product->short_description ?? 'No description available.' }}</p>
                
                <div class="product-actions">
                    <div class="quantity-selector">
                        <button type="button" class="quantity-btn" onclick="updateQuantity('decrease')">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="10" class="quantity-input">
                        <button type="button" class="quantity-btn" onclick="updateQuantity('increase')">+</button>
                    </div>
                    
                    <button class="add-to-cart-btn" onclick="addToCart({{ $product->id }})">
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>
                    
                    <button class="buy-now-btn" onclick="buyNow({{ $product->id }})">
                        <i class="fas fa-bolt"></i>
                        Buy Now
                    </button>
                </div>
                
                <div class="flex flex-wrap gap-4 py-4 border-t border-b border-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-tag text-indigo-600 mr-2"></i>
                        <span>Category: <a href="{{ $product->category ? route('shop', ['category' => $product->category->slug]) : '#' }}" class="text-indigo-600 hover:underline">{{ $product->category->name ?? 'Uncategorized' }}</a></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-share-alt text-indigo-600 mr-2"></i>
                        <span>Share: 
                            <a href="#" class="text-gray-500 hover:text-indigo-600 ml-1"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="text-gray-500 hover:text-indigo-600 ml-1"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-gray-500 hover:text-indigo-600 ml-1"><i class="fab fa-instagram"></i></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="mt-16">
            <div class="tabs">
                <div class="tab active" onclick="openTab('description')">Description</div>
                <div class="tab" onclick="openTab('specifications')">Specifications</div>
                <div class="tab" onclick="openTab('reviews')">Reviews ({{ $product->reviews_count }})</div>
            </div>
            
            <div id="description" class="tab-content active py-6">
                {!! $product->description ?? '<p>No description available.</p>' !!}
            </div>
            
            <div id="specifications" class="tab-content py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">General</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-0 py-2 text-sm font-medium text-gray-500 w-1/3">Brand</td>
                                    <td class="px-0 py-2 text-sm text-gray-900">{{ $product->brand ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="px-0 py-2 text-sm font-medium text-gray-500">Model</td>
                                    <td class="px-0 py-2 text-sm text-gray-900">{{ $product->model ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="px-0 py-2 text-sm font-medium text-gray-500">SKU</td>
                                    <td class="px-0 py-2 text-sm text-gray-900">{{ $product->sku ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Dimensions & Weight</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-0 py-2 text-sm font-medium text-gray-500 w-1/3">Weight</td>
                                    <td class="px-0 py-2 text-sm text-gray-900">{{ $product->weight ? $product->weight . ' kg' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="px-0 py-2 text-sm font-medium text-gray-500">Dimensions</td>
                                    <td class="px-0 py-2 text-sm text-gray-900">
                                        @if($product->length && $product->width && $product->height)
                                            {{ $product->length }} x {{ $product->width }} x {{ $product->height }} cm
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div id="reviews" class="tab-content py-6">
                <div class="text-center py-12">
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Customer Reviews</h3>
                    <p class="text-gray-600 mb-6">Be the first to review "{{ $product->name }}"</p>
                    <button class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition">
                        Write a Review
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">You May Also Like</h2>
            <div class="w-20 h-1 bg-indigo-600 mx-auto"></div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
                <div class="related-product">
                    <div class="relative">
                        @if($relatedProduct->sale_price)
                            <span class="product-badge">Sale</span>
                        @elseif($relatedProduct->is_new)
                            <span class="product-badge" style="background: #3B82F6;">New</span>
                        @endif
                        
                        <div class="related-product-image">
                            <a href="{{ route('product.show', $relatedProduct->slug) }}">
                                <img src="{{ $relatedProduct->image ? asset('storage/' . $relatedProduct->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}" 
                                     alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover">
                            </a>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2">
                                <a href="{{ route('product.show', $relatedProduct->slug) }}" class="hover:text-indigo-600 transition">
                                    {{ $relatedProduct->name }}
                                </a>
                            </h3>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($relatedProduct->sale_price)
                                        <span class="text-lg font-bold text-indigo-600">₦{{ number_format($relatedProduct->sale_price, 2) }}</span>
                                        <span class="text-sm text-gray-500 line-through ml-1">₦{{ number_format($relatedProduct->price, 2) }}</span>
                                    @else
                                        <span class="text-lg font-bold text-indigo-600">₦{{ number_format($relatedProduct->price, 2) }}</span>
                                    @endif
                                </div>
                                <button class="text-gray-400 hover:text-indigo-600" 
                                        onclick="addToCart({{ $relatedProduct->id }})">
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
@endif

<!-- Newsletter -->
<section class="py-12 bg-indigo-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Subscribe to Our Newsletter</h2>
        <p class="text-indigo-100 max-w-2xl mx-auto mb-8">Get the latest updates on new products and upcoming sales</p>
        
        <form class="max-w-md mx-auto flex">
            <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 rounded-l-lg focus:outline-none text-gray-900">
            <button type="submit" class="bg-indigo-800 hover:bg-indigo-900 text-white px-6 rounded-r-lg font-semibold transition">
                Subscribe
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Change main product image when thumbnail is clicked
    function changeMainImage(thumbnail, imageUrl) {
        // Update active thumbnail
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        thumbnail.classList.add('active');
        
        // Update main image
        const mainImage = document.getElementById('mainImage');
        mainImage.style.opacity = 0;
        
        // Wait for fade out, then change src and fade in
        setTimeout(() => {
            mainImage.src = imageUrl;
            mainImage.style.opacity = 1;
        }, 150);
    }
    
    // Update quantity
    function updateQuantity(action) {
        const quantityInput = document.getElementById('quantity');
        let quantity = parseInt(quantityInput.value);
        
        if (action === 'increase' && quantity < 10) {
            quantityInput.value = quantity + 1;
        } else if (action === 'decrease' && quantity > 1) {
            quantityInput.value = quantity - 1;
        }
    }
    
    // Add to cart
    function addToCart(productId) {
        const quantity = document.getElementById('quantity').value;
        
        // Show loading state
        const addToCartBtn = document.querySelector('.add-to-cart-btn');
        const originalText = addToCartBtn.innerHTML;
        addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        addToCartBtn.disabled = true;
        
        // Make AJAX request to add to cart
        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                quantity: quantity
            })
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
            addToCartBtn.innerHTML = originalText;
            addToCartBtn.disabled = false;
        });
    }
    
    // Buy now
    function buyNow(productId) {
        addToCart(productId);
        // Redirect to checkout after a short delay
        setTimeout(() => {
            window.location.href = '/checkout';
        }, 1000);
    }
    
    // Show notification
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
    
    // Tab functionality
    function openTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Show the selected tab content
        document.getElementById(tabName).classList.add('active');
        
        // Add active class to the clicked tab
        event.currentTarget.classList.add('active');
    }
</script>
@endpush
