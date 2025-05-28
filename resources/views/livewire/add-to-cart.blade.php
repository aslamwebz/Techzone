<div class="mt-4">
    @if($inCart)
        <div class="flex items-center space-x-2">
            <button 
                wire:click="removeFromCart"
                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors"
            >
                Remove from Cart
            </button>
            <span class="text-green-600">In Cart ({{ $cartItem->qty }})</span>
        </div>
        @if($showQuantity)
            <div class="flex items-center space-x-2">
                <button wire:click="decrement" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-3 rounded-l">
                    -
                </button>
                <input type="number" wire:model.live="quantity" min="1" class="w-12 text-center border-t border-b border-gray-300">
                <button wire:click="increment" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-3 rounded-r">
                    +
                </button>
                <button wire:click="removeFromCart" class="text-red-500 hover:text-red-700 ml-2">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        @else
            <button class="quick-action-btn bg-green-500 text-white hover:bg-green-600" data-tooltip="In Cart">
                <i class="fas fa-check"></i>
            </button>
        @endif
    @else
        @if($showQuantity)
            <div class="flex items-center space-x-2">
                <input 
                    type="number" 
                    wire:model.live="quantity" 
                    min="1" 
                    max="{{ $product->quantity }}" 
                    class="w-20 px-3 py-2 border rounded focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
                <button 
                    wire:click="addToCart"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md transition-colors duration-300 flex items-center justify-center"
                >
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Add to Cart
                </button>
            </div>
        @else
            <button wire:click="addToCart" class="quick-action-btn bg-indigo-600 text-white hover:bg-indigo-700">
                <i class="fas fa-shopping-cart"></i>
            </button>
        @endif
    @endif
</div>
