<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold font-heading text-slate-900 tracking-tight">Coupons & Discounts</h1>
            <p class="text-xs text-slate-500 mt-1">Manage promo codes, percentage/fixed discounts, usage limits, and validity rules.</p>
        </div>
        <div>
            <a href="{{ route('admin.coupons.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 shadow-md shadow-rose-500/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Create New Coupon</span>
            </a>
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

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Coupons</p>
                <h3 class="text-2xl font-bold font-heading text-slate-900 mt-1">{{ number_format($totalCoupons) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Active Coupons</p>
                <h3 class="text-2xl font-bold font-heading text-emerald-600 mt-1">{{ number_format($activeCoupons) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Expired Coupons</p>
                <h3 class="text-2xl font-bold font-heading text-red-600 mt-1">{{ number_format($expiredCoupons) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Coupon Usages</p>
                <h3 class="text-2xl font-bold font-heading text-indigo-600 mt-1">{{ number_format($totalCouponUsages) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>
    </div>

    <!-- Search & Filters Panel -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Search -->
            <div class="lg:col-span-2 relative">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Search Code</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by coupon code (e.g. WELCOME10)..." 
                           class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-slate-200 bg-slate-50/50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition font-mono uppercase">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Discount Type</label>
                <select wire:model.live="typeFilter" 
                        class="w-full px-3 py-2 rounded-xl text-xs border border-slate-200 bg-slate-50/50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                    <option value="">All Types</option>
                    <option value="percentage">Percentage (%)</option>
                    <option value="fixed">Fixed Amount (PKR)</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status Filter</label>
                <select wire:model.live="statusFilter" 
                        class="w-full px-3 py-2 rounded-xl text-xs border border-slate-200 bg-slate-50/50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                    <option value="">All Statuses</option>
                    <option value="1">Active Only</option>
                    <option value="0">Inactive Only</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-100">
            <div class="flex items-center gap-2">
                <span class="text-[11px] text-slate-400 font-medium">Validity Filter:</span>
                <select wire:model.live="validityFilter" 
                        class="px-2.5 py-1 rounded-lg text-xs border border-slate-200 bg-slate-50/50 text-slate-700">
                    <option value="">All Validity</option>
                    <option value="active">Currently Active</option>
                    <option value="expired">Expired</option>
                    <option value="upcoming">Upcoming</option>
                </select>
            </div>

            @if($search || $typeFilter || $statusFilter !== '' || $validityFilter)
                <button type="button" wire:click="resetFilters" 
                        class="inline-flex items-center gap-1 text-xs text-rose-600 hover:text-rose-700 font-medium px-3 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Clear Filters
                </button>
            @endif
        </div>
    </div>

    <!-- Coupons Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Coupon Code</th>
                        <th class="py-3.5 px-4">Discount Value</th>
                        <th class="py-3.5 px-4">Min. Order Amount</th>
                        <th class="py-3.5 px-4">Max. Discount</th>
                        <th class="py-3.5 px-4 text-center">Usage Limit & Count</th>
                        <th class="py-3.5 px-4">Validity Period</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($coupons as $coupon)
                        @php
                            $isExpired = $coupon->expires_at && $coupon->expires_at->isPast();
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <!-- Code -->
                            <td class="py-3.5 px-4">
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-900 text-amber-400 font-mono font-bold text-xs shadow-xs hover:bg-slate-800 transition">
                                    {{ $coupon->code }}
                                </a>
                            </td>

                            <!-- Value -->
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                @if($coupon->type === 'percentage')
                                    <span class="text-rose-600 font-heading text-sm">{{ number_format($coupon->value, 0) }}% OFF</span>
                                @else
                                    <span class="text-slate-900 font-heading text-sm">Rs. {{ number_format($coupon->value, 2) }}</span>
                                @endif
                            </td>

                            <!-- Min Order Amount -->
                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                @if($coupon->minimum_order_amount > 0)
                                    Rs. {{ number_format($coupon->minimum_order_amount, 2) }}
                                @else
                                    <span class="text-slate-400 italic">No Minimum</span>
                                @endif
                            </td>

                            <!-- Max Discount -->
                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                @if($coupon->maximum_discount > 0)
                                    Rs. {{ number_format($coupon->maximum_discount, 2) }}
                                @else
                                    <span class="text-slate-400 italic">No Cap</span>
                                @endif
                            </td>

                            <!-- Usage Limit & Count -->
                            <td class="py-3.5 px-4 text-center font-medium">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    <strong>{{ $coupon->used_count }}</strong> / {{ $coupon->usage_limit ? number_format($coupon->usage_limit) : '∞' }}
                                </span>
                            </td>

                            <!-- Validity Period -->
                            <td class="py-3.5 px-4 text-slate-600">
                                @if($coupon->starts_at || $coupon->expires_at)
                                    <span class="block text-[11px] font-medium">
                                        {{ $coupon->starts_at ? $coupon->starts_at->format('M d, Y') : 'Immediate' }}
                                        &rarr;
                                        {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'No Expiry' }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Always Valid</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-4 text-center">
                                @if($isExpired)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Expired
                                    </span>
                                @elseif($coupon->status)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Toggle Status Button -->
                                    <button type="button" wire:click="toggleStatus({{ $coupon->id }})" 
                                            class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition" 
                                            title="{{ $coupon->status ? 'Deactivate Coupon' : 'Activate Coupon' }}">
                                        @if($coupon->status)
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </button>

                                    <!-- Edit Link -->
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" 
                                       class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition" title="Edit Coupon">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <!-- Delete / Deactivate Action -->
                                    <button type="button" wire:click="confirmDelete({{ $coupon->id }})" 
                                            class="p-1.5 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition" title="Delete or Deactivate Coupon">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                    <p class="font-medium text-slate-600">No coupons found matching your criteria.</p>
                                    <p class="text-xs text-slate-400 mt-1">Try clearing filters or search term.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>

    <!-- Delete / Deactivate Confirmation Modal -->
    @if($confirmingCouponDeletion)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 transform transition-all">
                <div class="flex items-center gap-3 text-red-600 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">
                            {{ $hasHistoricalUsages ? 'Deactivate Coupon' : 'Delete Coupon' }}
                        </h3>
                        <p class="text-xs text-slate-500">Coupon Code: <span class="font-mono font-bold text-slate-900">{{ $couponToDeleteCode }}</span></p>
                    </div>
                </div>

                @if($hasHistoricalUsages)
                    <div class="mb-4 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs leading-relaxed font-medium">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>
                                This coupon has historical usage records in orders. To preserve financial order integrity, this coupon <strong>will be deactivated</strong> (`status = false`) rather than physically deleted.
                            </span>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-slate-600 mb-6">
                        Are you sure you want to permanently delete coupon <strong class="text-slate-900 font-mono">{{ $couponToDeleteCode }}</strong>? This action cannot be undone.
                    </p>
                @endif

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" wire:click="cancelDelete" 
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 border border-slate-200 transition">
                        Cancel
                    </button>
                    <button type="button" wire:click="deleteCoupon" wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-red-600 hover:bg-red-700 shadow-md shadow-red-600/20 transition flex items-center gap-2">
                        <span wire:loading.remove wire:target="deleteCoupon">
                            {{ $hasHistoricalUsages ? 'Deactivate Coupon' : 'Delete Coupon' }}
                        </span>
                        <span wire:loading wire:target="deleteCoupon" class="inline-flex items-center gap-1">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
