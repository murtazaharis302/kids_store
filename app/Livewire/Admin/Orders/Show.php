<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Show extends Component
{
    public Order $order;

    public $orderStatus = '';
    public $paymentStatus = '';
    public $confirmingCancellation = false;

    public array $allowedOrderStatuses = [
        'pending',
        'confirmed',
        'processing',
        'shipped',
        'delivered',
        'cancelled',
    ];

    public array $allowedPaymentStatuses = [
        'pending',
        'pending_verification',
        'paid',
        'failed',
        'refunded',
    ];

    public function mount(Order $order)
    {
        $this->order = $order->load(['user.addresses', 'items.product.primaryImage', 'payment']);
        $this->orderStatus = $order->order_status;
        $this->paymentStatus = $order->payment_status;
    }

    public function promptCancellation()
    {
        $this->confirmingCancellation = true;
    }

    public function cancelCancellationPrompt()
    {
        $this->confirmingCancellation = false;
        // Revert status selection to current DB state
        $this->orderStatus = $this->order->order_status;
    }

    public function updateStatuses()
    {
        $this->validate([
            'orderStatus' => ['required', Rule::in($this->allowedOrderStatuses)],
            'paymentStatus' => ['required', Rule::in($this->allowedPaymentStatuses)],
        ], [
            'orderStatus.in' => 'Selected order status is invalid.',
            'paymentStatus.in' => 'Selected payment status is invalid.',
        ]);

        // Require explicit confirmation when changing to cancelled
        if ($this->orderStatus === 'cancelled' && $this->order->order_status !== 'cancelled' && !$this->confirmingCancellation) {
            $this->confirmingCancellation = true;
            return;
        }

        $this->order->update([
            'order_status' => $this->orderStatus,
            'payment_status' => $this->paymentStatus,
        ]);

        // Sync payment record status if present
        if ($this->order->payment) {
            $paymentUpdate = ['status' => $this->paymentStatus];
            if ($this->paymentStatus === 'paid' && !$this->order->payment->paid_at) {
                $paymentUpdate['paid_at'] = now();
            }
            $this->order->payment->update($paymentUpdate);
        }

        $this->confirmingCancellation = false;
        $this->order->refresh();

        session()->flash('message', "Order #{$this->order->order_number} status updated successfully.");
    }

    public function verifyAndApprovePayment()
    {
        PaymentService::markAsReceived($this->order, 'Verified & approved via Admin Panel');
        $this->order->refresh();
        $this->orderStatus = $this->order->order_status;
        $this->paymentStatus = $this->order->payment_status;
        session()->flash('message', "Payment for Order #{$this->order->order_number} verified & marked as Paid!");
    }

    public function render()
    {
        return view('livewire.admin.orders.show')
            ->layout('components.layouts.admin', ['title' => 'Order #' . $this->order->order_number]);
    }
}
