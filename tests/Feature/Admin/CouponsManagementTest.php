<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Coupons\Create;
use App\Livewire\Admin\Coupons\Edit;
use App\Livewire\Admin\Coupons\Index;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CouponsManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser($overrides = [])
    {
        return User::factory()->create(array_merge(['role' => 'admin'], $overrides));
    }

    private function createStaffUser($overrides = [])
    {
        return User::factory()->create(array_merge(['role' => 'staff'], $overrides));
    }

    private function createCustomerUser($overrides = [])
    {
        return User::factory()->create(array_merge(['role' => 'customer'], $overrides));
    }

    private function createSampleCoupon($overrides = [])
    {
        return Coupon::create(array_merge([
            'code' => 'TEST' . Str::upper(Str::random(4)),
            'type' => 'percentage',
            'value' => 15.00,
            'minimum_order_amount' => 1000.00,
            'maximum_discount' => 500.00,
            'usage_limit' => 100,
            'used_count' => 0,
            'status' => true,
        ], $overrides));
    }

    #[Test]
    public function guests_are_redirected_to_login()
    {
        $coupon = $this->createSampleCoupon();

        $this->get(route('admin.coupons.index'))->assertRedirect(route('login'));
        $this->get(route('admin.coupons.create'))->assertRedirect(route('login'));
        $this->get(route('admin.coupons.edit', $coupon->id))->assertRedirect(route('login'));
    }

    #[Test]
    public function customers_receive_http_403_on_admin_coupons()
    {
        $customer = $this->createCustomerUser();
        $coupon = $this->createSampleCoupon();

        $this->actingAs($customer)
            ->get(route('admin.coupons.index'))
            ->assertStatus(403);

        $this->actingAs($customer)
            ->get(route('admin.coupons.create'))
            ->assertStatus(403);

        $this->actingAs($customer)
            ->get(route('admin.coupons.edit', $coupon->id))
            ->assertStatus(403);
    }

    #[Test]
    public function admin_and_staff_can_access_coupon_management()
    {
        $admin = $this->createAdminUser();
        $staff = $this->createStaffUser();
        $coupon = $this->createSampleCoupon();

        $this->actingAs($admin)
            ->get(route('admin.coupons.index'))
            ->assertStatus(200);

        $this->actingAs($staff)
            ->get(route('admin.coupons.index'))
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get(route('admin.coupons.edit', $coupon->id))
            ->assertStatus(200);
    }

    #[Test]
    public function coupon_listing_displays_existing_coupons_and_search_works()
    {
        $admin = $this->createAdminUser();
        $c1 = $this->createSampleCoupon(['code' => 'SUMMER15']);
        $c2 = $this->createSampleCoupon(['code' => 'WINTER30']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->assertSee('SUMMER15')
            ->assertSee('WINTER30')
            ->set('search', 'SUMMER15')
            ->assertSee('SUMMER15')
            ->assertDontSee('WINTER30');
    }

    #[Test]
    public function can_create_coupon_with_valid_percentage_and_fixed_data()
    {
        $admin = $this->createAdminUser();

        Livewire::actingAs($admin)
            ->test(Create::class)
            ->set('code', 'festive50')
            ->set('type', 'percentage')
            ->set('value', 50)
            ->set('minimum_order_amount', 2000)
            ->set('maximum_discount', 1000)
            ->set('usage_limit', 50)
            ->call('save')
            ->assertRedirect(route('admin.coupons.index'));

        $this->assertDatabaseHas('coupons', [
            'code' => 'FESTIVE50',
            'type' => 'percentage',
            'value' => 50.00,
            'minimum_order_amount' => 2000.00,
            'maximum_discount' => 1000.00,
            'usage_limit' => 50,
        ]);
    }

    #[Test]
    public function invalid_coupon_data_is_rejected()
    {
        $admin = $this->createAdminUser();

        Livewire::actingAs($admin)
            ->test(Create::class)
            ->set('code', '')
            ->set('type', 'percentage')
            ->set('value', 150) // > 100% percentage
            ->set('starts_at', '2026-10-10T10:00')
            ->set('expires_at', '2026-10-01T10:00') // expires before start
            ->call('save')
            ->assertHasErrors(['code', 'value', 'expires_at']);
    }

    #[Test]
    public function duplicate_coupon_code_is_rejected()
    {
        $admin = $this->createAdminUser();
        $existing = $this->createSampleCoupon(['code' => 'DUPLICATE10']);

        Livewire::actingAs($admin)
            ->test(Create::class)
            ->set('code', 'DUPLICATE10')
            ->set('type', 'fixed')
            ->set('value', 100)
            ->call('save')
            ->assertHasErrors(['code']);
    }

    #[Test]
    public function edit_coupon_preserves_used_count_and_updates_data()
    {
        $admin = $this->createAdminUser();
        $coupon = $this->createSampleCoupon([
            'code' => 'EDITME20',
            'used_count' => 12,
        ]);

        Livewire::actingAs($admin)
            ->test(Edit::class, ['coupon' => $coupon])
            ->set('value', 25)
            ->call('update')
            ->assertRedirect(route('admin.coupons.index'));

        $this->assertDatabaseHas('coupons', [
            'id' => $coupon->id,
            'code' => 'EDITME20',
            'value' => 25.00,
            'used_count' => 12, // Preserved!
        ]);
    }

    #[Test]
    public function fresh_db_check_prevents_physical_deletion_of_used_coupon_and_deactivates_it()
    {
        $admin = $this->createAdminUser();
        $customer = $this->createCustomerUser();
        $coupon = $this->createSampleCoupon(['code' => 'USEDCOUPON']);
        
        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'AH-2026-CUP01',
            'subtotal' => 2000,
            'total' => 2000,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
        ]);

        CouponUsage::create([
            'coupon_id' => $coupon->id,
            'user_id' => $customer->id,
            'order_id' => $order->id,
            'discount_amount' => 200,
        ]);

        // Attempt deletion via Livewire Index component
        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('confirmDelete', $coupon->id)
            ->assertSet('hasHistoricalUsages', true)
            ->call('deleteCoupon');

        // Verify coupon record is NOT deleted, but deactivated (`status = false`)
        $this->assertDatabaseHas('coupons', [
            'id' => $coupon->id,
            'status' => 0,
        ]);

        // Verify coupon usages record is intact
        $this->assertDatabaseHas('coupon_usages', [
            'coupon_id' => $coupon->id,
        ]);
    }

    #[Test]
    public function unused_coupon_can_be_deleted_after_confirmation()
    {
        $admin = $this->createAdminUser();
        $coupon = $this->createSampleCoupon(['code' => 'UNUSEDCOUPON']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('confirmDelete', $coupon->id)
            ->assertSet('hasHistoricalUsages', false)
            ->call('deleteCoupon');

        $this->assertDatabaseMissing('coupons', [
            'id' => $coupon->id,
        ]);
    }

    #[Test]
    public function coupon_usage_history_displays_customer_and_order_relationships()
    {
        $admin = $this->createAdminUser();
        $customer = $this->createCustomerUser(['name' => 'Sara Usage']);
        $coupon = $this->createSampleCoupon(['code' => 'USAGEHISTORY']);

        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'AH-2026-HIST01',
            'subtotal' => 3000,
            'total' => 2700,
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        CouponUsage::create([
            'coupon_id' => $coupon->id,
            'user_id' => $customer->id,
            'order_id' => $order->id,
            'discount_amount' => 300,
        ]);

        Livewire::actingAs($admin)
            ->test(Edit::class, ['coupon' => $coupon])
            ->assertStatus(200)
            ->assertSee('Sara Usage')
            ->assertSee('AH-2026-HIST01')
            ->assertSee('Rs. 300.00');
    }
}
