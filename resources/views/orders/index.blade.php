<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Orders
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded p-4">
                @forelse($orders as $order)
                    <div class="border-b py-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold">Order #{{ $order->id }}</p>
                                <p class="text-sm text-gray-600">Status: {{ ucfirst($order->status) }}</p>
                                <p class="text-sm text-gray-600">Payment: {{ $order->payment_method }}</p>
                                <p class="text-sm text-gray-600">Total: ${{ number_format($order->total_amount, 2) }}</p>
                            </div>

                            <a href="{{ route('orders.show', $order) }}"
                               class="bg-blue-600 text-black px-4 py-2 rounded">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No orders found.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
