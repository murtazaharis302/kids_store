<?php

namespace App\Livewire\Storefront;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class Checkout extends Component
{
    // Address selection & form properties
    public $selectedAddressId = null;
    public $useNewAddress = false;

    public $first_name = '';
    public $last_name = '';
    public $phone = '';
    public $address_line_1 = '';
    public $address_line_2 = '';
    public $city = '';
    public $state = '';
    public $postal_code = '';
    public $country = 'Pakistan';
    public $save_address = true;

    // Coupon & Payment properties
    public $coupon_code = '';
    public $appliedCoupon = null; // ['code' => ..., 'discount' => ..., 'coupon_id' => ...]
    public $couponMessage = '';
    public $couponMessageType = 'success';

    public $payment_method = 'cod';
    public $customer_notes = '';

    public $errorMessage = '';

    public function mount()
    {
        // 1. Authentication check
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Admin/staff role check: Admin/staff accounts must not be treated as normal customers for customer checkout
        if (in_array($user->role, ['admin', 'staff'])) {
            session()->flash('error', 'Admin/staff accounts cannot place customer storefront orders.');
            return redirect()->route('cart.index');
        }

        // 2. Cart Resolution & Empty Cart Check
        $cart = CartService::getCart();
        if ($cart->items()->count() === 0) {
            session()->flash('error', 'Your shopping cart is empty.');
            return redirect()->route('cart.index');
        }

        // Pre-fill default address or user defaults
        $savedAddresses = Address::where('user_id', $user->id)->get();
        if ($savedAddresses->isNotEmpty()) {
            $defaultAddr = $savedAddresses->firstWhere('is_default', true) ?? $savedAddresses->first();
            $this->selectedAddressId = $defaultAddr->id;
            $this->useNewAddress = false;
        } else {
            $this->useNewAddress = true;
            $nameParts = explode(' ', $user->name, 2);
            $this->first_name = $nameParts[0] ?? '';
            $this->last_name = $nameParts[1] ?? '';
        }
    }

    public function selectSavedAddress($addressId)
    {
        $this->selectedAddressId = $addressId;
        $this->useNewAddress = false;
        $this->errorMessage = '';
    }

    public function switchToNewAddress()
    {
        $this->selectedAddressId = null;
        $this->useNewAddress = true;
        $this->errorMessage = '';
    }

    public function applyCoupon()
    {
        $this->couponMessage = '';
        $code = trim($this->coupon_code);

        if (empty($code)) {
            $this->couponMessage = 'Please enter a coupon code.';
            $this->couponMessageType = 'error';
            return;
        }

        $cart = CartService::getCart();
        $subtotal = CartService::getSubtotal();

        $result = $this->validateAndCalculateCoupon($code, $subtotal);

        if (!$result['valid']) {
            $this->couponMessage = $result['message'];
            $this->couponMessageType = 'error';
            $this->appliedCoupon = null;
            return;
        }

        $this->appliedCoupon = [
            'coupon_id' => $result['coupon']->id,
            'code' => $result['coupon']->code,
            'discount' => $result['discount'],
        ];

        $this->couponMessage = "Coupon '{$result['coupon']->code}' applied successfully!";
        $this->couponMessageType = 'success';
    }

    public function removeCoupon()
    {
        $this->appliedCoupon = null;
        $this->coupon_code = '';
        $this->couponMessage = 'Coupon removed.';
        $this->couponMessageType = 'success';
    }

