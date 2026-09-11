<?php

namespace Tests\Feature\Storefront;

use App\Livewire\Storefront\Cart;
use App\Livewire\Storefront\ProductShow;
use App\Models\Cart as CartModel;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected $category;
    protected $color;
    protected $size;
    protected $product;
    protected $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Girls Collection',
            'slug' => 'girls-collection',
            'status' => true,
        ]);

        $this->color = Color::create(['name' => 'Rose Pink', 'hex_code' => '#FFC0CB']);
        $this->size = Size::create(['name' => '3-4Y']);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Floral Summer Dress',
            'slug' => 'floral-summer-dress',
            'sku' => 'FSD-001',
            'price' => 2500.00,
            'sale_price' => 2000.00,
            'status' => true,
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $this->product->id,
            'color_id' => $this->color->id,
            'size_id' => $this->size->id,
            'sku' => 'FSD-001-PNK-34Y',
            'price' => 2500.00,
            'sale_price' => 1800.00,
            'stock_quantity' => 10,
            'status' => true,
        ]);
    }

    public function test_cart_page_renders_successfully()
    {
        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
        $response->assertSee('Shopping Cart');
    }

    public function test_guest_can_add_product_with_variant_to_cart()
    {
        Livewire::test(ProductShow::class, ['product' => $this->product])
            ->set('selectedVariantId', $this->variant->id)
            ->set('quantity', 2)
            ->call('addToCart')
            ->assertHasNoErrors()
            ->assertSet('cartMessage', 'Added to cart.');

        $cart = CartService::getCart();
        $this->assertCount(1, $cart->items);
        $this->assertEquals(2, $cart->items->first()->quantity);
        $this->assertEquals(1800.00, $cart->items->first()->price);
    }

    public function test_guest_cannot_add_more_quantity_than_available_stock()
    {
        // Variant stock is 10
        Livewire::test(ProductShow::class, ['product' => $this->product])
            ->set('selectedVariantId', $this->variant->id)
            ->set('quantity', 15)
            ->call('addToCart')
            ->assertHasErrors(['quantity' => 'Only 10 items are currently available.']);

        $cart = CartService::getCart();
        $this->assertCount(0, $cart->items);
    }

    public function test_guest_cannot_add_inactive_product_to_cart()
    {
        $inactiveProduct = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Inactive Dress',
            'slug' => 'inactive-dress',
            'sku' => 'IN-001',
            'price' => 1000.00,
            'status' => false,
        ]);

        $result = CartService::addToCart($inactiveProduct->id, null, 1);
        $this->assertFalse($result['success']);
        $this->assertEquals('Product is currently unavailable.', $result['message']);
    }

    public function test_guest_cannot_add_product_without_selecting_variant_when_variants_exist()
    {
        Livewire::test(ProductShow::class, ['product' => $this->product])
            ->set('selectedVariantId', null)
            ->set('quantity', 1)
            ->call('addToCart')
            ->assertHasErrors(['variant']);
    }

    public function test_guest_can_update_cart_item_quantity()
    {
        CartService::addToCart($this->product->id, $this->variant->id, 2);
        $cart = CartService::getCart();
        $item = $cart->items->first();

        Livewire::test(Cart::class)
            ->call('updateQuantity', $item->id, 5);

        $this->assertEquals(5, $item->fresh()->quantity);
    }

    public function test_updating_cart_quantity_clamps_if_exceeding_stock()
    {
        CartService::addToCart($this->product->id, $this->variant->id, 2);
        $cart = CartService::getCart();
        $item = $cart->items->first();

        // Reduce variant stock to 3
        $this->variant->update(['stock_quantity' => 3]);

        Livewire::test(Cart::class)
            ->call('updateQuantity', $item->id, 8)
            ->assertSet('flashMessage', 'Quantity adjusted to available stock (3).');

        $this->assertEquals(3, $item->fresh()->quantity);
    }

    public function test_guest_can_remove_item_from_cart()
    {
        CartService::addToCart($this->product->id, $this->variant->id, 2);
        $cart = CartService::getCart();
        $item = $cart->items->first();

        Livewire::test(Cart::class)
            ->call('removeItem', $item->id);

        $this->assertCount(0, $cart->fresh()->items);
    }

    public function test_guest_cart_merges_into_authenticated_user_cart_upon_login()
    {
        // 1. Guest adds item to cart
        $guestSessionId = session()->getId();
        CartService::addToCart($this->product->id, $this->variant->id, 3);
        $guestCart = CartModel::where('session_id', $guestSessionId)->first();
        $this->assertNotNull($guestCart);
        $this->assertCount(1, $guestCart->items);

        // 2. User logs in
        $user = User::factory()->create();
        $this->actingAs($user);

        // 3. Request user cart via CartService::getCart()
        $userCart = CartService::getCart();

        // Guest cart items should be merged into user cart
        $this->assertEquals($user->id, $userCart->user_id);
        $this->assertCount(1, $userCart->items);
        $this->assertEquals(3, $userCart->items->first()->quantity);

        // Guest cart record and its items should be completely deleted
        $this->assertDatabaseMissing('carts', ['id' => $guestCart->id]);
    }

    public function test_cart_subtotal_calculated_correctly_with_sale_prices()
    {
        // Variant effective price is 1800.00
        CartService::addToCart($this->product->id, $this->variant->id, 2); // 2 * 1800 = 3600

        $subtotal = CartService::getSubtotal();
        $this->assertEquals(3600.00, $subtotal);
    }

    public function test_header_cart_count_reflects_total_cart_quantity()
    {
        CartService::addToCart($this->product->id, $this->variant->id, 4);
        $this->assertEquals(4, CartService::getCartCount());
    }

    public function test_adding_duplicate_product_variant_increments_existing_cart_item()
    {
        CartService::addToCart($this->product->id, $this->variant->id, 2);
        CartService::addToCart($this->product->id, $this->variant->id, 3);

        $cart = CartService::getCart();
        $this->assertCount(1, $cart->items);
        $this->assertEquals(5, $cart->items->first()->quantity);
    }

    public function test_checkout_button_is_disabled_or_placeholder_without_creating_checkout_route()
    {
        $this->assertFalse(\Route::has('checkout'));

        CartService::addToCart($this->product->id, $this->variant->id, 1);

        Livewire::test(Cart::class)
            ->assertSee('Proceed to Checkout');
    }
}
