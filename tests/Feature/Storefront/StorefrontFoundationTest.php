<?php

namespace Tests\Feature\Storefront;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StorefrontFoundationTest extends TestCase
{
    use RefreshDatabase;

    private function createCustomerUser()
    {
        return User::factory()->create([
            'role' => 'customer',
            'status' => true,
            'name' => 'Tariq Customer',
            'email' => 'tariq_' . Str::random(5) . '@example.com',
        ]);
    }

    private function createAdminUser()
    {
        return User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin Boss',
            'email' => 'admin_' . Str::random(5) . '@ahkids.pk',
        ]);
    }

    #[Test]
    public function guest_can_access_storefront_home()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Al Hayat Kids');
    }

    #[Test]
    public function storefront_displays_ah_kids_branding()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Al Hayat Kids');
        $response->assertSee('logo.png');
        $response->assertSee('Children\'s Wear', false);
    }

    #[Test]
    public function navigation_foundation_renders_without_errors()
    {
        \App\Models\Category::create(['name' => 'Newborn', 'slug' => 'newborn', 'status' => true]);
        \App\Models\Category::create(['name' => 'Girls', 'slug' => 'girls', 'status' => true]);
        \App\Models\Category::create(['name' => 'Boys', 'slug' => 'boys', 'status' => true]);
        \App\Models\Category::create(['name' => 'Accessories', 'slug' => 'accessories', 'status' => true]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Shop All');
        $response->assertSee('New Arrivals');
        $response->assertSee('Newborn');
        $response->assertSee('Girls');
        $response->assertSee('Boys');
        $response->assertSee('Accessories');
        $response->assertSee('Sale');
    }

    #[Test]
    public function search_ui_renders_in_header()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Search kids dresses, categories');
    }

    #[Test]
    public function guest_sees_login_and_register_links()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Log in');
        $response->assertSee('Register');
        $response->assertDontSee('Admin Panel');
    }

    #[Test]
    public function authenticated_customer_sees_account_ui_without_admin_panel_link()
    {
        $customer = $this->createCustomerUser();

        $response = $this->actingAs($customer)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('My Account');
        $response->assertSee('Logout');
        $response->assertDontSee('Admin Panel');
    }

    #[Test]
    public function authenticated_admin_user_sees_admin_panel_link()
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Admin Panel');
        $response->assertSee('My Account');
    }

    #[Test]
    public function customer_cannot_access_admin_panel_routes()
    {
        $customer = $this->createCustomerUser();

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertStatus(403);
    }

    #[Test]
    public function mobile_navigation_drawer_renders()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('mobileMenuOpen');
        $response->assertSee('Open Navigation Menu');
    }

    #[Test]
    public function footer_sections_and_copyright_render_correctly()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Shop Collections');
        $response->assertSee('Customer Care');
        $response->assertSee('Account & Legal', false);
        $response->assertSee('Al Hayat Kids</span>. All rights reserved.', false);
        $response->assertSee('100% Secure Checkout');
    }
}
