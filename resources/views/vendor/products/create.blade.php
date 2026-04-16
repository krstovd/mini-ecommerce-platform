<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Product
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('vendor.products.store') }}" class="bg-white shadow rounded p-6 space-y-4">
                @csrf

                <div>
                    <label class="block mb-1">Name</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name') }}">
                </div>

                <div>
                    <label class="block mb-1">Description</label>
                    <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block mb-1">Price</label>
                    <input type="number" step="0.01" name="price" class="w-full border rounded px-3 py-2" value="{{ old('price') }}">
                </div>

                <div>
                    <label class="block mb-1">Stock Quantity</label>
                    <input type="number" name="stock_quantity" class="w-full border rounded px-3 py-2" value="{{ old('stock_quantity') }}">
                </div>

                <div>
                    <label class="block mb-1">Image URL</label>
                    <input type="url" name="image_url" class="w-full border rounded px-3 py-2" value="{{ old('image_url') }}">
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Save Product
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
