<?php

namespace App\Livewire\Storefront;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartHeaderCount extends Component
{
    #[On('cart-updated')]
    public function updateCartCount()
    {
        // Re-renders component when cart-updated event is dispatched
    }

    public function render()
    {
        $count = CartService::getCartCount();

        return view('livewire.storefront.cart-header-count', [
            'count' => $count,
        ]);
    }
}
