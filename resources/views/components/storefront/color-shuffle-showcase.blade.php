<section x-data="{
    products: [
        {
            id: 1,
            name: 'Organic Cotton Baby Romper',
            category: 'Baby & Toddler (0-24M)',
            price: 'Rs. 1,850',
            originalPrice: 'Rs. 2,300',
            rating: 5,
            reviewsCount: 42,
            badge: 'Best Seller',
            badgeClass: 'bg-rose-500 text-white',
            url: '{{ route('shop') }}',
            variants: [
                { name: 'Pastel Rose Pink', colorHex: '#f472b6', bgGradient: 'from-pink-100/70 to-rose-50/80', ringClass: 'ring-pink-400', image: '{{ asset('images/variants/romper_pink.png') }}', tag: 'Rose Pink' },
                { name: 'Sky Ocean Blue', colorHex: '#38bdf8', bgGradient: 'from-sky-100/70 to-blue-50/80', ringClass: 'ring-sky-400', image: '{{ asset('images/variants/romper_blue.png') }}', tag: 'Sky Blue' },
                { name: 'Sunshine Yellow', colorHex: '#fbbf24', bgGradient: 'from-amber-100/70 to-yellow-50/80', ringClass: 'ring-amber-400', image: '{{ asset('images/variants/romper_yellow.png') }}', tag: 'Butter Yellow' }
            ],
            activeIndex: 0
        },
        {
            id: 2,
            name: 'Cozy Fleece Hoodie & Shorts Set',
            category: 'Boys & Girls (2-6Y)',
            price: 'Rs. 2,490',
            originalPrice: 'Rs. 2,990',
            rating: 5,
            reviewsCount: 29,
            badge: 'Trending Now',
            badgeClass: 'bg-amber-500 text-white',
            url: '{{ route('shop') }}',
            variants: [
                { name: 'Sand Beige', colorHex: '#d1d5db', bgGradient: 'from-stone-100/80 to-amber-50/60', ringClass: 'ring-stone-400', image: '{{ asset('images/variants/hoodie_beige.png') }}', tag: 'Sand Beige' },
                { name: 'Pastel Lavender', colorHex: '#c084fc', bgGradient: 'from-purple-100/70 to-indigo-50/70', ringClass: 'ring-purple-400', image: '{{ asset('images/variants/hoodie_lavender.png') }}', tag: 'Pastel Lavender' },
                { name: 'Coral Peach', colorHex: '#fb7185', bgGradient: 'from-rose-100/70 to-pink-50/70', ringClass: 'ring-rose-400', image: '{{ asset('images/variants/hoodie_coral.png') }}', tag: 'Coral Peach' }
            ],
            activeIndex: 0
        },
        {
            id: 3,
            name: 'Floral Summer Party Dress',
            category: 'Girls Boutique (1-5Y)',
            price: 'Rs. 2,990',
            originalPrice: 'Rs. 3,500',
            rating: 5,
            reviewsCount: 56,
            badge: 'New Edition',
            badgeClass: 'bg-emerald-500 text-white',
            url: '{{ route('shop') }}',
            variants: [
                { name: 'Dusty Rose', colorHex: '#e11d48', bgGradient: 'from-rose-100/80 to-pink-50/80', ringClass: 'ring-rose-600', image: '{{ asset('images/variants/dress_rose.png') }}', tag: 'Dusty Rose' },
                { name: 'Sunshine Gold', colorHex: '#eab308', bgGradient: 'from-amber-100/80 to-yellow-50/80', ringClass: 'ring-yellow-500', image: '{{ asset('images/variants/dress_yellow.png') }}', tag: 'Sunny Gold' },
                { name: 'Teal Breeze', colorHex: '#14b8a6', bgGradient: 'from-teal-100/80 to-cyan-50/80', ringClass: 'ring-teal-500', image: '{{ asset('images/variants/dress_teal.png') }}', tag: 'Teal Breeze' }
            ],
            activeIndex: 0
        }
    ],
    isPlaying: true,
    progress: 0,
    progressTimer: null,
    addedProduct: null,
    startAutoShuffle() {
        if (this.progressTimer) clearInterval(this.progressTimer);
        this.progress = 0;
        this.progressTimer = setInterval(() => {
            if (this.isPlaying) {
                this.progress += 2.5;
                if (this.progress >= 100) {
                    this.progress = 0;
                    this.products.forEach(p => {
                        p.activeIndex = (p.activeIndex + 1) % p.variants.length;
                    });
                }
            }
        }, 75);
    },
    togglePlay() {
        this.isPlaying = !this.isPlaying;
    },
    selectVariant(pIdx, vIdx) {
        this.products[pIdx].activeIndex = vIdx;
        this.progress = 0;
    },
    quickAdd(pIdx) {
        let p = this.products[pIdx];
        let variantName = p.variants[p.activeIndex].name;
        this.addedProduct = p.name + ' (' + variantName + ')';
        setTimeout(() => { this.addedProduct = null; }, 3000);
    }
}" x-init="startAutoShuffle()" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    
    <!-- Sleek Clean Section Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold shadow-2xs mb-2">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                <span>✨ Interactive Color Showcase</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading">
                Signature Styles in <span class="text-rose-600">Shuffling Colorways</span>
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">
                Browse our top kids garments cycling through pastel colors in real time. Click any item to explore!
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <button @click="togglePlay()" 
                    class="px-4 py-2 text-xs font-bold rounded-xl border transition-all duration-200 flex items-center gap-1.5 shadow-2xs"
                    :class="isPlaying ? 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' : 'bg-emerald-50 border-emerald-200 text-emerald-700'">
                <span x-text="isPlaying ? '⏸ Pause Shuffle' : '▶ Resume Shuffle'"></span>
            </button>
            <a href="{{ route('shop') }}" class="text-xs font-extrabold text-rose-600 hover:text-rose-700 transition">
                View All Catalog →
            </a>
        </div>
    </div>

    <!-- Added to Cart Toast Notification -->
    <div x-show="addedProduct" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-5 py-4 rounded-2xl shadow-2xl border border-rose-500/40 flex items-center gap-3 max-w-md">
        <div class="w-9 h-9 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-lg shrink-0">
            ✓
        </div>
        <div>
            <h4 class="text-xs font-bold text-slate-200">Added to Cart!</h4>
            <p class="text-xs text-rose-300 font-medium" x-text="addedProduct"></p>
        </div>
    </div>

    <!-- 3 PRODUCTS CAROUSEL / SHUFFLE GRID -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <template x-for="(product, pIdx) in products" :key="product.id">
            <div class="group relative bg-white rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-500 overflow-hidden flex flex-col p-5">
                
                <!-- Clickable Product Artwork Container -->
                <a :href="product.url" class="block aspect-[4/3] sm:aspect-square w-full rounded-2xl overflow-hidden relative mb-5 transition-all duration-700 bg-gradient-to-br border border-black/5"
                   :class="product.variants[product.activeIndex].bgGradient">
                    
                    <!-- Floating Badge -->
                    <div class="absolute top-3 left-3 z-20 flex flex-col gap-1.5">
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider shadow-sm"
                              :class="product.badgeClass"
                              x-text="product.badge"></span>
                    </div>

                    <!-- Dynamic Active Color Tag Badge -->
                    <div class="absolute top-3 right-3 z-20">
                        <div class="px-3 py-1 rounded-full bg-white/95 backdrop-blur-md border border-slate-200/80 text-slate-800 text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all duration-300">
                            <span class="w-2.5 h-2.5 rounded-full border border-black/10 shadow-xs shrink-0"
                                  :style="'background-color: ' + product.variants[product.activeIndex].colorHex"></span>
                            <span x-text="product.variants[product.activeIndex].tag"></span>
                        </div>
                    </div>

                    <!-- Image Display with Smooth Cross-Fade Animation -->
                    <div class="w-full h-full p-2 sm:p-3 flex items-center justify-center relative">
                        <template x-for="(variant, vIdx) in product.variants" :key="vIdx">
                            <img :src="variant.image" 
                                 :alt="product.name + ' - ' + variant.name" 
                                 x-show="product.activeIndex === vIdx"
                                 x-transition:enter="transition ease-out duration-500 transform"
                                 x-transition:enter-start="opacity-0 scale-90 rotate-1"
                                 x-transition:enter-end="opacity-100 scale-100 rotate-0"
                                 x-transition:leave="transition ease-in duration-300 transform"
                                 x-transition:leave-start="opacity-100 scale-100 rotate-0"
                                 x-transition:leave-end="opacity-0 scale-95 -rotate-1"
                                 class="absolute inset-0 w-full h-full object-contain p-2 sm:p-3 drop-shadow-md transform group-hover:scale-105 transition-transform duration-500"
                                 loading="lazy">
                        </template>
                    </div>

                    <!-- Bottom Overlay Bar showing current color count -->
                    <div class="absolute bottom-3 left-3 right-3 z-20 px-3 py-1.5 rounded-xl bg-slate-900/80 backdrop-blur-md border border-white/20 text-white text-xs flex items-center justify-between shadow-md">
                        <span class="font-medium text-[11px] text-slate-200">
                            Colorway <span class="font-extrabold text-amber-300" x-text="(product.activeIndex + 1)"></span> of <span x-text="product.variants.length"></span>
                        </span>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-400 animate-ping"></span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-rose-300">Live Auto-Rotate</span>
                        </div>
                    </div>
                </a>

                <!-- Color Swatches Control Bar & Clickable Details -->
                <div class="space-y-4 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider" x-text="product.category"></span>
                            <div class="flex items-center text-amber-400 text-xs font-bold gap-1">
                                <span>★★★★★</span>
                                <span class="text-slate-400 font-normal" x-text="'(' + product.reviewsCount + ')'"></span>
                            </div>
                        </div>

                        <!-- Clickable Title -->
                        <a :href="product.url" class="block">
                            <h3 class="text-lg font-extrabold text-slate-900 font-heading hover:text-rose-600 transition-colors leading-snug"
                                x-text="product.name"></h3>
                        </a>

                        <!-- Color Selector Swatches -->
                        <div class="mt-4 pt-3 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-700">Available Color Variants:</span>
                                <span class="text-xs font-extrabold text-rose-600" x-text="product.variants[product.activeIndex].name"></span>
                            </div>

                            <div class="flex items-center gap-3">
                                <template x-for="(variant, vIdx) in product.variants" :key="vIdx">
                                    <button @click="selectVariant(pIdx, vIdx)"
                                            @mouseenter="selectVariant(pIdx, vIdx)"
                                            type="button"
                                            class="relative group/swatch p-1 rounded-full transition-all duration-300 focus:outline-none"
                                            :class="product.activeIndex === vIdx ? 'ring-2 ring-offset-2 ' + variant.ringClass + ' scale-110' : 'hover:scale-105 opacity-70 hover:opacity-100'">
                                        <span class="block w-6 h-6 rounded-full border border-black/10 shadow-xs"
                                              :style="'background-color: ' + variant.colorHex"></span>
                                        
                                        <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 hidden group-hover/swatch:block bg-slate-900 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-md whitespace-nowrap z-30 pointer-events-none"
                                              x-text="variant.tag"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Action Button -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3 mt-4">
                        <div>
                            <div class="text-lg font-extrabold text-slate-900 font-heading" x-text="product.price"></div>
                            <div class="text-xs text-slate-400 line-through font-medium" x-text="product.originalPrice"></div>
                        </div>

                        <a :href="product.url"
                           class="px-5 py-3 rounded-2xl bg-slate-900 hover:bg-rose-600 text-white font-extrabold text-xs transition-all duration-200 shadow-md hover:shadow-lg hover:shadow-rose-500/25 flex items-center gap-2">
                            <span>View Product</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>

                </div>
            </div>
        </template>
    </div>
</section>
