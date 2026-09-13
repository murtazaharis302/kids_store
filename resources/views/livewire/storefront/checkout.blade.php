<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Breadcrumbs -->
    <nav class="flex text-xs text-slate-400 font-medium space-x-2">
        <a href="{{ route('home') }}" class="hover:text-rose-600 transition">Home</a>
        <span>/</span>
        <a href="{{ route('cart.index') }}" class="hover:text-rose-600 transition">Shopping Cart</a>
        <span>/</span>
        <span class="text-slate-700 font-semibold">Checkout</span>
    </nav>

    <!-- Page Header -->
    <div class="border-b border-slate-200/80 pb-5">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading tracking-tight">
            Checkout & Shipping
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Complete your delivery address and order details</p>
    </div>

    <!-- Error Alert Notification -->
    @if($errorMessage)
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-bold flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $errorMessage }}</span>
            </div>
            <button type="button" wire:click="$set('errorMessage', '')" class="opacity-60 hover:opacity-100">&times;</button>
        </div>
    @endif

    <form wire:submit.prevent="placeOrder">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT COLUMN: Shipping & Address (8 Cols) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Saved Addresses Section -->
                @if($savedAddresses->isNotEmpty())
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 space-y-4 shadow-xs">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h2 class="text-base font-extrabold text-slate-900 font-heading">
                                Saved Shipping Addresses
                            </h2>
                            <button type="button" 
                                    wire:click="switchToNewAddress"
                                    class="text-xs font-bold text-rose-600 hover:text-rose-700 transition">
                                + Add New Address
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($savedAddresses as $addr)
                                <div wire:click="selectSavedAddress({{ $addr->id }})"
                                     class="cursor-pointer p-4 rounded-2xl border transition-all duration-200 relative flex flex-col justify-between space-y-3 {{ !$useNewAddress && $selectedAddressId == $addr->id ? 'border-rose-500 bg-rose-50/40 ring-2 ring-rose-500/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                    <div class="space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-extrabold text-slate-900 font-heading">
                                                {{ $addr->first_name }} {{ $addr->last_name }}
                                            </span>
                                            @if($addr->is_default)
                                                <span class="text-[10px] font-extrabold bg-slate-900 text-white px-2 py-0.5 rounded-md uppercase">Default</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-600 font-medium leading-relaxed">
                                            {{ $addr->address_line_1 }}
                                            @if($addr->address_line_2), {{ $addr->address_line_2 }} @endif
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ $addr->city }}{{ $addr->state ? ', ' . $addr->state : '' }} {{ $addr->postal_code }}
                                        </p>
                                        <p class="text-xs text-slate-500 font-mono">
                                            Phone: {{ $addr->phone }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 pt-2 border-t border-slate-100 text-xs font-bold {{ !$useNewAddress && $selectedAddressId == $addr->id ? 'text-rose-600' : 'text-slate-400' }}">
                                        <span class="w-3 h-3 rounded-full border border-current flex items-center justify-center">
                                            @if(!$useNewAddress && $selectedAddressId == $addr->id)
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                            @endif
                                        </span>
                                        <span>{{ !$useNewAddress && $selectedAddressId == $addr->id ? 'Selected' : 'Use this address' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Shipping Address Form (New Address / Edit) -->
                <div x-data="{ open: @entangle('useNewAddress') }" 
                     x-show="open || {{ $savedAddresses->isEmpty() ? 'true' : 'false' }}"
                     class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 space-y-6 shadow-xs">
                    
                    <h2 class="text-lg font-extrabold text-slate-900 font-heading border-b border-slate-100 pb-3">
                        Shipping Information
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Email Address -->
                        <div class="space-y-1 sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" 
                                   wire:model="email"
                                   placeholder="your.email@example.com" 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                            @error('email') <span class="text-[11px] font-bold text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- First Name -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">First Name <span class="text-rose-500">*</span></label>
                            <input type="text" 
                                   wire:model="first_name"
                                   placeholder="First Name" 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                            @error('first_name') <span class="text-[11px] font-bold text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Last Name</label>
                            <input type="text" 
                                   wire:model="last_name"
                                   placeholder="Last Name" 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                        </div>

                        <!-- Phone -->
                        <div class="space-y-1 sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700">Phone Number <span class="text-rose-500">*</span></label>
                            <input type="text" 
                                   wire:model="phone"
                                   placeholder="03001234567" 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                            @error('phone') <span class="text-[11px] font-bold text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Address Line 1 -->
                        <div class="space-y-1 sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700">Street Address <span class="text-rose-500">*</span></label>
                            <input type="text" 
                                   wire:model="address_line_1"
                                   placeholder="House/Apartment number, street name" 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                            @error('address_line_1') <span class="text-[11px] font-bold text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Address Line 2 -->
                        <div class="space-y-1 sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700">Apartment, Suite, Landmark (Optional)</label>
                            <input type="text" 
                                   wire:model="address_line_2"
                                   placeholder="Near main plaza, etc." 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                        </div>

                        <!-- City -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">City <span class="text-rose-500">*</span></label>
                            <input type="text" 
                                   wire:model="city"
                                   placeholder="Lahore, Karachi, Islamabad..." 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                            @error('city') <span class="text-[11px] font-bold text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- State -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">State / Province</label>
                            <input type="text" 
                                   wire:model="state"
                                   placeholder="Punjab, Sindh, etc." 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                        </div>

                        <!-- Postal Code -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Postal Code</label>
                            <input type="text" 
                                   wire:model="postal_code"
                                   placeholder="54000" 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                        </div>

                        <!-- Country -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Country <span class="text-rose-500">*</span></label>
                            <input type="text" 
                                   wire:model="country"
                                   readonly
                                   class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 cursor-not-allowed">
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="save_address" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                            <span class="text-xs font-bold text-slate-700">Save this address to my account for future orders</span>
                        </label>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 space-y-6 shadow-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900 font-heading">
                                Select Payment Method
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">Choose how you'd like to pay for your order</p>
                        </div>
                        <span class="text-[11px] font-extrabold bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full border border-emerald-200 uppercase tracking-wider">
                            🔒 100% Secure Payment
                        </span>
                    </div>

                    <!-- Payment Option Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        @foreach($paymentMethods as $methodKey => $method)
                            <label class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer flex flex-col justify-between space-y-2 {{ $payment_method === $methodKey ? 'border-rose-500 bg-rose-50/40 ring-2 ring-rose-500/20 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" 
                                               wire:model.live="payment_method" 
                                               value="{{ $methodKey }}" 
                                               class="text-rose-600 focus:ring-rose-500">
                                        <span class="text-sm font-extrabold text-slate-900 font-heading">
                                            {{ $method['name'] }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-md inline-block w-fit">
                                    {{ $method['badge'] }}
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Selected Gateway Short Notice -->
                    <div class="pt-3">
                        @if(in_array($payment_method, ['jazzcash', 'easypaisa', 'bank_transfer']))
                            <div class="p-4 rounded-2xl bg-slate-900 text-white text-xs space-y-1 shadow-md">
                                <div class="flex items-center gap-2 font-bold text-rose-300">
                                    <span>⚡ Advance Digital Payment</span>
                                </div>
                                <p class="text-slate-300 leading-relaxed">
                                    After clicking <strong>Place Order & Proceed to Payment</strong> below, you will get our official receiving account details and can easily submit your TRX ID payment receipt.
                                </p>
                            </div>
                        @elseif($payment_method === 'card')
                            <div class="p-4 rounded-2xl bg-gradient-to-r from-slate-900 to-rose-950 text-white text-xs space-y-1 shadow-md">
                                <div class="flex items-center gap-2 font-bold text-emerald-400">
                                    <span>💳 Stripe Instant Card Gateway</span>
                                </div>
                                <p class="text-slate-300 leading-relaxed">
                                    Pay instantly using your Visa, Mastercard, or UnionPay debit/credit card. Payment is verified in real-time.
                                </p>
                            </div>
                        @elseif($payment_method === 'cod')
                            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs space-y-1">
                                <span class="font-extrabold">Cash on Delivery (COD)</span>
                                <p class="text-amber-800">You will pay cash directly to the courier representative upon receiving your parcel.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Customer Notes -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 space-y-3 shadow-xs">
                    <h2 class="text-sm font-extrabold text-slate-900 font-heading">
                        Order Notes (Optional)
                    </h2>
                    <textarea wire:model="customer_notes" 
                              rows="3" 
                              placeholder="Any special instructions for delivery or packaging..."
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition"></textarea>
                </div>

            </div>

            <!-- RIGHT COLUMN: Order Summary & Placement (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 space-y-6 shadow-xs sticky top-24">
                    <h2 class="text-lg font-extrabold text-slate-900 font-heading border-b border-slate-100 pb-4">
                        Order Summary
                    </h2>

                    <!-- Items List -->
                    <div class="space-y-4 max-h-64 overflow-y-auto pr-1">
                        @foreach($items as $item)
                            @php
                                $product = $item->product;
                                $variant = $item->variant;
                                $unitPrice = \App\Services\CartService::getEffectivePrice($variant, $product);
                                $lineTotal = $unitPrice * $item->quantity;

                                $imgUrl = '';
                                $hasImg = false;
                                if ($product) {
                                    if ($product->primaryImage && !empty($product->primaryImage->url)) {
                                        $imgUrl = $product->primaryImage->url;
                                        $hasImg = true;
                                    } elseif ($product->images && $product->images->isNotEmpty() && !empty($product->images->first()->url)) {
                                        $imgUrl = $product->images->first()->url;
                                        $hasImg = true;
                                    }
                                }
                            @endphp

                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                    @if($hasImg)
                                        <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[9px] font-bold text-slate-400">AH Kids</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-bold text-slate-900 truncate font-heading">{{ $product ? $product->name : 'Item' }}</h4>
                                    <div class="text-[11px] text-slate-500">
                                        Qty: {{ $item->quantity }}
                                        @if($variant && $variant->size) • {{ $variant->size->name }} @endif
                                        @if($variant && $variant->color) • {{ $variant->color->name }} @endif
                                    </div>
                                </div>
                                <div class="text-xs font-extrabold text-slate-900 font-heading">
                                    Rs. {{ number_format($lineTotal, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Coupon Code Input -->
                    <div class="pt-4 border-t border-slate-100 space-y-2">
                        <label class="block text-xs font-bold text-slate-700">Have a Promo Coupon?</label>
                        @if($appliedCoupon)
                            <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between text-xs font-bold text-emerald-800">
                                <span>Coupon '{{ $appliedCoupon['code'] }}' Applied</span>
                                <button type="button" wire:click="removeCoupon" class="text-rose-600 hover:underline">Remove</button>
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <input type="text" 
                                       wire:model="coupon_code"
                                       placeholder="Enter coupon code"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold uppercase focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                                <button type="button" 
                                        wire:click="applyCoupon"
                                        class="px-4 py-2 bg-slate-900 hover:bg-rose-600 text-white font-bold text-xs rounded-xl transition shrink-0">
                                    Apply
                                </button>
                            </div>
                        @endif

                        @if($couponMessage)
                            <p class="text-[11px] font-bold {{ $couponMessageType === 'success' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $couponMessage }}
                            </p>
                        @endif
                    </div>

                    <!-- Totals Breakdown -->
                    <div class="space-y-2.5 text-xs pt-4 border-t border-slate-100">
                        <div class="flex justify-between text-slate-600 font-medium">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-900 font-heading">Rs. {{ number_format($subtotal, 2) }}</span>
                        </div>

                        @if($discount > 0)
                            <div class="flex justify-between text-emerald-600 font-bold">
                                <span>Discount</span>
                                <span>- Rs. {{ number_format($discount, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-slate-600 font-medium">
                            <span>Shipping (Standard)</span>
                            <span class="font-bold text-slate-900 font-heading">Rs. {{ number_format($shippingCost, 2) }}</span>
                        </div>
                    </div>

                    <!-- Final Payable Total -->
                    <div class="border-t border-slate-100 pt-4 flex justify-between items-baseline">
                        <span class="text-base font-extrabold text-slate-900 font-heading">Total Payable</span>
                        <span class="text-2xl font-extrabold text-rose-600 font-heading">Rs. {{ number_format($total, 2) }}</span>
                    </div>

                    <!-- Place Order Action -->
                    <div class="space-y-2 pt-2">
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="w-full py-4 px-6 rounded-2xl bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white font-extrabold text-sm shadow-md shadow-rose-500/20 transition flex items-center justify-center gap-2">
                            <span wire:loading.remove>
                                {{ $payment_method === 'cod' ? 'Place Order (Rs. ' . number_format($total, 2) . ')' : 'Place Order & Proceed to Payment (Rs. ' . number_format($total, 2) . ')' }}
                            </span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Placing Order...</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