    private function validateAndCalculateCoupon(string $code, float $subtotal): array
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Invalid coupon code.'];
        }

        if (!$coupon->status) {
            return ['valid' => false, 'message' => 'This coupon is inactive.'];
        }

        $now = now();
        if ($coupon->starts_at && $now->lt($coupon->starts_at)) {
            return ['valid' => false, 'message' => 'This coupon is not yet active.'];
        }

        if ($coupon->expires_at && $now->gt($coupon->expires_at)) {
            return ['valid' => false, 'message' => 'This coupon has expired.'];
        }

        // Usage limit validation using historical CouponUsage records
        if (!is_null($coupon->usage_limit)) {
            $usageCount = CouponUsage::where('coupon_id', $coupon->id)->count();
            if ($usageCount >= $coupon->usage_limit) {
                return ['valid' => false, 'message' => 'This coupon has reached its maximum usage limit.'];
            }
        }

        // Minimum order amount check
        if ($subtotal < (float) $coupon->minimum_order_amount) {
            return [
                'valid' => false,
                'message' => "Minimum order amount of Rs. " . number_format($coupon->minimum_order_amount, 2) . " is required for this coupon.",
            ];
        }

        // Calculate discount amount
        $discount = 0.0;
        if ($coupon->type === 'fixed') {
            $discount = (float) $coupon->value;
        } elseif ($coupon->type === 'percentage') {
            $discount = round(($subtotal * (float) $coupon->value) / 100, 2);
        }

        if (!is_null($coupon->maximum_discount)) {
            $discount = min($discount, (float) $coupon->maximum_discount);
        }

        // Discount cannot exceed subtotal
        $discount = min($discount, $subtotal);

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discount,
        ];
    }

    public function placeOrder()
    {
        $this->errorMessage = '';

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (in_array($user->role, ['admin', 'staff'])) {
            $this->errorMessage = 'Admin/staff accounts cannot place storefront orders.';
            return;
        }

        // Address Validation & Data Resolution
        $shippingData = [];
        if (!$this->useNewAddress && $this->selectedAddressId) {
            // Freshly query database to verify address ownership
            $address = Address::where('id', $this->selectedAddressId)
                ->where('user_id', $user->id)
                ->first();

            if (!$address) {
                $this->errorMessage = 'Selected shipping address is invalid or does not belong to your account.';
                return;
            }

            $shippingData = [
                'first_name' => $address->first_name,
                'last_name' => $address->last_name,
                'phone' => $address->phone,
                'address_line_1' => $address->address_line_1,
                'address_line_2' => $address->address_line_2,
                'city' => $address->city,
                'state' => $address->state,
                'postal_code' => $address->postal_code,
                'country' => $address->country,
            ];
        } else {
            $this->validate([
                'first_name' => 'required|string|max:100',
                'phone' => 'required|string|max:30',
                'address_line_1' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'country' => 'required|string|max:100',
            ]);

            $shippingData = [
                'first_name' => trim($this->first_name),
                'last_name' => trim($this->last_name),
                'phone' => trim($this->phone),
                'address_line_1' => trim($this->address_line_1),
                'address_line_2' => trim($this->address_line_2),
                'city' => trim($this->city),
                'state' => trim($this->state),
                'postal_code' => trim($this->postal_code),
                'country' => trim($this->country),
            ];

            if ($this->save_address) {
                Address::create(array_merge(['user_id' => $user->id], $shippingData));
            }
        }

        // Database Transaction for Stock Concurrency & Order Creation
        try {
            $order = DB::transaction(function () use ($user, $shippingData) {
                // Freshly load customer cart inside transaction
                $cart = CartService::getCart();
                $cartItems = $cart->items()->get();

                if ($cartItems->isEmpty()) {
                    throw new \Exception('Your shopping cart is empty.');
                }

                $orderItemsToCreate = [];
                $subtotal = 0.0;

                foreach ($cartItems as $item) {
                    // Fresh-load Product
                    $product = Product::find($item->product_id);
                    if (!$product || !$product->status) {
                        throw new \Exception("Product '" . ($product ? $product->name : 'Item') . "' is currently unavailable.");
                    }

                    $variant = null;
                    if ($item->variant_id) {
                        // Lock ProductVariant row for update
                        $variant = ProductVariant::where('id', $item->variant_id)
                            ->lockForUpdate()
                            ->first();

                        if (!$variant || !$variant->status || $variant->product_id != $product->id) {
                            throw new \Exception("Selected variant option for '{$product->name}' is no longer available.");
                        }

                        if ($variant->stock_quantity < $item->quantity) {
                            throw new \Exception("Insufficient stock for '{$product->name}'. Only {$variant->stock_quantity} available.");
                        }
                    } else {
                        // Check if product has active variants that required selection
                        if ($product->variants()->where('status', true)->exists()) {
                            throw new \Exception("A variant selection is required for '{$product->name}'.");
                        }
                    }

                    $unitPrice = CartService::getEffectivePrice($variant, $product);
                    $lineTotal = round($unitPrice * $item->quantity, 2);
                    $subtotal += $lineTotal;

                    // Resolve Historical Snapshot strings
                    $sizeName = ($variant && $variant->size) ? $variant->size->name : null;
                    $colorName = ($variant && $variant->color) ? $variant->color->name : null;
                    $sku = $variant ? $variant->sku : $product->sku;

                    $orderItemsToCreate[] = [
                        'product_id' => $product->id,
                        'variant_id' => $variant ? $variant->id : null,
                        'product_name' => $product->name,
                        'sku' => $sku,
                        'size' => $sizeName,
                        'color' => $colorName,
                        'quantity' => $item->quantity,
                        'unit_price' => $unitPrice,
                        'total' => $lineTotal,
                        'variant_instance' => $variant,
                    ];
                }

                // Server-side Coupon validation & calculation inside transaction
                $discount = 0.0;
                $couponModel = null;

                if ($this->appliedCoupon) {
                    $couponResult = $this->validateAndCalculateCoupon($this->appliedCoupon['code'], $subtotal);
                    if (!$couponResult['valid']) {
                        throw new \Exception("Coupon error: " . $couponResult['message']);
                    }
                    $couponModel = $couponResult['coupon'];
                    $discount = $couponResult['discount'];
                }

                // Shipping cost calculation
                $shippingCost = 200.00; // Standard flat shipping cost
                $total = max(0.00, round($subtotal - $discount + $shippingCost, 2));

                // Unique Order Number Generation
                do {
                    $orderNumber = 'AHK-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                } while (Order::where('order_number', $orderNumber)->exists());

                // Create Order record
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => $orderNumber,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'shipping_cost' => $shippingCost,
                    'total' => $total,
                    'payment_method' => $this->payment_method ?: 'cod',
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                    'customer_notes' => $this->customer_notes,
                ]);

                // Create Order Items & Deduct Stock
                foreach ($orderItemsToCreate as $itemData) {
                    $variantInstance = $itemData['variant_instance'];
                    unset($itemData['variant_instance']);

                    $itemData['order_id'] = $order->id;
                    OrderItem::create($itemData);

                    if ($variantInstance) {
                        $variantInstance->decrement('stock_quantity', $itemData['quantity']);
                    }
                }

                // Create CouponUsage record if coupon applied
                if ($couponModel && $discount > 0) {
                    CouponUsage::create([
                        'coupon_id' => $couponModel->id,
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'discount_amount' => $discount,
                    ]);

                    $couponModel->increment('used_count');
                }

                // Create Payment placeholder record
                Payment::create([
                    'order_id' => $order->id,
                    'transaction_id' => null,
                    'method' => $this->payment_method ?: 'cod',
                    'amount' => $total,
                    'status' => 'pending',
                    'paid_at' => null,
                ]);

                // Clear customer's cart only after successful order creation
                $cart->items()->delete();

                return $order;
            });

            return redirect()->route('orders.show', $order->id);
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }
    }

    public function render()
    {
        $cart = CartService::getCart();
        $items = $cart->items()
            ->with(['product.primaryImage', 'product.images', 'variant.color', 'variant.size'])
            ->get();

        // Freshly recalculate totals
        $subtotal = 0.0;
        foreach ($items as $item) {
            if ($item->product && $item->product->status && ($item->variant_id === null || ($item->variant && $item->variant->status))) {
                $unitPrice = CartService::getEffectivePrice($item->variant, $item->product);
                $subtotal += $unitPrice * $item->quantity;
            }
        }

        $discount = $this->appliedCoupon ? (float) $this->appliedCoupon['discount'] : 0.0;
        $shippingCost = 200.00;
        $total = max(0.00, round($subtotal - $discount + $shippingCost, 2));

        $savedAddresses = Address::where('user_id', auth()->id())->get();

        return view('livewire.storefront.checkout', [
            'cart' => $cart,
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shippingCost' => $shippingCost,
            'total' => $total,
            'savedAddresses' => $savedAddresses,
        ])->layout('components.layouts.storefront', [
            'title' => 'Checkout | AH Kids',
        ]);
    }
}
