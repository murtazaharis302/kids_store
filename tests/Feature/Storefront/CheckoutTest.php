<?php

namespace Tests\Feature\Storefront;

use App\Livewire\Storefront\Checkout;
use App\Models\Address;
use App\Models\Category;
use App\Models\Color;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function createCustomerUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role' => 'customer',
            'status' => true,
        ], $attributes));
    }

    private function createCategory(): Category
    {
        return Category::create([
            'name' => 'Baby Wear',
            'slug' => 'baby-wear',
            'status' => true,
        ]);
    }

    private function createTestProduct(array $attributes = []): Product
    {
        $category = $this->createCategory();

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Soft Cotton Romper',
            'slug' => 'soft-cotton-romper',
            'sku' => 'SCR-001',
            'price' => 2000.00,
            'sale_price' => null,
            'status' => true,
        ], $attributes));
    }

    private function createTestVariant(Product $product, array $attributes = []): ProductVariant
    {
        $size = Size::firstOrCreate(['name' => '0-3M', 'code' => '0-3M'], ['sort_order' => 1]);
        $color = Color::firstOrCreate(['name' => 'Pink', 'code' => '#f472b6']);

        return ProductVariant::create(array_merge([
            'product_id' => $product->id,
            'sku' => $product->sku . '-PNK-03M',
            'size_id' => $size->id,
            'color_id' => $color->id,
            'price' => 2000.00,
            'sale_price' => null,
            'stock_quantity' => 10,
            'status' => true,
        ], $attributes));
    }

    // A. AUTHENTICATION
    public function test_guest_cannot_access_checkout()
    {
        $response = $this->get(route('checkout.index'));
        $response->assertRedirect();
    }

    public function test_authenticated_customer_can_access_checkout_when_cart_has_items()
    {
        $customer = $this->createCustomerUser();
        $product = $this->createTestProduct();
        $variant = $this->createTestVariant($product);

        $this->actingAs($customer);
        CartService::addToCart($product->id, $variant->id, 2);

        $response = $this->get(route('checkout.index'));
        $response->assertStatus(200);
        $response->assertSee('Checkout & Shipping', false);
    }

    public function test_customer_cannot_access_another_customers_order()
    {
        $customer1 = $this->createCustomerUser(['email' => 'customer1@example.com']);
        $customer2 = $this->createCustomerUser(['email' => 'customer2@example.com']);

        $order = Order::create([
            'user_id' => $customer1->id,
            'order_number' => 'AHK-TEST-0001',
            'subtotal' => 2000.00,
            'discount' => 0.00,
            'shipping_cost' => 200.00,
            'total' => 2200.00,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'order_status' => 'pending',
        ]);

        $response = $this->actingAs($customer2)->get(route('orders.show', $order->id));
        $response->assertStatus(403);
    }

    // B. EMPTY CART
    public function test_checkout_redirects_to_cart_if_cart_is_empty()
    {
        $customer = $this->createCustomerUser();

        $response = $this->actingAs($customer)->get(route('checkout.index'));
        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', 'Your shopping cart is empty.');
    }

    // C. ADDRESS VALIDATION & OWNERSHIP
    public function test_customer_can_checkout_using_own_saved_address()
    {
        $customer = $this->createCustomerUser();
        $product = $this->createTestProduct();
        $variant = $this->createTestVariant($product, ['stock_quantity' => 10]);

        $address = Address::create([
            'user_id' => $customer->id,
            'first_name' => 'Tariq',
            'last_name' => 'Customer',
            'phone' => '03001234567',
            'address_line_1' => 'Street 5, Model Town',
            'city' => 'Lahore',
            'country' => 'Pakistan',
            'is_default' => true,
        ]);

        $this->actingAs($customer);
        CartService::addToCart($product->id, $variant->id, 1);

        Livewire::test(Checkout::class)
            ->set('selectedAddressId', $address->id)
            ->set('useNewAddress', false)
            ->call('placeOrder')
            ->assertHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'subtotal' => 2000.00,
        ]);
    }

    public function test_customer_cannot_use_another_users_saved_address()
    {
        $customer1 = $this->createCustomerUser(['email' => 'c1@example.com']);
        $customer2 = $this->createCustomerUser(['email' => 'c2@example.com']);

        $otherAddress = Address::create([
            'user_id' => $customer2->id,
            'first_name' => 'Other',
            'phone' => '03009999999',
            'address_line_1' => 'Far Away Street',
            'city' => 'Karachi',
            'country' => 'Pakistan',
        ]);

        $product = $this->createTestProduct();
        $variant = $this->createTestVariant($product);

        $this->actingAs($customer1);
        CartService::addToCart($product->id, $variant->id, 1);

        Livewire::test(Checkout::class)
            ->set('selectedAddressId', $otherAddress->id)
            ->set('useNewAddress', false)
            ->call('placeOrder')
            ->assertSet('errorMessage', 'Selected shipping address is invalid or does not belong to your account.');

        $this->assertDatabaseCount('orders', 0);
    }

    // D. PRODUCT & VARIANT VALIDATION
    public function test_checkout_rejects_inactive_product()
    {
        $customer = $this->createCustomerUser();
        $product = $this->createTestProduct();
        $variant = $this->createTestVariant($product);

        $this->actingAs($customer);
        CartService::addToCart($product->id, $variant->id, 1);

        // Deactivate product before placeOrder
        $product->update(['status' => false]);

        Livewire::test(Checkout::class)
            ->set('first_name', 'Test')
            ->set('phone', '03001234567')
            ->set('address_line_1', 'Street 1')
            ->set('city', 'Lahore')
            ->set('country', 'Pakistan')
            ->call('placeOrder');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_rejects_inactive_variant()
    {
        $customer = $this->createCustomerUser();
        $product = $this->createTestProduct();
        $variant = $this->createTestVariant($product);

        $this->actingAs($customer);
        CartService::addToCart($product->id, $variant->id, 1);

        // Deactivate variant before placeOrder
        $variant->update(['status' => false]);

        Livewire::test(Checkout::class)
            ->set('first_name', 'Test')
            ->set('phone', '03001234567')
            ->set('address_line_1', 'Street 1')
            ->set('city', 'Lahore')
            ->set('country', 'Pakistan')
            ->call('placeOrder');

        $this->assertDatabaseCount('orders', 0);
    }

    // E. STOCK VALIDATION & DEDUCTION
    public function test_checkout_fails_if_stock_is_insufficient()
    {
        $customer = $this->createCustomerUser();
        $product = $this->createTestProduct();
        $variant = $this->createTestVariant($product, ['stock_quantity' => 2]);

        $this->actingAs($customer);
        CartService::addToCart($product->id, $variant->id, 2);

        // Reduce stock in DB to 1 before placeOrder
        $variant->update(['stock_quantity' => 1]);

        Livewire::test(Checkout::class)
            ->set('first_name', 'Test')
            ->set('phone', '03001234567')
            ->set('address_line_1', 'Street 1')
            ->set('city', 'Lahore')
            ->set('country', 'Pakistan')
            ->call('placeOrder');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_stock_is_deducted_correctly_on_successful_order()
    {
        $customer = $this->createCustomerUser();
        $product = $this->createTestProduct();
        $variant = $this->createTestVariant($product, ['stock_quantity' => 10]);

        $this->actingAs($customer);
        CartService::addToCart($product->id, $variant->id, 3);

        Livewire::test(Checkout::class)
            ->set('first_name', 'Test')
            ->set('phone', '03001234567')
            ->set('address_line_1', 'Street 1')
            ->set('city', 'Lahore')
            ->set('country', 'Pakistan')
            ->call('placeOrder');

        $this->assertEquals(7, $variant->fresh()->stock_quantity);
    }

    // F. PRICING VALIDATION
    public function test_checkout_uses_current_database_prices_and_valid_variant_sale_price()
    {
        $customer = $this->createCustomerUser();
        $product = $this->createTestProduct(['price' => 2500.00]);
        $variant = $this->createTestVariant($product, [
            'price' => 2500.00,
            'sale_price' => 1800.00,
        ]);

        $this->actingAs($customer);
        CartService::addToCart($product->id, $variant->id, 2);

        Livewire::test(Checkout::class)
            ->set('first_name', 'Test')
            ->set('phone', '03001234567')
            ->set('address_line_1', 'Street 1')
            ->set('city', 'Lahore')
            ->set('country', 'Pakistan')
            ->call('placeOrder');

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertEquals(3600.00, (float) $order->subtotal); // 1800 * 2
    }

    // G. COUPON VALIDATION & USAGE
    public function test_valid_coupon_applies_discount_and_creates_coupon_usage()
    {
        $customer = $this->createCustomerUser();
        $product = $this->createTestProduct(['price' => 3000.00]);
        $variant = $this->createTestVariant($product, ['price' => 3000.00]);

        $coupon = Coupon::create([
            'code' => 'SAVE500',
            'type' => 'fixed',
            'value' => 500.00,
            'minimum_order_amount' => 1000.00,
            'status' => true,
        ]);

        $this->actingAs($customer);
        CartService::addToCart($product->id, $variant->id, 1);

        Livewire::test(Checkout::class)
            ->set('coupon_code', 'SAVE500')
            ->call('applyCoupon')
            ->assertSet('couponMessageType', 'success')
            ->set('first_name', 'Test')
            ->set('phone', '03001234567')
            ->set('address_line_1', 'Street 1')
            ->set('city', 'Lahore')
            ->set('country', 'Pakistan')
            ->call('placeOrder');

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertEquals(500.00, (float) $order->discount);
        $this->assertEquals(2850.00, (float) $order->total); // 3000 - 500 + 350 delivery

        $this->assertDatabaseHas('coupon_usages', [
            'coupon_id' => $coupon->id,
            'user_id' => $customer->id,
            'order_id' => $order->id,
            'discount_amount' => 500.00,
        ]);

        $this->assertEquals(1, $coupon->fresh()->used_count);
    }

    public function test_expired_coupon_is_rejected()
    {
        $customer = $this->createCustomerUser();
        $product = $this->createTestProduct();
        $variant = $this->createTestVariant($product);

        Coupon::create([
            'code' => 'EXPIRED10',
            'type' => 'percentage',
            'value' => 10.00,
            'expires_at' => now()->subDay(),
            'status' => true,
        ]);

        $this->actingAs($customer);
        CartService::addToCart($product->id, $variant->id, 1);

        Livewire::test(Checkout::class)
            ->set('coupon_code', 'EXPIRED10')
            ->call('applyCoupon')
            ->assertSet('couponMessageType', 'error')
            ->assertSet('couponMessage', 'This coupon has expired.');
    }

    public function test_coupon_usage_limit_is_respected()
    {
        $customer = $this->createCustomerUser();
        $product = $this->createTestProduct();
        $variant = $this->createTestVariant($product);

        $coupon = Coupon::create([
            'code' => 'LIMITED1',
            'type' => 'fixed',
            'value' => 200.00,
            'usage_limit' => 1,
            'status' => true,
        ]);

        // Pre-create an Order & CouponUsage record simulating 1 prior usage
        $priorOrder = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'AHK-TEST-PRIOR',
            'subtotal' => 1000.00,
            'discount' => 200.00,
            'shipping_cost' => 200.00,
            'total' => 1000.00,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'order_status' => 'pending',
        ]);

        CouponUsage::create([
            'coupon_id' => $coupon->id,
            'user_id' => $customer->id,
            'order_id' => $priorOrder->id,
            'discount_amount' => 200.00,
        ]);

        $this->actingAs($customer);
        CartService::addToCart($product->id, $variant->id, 1);

        Livewire::test(Checkout::class)
            ->set('coupon_code', 'LIMITED1')
            ->call('applyCoupon')
            ->assertSet('couponMessageType', 'error')
            ->assertSet('couponMessage', 'This coupon has reached its maximum usage limit.');
    }

    // H. ORDER & ITEM SNAPSHOT CREATION
    public function test_order_and_items_are_created_with_historical_snapshot_fields()
    {
        $customer = $this->createCustomerUser();
        $product = $this->createTestProduct([
            'name' => 'Original Romper Name',
            'sku' => 'ROM-001',
        ]);
        $variant = $this->createTestVariant($product, [
            'sku' => 'ROM-001-PNK-03M',
            'price' => 1500.00,
        ]);

        $this->actingAs($customer);
        CartService::addToCart($product->id, $variant->id, 2);

        Livewire::test(Checkout::class)
            ->set('first_name', 'Tariq')
            ->set('last_name', 'Customer')
            ->set('phone', '03001234567')
            ->set('address_line_1', 'Main Boulevard')
            ->set('city', 'Islamabad')
            ->set('country', 'Pakistan')
            ->call('placeOrder');

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertStringStartsWith('AHK-', $order->order_number);
        $this->assertEquals('pending', $order->order_status);
        $this->assertEquals('pending_verification', $order->payment_status);

        $orderItem = OrderItem::where('order_id', $order->id)->first();
        $this->assertNotNull($orderItem);
        $this->assertEquals('Original Romper Name', $orderItem->product_name);
        $this->assertEquals('ROM-001-PNK-03M', $orderItem->sku);
        $this->assertEquals(2, $orderItem->quantity);
        $this->assertEquals(1500.00, (float) $orderItem->unit_price);
        $this->assertEquals(3000.00, (float) $orderItem->total);
    }

    // I. CART CLEARING
    public function test_cart_is_cleared_after_successful_order()
    {
        $customer = $this->createCustomerUser();
        $product = $this->createTestProduct();
        $variant = $this->createTestVariant($product);

        $this->actingAs($customer);
        CartService::addToCart($product->id, $variant->id, 1);

        $this->assertEquals(1, CartService::getCartCount());

        Livewire::test(Checkout::class)
            ->set('first_name', 'Test')
            ->set('phone', '03001234567')
            ->set('address_line_1', 'Street 1')
            ->set('city', 'Lahore')
            ->set('country', 'Pakistan')
            ->call('placeOrder');

        $this->assertEquals(0, CartService::getCartCount());
    }
}
