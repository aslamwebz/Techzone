<div class="relative">
    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-indigo-600 transition-colors flex items-center p-2">
        <i class="fas fa-shopping-cart text-xl"></i>
        @if($count > 0)
            <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                {{ $count }}
            </span>
        @endif
    </a>
</div>
