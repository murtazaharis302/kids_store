<div style="max-width:900px;margin:0 auto;padding:32px 16px 80px;" class="order-show-container">

    {{-- ── Breadcrumbs ── --}}
    <nav style="display:flex;align-items:center;gap:8px;font-size:11px;font-weight:500;color:#999;letter-spacing:0.04em;margin-bottom:28px;flex-wrap:wrap;">
        <a href="{{ route('home') }}" style="color:#999;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#C9A96E'" onmouseout="this.style.color='#999'">Home</a>
        <span>›</span>
        <a href="{{ route('dashboard') }}" style="color:#999;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#C9A96E'" onmouseout="this.style.color='#999'">My Orders</a>
        <span>›</span>
        <span style="color:#0D0D0D;font-weight:600;">#{{ $order->order_number }}</span>
    </nav>

    {{-- ── Success Banner ── --}}
    <div style="background:#0D0D0D;padding:28px 32px;margin-bottom:28px;display:flex;align-items:flex-start;gap:20px;flex-wrap:wrap;" class="order-success-banner">
        <div style="width:52px;height:52px;background:#48BB78;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="24" height="24" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div style="flex:1;min-width:200px;">
            <div style="display:inline-block;font-size:9px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#48BB78;background:rgba(72,187,120,0.15);padding:3px 10px;border:1px solid rgba(72,187,120,0.3);margin-bottom:8px;">
                Order Placed Successfully
            </div>
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(22px,3vw,30px);font-weight:600;color:#FFFFFF;margin:0 0 6px;line-height:1.2;">
                Thank You for Your Order!
            </h1>
            <p style="font-size:12px;color:rgba(255,255,255,0.65);margin:0;line-height:1.6;">
                Order <strong style="color:#C9A96E;">#{{ $order->order_number }}</strong> has been received.
                @if(in_array($order->payment_method, ['jazzcash', 'easypaisa', 'bank_transfer']) && $order->payment_status !== 'paid')
                    Please complete payment transfer using the account details below.
                @endif
            </p>
        </div>
    </div>

    {{-- Flash Message --}}
    @if($flashMessage)
        <div style="padding:14px 20px;margin-bottom:24px;background:#F0FFF4;border:1px solid #C6F6D5;color:#276749;font-size:12px;font-weight:600;display:flex;align-items:center;justify-content:space-between;gap:12px;">
            <div style="display:flex;align-items:center;gap:8px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ $flashMessage }}</span>
            </div>
            <button type="button" wire:click="$set('flashMessage', '')" style="background:none;border:none;cursor:pointer;color:#888;font-size:16px;">&times;</button>
        </div>
    @endif

    {{-- ══ ADVANCE PAYMENT SECTION ══ --}}
    @if(in_array($order->payment_method, ['jazzcash', 'easypaisa', 'bank_transfer']) && isset($paymentMethods[$order->payment_method]))
        @php $methodInfo = $paymentMethods[$order->payment_method]; @endphp

        <div style="background:#FFFFFF;border:1px solid #E8E3DC;margin-bottom:28px;" x-data="{ copyText(text) { navigator.clipboard.writeText(text).then(() => { alert('Copied: ' + text); }); } }">

            {{-- Payment Header --}}
            <div style="padding:20px 24px;border-bottom:1px solid #E8E3DC;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;background:#F7F4EF;">
                <div>
                    <div style="font-size:9px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:4px;">STEP 2 OF 2</div>
                    <h2 style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:#0D0D0D;margin:0;">
                        Complete Your Payment Transfer
                    </h2>
                </div>
                <div style="background:#0D0D0D;padding:10px 20px;text-align:right;">
                    <div style="font-size:9px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.5);margin-bottom:2px;">TOTAL DUE</div>
                    <div style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:700;color:#C9A96E;">
                        Rs. {{ number_format($order->total, 0) }}
                    </div>
                </div>
            </div>

            {{-- Receiving Accounts --}}
            <div style="padding:24px;background:#0D0D0D;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid rgba(255,255,255,0.1);">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#48BB78;display:inline-block;animation:pulse 2s infinite;"></span>
                        <span style="font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:rgba(255,255,255,0.8);">OFFICIAL RECEIVING ACCOUNTS</span>
                    </div>
                    <span style="font-size:9px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;background:#48BB78;color:#FFFFFF;padding:3px 10px;">0% FEE</span>
                </div>

                <div class="payment-accounts-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">

                    {{-- Askari Bank --}}
                    <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);padding:16px;display:flex;flex-direction:column;gap:10px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <span style="font-size:9px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#C9A96E;">Bank Account</span>
                            <span style="font-size:9px;font-weight:700;background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.7);padding:2px 8px;">Askari Bank</span>
                        </div>
                        <div>
                            <div style="font-size:9px;color:rgba(255,255,255,0.4);margin-bottom:2px;">Account Title</div>
                            <div style="font-size:11px;font-weight:700;color:#48BB78;">Al hayat Garments</div>
                        </div>
                        <div>
                            <div style="font-size:9px;color:rgba(255,255,255,0.4);margin-bottom:4px;">Account Number</div>
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:6px;">
                                <span style="font-family:monospace;font-size:11px;font-weight:700;color:#FFFFFF;letter-spacing:0.05em;">09450200002583</span>
                                <button type="button" @click="copyText('09450200002583')" style="font-size:9px;font-weight:700;background:#48BB78;color:#FFFFFF;border:none;cursor:pointer;padding:4px 8px;letter-spacing:0.06em;text-transform:uppercase;flex-shrink:0;" onmouseover="this.style.background='#38A169'" onmouseout="this.style.background='#48BB78'">Copy</button>
                            </div>
                        </div>
                    </div>

                    {{-- EasyPaisa --}}
                    <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);padding:16px;display:flex;flex-direction:column;gap:10px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <span style="font-size:9px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#48BB78;">EasyPaisa</span>
                            <span style="font-size:9px;font-weight:700;background:rgba(72,187,120,0.2);color:#48BB78;padding:2px 8px;">Wallet</span>
                        </div>
                        <div>
                            <div style="font-size:9px;color:rgba(255,255,255,0.4);margin-bottom:2px;">Account Title</div>
                            <div style="font-size:11px;font-weight:700;color:#48BB78;">Khizer hayat</div>
                        </div>
                        <div>
                            <div style="font-size:9px;color:rgba(255,255,255,0.4);margin-bottom:4px;">Mobile / Account No</div>
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:6px;">
                                <span style="font-family:monospace;font-size:11px;font-weight:700;color:#FFFFFF;letter-spacing:0.05em;">03150132001</span>
                                <button type="button" @click="copyText('03150132001')" style="font-size:9px;font-weight:700;background:#48BB78;color:#FFFFFF;border:none;cursor:pointer;padding:4px 8px;letter-spacing:0.06em;text-transform:uppercase;flex-shrink:0;" onmouseover="this.style.background='#38A169'" onmouseout="this.style.background='#48BB78'">Copy</button>
                            </div>
                        </div>
                    </div>

                    {{-- JazzCash --}}
                    <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);padding:16px;display:flex;flex-direction:column;gap:10px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <span style="font-size:9px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#C9A96E;">JazzCash</span>
                            <span style="font-size:9px;font-weight:700;background:rgba(201,169,110,0.15);color:#C9A96E;padding:2px 8px;">Wallet</span>
                        </div>
                        <div>
                            <div style="font-size:9px;color:rgba(255,255,255,0.4);margin-bottom:2px;">Account Title</div>
                            <div style="font-size:11px;font-weight:700;color:#48BB78;">Khizer hayat</div>
                        </div>
                        <div>
                            <div style="font-size:9px;color:rgba(255,255,255,0.4);margin-bottom:4px;">Mobile / Account No</div>
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:6px;">
                                <span style="font-family:monospace;font-size:11px;font-weight:700;color:#FFFFFF;letter-spacing:0.05em;">03249171213</span>
                                <button type="button" @click="copyText('03249171213')" style="font-size:9px;font-weight:700;background:#C9A96E;color:#FFFFFF;border:none;cursor:pointer;padding:4px 8px;letter-spacing:0.06em;text-transform:uppercase;flex-shrink:0;" onmouseover="this.style.background='#a07830'" onmouseout="this.style.background='#C9A96E'">Copy</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top:16px;padding-top:14px;border-top:1px solid rgba(255,255,255,0.1);font-size:11px;color:rgba(255,255,255,0.6);line-height:1.7;">
                    💡 <strong style="color:rgba(255,255,255,0.85);">Instructions:</strong>
                    Transfer <strong style="color:#C9A96E;">Rs. {{ number_format($order->total, 0) }}</strong> to any account above via your Banking / EasyPaisa / JazzCash app. Once done, submit your TRX ID below.
                </div>
            </div>

            {{-- Payment Proof Submission Form --}}
            <form wire:submit.prevent="submitPaymentReference" style="padding:24px;border-top:1px solid #E8E3DC;display:flex;flex-direction:column;gap:20px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;padding-bottom:16px;border-bottom:1px solid #E8E3DC;">
                    <div>
                        <h3 style="font-size:13px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin:0 0 4px;">Submit Payment Proof</h3>
                        <p style="font-size:12px;color:#888;margin:0;">Upload your payment screenshot or enter your TRX ID</p>
                    </div>
                    <a href="https://api.whatsapp.com/send?phone=923249171213&text={{ urlencode('Hi Al Hayat Kids, I have transferred payment of Rs. ' . number_format($order->total, 0) . ' for Order #' . $order->order_number . '. Here is my receipt.') }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:#25D366;color:#FFFFFF;font-size:10px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;transition:background 0.2s;"
                       onmouseover="this.style.background='#128C7E'" onmouseout="this.style.background='#25D366'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Send on WhatsApp
                    </a>
                </div>

                <div style="display:grid;grid-template-columns:1fr;gap:16px;" class="payment-form-grid">

                    {{-- File Upload --}}
                    <div>
                        <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                            Upload Payment Receipt / Screenshot (PNG, JPG)
                        </label>
                        <input type="file"
                               wire:model="payment_proof_file"
                               accept="image/*"
                               style="width:100%;padding:10px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:12px;color:#555;cursor:pointer;">
                        @error('payment_proof_file')
                            <span style="font-size:11px;font-weight:600;color:#E53E3E;display:block;margin-top:4px;">{{ $message }}</span>
                        @enderror

                        @if($payment_proof_file)
                            <div style="margin-top:8px;padding:8px;background:#F7F4EF;border:1px solid #E8E3DC;display:inline-block;">
                                <div style="font-size:9px;font-weight:700;color:#888;margin-bottom:4px;text-transform:uppercase;">New Image Preview</div>
                                <img src="{{ $payment_proof_file->temporaryUrl() }}" alt="Receipt" style="height:100px;max-width:200px;object-fit:contain;display:block;">
                            </div>
                        @elseif($order->payment && $order->payment->payment_proof_image && file_exists(storage_path('app/public/' . $order->payment->payment_proof_image)))
                            <div style="margin-top:8px;padding:8px;background:#F0FFF4;border:1px solid #C6F6D5;display:inline-block;">
                                <div style="font-size:9px;font-weight:700;color:#276749;margin-bottom:4px;text-transform:uppercase;">✓ Current Receipt:</div>
                                <img src="{{ route('orders.receipt-image', $order->id) }}" alt="Current Receipt" style="height:100px;max-width:200px;object-fit:contain;display:block;">
                            </div>
                        @endif
                    </div>

                    {{-- TRX ID --}}
                    <div>
                        <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                            Transaction Reference / TRX ID
                        </label>
                        <input type="text"
                               wire:model="reference_number"
                               placeholder="e.g. 012345678987"
                               style="width:100%;padding:12px 14px;background:#F7F4EF;border:1px solid #E8E3DC;font-family:monospace;font-size:13px;font-weight:700;color:#0D0D0D;outline:none;transition:border-color 0.2s;"
                               onfocus="this.style.borderColor='#0D0D0D'"
                               onblur="this.style.borderColor='#E8E3DC'">
                        @error('reference_number')
                            <span style="font-size:11px;font-weight:600;color:#E53E3E;display:block;margin-top:4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="payment-form-sub-grid">
                        {{-- Sender Name --}}
                        <div>
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                                Sender Name (Optional)
                            </label>
                            <input type="text"
                                   wire:model="sender_name"
                                   placeholder="Account holder name"
                                   style="width:100%;padding:12px 14px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:13px;color:#0D0D0D;outline:none;transition:border-color 0.2s;"
                                   onfocus="this.style.borderColor='#0D0D0D'"
                                   onblur="this.style.borderColor='#E8E3DC'">
                        </div>

                        {{-- Payment Notes --}}
                        <div>
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;margin-bottom:6px;">
                                Notes (Optional)
                            </label>
                            <input type="text"
                                   wire:model="payment_notes"
                                   placeholder="Additional note..."
                                   style="width:100%;padding:12px 14px;background:#F7F4EF;border:1px solid #E8E3DC;font-size:13px;color:#0D0D0D;outline:none;transition:border-color 0.2s;"
                                   onfocus="this.style.borderColor='#0D0D0D'"
                                   onblur="this.style.borderColor='#E8E3DC'">
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            style="padding:14px 32px;background:#0D0D0D;color:#FFFFFF;font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;border:none;cursor:pointer;transition:background 0.2s;display:inline-flex;align-items:center;gap:8px;"
                            onmouseover="this.style.background='#C9A96E'"
                            onmouseout="this.style.background='#0D0D0D'">
                        <span wire:loading.remove wire:target="submitPaymentReference">Submit Payment Confirmation</span>
                        <span wire:loading wire:target="submitPaymentReference" style="display:flex;align-items:center;gap:8px;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" style="animation:spin 1s linear infinite;">
                                <circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,0.3)" stroke-width="4"></circle>
                                <path d="M4 12a8 8 0 018-8" stroke="#FFFFFF" stroke-width="4" stroke-linecap="round"></path>
                            </svg>
                            Uploading & Submitting...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- ── Main Order Details Card ── --}}
    <div style="background:#FFFFFF;border:1px solid #E8E3DC;margin-bottom:28px;">

        {{-- Order Header --}}
        <div style="padding:20px 24px;background:#F7F4EF;border-bottom:1px solid #E8E3DC;display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div>
                <div style="font-size:9px;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#888;margin-bottom:4px;">Order Reference</div>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:#0D0D0D;margin:0 0 4px;">#{{ $order->order_number }}</h2>
                <p style="font-size:11px;color:#888;margin:0;">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>

            <div style="display:flex;flex-wrap:wrap;gap:12px;">
                <div style="text-align:right;">
                    <div style="font-size:9px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#888;margin-bottom:4px;">Order Status</div>
                    <span style="display:inline-block;padding:5px 12px;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;background:{{ $order->order_status === 'completed' ? '#F0FFF4' : '#FFFBEB' }};color:{{ $order->order_status === 'completed' ? '#276749' : '#B7791F' }};border:1px solid {{ $order->order_status === 'completed' ? '#C6F6D5' : '#FBD38D' }};">
                        {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                    </span>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:9px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#888;margin-bottom:4px;">Payment Status</div>
                    @if($order->payment_status === 'paid')
                        <span style="display:inline-block;padding:5px 12px;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;background:#48BB78;color:#FFFFFF;">✓ Paid & Verified</span>
                    @elseif($order->payment_status === 'pending_verification')
                        <span style="display:inline-block;padding:5px 12px;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;background:#ECC94B;color:#744210;">⏳ Verification Pending</span>
                    @else
                        <span style="display:inline-block;padding:5px 12px;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;background:#F7F4EF;color:#666;border:1px solid #E8E3DC;">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Order Items --}}
        <div style="padding:20px 24px;border-bottom:1px solid #E8E3DC;">
            <div style="font-size:10px;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#888;margin-bottom:16px;">Order Items</div>
            <div style="display:flex;flex-direction:column;gap:0;">
                @foreach($order->items as $item)
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;padding:14px 0;border-bottom:1px solid #F0EDE7;" class="order-item-row">
                        <div style="display:flex;align-items:center;gap:14px;flex:1;min-width:0;">
                            <div style="width:52px;height:64px;background:#F7F4EF;border:1px solid #E8E3DC;overflow:hidden;flex-shrink:0;">
                                @if($item->product && $item->product->primaryImage && !empty($item->product->primaryImage->url))
                                    <img src="{{ $item->product->primaryImage->url }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:9px;color:#C9A96E;font-weight:700;text-align:center;">AH Kids</div>
                                @endif
                            </div>
                            <div style="flex:1;min-width:0;">
                                <h4 style="font-size:13px;font-weight:600;color:#0D0D0D;margin:0 0 4px;line-height:1.3;">{{ $item->product_name }}</h4>
                                <div style="display:flex;flex-wrap:wrap;align-items:center;gap:6px;">
                                    @if($item->sku)
                                        <span style="font-size:9px;font-weight:700;letter-spacing:0.08em;background:#F7F4EF;color:#666;padding:2px 6px;font-family:monospace;">SKU: {{ $item->sku }}</span>
                                    @endif
                                    @if($item->size)
                                        <span style="font-size:9px;font-weight:700;background:#FFF5F5;color:#C53030;padding:2px 6px;">Size: {{ $item->size }}</span>
                                    @endif
                                    @if($item->color)
                                        <span style="font-size:9px;font-weight:700;background:#F7F4EF;color:#555;padding:2px 6px;">Color: {{ $item->color }}</span>
                                    @endif
                                </div>
                                <p style="font-size:11px;color:#888;margin:4px 0 0;">Rs. {{ number_format($item->unit_price, 0) }} × {{ $item->quantity }}</p>
                            </div>
                        </div>
                        <div style="font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:700;color:#0D0D0D;white-space:nowrap;flex-shrink:0;">
                            Rs. {{ number_format($item->total, 0) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Summary Grid: Address + Breakdown --}}
        <div class="order-info-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:0;">

            {{-- Delivery Address --}}
            <div style="padding:20px 24px;border-right:1px solid #E8E3DC;">
                <div style="font-size:10px;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#888;margin-bottom:14px;">Delivery Address</div>
                @if($shippingAddress)
                    <div style="font-size:13px;line-height:1.8;color:#555;">
                        <div style="font-size:14px;font-weight:700;color:#0D0D0D;margin-bottom:4px;">{{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}</div>
                        <div>{{ $shippingAddress->address_line_1 }}</div>
                        @if($shippingAddress->address_line_2)<div>{{ $shippingAddress->address_line_2 }}</div>@endif
                        <div>{{ $shippingAddress->city }}{{ $shippingAddress->state ? ', ' . $shippingAddress->state : '' }} {{ $shippingAddress->postal_code }}</div>
                        <div style="font-family:monospace;color:#888;margin-top:4px;">📞 {{ $shippingAddress->phone }}</div>
                    </div>
                @else
                    <p style="font-size:12px;color:#888;">Standard Home Delivery across Pakistan</p>
                @endif

                @if($order->payment)
                    <div style="margin-top:16px;padding-top:14px;border-top:1px solid #E8E3DC;">
                        <div style="font-size:10px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#888;margin-bottom:8px;">Payment Reference</div>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;">
                                <span style="color:#666;">Method</span>
                                <span style="font-weight:700;color:#0D0D0D;text-transform:uppercase;">{{ strtoupper($order->payment->method) }}</span>
                            </div>
                            @if($order->payment->reference_number)
                                <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;">
                                    <span style="color:#666;">TRX ID</span>
                                    <span style="font-family:monospace;font-weight:700;color:#0D0D0D;">{{ $order->payment->reference_number }}</span>
                                </div>
                            @endif
                            @if($order->payment->sender_name)
                                <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;">
                                    <span style="color:#666;">Sender</span>
                                    <span style="font-weight:600;color:#0D0D0D;">{{ $order->payment->sender_name }}</span>
                                </div>
                            @endif
                            <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;">
                                <span style="color:#666;">Status</span>
                                <span style="font-weight:700;color:{{ $order->payment->status === 'paid' ? '#276749' : '#B7791F' }};">
                                    {{ $order->payment->status === 'paid' ? '✓ Verified & Paid' : 'Pending Verification' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Price Breakdown --}}
            <div style="padding:20px 24px;">
                <div style="font-size:10px;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#888;margin-bottom:14px;">Payment Breakdown</div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;justify-content:space-between;font-size:13px;">
                        <span style="color:#666;">Subtotal</span>
                        <span style="font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:700;color:#0D0D0D;">Rs. {{ number_format($order->subtotal, 0) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div style="display:flex;justify-content:space-between;font-size:13px;color:#276749;font-weight:700;">
                            <span>Discount</span>
                            <span>- Rs. {{ number_format($order->discount, 0) }}</span>
                        </div>
                    @endif
                    <div style="display:flex;justify-content:space-between;font-size:13px;">
                        <span style="color:#666;">Shipping Fee</span>
                        <span style="font-weight:600;color:#0D0D0D;">Rs. {{ number_format($order->shipping_cost, 0) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:baseline;padding-top:12px;border-top:2px solid #E8E3DC;margin-top:4px;">
                        <span style="font-size:11px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#0D0D0D;">TOTAL DUE</span>
                        <span style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:#0D0D0D;">Rs. {{ number_format($order->total, 0) }}</span>
                    </div>
                </div>

                @if($order->customer_notes)
                    <div style="margin-top:20px;padding-top:16px;border-top:1px solid #E8E3DC;">
                        <div style="font-size:10px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#888;margin-bottom:6px;">Order Notes</div>
                        <p style="font-size:12px;color:#555;line-height:1.6;margin:0;">{{ $order->customer_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- CTA Buttons --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;" class="order-cta-row">
        <a href="{{ route('shop') }}"
           style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;color:#0D0D0D;text-decoration:none;letter-spacing:0.08em;text-transform:uppercase;border-bottom:1px solid #0D0D0D;padding-bottom:2px;transition:color 0.2s,border-color 0.2s;"
           onmouseover="this.style.color='#C9A96E';this.style.borderColor='#C9A96E'"
           onmouseout="this.style.color='#0D0D0D';this.style.borderColor='#0D0D0D'">
            ← Continue Shopping
        </a>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('dashboard') }}"
               style="display:inline-flex;align-items:center;gap:6px;padding:12px 24px;background:#F7F4EF;color:#0D0D0D;font-size:10px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;text-decoration:none;border:1px solid #E8E3DC;transition:background 0.2s;"
               onmouseover="this.style.background='#EDE9E1'"
               onmouseout="this.style.background='#F7F4EF'">
                My Orders
            </a>
            <a href="{{ route('home') }}"
               style="display:inline-flex;align-items:center;gap:6px;padding:12px 24px;background:#0D0D0D;color:#FFFFFF;font-size:10px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;text-decoration:none;transition:background 0.2s;"
               onmouseover="this.style.background='#C9A96E'"
               onmouseout="this.style.background='#0D0D0D'">
                Back to Home
            </a>
        </div>
    </div>

</div>

<style>
/* ── ORDER SHOW RESPONSIVE ── */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.order-success-banner {
    flex-direction: row;
}

@media (max-width: 768px) {
    .order-show-container {
        padding: 16px 14px 60px !important;
    }
    .order-success-banner {
        padding: 20px !important;
        gap: 14px !important;
    }
    .payment-accounts-grid {
        grid-template-columns: 1fr !important;
    }
    .order-info-grid {
        grid-template-columns: 1fr !important;
    }
    .order-info-grid > div:first-child {
        border-right: none !important;
        border-bottom: 1px solid #E8E3DC;
    }
    .payment-form-sub-grid {
        grid-template-columns: 1fr !important;
    }
    .order-cta-row {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    .order-cta-row a {
        text-align: center !important;
        justify-content: center !important;
    }
    .order-item-row {
        flex-wrap: wrap !important;
    }
}

@media (max-width: 480px) {
    .order-info-grid > div {
        padding: 16px !important;
    }
}
</style>
