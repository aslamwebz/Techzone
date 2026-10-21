@extends('admin.layouts.app')

@section('header', __('Dashboard'))

@section('content')
<div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Users Card -->
    <div class="p-6 bg-white rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-semibold text-gray-700">{{ $stats['users']['total'] }}</h3>
                <p class="text-gray-500">Total Users</p>
            </div>
        </div>
        <div class="mt-4 text-sm text-blue-600">
            <span class="font-medium">{{ $stats['users']['new_this_month'] }} new this month</span>
        </div>
    </div>

    <!-- Products Card -->
    <div class="p-6 bg-white rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-semibold text-gray-700">{{ $stats['products']['total'] }}</h3>
                <p class="text-gray-500">Total Products</p>
            </div>
        </div>
        <div class="mt-4 text-sm text-green-600">
            <span class="font-medium">{{ $stats['products']['active'] }} active</span>
        </div>
    </div>

    <!-- Categories Card -->
    <div class="p-6 bg-white rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-semibold text-gray-700">{{ $stats['categories']['total'] }}</h3>
                <p class="text-gray-500">Categories</p>
            </div>
        </div>
        <div class="mt-4 text-sm text-purple-600">
            <a href="{{ route('admin.categories.index') }}" class="font-medium hover:underline">View all</a>
        </div>
    </div>

    <!-- Orders Card -->
    <div class="p-6 bg-white rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-semibold text-gray-700">{{ $stats['orders']['total'] ?? 0 }}</h3>
                <p class="text-gray-500">Total Orders</p>
            </div>
        </div>
        <div class="mt-4 text-sm text-yellow-600">
            <a href="{{ route('admin.orders.index') }}" class="font-medium hover:underline">View all</a>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="mt-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activities</h3>
    
    <div class="bg-white shadow-sm overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($activities as $activity)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-indigo-600 truncate">
                                {{ $activity->description }}
                            </p>
                            <div class="ml-2 shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500">
                                    <svg class="shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $activity->causer->name ?? 'System' }}
                                </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                <svg class="shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                <p>
                                    {{ $activity->created_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-4 text-center text-gray-500">
                    No recent activities found.
                </li>
            @endforelse
        </ul>
    </div>
    
    @if(isset($activities) && $activities->hasPages())
        <div class="mt-4">
            {{ $activities->links() }}
        </div>
    @endif
</div>
@endsection
