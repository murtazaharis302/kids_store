<?php

use App\Models\AgeGroup;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\User;

function getTestCategory() {
    return Category::firstOrCreate(
        ['slug' => 'test-category'],
        [
            'name' => 'Test Category',
            'parent_id' => null,
            'status' => true,
            'sort_order' => 1,
        ]
    );
}

test('guest can access homepage', function () {
    $this->get('/')
        ->assertStatus(200);
});

test('homepage renders ah kids branding and title tag', function () {
    $this->get('/')
        ->assertSee('Al Hayat Kids')
        ->assertSee('Al Hayat Kids Official Storefront');
});

test('hero section renders with ctas', function () {
    $this->get('/')
        ->assertSee('Shop New Arrivals')
        ->assertSee('Explore Collections');
});

test('active categories are displayed dynamically', function () {
    $category = Category::create([
        'name' => 'Test Dynamic Category',
        'slug' => 'test-dynamic-category',
        'parent_id' => null,
        'status' => true,
        'sort_order' => 1,
    ]);

    $this->get('/')
        ->assertSee('Test Dynamic Category');
});

test('new arrival products are displayed dynamically', function () {
    $category = getTestCategory();

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Dynamic New Arrival Shirt',
        'slug' => 'dynamic-new-arrival-shirt',
        'sku' => 'DNA-001',
        'status' => true,
        'new_arrival' => true,
        'price' => 1500.00,
    ]);

    $this->get('/')
        ->assertSee('Dynamic New Arrival Shirt')
        ->assertSee('Rs. 1,500.00');
});

test('inactive products are not displayed', function () {
    $category = getTestCategory();

    $inactiveProduct = Product::create([
        'category_id' => $category->id,
        'name' => 'Hidden Inactive Product',
        'slug' => 'hidden-inactive-product',
        'sku' => 'HIP-001',
        'status' => false,
        'new_arrival' => true,
        'price' => 1000.00,
    ]);

    $this->get('/')
        ->assertDontSee('Hidden Inactive Product');
});

test('featured collection renders when available', function () {
    $category = getTestCategory();

    $collection = Collection::where('slug', 'featured')->first();
    if (!$collection) {
        $collection = Collection::create([
            'name' => 'Featured Favorites',
            'slug' => 'featured',
            'status' => true,
        ]);
    }

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Featured Collection Item',
        'slug' => 'featured-collection-item',
        'sku' => 'FCI-001',
        'status' => true,
        'price' => 2000.00,
    ]);

    $collection->products()->attach($product->id);

    $this->get('/')
        ->assertSee('Featured Collection Item');
});

test('age groups are loaded dynamically', function () {
    $ageGroup = AgeGroup::firstOrCreate([
        'slug' => '9-10-years',
    ], [
        'name' => '9 to 10 Years',
        'status' => true,
        'sort_order' => 10,
    ]);

    $this->get('/')
        ->assertSee('9 to 10 Years');
});

test('sale products render when available with correct sale pricing', function () {
    $category = getTestCategory();

    $saleProduct = Product::create([
        'category_id' => $category->id,
        'name' => 'On Sale Jacket',
        'slug' => 'on-sale-jacket',
        'sku' => 'OSJ-001',
        'status' => true,
        'price' => 3000.00,
        'sale_price' => 2400.00,
        'is_sale' => true,
    ]);

    $this->get('/')
        ->assertSee('On Sale Jacket')
        ->assertSee('Rs. 2,400.00')
        ->assertSee('Rs. 3,000.00');
});

test('empty sections do not crash homepage', function () {
    // Hide all products safely for this test query
    Product::query()->update(['status' => false]);

    $this->get('/')
        ->assertStatus(200);

    // Restore products status
    Product::query()->update(['status' => true]);
});

test('product pricing renders correctly without float corruption', function () {
    $category = getTestCategory();

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Exact Price Denim',
        'slug' => 'exact-price-denim',
        'sku' => 'EPD-001',
        'status' => true,
        'new_arrival' => true,
        'price' => 2499.00,
        'sale_price' => null,
    ]);

    $this->get('/')
        ->assertSee('Exact Price Denim')
        ->assertSee('Rs. 2,499.00');
});

test('missing product images do not crash', function () {
    $category = getTestCategory();

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'No Image Product',
        'slug' => 'no-image-product',
        'sku' => 'NIP-001',
        'status' => true,
        'new_arrival' => true,
        'price' => 1200.00,
    ]);

    $this->get('/')
        ->assertSee('No Image Product')
        ->assertSee('Al Hayat Kids');
});

test('authenticated customer can access homepage', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($customer)
        ->get('/')
        ->assertStatus(200)
        ->assertSee('My Account')
        ->assertDontSee('Admin Panel');
});

test('admin can access homepage without breaking storefront', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get('/')
        ->assertStatus(200)
        ->assertSee('Admin Panel');
});

test('homepage does not expose admin-only data or management controls', function () {
    $this->get('/')
        ->assertDontSee('Revenue')
        ->assertDontSee('Create Product')
        ->assertDontSee('Coupons Management');
});
