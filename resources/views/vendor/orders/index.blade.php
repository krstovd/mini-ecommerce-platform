<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vendor Orders
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

            <div class="bg-white shadow rounded p-4">
                @forelse($orderItems as $item)
                    <div class="border-b py-4">
                        <p><strong>Order #{{ $item->order->id }}</strong></p>
                        <p><strong>Buyer:</strong> {{ $item->order->user->name }}</p>
                        <p><strong>Product:</strong> {{ $item->product_name }}</p>
                        <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                        <p><strong>Price:</strong> ${{ number_format($item->price, 2) }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>

                        <form method="POST" action="{{ route('vendor.orders.update-status', $item) }}" class="mt-3 flex items-center gap-2">
                            @csrf
                            @method('PUT')

                            <select name="status" class="border rounded px-3 py-2">
                                <option value="pending" {{ $item->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $item->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="shipped" {{ $item->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $item->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>

                            <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded">
                                Update Status
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500">No vendor orders found.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
