<?php

namespace App\Livewire\Storefront;

use App\Services\CartService;
use Livewire\Component;

class Cart extends Component
{
    public $flashMessage = '';
    public $flashMessageType = 'success'; // 'success' or 'error'

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
            $this->flashMessage = $result['message'];
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

        $subtotal = CartService::getSubtotal();

        return view('livewire.storefront.cart', [
            'cart' => $cart,
            'items' => $items,
            'subtotal' => $subtotal,
        ])->layout('components.layouts.storefront', [
            'title' => 'Shopping Cart | AH Kids',
        ]);
    }
}
