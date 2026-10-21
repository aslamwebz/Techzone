@extends('admin.layout')

@section('title', 'Create New Product')

@section('content')
<div class="px-4 py-5 sm:px-6 border-b border-gray-200">
    <div class="flex justify-between items-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Create New Product
        </h3>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-xs text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Back to Products
        </a>
    </div>
    <p class="mt-1 max-w-2xl text-sm text-gray-500">
        Add a new product to your store.
    </p>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @include('admin.products.form', [
        'product' => new \App\Models\Product(),
        'categories' => $categories,
        'method' => 'POST',
        'action' => route('admin.products.store')
    ])
</form>

@push('scripts')
<script>
    // Image preview functionality
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
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
