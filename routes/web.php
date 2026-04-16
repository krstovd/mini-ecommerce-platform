<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;


Route::get('/', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace');

/*Route::get('/', function () {
    return view('welcome');
});*/


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'vendor'])->prefix('vendor')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('vendor.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('vendor.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('vendor.products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('vendor.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('vendor.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('vendor.products.destroy');
});

Route::middleware(['auth', 'buyer'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/item/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/item/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
});

Route::middleware(['auth', 'buyer'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show']);
    Route::post('/checkout', [CheckoutController::class, 'process']);
});

require __DIR__.'/auth.php';
