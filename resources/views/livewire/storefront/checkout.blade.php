<div style="max-width:1200px;margin:0 auto;padding:32px 24px 80px;" class="checkout-page-container">

    {{-- ── Breadcrumbs ── --}}
    <nav style="display:flex;align-items:center;gap:8px;font-size:11px;font-weight:500;color:#999;letter-spacing:0.04em;margin-bottom:28px;">
        <a href="{{ route('home') }}" style="color:#999;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#C9A96E'" onmouseout="this.style.color='#999'">Home</a>
        <span>›</span>
        <a href="{{ route('cart.index') }}" style="color:#999;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#C9A96E'" onmouseout="this.style.color='#999'">Shopping Bag</a>
        <span>›</span>
        <span style="color:#0D0D0D;font-weight:600;">Checkout</span>
    </nav>

    {{-- ── Page Header ── --}}
    <div style="margin-bottom:32px;padding-bottom:16px;border-bottom:1px solid #E8E3DC;">
        <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin:0 0 4px;">Al Hayat Kids</p>
        <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(26px,4vw,36px);font-weight:600;color:#0D0D0D;margin:0;line-height:1.1;">
            Checkout &amp; Shipping
        </h1>
        <p style="font-size:12px;color:#888;margin:6px 0 0;">Enter your shipping details and choose payment method to complete your order.</p>
    </div>

    {{-- Error Alert --}}
    @if($errorMessage)
        <div style="padding:14px 20px;margin-bottom:24px;background:#FFF5F5;border:1px solid #FED7D7;color:#C53030;font-size:12px;font-weight:600;display:flex;align-items:center;justify-content:space-between;">
            <span>{{ $errorMessage }}</span>
            <button type="button" wire:click="$set('errorMessage', '')" style="background:none;border:none;cursor:pointer;color:#888;">&times;</button>
        </div>
    @endif

    {{-- Stock Reservation Banner --}}
    @if($items->isNotEmpty())
        @php
            $remainingSeconds = \App\Services\CartService::getCartReservationRemainingSeconds();
        @endphp
        <div x-data="{
                secondsLeft: {{ $remainingSeconds }},
                timer: null,
                formatTime(seconds) {
                    const m = Math.floor(seconds / 60);
                    const s = seconds % 60;
                    return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
                }
             }"
             x-init="
                timer = setInterval(() => {
                    if (secondsLeft > 0) {
                        secondsLeft--;
                    } else {
                        clearInterval(timer);
                        $wire.$refresh();
                    }
                }, 1000);
             "
             style="padding:14px 20px;background:#F7F4EF;border:1px solid #E8E3DC;margin-bottom:32px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <span style="font-size:16px;">⏱️</span>
                <div>
                    <div style="font-size:11px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#0D0D0D;">Stock Reservation Active (10 Mins)</div>
                    <div style="font-size:11px;color:#666;margin-top:2px;">Your items are reserved for 10 minutes. Complete order now to lock in stock!</div>
                </div>
            </div>
            <div style="background:#FFFFFF;padding:6px 14px;border:1px solid #E8E3DC;font-family:monospace;font-size:13px;font-weight:700;color:#C9A96E;letter-spacing:0.1em;">
                <span x-text="secondsLeft > 0 ? formatTime(secondsLeft) : '00:00'"></span>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="placeOrder">
        <div class="checkout-grid-wrap">

            {{-- ══ LEFT COLUMN: Shipping & Address ══ --}}
            <div style="display:flex;flex-direction:column;gap:28px;">

                {{-- Saved Addresses Section --}}
                @if($savedAddresses->isNotEmpty())
                    <div style="padding:24px;background:#FFFFFF;border:1px solid #E8E3DC;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #E8E3DC;">
                            <h2 style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:#0D0D0D;margin:0;">
                                Saved Shipping Addresses
                            </h2>
                            <button type="button"
                                    wire:click="switchToNewAddress"
                                    style="background:none;border:none;cursor:pointer;font-size:11px;font-weight:600;color:#C9A96E;letter-spacing:0.06em;">
                                + Add New Address
                            </button>
                        </div>

                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;" class="saved-addrs-grid">
                            @foreach($savedAddresses as $addr)
                                <div wire:click="selectSavedAddress({{ $addr->id }})"
                                     style="cursor:pointer;padding:16px;background:{{ !$useNewAddress && $selectedAddressId == $addr->id ? '#F7F4EF' : '#FFFFFF' }};border:2px solid {{ !$useNewAddress && $selectedAddressId == $addr->id ? '#0D0D0D' : '#E8E3DC' }};display:flex;flex-direction:column;justify-content:space-between;gap:10px;">
                                    <div>
                                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                                            <span style="font-size:13px;font-weight:700;color:#0D0D0D;">{{ $addr->first_name }} {{ $addr->last_name }}</span>
                                            @if($addr->is_default)
                                                <span style="font-size:9px;font-weight:700;background:#0D0D0D;color:#FFFFFF;padding:2px 6px;">DEFAULT</span>
                                            @endif
                                        </div>
                                        <p style="font-size:12px;color:#555;margin:0 0 4px;line-height:1.5;">
                                            {{ $addr->address_line_1 }}{{ $addr->address_line_2 ? ', ' . $addr->address_line_2 : '' }}
                                        </p>
                                        <p style="font-size:11px;color:#888;margin:0;">
                                            {{ $addr->city }}{{ $addr->state ? ', ' . $addr->state : '' }} {{ $addr->postal_code }}
                                        </p>
                                        <p style="font-size:11px;color:#888;margin:2px 0 0;font-family:monospace;">
                                            {{ $addr->phone }}
                                        </p>
                                    </div>
                                    <div style="font-size:11px;font-weight:700;color:{{ !$useNewAddress && $selectedAddressId == $addr->id ? '#0D0D0D' : '#999' }};border-top:1px solid #E8E3DC;padding-top:8px;">
                                        {{ !$useNewAddress && $selectedAddressId == $addr->id ? '✓ Selected Address' : 'Use this address' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Shipping Information Form --}}
                <div x-data="{ open: @entangle('useNewAddress') }"
                     x-show="open || {{ $savedAddresses->isEmpty() ? 'true' : 'false' }}"
                     style="padding:28px;background:#FFFFFF;border:1px solid #E8E3DC;display:flex;flex-direction:column;gap:20px;">

                    <h2 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:#0D0D0D;margin:0;padding-bottom:12px;border-bottom:1px solid #E8E3DC;">
                        Shipping Details
                    </h2>

                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;" class="checkout-form-grid">
                        
                        {{-- Email --}}
                        <div style="grid-column:span 2;" class="form-col-full">
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                                Email Address <span style="color:#C9A96E;">*</span>
                            </label>
                            <input type="email"
                                   wire:model="email"
                                   placeholder="your.email@example.com"
                                   style="width:100%;padding:12px 14px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:13px;color:#0D0D0D;outline:none;transition:border-color 0.2s;"
                                   onfocus="this.style.borderColor='#0D0D0D'"
                                   onblur="this.style.borderColor='#E8E3DC'">
                            @error('email') <span style="font-size:11px;font-weight:600;color:#E53E3E;margin-top:4px;display:block;">{{ $message }}</span> @enderror
                        </div>

                        {{-- First Name --}}
                        <div>
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                                First Name <span style="color:#C9A96E;">*</span>
                            </label>
                            <input type="text"
                                   wire:model="first_name"
                                   placeholder="First Name"
                                   style="width:100%;padding:12px 14px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:13px;color:#0D0D0D;outline:none;transition:border-color 0.2s;"
                                   onfocus="this.style.borderColor='#0D0D0D'"
                                   onblur="this.style.borderColor='#E8E3DC'">
                            @error('first_name') <span style="font-size:11px;font-weight:600;color:#E53E3E;margin-top:4px;display:block;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Last Name --}}
                        <div>
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                                Last Name
                            </label>
                            <input type="text"
                                   wire:model="last_name"
                                   placeholder="Last Name"
                                   style="width:100%;padding:12px 14px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:13px;color:#0D0D0D;outline:none;transition:border-color 0.2s;"
                                   onfocus="this.style.borderColor='#0D0D0D'"
                                   onblur="this.style.borderColor='#E8E3DC'">
                        </div>

                        {{-- Phone --}}
                        <div style="grid-column:span 2;" class="form-col-full">
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                                Phone Number (WhatsApp preferred) <span style="color:#C9A96E;">*</span>
                            </label>
                            <input type="text"
                                   wire:model="phone"
                                   placeholder="03249171213"
                                   style="width:100%;padding:12px 14px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:13px;color:#0D0D0D;outline:none;transition:border-color 0.2s;"
                                   onfocus="this.style.borderColor='#0D0D0D'"
                                   onblur="this.style.borderColor='#E8E3DC'">
                            @error('phone') <span style="font-size:11px;font-weight:600;color:#E53E3E;margin-top:4px;display:block;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Address Line 1 --}}
                        <div style="grid-column:span 2;" class="form-col-full">
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                                Street Address <span style="color:#C9A96E;">*</span>
                            </label>
                            <input type="text"
                                   wire:model="address_line_1"
                                   placeholder="House / Apartment number, street name"
                                   style="width:100%;padding:12px 14px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:13px;color:#0D0D0D;outline:none;transition:border-color 0.2s;"
                                   onfocus="this.style.borderColor='#0D0D0D'"
                                   onblur="this.style.borderColor='#E8E3DC'">
                            @error('address_line_1') <span style="font-size:11px;font-weight:600;color:#E53E3E;margin-top:4px;display:block;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Address Line 2 --}}
                        <div style="grid-column:span 2;" class="form-col-full">
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                                Apartment, Suite, Landmark (Optional)
                            </label>
                            <input type="text"
                                   wire:model="address_line_2"
                                   placeholder="Near main plaza, sector..."
                                   style="width:100%;padding:12px 14px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:13px;color:#0D0D0D;outline:none;transition:border-color 0.2s;"
                                   onfocus="this.style.borderColor='#0D0D0D'"
                                   onblur="this.style.borderColor='#E8E3DC'">
                        </div>

                        {{-- City --}}
                        <div>
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                                City <span style="color:#C9A96E;">*</span>
                            </label>
                            <input type="text"
                                   wire:model="city"
                                   placeholder="Lahore, Karachi, Islamabad..."
                                   style="width:100%;padding:12px 14px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:13px;color:#0D0D0D;outline:none;transition:border-color 0.2s;"
                                   onfocus="this.style.borderColor='#0D0D0D'"
                                   onblur="this.style.borderColor='#E8E3DC'">
                            @error('city') <span style="font-size:11px;font-weight:600;color:#E53E3E;margin-top:4px;display:block;">{{ $message }}</span> @enderror
                        </div>

                        {{-- State --}}
                        <div>
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                                State / Province
                            </label>
                            <input type="text"
                                   wire:model="state"
                                   placeholder="Punjab, Sindh, etc."
                                   style="width:100%;padding:12px 14px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:13px;color:#0D0D0D;outline:none;transition:border-color 0.2s;"
                                   onfocus="this.style.borderColor='#0D0D0D'"
                                   onblur="this.style.borderColor='#E8E3DC'">
                        </div>

                        {{-- Postal Code --}}
                        <div>
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                                Postal Code
                            </label>
                            <input type="text"
                                   wire:model="postal_code"
                                   placeholder="54000"
                                   style="width:100%;padding:12px 14px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:13px;color:#0D0D0D;outline:none;transition:border-color 0.2s;"
                                   onfocus="this.style.borderColor='#0D0D0D'"
                                   onblur="this.style.borderColor='#E8E3DC'">
                        </div>

                        {{-- Country --}}
                        <div>
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                                Country
                            </label>
                            <input type="text"
                                   wire:model="country"
                                   readonly
                                   style="width:100%;padding:12px 14px;background:#EDE9E1;border:1px solid #E8E3DC;font-size:13px;color:#666;cursor:not-allowed;">
                        </div>

                    </div>

                    <div style="padding-top:8px;">
                        <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;font-size:12px;color:#0D0D0D;font-weight:500;">
                            <input type="checkbox" wire:model="save_address" style="width:15px;height:15px;accent-color:#0D0D0D;">
                            <span>Save this address to my account for future orders</span>
                        </label>
                    </div>
                </div>

                {{-- Payment Method Selection --}}
                <div style="padding:28px;background:#FFFFFF;border:1px solid #E8E3DC;display:flex;flex-direction:column;gap:20px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;padding-bottom:12px;border-bottom:1px solid #E8E3DC;flex-wrap:wrap;gap:8px;">
                        <div>
                            <h2 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:#0D0D0D;margin:0;">
                                Payment Method
                            </h2>
                            <p style="font-size:12px;color:#888;margin:2px 0 0;">Choose how you would like to pay for your order</p>
                        </div>
                        <span style="font-size:10px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#C9A96E;background:#F7F4EF;padding:4px 10px;border:1px solid #E8E3DC;">
                            🔒 100% SECURE CHECKOUT
                        </span>
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;" class="payment-methods-grid">
                        @foreach($paymentMethods as $methodKey => $method)
                            @php $isMethodSelected = $payment_method === $methodKey; @endphp
                            <label style="padding:16px;background:{{ $isMethodSelected ? '#F7F4EF' : '#FFFFFF' }};border:2px solid {{ $isMethodSelected ? '#0D0D0D' : '#E8E3DC' }};cursor:pointer;display:flex;flex-direction:column;justify-content:space-between;gap:10px;transition:all 0.2s;">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <input type="radio"
                                           wire:model.live="payment_method"
                                           value="{{ $methodKey }}"
                                           style="accent-color:#0D0D0D;width:16px;height:16px;">
                                    <span style="font-size:13px;font-weight:700;color:#0D0D0D;">{{ $method['name'] }}</span>
                                </div>
                                <span style="font-size:9px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C9A96E;background:#FFFFFF;padding:3px 8px;border:1px solid #E8E3DC;width:fit-content;">
                                    {{ $method['badge'] }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Selected Method Notice --}}
                    <div>
                        @if(in_array($payment_method, ['jazzcash', 'easypaisa', 'bank_transfer']))
                            <div style="padding:16px;background:#0D0D0D;color:#FFFFFF;font-size:12px;line-height:1.7;">
                                <div style="color:#C9A96E;font-weight:700;margin-bottom:4px;letter-spacing:0.06em;text-transform:uppercase;">⚡ Advance Digital Payment</div>
                                <p style="color:rgba(255,255,255,0.7);margin:0;">
                                    After clicking <strong>Place Order</strong> below, you will receive our official receiving account details and TRX ID submission form.
                                </p>
                            </div>
                        @elseif($payment_method === 'card')
                            <div style="padding:16px;background:#0D0D0D;color:#FFFFFF;font-size:12px;line-height:1.7;">
                                <div style="color:#C9A96E;font-weight:700;margin-bottom:4px;letter-spacing:0.06em;text-transform:uppercase;">💳 Instant Debit/Credit Card</div>
                                <p style="color:rgba(255,255,255,0.7);margin:0;">
                                    Pay securely using your Visa, Mastercard, or UnionPay debit/credit card with instant automated verification.
                                </p>
                            </div>
                        @elseif($payment_method === 'cod')
                            <div style="padding:16px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:12px;color:#0D0D0D;line-height:1.7;">
                                <div style="font-weight:700;margin-bottom:2px;letter-spacing:0.06em;text-transform:uppercase;">Cash on Delivery (COD)</div>
                                <p style="color:#666;margin:0;">Pay cash directly to the courier agent upon receiving your package.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Order Notes --}}
                <div style="padding:24px;background:#FFFFFF;border:1px solid #E8E3DC;">
                    <h2 style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:#0D0D0D;margin:0 0 12px;">
                        Order Notes (Optional)
                    </h2>
                    <textarea wire:model="customer_notes"
                              rows="3"
                              placeholder="Any special instructions for delivery or gift packaging..."
                              style="width:100%;padding:12px 14px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:13px;color:#0D0D0D;outline:none;transition:border-color 0.2s;"
                              onfocus="this.style.borderColor='#0D0D0D'"
                              onblur="this.style.borderColor='#E8E3DC'"></textarea>
                </div>

            </div>

            {{-- ══ RIGHT COLUMN: Order Summary ══ --}}
            <div style="padding:28px;background:#F7F4EF;border:1px solid #E8E3DC;position:sticky;top:24px;" class="checkout-summary-box">
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:#0D0D0D;margin:0 0 20px;padding-bottom:12px;border-bottom:1px solid #E8E3DC;">
                    Order Summary
                </h2>

                {{-- Items List --}}
                <div style="display:flex;flex-direction:column;gap:14px;max-height:280px;overflow-y:auto;padding-right:4px;margin-bottom:20px;">
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

                        <div style="display:flex;align-items:center;gap:12px;padding-bottom:10px;border-bottom:1px solid #E8E3DC;">
                            <div style="width:48px;height:60px;background:#FFFFFF;border:1px solid #E8E3DC;overflow:hidden;flex-shrink:0;">
                                @if($hasImg)
                                    <img src="{{ $imgUrl }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#C9A96E;font-size:9px;">AH Kids</div>
                                @endif
                            </div>
                            <div style="flex:1;min-width:0;">
                                <h4 style="font-size:12px;font-weight:600;color:#0D0D0D;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $product ? $product->name : 'Item' }}
                                </h4>
                                <div style="font-size:10px;color:#888;margin-top:2px;">
                                    Qty: {{ $item->quantity }}
                                    @if($variant && $variant->size) • {{ $variant->size->name }} @endif
                                    @if($variant && $variant->color) • {{ $variant->color->name }} @endif
                                </div>
                            </div>
                            <div style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:700;color:#0D0D0D;">
                                Rs. {{ number_format($lineTotal, 0) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Promo Coupon --}}
                <div style="padding-top:16px;border-top:1px solid #E8E3DC;margin-bottom:20px;">
                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:8px;">
                        Have a Promo Coupon?
                    </label>
                    @if($appliedCoupon)
                        <div style="padding:10px 14px;background:#F0FFF4;border:1px solid #C6F6D5;color:#276749;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:space-between;">
                            <span>Coupon '{{ $appliedCoupon['code'] }}' Applied</span>
                            <button type="button" wire:click="removeCoupon" style="background:none;border:none;cursor:pointer;color:#E53E3E;">Remove</button>
                        </div>
                    @else
                        <div style="display:flex;gap:8px;">
                            <input type="text"
                                   wire:model="coupon_code"
                                   placeholder="Enter coupon code"
                                   style="flex:1;padding:10px 12px;background:#FFFFFF;border:1px solid #E8E3DC;font-size:12px;color:#0D0D0D;text-transform:uppercase;outline:none;">
                            <button type="button"
                                    wire:click="applyCoupon"
                                    style="padding:10px 18px;background:#0D0D0D;color:#FFFFFF;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;border:none;cursor:pointer;transition:background 0.2s;"
                                    onmouseover="this.style.background='#C9A96E'"
                                    onmouseout="this.style.background='#0D0D0D'">
                                APPLY
                            </button>
                        </div>
                    @endif

                    @if($couponMessage)
                        <p style="font-size:11px;font-weight:600;color:{{ $couponMessageType === 'success' ? '#276749' : '#C53030' }};margin:6px 0 0;">
                            {{ $couponMessage }}
                        </p>
                    @endif
                </div>

                {{-- Breakdown --}}
                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;font-size:12px;">
                    <div style="display:flex;justify-content:space-between;color:#666;">
                        <span>Subtotal</span>
                        <span style="font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:700;color:#0D0D0D;">
                            Rs. {{ number_format($subtotal, 0) }}
                        </span>
                    </div>

                    @if($discount > 0)
                        <div style="display:flex;justify-content:space-between;color:#276749;font-weight:700;">
                            <span>Discount</span>
                            <span>- Rs. {{ number_format($discount, 0) }}</span>
                        </div>
                    @endif

                    <div style="display:flex;justify-content:space-between;color:#666;">
                        <span>Shipping</span>
                        <span style="font-weight:600;color:#0D0D0D;">
                            Rs. {{ number_format($shippingCost, 0) }}
                        </span>
                    </div>
                </div>

                {{-- Total Payable --}}
                <div style="padding-top:14px;border-top:1px solid #E8E3DC;margin-bottom:24px;display:flex;justify-content:space-between;align-items:baseline;">
                    <span style="font-size:13px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;">TOTAL PAYABLE</span>
                    <span style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:#0D0D0D;">
                        Rs. {{ number_format($total, 0) }}
                    </span>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                        wire:loading.attr="disabled"
                        style="width:100%;padding:16px;background:#0D0D0D;color:#FFFFFF;font-size:11px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;border:none;cursor:pointer;transition:background 0.25s;"
                        onmouseover="this.style.background='#C9A96E'"
                        onmouseout="this.style.background='#0D0D0D'">
                    <span wire:loading.remove wire:target="placeOrder">
                        {{ $payment_method === 'cod' ? 'PLACE ORDER (Rs. ' . number_format($total, 0) . ')' : 'PLACE ORDER & PROCEED (Rs. ' . number_format($total, 0) . ')' }}
                    </span>
                    <span wire:loading wire:target="placeOrder">PROCESSING ORDER...</span>
                </button>

            </div>

        </div>
    </form>
</div>

<style>
/* ── CHECKOUT PAGE RESPONSIVE ── */
.checkout-grid-wrap {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 36px;
    align-items: start;
}
@media (max-width: 992px) {
    .checkout-grid-wrap {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    .checkout-summary-box {
        position: static !important;
        top: auto !important;
    }
}
@media (max-width: 600px) {
    .saved-addrs-grid,
    .payment-methods-grid,
    .checkout-form-grid {
        grid-template-columns: 1fr !important;
    }
    .form-col-full {
        grid-column: span 1 !important;
    }
}
</style>
