<div class="group relative bg-white rounded-lg overflow-hidden shadow-xs hover:shadow-md transition-shadow duration-200">
    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-200 lg:aspect-none lg:h-60">
        <img 
            src="{{ $product->image_url ?? 'https://via.placeholder.com/300' }}" 
            alt="{{ $product->name }}" 
            class="h-full w-full object-cover object-center lg:h-full lg:w-full"
            onerror="this.onerror=null; this.src='https://via.placeholder.com/300?text=No+Image'"
        >
        @if(isset($product->compare_at_price) && $product->compare_at_price > $product->price)
            <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                SALE
            </div>
        @endif
    </div>
    <div class="p-4">
        <div>
            <h3 class="text-sm text-gray-700">
                <a href="{{ route('product.show', $product->slug) }}" class="hover:text-indigo-600">
                    <span aria-hidden="true" class="absolute inset-0"></span>
                    {{ $product->name }}
                </a>
            </h3>
            @if(isset($product->brand) && $product->brand)
                <p class="mt-1 text-sm text-gray-500">{{ $product->brand->name }}</p>
            @endif
        </div>
        <div class="mt-2">
            @if(isset($product->compare_at_price) && $product->compare_at_price > $product->price)
                <p class="text-sm text-gray-900 font-medium flex items-center">
                    <span class="text-lg text-red-600">${{ number_format($product->price / 100, 2) }}</span>
                    <span class="ml-2 text-xs text-gray-500 line-through">${{ number_format($product->compare_at_price / 100, 2) }}</span>
                    @php
                        $discount = (($product->compare_at_price - $product->price) / $product->compare_at_price) * 100;
                    @endphp
                    <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full">
                        -{{ round($discount) }}%
                    </span>
                </p>
            @else
                <p class="text-sm text-gray-900 font-medium">${{ number_format($product->price / 100, 2) }}</p>
            @endif
        </div>
        <div class="mt-4">
            <form action="{{ route('cart.add', $product) }}" method="POST">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-xs text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Add to Cart
                </button>
            </form>
        </div>
    </div>
</div>
