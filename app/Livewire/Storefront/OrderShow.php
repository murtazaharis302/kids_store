<?php

namespace App\Livewire\Storefront;

use App\Models\Order;
use App\Services\PaymentService;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderShow extends Component
{
    use WithFileUploads;

    public Order $order;

    public $reference_number = '';
    public $sender_name = '';
    public $payment_notes = '';
    public $payment_proof_file = null;
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
        $rules = [
            'reference_number' => 'nullable|string|max:100',
            'payment_proof_file' => 'nullable|image|max:10240', // Max 10MB image
        ];

        if (empty($this->reference_number) && !$this->payment_proof_file && !($this->order->payment && $this->order->payment->payment_proof_image)) {
            $this->addError('reference_number', 'Please enter your 12-digit TRX ID or upload a payment screenshot receipt.');
            return;
        }

        $this->validate($rules);

        $proofImagePath = null;
        if ($this->payment_proof_file) {
            $proofImagePath = $this->payment_proof_file->store('payment_proofs', 'public');
        }

        PaymentService::processPayment($this->order, $this->order->payment_method ?: 'jazzcash', [
            'reference_number' => $this->reference_number ?: ($this->order->payment->reference_number ?? null),
            'sender_name' => $this->sender_name,
            'payment_notes' => $this->payment_notes,
            'payment_proof_image' => $proofImagePath,
        ]);

        $this->order->refresh();
        $this->order->load(['payment']);
        $this->flashMessage = 'Payment receipt submitted successfully! Store admin will verify and confirm your order shortly.';
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
