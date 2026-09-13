@props(['product'])

@php
    $primaryImg = $product->primaryImage;
    $imageUrl = '';
    $hasValidImage = false;

    if ($primaryImg && !empty($primaryImg->url)) {
        $imageUrl = $primaryImg->url;
        $hasValidImage = true;
    } elseif ($product->images && $product->images->isNotEmpty()) {
        $imageUrl = $product->images->first()->url;
        $hasValidImage = !empty($imageUrl);
    }

    $regularPrice = (float) $product->price;
    $salePrice = !is_null($product->sale_price) ? (float) $product->sale_price : null;
    $hasSale = !is_null($salePrice) && $salePrice < $regularPrice;
@endphp

<div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col h-full overflow-hidden">
    <!-- Image Container -->
    <div class="relative aspect-square w-full bg-slate-100 overflow-hidden flex items-center justify-center">
        @if($hasValidImage)
            <img src="{{ $imageUrl }}" 
                 alt="{{ $primaryImg->alt_text ?? $product->name }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 loading="lazy">
        @else
            <div class="flex flex-col items-center justify-center p-6 text-slate-400 text-center">
                <svg class="w-12 h-12 stroke-current opacity-40 mb-2" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-xs font-semibold text-slate-400">Al Hayat Kids Collection</span>
            </div>
        @endif

        <!-- Badges Layer -->
        <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
            @if($hasSale)
                <span class="px-2.5 py-1 rounded-lg bg-rose-600 text-white text-[11px] font-extrabold uppercase tracking-wider shadow-xs">
                    Sale
                </span>
            @endif

            @if($product->new_arrival)
                <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white text-[11px] font-extrabold uppercase tracking-wider shadow-xs">
                    New
                </span>
            @endif
        </div>

        <!-- Wishlist Placeholder UI -->
        <div class="absolute top-3 right-3 z-10">
            <button type="button" 
                    @click.prevent="" 
                    title="Wishlist feature coming soon"
                    class="w-8 h-8 rounded-full bg-white/80 backdrop-blur-xs border border-slate-200/60 text-slate-400 hover:text-rose-500 hover:bg-white flex items-center justify-center transition shadow-2xs cursor-not-allowed">
                <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Product Info -->
    <div class="p-4 flex flex-col flex-1 justify-between gap-3">
        <div class="space-y-1">
            @if($product->category)
                <p class="text-[11px] font-semibold text-rose-600 uppercase tracking-wider">
                    {{ $product->category->name }}
                </p>
            @endif

            <h3 class="text-sm font-bold text-slate-800 line-clamp-2 group-hover:text-rose-600 transition-colors leading-snug">
                <a href="{{ route('products.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h3>
        </div>

        <!-- Pricing -->
        <div class="pt-2 border-t border-slate-100 flex items-baseline gap-2">
            @if($hasSale)
                <span class="text-base font-extrabold text-rose-600">
                    Rs. {{ number_format($salePrice, 2) }}
                </span>
                <span class="text-xs text-slate-400 line-through">
                    Rs. {{ number_format($regularPrice, 2) }}
                </span>
            @else
                <span class="text-base font-extrabold text-slate-900">
                    Rs. {{ number_format($regularPrice, 2) }}
                </span>
            @endif
        </div>
    </div>
</div>
