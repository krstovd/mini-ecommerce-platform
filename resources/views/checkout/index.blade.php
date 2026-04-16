<x-app-layout>
    <div class="max-w-2xl mx-auto p-6">

        <h2 class="text-xl font-bold mb-4">Checkout</h2>

        @if(session('error'))
            <div class="bg-red-200 p-3 mb-4">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="bg-green-200 p-3 mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="/checkout">
            @csrf

            <label class="block mb-2">Payment Method</label>

            <select name="payment_method" class="w-full border p-2 mb-4">
                <option value="card">Card</option>
                <option value="cash">Cash</option>
            </select>

            <button class="bg-green-600 text-black px-4 py-2 rounded">
                Confirm Order
            </button>
        </form>
    </div>
</x-app-layout>
