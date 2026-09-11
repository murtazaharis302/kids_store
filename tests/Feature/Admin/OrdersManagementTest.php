<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Orders\Index;
use App\Livewire\Admin\Orders\Show;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrdersManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser()
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function createStaffUser()
    {
        return User::factory()->create(['role' => 'staff']);
    }

    private function createCustomerUser()
    {
        return User::factory()->create(['role' => 'customer']);
    }

    private function createSampleOrder($overrides = [])
    {
        $customer = User::factory()->create([
            'role' => 'customer', 
            'name' => 'Tariq Mahmood', 
            'email' => 'tariq_' . Str::random(5) . '@example.com'
        ]);
        $category = \App\Models\Category::firstOrCreate(
            ['slug' => 'test-cat'],
            ['name' => 'Test Cat', 'status' => true, 'sort_order' => 1]
        );

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Original Cotton Shirt',
            'slug' => 'original-cotton-shirt-' . Str::random(5),
            'sku' => 'OCS-001-' . Str::random(3),
            'price' => 1000.00,
            'status' => true,
        ]);

        $orderData = array_merge([
            'user_id' => $customer->id,
            'order_number' => 'AH-2026-TEST01',
            'subtotal' => 2000.00,
            'discount' => 100.00,
            'shipping_cost' => 150.00,
            'total' => 2050.00,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'customer_notes' => 'Test order notes',
        ], $overrides);

        $order = Order::create($orderData);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => 'Original Cotton Shirt',
            'sku' => 'OCS-001-RED-S',
            'size' => '1-2Y',
            'color' => 'Red',
            'quantity' => 2,
            'unit_price' => 1000.00,
            'total' => 2000.00,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'method' => $order->payment_method,
            'amount' => $order->total,
            'status' => $order->payment_status,
        ]);

        return $order;
    }

    #[Test]
    public function guests_are_redirected_to_login()
    {
        $order = $this->createSampleOrder();

        $this->get(route('admin.orders.index'))->assertRedirect(route('login'));
        $this->get(route('admin.orders.show', $order->id))->assertRedirect(route('login'));
    }

    #[Test]
    public function customers_receive_http_403_on_admin_orders()
    {
        $customer = $this->createCustomerUser();
        $order = $this->createSampleOrder();

        $this->actingAs($customer)
            ->get(route('admin.orders.index'))
            ->assertStatus(403);

        $this->actingAs($customer)
            ->get(route('admin.orders.show', $order->id))
            ->assertStatus(403);
    }

    #[Test]
    public function admin_and_staff_can_access_orders_management()
    {
        $admin = $this->createAdminUser();
        $staff = $this->createStaffUser();
        $order = $this->createSampleOrder();

        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get(route('admin.orders.show', $order->id))
            ->assertStatus(200);

        $this->actingAs($staff)
            ->get(route('admin.orders.index'))
            ->assertStatus(200);
    }

    #[Test]
    public function orders_listing_displays_orders_and_filters_correctly()
    {
        $admin = $this->createAdminUser();
        $order1 = $this->createSampleOrder(['order_number' => 'AH-2026-SEARCH1', 'order_status' => 'processing']);
        $order2 = $this->createSampleOrder(['order_number' => 'AH-2026-SEARCH2', 'order_status' => 'delivered']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->assertSee('AH-2026-SEARCH1')
            ->assertSee('AH-2026-SEARCH2')
            ->set('search', 'SEARCH1')
            ->assertSee('AH-2026-SEARCH1')
            ->assertDontSee('AH-2026-SEARCH2')
            ->set('search', '')
            ->set('orderStatusFilter', 'delivered')
            ->assertSee('AH-2026-SEARCH2')
            ->assertDontSee('AH-2026-SEARCH1');
    }

    #[Test]
    public function order_details_displays_snapshot_information_correctly()
    {
        $admin = $this->createAdminUser();
        $order = $this->createSampleOrder();

        Livewire::actingAs($admin)
            ->test(Show::class, ['order' => $order])
            ->assertSee($order->order_number)
            ->assertSee('Original Cotton Shirt')
            ->assertSee('OCS-001-RED-S')
            ->assertSee('1-2Y')
            ->assertSee('Red')
            ->assertSee('Tariq Mahmood');
    }

    #[Test]
    public function historical_snapshot_remains_readable_when_product_is_deleted()
    {
        $admin = $this->createAdminUser();
        $order = $this->createSampleOrder();

        // Delete the referenced product to simulate product deletion
        Product::query()->delete();

        Livewire::actingAs($admin)
            ->test(Show::class, ['order' => $order])
            ->assertStatus(200)
            ->assertSee('Original Cotton Shirt')
            ->assertSee('OCS-001-RED-S');
    }

    #[Test]
    public function status_and_payment_status_can_be_updated()
    {
        $admin = $this->createAdminUser();
        $order = $this->createSampleOrder();

        Livewire::actingAs($admin)
            ->test(Show::class, ['order' => $order])
            ->set('orderStatus', 'shipped')
            ->set('paymentStatus', 'paid')
            ->call('updateStatuses');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'shipped',
            'payment_status' => 'paid',
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'paid',
        ]);
    }

    #[Test]
    public function invalid_status_values_are_rejected()
    {
        $admin = $this->createAdminUser();
        $order = $this->createSampleOrder();

        Livewire::actingAs($admin)
            ->test(Show::class, ['order' => $order])
            ->set('orderStatus', 'invalid_status_value')
            ->call('updateStatuses')
            ->assertHasErrors(['orderStatus']);
    }
}
