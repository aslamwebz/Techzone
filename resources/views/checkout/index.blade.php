@extends('layouts.shop')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')
<div class="bg-indigo-700 text-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Checkout</h1>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-indigo-200 hover:text-white">
                        <i class="fas fa-home mr-2"></i>
                        Home
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <i class="fas fa-chevron-right text-indigo-300 mx-2"></i>
                    <a href="{{ route('cart.index') }}" class="text-sm font-medium text-indigo-200 hover:text-white">Shopping Cart</a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-indigo-300 mx-2"></i>
                        <span class="text-sm font-medium text-white">Checkout</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="py-12">
    <div class="container mx-auto px-4">
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Checkout Form -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Billing Details</h2>
                    
                    <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                <input type="text" id="first_name" name="first_name" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('first_name', $user->first_name ?? '') }}" required>
                            </div>
                            
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" id="last_name" name="last_name" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('last_name', $user->last_name ?? '') }}" required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('email', $user->email ?? '') }}" required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
                                <input type="tel" id="phone" name="phone" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('phone', $user->phone ?? '') }}" required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Street Address <span class="text-red-500">*</span></label>
                                <input type="text" id="address" name="address" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('address', $user->address ?? '') }}" required>
                            </div>
                            
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Town / City <span class="text-red-500">*</span></label>
                                <input type="text" id="city" name="city" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('city', $user->city ?? '') }}" required>
                            </div>
                            
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State <span class="text-red-500">*</span></label>
                                <input type="text" id="state" name="state" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('state', $user->state ?? '') }}" required>
                            </div>
                            
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postcode / ZIP <span class="text-red-500">*</span></label>
                                <input type="text" id="postal_code" name="postal_code" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('postal_code', $user->postal_code ?? '') }}" required>
                            </div>
                            
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                                <select id="country" name="country" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="">Select a country</option>
                                    <option value="Nigeria" {{ (old('country', $user->country ?? '') == 'Nigeria') ? 'selected' : '' }}>Nigeria</option>
                                    <!-- Add more countries as needed -->
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Method</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input id="cash_on_delivery" name="payment_method" type="radio" value="cash_on_delivery" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" checked>
                                    <label for="cash_on_delivery" class="ml-3 block text-sm font-medium text-gray-700">
                                        Cash on Delivery
                                    </label>
                                </div>
                                <!-- Add more payment methods as needed -->
                            </div>
                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6 rounded-md hover:shadow-lg hover:shadow-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Your Order</h2>
                    
                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                            <div class="flex justify-between">
                                <div class="flex items-center">
                                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                        <img src="{{ $item->options->image ?? 'https://via.placeholder.com/150' }}" alt="{{ $item->name }}" class="h-full w-full object-cover">
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $item->name }}</h3>
                                        <p class="text-sm text-gray-500">Qty: {{ $item->qty }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ format_currency($item->price * $item->qty) }}</p>
                                    @if(isset($item->options->original_price) && $item->options->original_price > $item->price)
                                        <p class="text-xs text-gray-500 line-through">{{ format_currency($item->options->original_price * $item->qty) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between text-base font-medium text-gray-900 mb-2">
                            <p>Subtotal</p>
                            <p>{{ format_currency($subtotal) }}</p>
                        </div>
                        <div class="flex justify-between text-base font-medium text-gray-900 mb-2">
                            <p>Tax</p>
                            <p>{{ format_currency($tax) }}</p>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-900 mt-4 pt-4 border-t border-gray-200">
                            <p>Total</p>
                            <p>{{ format_currency($total) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const form = document.getElementById('checkout-form');
        
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });
</script>
@endpush
