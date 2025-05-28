@extends('layouts.shop')

@section('title', 'Shopping Cart')

@push('styles')
<style>
    .cart-item {
        transition: all 0.3s ease;
    }
    
    .cart-item:hover {
        background-color: #f9fafb;
    }
    
    .quantity-btn {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f3f4f6;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        user-select: none;
    }
    
    .quantity-btn:hover {
        background-color: #e5e7eb;
    }
    
    .quantity-input {
        width: 50px;
        text-align: center;
        border: 1px solid #e5e7eb;
        -moz-appearance: textfield;
    }
    
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endpush

@section('content')
<div class="bg-indigo-700 text-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Shopping Cart</h1>
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
                        <span class="text-sm font-medium text-white">Shopping Cart</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="py-12">
    <div class="container mx-auto px-4">
        @if(Cart::count() > 0)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Cart Header -->
                <div class="hidden md:grid grid-cols-12 bg-gray-50 border-b px-6 py-4">
                    <div class="col-span-6 font-medium text-gray-500">Product</div>
                    <div class="col-span-2 text-center font-medium text-gray-500">Price</div>
                    <div class="col-span-2 text-center font-medium text-gray-500">Quantity</div>
                    <div class="col-span-2 text-right font-medium text-gray-500">Total</div>
                </div>
                
                <!-- Cart Items -->
                <div id="cart-items">
                    @foreach(Cart::content() as $item)
                        <div class="cart-item border-b p-4 md:p-6" data-id="{{ $item->rowId }}">
                            <div class="flex flex-col md:grid md:grid-cols-12 gap-4">
                                <!-- Product Info -->
                                <div class="flex items-center col-span-6">
                                    <a href="{{ route('product.show', $item->options->slug ?? '#' ) }}" class="w-20 h-20 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                        <img src="{{ $item->options->image ?? 'https://via.placeholder.com/150' }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                    </a>
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('product.show', $item->options->slug ?? '#' ) }}" class="font-medium text-gray-900 hover:text-indigo-600">{{ $item->name }}</a>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">{{ $item->options->description ?? $item->name }}</p>
                                        <!-- Price for mobile -->
                                        <div class="md:hidden mt-2">
                                            @if(isset($item->options->original_price) && $item->options->original_price > $item->price)
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm text-gray-500 line-through">{{ format_currency($item->options->original_price) }}</span>
                                                    <span class="text-sm font-medium text-gray-900">{{ format_currency($item->price) }}</span>
                                                </div>
                                            @else
                                                <span class="text-sm font-medium text-gray-900">{{ format_currency($item->price) }}</span>
                                            @endif
                                        </div>
                                        <button type="button" 
                                                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium mt-2 remove-item" 
                                                onclick="removeFromCart('{{ $item->rowId }}')">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Price (Desktop) -->
                            <div class="hidden md:flex col-span-2 items-center justify-center">
                                <div class="flex flex-col items-center">
                                    @if(isset($item->options->original_price) && $item->options->original_price > $item->price)
                                        <span class="text-sm text-gray-500 line-through">{{ format_currency($item->options->original_price) }}</span>
                                    @endif
                                    <span class="font-medium text-gray-900">{{ format_currency($item->price) }}</span>
                                </div>
                            </div>
                            
                            <!-- Quantity -->
                            <div class="col-span-12 md:col-span-2 flex items-center justify-between md:justify-center my-4 md:my-0">
                                <span class="md:hidden text-sm font-medium text-gray-700">Quantity:</span>
                                <div class="flex items-center border border-gray-200 rounded-md overflow-hidden">
                                    <form action="{{ route('cart.update', $item->rowId) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" 
                                                onclick="updateQuantity('{{ $item->rowId }}', -1)" 
                                                class="w-10 h-10 flex items-center justify-center bg-white text-gray-600 hover:bg-gray-50 focus:outline-none transition-colors">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <input type="number" id="quantity-{{ $item->rowId }}" name="quantity" min="1" value="{{ $item->qty }}" class="quantity-input" onchange="updateQuantity('{{ $item->rowId }}', this.value)">
                                        <button type="button" 
                                                onclick="updateQuantity('{{ $item->rowId }}', 1)" 
                                                class="w-10 h-10 flex items-center justify-center bg-white text-gray-600 hover:bg-gray-50 focus:outline-none transition-colors">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Total -->
                            <div class="col-span-6 md:col-span-2 flex items-center justify-between md:justify-end">
                                <span class="md:hidden text-sm font-medium text-gray-700">Total:</span>
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-900">{{ format_currency($item->price * $item->qty) }}</span>
                                </div>
                            </div>
                            
                            <!-- Remove -->
                            <div class="col-span-6 md:col-span-1 flex items-center justify-end md:justify-center">
                                <form action="{{ route('cart.remove', $item->rowId) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800" onclick="removeFromCart('{{ $item->rowId }}')">
                                        <i class="far fa-trash-alt"></i>
                                        <span class="sr-only">Remove</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Cart Summary -->
                <div class="p-6 bg-gray-50 border-t border-gray-100">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-6 lg:space-y-0">
                        <!-- Coupon Code -->
                        <div class="w-full lg:w-1/3">
                            <div class="flex items-center">
                                <input type="text" 
                                       placeholder="Coupon code" 
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none">
                                <button class="px-6 py-3 bg-gray-800 text-white font-medium rounded-r-lg hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800 transition-colors">
                                    Apply
                                </button>
                            </div>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="w-full lg:w-1/3 bg-white p-6 rounded-xl shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Order Summary</h3>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <div class="text-lg font-bold">{{ format_currency(Cart::subtotal()) }}</div>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="text-green-600 font-medium">Free</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-500">
                                    <span>Estimated Delivery</span>
                                    <span>2-4 business days</span>
                                </div>
                            </div>
                            
                            <div class="pt-4 border-t border-gray-100">
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-lg font-bold">Total</span>
                                    <span class="text-2xl font-bold text-indigo-600">{{ format_currency(Cart::total()) }}</span>
                                </div>
                                
                                <a href="{{ route('checkout') }}" 
                                   class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center py-3 px-6 rounded-lg hover:shadow-lg hover:shadow-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    Proceed to Checkout
                                </a>
                                
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-gray-500">or</p>
                                    <a href="{{ route('shop') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium mt-2 transition-colors">
                                        <i class="fas fa-arrow-left mr-2 text-sm"></i> Continue Shopping
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Secure Payment Info -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8" data-aos="fade-up">
                <div class="text-center">
                    <div class="flex justify-center space-x-1 mb-4">
                        <i class="fas fa-lock text-green-500 text-xl"></i>
                        <h3 class="font-medium text-gray-900">Secure Payment</h3>
                    </div>
                    <p class="text-gray-600 mb-4 max-w-2xl mx-auto">
                        Your payment information is processed securely. We do not store credit card details nor have access to your credit card information.
                    </p>
                    <div class="flex flex-wrap justify-center items-center space-x-6">
                        <i class="fab fa-cc-visa text-3xl text-gray-400 hover:text-gray-700 transition-colors"></i>
                        <i class="fab fa-cc-mastercard text-3xl text-gray-400 hover:text-gray-700 transition-colors"></i>
                        <i class="fab fa-cc-amex text-3xl text-gray-400 hover:text-gray-700 transition-colors"></i>
                        <i class="fab fa-cc-paypal text-3xl text-gray-400 hover:text-gray-700 transition-colors"></i>
                        <i class="fab fa-apple-pay text-3xl text-gray-400 hover:text-gray-700 transition-colors"></i>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden py-16 text-center" data-aos="zoom-in">
                <div class="max-w-md mx-auto px-4">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-indigo-50 mb-6">
                        <i class="fas fa-shopping-cart text-4xl text-indigo-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">Your cart is empty</h2>
                    <p class="text-gray-600 mb-8">Looks like you haven't added anything to your cart yet.</p>
                    <a href="{{ route('shop') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
                    </a>
                </div>
            </div>
            
            <!-- Recommended Products -->
            <div class="mt-16" data-aos="fade-up">
                <h3 class="text-xl font-bold text-gray-900 mb-6">You might also like</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Product Card 1 -->
                    <div class="group bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <div class="relative overflow-hidden">
                            <img src="https://via.placeholder.com/300x300" alt="Product" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center text-gray-700 hover:bg-indigo-600 hover:text-white transition-colors">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <span class="text-xs font-medium text-indigo-600">Category</span>
                            <h4 class="font-medium text-gray-900 mt-1 hover:text-indigo-600 transition-colors">
                                <a href="#">Wireless Earbuds</a>
                            </h4>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-lg font-bold text-gray-900">$99.99</span>
                                <button class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-colors">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Repeat for other products -->
                    <div class="group bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <div class="relative overflow-hidden">
                            <img src="https://via.placeholder.com/300x300" alt="Product" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center text-gray-700 hover:bg-indigo-600 hover:text-white transition-colors">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <span class="text-xs font-medium text-indigo-600">Category</span>
                            <h4 class="font-medium text-gray-900 mt-1 hover:text-indigo-600 transition-colors">
                                <a href="#">Smart Watch</a>
                            </h4>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-lg font-bold text-gray-900">$199.99</span>
                                <button class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-colors">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Update quantity function
    function updateQuantity(rowId, value) {
        fetch(`/cart/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                rowId: rowId,
                qty: value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to update the cart
                window.location.reload();
            } else {
                showAlert('Failed to update cart: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while updating the cart', 'error');
        });
    }
    
    // Remove item from cart
    function removeFromCart(rowId) {
        if (!confirm('Are you sure you want to remove this item from your cart?')) {
            return;
        }

        fetch(`/cart/remove/${rowId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the item from the DOM
                const itemElement = document.querySelector(`[data-id="${rowId}"]`);
                if (itemElement) {
                    itemElement.remove();
                }
                // Update the cart count in the header
                updateCartCount(data.cart_count);
                // Reload the page if cart is empty
                if (data.cart_count === 0) {
                    window.location.reload();
                }
                showAlert('Item removed from cart', 'success');
            } else {
                showAlert('Failed to remove item from cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while removing the item', 'error');
        });
    }
    
    // Update cart count in the header
    function updateCartCount(count) {
        document.querySelectorAll('.cart-count').forEach(element => {
            element.textContent = count;
        });
    }
    
    // Show alert message
    function showAlert(message, type = 'info') {
        // Remove any existing alerts
        const existingAlert = document.querySelector('.alert-message');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Create alert element
        const alert = document.createElement('div');
        alert.className = `alert-message fixed top-4 right-4 px-6 py-4 rounded-md shadow-lg text-white ${type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500'}`;
        alert.textContent = message;
        
        // Add to DOM
        document.body.appendChild(alert);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS if available
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 600,
                once: true,
                offset: 100
            });
        }
        
        // Update cart count on page load
        const cartCountElements = document.querySelectorAll('.cart-count');
        if (cartCountElements.length > 0) {
            const count = {{ Cart::count() }};
            cartCountElements.forEach(element => {
                element.textContent = count;
            });
        }
    });
</script>
@endsection
