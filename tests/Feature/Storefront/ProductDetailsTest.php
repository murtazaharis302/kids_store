<?php

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Storefront\ProductShow;

function getTestDetailsCategory() {
    return Category::firstOrCreate(
        ['slug' => 'details-category'],
        [
            'name' => 'Details Category',
            'parent_id' => null,
            'status' => true,
            'sort_order' => 1,
        ]
    );
}

test('active product page returns 200', function () {
    $category = getTestDetailsCategory();
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Active Sweater Test',
        'slug' => 'active-sweater-test',
        'sku' => 'AST-001',
        'status' => true,
        'price' => 2500.00,
    ]);

    $this->get('/products/active-sweater-test')
        ->assertStatus(200);
});

test('inactive product returns 404', function () {
    $category = getTestDetailsCategory();
    $inactive = Product::create([
        'category_id' => $category->id,
        'name' => 'Hidden Inactive Product',
        'slug' => 'hidden-inactive-product',
        'sku' => 'HIP-002',
        'status' => false,
        'price' => 1200.00,
    ]);

    $this->get('/products/hidden-inactive-product')
        ->assertStatus(404);
});

test('nonexistent slug returns 404', function () {
    $this->get('/products/non-existent-slug-xyz')
        ->assertStatus(404);
});

test('product name renders', function () {
    $category = getTestDetailsCategory();
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Charming Floral Dress',
        'slug' => 'charming-floral-dress',
        'sku' => 'CFD-001',
        'status' => true,
        'price' => 3200.00,
    ]);

    $this->get('/products/charming-floral-dress')
        ->assertSee('Charming Floral Dress')
        ->assertSee('CFD-001');
});

test('pricing renders correctly', function () {
    $category = getTestDetailsCategory();
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Regular Price Jeans',
        'slug' => 'regular-price-jeans',
        'sku' => 'RPJ-001',
        'status' => true,
        'price' => 2400.00,
        'sale_price' => null,
    ]);

    $this->get('/products/regular-price-jeans')
        ->assertSee('Rs. 2,400.00');
});

test('invalid sale price is not displayed', function () {
    $category = getTestDetailsCategory();
    // Sale price higher than regular price should be ignored
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Invalid Discount Shirt',
        'slug' => 'invalid-discount-shirt',
        'sku' => 'IDS-001',
        'status' => true,
        'price' => 2000.00,
        'sale_price' => 2500.00,
    ]);

    $this->get('/products/invalid-discount-shirt')
        ->assertSee('Rs. 2,000.00')
        ->assertDontSee('Rs. 2,500.00');
});

test('product images render or fallback safely', function () {
    $category = getTestDetailsCategory();
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'No Image Product Test',
        'slug' => 'no-image-product-test',
        'sku' => 'NIPT-001',
        'status' => true,
        'price' => 1500.00,
    ]);

    $this->get('/products/no-image-product-test')
        ->assertSee('Al Hayat Kids Collection');
});

test('variants render and color size selection works', function () {
    $category = getTestDetailsCategory();
    $colorRed = Color::create(['name' => 'Red', 'hex_code' => '#FF0000', 'status' => true]);
    $colorBlue = Color::create(['name' => 'Blue', 'hex_code' => '#0000FF', 'status' => true]);
    $sizeSmall = Size::create(['name' => 'S', 'sort_order' => 1, 'status' => true]);
    $sizeMedium = Size::create(['name' => 'M', 'sort_order' => 2, 'status' => true]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Multi Variant Hoodie',
        'slug' => 'multi-variant-hoodie',
        'sku' => 'MVH-001',
        'status' => true,
        'price' => 3000.00,
    ]);

    $v1 = ProductVariant::create([
        'product_id' => $product->id,
        'color_id' => $colorRed->id,
        'size_id' => $sizeSmall->id,
        'sku' => 'MVH-RED-S',
        'price' => 3000.00,
        'stock_quantity' => 20,
        'status' => true,
    ]);

    $v2 = ProductVariant::create([
        'product_id' => $product->id,
        'color_id' => $colorBlue->id,
        'size_id' => $sizeMedium->id,
        'sku' => 'MVH-BLU-M',
        'price' => 3000.00,
        'stock_quantity' => 5,
        'status' => true,
    ]);

    Livewire::test(ProductShow::class, ['product' => $product])
        ->assertSee('Red')
        ->assertSee('Blue')
        ->assertSee('S')
        ->assertSee('M')
        ->call('selectColor', $colorBlue->id)
        ->assertSet('selectedColorId', $colorBlue->id)
        ->assertSet('selectedVariantId', $v2->id)
        ->assertSee('Low Stock');
});

