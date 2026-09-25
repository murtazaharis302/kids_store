<div x-data="{ isOpen: false }"
     @open-cart-drawer.window="isOpen = true"
     @cart-updated.window="if ($event.detail && $event.detail.openDrawer) { isOpen = true }"
     @toggle-cart-drawer.window="isOpen = !isOpen"
     @keydown.escape.window="isOpen = false"
     class="relative z-50">

    <!-- Backdrop Overlay -->
    <div x-show="isOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="isOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50"
         style="display: none;"></div>

    <!-- Slide-Over Cart Drawer Panel -->
    <div x-show="isOpen"
         x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 max-w-full flex pl-10 z-50"
         style="display: none;">

        <div class="w-screen max-w-md sm:max-w-lg bg-white shadow-2xl flex flex-col h-full overflow-hidden text-slate-800">

            <!-- Drawer Header -->
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                <div class="flex items-center gap-2.5">
                    <h2 class="text-xl font-extrabold text-slate-900 font-heading tracking-tight">Shopping Cart</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700 text-xs font-bold font-heading">
                        {{ $count }} {{ Str::plural('item', $count) }}
                    </span>
                </div>

                <button type="button" 
                        @click="isOpen = false" 
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition focus:outline-none"
                        aria-label="Close cart drawer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Flash Notifications -->
            @if($flashMessage)
                <div class="px-5 py-2.5 text-xs font-bold {{ $flashMessageType === 'error' ? 'bg-rose-50 text-rose-700 border-b border-rose-100' : 'bg-emerald-50 text-emerald-700 border-b border-emerald-100' }} flex items-center justify-between">
                    <span>{{ $flashMessage }}</span>
                    <button wire:click="$set('flashMessage', '')" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>
            @endif

            <!-- Cart Items List Container -->
            <div class="flex-1 overflow-y-auto px-5 py-4 divide-y divide-slate-100">
                @if($items->isEmpty())
                    <!-- Empty Cart State -->
                    <div class="h-full flex flex-col items-center justify-center text-center py-12 px-4 space-y-4">
                        <div class="w-20 h-20 rounded-full bg-rose-50 flex items-center justify-center text-3xl text-rose-500 shadow-inner">
                            🛍️
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-lg font-bold text-slate-900 font-heading">Your shopping cart is empty</h3>
                            <p class="text-xs text-slate-500 max-w-xs">Discover our luxury kids collections and add your favorite outfits to cart.</p>
                        </div>
                        <a href="{{ route('shop') }}" 
                           @click="isOpen = false" 
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition transform active:scale-95">
                            <span>Explore Storefront</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                @else
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
                        <div class="py-4 first:pt-0 flex items-start gap-4 group">
                            
                            <!-- Thumbnail Image with Fail-Proof Fallback -->
                            <a href="{{ route('products.show', $product->slug) }}" 
                               @click="isOpen = false" 
                               class="shrink-0 w-20 h-24 sm:w-22 sm:h-26 rounded-xl overflow-hidden bg-slate-100 border border-slate-200/80 shadow-2xs group-hover:border-rose-300 transition relative flex items-center justify-center">
                                @if($hasValidImage)
                                    <img src="{{ $imageUrl }}" 
                                         alt="{{ $product->name }}" 
                                         onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @endif
                                <div class="flex flex-col items-center justify-center p-2 text-slate-400 text-center w-full h-full bg-slate-100 {{ $hasValidImage ? 'hidden' : '' }}">
                                    <svg class="w-7 h-7 stroke-current opacity-40 mb-1" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-[9px] font-extrabold text-slate-400 leading-tight uppercase font-heading">AH Kids</span>
                                </div>
                            </a>

                            <!-- Item Details -->
                            <div class="flex-1 min-w-0 flex flex-col justify-between h-full space-y-1.5">
                                <div>
                                    <a href="{{ route('products.show', $product->slug) }}" 
                                       @click="isOpen = false" 
                                       class="font-extrabold text-sm text-slate-900 hover:text-rose-600 font-heading leading-tight truncate block transition">
                                        {{ $product->name }}
                                    </a>

                                    <span class="text-[11px] font-medium text-slate-400 block mt-0.5">
                                        SKU: {{ $variant?->sku ?? ('AHK-' . sprintf('%04d', $product->id)) }}
                                    </span>

                                    <!-- Variant Specifications -->
                                    @if($variant)
                                        <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                            @if($variant->size)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-bold border border-slate-200">
                                                    <span>Size: {{ $variant->size->name }}</span>
                                                </span>
                                            @endif
                                            @if($variant->color)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-bold border border-slate-200">
                                                    @if($variant->color->hex_code)
                                                        <span class="w-2 h-2 rounded-full border border-black/20" style="background-color: {{ $variant->color->hex_code }}"></span>
                                                    @endif
                                                    <span>{{ $variant->color->name }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Unit Price display -->
                                <div class="flex items-center gap-2">
                                    @if($item->unit_price > $item->effective_unit_price)
                                        <span class="text-xs text-slate-400 line-through">Rs. {{ number_format($item->unit_price, 2) }}</span>
                                        <span class="text-sm font-extrabold text-rose-600 font-heading">Rs. {{ number_format($item->effective_unit_price, 2) }}</span>
                                    @else
                                        <span class="text-sm font-extrabold text-slate-900 font-heading">Rs. {{ number_format($item->effective_unit_price, 2) }}</span>
                                    @endif
                                </div>

                                <!-- Quantity Stepper & Removal Row -->
                                <div class="flex items-center justify-between pt-1">
                                    <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50 overflow-hidden shadow-2xs">
                                        <button type="button" 
                                                wire:click="decrementQuantity({{ $item->id }}, {{ $item->quantity }})" 
                                                class="w-7 h-7 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition font-bold text-xs">
                                            &minus;
                                        </button>
                                        <span class="w-8 text-center text-xs font-bold text-slate-900 font-heading">
                                            {{ $item->quantity }}
                                        </span>
                                        <button type="button" 
                                                wire:click="incrementQuantity({{ $item->id }}, {{ $item->quantity }})" 
                                                class="w-7 h-7 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition font-bold text-xs">
                                            &#43;
                                        </button>
                                    </div>

                                    <!-- Item Subtotal & Trash Button -->
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-extrabold text-slate-900 font-heading">
                                            Rs. {{ number_format($item->effective_unit_price * $item->quantity, 2) }}
                                        </span>

                                        <button type="button" 
                                                wire:click="removeItem({{ $item->id }})" 
                                                class="p-1 text-slate-400 hover:text-rose-600 transition" 
                                                title="Remove Item">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Drawer Footer Summary & Actions -->
            @if($count > 0)
                <div class="border-t border-slate-200/80 bg-slate-50/80 px-5 py-4 space-y-3 shrink-0">
                    
                    <div class="space-y-1.5 text-xs">
                        <div class="flex justify-between font-medium text-slate-600">
                            <span>Subtotal</span>
                            <span class="text-slate-900 font-extrabold font-heading">Rs. {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-medium text-slate-600">
                            <span>Estimated Shipping</span>
                            <span class="text-slate-900 font-extrabold font-heading">
                                {{ $shippingFee == 0 ? 'FREE' : 'Rs. ' . number_format($shippingFee, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-base font-extrabold text-slate-900 border-t border-slate-200/80 pt-2 font-heading">
                            <span>Total</span>
                            <span class="text-rose-600">Rs. {{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-500 text-center font-medium">
                        Tax included and shipping calculated at checkout
                    </p>

                    <!-- Checkout & View Cart Buttons -->
                    <div class="space-y-2 pt-1">
                        <a href="{{ route('checkout.index') }}" 
                           @click="isOpen = false" 
                           class="w-full py-3.5 px-4 rounded-xl bg-slate-900 hover:bg-black text-white font-extrabold text-xs sm:text-sm uppercase tracking-wider block text-center shadow-md hover:shadow-lg transition transform active:scale-98 cursor-pointer">
                            CHECKOUT
                        </a>

                        <a href="{{ route('cart.index') }}" 
                           @click="isOpen = false" 
                           class="w-full py-3 px-4 rounded-xl bg-white hover:bg-slate-100 text-slate-900 border-2 border-slate-900 font-extrabold text-xs uppercase tracking-wider block text-center transition transform active:scale-98 cursor-pointer">
                            VIEW CART
                        </a>
                    </div>

                </div>
            @endif

        </div>

    </div>
</div>
