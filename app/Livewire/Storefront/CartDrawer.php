<?php

namespace App\Livewire\Storefront;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartDrawer extends Component
{
    public $flashMessage = '';
    public $flashMessageType = 'success'; // 'success' or 'error'

    #[On('cart-updated')]
    public function refreshCart()
    {
        // Re-renders the component on cart-updated
    }

    public function updateQuantity($itemId, $quantity)
    {
        $quantity = (int) $quantity;
        if ($quantity < 1) {
            $this->removeItem($itemId);
            return;
        }

        $result = CartService::updateQuantity($itemId, $quantity);

        if ($result['success']) {
            $this->flashMessage = $result['message'];
            $this->flashMessageType = 'success';
        } else {
            $this->flashMessage = $result['message'];
            $this->flashMessageType = 'error';
        }

        $this->dispatch('cart-updated');
    }

    public function incrementQuantity($itemId, $currentQty)
    {
        $this->updateQuantity($itemId, $currentQty + 1);
    }

    public function decrementQuantity($itemId, $currentQty)
    {
        if ($currentQty <= 1) {
            $this->removeItem($itemId);
            return;
        }

        $this->updateQuantity($itemId, $currentQty - 1);
    }

    public function removeItem($itemId)
    {
        $result = CartService::removeItem($itemId);

        if ($result['success']) {
            $this->flashMessage = 'Item removed from cart.';
            $this->flashMessageType = 'success';
        } else {
            $this->flashMessage = $result['message'];
            $this->flashMessageType = 'error';
        }

        $this->dispatch('cart-updated');
    }

    public function clearCart()
    {
        $cart = CartService::getCart();
        $cart->items()->delete();

        $this->flashMessage = 'Shopping cart cleared.';
        $this->flashMessageType = 'success';

        $this->dispatch('cart-updated');
    }

    public function render()
    {
        $cart = CartService::getCart();
        $items = $cart->items()
            ->with(['product.primaryImage', 'product.images', 'variant.color', 'variant.size'])
            ->get();

        $count = 0;
        $subtotal = 0.0;

        foreach ($items as $item) {
            $effectivePrice = CartService::getEffectivePrice($item->variant, $item->product);
            $item->effective_unit_price = $effectivePrice;
            $item->unit_price = (float) ($item->variant ? ($item->variant->price ?: $item->product->price) : $item->product->price);
            $subtotal += $effectivePrice * $item->quantity;
            $count += $item->quantity;
        }

        $shippingFee = $count > 0 ? 350.0 : 0.0;
        $total = $subtotal + $shippingFee;

        return view('livewire.storefront.cart-drawer', [
            'cart' => $cart,
            'items' => $items,
            'count' => $count,
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'total' => $total,
        ]);
    }
}
