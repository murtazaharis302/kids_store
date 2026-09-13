<?php

use App\Livewire\Admin\Categories\Create as CategoryCreate;
use App\Livewire\Admin\Categories\Edit as CategoryEdit;
use App\Livewire\Admin\Categories\Index as CategoryIndex;
use App\Livewire\Admin\Coupons\Create as CouponCreate;
use App\Livewire\Admin\Coupons\Edit as CouponEdit;
use App\Livewire\Admin\Coupons\Index as CouponIndex;
use App\Livewire\Admin\Customers\Index as CustomerIndex;
use App\Livewire\Admin\Customers\Show as CustomerShow;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Inventory\Index as InventoryIndex;
use App\Livewire\Admin\Orders\Index as OrderIndex;
use App\Livewire\Admin\Orders\Show as OrderShow;
use App\Livewire\Admin\Products\Create as ProductCreate;
use App\Livewire\Admin\Products\Edit as ProductEdit;
use App\Livewire\Admin\Products\Index as ProductIndex;
use App\Livewire\Admin\Reviews\Index as ReviewIndex;
use App\Livewire\Admin\Reviews\Show as ReviewShow;
use App\Livewire\Storefront\Cart as StorefrontCart;
use App\Livewire\Storefront\Checkout as StorefrontCheckout;
use App\Livewire\Storefront\Home as StorefrontHome;
use App\Livewire\Storefront\OrderShow as StorefrontOrderShow;
use App\Livewire\Storefront\ProductShow as StorefrontProductShow;
use App\Livewire\Storefront\Shop as StorefrontShop;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', StorefrontHome::class)->name('home');
Route::get('/shop', StorefrontShop::class)->name('shop');
Route::get('/products/{product:slug}', StorefrontProductShow::class)->name('products.show');
Route::get('/cart', StorefrontCart::class)->name('cart.index');
Route::get('/checkout', StorefrontCheckout::class)->name('checkout.index');
Route::get('/orders/{order}', StorefrontOrderShow::class)->name('orders.show');

// Storage File Serving Route (Fallback for cPanel servers without symlink access)
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*')->name('storage.file');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Admin Panel Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Products Management Routes
    Route::get('/products', ProductIndex::class)->name('products.index');
    Route::get('/products/create', ProductCreate::class)->name('products.create');
    Route::get('/products/{product}/edit', ProductEdit::class)->name('products.edit');

    // Categories Management Routes
    Route::get('/categories', CategoryIndex::class)->name('categories.index');
    Route::get('/categories/create', CategoryCreate::class)->name('categories.create');
    Route::get('/categories/{category}/edit', CategoryEdit::class)->name('categories.edit');

    // Inventory & Stock Management Routes
    Route::get('/inventory', InventoryIndex::class)->name('inventory.index');

    // Orders Management Routes
    Route::get('/orders', OrderIndex::class)->name('orders.index');
    Route::get('/orders/{order}', OrderShow::class)->name('orders.show');

    // Customers Management Routes
    Route::get('/customers', CustomerIndex::class)->name('customers.index');
    Route::get('/customers/{user}', CustomerShow::class)->name('customers.show');

    // Coupons Management Routes
    Route::get('/coupons', CouponIndex::class)->name('coupons.index');
    Route::get('/coupons/create', CouponCreate::class)->name('coupons.create');
    Route::get('/coupons/{coupon}/edit', CouponEdit::class)->name('coupons.edit');

    // Reviews Management Routes
    Route::get('/reviews', ReviewIndex::class)->name('reviews.index');
    Route::get('/reviews/{review}', ReviewShow::class)->name('reviews.show');
});

require __DIR__.'/auth.php';
