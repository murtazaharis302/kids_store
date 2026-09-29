<div style="max-width:1200px;margin:0 auto;padding:32px 24px 80px;" class="cart-page-container">

    {{-- ── Breadcrumbs ── --}}
    <nav style="display:flex;align-items:center;gap:8px;font-size:11px;font-weight:500;color:#999;letter-spacing:0.04em;margin-bottom:28px;">
        <a href="{{ route('home') }}" style="color:#999;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#C9A96E'" onmouseout="this.style.color='#999'">Home</a>
        <span>›</span>
        <a href="{{ route('shop') }}" style="color:#999;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#C9A96E'" onmouseout="this.style.color='#999'">Shop</a>
        <span>›</span>
        <span style="color:#0D0D0D;font-weight:600;">Shopping Bag</span>
    </nav>

    {{-- ── Page Header ── --}}
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:32px;padding-bottom:16px;border-bottom:1px solid #E8E3DC;flex-wrap:wrap;gap:16px;">
        <div>
            <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin:0 0 4px;">Al Hayat Kids</p>
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(26px,4vw,36px);font-weight:600;color:#0D0D0D;margin:0;line-height:1.1;">
                Shopping Bag
            </h1>
        </div>
        @if($items->isNotEmpty())
            <button type="button"
                    wire:click="clearCart"
                    wire:confirm="Are you sure you want to clear your cart?"
                    style="background:none;border:none;cursor:pointer;font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#888;transition:color 0.2s;"
                    onmouseover="this.style.color='#E53E3E'"
                    onmouseout="this.style.color='#888'">
                CLEAR BAG
            </button>
        @endif
    </div>

    {{-- Flash Notifications --}}
    @if($flashMessage)
        <div style="padding:12px 20px;margin-bottom:24px;background:{{ $flashMessageType === 'success' ? '#F0FFF4' : '#FFF5F5' }};border:1px solid {{ $flashMessageType === 'success' ? '#C6F6D5' : '#FED7D7' }};color:{{ $flashMessageType === 'success' ? '#276749' : '#C53030' }};font-size:12px;font-weight:600;display:flex;align-items:center;justify-content:space-between;">
            <span>{{ $flashMessage }}</span>
            <button type="button" wire:click="$set('flashMessage', '')" style="background:none;border:none;cursor:pointer;color:#888;">&times;</button>
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
                    <div style="font-size:11px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#0D0D0D;">Temporary Stock Reservation</div>
                    <div style="font-size:11px;color:#666;margin-top:2px;">Items in your bag are reserved for 10 minutes to secure stock.</div>
                </div>
            </div>
            <div style="background:#FFFFFF;padding:6px 14px;border:1px solid #E8E3DC;font-family:monospace;font-size:13px;font-weight:700;color:#C9A96E;letter-spacing:0.1em;">
                <span x-text="secondsLeft > 0 ? formatTime(secondsLeft) : '00:00'"></span>
            </div>
        </div>
    @endif

    @if($items->isEmpty())
        {{-- Empty Bag State --}}
        <div style="padding:64px 24px;text-align:center;background:#FFFFFF;border:1px solid #E8E3DC;max-width:520px;margin:0 auto;">
            <div style="width:64px;height:64px;border-radius:50%;background:#F7F4EF;display:flex;align-items:center;justify-content:center;color:#C9A96E;margin:0 auto 16px;">
                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h2 style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:600;color:#0D0D0D;margin:0 0 8px;">Your Shopping Bag is Empty</h2>
            <p style="font-size:13px;color:#888;margin:0 0 24px;line-height:1.6;">Discover our luxury kids collections and add your favorite outfits to bag.</p>
            <a href="{{ route('shop') }}"
               style="display:inline-block;padding:14px 36px;background:#0D0D0D;color:#FFFFFF;font-size:11px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;text-decoration:none;transition:background 0.2s;"
               onmouseover="this.style.background='#C9A96E'"
               onmouseout="this.style.background='#0D0D0D'">
                START SHOPPING
            </a>
        </div>
    @else
        {{-- Main Cart Grid --}}
        <div class="cart-grid-wrap">

            {{-- LEFT: Cart Items --}}
            <div style="display:flex;flex-direction:column;gap:16px;">
                @foreach($items as $item)
                    @php
                        $product = $item->product;
                        $variant = $item->variant;

                        $imgUrl = '';
                        $hasImg = false;
                        if ($product) {
                            if ($product->primaryImage && !empty($product->primaryImage->url)) {
                                $imgUrl = $product->primaryImage->url;
                                $hasImg = true;
                            } elseif ($product->images->isNotEmpty() && !empty($product->images->first()->url)) {
                                $imgUrl = $product->images->first()->url;
                                $hasImg = true;
                            }
                        }

                        $stock = $variant ? $variant->stock_quantity : 0;
                        $itemTotal = (float) $item->price * $item->quantity;
                    @endphp

                    <div style="padding:20px;background:#FFFFFF;border:1px solid #E8E3DC;display:flex;align-items:center;gap:20px;flex-wrap:wrap;" class="cart-item-card">
                        
                        {{-- Thumbnail --}}
                        <a href="{{ $product ? route('products.show', $product->slug) : '#' }}"
                           style="width:84px;height:104px;flex-shrink:0;background:#F7F4EF;border:1px solid #E8E3DC;overflow:hidden;display:block;">
                            @if($hasImg)
                                <img src="{{ $imgUrl }}" alt="{{ $product->name ?? 'Product' }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#C9A96E;font-size:10px;">Al Hayat Kids</div>
                            @endif
                        </a>

                        {{-- Details --}}
                        <div style="flex:1;min-width:200px;display:flex;flex-direction:column;gap:6px;">
                            @if($product)
                                <a href="{{ route('products.show', $product->slug) }}"
                                   style="font-size:14px;font-weight:600;color:#0D0D0D;text-decoration:none;line-height:1.35;"
                                   onmouseover="this.style.color='#C9A96E'"
                                   onmouseout="this.style.color='#0D0D0D'">
                                    {{ $product->name }}
                                </a>
                            @else
                                <span style="font-size:14px;font-weight:600;color:#0D0D0D;">Unavailable Product</span>
                            @endif

                            @if($variant)
                                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:2px;">
                                    @if($variant->color)
                                        <span style="font-size:9px;font-weight:700;color:#666;background:#F7F4EF;padding:2px 8px;border:1px solid #E8E3DC;">
                                            COLOR: {{ strtoupper($variant->color->name) }}
                                        </span>
                                    @endif
                                    @if($variant->size)
                                        <span style="font-size:9px;font-weight:700;color:#666;background:#F7F4EF;padding:2px 8px;border:1px solid #E8E3DC;">
                                            SIZE: {{ strtoupper($variant->size->name) }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div style="font-size:11px;color:#888;margin-top:2px;">
                                Unit Price: <strong style="color:#0D0D0D;">Rs. {{ number_format($item->price, 0) }}</strong>
                            </div>
                        </div>

                        {{-- Quantity & Total --}}
                        <div style="display:flex;align-items:center;gap:24px;margin-left:auto;flex-wrap:wrap;">
                            {{-- Stepper --}}
                            <div style="display:flex;align-items:center;border:1px solid #0D0D0D;">
                                <button type="button"
                                        wire:click="decrementQuantity({{ $item->id }}, {{ $item->quantity }})"
                                        style="width:30px;height:32px;background:#FFFFFF;border:none;cursor:pointer;font-size:14px;font-weight:600;color:#0D0D0D;display:flex;align-items:center;justify-content:center;">
                                    −
                                </button>
                                <span style="width:32px;text-align:center;font-size:12px;font-weight:700;color:#0D0D0D;border-left:1px solid #E8E3DC;border-right:1px solid #E8E3DC;line-height:32px;">
                                    {{ $item->quantity }}
                                </span>
                                <button type="button"
                                        wire:click="incrementQuantity({{ $item->id }}, {{ $item->quantity }})"
                                        @if($item->quantity >= $stock) disabled @endif
                                        style="width:30px;height:32px;background:#FFFFFF;border:none;cursor:pointer;font-size:14px;font-weight:600;color:#0D0D0D;display:flex;align-items:center;justify-content:center;">
                                    +
                                </button>
                            </div>

                            {{-- Subtotal --}}
                            <div style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:700;color:#0D0D0D;min-width:90px;text-align:right;">
                                Rs. {{ number_format($itemTotal, 0) }}
                            </div>

                            {{-- Remove --}}
                            <button type="button"
                                    wire:click="removeItem({{ $item->id }})"
                                    style="background:none;border:none;cursor:pointer;color:#BBB;padding:4px;transition:color 0.2s;"
                                    onmouseover="this.style.color='#E53E3E'"
                                    onmouseout="this.style.color='#BBB'"
                                    title="Remove Item">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- RIGHT: Order Summary --}}
            <div style="padding:28px;background:#F7F4EF;border:1px solid #E8E3DC;position:sticky;top:24px;">
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:#0D0D0D;margin:0 0 20px;padding-bottom:12px;border-bottom:1px solid #E8E3DC;">
                    Order Summary
                </h2>

                <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:24px;font-size:13px;">
                    <div style="display:flex;justify-content:space-between;color:#666;">
                        <span>Subtotal</span>
                        <span style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:700;color:#0D0D0D;">
                            Rs. {{ number_format($subtotal, 0) }}
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;color:#666;">
                        <span>Estimated Shipping</span>
                        <span style="font-weight:600;color:#0D0D0D;">
                            {{ $shippingFee == 0 ? 'FREE' : 'Rs. ' . number_format($shippingFee, 0) }}
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:baseline;padding-top:14px;border-top:1px solid #E8E3DC;font-size:15px;font-weight:700;color:#0D0D0D;">
                        <span>ESTIMATED TOTAL</span>
                        <span style="font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:700;color:#0D0D0D;">
                            Rs. {{ number_format($total, 0) }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}"
                   style="display:block;width:100%;padding:16px 20px;background:#0D0D0D;color:#FFFFFF;font-size:11px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;text-decoration:none;text-align:center;transition:background 0.25s;margin-bottom:12px;"
                   onmouseover="this.style.background='#C9A96E'"
                   onmouseout="this.style.background='#0D0D0D'">
                    PROCEED TO CHECKOUT
                </a>

                <a href="{{ route('shop') }}"
                   style="display:block;width:100%;padding:12px 20px;background:transparent;color:#0D0D0D;border:1px solid #0D0D0D;font-size:10px;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;text-decoration:none;text-align:center;transition:background 0.2s;"
                   onmouseover="this.style.background='#FFFFFF'"
                   onmouseout="this.style.background='transparent'">
                    CONTINUE SHOPPING
                </a>
            </div>

        </div>
    @endif

</div>

<style>
/* ── CART PAGE RESPONSIVE ── */
.cart-grid-wrap {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 36px;
    align-items: start;
}
@media (max-width: 992px) {
    .cart-grid-wrap {
        grid-template-columns: 1fr;
        gap: 24px;
    }
}
@media (max-width: 600px) {
    .cart-item-card {
        padding: 14px !important;
        gap: 12px !important;
    }
}
</style>
