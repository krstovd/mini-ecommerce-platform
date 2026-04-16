<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function checkout($user, $paymentMethod)
    {
        return DB::transaction(function () use ($user, $paymentMethod) {

            $cart = $user->cart()->with('items.product')->first();

            if (!$cart || $cart->items->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            $total = 0;

            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock_quantity) {
                    throw new \Exception('Not enough stock for ' . $item->product->name);
                }

                $total += $item->quantity * $item->product->price;
            }

            // FAIL RULE
            if ($total > 999) {
                throw new \Exception('Payment failed (amount too high)');
            }

            // create order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'status' => 'paid',
                'payment_method' => $paymentMethod,
            ]);

            // create order items + reduce stock
            foreach ($cart->items as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'vendor_id' => $item->product->vendor_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'status' => 'paid',
                ]);

                $item->product->decrement('stock_quantity', $item->quantity);
            }

            // clear cart
            $cart->items()->delete();

            return $order;
        });
    }
}
