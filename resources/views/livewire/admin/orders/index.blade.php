<div class="space-y-6">
    <!-- Header & Summary Cards Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold font-heading text-slate-900 tracking-tight">Orders Catalog</h1>
            <p class="text-xs text-slate-500 mt-1">Manage customer orders, track fulfillment status, and update payment states.</p>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Orders</p>
                <h3 class="text-2xl font-bold font-heading text-slate-900 mt-1">{{ number_format($totalOrdersCount) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pending Orders</p>
                <h3 class="text-2xl font-bold font-heading text-amber-600 mt-1">{{ number_format($pendingOrdersCount) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Delivered Orders</p>
                <h3 class="text-2xl font-bold font-heading text-emerald-600 mt-1">{{ number_format($deliveredOrdersCount) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Paid Revenue</p>
                <h3 class="text-2xl font-bold font-heading text-slate-900 mt-1">Rs. {{ number_format($totalRevenueSum, 2) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search -->
            <div class="lg:col-span-2 relative">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Search</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Order #, Customer Name or Email..." 
                           class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-slate-200 bg-slate-50/50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Order Status Filter -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Order Status</label>
                <select wire:model.live="orderStatusFilter" 
                        class="w-full px-3 py-2 rounded-xl text-xs border border-slate-200 bg-slate-50/50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                    <option value="">All Order Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Payment Status Filter -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Payment Status</label>
                <select wire:model.live="paymentStatusFilter" 
                        class="w-full px-3 py-2 rounded-xl text-xs border border-slate-200 bg-slate-50/50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                    <option value="">All Payment Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="failed">Failed</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>

            <!-- Payment Method Filter -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Payment Method</label>
                <select wire:model.live="paymentMethodFilter" 
                        class="w-full px-3 py-2 rounded-xl text-xs border border-slate-200 bg-slate-50/50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                    <option value="">All Payment Methods</option>
                    <option value="cod">Cash on Delivery (COD)</option>
                    <option value="card">Credit / Debit Card</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="easypaisa">EasyPaisa</option>
                    <option value="jazzcash">JazzCash</option>
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100">
            <div class="flex items-center gap-3">
                <div>
                    <span class="text-[11px] text-slate-400 font-medium">From Date:</span>
                    <input type="date" wire:model.live="dateFrom" class="ml-1 px-2.5 py-1 rounded-lg text-xs border border-slate-200 bg-slate-50/50 text-slate-700">
                </div>
                <div>
                    <span class="text-[11px] text-slate-400 font-medium">To Date:</span>
                    <input type="date" wire:model.live="dateTo" class="ml-1 px-2.5 py-1 rounded-lg text-xs border border-slate-200 bg-slate-50/50 text-slate-700">
                </div>
            </div>

            @if($search || $orderStatusFilter || $paymentStatusFilter || $paymentMethodFilter || $dateFrom || $dateTo)
                <button type="button" wire:click="resetFilters" 
                        class="inline-flex items-center gap-1 text-xs text-rose-600 hover:text-rose-700 font-medium px-3 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Clear Filters
                </button>
            @endif
        </div>
    </div>

    <!-- Orders Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Order #</th>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">Date</th>
                        <th class="py-3.5 px-4 text-center">Items</th>
                        <th class="py-3.5 px-4 text-right">Total Amount</th>
                        <th class="py-3.5 px-4 text-center">Payment Method</th>
                        <th class="py-3.5 px-4 text-center">Payment Status</th>
                        <th class="py-3.5 px-4 text-center">Order Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition">
                            <!-- Order Number -->
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-rose-600 hover:underline">
                                    {{ $order->order_number }}
                                </a>
                            </td>

                            <!-- Customer -->
                            <td class="py-3.5 px-4">
                                @if($order->user)
                                    <span class="font-semibold text-slate-900 block">{{ $order->user->name }}</span>
                                    <span class="text-[11px] text-slate-400 block">{{ $order->user->email }}</span>
                                @else
                                    <span class="font-medium text-slate-500 italic">Guest Customer</span>
                                @endif
                            </td>

                            <!-- Date -->
                            <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">
                                <span class="block font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                                <span class="text-[10px] text-slate-400">{{ $order->created_at->format('h:i A') }}</span>
                            </td>

                            <!-- Items Count -->
                            <td class="py-3.5 px-4 text-center font-medium">
                                <span class="inline-flex items-center justify-center min-w-6 px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $order->items_count }}
                                </span>
                            </td>

                            <!-- Total -->
                            <td class="py-3.5 px-4 text-right font-bold text-slate-900">
                                Rs. {{ number_format($order->total, 2) }}
                            </td>

                            <!-- Payment Method -->
                            <td class="py-3.5 px-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                <span class="px-2 py-1 rounded bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ str_replace('_', ' ', $order->payment_method) }}
                                </span>
                            </td>

                            <!-- Payment Status Badge -->
                            <td class="py-3.5 px-4 text-center">
                                @switch($order->payment_status)
                                    @case('paid')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                        </span>
                                        @break
                                    @case('pending_verification')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span> Verification Pending
                                        </span>
                                        @break
                                    @case('pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                        </span>
                                        @break
                                    @case('failed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Failed
                                        </span>
                                        @break
                                    @case('refunded')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Refunded
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                @endswitch
                            </td>

                            <!-- Order Status Badge -->
                            <td class="py-3.5 px-4 text-center">
                                @switch($order->order_status)
                                    @case('pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            Pending
                                        </span>
                                        @break
                                    @case('confirmed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            Confirmed
                                        </span>
                                        @break
                                    @case('processing')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            Processing
                                        </span>
                                        @break
                                    @case('shipped')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            Shipped
                                        </span>
                                        @break
                                    @case('delivered')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Delivered
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">
                                            Cancelled
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                @endswitch
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 transition border border-rose-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <p class="font-medium text-slate-600">No orders found matching your search or filters.</p>
                                    <p class="text-xs text-slate-400 mt-1">Try resetting filters or adjusting date ranges.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
