<?php

namespace App\Livewire\Storefront;

use App\Models\Order;
use Livewire\Component;

class OrderShow extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if (!in_array($user->role, ['admin', 'staff']) && $order->user_id !== null && $order->user_id !== $user->id) {
                abort(403, 'Unauthorized access to order.');
            }
        }

        $this->order = $order->load(['items.product.primaryImage', 'user.addresses', 'payment']);
    }

    public function render()
    {
        // Resolve customer's current default address or fallback address
        $shippingAddress = $this->order->user ? $this->order->user->addresses()->first() : null;

        return view('livewire.storefront.orders.show', [
            'order' => $this->order,
            'shippingAddress' => $shippingAddress,
        ])->layout('components.layouts.storefront', [
            'title' => "Order #{$this->order->order_number} Details | AH Kids",
        ]);
    }
}
