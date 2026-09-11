<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.coupons.index') }}" 
               class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Back to Coupons">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold font-heading text-slate-900 tracking-tight">Edit Coupon: {{ $coupon->code }}</h1>
                    @if($coupon->status)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">Inactive</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Update discount rules, usage caps, and validity dates.</p>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <form wire:submit.prevent="update" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Coupon Code -->
            <div>
                <label for="code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Coupon Code <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="code" wire:model.live="code" {{ $hasUsages ? 'disabled' : '' }} 
                       class="w-full px-4 py-2.5 rounded-xl text-xs border @error('code') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition font-mono uppercase font-bold {{ $hasUsages ? 'bg-slate-100/80 text-slate-500 cursor-not-allowed' : '' }}">
                @if($hasUsages)
                    <p class="text-[11px] text-amber-600 mt-1 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Code is locked because historical orders reference this coupon.
                    </p>
                @else
                    <p class="text-[11px] text-slate-400 mt-1">Automatically converted to uppercase without spaces.</p>
                @endif
                @error('code')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Discount Type -->
            <div>
                <label for="type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Discount Type <span class="text-rose-500">*</span>
                </label>
                <select id="type" wire:model.live="type" 
                        class="w-full px-4 py-2.5 rounded-xl text-xs border @error('type') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition font-medium">
                    <option value="percentage">Percentage Discount (%)</option>
                    <option value="fixed">Fixed Amount Discount (PKR)</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Discount Value -->
            <div>
                <label for="value" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Discount Value <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" step="0.01" id="value" wire:model="value" 
                           class="w-full px-4 py-2.5 rounded-xl text-xs border @error('value') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition font-bold">
                    <span class="absolute right-4 top-2.5 text-xs text-slate-400 font-bold">
                        {{ $type === 'percentage' ? '%' : 'PKR' }}
                    </span>
                </div>
                @error('value')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Minimum Order Amount -->
            <div>
                <label for="minimum_order_amount" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Minimum Order Amount <span class="text-slate-400 font-normal">(PKR)</span>
                </label>
                <input type="number" step="0.01" id="minimum_order_amount" wire:model="minimum_order_amount" placeholder="0.00" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs border @error('minimum_order_amount') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                <p class="text-[11px] text-slate-400 mt-1">Minimum subtotal required to apply coupon. Set 0 for no threshold.</p>
                @error('minimum_order_amount')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Maximum Discount Cap -->
            <div>
                <label for="maximum_discount" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Maximum Discount Cap <span class="text-slate-400 font-normal">(PKR, Optional)</span>
                </label>
                <input type="number" step="0.01" id="maximum_discount" wire:model="maximum_discount" placeholder="Leave empty for uncapped discount" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs border @error('maximum_discount') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                <p class="text-[11px] text-slate-400 mt-1">Maximum allowed discount amount for percentage coupons.</p>
                @error('maximum_discount')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Usage Limit -->
            <div>
                <label for="usage_limit" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Usage Limit <span class="text-slate-400 font-normal">(Total Max Uses, Optional)</span>
                </label>
                <input type="number" id="usage_limit" wire:model="usage_limit" min="1" placeholder="Leave empty for unlimited redemptions" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs border @error('usage_limit') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                <p class="text-[11px] text-slate-400 mt-1">Currently redeemed <strong>{{ $coupon->used_count }}</strong> time(s).</p>
                @error('usage_limit')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Start Date -->
            <div>
                <label for="starts_at" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Start Date & Time <span class="text-slate-400 font-normal">(Optional)</span>
                </label>
                <input type="datetime-local" id="starts_at" wire:model="starts_at" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs border @error('starts_at') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                <p class="text-[11px] text-slate-400 mt-1">Leave empty for immediate activation.</p>
                @error('starts_at')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Expiry Date -->
            <div>
                <label for="expires_at" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Expiry Date & Time <span class="text-slate-400 font-normal">(Optional)</span>
                </label>
                <input type="datetime-local" id="expires_at" wire:model="expires_at" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs border @error('expires_at') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                <p class="text-[11px] text-slate-400 mt-1">Leave empty for no expiration date.</p>
                @error('expires_at')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Status Toggle -->
        <div class="pt-2">
            <label class="inline-flex items-center gap-3 cursor-pointer">
                <input type="checkbox" wire:model="status" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500 border-slate-300">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Active Coupon</span>
            </label>
            <p class="text-[11px] text-slate-400 mt-1 pl-7">Inactive coupons cannot be applied by customers during checkout.</p>
        </div>

        <!-- Action Buttons -->
        <div class="pt-6 border-t border-slate-200/80 flex items-center justify-end gap-3">
            <a href="{{ route('admin.coupons.index') }}" 
               class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 border border-slate-200 transition">
                Cancel
            </a>
            <button type="submit" wire:loading.attr="disabled"
                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 shadow-md shadow-rose-500/20 transition flex items-center gap-2">
                <span wire:loading.remove wire:target="update">Update Coupon</span>
                <span wire:loading wire:target="update" class="inline-flex items-center gap-1">
                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Saving...
                </span>
            </button>
        </div>
    </form>

    <!-- Coupon Usage History Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="font-bold text-slate-900 font-heading text-sm uppercase tracking-wider">Coupon Usage History</h2>
            <span class="text-xs text-slate-400 font-medium">{{ number_format($coupon->used_count) }} Total Redemption(s)</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">Order #</th>
                        <th class="py-3.5 px-4 text-right">Discount Amount</th>
                        <th class="py-3.5 px-4 text-right">Redemption Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($usageHistory as $usage)
                        <tr class="hover:bg-slate-50/50 transition">
                            <!-- Customer -->
                            <td class="py-3.5 px-4">
                                @if($usage->user)
                                    <a href="{{ route('admin.customers.show', $usage->user->id) }}" class="font-bold text-slate-900 hover:text-rose-600 transition">
                                        {{ $usage->user->name }}
                                    </a>
                                    <span class="block text-[11px] text-slate-400">{{ $usage->user->email }}</span>
                                @else
                                    <span class="italic text-slate-500">Guest Customer</span>
                                @endif
                            </td>

                            <!-- Order -->
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                @if($usage->order)
                                    <a href="{{ route('admin.orders.show', $usage->order->id) }}" class="text-rose-600 hover:underline">
                                        {{ $usage->order->order_number }}
                                    </a>
                                @else
                                    <span class="text-slate-400">Order #{{ $usage->order_id }}</span>
                                @endif
                            </td>

                            <!-- Discount Amount -->
                            <td class="py-3.5 px-4 text-right font-bold text-emerald-600">
                                Rs. {{ number_format($usage->discount_amount, 2) }}
                            </td>

                            <!-- Redemption Date -->
                            <td class="py-3.5 px-4 text-right text-slate-600">
                                {{ $usage->created_at->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-slate-400">
                                No historical usage records found for this coupon yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($usageHistory->hasPages())
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $usageHistory->links() }}
            </div>
        @endif
    </div>
</div>
