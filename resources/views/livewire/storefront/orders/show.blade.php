<div class="os-wrap">

    {{-- ══════════════════════════════════════════
         SUCCESS BANNER
    ══════════════════════════════════════════ --}}
    <div class="os-success-banner">
        <div class="os-check">
            <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="os-banner-text">
            <div class="os-badge-green">Order Placed Successfully</div>
            <h1 class="os-banner-title">Thank You for Your Order!</h1>
            <p class="os-banner-sub">
                Order <strong style="color:#C9A96E;">#{{ $order->order_number }}</strong> has been confirmed.
                @if(in_array($order->payment_method, ['jazzcash','easypaisa','bank_transfer']) && $order->payment_status !== 'paid')
                    Please complete payment using the account details below.
                @endif
            </p>
        </div>
    </div>

    {{-- Flash Success Message --}}
    @if($flashMessage)
        <div class="os-flash">
            <div style="display:flex;align-items:center;gap:8px;">
                <svg width="16" height="16" fill="none" stroke="#276749" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ $flashMessage }}</span>
            </div>
            <button wire:click="$set('flashMessage','')" class="os-flash-close">&times;</button>
        </div>
    @endif

    {{-- ══════════════════════════════════════════
         STEP STATUS PILLS
    ══════════════════════════════════════════ --}}
    <div class="os-steps">
        <div class="os-step os-step-done">
            <div class="os-step-num">✓</div>
            <div class="os-step-label">Order Placed</div>
        </div>
        <div class="os-step-line"></div>
        @if(in_array($order->payment_method, ['jazzcash','easypaisa','bank_transfer']))
            <div class="os-step {{ $order->payment_status === 'paid' ? 'os-step-done' : 'os-step-active' }}">
                <div class="os-step-num">2</div>
                <div class="os-step-label">
                    {{ $order->payment_status === 'paid' ? '✓ Payment Verified' : 'Complete Payment' }}
                </div>
            </div>
            <div class="os-step-line"></div>
        @endif
        <div class="os-step {{ $order->order_status === 'processing' || $order->order_status === 'shipped' || $order->order_status === 'completed' ? 'os-step-done' : 'os-step-wait' }}">
            <div class="os-step-num">3</div>
            <div class="os-step-label">Processing</div>
        </div>
        <div class="os-step-line"></div>
        <div class="os-step {{ $order->order_status === 'shipped' || $order->order_status === 'completed' ? 'os-step-done' : 'os-step-wait' }}">
            <div class="os-step-num">4</div>
            <div class="os-step-label">Shipped</div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         ADVANCE PAYMENT SECTION
    ══════════════════════════════════════════ --}}
    @if(in_array($order->payment_method, ['jazzcash','easypaisa','bank_transfer']) && $order->payment_status !== 'paid')
        <div class="os-card" x-data="{ copied: '' }">

            {{-- Payment Header --}}
            <div class="os-card-header" style="background:#0D0D0D;">
                <div>
                    <div style="font-size:9px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:6px;">STEP 2 — COMPLETE PAYMENT</div>
                    <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:#FFFFFF;line-height:1.2;">
                        Transfer to Any Account Below
                    </div>
                    <div style="font-size:12px;color:rgba(255,255,255,0.55);margin-top:4px;">
                        Use your Banking / EasyPaisa / JazzCash app
                    </div>
                </div>
                <div class="os-total-badge">
                    <div style="font-size:9px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.5);margin-bottom:4px;">TOTAL DUE</div>
                    <div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:#C9A96E;line-height:1;">
                        Rs. {{ number_format($order->total, 0) }}
                    </div>
                </div>
            </div>

            {{-- Account Cards --}}
            <div class="os-accounts-grid">

                {{-- Askari Bank --}}
                <div class="os-account-card">
                    <div class="os-account-badge" style="background:#1a3a6b;color:#90cdf4;">🏦 Askari Bank</div>
                    <div class="os-account-row">
                        <span class="os-account-key">Account Title</span>
                        <span class="os-account-val">Al Hayat Garments</span>
                    </div>
                    <div class="os-account-row">
                        <span class="os-account-key">Account No.</span>
                        <div class="os-copy-row">
                            <span class="os-mono">09450200002583</span>
                            <button @click="navigator.clipboard.writeText('09450200002583'); copied='bank'" class="os-copy-btn" :class="copied==='bank' ? 'os-copy-done' : ''">
                                <span x-text="copied==='bank' ? '✓ Copied' : 'Copy'"></span>
                            </button>
                        </div>
                    </div>
                    <div class="os-account-row">
                        <span class="os-account-key">Bank</span>
                        <span class="os-account-val">Askari Commercial Bank</span>
                    </div>
                </div>

                {{-- EasyPaisa --}}
                <div class="os-account-card">
                    <div class="os-account-badge" style="background:#1a4731;color:#68d391;">📱 EasyPaisa</div>
                    <div class="os-account-row">
                        <span class="os-account-key">Account Title</span>
                        <span class="os-account-val">Khizer Hayat</span>
                    </div>
                    <div class="os-account-row">
                        <span class="os-account-key">Mobile No.</span>
                        <div class="os-copy-row">
                            <span class="os-mono">03150132001</span>
                            <button @click="navigator.clipboard.writeText('03150132001'); copied='ep'" class="os-copy-btn" :class="copied==='ep' ? 'os-copy-done' : ''">
                                <span x-text="copied==='ep' ? '✓ Copied' : 'Copy'"></span>
                            </button>
                        </div>
                    </div>
                    <div class="os-account-row">
                        <span class="os-account-key">Type</span>
                        <span class="os-account-val">EasyPaisa Wallet</span>
                    </div>
                </div>

                {{-- JazzCash --}}
                <div class="os-account-card">
                    <div class="os-account-badge" style="background:#4a1a00;color:#fbd38d;">📲 JazzCash</div>
                    <div class="os-account-row">
                        <span class="os-account-key">Account Title</span>
                        <span class="os-account-val">Khizer Hayat</span>
                    </div>
                    <div class="os-account-row">
                        <span class="os-account-key">Mobile No.</span>
                        <div class="os-copy-row">
                            <span class="os-mono">03249171213</span>
                            <button @click="navigator.clipboard.writeText('03249171213'); copied='jc'" class="os-copy-btn" :class="copied==='jc' ? 'os-copy-done' : ''">
                                <span x-text="copied==='jc' ? '✓ Copied' : 'Copy'"></span>
                            </button>
                        </div>
                    </div>
                    <div class="os-account-row">
                        <span class="os-account-key">Type</span>
                        <span class="os-account-val">JazzCash Wallet</span>
                    </div>
                </div>
            </div>

            <div class="os-tip">
                💡 Transfer exactly <strong>Rs. {{ number_format($order->total, 0) }}</strong> to any account above, then submit your TRX ID or screenshot below. Our team will verify within 1–2 hours.
            </div>

            {{-- Payment Proof Form --}}
            <form wire:submit.prevent="submitPaymentReference" class="os-proof-form">
                <div class="os-proof-header">
                    <div>
                        <div style="font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#0D0D0D;margin-bottom:4px;">Submit Payment Proof</div>
                        <p style="font-size:12px;color:#888;margin:0;">Enter your TRX ID or upload payment screenshot</p>
                    </div>
                    <a href="https://api.whatsapp.com/send?phone=923249171213&text={{ urlencode('Hi Al Hayat Kids! Order #' . $order->order_number . ' — Rs. ' . number_format($order->total,0) . ' transferred. Please find my receipt attached.') }}"
                       target="_blank" rel="noopener" class="os-wa-btn">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Send via WhatsApp
                    </a>
                </div>

                <div class="os-form-fields">

                    {{-- TRX ID --}}
                    <div class="os-field-group os-field-full">
                        <label class="os-label">Transaction ID / TRX Reference <span style="color:#C9A96E;">*</span></label>
                        <input type="text"
                               wire:model="reference_number"
                               placeholder="e.g. 012345678987654"
                               class="os-input os-mono-input">
                        @error('reference_number')
                            <span class="os-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Screenshot --}}
                    <div class="os-field-group os-field-full">
                        <label class="os-label">Upload Receipt / Screenshot (PNG, JPG)</label>
                        <input type="file"
                               wire:model="payment_proof_file"
                               accept="image/*"
                               class="os-file-input">
                        @error('payment_proof_file')
                            <span class="os-error">{{ $message }}</span>
                        @enderror
                        @if($payment_proof_file)
                            <div class="os-preview">
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#276749;margin-bottom:6px;">Preview:</div>
                                <img src="{{ $payment_proof_file->temporaryUrl() }}" alt="Receipt" class="os-preview-img">
                            </div>
                        @endif
                    </div>

                    {{-- Sender + Notes --}}
                    <div class="os-field-group">
                        <label class="os-label">Sender Account Name</label>
                        <input type="text" wire:model="sender_name" placeholder="Account holder name" class="os-input">
                    </div>
                    <div class="os-field-group">
                        <label class="os-label">Notes (Optional)</label>
                        <input type="text" wire:model="payment_notes" placeholder="Any additional info..." class="os-input">
                    </div>
                </div>

                <div>
                    <button type="submit" wire:loading.attr="disabled" class="os-submit-btn">
                        <span wire:loading.remove wire:target="submitPaymentReference">
                            ✓ Submit Payment Confirmation
                        </span>
                        <span wire:loading wire:target="submitPaymentReference">
                            Uploading &amp; Submitting…
                        </span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- ══════════════════════════════════════════
         ORDER DETAILS CARD
    ══════════════════════════════════════════ --}}
    <div class="os-card">

        {{-- Order Header --}}
        <div class="os-order-header">
            <div>
                <div class="os-section-label">Order Reference</div>
                <div class="os-order-num">#{{ $order->order_number }}</div>
                <div class="os-order-date">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</div>
            </div>
            <div class="os-status-row">
                <div class="os-status-item">
                    <div class="os-section-label">Order Status</div>
                    <span class="os-status-pill os-status-{{ $order->order_status }}">
                        {{ ucfirst(str_replace('_',' ',$order->order_status)) }}
                    </span>
                </div>
                <div class="os-status-item">
                    <div class="os-section-label">Payment</div>
                    @if($order->payment_status === 'paid')
                        <span class="os-status-pill" style="background:#48BB78;color:#fff;">✓ Paid</span>
                    @elseif($order->payment_status === 'pending_verification')
                        <span class="os-status-pill" style="background:#ECC94B;color:#744210;">⏳ Verifying</span>
                    @else
                        <span class="os-status-pill" style="background:#F7F4EF;color:#666;border:1px solid #E8E3DC;">
                            {{ strtoupper($order->payment_method) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Items List --}}
        <div style="padding:0 24px;">
            <div class="os-section-label" style="padding:16px 0 12px;">Items Ordered</div>
            @foreach($order->items as $item)
                <div class="os-item-row">
                    <div class="os-item-img">
                        @if($item->product && $item->product->primaryImage && !empty($item->product->primaryImage->url))
                            <img src="{{ $item->product->primaryImage->url }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:#C9A96E;">AHK</div>
                        @endif
                    </div>
                    <div class="os-item-info">
                        <div class="os-item-name">{{ $item->product_name }}</div>
                        <div class="os-item-tags">
                            @if($item->sku)
                                <span class="os-tag">SKU: {{ $item->sku }}</span>
                            @endif
                            @if($item->size)
                                <span class="os-tag os-tag-red">Size: {{ $item->size }}</span>
                            @endif
                            @if($item->color)
                                <span class="os-tag">{{ $item->color }}</span>
                            @endif
                            <span class="os-tag">Qty: {{ $item->quantity }}</span>
                        </div>
                        <div style="font-size:11px;color:#888;">Rs. {{ number_format($item->unit_price, 0) }} × {{ $item->quantity }}</div>
                    </div>
                    <div class="os-item-total">Rs. {{ number_format($item->total, 0) }}</div>
                </div>
            @endforeach
        </div>

        {{-- Summary Footer --}}
        <div class="os-summary-grid">

            {{-- Address --}}
            <div class="os-summary-col">
                <div class="os-section-label" style="margin-bottom:12px;">Delivery Address</div>
                @if($shippingAddress)
                    <div class="os-address-block">
                        <div class="os-address-name">{{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}</div>
                        <div class="os-address-line">{{ $shippingAddress->address_line_1 }}</div>
                        @if($shippingAddress->address_line_2)
                            <div class="os-address-line">{{ $shippingAddress->address_line_2 }}</div>
                        @endif
                        <div class="os-address-line">{{ $shippingAddress->city }}{{ $shippingAddress->state ? ', '.$shippingAddress->state : '' }}</div>
                        <div class="os-address-phone">📞 {{ $shippingAddress->phone }}</div>
                    </div>
                @else
                    <div class="os-address-block">
                        <div class="os-address-line">Standard Home Delivery across Pakistan</div>
                        <div class="os-address-line">Delivery via TCS / Leopards Courier</div>
                    </div>
                @endif

                @if($order->payment && ($order->payment->reference_number || $order->payment->sender_name))
                    <div class="os-section-label" style="margin:16px 0 10px;">Payment Reference</div>
                    <div class="os-ref-block">
                        <div class="os-ref-row">
                            <span>Method</span>
                            <strong>{{ strtoupper($order->payment->method) }}</strong>
                        </div>
                        @if($order->payment->reference_number)
                            <div class="os-ref-row">
                                <span>TRX ID</span>
                                <strong class="os-mono" style="font-size:12px;">{{ $order->payment->reference_number }}</strong>
                            </div>
                        @endif
                        @if($order->payment->sender_name)
                            <div class="os-ref-row">
                                <span>Sender</span>
                                <strong>{{ $order->payment->sender_name }}</strong>
                            </div>
                        @endif
                        <div class="os-ref-row">
                            <span>Status</span>
                            <strong style="color:{{ $order->payment->status === 'paid' ? '#276749' : '#B7791F' }}">
                                {{ $order->payment->status === 'paid' ? '✓ Verified' : 'Pending' }}
                            </strong>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Price Breakdown --}}
            <div class="os-summary-col os-summary-right">
                <div class="os-section-label" style="margin-bottom:12px;">Price Breakdown</div>
                <div class="os-price-table">
                    <div class="os-price-row">
                        <span>Subtotal</span>
                        <span>Rs. {{ number_format($order->subtotal, 0) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="os-price-row" style="color:#276749;">
                            <span>Discount</span>
                            <span>− Rs. {{ number_format($order->discount, 0) }}</span>
                        </div>
                    @endif
                    <div class="os-price-row">
                        <span>Shipping</span>
                        <span>Rs. {{ number_format($order->shipping_cost, 0) }}</span>
                    </div>
                    <div class="os-price-total">
                        <span>Total</span>
                        <span>Rs. {{ number_format($order->total, 0) }}</span>
                    </div>
                </div>

                @if($order->customer_notes)
                    <div style="margin-top:16px;padding-top:14px;border-top:1px solid #E8E3DC;">
                        <div class="os-section-label" style="margin-bottom:6px;">Order Notes</div>
                        <p style="font-size:12px;color:#555;line-height:1.6;margin:0;">{{ $order->customer_notes }}</p>
                    </div>
                @endif

                {{-- WhatsApp CTA --}}
                <a href="https://wa.me/923249171213?text={{ urlencode('Hi! Checking status of my order #' . $order->order_number) }}"
                   target="_blank" rel="noopener"
                   class="os-wa-secondary">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp Order Support
                </a>
            </div>
        </div>
    </div>

    {{-- ── Bottom CTA ── --}}
    <div class="os-bottom-row">
        <a href="{{ route('shop') }}" class="os-link-back">← Continue Shopping</a>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('dashboard') }}" class="os-btn-outline">My Orders</a>
            <a href="{{ route('home') }}" class="os-btn-solid">Back to Home</a>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════
     ORDER SHOW — STYLES
