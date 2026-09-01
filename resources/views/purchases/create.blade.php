<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Purchase
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('purchases.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Item ID</label>
                            <input type="number" name="item_id" value="{{ old('item_id') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('item_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Save
                            </button>
                            <a href="{{ route('purchases.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
