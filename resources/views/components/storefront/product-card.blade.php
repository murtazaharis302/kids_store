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
    $discount     = $hasSale ? round((($regularPrice - $salePrice) / $regularPrice) * 100) : 0;

    // Get first available variant for quick add to cart
    $firstVariant = $product->variants ? $product->variants->where('status', true)->first() : null;
@endphp

{{-- ─────────────────────────────────────────────────────────
     PRODUCT CARD  — Professional kids store editorial style
     Al Hayat Kids | ink #0D0D0D  cream #F7F4EF  gold #C9A96E
───────────────────────────────────────────────────────────── --}}
<div class="product-card-root" style="position:relative;background:#FFFFFF;border:1px solid #E8E3DC;overflow:hidden;display:flex;flex-direction:column;height:100%;transition:all 0.3s ease;border-radius:2px;">

    {{-- ── Image Section ── --}}
    <a href="{{ route('products.show', $product->slug) }}"
       style="display:block;position:relative;overflow:hidden;background:#F7F4EF;aspect-ratio:3/4;text-decoration:none;"
       class="product-card-link">

        @if($hasValidImage)
            <img src="{{ $imageUrl }}"
                 alt="{{ $primaryImg->alt_text ?? $product->name }}"
                 onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='flex';"
                 style="width:100%;height:100%;object-fit:cover;object-position:center top;transition:transform 0.6s cubic-bezier(0.25,0.46,0.45,0.94);display:block;"
                 class="product-card-img"
                 loading="lazy">
            {{-- Fallback if image fails --}}
            <div style="display:none;width:100%;height:100%;flex-direction:column;align-items:center;justify-content:center;background:#F7F4EF;color:#C9A96E;">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span style="font-size:10px;font-weight:600;letter-spacing:0.08em;color:#888;margin-top:10px;text-transform:uppercase;">Al Hayat Kids</span>
            </div>
        @else
            <div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;background:#F7F4EF;color:#C9A96E;">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span style="font-size:10px;font-weight:600;letter-spacing:0.08em;color:#888;margin-top:10px;text-transform:uppercase;">Al Hayat Kids</span>
            </div>
        @endif

        {{-- Gradient overlay for "SHOP NOW" hover effect --}}
        <div class="product-card-overlay" style="position:absolute;inset:0;background:linear-gradient(to top, rgba(13,13,13,0.65) 0%, transparent 50%);opacity:0;transition:opacity 0.35s ease;display:flex;align-items:flex-end;justify-content:center;padding-bottom:20px;">
            <span style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#FFFFFF;border-bottom:1px solid rgba(255,255,255,0.7);padding-bottom:2px;">VIEW PRODUCT</span>
        </div>

        {{-- Badges top-left --}}
        <div style="position:absolute;top:10px;left:10px;display:flex;flex-direction:column;gap:4px;z-index:3;">
            @if($hasSale)
                <span style="display:inline-block;padding:3px 8px;background:#0D0D0D;color:#FFFFFF;font-size:9px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;">-{{ $discount }}%</span>
            @endif
            @if($product->new_arrival)
                <span style="display:inline-block;padding:3px 8px;background:#C9A96E;color:#FFFFFF;font-size:9px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;">NEW</span>
            @endif
        </div>

    </a>

    {{-- ── Quick Add to Cart Button (appears on hover) ── --}}
    <div class="product-card-atc" style="position:absolute;bottom:0;left:0;right:0;transform:translateY(100%);transition:transform 0.3s cubic-bezier(0.25,0.46,0.45,0.94);z-index:5;">
        <a href="{{ route('products.show', $product->slug) }}"
           style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:13px 16px;background:#0D0D0D;color:#FFFFFF;font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;text-decoration:none;cursor:pointer;border:none;transition:background 0.2s ease;"
           onmouseover="this.style.background='#C9A96E'"
           onmouseout="this.style.background='#0D0D0D'">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            SELECT OPTIONS
        </a>
    </div>

    {{-- ── Product Info ── --}}
    <div style="padding:14px 16px 16px;flex:1;display:flex;flex-direction:column;gap:6px;">
        {{-- Category --}}
        @if($product->category)
            <p style="font-size:9px;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#C9A96E;margin:0;">{{ $product->category->name }}</p>
        @endif

        {{-- Product Name --}}
        <a href="{{ route('products.show', $product->slug) }}"
           style="text-decoration:none;">
            <h3 style="font-size:13px;font-weight:500;color:#0D0D0D;line-height:1.45;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                {{ $product->name }}
            </h3>
        </a>

        {{-- Pricing --}}
        <div style="display:flex;align-items:baseline;gap:7px;margin-top:4px;">
            @if($hasSale)
                <span style="font-size:15px;font-weight:700;color:#0D0D0D;font-family:'Cormorant Garamond',serif;">
                    Rs. {{ number_format($salePrice, 0) }}
                </span>
                <span style="font-size:12px;color:#bbb;text-decoration:line-through;">
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
.product-card-root:hover {
    border-color: #C9A96E;
    box-shadow: 0 8px 32px rgba(0,0,0,0.10);
    transform: translateY(-2px);
}
.product-card-root:hover .product-card-img {
    transform: scale(1.06);
}
.product-card-root:hover .product-card-overlay {
    opacity: 1;
}
.product-card-root:hover .product-card-atc {
    transform: translateY(0);
}
@media (max-width: 640px) {
    .product-card-atc {
        transform: translateY(0) !important;
    }
}
</style>
