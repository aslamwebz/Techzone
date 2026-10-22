@extends('layouts.admin')

@section('title', 'Products')

@push('styles')
<style>
    .product-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .product-image {
        height: 160px;
        object-fit: contain;
        background: #f9fafb;
        padding: 1rem;
    }
    
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
    }
    
    .status-active {
        background-color: #dcfce7;
        color: #166534;
    }
    
    .status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .action-btn {
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="bg-white shadow-sm rounded-lg">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Products</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your products and inventory</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <input 
                    type="text" 
                    placeholder="Search products..." 
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64"
                    id="searchInput"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <a 
                href="{{ route('products.create') }}" 
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
            >
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Product
            </a>
        </div>
    </div>
    
    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mx-6 mt-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Products Grid -->
    <div class="p-6">
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="product-card bg-white rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="relative">
                            <!-- Product Image -->
                            <div class="w-full bg-gray-50 flex items-center justify-center">
                                @if($product->image)
                                    <img 
                                        src="{{ asset('storage/' . $product->image) }}" 
                                        alt="{{ $product->name }}" 
                                        class="product-image w-full object-contain"
                                    >
                                @else
                                    <div class="product-image w-full flex items-center justify-center text-gray-400">
                                        <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Status Badge -->
                            <div class="absolute top-2 right-2">
                                <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            
                            <!-- Stock Badge -->
                            @if($product->stock <= 0)
                                <div class="absolute top-2 left-2">
                                    <span class="status-badge bg-red-100 text-red-800">
                                        Out of Stock
                                    </span>
                                </div>
                            @elseif($product->stock < 10)
                                <div class="absolute top-2 left-2">
                                    <span class="status-badge bg-amber-100 text-amber-800">
                                        Low Stock ({{ $product->stock }} left)
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Info -->
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mb-2">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="text-lg font-bold text-gray-900">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                    @if($product->compare_price > $product->price)
                                        <div class="text-xs text-gray-500 line-through">
                                            ${{ number_format($product->compare_price, 2) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <div class="text-sm">
                                    <span class="font-medium text-gray-700">SKU:</span>
                                    <span class="text-gray-500 ml-1">{{ $product->sku ?? 'N/A' }}</span>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <!-- View Button -->
                                    <a 
                                        href="{{ route('products.show', $product->id) }}" 
                                        class="text-gray-400 hover:text-indigo-600 action-btn"
                                        title="View"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a 
                                        href="{{ route('products.edit', $product->id) }}" 
                                        class="text-gray-400 hover:text-indigo-600 action-btn"
                                        title="Edit"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <form 
                                        action="{{ route('products.destroy', $product->id) }}" 
                                        method="POST" 
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this product?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="text-gray-400 hover:text-red-600 action-btn"
                                            title="Delete"
                                        >
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No products</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new product.</p>
                <div class="mt-6">
                    <a 
                        href="{{ route('products.create') }}" 
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        New Product
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    const query = this.value.trim();
                    if (query) {
                        window.location.href = '{{ route("products.index") }}?search=' + encodeURIComponent(query);
                    } else {
                        window.location.href = '{{ route("products.index") }}';
                    }
                }
            });
        }
        
        // Highlight active filter
        const urlParams = new URLSearchParams(window.location.search);
        const statusFilter = urlParams.get('status');
        
        if (statusFilter) {
            const activeFilter = document.querySelector(`[data-filter-status="${statusFilter}"]`);
            if (activeFilter) {
                activeFilter.classList.add('bg-indigo-100', 'text-indigo-700');
            }
        }
    });
</script>
@endpush
