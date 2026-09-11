<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Reviews\Index;
use App\Livewire\Admin\Reviews\Show;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReviewsManagementTest extends TestCase
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

    private function createCustomerUser($overrides = [])
    {
        return User::factory()->create(array_merge([
            'role' => 'customer',
            'status' => true,
            'phone' => '0300' . rand(1000000, 9999999),
        ], $overrides));
    }

    private function createCategory()
    {
        return Category::firstOrCreate(
            ['slug' => 'test-category'],
            ['name' => 'Test Category', 'status' => true, 'sort_order' => 1]
        );
    }

    private function createProduct($overrides = [])
    {
        $category = $this->createCategory();

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Test Kids Toy',
            'slug' => 'test-kids-toy-' . Str::random(5),
            'sku' => 'SKU-' . rand(1000, 9999),
            'price' => 1500.00,
            'status' => true,
        ], $overrides));
    }

    private function createReview($overrides = [])
    {
        $customer = $overrides['user_id'] ?? $this->createCustomerUser()->id;
        $product = $overrides['product_id'] ?? $this->createProduct()->id;

        return Review::create(array_merge([
            'user_id' => $customer,
            'product_id' => $product,
            'order_id' => null,
            'rating' => 5,
            'comment' => 'Great quality product! Highly recommended.',
            'status' => true,
        ], $overrides));
    }

    #[Test]
    public function guests_are_redirected_to_login()
    {
        $review = $this->createReview();

        $this->get(route('admin.reviews.index'))->assertRedirect(route('login'));
        $this->get(route('admin.reviews.show', $review->id))->assertRedirect(route('login'));
    }

    #[Test]
    public function customer_receives_http_403_on_admin_reviews_index_and_show()
    {
        $customer = $this->createCustomerUser();
        $review = $this->createReview();

        $this->actingAs($customer)
            ->get(route('admin.reviews.index'))
            ->assertStatus(403);

        $this->actingAs($customer)
            ->get(route('admin.reviews.show', $review->id))
            ->assertStatus(403);
    }

    #[Test]
    public function admin_can_access_reviews_index()
    {
        $admin = $this->createAdminUser();

        $this->actingAs($admin)
            ->get(route('admin.reviews.index'))
            ->assertStatus(200);
    }

    #[Test]
    public function staff_can_access_reviews_index()
    {
        $staff = $this->createStaffUser();

        $this->actingAs($staff)
            ->get(route('admin.reviews.index'))
            ->assertStatus(200);
    }

    #[Test]
    public function reviews_are_listed_correctly()
    {
        $admin = $this->createAdminUser();
        $customer = $this->createCustomerUser(['name' => 'Ayesha Fatima']);
        $product = $this->createProduct(['name' => 'Super Plush Bear']);
        $review = $this->createReview([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'comment' => 'Softest toy ever!',
            'rating' => 5,
        ]);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->assertSee('Ayesha Fatima')
            ->assertSee('Super Plush Bear')
            ->assertSee('Softest toy ever!');
    }

    #[Test]
    public function search_by_customer_name_works()
    {
        $admin = $this->createAdminUser();
        $c1 = $this->createCustomerUser(['name' => 'Usman Ali']);
        $c2 = $this->createCustomerUser(['name' => 'Zainab Bibi']);

        $r1 = $this->createReview(['user_id' => $c1->id, 'comment' => 'Comment for Usman']);
        $r2 = $this->createReview(['user_id' => $c2->id, 'comment' => 'Comment for Zainab']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('search', 'Usman')
            ->assertSee('Usman Ali')
            ->assertSee('Comment for Usman')
            ->assertDontSee('Zainab Bibi')
            ->assertDontSee('Comment for Zainab');
    }

    #[Test]
    public function search_by_customer_email_works()
    {
        $admin = $this->createAdminUser();
        $c1 = $this->createCustomerUser(['email' => 'unique_usman@example.com']);
        $c2 = $this->createCustomerUser(['email' => 'unique_zainab@example.com']);

        $r1 = $this->createReview(['user_id' => $c1->id, 'comment' => 'Review from Usman email']);
        $r2 = $this->createReview(['user_id' => $c2->id, 'comment' => 'Review from Zainab email']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('search', 'unique_usman@example.com')
            ->assertSee('Review from Usman email')
            ->assertDontSee('Review from Zainab email');
    }

    #[Test]
    public function search_by_product_name_works()
    {
        $admin = $this->createAdminUser();
        $p1 = $this->createProduct(['name' => 'Wooden Blocks Set']);
        $p2 = $this->createProduct(['name' => 'Remote Control Car']);

        $r1 = $this->createReview(['product_id' => $p1->id, 'comment' => 'Blocks are awesome']);
        $r2 = $this->createReview(['product_id' => $p2->id, 'comment' => 'Car drives fast']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('search', 'Wooden Blocks')
            ->assertSee('Blocks are awesome')
            ->assertDontSee('Car drives fast');
    }

    #[Test]
    public function search_by_comment_text_works()
    {
        $admin = $this->createAdminUser();
        $r1 = $this->createReview(['comment' => 'Spectacular toy quality and packaging!']);
        $r2 = $this->createReview(['comment' => 'Poor durability broke in 2 days.']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('search', 'Spectacular')
            ->assertSee('Spectacular toy quality')
            ->assertDontSee('Poor durability');
    }

    #[Test]
    public function rating_filter_works()
    {
        $admin = $this->createAdminUser();
        $r1 = $this->createReview(['rating' => 5, 'comment' => 'Five Star Rating Item']);
        $r2 = $this->createReview(['rating' => 1, 'comment' => 'One Star Rating Item']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('ratingFilter', '5')
            ->assertSee('Five Star Rating Item')
            ->assertDontSee('One Star Rating Item');
    }

    #[Test]
    public function status_filter_works()
    {
        $admin = $this->createAdminUser();
        $r1 = $this->createReview(['status' => true, 'comment' => 'Published Approved Item']);
        $r2 = $this->createReview(['status' => false, 'comment' => 'Pending Moderation Item']);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('statusFilter', 'published')
            ->assertSee('Published Approved Item')
            ->assertDontSee('Pending Moderation Item')
            ->set('statusFilter', 'pending')
            ->assertSee('Pending Moderation Item')
            ->assertDontSee('Published Approved Item');
    }

    #[Test]
    public function review_detail_page_works()
    {
        $admin = $this->createAdminUser();
        $customer = $this->createCustomerUser(['name' => 'Bilal Ahmed', 'email' => 'bilal@example.com']);
        $product = $this->createProduct(['name' => 'Baby Stroller Deluxe', 'sku' => 'STR-9988']);
        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'AH-2026-REV01',
            'subtotal' => 5000.00,
            'total' => 5000.00,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
        ]);

        $review = $this->createReview([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => 4,
            'comment' => 'Detailed test feedback about baby stroller',
            'status' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.reviews.show', $review->id))
            ->assertStatus(200);

        Livewire::actingAs($admin)
            ->test(Show::class, ['review' => $review])
            ->assertSee('Bilal Ahmed')
            ->assertSee('bilal@example.com')
            ->assertSee('Baby Stroller Deluxe')
            ->assertSee('STR-9988')
            ->assertSee('AH-2026-REV01')
            ->assertSee('Detailed test feedback about baby stroller');
    }

    #[Test]
    public function missing_null_order_relationship_does_not_crash()
    {
        $admin = $this->createAdminUser();
        $review = $this->createReview(['order_id' => null, 'comment' => 'Direct review without order']);

        Livewire::actingAs($admin)
            ->test(Show::class, ['review' => $review])
            ->assertStatus(200)
            ->assertSee('Direct review without order')
            ->assertSee('No associated order (Direct Product Review)');
    }

    #[Test]
    public function moderation_status_toggle_works()
    {
        $admin = $this->createAdminUser();
        $review = $this->createReview(['status' => false]);

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('toggleStatus', $review->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'status' => true,
        ]);

        // Toggle back to false
        Livewire::actingAs($admin)
            ->test(Show::class, ['review' => $review->fresh()])
            ->call('toggleStatus')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'status' => false,
        ]);
    }

    #[Test]
    public function unauthorized_customer_cannot_perform_admin_review_actions()
    {
        $customer = $this->createCustomerUser();
        $review = $this->createReview();

        Livewire::actingAs($customer)
            ->test(Index::class)
            ->call('toggleStatus', $review->id)
            ->assertStatus(403);

        Livewire::actingAs($customer)
            ->test(Index::class)
            ->call('confirmDelete', $review->id)
            ->assertStatus(403);
    }

    #[Test]
    public function deletion_requires_confirmation_and_does_not_affect_customer_product_order_records()
    {
        $admin = $this->createAdminUser();
        $customer = $this->createCustomerUser(['name' => 'Preserved Customer']);
        $product = $this->createProduct(['name' => 'Preserved Product']);
        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'AH-2026-KEEP01',
            'subtotal' => 2000.00,
            'total' => 2000.00,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
        ]);

        $review = $this->createReview([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'comment' => 'Review to be deleted',
        ]);

        // Confirm delete modal state
        Livewire::actingAs($admin)
            ->test(Index::class)
            ->call('confirmDelete', $review->id)
            ->assertSet('confirmingReviewDeletion', true)
            ->assertSet('reviewToDeleteId', $review->id);

        // Perform deletion
        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('reviewToDeleteId', $review->id)
            ->call('deleteReview')
            ->assertHasNoErrors();

        // Verify ONLY review row deleted from database
        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);

        // Verify Customer, Product, and Order remain intact in database
        $this->assertDatabaseHas('users', ['id' => $customer->id]);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }
}
