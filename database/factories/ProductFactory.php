<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vendor_id' => Vendor::factory(),
            'name' => fake()->randomElement([
                'Wireless Mouse',
                'Mechanical Keyboard',
                'USB-C Hub',
                'Laptop Stand',
                'Monitor Light Bar',
                'Bluetooth Speaker',
                'Phone Holder',
                'Desk Lamp',
                'Gaming Headset',
                'Webcam',
            ]),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2,10,300),
            'stock_quantity' => fake()->numberBetween(1,50),
            'image_url' => 'https://via.placeholder.com/300x200.png?text=Product',

        ];
    }
}
