<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EcommerceFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function createBuyerUser(): User
    {
        $user = User::factory()->create([
            'is_buyer' => true,
            'is_vendor' => false,
        ]);

        Cart::create([
            'user_id' => $user->id,
        ]);

        return $user;
    }

    protected function createVendorUser(): User
    {
        $user = User::factory()->create([
            'is_buyer' => false,
            'is_vendor' => true,
        ]);

        Vendor::create([
            'user_id' => $user->id,
            'store_name' => 'Test Vendor Store',
            'description' => 'Test vendor',
        ]);

        return $user;
    }

    protected function createVendorProduct(array $overrides = []): Product
    {
        $vendorUser = User::factory()->create([
            'is_buyer' => false,
            'is_vendor' => true,
        ]);

        $vendor = Vendor::create([
            'user_id' => $vendorUser->id,
            'store_name' => 'Vendor Product Store',
            'description' => 'Vendor description',
        ]);

        return Product::create(array_merge([
            'vendor_id' => $vendor->id,
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 100,
            'stock_quantity' => 5,
            'image_url' => 'https://example.com/product.jpg',
        ], $overrides));
    }

    public function test_buyer_cannot_access_vendor_routes(): void
    {
        $buyer = $this->createBuyerUser();

        $response = $this->actingAs($buyer)->get('/vendor/products');

        $response->assertStatus(403);
    }

    public function test_vendor_cannot_access_buyer_cart_route(): void
    {
        $vendor = $this->createVendorUser();

        $response = $this->actingAs($vendor)->get('/cart');

        $response->assertStatus(403);
    }

    public function test_cart_quantity_cannot_exceed_stock(): void
    {
        $buyer = $this->createBuyerUser();
        $product = $this->createVendorProduct([
            'stock_quantity' => 3,
            'price' => 50,
        ]);

        $cart = $buyer->cart;

        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($buyer)->put("/cart/item/{$cartItem->id}", [
            'quantity' => 10,
        ]);

        $response->assertSessionHas('error', 'Requested quantity exceeds stock.');
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 1,
        ]);
    }

    public function test_checkout_succeeds_and_clears_cart(): void
    {
        $buyer = $this->createBuyerUser();
        $product = $this->createVendorProduct([
            'stock_quantity' => 10,
            'price' => 100,
        ]);

        CartItem::create([
            'cart_id' => $buyer->cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($buyer)->post('/checkout', [
            'payment_method' => 'card',
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'status' => 'paid',
            'payment_method' => 'card',
            'total_amount' => 200.00,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100.00,
            'status' => 'paid',
        ]);

        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $buyer->cart->id,
            'product_id' => $product->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);
    }

    public function test_checkout_fails_when_total_is_above_999_and_cart_stays_intact(): void
    {
        $buyer = $this->createBuyerUser();
        $product = $this->createVendorProduct([
            'stock_quantity' => 10,
            'price' => 600,
        ]);

        CartItem::create([
            'cart_id' => $buyer->cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($buyer)->from('/checkout')->post('/checkout', [
            'payment_method' => 'card',
        ]);

        $response->assertRedirect('/checkout');
        $response->assertSessionHas('error');

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $buyer->cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 10,
        ]);
    }
}
