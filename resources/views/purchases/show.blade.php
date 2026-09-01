<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Purchase Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-3">
                        <p><strong>ID:</strong> {{ $purchase->id }}</p>
                        <p><strong>Name:</strong> {{ $purchase->name }}</p>
                        <p><strong>Email:</strong> {{ $purchase->email ?? '—' }}</p>
                        <p><strong>Description:</strong> {{ $purchase->description ?? '—' }}</p>
                        <p><strong>Item ID:</strong> {{ $purchase->item_id ?? '—' }}</p>
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <a href="{{ route('purchases.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                        <a href="{{ route('purchases.edit', $purchase->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
