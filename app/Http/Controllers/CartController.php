<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cart()->with('items.product.vendor')->first();

        $groupedItems = $cart
            ? $cart->items->groupBy(fn ($item) => $item->product->vendor->store_name ?? 'Unknown Vendor')
            : collect();

        return view('cart.index', compact('cart', 'groupedItems'));
    }

    public function add(Product $product)
    {
        $cart = auth()->user()->cart;

        if (!$cart) {
            $cart = auth()->user()->cart()->create();
        }

        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + 1;

            if ($newQuantity > $product->stock_quantity) {
                return back()->with('error', 'Not enough stock available.');
            }

            $existingItem->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            if ($product->stock_quantity < 1) {
                return back()->with('error', 'This product is out of stock.');
            }

            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->quantity > $cartItem->product->stock_quantity) {
            return back()->with('error', 'Requested quantity exceeds stock.');
        }

        $cartItem->update([
            'quantity' => $request->quantity,
        ]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
