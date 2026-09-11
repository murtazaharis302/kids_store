<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public User $user;
    public $status = true;

    public function mount(User $user)
    {
        // Enforce customer role verification
        if ($user->role !== 'customer') {
            abort(403, 'Unauthorized access to non-customer profile.');
        }

        $this->user = $user;
        $this->status = (bool) $user->status;
    }

    public function updateStatus()
    {
        // Ensure user is still customer before updating
        if ($this->user->role !== 'customer') {
            abort(403, 'Unauthorized access.');
        }

        $this->validate([
            'status' => 'required|boolean',
        ]);

        // Update ONLY the status boolean column
        $this->user->update([
            'status' => (bool) $this->status,
        ]);

        session()->flash('message', "Customer account status updated to " . ($this->status ? 'Active' : 'Inactive') . ".");
    }

    public function render()
    {
        $orders = $this->user->orders()->latest()->paginate(5);

        $totalOrdersCount = $this->user->orders()->count();
        $totalSpent = $this->user->orders()->where('payment_status', 'paid')->sum('total');
        $pendingOrdersCount = $this->user->orders()->where('order_status', 'pending')->count();
        $deliveredOrdersCount = $this->user->orders()->where('order_status', 'delivered')->count();
        $cancelledOrdersCount = $this->user->orders()->where('order_status', 'cancelled')->count();

        $savedAddresses = $this->user->addresses;

        return view('livewire.admin.customers.show', [
            'orders' => $orders,
            'totalOrdersCount' => $totalOrdersCount,
            'totalSpent' => $totalSpent,
            'pendingOrdersCount' => $pendingOrdersCount,
            'deliveredOrdersCount' => $deliveredOrdersCount,
            'cancelledOrdersCount' => $cancelledOrdersCount,
            'savedAddresses' => $savedAddresses,
        ])->layout('components.layouts.admin', ['title' => 'Customer Profile: ' . $this->user->name]);
    }
}
