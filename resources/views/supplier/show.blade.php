<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Supplier Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-3">
                        <p><strong>ID:</strong> {{ $supplier->id }}</p>
                        <p><strong>Name:</strong> {{ $supplier->name }}</p>
                        <p><strong>Email:</strong> {{ $supplier->email ?? '—' }}</p>
                        <p><strong>Description:</strong> {{ $supplier->description ?? '—' }}</p>
                        <p><strong>Item:</strong> {{ $supplier->item?->name ?? '—' }}</p>
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <a href="{{ route('suppliers.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
          
