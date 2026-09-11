<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $orderStatusFilter = '';
    public $paymentStatusFilter = '';
    public $paymentMethodFilter = '';
    public $dateFrom = '';
    public $dateTo = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'orderStatusFilter' => ['except' => ''],
        'paymentStatusFilter' => ['except' => ''],
        'paymentMethodFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingOrderStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPaymentStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPaymentMethodFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'orderStatusFilter', 'paymentStatusFilter', 'paymentMethodFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Order::with(['user'])->withCount('items');

        if (!empty($this->search)) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('order_number', 'like', $searchTerm)
                  ->orWhereHas('user', function ($uq) use ($searchTerm) {
                      $uq->where('name', 'like', $searchTerm)
                         ->orWhere('email', 'like', $searchTerm);
                  });
            });
        }

        if ($this->orderStatusFilter !== '') {
            $query->where('order_status', $this->orderStatusFilter);
        }

        if ($this->paymentStatusFilter !== '') {
            $query->where('payment_status', $this->paymentStatusFilter);
        }

        if ($this->paymentMethodFilter !== '') {
            $query->where('payment_method', $this->paymentMethodFilter);
        }

        if (!empty($this->dateFrom)) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if (!empty($this->dateTo)) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $orders = $query->latest()->paginate(10);

        $totalOrdersCount = Order::count();
        $pendingOrdersCount = Order::where('order_status', 'pending')->count();
        $deliveredOrdersCount = Order::where('order_status', 'delivered')->count();
        $totalRevenueSum = Order::where('payment_status', 'paid')->sum('total');

        return view('livewire.admin.orders.index', [
            'orders' => $orders,
            'totalOrdersCount' => $totalOrdersCount,
            'pendingOrdersCount' => $pendingOrdersCount,
            'deliveredOrdersCount' => $deliveredOrdersCount,
            'totalRevenueSum' => $totalRevenueSum,
        ])->layout('components.layouts.admin', ['title' => 'Orders Catalog']);
    }
}
