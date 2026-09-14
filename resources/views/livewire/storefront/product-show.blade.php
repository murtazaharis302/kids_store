<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">
    <!-- Breadcrumbs Navigation -->
    <nav class="flex text-xs text-slate-400 font-medium space-x-2">
        <a href="{{ route('home') }}" class="hover:text-rose-600 transition">Home</a>
        <span>/</span>
        @if($product->category)
            <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="hover:text-rose-600 transition">
                {{ $product->category->name }}
            </a>
            <span>/</span>
        @endif
        <span class="text-slate-700 font-semibold truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <!-- Main Product Showcase Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <!-- Left Column: Gallery & Image Thumbnails (7 Cols) -->
        <div class="lg:col-span-7 space-y-4">
            @php
                $activeImgPath = $selectedImage;
                $activeImgUrl = \App\Models\ProductImage::getImageUrl($activeImgPath);
                $hasActiveImg = !empty($activeImgUrl);
            @endphp

            <!-- Hero Active Image Container -->
            <div class="relative aspect-4/3 sm:aspect-square w-full rounded-3xl bg-slate-100 border border-slate-200/80 overflow-hidden shadow-sm flex items-center justify-center">
                @if($hasActiveImg)
                    <img src="{{ $activeImgUrl }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover transition-all duration-300">
                @else
                    <div class="flex flex-col items-center justify-center p-8 text-slate-400 text-center">
                        <svg class="w-16 h-16 stroke-current opacity-40 mb-3" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm font-bold text-slate-400 font-heading">Al Hayat Kids Collection</span>
                    </div>
                @endif

                <!-- Badges -->
                <div class="absolute top-4 left-4 flex flex-col gap-2 z-10">
                    @if($this->pricing['has_sale'])
                        <span class="px-3 py-1 rounded-xl bg-rose-600 text-white text-xs font-extrabold uppercase tracking-wider shadow-xs">
                            Sale
                        </span>
                    @endif

                    @if($product->new_arrival)
                        <span class="px-3 py-1 rounded-xl bg-amber-500 text-white text-xs font-extrabold uppercase tracking-wider shadow-xs">
                            New Arrival
                        </span>
                    @endif
                </div>
            </div>

            <!-- Thumbnail Selector Navigation -->
            @if($product->images->isNotEmpty() || $product->primaryImage)
                @php
                    $galleryImages = collect();
                    if ($product->primaryImage) {
                        $galleryImages->push($product->primaryImage);
                    }
                    foreach ($product->images as $img) {
                        if (!$product->primaryImage || $img->id !== $product->primaryImage->id) {
                            $galleryImages->push($img);
                        }
                    }
                @endphp

                @if($galleryImages->count() > 1)
                    <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-none">
                        @foreach($galleryImages as $gImg)
                            @php
                                $thumbUrl = $gImg->url;
                                $hasThumb = !empty($thumbUrl);
                            @endphp

                            <button type="button" 
                                    wire:click="selectImage('{{ $gImg->image }}')" 
                                    class="w-20 h-20 rounded-2xl border-2 overflow-hidden shrink-0 transition-all {{ $selectedImage === $gImg->image ? 'border-rose-600 ring-2 ring-rose-500/20 scale-105' : 'border-slate-200 hover:border-slate-300' }}">
                                @if($hasThumb)
                                    <img src="{{ $thumbUrl }}" alt="Thumbnail" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400 text-[10px]">Thumb</div>
                                @endif
                            </button>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        <!-- Right Column: Product Information & Purchase Controls (5 Cols) -->
        <div class="lg:col-span-5 space-y-6 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs">
            <!-- Header & Title -->
            <div class="space-y-2">
                @if($product->category)
                    <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="inline-block text-xs font-bold text-rose-600 uppercase tracking-wider hover:underline">
                        {{ $product->category->name }}
                    </a>
                @endif

                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading leading-tight">
                    {{ $product->name }}
                </h1>

                <div class="flex items-center gap-3 text-xs text-slate-400 font-medium">
                    <span>SKU: <strong class="text-slate-700">{{ $product->sku }}</strong></span>
                </div>
            </div>

            <!-- Pricing Display -->
            <div class="pt-2 border-t border-slate-100 flex items-baseline gap-3">
                @if($this->pricing['has_sale'])
                    <span class="text-3xl font-extrabold text-rose-600 font-heading">
                        Rs. {{ number_format($this->pricing['sale_price'], 2) }}
                    </span>
                    <span class="text-base text-slate-400 line-through">
                        Rs. {{ number_format($this->pricing['regular_price'], 2) }}
                    </span>
                @else
                    <span class="text-3xl font-extrabold text-slate-900 font-heading">
                        Rs. {{ number_format($this->pricing['regular_price'], 2) }}
                    </span>
                @endif
            </div>

            <!-- Short Description -->
            @if($product->short_description)
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                    {{ $product->short_description }}
                </p>
            @endif

            <!-- Variant Selectors (Color & Size) -->
            @if($product->variants->isNotEmpty())
                <div class="space-y-5 border-t border-slate-100 pt-5">
                    
                    <!-- Color Swatches -->
                    @if($availableColors->isNotEmpty())
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-800 uppercase tracking-wider font-heading">Color</span>
                                @if($selectedColorId && ($colObj = $availableColors->firstWhere('id', $selectedColorId)))
                                    <span class="text-slate-500 font-medium">{{ $colObj->name }}</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($availableColors as $col)
                                    @php
                                        $isSelected = $selectedColorId == $col->id;
                                    @endphp
                                    <button type="button" 
                                            wire:click="selectColor({{ $col->id }})" 
                                            class="px-3.5 py-2 rounded-xl border text-xs font-bold transition flex items-center gap-2 {{ $isSelected ? 'bg-slate-900 text-white border-slate-900 ring-2 ring-slate-900/20 shadow-xs' : 'bg-white border-slate-200 text-slate-800 hover:border-rose-400' }}">
                                        @if($col->hex_code)
                                            <span class="w-3 h-3 rounded-full border border-black/20" style="background-color: {{ $col->hex_code }}"></span>
                                        @endif
                                        <span>{{ $col->name }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Size Pills -->
                    @if($availableSizes->isNotEmpty())
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-800 uppercase tracking-wider font-heading">Size</span>
                                @if($selectedSizeId && ($szObj = $availableSizes->firstWhere('id', $selectedSizeId)))
                                    <span class="text-slate-500 font-medium">{{ $szObj->name }}</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($availableSizes as $sz)
                                    @php
                                        $isSelected = $selectedSizeId == $sz->id;
                                    @endphp
                                    <button type="button" 
                                            wire:click="selectSize({{ $sz->id }})" 
                                            class="px-4 py-2.5 rounded-xl border text-xs font-bold transition {{ $isSelected ? 'bg-rose-600 text-white border-rose-600 ring-2 ring-rose-500/20 shadow-xs' : 'bg-white border-slate-200 text-slate-800 hover:border-rose-400' }}">
                                        {{ $sz->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @error('variant')
                        <p class="text-xs font-bold text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Stock Availability Badge -->
            <div class="border-t border-slate-100 pt-5 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-800 uppercase tracking-wider font-heading">Availability</span>
                    @php
                        $stock = $this->availableStock;
                    @endphp
                    @if($stock > 15)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            In Stock
                        </span>
                    @elseif($stock > 0)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            Low Stock (Only {{ $stock }} left)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            Out of Stock
                        </span>
                    @endif
                </div>
            </div>

            <!-- Quantity & Add to Cart Section -->
            @php
                $stock = $this->availableStock;
            @endphp

            <div class="space-y-4 border-t border-slate-100 pt-5">
                <div class="flex items-center gap-4">
                    <!-- Quantity Stepper -->
                    <div class="flex items-center border border-slate-200 rounded-2xl bg-slate-50 overflow-hidden shadow-2xs">
                        <button type="button" 
                                wire:click="decrementQuantity" 
                                @if($quantity <= 1 || $stock <= 0) disabled @endif
                                class="w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </button>
                        <span class="w-12 text-center text-sm font-bold text-slate-900 font-heading">
                            {{ $quantity }}
                        </span>
                        <button type="button" 
                                wire:click="incrementQuantity" 
                                @if($quantity >= $stock || $stock <= 0) disabled @endif
                                class="w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>

                    <!-- Add to Cart Button -->
                    <button type="button" 
                            wire:click="addToCart" 
                            @if($stock <= 0) disabled @endif
                            class="flex-1 py-3.5 px-6 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-sm shadow-md shadow-rose-500/20 hover:shadow-lg transition-all flex items-center justify-center gap-2 disabled:bg-slate-300 disabled:text-slate-500 disabled:shadow-none disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>{{ $stock > 0 ? 'Add to Cart' : 'Out of Stock' }}</span>
                    </button>

                    <!-- Wishlist UI Placeholder -->
                    <button type="button" 
                            @click.prevent="" 
                            title="Wishlist feature launching in Task 13" 
                            class="w-12 h-12 rounded-2xl border border-slate-200 text-slate-400 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 flex items-center justify-center transition shrink-0 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </div>

                @error('quantity')
                    <p class="text-xs font-bold text-rose-600 mt-1">{{ $message }}</p>
                @enderror

                @if($cartMessage)
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs font-bold text-emerald-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $cartMessage }}</span>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Full Product Description Section -->
    @if($product->description)
        <section class="bg-white p-8 sm:p-12 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 font-heading">
                Full Description
            </h2>
            <div class="prose prose-rose text-slate-600 text-sm leading-relaxed max-w-none">
                {!! nl2br(e($product->description)) !!}
            </div>
        </section>
    @endif

    <!-- Related Products Section -->
    @if($relatedProducts->isNotEmpty())
        <section class="space-y-6 pt-4">
            <div class="flex items-end justify-between border-b border-slate-200/80 pb-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 font-heading">
                        Related Products
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Discover similar styles for little personalities</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relProduct)
                    <x-storefront.product-card :product="$relProduct" />
                @endforeach
            </div>
        </section>
    @endif
</div>