test('stock status works', function () {
    $category = getTestDetailsCategory();
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Low Stock Jacket',
        'slug' => 'low-stock-jacket',
        'sku' => 'LSJ-001',
        'status' => true,
        'price' => 4000.00,
    ]);

    ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'LSJ-001-VAR',
        'price' => 4000.00,
        'stock_quantity' => 3,
        'status' => true,
    ]);

    Livewire::test(ProductShow::class, ['product' => $product])
        ->assertSee('Low Stock (Only 3 left)');
});

test('quantity cannot exceed stock', function () {
    $category = getTestDetailsCategory();
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Limited Stock Hat',
        'slug' => 'limited-stock-hat',
        'sku' => 'LSH-001',
        'status' => true,
        'price' => 900.00,
    ]);

    ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'LSH-001-VAR',
        'price' => 900.00,
        'stock_quantity' => 2,
        'status' => true,
    ]);

    Livewire::test(ProductShow::class, ['product' => $product])
        ->assertSet('quantity', 1)
        ->call('incrementQuantity')
        ->assertSet('quantity', 2)
        ->call('incrementQuantity')
        ->assertSet('quantity', 2); // Cannot exceed 2
});

test('related products exclude current product and are active only', function () {
    $category = getTestDetailsCategory();

    $mainProduct = Product::create([
        'category_id' => $category->id,
        'name' => 'Main Product Item',
        'slug' => 'main-product-item',
        'sku' => 'MPI-001',
        'status' => true,
        'price' => 2000.00,
    ]);

    $related1 = Product::create([
        'category_id' => $category->id,
        'name' => 'Related Active Romper',
        'slug' => 'related-active-romper',
        'sku' => 'RAR-001',
        'status' => true,
        'price' => 1800.00,
    ]);

    $relatedInactive = Product::create([
        'category_id' => $category->id,
        'name' => 'Related Inactive Item',
        'slug' => 'related-inactive-item',
        'sku' => 'RII-001',
        'status' => false,
        'price' => 1800.00,
    ]);

    Livewire::test(ProductShow::class, ['product' => $mainProduct])
        ->assertSee('Related Products')
        ->assertSee('Related Active Romper')
        ->assertDontSee('Related Inactive Item');
});

test('guest can access product details', function () {
    $category = getTestDetailsCategory();
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Guest Accessible Shirt',
        'slug' => 'guest-accessible-shirt',
        'sku' => 'GAS-001',
        'status' => true,
        'price' => 1000.00,
    ]);

    $this->get('/products/guest-accessible-shirt')
        ->assertStatus(200);
});

test('authenticated customer can access product details', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $category = getTestDetailsCategory();
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Customer Product Access',
        'slug' => 'customer-product-access',
        'sku' => 'CPA-001',
        'status' => true,
        'price' => 1500.00,
    ]);

    $this->actingAs($customer)
        ->get('/products/customer-product-access')
        ->assertStatus(200)
        ->assertSee('Customer Product Access');
});

test('admin can access product details', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = getTestDetailsCategory();
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Admin Product Access',
        'slug' => 'admin-product-access',
        'sku' => 'APA-001',
        'status' => true,
        'price' => 1500.00,
    ]);

    $this->actingAs($admin)
        ->get('/products/admin-product-access')
        ->assertStatus(200)
        ->assertSee('Admin Panel');
});

test('existing admin authorization remains intact', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($customer)
        ->get('/admin/products')
        ->assertStatus(403);
});
