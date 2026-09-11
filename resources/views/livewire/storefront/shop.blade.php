<div x-data="{ mobileFiltersOpen: false }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Header & Breadcrumbs -->
    <div class="space-y-3">
        <nav class="flex text-xs text-slate-400 font-medium space-x-2">
            <a href="{{ route('home') }}" class="hover:text-rose-600 transition">Home</a>
            <span>/</span>
            <span class="text-slate-700 font-semibold">Shop Catalog</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200/80 pb-6">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                    @if($category)
                        {{ optional(\App\Models\Category::where('slug', $category)->orWhere('id', $category)->first())->name ?? 'Shop Catalog' }}
                    @else
                        Shop All Products
                    @endif
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Showing {{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }}
                </p>
            </div>

            <!-- Controls (Mobile Filter Button & Sort Dropdown) -->
            <div class="flex items-center gap-3">
                <!-- Mobile Filter Button -->
                <button type="button" 
                        @click="mobileFiltersOpen = true" 
                        class="lg:hidden inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-800 shadow-2xs hover:bg-slate-50 transition">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filters</span>
                    @php
                        $activeCount = ($search ? 1 : 0) + ($category ? 1 : 0) + ($age_group ? 1 : 0) + ($size ? 1 : 0) + ($color ? 1 : 0) + ($sale ? 1 : 0) + ($new_arrival ? 1 : 0) + ($min_price !== '' ? 1 : 0) + ($max_price !== '' ? 1 : 0);
                    @endphp
                    @if($activeCount > 0)
                        <span class="w-5 h-5 rounded-full bg-rose-600 text-white text-[10px] flex items-center justify-center font-bold">
                            {{ $activeCount }}
                        </span>
                    @endif
                </button>

                <!-- Sorting Dropdown -->
                <div class="flex items-center gap-2">
                    <label for="sort-select" class="text-xs font-semibold text-slate-500 hidden sm:inline">Sort by:</label>
                    <select id="sort-select" 
                            wire:model.live="sort" 
                            class="text-xs font-bold text-slate-800 bg-white border border-slate-200/80 rounded-xl px-3 py-2.5 shadow-2xs focus:border-rose-500 focus:ring-rose-500 transition cursor-pointer">
                        <option value="featured">Featured</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="newest">Newest Arrivals</option>
                        <option value="name_asc">Alphabetical (A-Z)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Filter Chips Bar -->
    @php
        $hasActiveFilters = $search || $category || $age_group || $size || $color || $sale || $new_arrival || $min_price !== '' || $max_price !== '';
    @endphp

    @if($hasActiveFilters)
        <div class="flex flex-wrap items-center gap-2 bg-rose-50/50 p-3.5 rounded-2xl border border-rose-100/80 text-xs">
            <span class="font-bold text-rose-800 mr-1">Active Filters:</span>

            @if($search)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white border border-rose-200 text-rose-700 font-semibold shadow-2xs">
                    Search: "{{ $search }}"
                    <button type="button" wire:click="removeFilter('search')" class="hover:text-rose-900 font-bold ml-1">×</button>
                </span>
            @endif

            @if($category)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white border border-rose-200 text-rose-700 font-semibold shadow-2xs">
                    Category: {{ optional(\App\Models\Category::where('slug', $category)->orWhere('id', $category)->first())->name ?? $category }}
                    <button type="button" wire:click="removeFilter('category')" class="hover:text-rose-900 font-bold ml-1">×</button>
                </span>
            @endif

            @if($age_group)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white border border-rose-200 text-rose-700 font-semibold shadow-2xs">
                    Age: {{ optional(\App\Models\AgeGroup::where('slug', $age_group)->orWhere('id', $age_group)->first())->name ?? $age_group }}
                    <button type="button" wire:click="removeFilter('age_group')" class="hover:text-rose-900 font-bold ml-1">×</button>
                </span>
            @endif

            @if($size)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white border border-rose-200 text-rose-700 font-semibold shadow-2xs">
                    Size: {{ $size }}
                    <button type="button" wire:click="removeFilter('size')" class="hover:text-rose-900 font-bold ml-1">×</button>
                </span>
            @endif

            @if($color)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white border border-rose-200 text-rose-700 font-semibold shadow-2xs">
                    Color: {{ $color }}
                    <button type="button" wire:click="removeFilter('color')" class="hover:text-rose-900 font-bold ml-1">×</button>
                </span>
            @endif

            @if($sale)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white border border-rose-200 text-rose-700 font-semibold shadow-2xs">
                    On Sale Only
                    <button type="button" wire:click="removeFilter('sale')" class="hover:text-rose-900 font-bold ml-1">×</button>
                </span>
            @endif

            @if($new_arrival)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white border border-rose-200 text-rose-700 font-semibold shadow-2xs">
                    New Arrivals Only
                    <button type="button" wire:click="removeFilter('new_arrival')" class="hover:text-rose-900 font-bold ml-1">×</button>
                </span>
            @endif

            @if($min_price !== '' || $max_price !== '')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white border border-rose-200 text-rose-700 font-semibold shadow-2xs">
                    Price: Rs. {{ $min_price ?: '0' }} - Rs. {{ $max_price ?: 'Any' }}
                    <button type="button" wire:click="removeFilter('min_price'); $wire.removeFilter('max_price')" class="hover:text-rose-900 font-bold ml-1">×</button>
                </span>
            @endif

            <button type="button" wire:click="clearFilters" class="text-xs font-bold text-rose-600 hover:text-rose-800 underline ml-auto">
                Clear All Filters
            </button>
        </div>
    @endif

    <!-- Main Layout (Sidebar + Product Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- DESKTOP FILTER SIDEBAR -->
        <aside class="hidden lg:block space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 space-y-6 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h2 class="text-base font-extrabold text-slate-900 font-heading flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter Products
                    </h2>
                    @if($hasActiveFilters)
                        <button type="button" wire:click="clearFilters" class="text-xs font-bold text-rose-600 hover:text-rose-700">
                            Reset
                        </button>
                    @endif
                </div>

                <!-- Search Input -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-700 font-heading uppercase tracking-wider">Search</label>
                    <div class="relative">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search" 
                               placeholder="Search product, SKU..." 
                               class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 focus:bg-white focus:border-rose-500 focus:ring-rose-500">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="space-y-2 border-t border-slate-100 pt-4">
                    <label class="text-xs font-bold text-slate-700 font-heading uppercase tracking-wider">Category</label>
                    <div class="space-y-1 max-h-52 overflow-y-auto pr-1">
                        <button type="button" 
                                wire:click="$set('category', '')" 
                                class="w-full text-left px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center justify-between {{ $category === '' ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <span>All Categories</span>
                        </button>
                        @foreach($categories as $cat)
                            <div class="space-y-1">
                                <button type="button" 
                                        wire:click="$set('category', '{{ $cat->slug }}')" 
                                        class="w-full text-left px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center justify-between {{ $category === $cat->slug ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                                    <span>{{ $cat->name }}</span>
                                </button>

                                @if($cat->children->isNotEmpty())
                                    <div class="pl-4 space-y-1 border-l-2 border-slate-100 ml-3">
                                        @foreach($cat->children as $child)
                                            <button type="button" 
                                                    wire:click="$set('category', '{{ $child->slug }}')" 
                                                    class="w-full text-left px-2 py-1 rounded-md text-[11px] font-medium transition flex items-center justify-between {{ $category === $child->slug ? 'text-rose-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                                                <span>{{ $child->name }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Age Group Filter -->
                @if($ageGroups->isNotEmpty())
                    <div class="space-y-2 border-t border-slate-100 pt-4">
                        <label class="text-xs font-bold text-slate-700 font-heading uppercase tracking-wider">Age Group</label>
                        <div class="space-y-1 max-h-48 overflow-y-auto pr-1">
                            <button type="button" 
                                    wire:click="$set('age_group', '')" 
                                    class="w-full text-left px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $age_group === '' ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                                All Ages
                            </button>
                            @foreach($ageGroups as $ag)
                                <button type="button" 
                                        wire:click="$set('age_group', '{{ $ag->slug }}')" 
                                        class="w-full text-left px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center justify-between {{ $age_group === $ag->slug ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                                    <span>{{ $ag->name }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Size Filter -->
                @if($sizes->isNotEmpty())
                    <div class="space-y-2 border-t border-slate-100 pt-4">
                        <label class="text-xs font-bold text-slate-700 font-heading uppercase tracking-wider">Size</label>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($sizes as $sz)
                                <button type="button" 
                                        wire:click="$set('size', '{{ $size === $sz->name ? '' : $sz->name }}')" 
                                        class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition {{ $size === $sz->name ? 'bg-rose-600 text-white border-rose-600 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:border-rose-300 hover:bg-rose-50/40' }}">
                                    {{ $sz->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Color Filter -->
                @if($colors->isNotEmpty())
                    <div class="space-y-2 border-t border-slate-100 pt-4">
                        <label class="text-xs font-bold text-slate-700 font-heading uppercase tracking-wider">Color</label>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($colors as $clr)
                                <button type="button" 
                                        wire:click="$set('color', '{{ $color === $clr->name ? '' : $clr->name }}')" 
                                        class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1.5 {{ $color === $clr->name ? 'bg-slate-900 text-white border-slate-900 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:border-slate-300' }}">
                                    @if($clr->hex_code)
                                        <span class="w-2.5 h-2.5 rounded-full border border-black/20" style="background-color: {{ $clr->hex_code }}"></span>
                                    @endif
                                    <span>{{ $clr->name }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Price Range Filter -->
                <div class="space-y-2 border-t border-slate-100 pt-4">
                    <label class="text-xs font-bold text-slate-700 font-heading uppercase tracking-wider">Price Range (PKR)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" 
                               wire:model.live.debounce.500ms="min_price" 
                               placeholder="Min" 
                               class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:bg-white focus:border-rose-500">
                        <input type="number" 
                               wire:model.live.debounce.500ms="max_price" 
                               placeholder="Max" 
                               class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:bg-white focus:border-rose-500">
                    </div>
                </div>

                <!-- Toggles (Sale / New) -->
                <div class="space-y-3 border-t border-slate-100 pt-4 text-xs font-semibold text-slate-700">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" wire:model.live="sale" class="rounded text-rose-600 focus:ring-rose-500 w-4 h-4 border-slate-300">
                        <span>On Sale Only</span>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" wire:model.live="new_arrival" class="rounded text-rose-600 focus:ring-rose-500 w-4 h-4 border-slate-300">
                        <span>New Arrivals Only</span>
                    </label>
                </div>
            </div>
        </aside>

        <!-- PRODUCT GRID & MAIN CONTENT -->
        <main class="lg:col-span-3 space-y-6">
            @if($products->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <x-storefront.product-card :product="$product" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-6">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 text-center">
                    <x-storefront.empty-state 
                        title="No Products Found" 
                        description="We couldn't find any products matching your selected search or filter criteria." 
                        actionText="Clear All Filters"
                        actionUrl="#" />
                    <div class="mt-4">
                        <button type="button" 
                                wire:click="clearFilters" 
                                class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                            Reset Filters
                        </button>
                    </div>
                </div>
            @endif
        </main>
    </div>

    <!-- MOBILE FILTER DRAWER -->
    <div x-show="mobileFiltersOpen" 
         x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex justify-end lg:hidden">
        
        <div @click.outside="mobileFiltersOpen = false" 
             class="w-full max-w-xs bg-white h-full shadow-2xl p-6 overflow-y-auto space-y-6 flex flex-col justify-between">
            <div class="space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h2 class="text-base font-extrabold text-slate-900 font-heading">Filter Products</h2>
                    <button type="button" @click="mobileFiltersOpen = false" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Search Input Mobile -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-700 font-heading uppercase tracking-wider">Search</label>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Search..." 
                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                </div>

                <!-- Category Filter Mobile -->
                <div class="space-y-2 border-t border-slate-100 pt-4">
                    <label class="text-xs font-bold text-slate-700 font-heading uppercase tracking-wider">Category</label>
                    <select wire:model.live="category" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                            @foreach($cat->children as $child)
                                <option value="{{ $child->slug }}">&nbsp;&nbsp;-- {{ $child->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <!-- Age Group Mobile -->
                @if($ageGroups->isNotEmpty())
                    <div class="space-y-2 border-t border-slate-100 pt-4">
                        <label class="text-xs font-bold text-slate-700 font-heading uppercase tracking-wider">Age Group</label>
                        <select wire:model.live="age_group" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                            <option value="">All Ages</option>
                            @foreach($ageGroups as $ag)
                                <option value="{{ $ag->slug }}">{{ $ag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Toggles Mobile -->
                <div class="space-y-3 border-t border-slate-100 pt-4 text-xs font-semibold text-slate-700">
                    <label class="flex items-center gap-2.5">
                        <input type="checkbox" wire:model.live="sale" class="rounded text-rose-600">
                        <span>On Sale Only</span>
                    </label>
                    <label class="flex items-center gap-2.5">
                        <input type="checkbox" wire:model.live="new_arrival" class="rounded text-rose-600">
                        <span>New Arrivals Only</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                <button type="button" wire:click="clearFilters" class="w-1/2 py-2.5 text-xs font-bold text-slate-700 bg-slate-100 rounded-xl">Reset</button>
                <button type="button" @click="mobileFiltersOpen = false" class="w-1/2 py-2.5 text-xs font-bold text-white bg-rose-600 rounded-xl">Apply</button>
            </div>
        </div>
    </div>
</div>
