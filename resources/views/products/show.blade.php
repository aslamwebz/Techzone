<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="md:flex">
                        <div class="md:flex-shrink-0">
                            @if($product->image)
                                <img class="h-48 w-full object-cover md:w-48 rounded-lg" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <div class="h-48 w-full bg-gray-200 flex items-center justify-center text-gray-500 rounded-lg">
                                    <span>No image available</span>
                                </div>
                            @endif
                        </div>
                        <div class="mt-4 md:mt-0 md:ml-6">
                            <div class="uppercase tracking-wide text-sm text-indigo-600 font-semibold">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </div>
                            <h1 class="block mt-1 text-lg leading-tight font-medium text-black">
                                {{ $product->name }}
                            </h1>
                            <div class="mt-2 text-2xl font-bold text-gray-900">
                                ${{ number_format($product->price, 2) }}
                            </div>
                            <div class="mt-2">
                                @if($product->stock > 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        In Stock ({{ $product->stock }} available)
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Out of Stock
                                    </span>
                                @endif
                            </div>
                            <p class="mt-3 text-gray-600">
                                {{ $product->description ?? 'No description available.' }}
                            </p>
                            <div class="mt-6">
                                <div class="flex items-center">
                                    <span class="text-gray-700">Status:</span>
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="mt-2 text-sm text-gray-500">
                                    <p>Created: {{ $product->created_at->format('M d, Y') }}</p>
                                    <p>Last updated: {{ $product->updated_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between items-center">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Products
                        </a>
                        <div class="space-x-2">
                            <a href="{{ route('products.edit', $product->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Edit Product
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to delete this product?')">
                                    Delete Product
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
