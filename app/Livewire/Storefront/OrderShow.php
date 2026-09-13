<?php

namespace App\Livewire\Storefront;

use App\Models\Order;
use App\Services\PaymentService;
use Livewire\Component;

class OrderShow extends Component
{
    public Order $order;

    public $reference_number = '';
    public $sender_name = '';
    public $payment_notes = '';
    public $flashMessage = '';

    public function mount(Order $order)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if (!in_array($user->role, ['admin', 'staff']) && $order->user_id !== null && $order->user_id !== $user->id) {
                abort(403, 'Unauthorized access to order.');
            }
        }

        $this->order = $order->load(['items.product.primaryImage', 'user.addresses', 'payment']);

        if ($this->order->payment) {
            $this->reference_number = $this->order->payment->reference_number ?? '';
            $this->sender_name = $this->order->payment->sender_name ?? '';
            $this->payment_notes = $this->order->payment->payment_notes ?? '';
        }
    }

    public function submitPaymentReference()
    {
        $this->validate([
            'reference_number' => 'required|string|min:4|max:100',
        ], [
            'reference_number.required' => 'Please enter your 12-digit Transaction Reference (TRX ID).',
            'reference_number.min' => 'Please enter a valid Transaction Reference (TRX ID).',
        ]);

        PaymentService::processPayment($this->order, $this->order->payment_method ?: 'jazzcash', [
            'reference_number' => $this->reference_number,
            'sender_name' => $this->sender_name,
            'payment_notes' => $this->payment_notes,
        ]);

        $this->order->refresh();
        $this->order->load(['payment']);
        $this->flashMessage = 'Payment reference submitted successfully! Store admin will verify and approve your payment.';
    }

    public function render()
    {
        // Resolve customer's current default address or fallback address
        $shippingAddress = $this->order->user ? $this->order->user->addresses()->first() : null;
        $paymentMethods = PaymentService::getAvailableMethods();

        return view('livewire.storefront.orders.show', [
            'order' => $this->order,
            'shippingAddress' => $shippingAddress,
            'paymentMethods' => $paymentMethods,
        ])->layout('components.layouts.storefront', [
            'title' => "Order #{$this->order->order_number} Details | AH Kids",
        ]);
    }
}
