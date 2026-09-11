<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.categories.index') }}" 
               class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Back to Categories">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold font-heading text-slate-900 tracking-tight">Create New Category</h1>
                <p class="text-xs text-slate-500 mt-0.5">Add a new category or subcategory to your store catalog.</p>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <form wire:submit.prevent="save" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Category Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Category Name <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" wire:model.live.debounce.300ms="name" placeholder="e.g. Dresses, Winter Wear" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs border @error('name') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                @error('name')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug -->
            <div>
                <label for="slug" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    URL Slug <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="slug" wire:model.live="slug" placeholder="e.g. dresses, winter-wear" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs border @error('slug') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition font-mono">
                <p class="text-[11px] text-slate-400 mt-1">Auto-generated from name. You can manually customize it.</p>
                @error('slug')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Parent Category -->
            <div>
                <label for="parent_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Parent Category <span class="text-slate-400 font-normal">(Optional)</span>
                </label>
                <select id="parent_id" wire:model="parent_id" 
                        class="w-full px-4 py-2.5 rounded-xl text-xs border @error('parent_id') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                    <option value="">None (Top-Level Category)</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}">
                            {{ $parent->parent_id ? '— ' : '' }}{{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Leave empty if this is a main parent category (e.g. Girls, Boys).</p>
                @error('parent_id')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sort Order -->
            <div>
                <label for="sort_order" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Sort Order
                </label>
                <input type="number" id="sort_order" wire:model="sort_order" min="0" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs border @error('sort_order') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                <p class="text-[11px] text-slate-400 mt-1">Lower values appear first in store navigation.</p>
                @error('sort_order')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Description <span class="text-slate-400 font-normal">(Optional)</span>
            </label>
            <textarea id="description" wire:model="description" rows="3" placeholder="Brief description of category for storefront and SEO..."
                      class="w-full px-4 py-2.5 rounded-xl text-xs border @error('description') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 @enderror text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition"></textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Image Upload -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Category Image/Thumbnail <span class="text-slate-400 font-normal">(Optional, Max 2MB)</span>
            </label>
            
            <div class="flex items-center gap-6">
                <!-- Preview area -->
                <div class="w-20 h-20 rounded-2xl bg-slate-100 border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shrink-0">
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                    @else
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    @endif
                </div>

                <!-- Input area -->
                <div class="flex-1">
                    <input type="file" id="image" wire:model="image" accept="image/*"
                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 cursor-pointer">
                    <div wire:loading wire:target="image" class="text-xs text-rose-500 font-medium mt-1">Uploading image preview...</div>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status Toggle -->
        <div class="pt-2">
            <label class="inline-flex items-center gap-3 cursor-pointer">
                <input type="checkbox" wire:model="status" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500 border-slate-300">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Active Category</span>
            </label>
            <p class="text-[11px] text-slate-400 mt-1 pl-7">Inactive categories will be hidden from storefront navigation.</p>
        </div>

        <!-- Action Buttons -->
        <div class="pt-6 border-t border-slate-200/80 flex items-center justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" 
               class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 border border-slate-200 transition">
                Cancel
            </a>
            <button type="submit" wire:loading.attr="disabled"
                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 shadow-md shadow-rose-500/20 transition flex items-center gap-2">
                <span wire:loading.remove wire:target="save">Create Category</span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-1">
                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Saving...
                </span>
            </button>
        </div>
    </form>
</div>
