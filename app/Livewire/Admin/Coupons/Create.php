<?php

namespace App\Livewire\Admin\Coupons;

use App\Models\Coupon;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public $code = '';
    public $type = 'percentage';
    public $value = '';
    public $minimum_order_amount = 0;
    public $maximum_discount = null;
    public $usage_limit = null;
    public $starts_at = null;
    public $expires_at = null;
    public $status = true;

    public function updatedCode($val)
    {
        $this->code = Str::upper(trim($val));
    }

    protected function rules()
    {
        $rules = [
            'code' => 'required|string|max:50|unique:coupons,code',
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
        'code.unique' => 'This coupon code already exists.',
        'type.required' => 'Please select a discount type.',
        'value.required' => 'Discount value is required.',
        'value.min' => 'Discount value must be greater than zero.',
        'value.max' => 'Percentage discount cannot exceed 100%.',
        'expires_at.after_or_equal' => 'Expiry date must be on or after the start date.',
    ];

    public function save()
    {
        $this->code = Str::upper(trim($this->code));
        $validatedData = $this->validate();

        Coupon::create([
            'code' => $this->code,
            'type' => $this->type,
            'value' => (float) $this->value,
            'minimum_order_amount' => (float) ($this->minimum_order_amount ?? 0),
            'maximum_discount' => $this->maximum_discount ? (float) $this->maximum_discount : null,
            'usage_limit' => $this->usage_limit ? (int) $this->usage_limit : null,
            'used_count' => 0,
            'starts_at' => $this->starts_at ? $this->starts_at : null,
            'expires_at' => $this->expires_at ? $this->expires_at : null,
            'status' => (bool) $this->status,
        ]);

        session()->flash('message', "Coupon '{$this->code}' created successfully.");

        return redirect()->route('admin.coupons.index');
    }

    public function render()
    {
        return view('livewire.admin.coupons.create')
            ->layout('components.layouts.admin', ['title' => 'Create Coupon']);
    }
}
