<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Top Bar Navigation & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.orders.index') }}" 
               class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Back to Orders Catalog">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold font-heading text-slate-900 tracking-tight">Order #{{ $order->order_number }}</h1>
                    <!-- Order Status Badge -->
                    @switch($order->order_status)
                        @case('pending')
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                            @break
                        @case('confirmed')
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">Confirmed</span>
                            @break
                        @case('processing')
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">Processing</span>
                            @break
                        @case('shipped')
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">Shipped</span>
                            @break
                        @case('delivered')
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Delivered</span>
                            @break
                        @case('cancelled')
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">Cancelled</span>
                            @break
                        @default
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">{{ ucfirst($order->order_status) }}</span>
                    @endswitch
                </div>
                <p class="text-xs text-slate-500 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('message') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details Column (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items Card (Historical Snapshot) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h2 class="font-bold text-slate-900 font-heading text-sm uppercase tracking-wider">Order Items</h2>
                    <span class="text-xs text-slate-400 font-medium">{{ $order->items->count() }} line item(s)</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                        <div class="p-4 sm:p-5 flex items-start sm:items-center justify-between gap-4 hover:bg-slate-50/30 transition">
                            <div class="flex items-start sm:items-center gap-4">
                                <!-- Thumbnail (using product primary image if product exists, or placeholder) -->
                                <div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                                    @if($item->product && $item->product->primaryImage)
                                        <img src="{{ asset('storage/' . $item->product->primaryImage->image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @endif
                                </div>

                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm">{{ $item->product_name }}</h3>
                                    <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-500">
                                        @if($item->sku)
                                            <span class="font-mono text-[11px] bg-slate-100 px-1.5 py-0.5 rounded text-slate-600">SKU: {{ $item->sku }}</span>
                                        @endif
                                        @if($item->size)
                                            <span class="bg-rose-50 text-rose-700 px-1.5 py-0.5 rounded font-semibold text-[11px]">Size: {{ $item->size }}</span>
                                        @endif
                                        @if($item->color)
                                            <span class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded font-semibold text-[11px]">Color: {{ $item->color }}</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1">
                                        Rs. {{ number_format($item->unit_price, 2) }} &times; {{ $item->quantity }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="font-bold text-slate-900 text-sm block">Rs. {{ number_format($item->total, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Financial Totals Summary -->
                <div class="p-5 bg-slate-50/70 border-t border-slate-200/80 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal</span>
                        <span class="font-medium text-slate-800">Rs. {{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-emerald-700 font-medium">
                            <span>Discount</span>
                            <span>- Rs. {{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-slate-600">
                        <span>Shipping Cost</span>
                        <span class="font-medium text-slate-800">Rs. {{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-slate-900 pt-2 border-t border-slate-200">
                        <span>Grand Total</span>
                        <span class="text-rose-600 font-heading text-base">Rs. {{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Notes (if present) -->
            @if($order->customer_notes)
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <h2 class="font-bold text-slate-900 font-heading text-xs uppercase tracking-wider mb-2">Customer Notes</h2>
                    <p class="text-xs text-slate-600 italic bg-amber-50/50 p-3 rounded-xl border border-amber-200/60">
                        &ldquo;{{ $order->customer_notes }}&rdquo;
                    </p>
                </div>
            @endif
        </div>

        <!-- Sidebar Column (1 Col) -->
        <div class="space-y-6">
            <!-- Order & Payment Status Lifecycle Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-5">
                <h2 class="font-bold text-slate-900 font-heading text-sm uppercase tracking-wider pb-3 border-b border-slate-100">
                    Order Status Management
                </h2>

                <form wire:submit.prevent="updateStatuses" class="space-y-4">
                    <!-- Order Status -->
                    <div>
                        <label for="orderStatus" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Order Status
                        </label>
                        <select id="orderStatus" wire:model="orderStatus" 
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-slate-200 bg-slate-50/50 text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition font-medium">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('orderStatus')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Status -->
                    <div>
                        <label for="paymentStatus" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Payment Status
                        </label>
                        <select id="paymentStatus" wire:model="paymentStatus" 
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-slate-200 bg-slate-50/50 text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition font-medium">
                            <option value="pending">Pending</option>
                            <option value="pending_verification">Pending Verification</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                        @error('paymentStatus')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Details & 1-Click Approve Box -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-slate-500">Method:</span>
                            <span class="font-extrabold text-slate-900 uppercase font-mono">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                        </div>

                        @if($order->payment)
                            @if($order->payment->reference_number)
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-slate-500">TRX Reference:</span>
                                    <span class="font-mono font-extrabold text-rose-600 bg-rose-50 px-2 py-0.5 rounded text-[11px]">{{ $order->payment->reference_number }}</span>
                                </div>
                            @endif

                            @if($order->payment->sender_name)
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-slate-500">Sender Name:</span>
                                    <span class="font-bold text-slate-800">{{ $order->payment->sender_name }}</span>
                                </div>
                            @endif

                            @if($order->payment->payment_proof_image)
                                <div class="pt-2 border-t border-slate-200/60 space-y-1">
                                    <span class="font-bold text-slate-700 text-[11px] block uppercase">Customer Screenshot Proof:</span>
                                    <a href="{{ asset('storage/' . $order->payment->payment_proof_image) }}" target="_blank" class="block group relative rounded-xl overflow-hidden border border-slate-200 bg-white p-1">
                                        <img src="{{ asset('storage/' . $order->payment->payment_proof_image) }}" alt="Payment Receipt Screenshot" class="w-full h-32 object-contain rounded-lg">
                                        <span class="text-[10px] font-bold text-rose-600 group-hover:underline text-center block mt-1">🔍 Click to View Full Size</span>
                                    </a>
                                </div>
                            @endif

                            @if($order->payment->payment_notes)
                                <div class="pt-1 text-[11px] text-slate-500 border-t border-slate-200/60">
                                    <strong>Notes:</strong> {{ $order->payment->payment_notes }}
                                </div>
                            @endif
                        @endif

                        @if($order->payment_status !== 'paid')
                            <button type="button" 
                                    wire:click="verifyAndApprovePayment" 
                                    wire:loading.attr="disabled"
                                    class="w-full mt-2 py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-sm transition flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Verify & Mark as Paid</span>
                            </button>
                        @else
                            <div class="p-2 rounded-lg bg-emerald-50 text-emerald-800 font-extrabold text-[11px] text-center border border-emerald-200">
                                ✓ Payment Verified & Received
                            </div>
                        @endif
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 shadow-md shadow-rose-500/20 transition flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="updateStatuses">Update Status</span>
                        <span wire:loading wire:target="updateStatuses" class="inline-flex items-center gap-1">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Saving Changes...
                        </span>
                    </button>
                </form>
            </div>

            <!-- Customer Details Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="font-bold text-slate-900 font-heading text-sm uppercase tracking-wider pb-3 border-b border-slate-100">
                    Customer Information
                </h2>

                @if($order->user)
                    <div class="space-y-3 text-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 font-bold flex items-center justify-center shrink-0 border border-rose-100">
                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm">{{ $order->user->name }}</p>
                                <p class="text-slate-400 text-[11px]">{{ $order->user->email }}</p>
                            </div>
                        </div>

                        @if($order->user->phone)
                            <div class="pt-2 border-t border-slate-100 flex items-center gap-2 text-slate-600">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span>{{ $order->user->phone }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-xs text-slate-500 italic">Guest Checkout Customer</p>
                @endif
            </div>

            <!-- Shipping / Billing Address Note Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <h2 class="font-bold text-slate-900 font-heading text-sm uppercase tracking-wider pb-3 border-b border-slate-100">
                    Shipping & Billing Address
                </h2>

                <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/80 text-amber-800 text-xs space-y-1">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Address snapshot was not preserved for this order.</span>
                    </div>
                </div>

                @if($order->user && $order->user->addresses->isNotEmpty())
                    @php $defaultAddress = $order->user->addresses->firstWhere('is_default', true) ?? $order->user->addresses->first(); @endphp
                    <div class="pt-2 text-xs text-slate-600 space-y-1">
                        <p class="font-bold text-slate-700 text-[11px] uppercase tracking-wider text-slate-400">Customer Current Profile Address:</p>
                        <p class="font-semibold text-slate-800">{{ $defaultAddress->first_name }} {{ $defaultAddress->last_name }}</p>
                        <p>{{ $defaultAddress->address_line_1 }} {{ $defaultAddress->address_line_2 }}</p>
                        <p>{{ $defaultAddress->city }}, {{ $defaultAddress->state }} {{ $defaultAddress->postal_code }}</p>
                        <p>{{ $defaultAddress->country }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Cancellation Confirmation Modal -->
    @if($confirmingCancellation)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 transform transition-all">
                <div class="flex items-center gap-3 text-red-600 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Confirm Order Cancellation</h3>
                        <p class="text-xs text-slate-500">Order #{{ $order->order_number }}</p>
                    </div>
                </div>

                <p class="text-sm text-slate-600 mb-6">
                    Are you sure you want to change this order status to <strong class="text-red-600 font-bold">CANCELLED</strong>? This action will mark the order as cancelled.
                </p>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" wire:click="cancelCancellationPrompt" 
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 border border-slate-200 transition">
                        No, Keep Active
                    </button>
                    <button type="button" wire:click="updateStatuses" wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-red-600 hover:bg-red-700 shadow-md shadow-red-600/20 transition flex items-center gap-2">
                        <span wire:loading.remove wire:target="updateStatuses">Yes, Cancel Order</span>
                        <span wire:loading wire:target="updateStatuses" class="inline-flex items-center gap-1">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Updating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
