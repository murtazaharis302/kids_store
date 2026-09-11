<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Customers\Index;
use App\Livewire\Admin\Customers\Show;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CustomersManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser()
    {
        return User::factory()->create(['role' => 'admin', 'name' => 'Admin Boss', 'email' => 'admin_' . Str::random(5) . '@ahkids.pk']);
    }

    private function createStaffUser()
    {
        return User::factory()->create(['role' => 'staff', 'name' => 'Staff Member', 'email' => 'staff_' . Str::random(5) . '@ahkids.pk']);
    }

    private function createCustomerUser($overrides = [])
    {
        return User::factory()->create(array_merge([
            'role' => 'customer',
            'status' => true,
            'phone' => '0300' . rand(1000000, 9999999),
        ], $overrides));
    }

    #[Test]
    public function guests_are_redirected_to_login()
    {
        $customer = $this->createCustomerUser();

        $this->get(route('admin.customers.index'))->assertRedirect(route('login'));
        $this->get(route('admin.customers.show', $customer->id))->assertRedirect(route('login'));
    }

    #[Test]
    public function customer_receives_http_403_on_admin_customers_catalog()
    {
        $customer = $this->createCustomerUser();

        $this->actingAs($customer)
            ->get(route('admin.customers.index'))
            ->assertStatus(403);
    }

    #[Test]
    public function customer_receives_http_403_on_another_customer_detail_page()
    {
        $customer1 = $this->createCustomerUser();
        $customer2 = $this->createCustomerUser();

        $this->actingAs($customer1)
            ->get(route('admin.customers.show', $customer2->id))
            ->assertStatus(403);
    }

    #[Test]
    public function admin_can_access_customer_listing()
    {
        $admin = $this->createAdminUser();

        $this->actingAs($admin)
            ->get(route('admin.customers.index'))
            ->assertStatus(200);
    }

    #[Test]
    public function staff_can_access_customer_listing()
    {
        $staff = $this->createStaffUser();

        $this->actingAs($staff)
            ->get(route('admin.customers.index'))
            ->assertStatus(200);
    }

    #[Test]
    public function customer_listing_shows_customer_accounts_only_and_excludes_admin_and_staff()
    {
        $admin = $this->createAdminUser();
        $staff = $this->createStaffUser();
        $customer = $this->createCustomerUser(['name' => 'Visible Customer']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->assertSee('Visible Customer')
            ->assertDontSee($admin->email)
            ->assertDontSee($staff->email);
    }

    #[Test]
    public function search_by_customer_name_works()
    {
        $admin = $this->createAdminUser();
        $c1 = $this->createCustomerUser(['name' => 'Ayesha Khan']);
        $c2 = $this->createCustomerUser(['name' => 'Bilal Ahmed']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('search', 'Ayesha')
            ->assertSee('Ayesha Khan')
            ->assertDontSee('Bilal Ahmed');
    }

    #[Test]
    public function search_by_email_works()
    {
        $admin = $this->createAdminUser();
        $c1 = $this->createCustomerUser(['name' => 'Customer One', 'email' => 'one_unique@example.com']);
        $c2 = $this->createCustomerUser(['name' => 'Customer Two', 'email' => 'two_unique@example.com']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('search', 'one_unique@example.com')
            ->assertSee('Customer One')
            ->assertDontSee('Customer Two');
    }

    #[Test]
    public function search_by_phone_works()
    {
        $admin = $this->createAdminUser();
        $c1 = $this->createCustomerUser(['name' => 'Phone Customer', 'phone' => '03998877665']);
        $c2 = $this->createCustomerUser(['name' => 'Other Customer', 'phone' => '03112233445']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('search', '03998877665')
            ->assertSee('Phone Customer')
            ->assertDontSee('Other Customer');
    }

    #[Test]
    public function customer_detail_displays_profile_data_and_addresses()
    {
        $admin = $this->createAdminUser();
        $customer = $this->createCustomerUser(['name' => 'Hamza Ali', 'email' => 'hamza@example.com', 'phone' => '03001234567']);
        
        Address::create([
            'user_id' => $customer->id,
            'first_name' => 'Hamza',
            'last_name' => 'Ali',
            'phone' => '03001234567',
            'address_line_1' => 'House 42, Street 7',
            'city' => 'Lahore',
            'state' => 'Punjab',
            'postal_code' => '54000',
            'country' => 'Pakistan',
            'is_default' => true,
        ]);

        Livewire::actingAs($admin)
            ->test(Show::class, ['user' => $customer])
            ->assertStatus(200)
            ->assertSee('Hamza Ali')
            ->assertSee('hamza@example.com')
            ->assertSee('03001234567')
            ->assertSee('Saved Customer Addresses')
            ->assertSee('House 42, Street 7');
    }

    #[Test]
    public function customer_detail_displays_order_history_and_correct_statistics()
    {
        $admin = $this->createAdminUser();
        $customer = $this->createCustomerUser();

        // Paid order (counted in total spent)
        Order::create([
            'user_id' => $customer->id,
            'order_number' => 'AH-2026-PAID01',
            'subtotal' => 1500.00,
            'total' => 1500.00,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
        ]);

        // Cancelled order (NOT counted in total spent)
        Order::create([
            'user_id' => $customer->id,
            'order_number' => 'AH-2026-CANCEL01',
            'subtotal' => 3000.00,
            'total' => 3000.00,
            'payment_method' => 'card',
            'payment_status' => 'failed',
            'order_status' => 'cancelled',
        ]);

        Livewire::actingAs($admin)
            ->test(Show::class, ['user' => $customer])
            ->assertSee('AH-2026-PAID01')
            ->assertSee('AH-2026-CANCEL01')
            ->assertSee('Rs. 1,500.00')
            ->assertDontSee('Rs. 4,500.00'); // Ensure cancelled order total is not added to revenue
    }

    #[Test]
    public function customer_status_can_be_updated_with_valid_boolean()
    {
        $admin = $this->createAdminUser();
        $customer = $this->createCustomerUser(['status' => true]);

        Livewire::actingAs($admin)
            ->test(Show::class, ['user' => $customer])
            ->set('status', 0)
            ->call('updateStatus')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'status' => 0,
        ]);
    }

    #[Test]
    public function non_customer_user_detail_page_access_is_forbidden()
    {
        $admin = $this->createAdminUser();
        $staff = $this->createStaffUser();

        $this->actingAs($admin)
            ->get(route('admin.customers.show', $staff->id))
            ->assertStatus(403);
    }
}
