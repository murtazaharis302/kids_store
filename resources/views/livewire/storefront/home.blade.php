<div class="space-y-16 lg:space-y-24 pb-12">
    <!-- 1. HERO SECTION -->
    <section class="relative bg-gradient-to-br from-amber-50 via-rose-50 to-pink-50 border-b border-rose-100/60 overflow-hidden py-14 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <!-- Left Column: Copy & Actions -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/90 border border-rose-200 text-rose-700 text-xs font-bold shadow-xs">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                        Al Hayat Kids Official Storefront
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 font-heading tracking-tight leading-tight">
                        Made for <span class="text-rose-600">Little Moments</span>
                    </h1>

                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl font-medium">
                        Discover comfortable, stylish kidswear designed for everyday adventures. From cozy newborn sets to durable playground outfits.
                    </p>

                    <!-- Primary & Secondary CTAs -->
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ route('shop', ['new_arrival' => 1]) }}" class="px-8 py-4 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-sm rounded-2xl transition-all shadow-md shadow-rose-500/20 hover:shadow-lg hover:shadow-rose-500/30">
                            Shop New Arrivals
                        </a>
                        <a href="{{ route('shop') }}" class="px-8 py-4 bg-white hover:bg-slate-100 text-slate-800 font-extrabold text-sm rounded-2xl border border-slate-200 transition-all shadow-2xs">
                            Explore Collections
                        </a>
                    </div>
                </div>

                <!-- Right Column: Visual Campaign Artwork Card -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none rounded-3xl bg-white p-3 shadow-2xl border border-rose-100 overflow-hidden transform lg:rotate-1 hover:rotate-0 transition-transform duration-500">
                        <div class="aspect-4/3 rounded-2xl overflow-hidden relative shadow-inner">
                            <img src="{{ asset('images/hero_kids.png') }}" alt="Al Hayat Kids Campaign" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700">
                            
                            <!-- Floating Overlay Badges -->
                            <div class="absolute bottom-4 left-4 right-4 p-3.5 rounded-2xl bg-white/90 backdrop-blur-md border border-white/60 shadow-lg flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                                        ✨
                                    </div>
                                    <div>
                                        <h3 class="text-xs font-extrabold text-slate-900 font-heading">Al Hayat Kids Apparel</h3>
                                        <p class="text-[11px] font-medium text-slate-500">100% Breathable Cotton • Pakistan Shipping</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-lg bg-rose-100 text-rose-700 text-[10px] font-extrabold uppercase">New</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- NEUTRAL BRAND BADGES -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 font-heading">Quality Kidswear</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Comfortable & durable fabrics</p>
                </div>
            </div>

            <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 font-heading">Easy Shopping</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Seamless kids garment browsing</p>
                </div>
            </div>

            <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 font-heading">Secure Shopping</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Protected store experience</p>
                </div>
            </div>

            <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 002.5-2.5V11a2 2 0 012-2h1.055"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 font-heading">Pakistan-wide Store</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Serving families nationwide</p>
                </div>
            </div>
        </div>
    </section>

    <!-- LIVE COLOR SHUFFLE SHOWCASE -->
    <x-storefront.color-shuffle-showcase />

    <!-- 2. SHOP BY CATEGORY -->
    @if($categories->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading">Shop by Category</h2>
                    <p class="text-sm text-slate-500 mt-1">Explore tailored collections for every age group</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($categories as $category)
                    @php
                        $catImg = $category->image;
                        $hasCatImg = false;
                        $catImgUrl = '';

                        if ($catImg) {
                            if (\Illuminate\Support\Str::startsWith($catImg, ['http://', 'https://'])) {
                                $catImgUrl = $catImg;
                                $hasCatImg = true;
                            } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($catImg)) {
                                $catImgUrl = asset('storage/' . $catImg);
                                $hasCatImg = true;
                            } elseif (file_exists(public_path($catImg))) {
                                $catImgUrl = asset($catImg);
                                $hasCatImg = true;
                            }
                        }
                    @endphp

                    <a href="#" @click.prevent="" class="group relative bg-white rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col p-4 text-center">
                        <div class="aspect-square w-full rounded-2xl bg-rose-50 overflow-hidden relative mb-4 flex items-center justify-center">
                            @if($hasCatImg)
                                <img src="{{ $catImgUrl }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            @else
                                <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                            @endif
                        </div>

                        <h3 class="text-base font-bold text-slate-900 group-hover:text-rose-600 transition-colors font-heading">
                            {{ $category->name }}
                        </h3>
                        <span class="text-xs text-slate-400 mt-1 font-medium group-hover:text-rose-500">
                            Explore →
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- 3. NEW ARRIVALS -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading">New Arrivals</h2>
                <p class="text-sm text-slate-500 mt-1">Fresh styles for your little ones</p>
            </div>
            <a href="#" @click.prevent="" class="text-xs sm:text-sm font-bold text-rose-600 hover:text-rose-700 transition">
                View All →
            </a>
        </div>

        @if($newArrivals->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($newArrivals as $product)
                    <x-storefront.product-card :product="$product" />
                @endforeach
            </div>
        @else
            <x-storefront.empty-state 
                title="No New Arrivals Yet" 
                description="Our upcoming seasonal drops will be added soon. Check back shortly!" 
                actionText="Explore Categories"
                actionUrl="#" />
        @endif
    </section>

    <!-- 4. FEATURED COLLECTION -->
    @if($featuredCollection && $featuredCollection->products->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-rose-600 to-pink-600 rounded-3xl p-8 sm:p-12 text-white mb-8 relative overflow-hidden shadow-lg">
                <div class="relative z-10 max-w-2xl">
                    <span class="px-3 py-1 rounded-full bg-white/20 text-white text-xs font-extrabold uppercase tracking-wider">
                        Featured Collection
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold mt-3 font-heading">
                        {{ $featuredCollection->name }}
                    </h2>
                    @if($featuredCollection->description)
                        <p class="text-rose-100 text-sm sm:text-base mt-2 leading-relaxed">
                            {{ $featuredCollection->description }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($featuredCollection->products as $product)
                    <x-storefront.product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- 5. SHOP BY AGE -->
    @if($ageGroups->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading">Shop by Age</h2>
                    <p class="text-sm text-slate-500 mt-1">Find the perfect fit for every stage of growth</p>
                </div>
            </div>

            <!-- Scrollable Pill Grid -->
            <div class="flex items-center gap-3 overflow-x-auto pb-4 scrollbar-none snap-x">
                @foreach($ageGroups as $ageGroup)
                    <a href="#" @click.prevent="" class="snap-start shrink-0 px-6 py-3.5 rounded-2xl bg-white border border-slate-200/80 hover:border-rose-300 hover:bg-rose-50/50 text-slate-700 hover:text-rose-700 font-bold text-sm transition-all shadow-2xs hover:shadow-xs flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                        {{ $ageGroup->name }}
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- 6. SALE / PROMOTIONAL SECTION -->
    @if($saleProducts->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading">Special Offers & Sale</h2>
                    <p class="text-sm text-slate-500 mt-1">Great values on selected kids apparel</p>
                </div>
                <a href="#" @click.prevent="" class="text-xs sm:text-sm font-bold text-rose-600 hover:text-rose-700 transition">
                    View All Deals →
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($saleProducts as $product)
                    <x-storefront.product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- 7. BRAND VALUE SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 rounded-3xl p-8 sm:p-12 text-white relative overflow-hidden shadow-xl">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-3">
                <span class="px-3 py-1 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30 text-xs font-bold uppercase tracking-wider">
                    Our Philosophy
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading">
                    Why Families Love Al Hayat Kids
                </h2>
                <p class="text-slate-400 text-sm">
                    Designing comfortable, stylish, and durable clothing made for real childhood moments.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-slate-800/60 rounded-2xl p-6 border border-slate-700/60 space-y-3 text-center sm:text-left">
                    <div class="w-12 h-12 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center mx-auto sm:mx-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold font-heading text-white">Comfort for Everyday Adventures</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Soft, breathable, and durable fabrics that keep up with active play, nap times, and daily adventures.
                    </p>
                </div>

                <div class="bg-slate-800/60 rounded-2xl p-6 border border-slate-700/60 space-y-3 text-center sm:text-left">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center mx-auto sm:mx-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    </div>
                    <h3 class="text-lg font-bold font-heading text-white">Styles Made for Little Personalities</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Charming color palettes, delightful patterns, and timeless cuts crafted for little personalities.
                    </p>
                </div>

                <div class="bg-slate-800/60 rounded-2xl p-6 border border-slate-700/60 space-y-3 text-center sm:text-left">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto sm:mx-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold font-heading text-white">Thoughtful Details for Growing Kids</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Flexible waistbands, tagless neck labels, and easy-snap closures designed for stress-free dressing.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. FINAL CTA SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-amber-100/70 via-rose-100/70 to-pink-100/70 rounded-3xl p-8 sm:p-14 text-center space-y-6 border border-rose-200/60 shadow-sm">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                Find Their Next Favourite Look
            </h2>
            <p class="text-slate-600 text-sm sm:text-base max-w-xl mx-auto leading-relaxed">
                Browse our complete collection of shirts, dresses, rompers, tops, and accessories.
            </p>
            <div class="pt-2">
                <a href="#" @click.prevent="" class="inline-flex items-center gap-2 px-8 py-4 bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm rounded-2xl transition shadow-lg shadow-rose-500/25">
                    Explore Full Catalog
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </section>
</div>
