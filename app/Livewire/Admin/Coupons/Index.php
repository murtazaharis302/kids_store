<?php

namespace App\Livewire\Admin\Coupons;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $typeFilter = '';
    public $validityFilter = '';

    public $confirmingCouponDeletion = false;
    public $couponToDeleteId = null;
    public $couponToDeleteCode = '';
    public $hasHistoricalUsages = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'validityFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingValidityFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'typeFilter', 'validityFilter']);
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon) {
            $this->couponToDeleteId = $coupon->id;
            $this->couponToDeleteCode = $coupon->code;

            // Authoritative Fresh Database Check for Historical Usages
            $this->hasHistoricalUsages = CouponUsage::where('coupon_id', $coupon->id)->exists();
            $this->confirmingCouponDeletion = true;
        }
    }

    public function cancelDelete()
    {
        $this->confirmingCouponDeletion = false;
        $this->couponToDeleteId = null;
        $this->couponToDeleteCode = '';
        $this->hasHistoricalUsages = false;
    }

    public function deleteCoupon()
    {
        if (!$this->couponToDeleteId) {
            return;
        }

        $coupon = Coupon::find($this->couponToDeleteId);
        if (!$coupon) {
            $this->cancelDelete();
            return;
        }

        // Authoritative Fresh Database Check immediately before deletion/deactivation
        $hasUsages = CouponUsage::where('coupon_id', $coupon->id)->exists();

        if ($hasUsages) {
            // Protect historical records: Deactivate instead of physical deletion
            $coupon->update(['status' => false]);
            session()->flash('message', "Coupon '{$coupon->code}' has historical usage records and was deactivated instead of deleted.");
        } else {
            // Physical deletion safe when no historical usage exists
            $couponCode = $coupon->code;
            $coupon->delete();
            session()->flash('message', "Coupon '{$couponCode}' deleted successfully.");
        }

        $this->cancelDelete();
    }

    public function toggleStatus($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon) {
            $coupon->update(['status' => !$coupon->status]);
            session()->flash('message', "Coupon '{$coupon->code}' status updated to " . ($coupon->status ? 'Active' : 'Inactive') . ".");
        }
    }

    public function render()
    {
        $query = Coupon::withCount('usages');

        if (!empty($this->search)) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->where('code', 'like', $searchTerm);
        }

        if ($this->statusFilter !== '') {
            $query->where('status', (bool) $this->statusFilter);
        }

        if ($this->typeFilter !== '') {
            $query->where('type', $this->typeFilter);
        }

        if ($this->validityFilter === 'active') {
            $query->where('status', true)
                  ->where(function ($q) {
                      $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                  });
        } elseif ($this->validityFilter === 'expired') {
            $query->whereNotNull('expires_at')
                  ->where('expires_at', '<=', now());
        } elseif ($this->validityFilter === 'upcoming') {
            $query->whereNotNull('starts_at')
                  ->where('starts_at', '>', now());
        }

        $coupons = $query->latest()->paginate(10);

        // Coupon Summary Metrics
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where('status', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->count();
        $expiredCoupons = Coupon::whereNotNull('expires_at')
            ->where('expires_at', '<=', now())->count();
        $totalCouponUsages = CouponUsage::count();

        return view('livewire.admin.coupons.index', [
            'coupons' => $coupons,
            'totalCoupons' => $totalCoupons,
            'activeCoupons' => $activeCoupons,
            'expiredCoupons' => $expiredCoupons,
            'totalCouponUsages' => $totalCouponUsages,
        ])->layout('components.layouts.admin', ['title' => 'Coupons & Discounts']);
    }
}
