<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        <a href="{{ route('categories.index') }}" class="block rounded-lg border border-gray-200 p-4 hover:bg-gray-50">
                            <div class="text-sm text-gray-500">Categories</div>
                            <div class="mt-2 text-xl font-semibold">Categories</div>
                        </a>

                        <a href="{{ route('items.index') }}" class="block rounded-lg border border-gray-200 p-4 hover:bg-gray-50">
                            <div class="text-sm text-gray-500">Items</div>
                            <div class="mt-2 text-xl font-semibold">Items</div>
                        </a>

                        <a href="{{ route('suppliers.index') }}" class="block rounded-lg border border-gray-200 p-4 hover:bg-gray-50">
                            <div class="text-sm text-gray-500">Suppliers</div>
                            <div class="mt-2 text-xl font-semibold">Suppliers</div>
                        </a>

                        <a href="{{ route('purchases.index') }}" class="block rounded-lg border border-gray-200 p-4 hover:bg-gray-50">
                            <div class="text-sm text-gray-500">Purchases</div>
                            <div class="mt-2 text-xl font-semibold">Purchases</div>
                        </a>

                        <a href="{{ route('branches.index') }}" class="block rounded-lg border border-gray-200 p-4 hover:bg-gray-50">
                            <div class="text-sm text-gray-500">Branches</div>
                            <div class="mt-2 text-xl font-semibold">Branches</div>
                        </a>

                        <a href="{{ route('stock.index') }}" class="block rounded-lg border border-gray-200 p-4 hover:bg-gray-50">
                            <div class="text-sm text-gray-500">Branch stock</div>
                            <div class="mt-2 text-xl font-semibold">Branch Stock</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
