<div class="space-y-6">
    <!-- Top Action & Navigation Bar -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-slate-900 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold font-heading text-slate-900">Add New Product</h1>
                <p class="text-xs text-slate-500">Create a new garment item with variants and images.</p>
            </div>
        </div>

        <button wire:click="save" 
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-semibold text-xs shadow-md shadow-rose-500/20 hover:from-rose-600 hover:to-pink-700 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Save & Publish</span>
        </button>
    </div>

    <!-- Main Grid Form -->
    <form wire:submit.prevent="save" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column (8 cols): Basic Info, Variants, Descriptions -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="text-base font-bold font-heading text-slate-900 border-b border-slate-100 pb-3">Basic Information</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Product Name *</label>
                        <input type="text" wire:model.live="name" placeholder="e.g. Teddy Bear Knitted Sweater" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                        @error('name') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">SKU (Stock Keeping Unit) *</label>
                        <input type="text" wire:model="sku" placeholder="e.g. AH-TEDDY-SW" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                        @error('sku') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Slug (URL) *</label>
                        <input type="text" wire:model="slug" placeholder="teddy-bear-knitted-sweater" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                        @error('slug') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Category *</label>
                        <select wire:model="category_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Short Summary / Teaser</label>
                    <textarea wire:model="short_description" rows="2" placeholder="Brief 1-2 sentence overview of the garment..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 focus:border-rose-500"></textarea>
                    @error('short_description') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Detailed Description & Care Instructions</label>
                    <textarea wire:model="description" rows="4" placeholder="Fabric composition, washing instructions, fit details..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 focus:border-rose-500"></textarea>
                    @error('description') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Product Variants Matrix Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-base font-bold font-heading text-slate-900">Product Variants (Size & Color Matrix)</h2>
                        <p class="text-xs text-slate-500">Add size and color combinations with stock inventory.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" wire:click="generateVariantSKUs" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                            Auto-Fill SKUs
                        </button>
                        <button type="button" wire:click="addVariant" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition border border-rose-200/60">
                            + Add Variant
                        </button>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach($variants as $index => $variant)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 grid grid-cols-1 sm:grid-cols-6 gap-3 items-center">
                            <!-- Size -->
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Size</label>
                                <select wire:model="variants.{{ $index }}.size_id" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                                    <option value="">No Size</option>
                                    @foreach($sizes as $sz)
                                        <option value="{{ $sz->id }}">{{ $sz->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Color -->
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Color</label>
                                <select wire:model="variants.{{ $index }}.color_id" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                                    <option value="">No Color</option>
                                    @foreach($colors as $cl)
                                        <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- SKU -->
                            <div class="sm:col-span-2">
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Variant SKU *</label>
                                <input type="text" wire:model="variants.{{ $index }}.sku" placeholder="SKU" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                                @error("variants.{$index}.sku") <span class="text-rose-500 text-[10px] block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Stock -->
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Stock *</label>
                                <input type="number" wire:model="variants.{{ $index }}.stock_quantity" min="0" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs font-bold">
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-end pt-3 sm:pt-0">
                                <button type="button" wire:click="removeVariant({{ $index }})" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition" title="Remove Variant">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Images Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="text-base font-bold font-heading text-slate-900 border-b border-slate-100 pb-3">Product Media & Gallery</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Primary Cover Image -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Primary Cover Image</label>
                        <input type="file" wire:model="primaryImage" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100">
                        @error('primaryImage') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror

                        @if ($primaryImage)
                            <div class="mt-2 w-24 h-24 rounded-xl border border-slate-200 overflow-hidden">
                                <img src="{{ $primaryImage->temporaryUrl() }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                    </div>

                    <!-- Gallery Images -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Additional Gallery Images</label>
                        <input type="file" wire:model="additionalImages" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                        @error('additionalImages.*') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror

                        @if ($additionalImages)
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($additionalImages as $img)
                                    <div class="w-16 h-16 rounded-xl border border-slate-200 overflow-hidden">
                                        <img src="{{ $img->temporaryUrl() }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (4 cols): Pricing, Status & Taxonomies -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Pricing Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="text-base font-bold font-heading text-slate-900 border-b border-slate-100 pb-3">Pricing</h2>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Base Regular Price (PKR) *</label>
                    <input type="number" step="0.01" wire:model="price" placeholder="2499.00" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-rose-500">
                    @error('price') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Sale Discount Price (PKR)</label>
                    <input type="number" step="0.01" wire:model="sale_price" placeholder="1999.00" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-rose-600 focus:ring-2 focus:ring-rose-500">
                    @error('sale_price') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Cost Price (Internal)</label>
                    <input type="number" step="0.01" wire:model="cost_price" placeholder="1100.00" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500">
                    @error('cost_price') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Status & Flags Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="text-base font-bold font-heading text-slate-900 border-b border-slate-100 pb-3">Status & Visibility</h2>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" wire:model="status" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500 border-slate-300">
                    <span class="text-xs font-semibold text-slate-800">Active (Visible in Storefront)</span>
                </label>

                <div class="pt-2 border-t border-slate-100 space-y-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="featured" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500 border-slate-300">
                        <span class="text-xs font-medium text-slate-700">Featured Product</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="new_arrival" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500 border-slate-300">
                        <span class="text-xs font-medium text-slate-700">New Arrival</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="is_sale" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500 border-slate-300">
                        <span class="text-xs font-medium text-slate-700">On Sale Badge</span>
                    </label>
                </div>
            </div>

            <!-- Age Groups & Marketing Collections -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="text-base font-bold font-heading text-slate-900 border-b border-slate-100 pb-3">Target Age & Collections</h2>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Age Groups</label>
                    <div class="max-h-36 overflow-y-auto space-y-1.5 p-2 bg-slate-50 rounded-xl border border-slate-200/80">
                        @foreach($ageGroups as $ag)
                            <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-700">
                                <input type="checkbox" value="{{ $ag->id }}" wire:model="selectedAgeGroups" class="w-3.5 h-3.5 rounded text-rose-600">
                                <span>{{ $ag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Marketing Collections</label>
                    <div class="max-h-36 overflow-y-auto space-y-1.5 p-2 bg-slate-50 rounded-xl border border-slate-200/80">
                        @foreach($collections as $coll)
                            <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-700">
                                <input type="checkbox" value="{{ $coll->id }}" wire:model="selectedCollections" class="w-3.5 h-3.5 rounded text-rose-600">
                                <span>{{ $coll->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
