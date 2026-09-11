<?php

use App\Models\AgeGroup;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Storefront\Shop;

function getTestShopCategory($slug = 'catalog-category', $name = 'Catalog Category') {
    return Category::firstOrCreate(
        ['slug' => $slug],
        [
            'name' => $name,
            'parent_id' => null,
            'status' => true,
            'sort_order' => 1,
        ]
    );
}

test('guest can access /shop', function () {
    $this->get('/shop')
        ->assertStatus(200);
});

test('authenticated customer can access /shop', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($customer)
        ->get('/shop')
        ->assertStatus(200)
        ->assertSee('Shop Catalog');
});

test('admin can access /shop without breaking storefront', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get('/shop')
        ->assertStatus(200)
        ->assertSee('Admin Panel');
});

test('active products displayed', function () {
    $category = getTestShopCategory();
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Active Catalog Shirt',
        'slug' => 'active-catalog-shirt',
        'sku' => 'ACS-001',
        'status' => true,
        'price' => 1800.00,
    ]);

    $this->get('/shop')
        ->assertSee('Active Catalog Shirt')
        ->assertSee('Rs. 1,800.00');
});

test('inactive products excluded', function () {
    $category = getTestShopCategory();
    $inactive = Product::create([
        'category_id' => $category->id,
        'name' => 'Inactive Catalog Product',
        'slug' => 'inactive-catalog-product',
        'sku' => 'ICP-001',
        'status' => false,
        'price' => 2000.00,
    ]);

    $this->get('/shop')
        ->assertDontSee('Inactive Catalog Product');
});

test('search works', function () {
    $category = getTestShopCategory();
    Product::create([
        'category_id' => $category->id,
        'name' => 'Unicorn Printed Dress',
        'slug' => 'unicorn-printed-dress',
        'sku' => 'UPD-001',
        'status' => true,
        'price' => 2500.00,
    ]);

    Product::create([
        'category_id' => $category->id,
        'name' => 'Dinosaur Hoodie',
        'slug' => 'dinosaur-hoodie',
        'sku' => 'DHO-001',
        'status' => true,
        'price' => 3000.00,
    ]);

    Livewire::test(Shop::class, ['search' => 'Unicorn'])
        ->assertSee('Unicorn Printed Dress')
        ->assertDontSee('Dinosaur Hoodie');
});

test('category filter works', function () {
    $cat1 = getTestShopCategory('girls-cat', 'Girls Collection');
    $cat2 = getTestShopCategory('boys-cat', 'Boys Collection');

    Product::create([
        'category_id' => $cat1->id,
        'name' => 'Girls Party Dress',
        'slug' => 'girls-party-dress',
        'sku' => 'GPD-001',
        'status' => true,
        'price' => 3500.00,
    ]);

    Product::create([
        'category_id' => $cat2->id,
        'name' => 'Boys Graphic Tee',
        'slug' => 'boys-graphic-tee',
        'sku' => 'BGT-001',
        'status' => true,
        'price' => 1200.00,
    ]);

    Livewire::test(Shop::class, ['category' => 'girls-cat'])
        ->assertSee('Girls Party Dress')
        ->assertDontSee('Boys Graphic Tee');
});

test('age group filter works', function () {
    $category = getTestShopCategory();
    $ageGroup = AgeGroup::create([
        'name' => '3-6 Months',
        'slug' => '3-6-months',
        'status' => true,
        'sort_order' => 2,
    ]);

    $p1 = Product::create([
        'category_id' => $category->id,
        'name' => 'Infant Romper Set',
        'slug' => 'infant-romper-set',
        'sku' => 'IRS-001',
        'status' => true,
        'price' => 1500.00,
    ]);
    $p1->ageGroups()->attach($ageGroup->id);

    $p2 = Product::create([
        'category_id' => $category->id,
        'name' => 'Teen Jacket',
        'slug' => 'teen-jacket',
        'sku' => 'TJK-001',
        'status' => true,
        'price' => 4500.00,
    ]);

    Livewire::test(Shop::class, ['age_group' => '3-6-months'])
        ->assertSee('Infant Romper Set')
        ->assertDontSee('Teen Jacket');
});

