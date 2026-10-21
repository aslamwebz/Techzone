@extends('admin.layout')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="bg-white shadow-sm rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Edit User: {{ $user->name }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Update user details and permissions
        </p>
    </div>
    
    <div class="px-4 py-5 sm:p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-xs py-2 px-3 focus:outline-hidden focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-xs py-2 px-3 focus:outline-hidden focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_admin" name="is_admin" type="checkbox" 
                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded-sm"
                               {{ $user->is_admin ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_admin" class="font-medium text-gray-700">Administrator</label>
                        <p class="text-gray-500">Grant this user administrator privileges</p>
                    </div>
                </div>
                
                <div class="pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('admin.users.index') }}" 
                           class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-xs text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-xs text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="mt-6 bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Danger Zone
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Perform dangerous actions here
        </p>
    </div>
    
    <div class="px-4 py-5 sm:p-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h4 class="text-lg font-medium text-gray-900">Delete User</h4>
                <p class="text-sm text-gray-500">
                    Once you delete a user, there is no going back. Please be certain.
                </p>
            </div>
            @if($user->id !== auth()->id())
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="mt-4 sm:mt-0">
                @csrf
                @method('DELETE')
                <button type="button" 
                        onclick="if(confirm('Are you sure you want to delete this user? This action cannot be undone.')) { this.form.submit(); }"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                    Delete User
                </button>
            </form>
            @else
            <button type="button" disabled
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed sm:text-sm">
                Cannot delete your own account
            </button>
            @endif
        </div>
    </div>
</div>
@endsection
