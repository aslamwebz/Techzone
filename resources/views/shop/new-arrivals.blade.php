@extends('shop.layout')

@section('shop-content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">New Arrivals</h1>
        <p class="text-gray-500">{{ $products->total() }} products found</p>
    </div>
    
    @if($products->count() > 0)
        <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($products as $product)
                @include('shop.partials.product-card', ['product' => $product])
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500">No new products found. Check back soon!</p>
        </div>
    @endif
</div>
@endsection
