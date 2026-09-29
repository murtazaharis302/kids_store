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
    $salePrice    = !is_null($product->sale_price) ? (float) $product->sale_price : null;
    $hasSale      = !is_null($salePrice) && $salePrice < $regularPrice;
@endphp

{{-- ────────────────────────────────────────────────────
     PRODUCT CARD  — Sapphire-inspired editorial style
     Palette: ink #0D0D0D  cream #F7F4EF  gold #C9A96E
──────────────────────────────────────────────────── --}}
<div style="position:relative;background:#FFFFFF;border:1px solid #E0DBD3;overflow:hidden;display:flex;flex-direction:column;height:100%;transition:border-color 0.25s;"
     class="product-card-root"
     onmouseover="this.style.borderColor='#C9A96E'"
     onmouseout="this.style.borderColor='#E0DBD3'">

    {{-- Full card link --}}
    <a href="{{ route('products.show', $product->slug) }}"
       style="position:absolute;inset:0;z-index:1;"
       aria-label="View {{ $product->name }}"></a>

    {{-- ── Image ── --}}
    <div style="position:relative;overflow:hidden;background:#F7F4EF;aspect-ratio:3/4;">
        @if($hasValidImage)
            <img src="{{ $imageUrl }}"
                 alt="{{ $primaryImg->alt_text ?? $product->name }}"
                 style="width:100%;height:100%;object-fit:cover;transition:transform 0.55s ease;display:block;"
                 class="product-card-img"
                 loading="lazy">
        @else
            <div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#C9A96E;">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span style="font-size:10px;font-weight:600;letter-spacing:0.08em;color:#888;margin-top:10px;text-transform:uppercase;">Al Hayat Kids</span>
            </div>
        @endif

        {{-- Badge row --}}
        <div style="position:absolute;top:12px;left:12px;display:flex;flex-direction:column;gap:5px;z-index:2;pointer-events:none;">
            @if($hasSale)
                <span style="display:inline-block;padding:3px 10px;background:#0D0D0D;color:#FFFFFF;font-size:9px;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;">SALE</span>
            @endif
            @if($product->new_arrival)
                <span style="display:inline-block;padding:3px 10px;background:#C9A96E;color:#FFFFFF;font-size:9px;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;">NEW</span>
            @endif
        </div>

        {{-- Wishlist button --}}
        <div style="position:absolute;top:12px;right:12px;z-index:3;">
            <button type="button"
                    @click.prevent=""
                    title="Save to wishlist"
                    style="width:32px;height:32px;background:rgba(255,255,255,0.9);border:1px solid #E0DBD3;display:flex;align-items:center;justify-content:center;color:#888;cursor:default;transition:all 0.2s;backdrop-filter:blur(4px);"
                    onmouseover="this.style.background='#FFFFFF';this.style.color='#0D0D0D'"
                    onmouseout="this.style.background='rgba(255,255,255,0.9)';this.style.color='#888'">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
        </div>
    </div>

    {{-- ── Product Info ── --}}
    <div style="padding:16px;flex:1;display:flex;flex-direction:column;justify-content:space-between;gap:10px;position:relative;z-index:2;pointer-events:none;">
        <div>
            @if($product->category)
                <p style="font-size:10px;font-weight:600;letter-spacing:0.14em;text-transform:uppercase;color:#C9A96E;margin-bottom:4px;">{{ $product->category->name }}</p>
            @endif
            <h3 style="font-size:13px;font-weight:500;color:#0D0D0D;line-height:1.45;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                {{ $product->name }}
            </h3>
        </div>

        {{-- Pricing --}}
        <div style="display:flex;align-items:baseline;gap:8px;padding-top:10px;border-top:1px solid #E0DBD3;">
            @if($hasSale)
                <span style="font-size:15px;font-weight:700;color:#0D0D0D;font-family:'Cormorant Garamond',serif;">
                    Rs. {{ number_format($salePrice, 0) }}
                </span>
                <span style="font-size:12px;color:#aaa;text-decoration:line-through;">
                    Rs. {{ number_format($regularPrice, 0) }}
                </span>
            @else
                <span style="font-size:15px;font-weight:700;color:#0D0D0D;font-family:'Cormorant Garamond',serif;">
                    Rs. {{ number_format($regularPrice, 0) }}
                </span>
            @endif
        </div>
    </div>
</div>

<style>
    .product-card-root:hover .product-card-img { transform: scale(1.05); }
</style>
