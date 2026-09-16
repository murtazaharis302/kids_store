<div class="space-y-6">
    <!-- Top Action & Navigation Bar -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-slate-900 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold font-heading text-slate-900">Edit Product: {{ $product->name }}</h1>
                <p class="text-xs text-slate-500">Update product attributes, prices, image gallery & variants.</p>
            </div>
        </div>

        <button wire:click="save" 
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-semibold text-xs shadow-md shadow-rose-500/20 hover:from-rose-600 hover:to-pink-700 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Update Changes</span>
        </button>
    </div>

    <!-- Main Grid Form -->
    <form wire:submit.prevent="save" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column (8 cols): Basic Info, Variants, Descriptions, Images -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="text-base font-bold font-heading text-slate-900 border-b border-slate-100 pb-3">Basic Information</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Product Name *</label>
                        <input type="text" wire:model.live="name" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                        @error('name') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">SKU *</label>
                        <input type="text" wire:model="sku" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                        @error('sku') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Slug *</label>
                        <input type="text" wire:model="slug" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
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
                    <textarea wire:model="short_description" rows="2" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 focus:border-rose-500"></textarea>
                    @error('short_description') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Detailed Description & Care Instructions</label>
                    <textarea wire:model="description" rows="4" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 focus:border-rose-500"></textarea>
                    @error('description') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Existing Image Gallery & Upload Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold font-heading text-slate-900">Product Media & Gallery</h2>
                    <span class="text-[11px] text-slate-400 font-medium">Supports PNG, JPG, WEBP, HEIC up to 20MB</span>
                </div>

                <!-- Existing Saved Gallery -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700">Currently Saved Images</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @forelse($existingImages as $img)
                            <div class="relative group rounded-xl border border-slate-200 overflow-hidden bg-slate-50 p-1 shadow-xs">
                                <div class="w-full h-24 rounded-lg overflow-hidden bg-white">
                                    @if(!empty($img->url))
                                        <img src="{{ $img->url }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-1 flex items-center justify-between px-1">
                                    @if($img->is_primary)
                                        <span class="text-[9px] font-bold uppercase bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded">Primary</span>
                                    @else
                                        <button type="button" wire:click="setPrimaryImage({{ $img->id }})" class="text-[9px] font-semibold text-slate-600 hover:text-rose-600 transition">Set Primary</button>
                                    @endif

                                    <button type="button" wire:click="deleteImage({{ $img->id }})" wire:confirm="Are you sure you want to delete this image?" class="text-[9px] font-bold text-rose-600 hover:text-rose-800 transition">Delete</button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-4 text-center text-xs text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                No images uploaded for this product yet. Upload cover and gallery images below!
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- New Uploads Section -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <!-- Upload New Primary Image -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700">Upload New Primary Cover Image</label>
                        <input type="file" wire:model="primaryImage" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 cursor-pointer">
                        
                        <!-- Uploading Indicator -->
                        <div wire:loading wire:target="primaryImage" class="text-xs font-bold text-rose-600 flex items-center gap-2 pt-1">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Uploading primary image...</span>
                        </div>

                        @error('primaryImage') 
                            <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> 
                        @enderror

                        @if ($primaryImage)
                            <div class="mt-2 relative w-28 h-28 rounded-xl border border-slate-200 overflow-hidden shadow-xs group">
                                <img src="{{ $primaryImage->temporaryUrl() }}" class="w-full h-full object-cover">
                                <button type="button" wire:click="removePrimaryImage" class="absolute top-1 right-1 bg-rose-600 text-white rounded-full p-1 text-xs font-bold opacity-90 hover:opacity-100 transition shadow-xs" title="Remove image">
                                    &times;
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Add Gallery Images -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700">Add Gallery Images</label>
                        <input type="file" wire:model="additionalImages" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                        
                        <!-- Uploading Indicator -->
                        <div wire:loading wire:target="additionalImages" class="text-xs font-bold text-rose-600 flex items-center gap-2 pt-1">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Uploading gallery images...</span>
                        </div>

                        @error('additionalImages') 
                            <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> 
                        @enderror
                        @error('additionalImages.*') 
                            <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> 
                        @enderror

                        @if ($additionalImages && count($additionalImages) > 0)
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($additionalImages as $idx => $img)
                                    <div class="relative w-20 h-20 rounded-xl border border-slate-200 overflow-hidden shadow-xs group">
                                        <img src="{{ $img->temporaryUrl() }}" class="w-full h-full object-cover">
                                        <button type="button" wire:click="removeNewAdditionalImage({{ $idx }})" class="absolute top-1 right-1 bg-rose-600 text-white rounded-full p-1 text-xs font-bold opacity-90 hover:opacity-100 transition shadow-xs" title="Remove image">
                                            &times;
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Variants Matrix Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-base font-bold font-heading text-slate-900">Product Variants (Size & Color Matrix)</h2>
                        <p class="text-xs text-slate-500">Manage size and color combinations, prices, stock, and published status.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" wire:click="generateVariantSKUs" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                            ⚡ Auto-Fill SKUs
                        </button>
                        <button type="button" wire:click="addVariant" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition border border-rose-200/60">
                            + Single Row
                        </button>
                    </div>
                </div>

                @if(session('variant_error'))
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs font-bold text-amber-800">
                        {{ session('variant_error') }}
                    </div>
                @endif

                <!-- Bulk Generator Section -->
                <div x-data="{ open: false }" class="bg-slate-50 p-4 rounded-xl border border-slate-200/80 space-y-3">
                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-xs font-bold text-slate-800 font-heading">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>1-Click Bulk Matrix Generator</span>
                        </span>
                        <span class="text-rose-600 font-semibold" x-text="open ? 'Hide Matrix Generator' : '+ Open Matrix Generator'"></span>
                    </button>

                    <div x-show="open" x-transition class="space-y-4 pt-3 border-t border-slate-200/80">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Sizes -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Select Sizes</label>
                                <div class="max-h-32 overflow-y-auto p-2 bg-white rounded-lg border border-slate-200 grid grid-cols-2 gap-1.5">
                                    @foreach($sizes as $sz)
                                        <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                            <input type="checkbox" value="{{ $sz->id }}" wire:model="bulkSizes" class="w-3.5 h-3.5 rounded text-rose-600">
                                            <span>{{ $sz->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Colors -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Select Colors</label>
                                <div class="max-h-32 overflow-y-auto p-2 bg-white rounded-lg border border-slate-200 grid grid-cols-2 gap-1.5">
                                    @foreach($colors as $cl)
                                        <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                            <input type="checkbox" value="{{ $cl->id }}" wire:model="bulkColors" class="w-3.5 h-3.5 rounded text-rose-600">
                                            <span>{{ $cl->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Bulk Variant Price (PKR)</label>
                                <input type="number" step="0.01" wire:model="bulkPrice" placeholder="Default: Base Price" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Bulk Sale Price (PKR)</label>
                                <input type="number" step="0.01" wire:model="bulkSalePrice" placeholder="Optional" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Bulk Initial Stock</label>
                                <input type="number" wire:model="bulkStock" placeholder="10" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs font-bold">
                            </div>
                        </div>

                        <button type="button" wire:click="generateBulkVariants" class="w-full py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg transition shadow-sm">
                            ✨ Add All Generated Variant Combinations
                        </button>
                    </div>
                </div>

                <!-- Variant Rows List -->
                <div class="space-y-3">
                    @foreach($variants as $index => $variant)
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                            <!-- Size (2 cols) -->
                            <div class="sm:col-span-2">
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Size</label>
                                <select wire:model="variants.{{ $index }}.size_id" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs font-medium">
                                    <option value="">No Size</option>
                                    @foreach($sizes as $sz)
                                        <option value="{{ $sz->id }}">{{ $sz->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Color (2 cols) -->
                            <div class="sm:col-span-2">
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Color</label>
                                <select wire:model="variants.{{ $index }}.color_id" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs font-medium">
                                    <option value="">No Color</option>
                                    @foreach($colors as $cl)
                                        <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Variant SKU (3 cols) -->
                            <div class="sm:col-span-3">
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Variant SKU</label>
                                <input type="text" wire:model="variants.{{ $index }}.sku" placeholder="Auto-generated" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs font-mono">
                                @error("variants.{$index}.sku") <span class="text-rose-500 text-[10px] block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Price (2 cols) -->
                            <div class="sm:col-span-2">
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Price (PKR)</label>
                                <input type="number" step="0.01" wire:model="variants.{{ $index }}.price" placeholder="Base Price" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs font-bold">
                            </div>

                            <!-- Stock (2 cols) -->
                            <div class="sm:col-span-2">
                                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Stock Qty *</label>
                                <input type="number" wire:model="variants.{{ $index }}.stock_quantity" min="0" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-900">
                            </div>

                            <!-- Actions & Status Toggle (1 col) -->
                            <div class="sm:col-span-1 flex items-center justify-end gap-2 pt-2 sm:pt-0">
                                <label class="inline-flex items-center cursor-pointer" title="Publish/Active Status">
                                    <input type="checkbox" wire:model="variants.{{ $index }}.status" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500 border-slate-300">
                                </label>
                                <button type="button" wire:click="removeVariant({{ $index }})" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition" title="Remove Variant">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
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
                    <input type="number" step="0.01" wire:model="price" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-rose-500">
                    @error('price') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Sale Discount Price (PKR)</label>
                    <input type="number" step="0.01" wire:model="sale_price" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-rose-600 focus:ring-2 focus:ring-rose-500">
                    @error('sale_price') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Cost Price (Internal)</label>
                    <input type="number" step="0.01" wire:model="cost_price" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-rose-500">
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