test('size filter works via product variants', function () {
    $category = getTestShopCategory();
    $sizeSmall = Size::create(['name' => 'S', 'sort_order' => 1, 'status' => true]);
    $sizeLarge = Size::create(['name' => 'L', 'sort_order' => 3, 'status' => true]);

    $p1 = Product::create([
        'category_id' => $category->id,
        'name' => 'Small Sized Pajamas',
        'slug' => 'small-sized-pajamas',
        'sku' => 'SSP-001',
        'status' => true,
        'price' => 1999.00,
    ]);
    ProductVariant::create([
        'product_id' => $p1->id,
        'size_id' => $sizeSmall->id,
        'sku' => 'SSP-001-S',
        'price' => 1999.00,
        'stock_quantity' => 10,
        'status' => true,
    ]);

    $p2 = Product::create([
        'category_id' => $category->id,
        'name' => 'Large Sized Coat',
        'slug' => 'large-sized-coat',
        'sku' => 'LSC-001',
        'status' => true,
        'price' => 4999.00,
    ]);
    ProductVariant::create([
        'product_id' => $p2->id,
        'size_id' => $sizeLarge->id,
        'sku' => 'LSC-001-L',
        'price' => 4999.00,
        'stock_quantity' => 10,
        'status' => true,
    ]);

    Livewire::test(Shop::class, ['size' => 'S'])
        ->assertSee('Small Sized Pajamas')
        ->assertDontSee('Large Sized Coat');
});

test('color filter works via product variants', function () {
    $category = getTestShopCategory();
    $colorRed = Color::create(['name' => 'Red', 'hex_code' => '#FF0000', 'status' => true]);
    $colorBlue = Color::create(['name' => 'Blue', 'hex_code' => '#0000FF', 'status' => true]);

    $p1 = Product::create([
        'category_id' => $category->id,
        'name' => 'Red Frock',
        'slug' => 'red-frock',
        'sku' => 'RFR-001',
        'status' => true,
        'price' => 2200.00,
    ]);
    ProductVariant::create([
        'product_id' => $p1->id,
        'color_id' => $colorRed->id,
        'sku' => 'RFR-001-RED',
        'price' => 2200.00,
        'stock_quantity' => 5,
        'status' => true,
    ]);

    $p2 = Product::create([
        'category_id' => $category->id,
        'name' => 'Blue Shorts',
        'slug' => 'blue-shorts',
        'sku' => 'BSH-001',
        'status' => true,
        'price' => 1400.00,
    ]);
    ProductVariant::create([
        'product_id' => $p2->id,
        'color_id' => $colorBlue->id,
        'sku' => 'BSH-001-BLU',
        'price' => 1400.00,
        'stock_quantity' => 5,
        'status' => true,
    ]);

    Livewire::test(Shop::class, ['color' => 'Red'])
        ->assertSee('Red Frock')
        ->assertDontSee('Blue Shorts');
});

test('sale filter works with valid sale pricing', function () {
    $category = getTestShopCategory();

    Product::create([
        'category_id' => $category->id,
        'name' => 'Discounted Cardigan',
        'slug' => 'discounted-cardigan',
        'sku' => 'DCD-001',
        'status' => true,
        'price' => 2000.00,
        'sale_price' => 1500.00,
        'is_sale' => true,
    ]);

    Product::create([
        'category_id' => $category->id,
        'name' => 'Full Price Sweater',
        'slug' => 'full-price-sweater',
        'sku' => 'FPS-001',
        'status' => true,
        'price' => 2500.00,
        'sale_price' => null,
        'is_sale' => false,
    ]);

    Livewire::test(Shop::class, ['sale' => true])
        ->assertSee('Discounted Cardigan')
        ->assertDontSee('Full Price Sweater');
});