══════════════════════════════════════════ --}}
<style>
/* ── WRAPPER ── */
.os-wrap {
    max-width: 860px;
    margin: 0 auto;
    padding: 36px 20px 80px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* ── SUCCESS BANNER ── */
.os-success-banner {
    background: #0D0D0D;
    padding: 28px 32px;
    display: flex;
    align-items: flex-start;
    gap: 20px;
}
.os-check {
    width: 48px;
    height: 48px;
    min-width: 48px;
    background: #48BB78;
    display: flex;
    align-items: center;
    justify-content: center;
}
.os-badge-green {
    display: inline-block;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #48BB78;
    background: rgba(72,187,120,0.12);
    border: 1px solid rgba(72,187,120,0.3);
    padding: 3px 10px;
    margin-bottom: 10px;
}
.os-banner-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(20px, 3vw, 28px);
    font-weight: 600;
    color: #FFFFFF;
    margin: 0 0 8px;
    line-height: 1.2;
}
.os-banner-sub {
    font-size: 12px;
    color: rgba(255,255,255,0.6);
    margin: 0;
    line-height: 1.6;
}

/* ── FLASH ── */
.os-flash {
    padding: 14px 20px;
    background: #F0FFF4;
    border: 1px solid #C6F6D5;
    color: #276749;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.os-flash-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #888;
    font-size: 18px;
    line-height: 1;
}

