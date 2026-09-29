<div x-data="{ isOpen: false }"
     @open-cart-drawer.window="isOpen = true"
     @cart-updated.window="if ($event.detail && $event.detail.openDrawer) { isOpen = true }"
     @toggle-cart-drawer.window="isOpen = !isOpen"
     @keydown.escape.window="isOpen = false"
     style="position:relative;z-index:99999;">

    {{-- Backdrop Overlay --}}
    <div x-show="isOpen"
         x-cloak
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="isOpen = false"
         style="position:fixed;inset:0;background:rgba(13,13,13,0.6);backdrop-filter:blur(4px);z-index:99998;"></div>

    {{-- Slide-Over Cart Panel --}}
    <div x-show="isOpen"
         x-cloak
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         style="position:fixed;top:0;bottom:0;right:0;width:100%;max-width:440px;background:#FFFFFF;box-shadow:-8px 0 32px rgba(0,0,0,0.25);display:flex;flex-direction:column;height:100%;overflow:hidden;color:#0D0D0D;z-index:99999;">

        {{-- ── Drawer Header ── --}}
        <div style="padding:20px 24px;border-bottom:1px solid #E8E3DC;display:flex;align-items:center;justify-content:space-between;background:#FFFFFF;flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:10px;">
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:#0D0D0D;margin:0;letter-spacing:0.02em;">
                    SHOPPING BAG
                </h2>
                <span style="padding:2px 8px;background:#F7F4EF;color:#C9A96E;font-size:10px;font-weight:700;letter-spacing:0.1em;border:1px solid #E8E3DC;">
                    {{ $count }} {{ Str::plural('ITEM', $count) }}
                </span>
            </div>

            <button type="button"
                    @click="isOpen = false"
                    style="background:none;border:none;cursor:pointer;color:#888;padding:4px;display:flex;align-items:center;justify-content:center;transition:color 0.2s;"
                    onmouseover="this.style.color='#0D0D0D'"
                    onmouseout="this.style.color='#888'"
                    aria-label="Close cart">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Flash Notifications --}}
        @if($flashMessage)
            <div style="padding:10px 20px;font-size:11px;font-weight:600;background:{{ $flashMessageType === 'error' ? '#FFF5F5' : '#F0FFF4' }};color:{{ $flashMessageType === 'error' ? '#C53030' : '#276749' }};border-bottom:1px solid #E8E3DC;display:flex;align-items:center;justify-content:space-between;">
                <span>{{ $flashMessage }}</span>
                <button wire:click="$set('flashMessage', '')" style="background:none;border:none;cursor:pointer;color:#888;">&times;</button>
            </div>
        @endif

        {{-- ── Cart Items List ── --}}
        <div style="flex:1;overflow-y:auto;padding:20px 24px;">
            @if($items->isEmpty())
                {{-- Empty State --}}
                <div style="height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:40px 20px;">
                    <div style="width:64px;height:64px;border-radius:50%;background:#F7F4EF;display:flex;align-items:center;justify-content:center;color:#C9A96E;margin-bottom:16px;">
                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <h3 style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:#0D0D0D;margin:0 0 6px;">Your Shopping Bag is Empty</h3>
                    <p style="font-size:12px;color:#888;margin:0 0 20px;max-width:240px;line-height:1.6;">Discover our luxury children's collection and add items to your cart.</p>
                    <a href="{{ route('shop') }}"
                       @click="isOpen = false"
                       style="display:inline-block;padding:12px 28px;background:#0D0D0D;color:#FFFFFF;font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;text-decoration:none;transition:background 0.2s;"
                       onmouseover="this.style.background='#C9A96E'"
                       onmouseout="this.style.background='#0D0D0D'">
                        EXPLORE CATALOG
                    </a>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:20px;">
                    @foreach($items as $item)
                        @php
                            $product = $item->product;
                            $variant = $item->variant;

                            $imgObj = $product->primaryImage ?: ($product->images ? $product->images->first() : null);
                            $imageUrl = '';
                            if ($imgObj && !empty($imgObj->url)) {
                                $imageUrl = $imgObj->url;
                            } elseif ($imgObj && !empty($imgObj->image)) {
                                $imageUrl = \App\Models\ProductImage::getImageUrl($imgObj->image);
                            }
                            $hasValidImage = !empty($imageUrl);
                        @endphp
                        <div style="display:flex;gap:14px;padding-bottom:16px;border-bottom:1px solid #E8E3DC;">

                            {{-- Image --}}
                            <a href="{{ route('products.show', $product->slug) }}"
                               @click="isOpen = false"
                               style="width:72px;height:90px;flex-shrink:0;background:#F7F4EF;border:1px solid #E8E3DC;overflow:hidden;display:block;">
                                @if($hasValidImage)
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#C9A96E;">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </a>

                            {{-- Item Details --}}
                            <div style="flex:1;min-width:0;display:flex;flex-direction:column;justify-content:space-between;">
                                <div>
                                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
                                        <a href="{{ route('products.show', $product->slug) }}"
                                           @click="isOpen = false"
                                           style="font-size:13px;font-weight:600;color:#0D0D0D;text-decoration:none;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                            {{ $product->name }}
                                        </a>
                                        <button type="button"
                                                wire:click="removeItem({{ $item->id }})"
                                                style="background:none;border:none;cursor:pointer;color:#BBB;padding:0;transition:color 0.2s;flex-shrink:0;"
                                                onmouseover="this.style.color='#E53E3E'"
                                                onmouseout="this.style.color='#BBB'"
                                                title="Remove">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>

                                    {{-- Variants --}}
                                    @if($variant)
                                        <div style="display:flex;gap:6px;margin-top:4px;flex-wrap:wrap;">
                                            @if($variant->size)
                                                <span style="font-size:9px;font-weight:700;color:#666;background:#F7F4EF;padding:2px 6px;border:1px solid #E8E3DC;">
                                                    SIZE: {{ $variant->size->name }}
                                                </span>
                                            @endif
                                            @if($variant->color)
                                                <span style="font-size:9px;font-weight:700;color:#666;background:#F7F4EF;padding:2px 6px;border:1px solid #E8E3DC;">
                                                    {{ strtoupper($variant->color->name) }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                {{-- Quantity & Subtotal Row --}}
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:8px;">
                                    {{-- Quantity Stepper --}}
                                    <div style="display:flex;align-items:center;border:1px solid #0D0D0D;">
                                        <button type="button"
                                                wire:click="decrementQuantity({{ $item->id }}, {{ $item->quantity }})"
                                                style="width:24px;height:24px;background:#FFFFFF;border:none;cursor:pointer;font-size:12px;font-weight:600;color:#0D0D0D;display:flex;align-items:center;justify-content:center;">
                                            −
                                        </button>
                                        <span style="width:26px;text-align:center;font-size:11px;font-weight:700;color:#0D0D0D;border-left:1px solid #E8E3DC;border-right:1px solid #E8E3DC;line-height:24px;">
                                            {{ $item->quantity }}
                                        </span>
                                        <button type="button"
                                                wire:click="incrementQuantity({{ $item->id }}, {{ $item->quantity }})"
                                                style="width:24px;height:24px;background:#FFFFFF;border:none;cursor:pointer;font-size:12px;font-weight:600;color:#0D0D0D;display:flex;align-items:center;justify-content:center;">
                                            +
                                        </button>
                                    </div>

                                    {{-- Item Total --}}
                                    <span style="font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:700;color:#0D0D0D;">
                                        Rs. {{ number_format($item->effective_unit_price * $item->quantity, 0) }}
                                    </span>
                                </div>

                            </div>

                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── Drawer Footer Summary & Checkout ── --}}
        @if($count > 0)
            <div style="border-top:1px solid #E8E3DC;background:#F7F4EF;padding:20px 24px;flex-shrink:0;">

                <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px;font-size:12px;">
                    <div style="display:flex;justify-content:space-between;color:#666;">
                        <span>Subtotal</span>
                        <span style="font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:700;color:#0D0D0D;">
                            Rs. {{ number_format($subtotal, 0) }}
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;color:#666;">
                        <span>Shipping</span>
                        <span style="font-weight:600;color:#0D0D0D;">
                            {{ $shippingFee == 0 ? 'FREE' : 'Rs. ' . number_format($shippingFee, 0) }}
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:baseline;padding-top:10px;border-top:1px solid #E8E3DC;font-size:14px;font-weight:700;color:#0D0D0D;">
                        <span>TOTAL</span>
                        <span style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:#0D0D0D;">
                            Rs. {{ number_format($total, 0) }}
                        </span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <a href="{{ route('checkout.index') }}"
                       @click="isOpen = false"
                       style="display:block;width:100%;padding:14px 16px;background:#0D0D0D;color:#FFFFFF;font-size:11px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;text-decoration:none;text-align:center;transition:background 0.2s;"
                       onmouseover="this.style.background='#C9A96E'"
                       onmouseout="this.style.background='#0D0D0D'">
                        PROCEED TO CHECKOUT
                    </a>

                    <a href="{{ route('cart.index') }}"
                       @click="isOpen = false"
                       style="display:block;width:100%;padding:11px 16px;background:transparent;color:#0D0D0D;border:1px solid #0D0D0D;font-size:10px;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;text-decoration:none;text-align:center;transition:background 0.2s;"
                       onmouseover="this.style.background='#FFFFFF'"
                       onmouseout="this.style.background='transparent'">
                        VIEW SHOPPING BAG
                    </a>
                </div>

            </div>
        @endif

    </div>
</div>