test('new arrival filter works', function () {
    $category = getTestShopCategory();

    Product::create([
        'category_id' => $category->id,
        'name' => 'New Season Beanie',
        'slug' => 'new-season-beanie',
        'sku' => 'NSB-001',
        'status' => true,
        'new_arrival' => true,
        'price' => 800.00,
    ]);

    Product::create([
        'category_id' => $category->id,
        'name' => 'Old Season Socks',
        'slug' => 'old-season-socks',
        'sku' => 'OSS-001',
        'status' => true,
        'new_arrival' => false,
        'price' => 400.00,
    ]);

    Livewire::test(Shop::class, ['new_arrival' => true])
        ->assertSee('New Season Beanie')
        ->assertDontSee('Old Season Socks');
});

test('price filtering uses effective price', function () {
    $category = getTestShopCategory();

    // Regular price 5000, sale 3000 -> effective price 3000
    Product::create([
        'category_id' => $category->id,
        'name' => 'Budget On Sale Blazer',
        'slug' => 'budget-blazer',
        'sku' => 'BBL-001',
        'status' => true,
        'price' => 5000.00,
        'sale_price' => 3000.00,
    ]);

    // Regular price 8000 -> effective price 8000
    Product::create([
        'category_id' => $category->id,
        'name' => 'Luxury Velvet Suit',
        'slug' => 'velvet-suit',
        'sku' => 'LVS-001',
        'status' => true,
        'price' => 8000.00,
    ]);

    Livewire::test(Shop::class, ['min_price' => 2500, 'max_price' => 3500])
        ->assertSee('Budget On Sale Blazer')
        ->assertDontSee('Luxury Velvet Suit');
});

test('sorting options work accurately', function () {
    $category = getTestShopCategory();

    Product::create([
        'category_id' => $category->id,
        'name' => 'Cheapest Item',
        'slug' => 'cheapest-item',
        'sku' => 'CHE-001',
        'status' => true,
        'price' => 500.00,
    ]);

    Product::create([
        'category_id' => $category->id,
        'name' => 'Most Expensive Item',
        'slug' => 'most-expensive-item',
        'sku' => 'EXP-001',
        'status' => true,
        'price' => 9999.00,
    ]);

    Livewire::test(Shop::class, ['sort' => 'price_low'])
        ->assertSeeInOrder(['Cheapest Item', 'Most Expensive Item']);

    Livewire::test(Shop::class, ['sort' => 'price_high'])
        ->assertSeeInOrder(['Most Expensive Item', 'Cheapest Item']);
});

test('pagination splits catalog results', function () {
    $category = getTestShopCategory();

    for ($i = 1; $i <= 15; $i++) {
        Product::create([
            'category_id' => $category->id,
            'name' => "Batch Product Item {$i}",
            'slug' => "batch-product-item-{$i}",
            'sku' => "BPI-{$i}",
            'status' => true,
            'price' => 1000 + $i,
        ]);
    }

    Livewire::test(Shop::class)
        ->assertSee('Showing');
});

test('query-string state reflects in request', function () {
    $this->get('/shop?category=catalog-category&sort=price_low')
        ->assertStatus(200)
        ->assertSee('Sort by:')
        ->assertSee('Price: Low to High');
});

test('empty results handled safely', function () {
    Livewire::test(Shop::class, ['search' => 'NonExistentProductKeywordXYZ'])
        ->assertSee('No Products Found')
        ->assertSee('Clear All Filters');
});

test('missing product images do not crash catalog', function () {
    $category = getTestShopCategory();
    Product::create([
        'category_id' => $category->id,
        'name' => 'No Image Catalog Item',
        'slug' => 'no-image-catalog-item',
        'sku' => 'NICI-001',
        'status' => true,
        'price' => 1000.00,
    ]);

    $this->get('/shop')
        ->assertSee('No Image Catalog Item')
        ->assertSee('Al Hayat Kids Collection');
});

test('existing admin authorization remains intact', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($customer)
        ->get('/admin/products')
        ->assertStatus(403);
});
