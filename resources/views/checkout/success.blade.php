@extends('layouts.shop')

@section('title', 'Order Confirmation - ' . config('app.name'))

@section('content')
<div class="bg-indigo-700 text-white py-12">
    <div class="container mx-auto px-4 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 mb-6">
            <i class="fas fa-check text-green-600 text-4xl"></i>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Thank You For Your Order!</h1>
        <p class="text-xl text-indigo-100 max-w-2xl mx-auto">Your order has been received and is being processed. You will receive an order confirmation email with details of your order.</p>
    </div>
</div>

<div class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-8 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Order Confirmed</h2>
                <p class="text-gray-600 mb-8">Your order #{{ session('order_id', '12345') }} has been placed successfully.</p>
                
                <div class="bg-gray-50 p-6 rounded-lg mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">What's Next?</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-envelope text-indigo-600 text-xl"></i>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-1">Order Confirmation</h4>
                            <p class="text-sm text-gray-500">Check your email for order confirmation and details.</p>
                        </div>
                        <div class="p-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-truck text-indigo-600 text-xl"></i>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-1">Shipping</h4>
                            <p class="text-sm text-gray-500">Your order will be processed and shipped soon.</p>
                        </div>
                        <div class="p-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-phone-alt text-indigo-600 text-xl"></i>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-1">Need Help?</h4>
                            <p class="text-sm text-gray-500">Contact our support team for any questions.</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('shop') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-shopping-bag mr-2"></i> Continue Shopping
                    </a>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-user-circle mr-2"></i> View My Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Summary (Hidden on mobile) -->
<div class="bg-gray-50 py-8 hidden md:block">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">Order Summary</h2>
        
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Address</h3>
                        <address class="not-italic text-gray-600">
                            {{ session('shipping_address.name', 'John Doe') }}<br>
                            {{ session('shipping_address.address', '123 Main St') }}<br>
                            {{ session('shipping_address.city', 'Lagos') }}, {{ session('shipping_address.state', 'Lagos') }} {{ session('shipping_address.postal_code', '100001') }}<br>
                            {{ session('shipping_address.country', 'Nigeria') }}<br>
                            <span class="block mt-2">
                                <span class="font-medium">Email:</span> {{ session('shipping_address.email', 'john@example.com') }}<br>
                                <span class="font-medium">Phone:</span> {{ session('shipping_address.phone', '+234 800 000 0000') }}
                            </span>
                        </address>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Details</h3>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Order Number:</dt>
                                <dd class="font-medium text-gray-900">#{{ session('order_id', '12345') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Date:</dt>
                                <dd class="text-gray-900">{{ now()->format('F j, Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Payment Method:</dt>
                                <dd class="text-gray-900">{{ session('payment_method', 'Cash on Delivery') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Shipping Method:</dt>
                                <dd class="text-gray-900">Standard Shipping</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                    <div class="border-t border-gray-200">
                        @php
                            // Sample order items - in a real app, this would come from the session or database
                            $orderItems = [
                                [
                                    'name' => 'Sample Product 1',
                                    'price' => 25000,
                                    'quantity' => 1,
                                    'image' => 'https://via.placeholder.com/150',
                                    'options' => ['size' => 'M', 'color' => 'Blue']
                                ],
                                [
                                    'name' => 'Sample Product 2',
                                    'price' => 35000,
                                    'quantity' => 2,
                                    'image' => 'https://via.placeholder.com/150',
                                    'options' => ['size' => 'L', 'color' => 'Red']
                                ]
                            ];
                            $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $orderItems));
                            $shipping = 1500; // Example shipping cost
                            $total = $subtotal + $shipping;
                        @endphp
                        
                        @foreach($orderItems as $item)
                            <div class="py-4 flex items-center border-b border-gray-200">
                                <div class="flex-shrink-0 h-16 w-16 rounded-md overflow-hidden border border-gray-200">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex justify-between text-base font-medium text-gray-900">
                                        <h4>{{ $item['name'] }}</h4>
                                        <p class="ml-4">{{ format_currency($item['price'] * $item['quantity']) }}</p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Qty: {{ $item['quantity'] }}</p>
                                    @if(!empty($item['options']))
                                        <div class="mt-1">
                                            @foreach($item['options'] as $key => $value)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mr-2">
                                                    {{ ucfirst($key) }}: {{ $value }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="mt-6">
                            <div class="flex justify-between text-base font-medium text-gray-900 py-2">
                                <p>Subtotal</p>
                                <p>{{ format_currency($subtotal) }}</p>
                            </div>
                            <div class="flex justify-between text-base font-medium text-gray-900 py-2">
                                <p>Shipping</p>
                                <p>{{ format_currency($shipping) }}</p>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-gray-900 pt-4 mt-4 border-t border-gray-200">
                                <p>Total</p>
                                <p>{{ format_currency($total) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Status Timeline -->
<div class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-12">Order Status</h2>
        
        <div class="max-w-3xl mx-auto">
            <div class="relative">
                <!-- Timeline line -->
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                
                <!-- Timeline items -->
                @php
                    $statuses = [
                        ['id' => 1, 'name' => 'Order Placed', 'description' => 'Your order has been received', 'completed' => true, 'active' => false],
                        ['id' => 2, 'name' => 'Processing', 'description' => 'Your order is being processed', 'completed' => true, 'active' => false],
                        ['id' => 3, 'name' => 'Shipped', 'description' => 'Your order is on the way', 'completed' => false, 'active' => true],
                        ['id' => 4, 'name' => 'Delivered', 'description' => 'Your order has been delivered', 'completed' => false, 'active' => false],
                    ];
                @endphp
                
                @foreach($statuses as $status)
                    <div class="relative pb-10 pl-12">
                        <!-- Icon -->
                        @if($status['completed'])
                            <div class="absolute left-0 flex items-center justify-center w-8 h-8 rounded-full bg-indigo-600 text-white">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                        @elseif($status['active'])
                            <div class="absolute left-0 flex items-center justify-center w-8 h-8 rounded-full bg-white border-2 border-indigo-600 text-indigo-600">
                                <i class="fas fa-circle-notch fa-spin"></i>
                            </div>
                        @else
                            <div class="absolute left-0 flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-400">
                                <i class="fas fa-circle text-xs"></i>
                            </div>
                        @endif
                        
                        <!-- Content -->
                        <div class="pt-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $status['name'] }}</h3>
                            <p class="mt-1 text-gray-600">{{ $status['description'] }}</p>
                            @if($status['active'])
                                <p class="mt-2 text-sm text-indigo-600">Estimated delivery: {{ now()->addDays(3)->format('F j, Y') }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-indigo-700">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
            <span class="block">Ready to shop some more?</span>
            <span class="block text-indigo-200">Check out our latest arrivals.</span>
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0
        <div class="inline-flex rounded-md shadow">
            <a href="{{ route('shop') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                Continue Shopping
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        <div class="ml-3 inline-flex rounded-md shadow">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 bg-opacity-60 hover:bg-opacity-70">
                View My Orders
                <i class="fas fa-user-circle ml-2"></i>
            </a>
        </div>
    </div>
</div>
@endsection
