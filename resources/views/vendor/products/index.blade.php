<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Products
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('vendor.products.create') }}" class="bg-blue-600 text-black px-4 py-2 rounded mb-4 inline-block">
                Add Product
            </a>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded p-4">
                <table class="w-full border-collapse">
                    <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">Name</th>
                        <th class="text-left py-2">Price</th>
                        <th class="text-left py-2">Stock</th>
                        <th class="text-left py-2">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($products as $product)
                        <tr class="border-b">
                            <td class="py-2">{{ $product->name }}</td>
                            <td class="py-2">${{ number_format($product->price, 2) }}</td>
                            <td class="py-2">{{ $product->stock_quantity }}</td>
                            <td class="py-2 flex gap-2">
                                <a href="{{ route('vendor.products.edit', $product) }}" class="bg-yellow-500 text-black px-3 py-1 rounded">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('vendor.products.destroy', $product) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">No products yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
