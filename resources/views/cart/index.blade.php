<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Cart
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @forelse($groupedItems as $vendorName => $items)
                <div class="bg-white shadow rounded p-4 mb-6">
                    <h3 class="text-lg font-bold mb-4">{{ $vendorName }}</h3>

                    @php $subtotal = 0; @endphp

                    @foreach($items as $item)
                        @php
                            $lineTotal = $item->quantity * $item->product->price;
                            $subtotal += $lineTotal;
                        @endphp

                        <div class="border-b py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h4 class="font-semibold">{{ $item->product->name }}</h4>
                                <p class="text-gray-600">${{ number_format($item->product->price, 2) }}</p>
                            </div>

                            <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" min="1" value="{{ $item->quantity }}" class="border rounded px-3 py-2 w-24">
                                <button type="submit" class="bg-blue-600 text-black px-3 py-2 rounded">
                                    Update
                                </button>
                            </form>

                            <div class="font-semibold">
                                ${{ number_format($lineTotal, 2) }}
                            </div>

                            <form method="POST" action="{{ route('cart.remove', $item) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-black px-3 py-2 rounded">
                                    Remove
                                </button>
                            </form>
                        </div>
                    @endforeach

                    <div class="text-right mt-4 font-bold">
                        Vendor subtotal: ${{ number_format($subtotal, 2) }}
                    </div>
                    <a href="/checkout" class="bg-blue-600 text-black px-4 py-2 rounded">
                        Proceed to Checkout
                    </a>
                </div>

            @empty
                <div class="bg-white shadow rounded p-6 text-center text-gray-500">
                    Your cart is empty.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
