<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.coupons.index') }}" 
               class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Back to Coupons">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold font-heading text-slate-900 tracking-tight">Create New Coupon</h1>
                <p class="text-xs text-slate-500 mt-0.5">Configure promo code rules, discount values, minimum spend, caps, and validity dates.</p>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <form wire:submit.prevent="save" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Coupon Code -->
            <div>
                <label for="code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Coupon Code <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="code" wire:model.live="code" placeholder="e.g. SUMMER15, EID2026" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs border @error('code') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition font-mono uppercase font-bold">
                <p class="text-[11px] text-slate-400 mt-1">Automatically converted to uppercase without spaces.</p>
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
                    <input type="number" step="0.01" id="value" wire:model="value" placeholder="{{ $type === 'percentage' ? 'e.g. 15 (for 15%)' : 'e.g. 500 (for Rs. 500)' }}" 
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
                <p class="text-[11px] text-slate-400 mt-1">Total maximum times this coupon can be redeemed storewide.</p>
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
                <p class="text-[11px] text-slate-400 mt-1">Leave empty to activate immediately.</p>
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
                <span wire:loading.remove wire:target="save">Create Coupon</span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-1">
                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Saving...
                </span>
            </button>
        </div>
    </form>
</div>
