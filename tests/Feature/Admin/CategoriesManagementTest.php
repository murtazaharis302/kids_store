<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Categories\Create;
use App\Livewire\Admin\Categories\Edit;
use App\Livewire\Admin\Categories\Index;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoriesManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser()
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    private function createStaffUser()
    {
        return User::factory()->create([
            'role' => 'staff',
        ]);
    }

    private function createCustomerUser()
    {
        return User::factory()->create([
            'role' => 'customer',
        ]);
    }

    #[Test]
    public function guests_are_redirected_to_login()
    {
        $this->get(route('admin.categories.index'))->assertRedirect(route('login'));
        $this->get(route('admin.categories.create'))->assertRedirect(route('login'));
    }

    #[Test]
    public function customers_receive_http_403_on_admin_categories()
    {
        $customer = $this->createCustomerUser();

        $this->actingAs($customer)
            ->get(route('admin.categories.index'))
            ->assertStatus(403);

        $this->actingAs($customer)
            ->get(route('admin.categories.create'))
            ->assertStatus(403);
    }

    #[Test]
    public function admin_and_staff_can_access_categories_management()
    {
        $admin = $this->createAdminUser();
        $staff = $this->createStaffUser();

        $this->actingAs($admin)
            ->get(route('admin.categories.index'))
            ->assertStatus(200);

        $this->actingAs($staff)
            ->get(route('admin.categories.index'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_create_category_with_automatic_slug()
    {
        $admin = $this->createAdminUser();

        Livewire::actingAs($admin)
            ->test(Create::class)
            ->set('name', 'Girls Festive Collection')
            ->assertSet('slug', 'girls-festive-collection')
            ->set('sort_order', 5)
            ->call('save')
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'name' => 'Girls Festive Collection',
            'slug' => 'girls-festive-collection',
            'sort_order' => 5,
        ]);
    }

    #[Test]
    public function edit_does_not_automatically_overwrite_slug_when_name_changes()
    {
        $admin = $this->createAdminUser();
        $category = Category::create([
            'name' => 'Boys Wear',
            'slug' => 'custom-boys-slug',
            'sort_order' => 1,
        ]);

        Livewire::actingAs($admin)
            ->test(Edit::class, ['category' => $category])
            ->set('name', 'Boys Apparel')
            ->assertSet('slug', 'custom-boys-slug')
            ->call('update')
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Boys Apparel',
            'slug' => 'custom-boys-slug',
        ]);
    }

    #[Test]
    public function edit_prevents_selecting_self_or_descendants_as_parent()
    {
        $admin = $this->createAdminUser();
        $parent = Category::create(['name' => 'Parent Cat', 'slug' => 'parent-cat']);
        $child = Category::create(['name' => 'Child Cat', 'slug' => 'child-cat', 'parent_id' => $parent->id]);

        Livewire::actingAs($admin)
            ->test(Edit::class, ['category' => $parent])
            ->set('parent_id', $child->id)
            ->call('update')
            ->assertHasErrors(['parent_id']);
    }

    #[Test]
    public function safe_delete_prevents_deleting_category_with_products()
    {
        $admin = $this->createAdminUser();
        $category = Category::create(['name' => 'Test Cat', 'slug' => 'test-cat']);
        
        Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 1000,
            'sku' => 'TP-001',
            'status' => true,
        ]);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('confirmDelete', $category->id)
            ->call('deleteCategory')
            ->assertSet('deleteError', 'This category cannot be deleted because it contains 1 product(s). Move or remove those products first.');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    #[Test]
    public function safe_delete_prevents_deleting_parent_category_with_children()
    {
        $admin = $this->createAdminUser();
        $parent = Category::create(['name' => 'Main Parent', 'slug' => 'main-parent']);
        Category::create(['name' => 'Sub Child', 'slug' => 'sub-child', 'parent_id' => $parent->id]);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('confirmDelete', $parent->id)
            ->call('deleteCategory')
            ->assertSet('deleteError', 'This category cannot be deleted because it has 1 child subcategory/subcategories. Handle or remove child categories first.');

        $this->assertDatabaseHas('categories', ['id' => $parent->id]);
    }

    #[Test]
    public function empty_category_can_be_deleted()
    {
        $admin = $this->createAdminUser();
        $category = Category::create(['name' => 'Empty Cat', 'slug' => 'empty-cat']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('confirmDelete', $category->id)
            ->call('deleteCategory')
            ->assertSet('confirmingCategoryDeletion', false);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
