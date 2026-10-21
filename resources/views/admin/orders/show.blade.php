@extends('admin.layout')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Order {{ $order->order_number }}</h3>
            <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('F d, Y \a\t H:i') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
            &larr; Back to orders
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Items -->
        <div class="lg:col-span-2 bg-white shadow-sm rounded-lg p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Items</h4>
            <div class="divide-y divide-gray-200">
                @foreach($order->items as $item)
                    <div class="py-4 flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} &times; ${{ number_format($item->price, 2) }}</p>
                        </div>
                        <p class="font-medium text-gray-900">${{ number_format($item->total, 2) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-gray-200 pt-4 mt-4 space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Tax</span>
                    <span>${{ number_format($order->tax, 2) }}</span>
                </div>
                <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-200">
                    <span>Total</span>
                    <span>${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Order & Customer Details -->
        <div class="space-y-6">
            <!-- Status Update -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Status</h4>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <select name="status" class="block w-full rounded-md border-gray-300 shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-xs text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Customer & Shipping Info -->
            <div class="bg-white shadow-sm rounded-lg p-6 text-sm">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Customer Details</h4>
                <div class="space-y-2">
                    <p><span class="font-medium text-gray-700">Name:</span> {{ $order->customer_name }}</p>
                    <p><span class="font-medium text-gray-700">Email:</span> {{ $order->email }}</p>
                    <p><span class="font-medium text-gray-700">Phone:</span> {{ $order->phone }}</p>
                    <div class="pt-2 border-t border-gray-200">
                        <p class="font-medium text-gray-700 mb-1">Shipping Address:</p>
                        <p class="text-gray-600">
                            {{ $order->address }}<br>
                            {{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}<br>
                            {{ $order->country }}
                        </p>
                    </div>
                    <div class="pt-2 border-t border-gray-200">
                        <p><span class="font-medium text-gray-700">Payment:</span> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }} ({{ ucfirst($order->payment_status) }})</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
