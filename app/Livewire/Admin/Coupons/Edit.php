<?php

namespace App\Livewire\Admin\Coupons;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Edit extends Component
{
    use WithPagination;

    public Coupon $coupon;

    public $code = '';
    public $type = 'percentage';
    public $value = '';
    public $minimum_order_amount = 0;
    public $maximum_discount = null;
    public $usage_limit = null;
    public $starts_at = null;
    public $expires_at = null;
    public $status = true;

    public $hasUsages = false;

    public function mount(Coupon $coupon)
    {
        $this->coupon = $coupon;
        $this->code = $coupon->code;
        $this->type = $coupon->type;
        $this->value = $coupon->value;
        $this->minimum_order_amount = $coupon->minimum_order_amount;
        $this->maximum_discount = $coupon->maximum_discount;
        $this->usage_limit = $coupon->usage_limit;
        $this->starts_at = $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : null;
        $this->expires_at = $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : null;
        $this->status = (bool) $coupon->status;

        // Authoritative Fresh Database Check for Historical Usage
        $this->hasUsages = CouponUsage::where('coupon_id', $coupon->id)->exists();
    }

    public function updatedCode($val)
    {
        if (!$this->hasUsages) {
            $this->code = Str::upper(trim($val));
        }
    }

    protected function rules()
    {
        $rules = [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code')->ignore($this->coupon->id),
            ],
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0.01',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'status' => 'boolean',
        ];

        if ($this->type === 'percentage') {
            $rules['value'] .= '|max:100';
        }

        return $rules;
    }

    protected $messages = [
        'code.required' => 'Coupon code is required.',
        'code.unique' => 'This coupon code is taken by another coupon.',
        'type.required' => 'Please select a discount type.',
        'value.required' => 'Discount value is required.',
        'value.min' => 'Discount value must be greater than zero.',
        'value.max' => 'Percentage discount cannot exceed 100%.',
        'expires_at.after_or_equal' => 'Expiry date must be on or after the start date.',
    ];

    public function update()
    {
        // Re-check authoritative database usage before update
        $this->hasUsages = CouponUsage::where('coupon_id', $this->coupon->id)->exists();

        // If coupon has historical usage, preserve historical code
        if ($this->hasUsages) {
            $this->code = $this->coupon->code;
        } else {
            $this->code = Str::upper(trim($this->code));
        }

        $validatedData = $this->validate();

        $this->coupon->update([
            'code' => $this->code,
            'type' => $this->type,
            'value' => (float) $this->value,
            'minimum_order_amount' => (float) ($this->minimum_order_amount ?? 0),
            'maximum_discount' => $this->maximum_discount ? (float) $this->maximum_discount : null,
            'usage_limit' => $this->usage_limit ? (int) $this->usage_limit : null,
            // used_count is explicitly preserved and untouched
            'starts_at' => $this->starts_at ? $this->starts_at : null,
            'expires_at' => $this->expires_at ? $this->expires_at : null,
            'status' => (bool) $this->status,
        ]);

        session()->flash('message', "Coupon '{$this->coupon->code}' updated successfully.");

        return redirect()->route('admin.coupons.index');
    }

    public function render()
    {
        $usageHistory = $this->coupon->usages()->with(['user', 'order'])->latest()->paginate(5);

        return view('livewire.admin.coupons.edit', [
            'usageHistory' => $usageHistory,
        ])->layout('components.layouts.admin', ['title' => 'Edit Coupon: ' . $this->coupon->code]);
    }
}
