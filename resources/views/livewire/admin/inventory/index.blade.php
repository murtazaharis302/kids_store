<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Inventory & Stock Management</h1>
            <p class="text-sm text-slate-500 mt-1">Monitor product variant inventory, track stock thresholds, and safely apply stock adjustments.</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Variants -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Variants</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1 font-heading">{{ number_format($totalVariants) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>

        <!-- Total Stock Units -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Stock Units</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1 font-heading">{{ number_format($totalStockUnits) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v2a2 2 0 01-2 2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
            </div>
        </div>

        <!-- In Stock Variants -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">In Stock (>15)</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1 font-heading">{{ number_format($inStockCount) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Low Stock Variants -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Low Stock (1-15)</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1 font-heading">{{ number_format($lowStockCount) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>

        <!-- Out of Stock Variants -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Out of Stock (<=0)</p>
                <h3 class="text-2xl font-bold text-rose-600 mt-1 font-heading">{{ number_format($outOfStockCount) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Search & Filters Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search -->
            <div class="relative">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search by product name or SKU..." 
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Category Filter -->
            <div>
                <select wire:model.live="categoryFilter" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 text-slate-700">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Size Filter -->
            <div>
                <select wire:model.live="sizeFilter" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 text-slate-700">
                    <option value="">All Sizes</option>
                    @foreach($sizes as $size)
                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Color Filter -->
            <div>
                <select wire:model.live="colorFilter" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 text-slate-700">
                    <option value="">All Colors</option>
                    @foreach($colors as $color)
                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Stock Status Filter -->
            <div>
                <select wire:model.live="stockStatusFilter" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 text-slate-700">
                    <option value="">All Stock Statuses</option>
                    <option value="in_stock">In Stock (>15)</option>
                    <option value="low_stock">Low Stock (1-15)</option>
                    <option value="out_of_stock">Out of Stock (<=0)</option>
                </select>
            </div>
        </div>

        @if($search !== '' || $categoryFilter !== '' || $sizeFilter !== '' || $colorFilter !== '' || $stockStatusFilter !== '')
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                <span class="text-slate-500">Showing filtered stock results</span>
                <button wire:click="resetFilters" class="text-rose-600 font-semibold hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset Filters
                </button>
            </div>
        @endif
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">SKU</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Size</th>
                        <th class="px-6 py-4">Color</th>
                        <th class="px-6 py-4 text-center">Current Stock</th>
                        <th class="px-6 py-4">Stock Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($variants as $variant)
                        <tr class="hover:bg-slate-50/70 transition">
                            <!-- Product Name & Image -->
                            <td class="px-6 py-4">
                                @if($variant->product)
                                    <div class="flex items-center gap-3">
                                        @if($variant->product->primaryImage && $variant->product->primaryImage->image_path)
                                            <img src="{{ asset('storage/' . $variant->product->primaryImage->image_path) }}" 
                                                 alt="{{ $variant->product->name }}" 
                                                 class="w-10 h-10 rounded-lg object-cover border border-slate-200 shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0 text-slate-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('admin.products.edit', $variant->product) }}" class="font-bold text-slate-900 hover:text-rose-600 transition truncate block max-w-[200px]">
                                                {{ $variant->product->name }}
                                            </a>
                                            <span class="text-xs text-slate-400">Rs. {{ number_format($variant->price ?? $variant->product->price, 2) }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="italic text-slate-400 text-xs">Product no longer available</span>
                                @endif
                            </td>

                            <!-- SKU -->
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-700">
                                {{ $variant->sku }}
                            </td>

                            <!-- Category -->
                            <td class="px-6 py-4 text-xs font-medium text-slate-600">
                                {{ $variant->product && $variant->product->category ? $variant->product->category->name : 'N/A' }}
                            </td>

                            <!-- Size -->
                            <td class="px-6 py-4 text-xs font-semibold text-slate-800">
                                {{ $variant->size ? $variant->size->name : 'Default' }}
                            </td>

                            <!-- Color -->
                            <td class="px-6 py-4 text-xs">
                                @if($variant->color)
                                    <div class="flex items-center gap-1.5">
                                        @if($variant->color->hex_code)
                                            <span class="w-3.5 h-3.5 rounded-full border border-slate-300 shadow-2xs inline-block" style="background-color: {{ $variant->color->hex_code }};"></span>
                                        @endif
                                        <span class="font-semibold text-slate-800">{{ $variant->color->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Default</span>
                                @endif
                            </td>

                            <!-- Current Stock -->
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="text-base font-bold font-heading {{ $variant->stock_quantity <= 0 ? 'text-rose-600' : ($variant->stock_quantity <= 15 ? 'text-amber-600' : 'text-slate-900') }}">
                                    {{ number_format($variant->stock_quantity) }}
                                </span>
                            </td>

                            <!-- Stock Status Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($variant->stock_quantity > 15)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        In Stock
                                    </span>
                                @elseif($variant->stock_quantity > 0)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                        Low Stock
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                        Out of Stock
                                    </span>
                                @endif
                            </td>

                            <!-- Action -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <button wire:click="openAdjustmentModal({{ $variant->id }})" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 rounded-xl font-semibold text-xs transition border border-slate-200 hover:border-rose-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    Adjust Stock
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    <p class="text-base font-semibold text-slate-700">No inventory variants found</p>
                                    <p class="text-xs text-slate-500 mt-1">Try adjusting search parameters or clearing filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($variants->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $variants->links() }}
            </div>
        @endif
    </div>

    <!-- Stock Adjustment Confirmation Modal -->
    @if($confirmingStockAdjustment && $selectedVariantForAdjustment)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="cancelAdjustment"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-200">
                    <div class="bg-slate-900 px-6 py-4 text-white flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold font-heading">Stock Adjustment</h3>
                            <p class="text-xs text-slate-400 font-mono mt-0.5">SKU: {{ $selectedVariantForAdjustment->sku }}</p>
                        </div>
                        <button wire:click="cancelAdjustment" class="text-slate-400 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Variant Context -->
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-xs space-y-1">
                            <p class="font-bold text-slate-900 text-sm">
                                {{ $selectedVariantForAdjustment->product ? $selectedVariantForAdjustment->product->name : 'Variant #' . $selectedVariantForAdjustment->id }}
                            </p>
                            <div class="flex items-center gap-3 text-slate-500 pt-1">
                                <span>Size: <strong class="text-slate-800">{{ $selectedVariantForAdjustment->size ? $selectedVariantForAdjustment->size->name : 'Default' }}</strong></span>
                                <span>Color: <strong class="text-slate-800">{{ $selectedVariantForAdjustment->color ? $selectedVariantForAdjustment->color->name : 'Default' }}</strong></span>
                            </div>
                        </div>

                        <!-- Current Stock Display -->
                        <div class="flex items-center justify-between p-3.5 bg-slate-100/80 rounded-xl border border-slate-200">
                            <span class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Current DB Stock:</span>
                            <span class="text-xl font-bold font-heading text-slate-900">
                                {{ number_format($selectedVariantForAdjustment->stock_quantity) }}
                            </span>
                        </div>

                        <!-- Adjustment Value Input -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Stock Adjustment (+/- Integer)
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       step="1"
                                       wire:model.live="adjustmentAmount" 
                                       placeholder="e.g. 10 or -5"
                                       class="w-full py-2.5 px-4 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                            </div>
                            @error('adjustmentAmount')
                                <p class="text-xs font-medium text-rose-600 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quick Adjustment Buttons -->
                        <div class="space-y-1.5">
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Quick Presets:</span>
                            <div class="flex flex-wrap gap-1.5">
                                <button type="button" wire:click="$set('adjustmentAmount', 5)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold">+5</button>
                                <button type="button" wire:click="$set('adjustmentAmount', 10)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold">+10</button>
                                <button type="button" wire:click="$set('adjustmentAmount', 25)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold">+25</button>
                                <button type="button" wire:click="$set('adjustmentAmount', -5)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold">-5</button>
                                <button type="button" wire:click="$set('adjustmentAmount', -10)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold">-10</button>
                            </div>
                        </div>

                        <!-- Calculation Preview -->
                        @php
                            $calculatedResult = (int)$selectedVariantForAdjustment->stock_quantity + (int)$adjustmentAmount;
                        @endphp
                        <div class="p-3.5 rounded-xl border {{ $calculatedResult < 0 ? 'bg-rose-50 border-rose-200 text-rose-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }}">
                            <div class="flex justify-between items-center text-xs font-medium">
                                <span>Estimated Resulting Stock:</span>
                                <span class="text-base font-bold font-heading">
                                    {{ $selectedVariantForAdjustment->stock_quantity }} 
                                    {{ (int)$adjustmentAmount >= 0 ? '+' : '' }}{{ (int)$adjustmentAmount }} 
                                    = {{ $calculatedResult }}
                                </span>
                            </div>
                            @if($calculatedResult < 0)
                                <p class="text-[11px] font-semibold text-rose-600 mt-1">Warning: Resulting stock cannot be negative.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="bg-slate-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3">
                        <button type="button" 
                                wire:click="applyStockAdjustment" 
                                @if($calculatedResult < 0 || (int)$adjustmentAmount === 0) disabled @endif
                                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-xs px-4 py-2.5 bg-rose-600 text-base font-semibold text-white hover:bg-rose-700 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none sm:w-auto sm:text-sm transition">
                            Confirm & Apply Stock
                        </button>
                        <button type="button" 
                                wire:click="cancelAdjustment" 
                                class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-xs px-4 py-2.5 bg-white text-base font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
