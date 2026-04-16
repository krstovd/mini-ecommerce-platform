<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Order Details
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-bold mb-4">Order #{{ $order->id }}</h3>

                <p class="mb-2"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                <p class="mb-2"><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
                <p class="mb-4"><strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}</p>

                <h4 class="text-md font-semibold mb-3">Items</h4>

                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="border rounded p-4">
                            <p><strong>Product:</strong> {{ $item->product_name }}</p>
                            <p><strong>Vendor:</strong> {{ $item->vendor->store_name ?? 'N/A' }}</p>
                            <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                            <p><strong>Price:</strong> ${{ number_format($item->price, 2) }}</p>
                            <p><strong>Line Status:</strong> {{ ucfirst($item->status) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
