<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.customers.index') }}" 
               class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Back to Customers Catalog">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold font-heading text-slate-900 tracking-tight">{{ $user->name }}</h1>
                    @if($user->status)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Active Customer</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">Inactive Customer</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-1">Customer Profile &bull; Registered on {{ $user->created_at->format('F d, Y') }}</p>
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

    <!-- Statistics Overview Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Orders</p>
            <h3 class="text-xl font-bold font-heading text-slate-900 mt-1">{{ number_format($totalOrdersCount) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Spent</p>
            <h3 class="text-xl font-bold font-heading text-rose-600 mt-1">Rs. {{ number_format($totalSpent, 2) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pending Orders</p>
            <h3 class="text-xl font-bold font-heading text-amber-600 mt-1">{{ number_format($pendingOrdersCount) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Delivered</p>
            <h3 class="text-xl font-bold font-heading text-emerald-600 mt-1">{{ number_format($deliveredOrdersCount) }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Cancelled</p>
            <h3 class="text-xl font-bold font-heading text-red-600 mt-1">{{ number_format($cancelledOrdersCount) }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile & Status Column (1 Col) -->
        <div class="space-y-6">
            <!-- Profile Info Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="font-bold text-slate-900 font-heading text-sm uppercase tracking-wider pb-3 border-b border-slate-100">
                    Profile Information
                </h2>

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center font-bold text-lg shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">{{ $user->name }}</h3>
                        <p class="text-xs text-slate-400">Role: Customer</p>
                    </div>
                </div>

                <div class="space-y-3 pt-2 text-xs divide-y divide-slate-100">
                    <div class="pt-2 flex justify-between">
                        <span class="text-slate-400 font-medium">Email Address:</span>
                        <span class="font-semibold text-slate-800">{{ $user->email }}</span>
                    </div>
                    <div class="pt-2 flex justify-between">
                        <span class="text-slate-400 font-medium">Phone Number:</span>
                        <span class="font-mono font-semibold text-slate-800">{{ $user->phone ?: 'N/A' }}</span>
                    </div>
                    <div class="pt-2 flex justify-between">
                        <span class="text-slate-400 font-medium">Registered Date:</span>
                        <span class="font-semibold text-slate-800">{{ $user->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- Account Status Management Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="font-bold text-slate-900 font-heading text-sm uppercase tracking-wider pb-3 border-b border-slate-100">
                    Account Status Management
                </h2>

                <form wire:submit.prevent="updateStatus" class="space-y-4">
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Customer Account Status
                        </label>
                        <select id="status" wire:model="status" 
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-slate-200 bg-slate-50/50 text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition font-medium">
                            <option value="1">Active Account</option>
                            <option value="0">Inactive / Suspended Account</option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">Inactive customers cannot place new store orders.</p>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 shadow-md shadow-rose-500/20 transition flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="updateStatus">Save Account Status</span>
                        <span wire:loading wire:target="updateStatus" class="inline-flex items-center gap-1">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Updating...
                        </span>
                    </button>
                </form>
            </div>

            <!-- Saved Customer Addresses Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="font-bold text-slate-900 font-heading text-sm uppercase tracking-wider pb-3 border-b border-slate-100">
                    Saved Customer Addresses
                </h2>
                <p class="text-[11px] text-slate-400">Saved profile addresses for future checkouts. Historical order addresses are stored independently per order.</p>

                <div class="space-y-3">
                    @forelse($savedAddresses as $addr)
                        <div class="p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/50 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900">{{ $addr->first_name }} {{ $addr->last_name }}</span>
                                @if($addr->is_default)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100 uppercase">Default</span>
                                @endif
                            </div>
                            <p class="text-slate-600">{{ $addr->address_line_1 }} {{ $addr->address_line_2 }}</p>
                            <p class="text-slate-600">{{ $addr->city }}, {{ $addr->state }} {{ $addr->postal_code }}</p>
                            <p class="text-slate-500 font-medium">{{ $addr->country }} &bull; Phone: {{ $addr->phone }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 italic p-3 rounded-xl bg-slate-50 border border-slate-100">
                            No saved profile addresses found for this customer.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Order History Column (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h2 class="font-bold text-slate-900 font-heading text-sm uppercase tracking-wider">Customer Order History</h2>
                    <span class="text-xs text-slate-400 font-medium">{{ number_format($totalOrdersCount) }} Total Order(s)</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="py-3.5 px-4">Order #</th>
                                <th class="py-3.5 px-4">Date</th>
                                <th class="py-3.5 px-4 text-center">Order Status</th>
                                <th class="py-3.5 px-4 text-center">Payment Status</th>
                                <th class="py-3.5 px-4 text-right">Total</th>
                                <th class="py-3.5 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($orders as $ord)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <!-- Order Number -->
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                        <a href="{{ route('admin.orders.show', $ord->id) }}" class="text-rose-600 hover:underline">
                                            {{ $ord->order_number }}
                                        </a>
                                    </td>

                                    <!-- Date -->
                                    <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">
                                        {{ $ord->created_at->format('M d, Y') }}
                                    </td>

                                    <!-- Order Status Badge -->
                                    <td class="py-3.5 px-4 text-center">
                                        @switch($ord->order_status)
                                            @case('pending')
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                                                @break
                                            @case('confirmed')
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">Confirmed</span>
                                                @break
                                            @case('processing')
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">Processing</span>
                                                @break
                                            @case('shipped')
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">Shipped</span>
                                                @break
                                            @case('delivered')
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Delivered</span>
                                                @break
                                            @case('cancelled')
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">Cancelled</span>
                                                @break
                                            @default
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">{{ ucfirst($ord->order_status) }}</span>
                                        @endswitch
                                    </td>

                                    <!-- Payment Status Badge -->
                                    <td class="py-3.5 px-4 text-center">
                                        @switch($ord->payment_status)
                                            @case('paid')
                                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Paid</span>
                                                @break
                                            @case('pending')
                                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                                                @break
                                            @case('failed')
                                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">Failed</span>
                                                @break
                                            @case('refunded')
                                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">Refunded</span>
                                                @break
                                            @default
                                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">{{ ucfirst($ord->payment_status) }}</span>
                                        @endswitch
                                    </td>

                                    <!-- Total -->
                                    <td class="py-3.5 px-4 text-right font-bold text-slate-900">
                                        Rs. {{ number_format($ord->total, 2) }}
                                    </td>

                                    <!-- View Action -->
                                    <td class="py-3.5 px-4 text-right">
                                        <a href="{{ route('admin.orders.show', $ord->id) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 transition border border-rose-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            View Order
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-10 text-center text-slate-400">
                                        No order history found for this customer.
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
    </div>
</div>
