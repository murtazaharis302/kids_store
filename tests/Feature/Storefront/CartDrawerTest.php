<?php

namespace Tests\Feature\Storefront;

use App\Livewire\Storefront\CartDrawer;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CartDrawerTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;
    private ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create(['name' => 'Girls', 'slug' => 'girls', 'status' => true]);
        $color = Color::create(['name' => 'Red', 'hex_code' => '#FF0000']);
        $size = Size::create(['name' => '3-4 Years', 'code' => '3-4Y']);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Embroidered Dress',
            'slug' => 'embroidered-dress',
            'sku' => 'EMB-PROD-001',
            'price' => 2500.00,
            'status' => true,
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $this->product->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
            'sku' => 'EMB-RED-3Y',
            'price' => 2500.00,
            'stock_quantity' => 20,
            'status' => true,
        ]);
    }

    public function test_cart_drawer_renders_successfully()
    {
        Livewire::test(CartDrawer::class)
            ->assertStatus(200)
            ->assertSee('Shopping Cart');
    }

    public function test_cart_drawer_displays_items_and_subtotal()
    {
        CartService::addToCart($this->product->id, $this->variant->id, 2);

        Livewire::test(CartDrawer::class)
            ->assertSee('Embroidered Dress')
            ->assertSee('Size: 3-4 Years')
            ->assertSee('Rs. 5,000.00')
            ->assertSee('CHECKOUT')
            ->assertSee('VIEW CART');
    }

    public function test_cart_drawer_allows_updating_quantity()
    {
        CartService::addToCart($this->product->id, $this->variant->id, 1);

        $cart = CartService::getCart();
        $item = $cart->items()->first();

        Livewire::test(CartDrawer::class)
            ->call('incrementQuantity', $item->id, $item->quantity)
            ->assertDispatched('cart-updated');

        $this->assertEquals(2, $item->fresh()->quantity);
    }

    public function test_cart_drawer_allows_removing_item()
    {
        CartService::addToCart($this->product->id, $this->variant->id, 1);

        $cart = CartService::getCart();
        $item = $cart->items()->first();

        Livewire::test(CartDrawer::class)
            ->call('removeItem', $item->id)
            ->assertDispatched('cart-updated');

        $this->assertCount(0, CartService::getCart()->items);
    }
}
