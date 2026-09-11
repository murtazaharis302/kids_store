<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Inventory\Index;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser()
    {
        return User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin User',
            'email' => 'admin_' . Str::random(5) . '@ahkids.pk',
        ]);
    }

    private function createStaffUser()
    {
        return User::factory()->create([
            'role' => 'staff',
            'name' => 'Staff Member',
            'email' => 'staff_' . Str::random(5) . '@ahkids.pk',
        ]);
    }

    private function createCustomerUser()
    {
        return User::factory()->create([
            'role' => 'customer',
            'status' => true,
        ]);
    }

    private function createCategory($name = 'Toys & Games')
    {
        return Category::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name, 'status' => true, 'sort_order' => 1]
        );
    }

    private function createProduct($overrides = [])
    {
        $category = $overrides['category_id'] ?? $this->createCategory()->id;

        return Product::create(array_merge([
            'category_id' => $category,
            'name' => 'Interactive Toy Set',
            'slug' => 'interactive-toy-set-' . Str::random(5),
            'sku' => 'PRD-' . rand(1000, 9999),
            'price' => 2500.00,
            'status' => true,
        ], $overrides));
    }

    private function createVariant($overrides = [])
    {
        $product = $overrides['product_id'] ?? $this->createProduct()->id;

        return ProductVariant::create(array_merge([
            'product_id' => $product,
            'size_id' => null,
            'color_id' => null,
            'sku' => 'SKU-VAR-' . Str::random(5),
            'price' => 2500.00,
            'stock_quantity' => 20,
            'status' => true,
        ], $overrides));
    }

    #[Test]
    public function guests_are_redirected_to_login()
    {
        $this->get(route('admin.inventory.index'))->assertRedirect(route('login'));
    }

    #[Test]
    public function customer_receives_http_403_on_admin_inventory()
    {
        $customer = $this->createCustomerUser();

        $this->actingAs($customer)
            ->get(route('admin.inventory.index'))
            ->assertStatus(403);
    }

    #[Test]
    public function admin_can_access_inventory()
    {
        $admin = $this->createAdminUser();

        $this->actingAs($admin)
            ->get(route('admin.inventory.index'))
            ->assertStatus(200);
    }

    #[Test]
    public function staff_can_access_inventory()
    {
        $staff = $this->createStaffUser();

        $this->actingAs($staff)
            ->get(route('admin.inventory.index'))
            ->assertStatus(200);
    }

    #[Test]
    public function inventory_variants_are_listed_correctly()
    {
        $admin = $this->createAdminUser();
        $product = $this->createProduct(['name' => 'Plush Teddy Bear']);
        $variant = $this->createVariant([
            'product_id' => $product->id,
            'sku' => 'TEDDY-RED-MEDIUM',
            'stock_quantity' => 45,
        ]);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->assertSee('Plush Teddy Bear')
            ->assertSee('TEDDY-RED-MEDIUM')
            ->assertSee('45');
    }

    #[Test]
    public function search_by_product_name_works()
    {
        $admin = $this->createAdminUser();
        $p1 = $this->createProduct(['name' => 'Wooden Educational Blocks']);
        $p2 = $this->createProduct(['name' => 'Remote Control Monster Truck']);

        $v1 = $this->createVariant(['product_id' => $p1->id, 'sku' => 'SKU-BLOCKS-01']);
        $v2 = $this->createVariant(['product_id' => $p2->id, 'sku' => 'SKU-TRUCK-01']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('search', 'Wooden Educational')
            ->assertSee('Wooden Educational Blocks')
            ->assertSee('SKU-BLOCKS-01')
            ->assertDontSee('Remote Control Monster Truck')
            ->assertDontSee('SKU-TRUCK-01');
    }

    #[Test]
    public function search_by_sku_works()
    {
        $admin = $this->createAdminUser();
        $v1 = $this->createVariant(['sku' => 'UNIQUE-SKU-9988']);
        $v2 = $this->createVariant(['sku' => 'OTHER-SKU-1122']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('search', 'UNIQUE-SKU-9988')
            ->assertSee('UNIQUE-SKU-9988')
            ->assertDontSee('OTHER-SKU-1122');
    }

    #[Test]
    public function category_filter_works()
    {
        $admin = $this->createAdminUser();
        $cat1 = $this->createCategory('Baby Clothes');
        $cat2 = $this->createCategory('Outdoor Toys');

        $p1 = $this->createProduct(['name' => 'Baby Romper', 'category_id' => $cat1->id]);
        $p2 = $this->createProduct(['name' => 'Water Gun', 'category_id' => $cat2->id]);

        $v1 = $this->createVariant(['product_id' => $p1->id, 'sku' => 'ROMPER-01']);
        $v2 = $this->createVariant(['product_id' => $p2->id, 'sku' => 'GUN-01']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('categoryFilter', (string) $cat1->id)
            ->assertSee('Baby Romper')
            ->assertDontSee('Water Gun');
    }

    #[Test]
    public function size_filter_works()
    {
        $admin = $this->createAdminUser();
        $sizeSmall = Size::create(['name' => 'Small', 'sort_order' => 1, 'status' => true]);
        $sizeLarge = Size::create(['name' => 'Large', 'sort_order' => 2, 'status' => true]);

        $v1 = $this->createVariant(['size_id' => $sizeSmall->id, 'sku' => 'VAR-SMALL-01']);
        $v2 = $this->createVariant(['size_id' => $sizeLarge->id, 'sku' => 'VAR-LARGE-01']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('sizeFilter', (string) $sizeSmall->id)
            ->assertSee('VAR-SMALL-01')
            ->assertDontSee('VAR-LARGE-01');
    }

    #[Test]
    public function color_filter_works()
    {
        $admin = $this->createAdminUser();
        $colorRed = Color::create(['name' => 'Ruby Red', 'hex_code' => '#FF0000', 'status' => true]);
        $colorBlue = Color::create(['name' => 'Ocean Blue', 'hex_code' => '#0000FF', 'status' => true]);

        $v1 = $this->createVariant(['color_id' => $colorRed->id, 'sku' => 'VAR-RED-01']);
        $v2 = $this->createVariant(['color_id' => $colorBlue->id, 'sku' => 'VAR-BLUE-01']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('colorFilter', (string) $colorRed->id)
            ->assertSee('VAR-RED-01')
            ->assertDontSee('VAR-BLUE-01');
    }

    #[Test]
    public function low_stock_filter_works()
    {
        $admin = $this->createAdminUser();
        $vInStock = $this->createVariant(['stock_quantity' => 50, 'sku' => 'HIGH-STOCK-50']);
        $vLowStock = $this->createVariant(['stock_quantity' => 8, 'sku' => 'LOW-STOCK-08']);
        $vOutOfStock = $this->createVariant(['stock_quantity' => 0, 'sku' => 'ZERO-STOCK-00']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('stockStatusFilter', 'low_stock')
            ->assertSee('LOW-STOCK-08')
            ->assertDontSee('HIGH-STOCK-50')
            ->assertDontSee('ZERO-STOCK-00');
    }

    #[Test]
    public function out_of_stock_filter_works()
    {
        $admin = $this->createAdminUser();
        $vInStock = $this->createVariant(['stock_quantity' => 50, 'sku' => 'HIGH-STOCK-50']);
        $vOutOfStock = $this->createVariant(['stock_quantity' => 0, 'sku' => 'ZERO-STOCK-00']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('stockStatusFilter', 'out_of_stock')
            ->assertSee('ZERO-STOCK-00')
            ->assertDontSee('HIGH-STOCK-50');
    }

    #[Test]
    public function stock_increase_works()
    {
        $admin = $this->createAdminUser();
        $variant = $this->createVariant(['stock_quantity' => 15, 'sku' => 'VAR-INC-TEST']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('openAdjustmentModal', $variant->id)
            ->set('adjustmentAmount', 10)
            ->call('applyStockAdjustment')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock_quantity' => 25,
        ]);
    }

    #[Test]
    public function stock_decrease_works()
    {
        $admin = $this->createAdminUser();
        $variant = $this->createVariant(['stock_quantity' => 20, 'sku' => 'VAR-DEC-TEST']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('openAdjustmentModal', $variant->id)
            ->set('adjustmentAmount', -5)
            ->call('applyStockAdjustment')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock_quantity' => 15,
        ]);
    }

    #[Test]
    public function negative_resulting_stock_is_rejected()
    {
        $admin = $this->createAdminUser();
        $variant = $this->createVariant(['stock_quantity' => 5, 'sku' => 'VAR-NEG-TEST']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('openAdjustmentModal', $variant->id)
            ->set('adjustmentAmount', -10)
            ->call('applyStockAdjustment')
            ->assertHasErrors(['adjustmentAmount']);

        // Database stock remains unchanged at 5
        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock_quantity' => 5,
        ]);
    }

    #[Test]
    public function zero_or_invalid_adjustment_is_rejected()
    {
        $admin = $this->createAdminUser();
        $variant = $this->createVariant(['stock_quantity' => 10]);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('openAdjustmentModal', $variant->id)
            ->set('adjustmentAmount', 0)
            ->call('applyStockAdjustment')
            ->assertHasErrors(['adjustmentAmount']);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock_quantity' => 10,
        ]);
    }

    #[Test]
    public function adjustment_uses_fresh_database_state()
    {
        $admin = $this->createAdminUser();
        $variant = $this->createVariant(['stock_quantity' => 10, 'sku' => 'FRESH-DB-TEST']);

        // Simulate concurrent DB change before admin submits modal
        ProductVariant::where('id', $variant->id)->update(['stock_quantity' => 15]);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('openAdjustmentModal', $variant->id)
            ->set('adjustmentAmount', +5)
            ->call('applyStockAdjustment')
            ->assertHasNoErrors();

        // 15 (fresh DB stock) + 5 = 20
        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock_quantity' => 20,
        ]);
    }

    #[Test]
    public function product_size_color_relationships_display_safely_when_null()
    {
        $admin = $this->createAdminUser();
        $variant = $this->createVariant([
            'size_id' => null,
            'color_id' => null,
            'sku' => 'NULL-RELS-VAR',
        ]);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->assertStatus(200)
            ->assertSee('NULL-RELS-VAR')
            ->assertSee('Default');
    }

    #[Test]
    public function adjustment_does_not_modify_unrelated_variant_fields_or_product()
    {
        $admin = $this->createAdminUser();
        $product = $this->createProduct(['price' => 3000.00, 'status' => true]);
        $variant = $this->createVariant([
            'product_id' => $product->id,
            'sku' => 'UNTOUCHED-SKU-99',
            'price' => 3000.00,
            'stock_quantity' => 10,
        ]);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('openAdjustmentModal', $variant->id)
            ->set('adjustmentAmount', 5)
            ->call('applyStockAdjustment');

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'sku' => 'UNTOUCHED-SKU-99',
            'price' => 3000.00,
            'stock_quantity' => 15,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'price' => 3000.00,
            'status' => true,
        ]);
    }
}
