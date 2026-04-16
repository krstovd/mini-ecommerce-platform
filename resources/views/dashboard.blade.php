<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                You're logged in!
            </div>

            @auth
                @if(auth()->user()->is_buyer)
                    <a href="{{ route('orders.index') }}" class="inline-block bg-green-600 text-black px-4 py-2 rounded">
                        My Orders
                    </a>
                @endif

                @if(auth()->user()->is_vendor)
                    <a href="{{ route('vendor.products.index') }}" class="inline-block bg-blue-600 text-black px-4 py-2 rounded">
                        My Products
                    </a>

                    <a href="{{ route('vendor.orders.index') }}" class="inline-block bg-purple-600 text-black px-4 py-2 rounded">
                        Vendor Orders
                    </a>
                @endif
            @endauth
        </div>
    </div>
</x-app-layout>
