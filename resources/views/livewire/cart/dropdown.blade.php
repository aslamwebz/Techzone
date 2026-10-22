<div class="p-4">
    <div class="flex items-center justify-between pb-3 border-b">
        <h3 class="font-semibold text-gray-800">Shopping Cart ({{ $count }})</h3>
        <span class="text-sm font-medium text-indigo-600">Total: {{ $total }}</span>
    </div>

    @if($items->count() > 0)
        <div class="max-h-60 overflow-y-auto divide-y divide-gray-100 my-2">
            @foreach($items as $item)
                <div class="py-2 flex items-center justify-between">
                    <div class="flex-1 min-w-0 pr-2">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $item->name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->qty }} &times; {{ number_format($item->price, 2) }}</p>
                    </div>
                    <button wire:click="removeItem('{{ $item->rowId }}')" class="text-red-500 hover:text-red-700 text-xs">
                        &times;
                    </button>
                </div>
            @endforeach
        </div>
        <div class="pt-3 border-t space-y-2">
            <a href="{{ route('cart.index') }}" class="block w-full text-center py-2 px-4 border border-transparent rounded-md shadow-xs text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                View Cart
            </a>
            <a href="{{ route('checkout') }}" class="block w-full text-center py-2 px-4 border border-gray-300 rounded-md shadow-xs text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Checkout
            </a>
        </div>
    @else
        <div class="py-6 text-center text-sm text-gray-500">
            Your cart is empty
        </div>
        <div class="pt-2">
            <a href="{{ route('shop') }}" class="block w-full text-center py-2 px-4 border border-transparent rounded-md shadow-xs text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                Start Shopping
            </a>
        </div>
    @endif
</div>
