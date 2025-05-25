<x-app-layout>
    @push('styles')
        <style>
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            .animate-float-delay-1 {
                animation: float 6s ease-in-out 0.5s infinite;
            }
            .animate-float-delay-2 {
                animation: float 6s ease-in-out 1s infinite;
            }
            .animate-float-delay-3 {
                animation: float 6s ease-in-out 1.5s infinite;
            }
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
                100% { transform: translateY(0px); }
            }
            .card-hover {
                transition: all 0.3s ease;
            }
            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            }
            .fade-in {
                animation: fadeIn 0.6s ease-in;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endpush
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Admin Dashboard
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-6 fade-in">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Products -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg card-hover transform transition-all duration-300 hover:shadow-lg">
                    <div class="p-6 relative">
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-indigo-50 rounded-full opacity-20"></div>
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 shadow-lg animate-float">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="ml-4 z-10">
                                <p class="text-sm font-medium text-gray-500 truncate">Total Products</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $totalProducts }}</p>
                                <p class="text-xs text-indigo-600 mt-1">{{ rand(5, 15) }}% from last month</p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Total Categories -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg card-hover transform transition-all duration-300 hover:shadow-lg">
                    <div class="p-6 relative">
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-green-50 rounded-full opacity-20"></div>
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600 shadow-lg animate-float-delay-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                </svg>
                            </div>
                            <div class="ml-4 z-10">
                                <p class="text-sm font-medium text-gray-500 truncate">Categories</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $totalCategories }}</p>
                                <p class="text-xs text-green-600 mt-1">{{ rand(2, 8) }}% from last month</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg card-hover transform transition-all duration-300 hover:shadow-lg">
                    <div class="p-6 relative">
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-yellow-50 rounded-full opacity-20"></div>
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 shadow-lg animate-float-delay-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-4 z-10">
                                <p class="text-sm font-medium text-gray-500 truncate">Total Users</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
                                <p class="text-xs text-yellow-600 mt-1">{{ rand(10, 25) }}% from last month</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg card-hover transform transition-all duration-300 hover:shadow-lg">
                    <div class="p-6 relative">
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-red-50 rounded-full opacity-20"></div>
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100 text-red-600 shadow-lg animate-float-delay-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="ml-4 z-10">
                                <p class="text-sm font-medium text-gray-500 truncate">Total Orders</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
                                <p class="text-xs text-red-600 mt-1">0% from last month</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 transform transition-all duration-300 hover:shadow-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Quick Actions</h3>
                            <p class="text-sm text-gray-500">Get things done quickly</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('admin.products.create') }}" class="group flex items-center p-4 border-2 border-indigo-50 rounded-xl hover:border-indigo-100 hover:bg-indigo-50 transition-all duration-300 transform hover:-translate-y-1">
                            <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-white group-hover:shadow-md transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <span class="block text-sm font-semibold text-gray-900 group-hover:text-indigo-600">Add New Product</span>
                                <span class="block text-xs text-gray-500">Create a new product listing</span>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.categories.create') }}" class="group flex items-center p-4 border-2 border-green-50 rounded-xl hover:border-green-100 hover:bg-green-50 transition-all duration-300 transform hover:-translate-y-1">
                            <div class="p-3 rounded-xl bg-green-50 text-green-600 group-hover:bg-white group-hover:shadow-md transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <span class="block text-sm font-semibold text-gray-900 group-hover:text-green-600">Add New Category</span>
                                <span class="block text-xs text-gray-500">Create a new category</span>
                            </div>
                        </a>
                        
                        <a href="#" class="group flex items-center p-4 border-2 border-yellow-50 rounded-xl hover:border-yellow-100 hover:bg-yellow-50 transition-all duration-300 transform hover:-translate-y-1">
                            <div class="p-3 rounded-xl bg-yellow-50 text-yellow-600 group-hover:bg-white group-hover:shadow-md transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <span class="block text-sm font-semibold text-gray-900 group-hover:text-yellow-600">View Orders</span>
                                <span class="block text-xs text-gray-500">Check recent orders</span>
                            </div>
                        </a>
                        
                        <a href="#" class="group flex items-center p-4 border-2 border-purple-50 rounded-xl hover:border-purple-100 hover:bg-purple-50 transition-all duration-300 transform hover:-translate-y-1">
                            <div class="p-3 rounded-xl bg-purple-50 text-purple-600 group-hover:bg-white group-hover:shadow-md transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0116 8H4a5 5 0 014.5 7 6.97 6.97 0 00-1.5 4.33c0 .34.024.673.07 1h5.86z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <span class="block text-sm font-semibold text-gray-900 group-hover:text-purple-600">Manage Users</span>
                                <span class="block text-xs text-gray-500">View and manage users</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 transform transition-all duration-300 hover:shadow-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Recent Products</h3>
                            <p class="text-sm text-gray-500">Latest products added to your store</p>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:-translate-y-0.5">
                            View All Products
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 -mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentProducts as $index => $product)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150" style="animation: fadeInUp 0.5s ease-out {{ $index * 0.1 }}s both;">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-lg overflow-hidden bg-gray-100">
                                                    @if($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 object-cover">
                                                    @else
                                                        <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100">
                                                            <svg class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $product->name }}</div>
                                                    <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($product->description, 40) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    {{ $product->category->name ?? 'Uncategorized' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">${{ number_format($product->price, 2) }}</div>
                                            <div class="text-xs text-gray-500">
                                                @if($product->compare_price)
                                                    <span class="line-through text-red-500">${{ number_format($product->compare_price, 2) }}</span>
                                                    <span class="ml-1 text-green-600">
                                                        {{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}% OFF
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->stock > 0)
                                                <span class="px-2.5 py-0.5 inline-flex items-center text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    In Stock ({{ $product->stock }})
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 inline-flex items-center text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Out of Stock
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200 p-1.5 rounded-full hover:bg-indigo-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.products.show', $product->id) }}" class="text-gray-500 hover:text-gray-900 transition-colors duration-200 p-1.5 rounded-full hover:bg-gray-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <button type="button" class="text-red-600 hover:text-red-900 transition-colors duration-200 p-1.5 rounded-full hover:bg-red-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">No products</h3>
                                                <p class="mt-1 text-sm text-gray-500">Get started by creating a new product.</p>
                                                <div class="mt-6">
                                                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        New Product
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
