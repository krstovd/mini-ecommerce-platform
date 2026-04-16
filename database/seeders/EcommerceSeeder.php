<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EcommerceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendorUsers = User::factory()->count(3)->create([
            'is_buyer' => false,
            'is_vendor' => true,
            'password' => Hash::make('password'),
        ]);

        foreach ($vendorUsers as $user) {
            $vendor = Vendor::create([
                'user_id' => $user->id,
                'store_name' => fake()->company(),
                'description' => fake()->sentence(),
            ]);

            Product::factory()->count(10)->create([
                'vendor_id' => $vendor->id,
            ]);
        }

        $buyerUsers = User::factory()->count(2)->create([
            'is_buyer' => true,
            'is_vendor' => false,
            'password' => Hash::make('password'),
        ]);

        foreach ($buyerUsers as $user) {
            Cart::create([
                'user_id' => $user->id,
            ]);
        }

        $bothUser = User::factory()->create([
            'name' => 'Demo Vendor Buyer',
            'email' => 'demo@example.com',
            'is_buyer' => true,
            'is_vendor' => true,
            'password' => Hash::make('password'),
        ]);

        $vendor = Vendor::create([
            'user_id' => $bothUser->id,
            'store_name' => 'Demo Store',
            'description' => 'Demo vendor account',
        ]);

        Product::factory()->count(5)->create([
            'vendor_id' => $vendor->id,
        ]);

        Cart::create([
            'user_id' => $bothUser->id,
        ]);
    }
}