/* ── STEPS ── */
.os-steps {
    display: flex;
    align-items: center;
    gap: 0;
    padding: 20px 24px;
    background: #FFFFFF;
    border: 1px solid #E8E3DC;
}
.os-step {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}
.os-step-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
}
.os-step-label {
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}
.os-step-done .os-step-num { background: #48BB78; color: #fff; }
.os-step-done .os-step-label { color: #276749; }
.os-step-active .os-step-num { background: #C9A96E; color: #fff; }
.os-step-active .os-step-label { color: #C9A96E; font-weight: 700; }
.os-step-wait .os-step-num { background: #F7F4EF; color: #AAA; border: 1px solid #E8E3DC; }
.os-step-wait .os-step-label { color: #AAA; }
.os-step-line {
    flex: 1;
    height: 1px;
    background: #E8E3DC;
    min-width: 12px;
}

/* ── CARD ── */
.os-card {
    background: #FFFFFF;
    border: 1px solid #E8E3DC;
    overflow: hidden;
}
.os-card-header {
    padding: 24px 28px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}
.os-total-badge {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    padding: 12px 20px;
    text-align: right;
    flex-shrink: 0;
}

/* ── ACCOUNT CARDS ── */
.os-accounts-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    padding: 0 24px 24px;
    background: #0D0D0D;
}
.os-account-card {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: border-color 0.2s;
}
.os-account-card:hover { border-color: rgba(201,169,110,0.4); }
.os-account-badge {
    display: inline-block;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    padding: 4px 10px;
    border-radius: 2px;
    width: fit-content;
}
.os-account-row {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.os-account-key {
    font-size: 9px;
    color: rgba(255,255,255,0.35);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-weight: 600;
}
.os-account-val {
    font-size: 12px;
    font-weight: 600;
    color: rgba(255,255,255,0.85);
}
.os-mono { font-family: monospace; font-weight: 700; }
.os-copy-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.os-copy-row .os-mono {
    font-size: 13px;
    color: #FFFFFF;
    letter-spacing: 0.04em;
}
.os-copy-btn {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    background: #C9A96E;
    color: #FFFFFF;
    border: none;
    cursor: pointer;
    padding: 5px 10px;
    transition: background 0.2s;
    flex-shrink: 0;
}
.os-copy-btn:hover { background: #a07830; }
.os-copy-done { background: #48BB78 !important; }
.os-tip {
    background: rgba(255,255,255,0.04);
    border-top: 1px solid rgba(255,255,255,0.08);
    padding: 14px 24px;
    font-size: 12px;
    color: rgba(255,255,255,0.6);
    line-height: 1.7;
}
.os-tip strong { color: #C9A96E; }

/* ── PROOF FORM ── */
.os-proof-form {
    padding: 24px 28px;
    border-top: 1px solid #E8E3DC;
    display: flex;
    flex-direction: column;
    gap: 20px;
    background: #FFFFFF;
}
.os-proof-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding-bottom: 16px;
    border-bottom: 1px solid #E8E3DC;
}
.os-wa-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 18px;
    background: #25D366;
    color: #FFFFFF;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    text-decoration: none;
    flex-shrink: 0;
    transition: background 0.2s;
}
.os-wa-btn:hover { background: #128C7E; }
.os-form-fields {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.os-field-full { grid-column: span 2; }
.os-field-group { display: flex; flex-direction: column; gap: 6px; }
.os-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #0D0D0D;
}
.os-input {
    width: 100%;
    padding: 12px 14px;
    background: #F7F4EF;
    border: 1px solid #E8E3DC;
    font-size: 13px;
    color: #0D0D0D;
    outline: none;
    transition: border-color 0.2s;
    font-family: 'Inter', sans-serif;
    box-sizing: border-box;
}
.os-input:focus { border-color: #0D0D0D; background: #FFFFFF; }
.os-mono-input { font-family: monospace; font-weight: 700; letter-spacing: 0.05em; }
.os-file-input {
    width: 100%;
    padding: 10px 0;
    font-size: 12px;
    color: #555;
    cursor: pointer;
    box-sizing: border-box;
}
.os-preview { margin-top: 8px; padding: 8px; background: #F0FFF4; border: 1px solid #C6F6D5; display: inline-block; }
.os-preview-img { height: 90px; max-width: 180px; object-fit: contain; display: block; }
.os-error { font-size: 11px; font-weight: 600; color: #E53E3E; }
.os-submit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 32px;
    background: #0D0D0D;
    color: #FFFFFF;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
}
.os-submit-btn:hover { background: #C9A96E; }
.os-submit-btn:disabled { background: #CCC; cursor: not-allowed; }

/* ── ORDER HEADER ── */
.os-order-header {
    padding: 20px 24px;
    background: #F7F4EF;
    border-bottom: 1px solid #E8E3DC;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}
.os-section-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #888;
}
.os-order-num {
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px;
    font-weight: 700;
    color: #0D0D0D;
    margin: 4px 0 2px;
}
.os-order-date { font-size: 11px; color: #888; }
.os-status-row { display: flex; gap: 16px; align-items: flex-start; flex-wrap: wrap; }
.os-status-item { text-align: right; }
.os-status-pill {
    display: inline-block;
    margin-top: 4px;
    padding: 5px 12px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}
.os-status-pending    { background: #FFFBEB; color: #B7791F; border: 1px solid #FBD38D; }
.os-status-processing { background: #EBF8FF; color: #2B6CB0; border: 1px solid #90CDF4; }
.os-status-shipped    { background: #F0FFF4; color: #276749; border: 1px solid #C6F6D5; }
.os-status-completed  { background: #48BB78; color: #FFFFFF; }
.os-status-cancelled  { background: #FFF5F5; color: #C53030; border: 1px solid #FEB2B2; }

/* ── ITEMS ── */
.os-item-row {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 0;
    border-bottom: 1px solid #F0EDE7;
}
.os-item-row:last-child { border-bottom: none; }
.os-item-img {
    width: 54px;
    height: 68px;
    min-width: 54px;
    background: #F7F4EF;
    border: 1px solid #E8E3DC;
    overflow: hidden;
}
.os-item-info { flex: 1; min-width: 0; }
.os-item-name {
    font-size: 13px;
    font-weight: 600;
    color: #0D0D0D;
    margin-bottom: 6px;
    line-height: 1.3;
}
.os-item-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 4px; }
.os-tag {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    background: #F7F4EF;
    color: #666;
    padding: 2px 7px;
}
.os-tag-red { background: #FFF5F5; color: #C53030; }
.os-item-total {
    font-family: 'Cormorant Garamond', serif;
    font-size: 16px;
    font-weight: 700;
    color: #0D0D0D;
    white-space: nowrap;
    flex-shrink: 0;
}

/* ── SUMMARY GRID ── */
.os-summary-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    border-top: 1px solid #E8E3DC;
}
.os-summary-col { padding: 20px 24px; }
.os-summary-right {
    border-left: 1px solid #E8E3DC;
    background: #F7F4EF;
}
.os-address-block {
    background: #FFFFFF;
    border: 1px solid #E8E3DC;
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.os-address-name { font-size: 14px; font-weight: 700; color: #0D0D0D; margin-bottom: 4px; }
.os-address-line { font-size: 12px; color: #555; line-height: 1.6; }
.os-address-phone { font-size: 11px; color: #888; margin-top: 4px; font-family: monospace; }
.os-ref-block {
    background: #F7F4EF;
    border: 1px solid #E8E3DC;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.os-ref-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    color: #555;
}
.os-price-table { display: flex; flex-direction: column; gap: 10px; }
.os-price-row {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    color: #555;
}
.os-price-row span:last-child { font-weight: 600; color: #0D0D0D; }
.os-price-total {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding-top: 12px;
    border-top: 2px solid #0D0D0D;
    margin-top: 4px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #0D0D0D;
}
.os-price-total span:last-child {
    font-family: 'Cormorant Garamond', serif;
    font-size: 26px;
    font-weight: 700;
    text-transform: none;
    letter-spacing: 0;
}
.os-wa-secondary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin-top: 16px;
    padding: 10px 16px;
    background: #FFFFFF;
    border: 1px solid #E8E3DC;
    color: #555;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    text-decoration: none;
    transition: all 0.2s;
}
.os-wa-secondary:hover { background: #25D366; color: #FFFFFF; border-color: #25D366; }

/* ── BOTTOM ROW ── */
.os-bottom-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.os-link-back {
    font-size: 11px;
    font-weight: 700;
    color: #0D0D0D;
    text-decoration: none;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    border-bottom: 1px solid #0D0D0D;
    padding-bottom: 1px;
    transition: color 0.2s, border-color 0.2s;
}
.os-link-back:hover { color: #C9A96E; border-color: #C9A96E; }
.os-btn-outline {
    display: inline-flex;
    align-items: center;
    padding: 11px 22px;
    background: #F7F4EF;
    color: #0D0D0D;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    text-decoration: none;
    border: 1px solid #E8E3DC;
    transition: background 0.2s;
}
.os-btn-outline:hover { background: #EDE9E1; }
.os-btn-solid {
    display: inline-flex;
    align-items: center;
    padding: 11px 22px;
    background: #0D0D0D;
    color: #FFFFFF;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    text-decoration: none;
    transition: background 0.2s;
}
.os-btn-solid:hover { background: #C9A96E; }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .os-wrap { padding: 16px 14px 60px; gap: 16px; }
    .os-success-banner { padding: 20px 18px; gap: 14px; }
    .os-card-header { padding: 20px 18px; }
    .os-accounts-grid { grid-template-columns: 1fr; gap: 12px; padding: 0 18px 18px; }
    .os-tip { padding: 12px 18px; }
    .os-proof-form { padding: 20px 18px; }
    .os-proof-header { flex-direction: column; }
    .os-wa-btn { width: 100%; justify-content: center; }
    .os-form-fields { grid-template-columns: 1fr; }
    .os-field-full { grid-column: span 1; }
    .os-submit-btn { width: 100%; }
    .os-order-header { padding: 16px 18px; }
    .os-status-row { flex-direction: column; gap: 8px; align-items: flex-start; }
    .os-status-item { text-align: left; }
    .os-card > div[style*="padding:0 24px"] { padding: 0 18px !important; }
    .os-summary-grid { grid-template-columns: 1fr; }
    .os-summary-right { border-left: none; border-top: 1px solid #E8E3DC; }
    .os-summary-col { padding: 16px 18px; }
    .os-steps { padding: 14px 16px; overflow-x: auto; }
    .os-step-label { font-size: 10px; }
    .os-bottom-row { flex-direction: column; }
    .os-bottom-row > div { width: 100%; display: flex; gap: 8px; }
    .os-btn-outline, .os-btn-solid { flex: 1; justify-content: center; }
    .os-link-back { align-self: flex-start; }
    .os-item-row { flex-wrap: wrap; }
}

@media (max-width: 400px) {
    .os-banner-title { font-size: 18px; }
    .os-check { width: 40px; height: 40px; min-width: 40px; }
}
</style>
