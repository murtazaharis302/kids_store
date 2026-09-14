<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Breadcrumbs -->
    <nav class="flex text-xs text-slate-400 font-medium space-x-2">
        <a href="{{ route('home') }}" class="hover:text-rose-600 transition">Home</a>
        <span>/</span>
        <a href="{{ route('shop') }}" class="hover:text-rose-600 transition">Shop</a>
        <span>/</span>
        <span class="text-slate-700 font-semibold">Shopping Cart</span>
    </nav>

    <!-- Page Header -->
    <div class="flex items-center justify-between border-b border-slate-200/80 pb-5">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading tracking-tight">
                Shopping Cart
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Review your selected items before proceeding</p>
        </div>
        @if($items->isNotEmpty())
            <button type="button" 
                    wire:click="clearCart" 
                    wire:confirm="Are you sure you want to clear your cart?"
                    class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline transition">
                Clear Cart
            </button>
        @endif
    </div>

    <!-- Flash Message Notification -->
    @if($flashMessage)
        <div class="p-4 rounded-2xl text-xs sm:text-sm font-bold flex items-center justify-between shadow-2xs {{ $flashMessageType === 'success' ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : 'bg-rose-50 border border-rose-200 text-rose-800' }}">
            <div class="flex items-center gap-2">
                @if($flashMessageType === 'success')
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                @else
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @endif
                <span>{{ $flashMessage }}</span>
            </div>
            <button type="button" wire:click="$set('flashMessage', '')" class="opacity-60 hover:opacity-100">&times;</button>
        </div>
    @endif

    <!-- 10-Minute Stock Reservation Banner -->
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
             class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-900 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
                    ⏱️
                </span>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold font-heading">Temporary Stock Reservation (10 Mins)</h4>
                    <p class="text-[11px] sm:text-xs opacity-80">Items in your cart are temporarily reserved for 10 minutes. Complete order within time to secure stock!</p>
                </div>
            </div>
            <div class="shrink-0 bg-white px-3.5 py-1.5 rounded-xl border border-amber-200 shadow-2xs text-center font-mono font-bold text-xs sm:text-sm text-amber-700">
                <span x-text="secondsLeft > 0 ? formatTime(secondsLeft) : '00:00'"></span>
                <span class="text-[10px] text-amber-600 font-sans block font-normal" x-text="secondsLeft > 0 ? 'Reserved' : 'Expired'"></span>
            </div>
        </div>
    @endif

    @if($items->isEmpty())
        <!-- Empty Cart State -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center space-y-6 shadow-xs max-w-lg mx-auto my-8">
            <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mx-auto text-rose-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div class="space-y-2">
                <h2 class="text-xl font-bold text-slate-900 font-heading">Your cart is empty</h2>
                <p class="text-xs sm:text-sm text-slate-500">Looks like you haven't added any adorable outfits to your cart yet.</p>
            </div>
            <a href="{{ route('shop') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-sm shadow-md shadow-rose-500/20 transition">
                Start Shopping
            </a>
        </div>
    @else
        <!-- Main Cart Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Cart Items List (8 Cols) -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden divide-y divide-slate-100">
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

                        <div class="p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
                            <!-- Product Image -->
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-slate-100 border border-slate-200 shrink-0 overflow-hidden relative flex items-center justify-center">
                                @if($hasImg)
                                    <img src="{{ $imgUrl }}" alt="{{ $product->name ?? 'Product' }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-[10px] font-bold text-slate-400">Al Hayat Kids</div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 space-y-1 min-w-0">
                                @if($product)
                                    <a href="{{ route('products.show', $product->slug) }}" class="font-bold text-slate-900 hover:text-rose-600 text-sm sm:text-base transition truncate block font-heading">
                                        {{ $product->name }}
                                    </a>
                                @else
                                    <span class="font-bold text-slate-900 text-sm">Unavailable Product</span>
                                @endif

                                <!-- Variant Details -->
                                @if($variant)
                                    <div class="flex flex-wrap gap-2 text-xs text-slate-500">
                                        @if($variant->color)
                                            <span class="bg-slate-100 px-2 py-0.5 rounded-md text-slate-700 font-medium">
                                                Color: {{ $variant->color->name }}
                                            </span>
                                        @endif
                                        @if($variant->size)
                                            <span class="bg-slate-100 px-2 py-0.5 rounded-md text-slate-700 font-medium">
                                                Size: {{ $variant->size->name }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Unit Price -->
                                <div class="text-xs text-slate-500 pt-1">
                                    Unit Price: <span class="font-semibold text-slate-700">Rs. {{ number_format($item->price, 2) }}</span>
                                </div>
                            </div>

                            <!-- Quantity Selector & Total -->
                            <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                <!-- Stepper -->
                                <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50 overflow-hidden">
                                    <button type="button" 
                                            wire:click="decrementQuantity({{ $item->id }}, {{ $item->quantity }})" 
                                            class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                    </button>
                                    <span class="w-9 text-center text-xs font-bold text-slate-900 font-heading">
                                        {{ $item->quantity }}
                                    </span>
                                    <button type="button" 
                                            wire:click="incrementQuantity({{ $item->id }}, {{ $item->quantity }})" 
                                            @if($item->quantity >= $stock) disabled @endif
                                            class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>

                                <!-- Item Total Price -->
                                <div class="text-right">
                                    <div class="text-sm font-extrabold text-slate-900 font-heading">
                                        Rs. {{ number_format($itemTotal, 2) }}
                                    </div>
                                </div>

                                <!-- Remove Button -->
                                <button type="button" 
                                        wire:click="removeItem({{ $item->id }})" 
                                        title="Remove item"
                                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Order Summary (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 space-y-6 shadow-xs">
                    <h2 class="text-lg font-extrabold text-slate-900 font-heading border-b border-slate-100 pb-4">
                        Order Summary
                    </h2>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-900 font-heading">Rs. {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Estimated Shipping</span>
                            <span class="text-xs text-slate-500 font-medium">Calculated at checkout</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4 flex justify-between items-baseline">
                        <span class="text-base font-extrabold text-slate-900 font-heading">Total</span>
                        <span class="text-2xl font-extrabold text-rose-600 font-heading">Rs. {{ number_format($subtotal, 2) }}</span>
                    </div>

                    <!-- Proceed to Checkout Link -->
                    <div class="space-y-2">
                        @if($items->isNotEmpty())
                            <a href="{{ route('checkout.index') }}" 
                               class="w-full py-4 px-6 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-sm shadow-md shadow-rose-500/20 transition flex items-center justify-center gap-2">
                                <span>Proceed to Checkout</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @else
                            <button type="button" 
                                    disabled 
                                    class="w-full py-4 px-6 rounded-2xl bg-slate-200 text-slate-400 font-extrabold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                                <span>Proceed to Checkout</span>
                            </button>
                        @endif
                    </div>

                    <!-- Continue Shopping Link -->
                    <div class="text-center pt-2">
                        <a href="{{ route('shop') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline">
                            &larr; Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
