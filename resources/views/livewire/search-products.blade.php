<div class="relative w-full max-w-2xl mx-auto">
    <div class="relative">
        <form wire:submit.prevent="search">
            <input 
                type="text" 
                wire:model="search"
                wire:keydown.enter="search"
                placeholder="Search products..." 
                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-full focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none text-sm transition-all duration-200"
            >
            <button type="submit" class="absolute right-3 top-2.5 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>

    @if(!empty($results))
        <div class="absolute z-50 mt-2 w-full bg-white rounded-md shadow-lg overflow-hidden border border-gray-200">
            <div class="py-1">
                @foreach($results as $product)
                    <a href="{{ route('products.show', $product['slug'] ?? $product['id']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                        <div class="flex items-center">
                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="h-10 w-10 object-cover rounded">
                            <div class="ml-3">
                                <div class="font-medium">{{ $product['name'] }}</div>
                                <div class="text-gray-600">${{ number_format($product['price'], 2) }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
                @if(count($results) === 0)
                    <div class="px-4 py-2 text-sm text-gray-500">No products found</div>
                @endif
            </div>
        </div>
    @endif
</div>
