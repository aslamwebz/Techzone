@extends('admin.layout')

@section('title', 'Edit Product: ' . $product->name)

@section('content')
<div class="px-4 py-5 sm:px-6 border-b border-gray-200">
    <div class="flex justify-between items-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Edit Product: {{ $product->name }}
        </h3>
        <div class="flex space-x-3">
            <a href="{{ route('admin.products.show', $product) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                View
            </a>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to Products
            </a>
        </div>
    </div>
    <p class="mt-1 max-w-2xl text-sm text-gray-500">
        Last updated {{ $product->updated_at->diffForHumans() }}
    </p>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    @include('admin.products.form', [
        'product' => $product,
        'categories' => $categories,
        'method' => 'PUT',
        'action' => route('admin.products.update', $product)
    ])
</form>

<!-- Delete Product -->
<div class="mt-8 bg-white shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Delete Product
                </h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>
                        Once you delete a product, there is no going back. Please be certain.
                    </p>
                </div>
            </div>
            <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center">
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" 
                            onclick="if(confirm('Are you sure you want to delete this product? This action cannot be undone.')) { this.form.submit(); }"
                            class="inline-flex items-center px-4 py-2 border border-transparent font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                        Delete Product
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview functionality
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Initialize file input preview
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('image');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                previewImage(this);
            });
        }
    });
</script>
@endpush
@endsection
