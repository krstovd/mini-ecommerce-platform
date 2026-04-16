<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Marketplace
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="GET" action="{{ route('marketplace') }}" class="bg-white p-4 rounded shadow mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search products..."
                    class="border rounded px-3 py-2"
                >

                <select name="vendor" class="border rounded px-3 py-2">
                    <option value="">All vendors</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ request('vendor') == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->store_name }}
                        </option>
                    @endforeach
                </select>

                <input
                    type="number"
                    step="0.01"
                    name="min_price"
                    value="{{ request('min_price') }}"
                    placeholder="Min price"
                    class="border rounded px-3 py-2"
                >

                <input
                    type="number"
                    step="0.01"
                    name="max_price"
                    value="{{ request('max_price') }}"
                    placeholder="Max price"
                    class="border rounded px-3 py-2"
                >

                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Filter
                    </button>

                    <a href="{{ route('marketplace') }}" class="bg-gray-300 text-black px-4 py-2 rounded">
                        Reset
                    </a>
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded shadow p-4">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded mb-4">

                        <h3 class="text-lg font-bold">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $product->vendor->store_name }}</p>
                        <p class="text-gray-700 mb-2">{{ $product->description }}</p>
                        <p class="font-semibold mb-2">${{ number_format($product->price, 2) }}</p>
                        <p class="text-sm text-gray-500 mb-4">Stock: {{ $product->stock_quantity }}</p>

                        @auth
                            @if(auth()->user()->is_buyer)
                                <form method="POST" action="{{ route('cart.add', $product) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded">
                                        Add to cart
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500">
                        No products found.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
