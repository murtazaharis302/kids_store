<div style="max-width:1380px;margin:0 auto;padding:32px 24px 80px;">

    {{-- ── Breadcrumbs ── --}}
    <nav style="display:flex;align-items:center;gap:8px;font-size:11px;font-weight:500;color:#999;letter-spacing:0.04em;margin-bottom:32px;flex-wrap:wrap;">
        <a href="{{ route('home') }}" style="color:#999;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#C9A96E'" onmouseout="this.style.color='#999'">Home</a>
        <span>›</span>
        @if($product->category)
            <a href="{{ route('shop', ['category' => $product->category->slug]) }}"
               style="color:#999;text-decoration:none;transition:color 0.2s;"
               onmouseover="this.style.color='#C9A96E'" onmouseout="this.style.color='#999'">
                {{ $product->category->name }}
            </a>
            <span>›</span>
        @endif
        <span style="color:#0D0D0D;font-weight:600;">{{ $product->name }}</span>
    </nav>

    {{-- ── Main Product Grid ── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:start;" class="product-show-grid">

        {{-- ══ LEFT: Image Gallery ══ --}}
        <div>
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
                $activeImgPath = $selectedImage;
                $activeImgUrl  = \App\Models\ProductImage::getImageUrl($activeImgPath);
                $hasActiveImg  = !empty($activeImgUrl);
            @endphp

            {{-- Main Hero Image --}}
            <div style="position:relative;width:100%;background:#F7F4EF;overflow:hidden;border:1px solid #E8E3DC;" class="product-main-img-wrap">
                @if($hasActiveImg)
                    <img src="{{ $activeImgUrl }}"
                         alt="{{ $product->name }}"
                         style="width:100%;height:100%;object-fit:contain;object-position:center;display:block;transition:opacity 0.3s ease;"
                         id="main-product-image">
                @else
                    <div style="width:100%;aspect-ratio:1/1;display:flex;flex-direction:column;align-items:center;justify-content:center;background:#F7F4EF;color:#C9A96E;gap:12px;">
                        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span style="font-size:12px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#888;">Al Hayat Kids</span>
                    </div>
                @endif

                {{-- Badges --}}
                <div style="position:absolute;top:16px;left:16px;display:flex;flex-direction:column;gap:6px;z-index:2;">
                    @if($this->pricing['has_sale'])
                        <span style="display:inline-block;padding:4px 10px;background:#0D0D0D;color:#FFFFFF;font-size:9px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;">SALE</span>
                    @endif
                    @if($product->new_arrival)
                        <span style="display:inline-block;padding:4px 10px;background:#C9A96E;color:#FFFFFF;font-size:9px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;">NEW ARRIVAL</span>
                    @endif
                </div>
            </div>

            {{-- Thumbnail Strip --}}
            @if($galleryImages->count() > 1)
                <div style="display:flex;gap:8px;margin-top:10px;overflow-x:auto;padding-bottom:4px;" class="product-thumbs-strip">
                    @foreach($galleryImages as $gImg)
                        @php
                            $thumbUrl = $gImg->url;
                            $isActive = $selectedImage === $gImg->image;
                        @endphp
                        <button type="button"
                                wire:click="selectImage('{{ $gImg->image }}')"
                                style="flex-shrink:0;width:72px;height:72px;border:2px solid {{ $isActive ? '#C9A96E' : '#E8E3DC' }};overflow:hidden;background:#F7F4EF;cursor:pointer;transition:border-color 0.2s;padding:0;"
                                onmouseover="this.style.borderColor='#C9A96E'"
                                onmouseout="this.style.borderColor='{{ $isActive ? '#C9A96E' : '#E8E3DC' }}'">
                            @if(!empty($thumbUrl))
                                <img src="{{ $thumbUrl }}" alt="Thumbnail"
                                     style="width:100%;height:100%;object-fit:cover;display:block;">
                            @endif
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ══ RIGHT: Product Info & Purchase ══ --}}
        <div style="position:sticky;top:24px;">

            {{-- Category & Title --}}
            <div style="margin-bottom:20px;">
                @if($product->category)
                    <a href="{{ route('shop', ['category' => $product->category->slug]) }}"
                       style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;text-decoration:none;margin-bottom:10px;display:block;">
                        {{ $product->category->name }}
                    </a>
                @endif
                <h1 style="font-family:'Cormorant Garamond','Playfair Display',serif;font-size:clamp(26px,3vw,40px);font-weight:600;color:#0D0D0D;line-height:1.15;margin:0 0 8px;">
                    {{ $product->name }}
                </h1>
                <p style="font-size:11px;color:#AAA;margin:0;">SKU: <strong style="color:#666;">{{ $product->sku }}</strong></p>
            </div>

            {{-- Price --}}
            <div style="margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid #E8E3DC;">
                <div style="display:flex;align-items:baseline;gap:12px;">
                    @if($this->pricing['has_sale'])
                        <span style="font-family:'Cormorant Garamond',serif;font-size:34px;font-weight:700;color:#0D0D0D;line-height:1;">
                            Rs. {{ number_format($this->pricing['sale_price'], 0) }}
                        </span>
                        <span style="font-size:18px;color:#CCC;text-decoration:line-through;">
                            Rs. {{ number_format($this->pricing['regular_price'], 0) }}
                        </span>
                        <span style="font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#FFFFFF;background:#0D0D0D;padding:3px 8px;">
                            SAVE {{ round((($this->pricing['regular_price'] - $this->pricing['sale_price']) / $this->pricing['regular_price']) * 100) }}%
                        </span>
                    @else
                        <span style="font-family:'Cormorant Garamond',serif;font-size:34px;font-weight:700;color:#0D0D0D;line-height:1;">
                            Rs. {{ number_format($this->pricing['regular_price'], 0) }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Short Description --}}
            @if($product->short_description)
                <div style="margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid #E8E3DC;">
                    <p style="font-size:14px;color:#555;line-height:1.75;margin:0;">
                        {{ $product->short_description }}
                    </p>
                </div>
            @endif

            {{-- Variant Selectors --}}
            @if($product->variants->isNotEmpty())
                <div style="margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid #E8E3DC;display:flex;flex-direction:column;gap:20px;">

                    {{-- Color --}}
                    @if($availableColors->isNotEmpty())
                        <div>
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                                <span style="font-size:11px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#0D0D0D;">Color</span>
                                @if($selectedColorId && ($colObj = $availableColors->firstWhere('id', $selectedColorId)))
                                    <span style="font-size:11px;color:#666;font-weight:500;">{{ $colObj->name }}</span>
                                @endif
                            </div>
                            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                @foreach($availableColors as $col)
                                    @php $isSelected = $selectedColorId == $col->id; @endphp
                                    <button type="button"
                                            wire:click="selectColor({{ $col->id }})"
                                            style="display:flex;align-items:center;gap:7px;padding:8px 14px;border:2px solid {{ $isSelected ? '#0D0D0D' : '#E8E3DC' }};background:{{ $isSelected ? '#0D0D0D' : '#FFFFFF' }};color:{{ $isSelected ? '#FFFFFF' : '#0D0D0D' }};font-size:11px;font-weight:600;cursor:pointer;transition:all 0.2s;letter-spacing:0.06em;">
                                        @if($col->hex_code)
                                            <span style="width:12px;height:12px;border-radius:50%;border:1px solid rgba(0,0,0,0.15);background:{{ $col->hex_code }};flex-shrink:0;"></span>
                                        @endif
                                        {{ $col->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Size --}}
                    @if($availableSizes->isNotEmpty())
                        <div>
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                                <span style="font-size:11px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#0D0D0D;">Size</span>
                                @if($selectedSizeId && ($szObj = $availableSizes->firstWhere('id', $selectedSizeId)))
                                    <span style="font-size:11px;color:#666;font-weight:500;">{{ $szObj->name }}</span>
                                @endif
                            </div>
                            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                @foreach($availableSizes as $sz)
                                    @php $isSelected = $selectedSizeId == $sz->id; @endphp
                                    <button type="button"
                                            wire:click="selectSize({{ $sz->id }})"
                                            style="min-width:44px;padding:10px 16px;border:2px solid {{ $isSelected ? '#C9A96E' : '#E8E3DC' }};background:{{ $isSelected ? '#C9A96E' : '#FFFFFF' }};color:{{ $isSelected ? '#FFFFFF' : '#0D0D0D' }};font-size:12px;font-weight:700;cursor:pointer;transition:all 0.2s;letter-spacing:0.06em;text-align:center;">
                                        {{ $sz->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @error('variant')
                        <p style="font-size:12px;font-weight:600;color:#E53E3E;margin:0;">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            {{-- Stock Badge --}}
            @php $stock = $this->availableStock; @endphp
            <div style="margin-bottom:24px;">
                @if($stock > 15)
                    <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:600;color:#2F855A;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#48BB78;"></span>
                        In Stock — Ready to Ship
                    </span>
                @elseif($stock > 0)
                    <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:600;color:#B7791F;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#ECC94B;animation:pulse 1.5s infinite;"></span>
                        Only {{ $stock }} left — Order Soon!
                    </span>
                @else
                    <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:600;color:#C53030;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#FC8181;"></span>
                        Out of Stock
                    </span>
                @endif
            </div>

            {{-- Quantity & Add to Cart --}}
            <div style="display:flex;gap:12px;align-items:stretch;margin-bottom:16px;">

                {{-- Quantity Stepper --}}
                <div style="display:flex;align-items:center;border:2px solid #0D0D0D;overflow:hidden;flex-shrink:0;">
                    <button type="button"
                            wire:click="decrementQuantity"
                            @if($quantity <= 1 || $stock <= 0) disabled @endif
                            style="width:44px;height:52px;background:#FFFFFF;border:none;font-size:20px;font-weight:300;color:#0D0D0D;cursor:pointer;transition:background 0.2s;display:flex;align-items:center;justify-content:center;"
                            onmouseover="this.style.background='#F7F4EF'"
                            onmouseout="this.style.background='#FFFFFF'">
                        −
                    </button>
                    <span style="width:48px;text-align:center;font-size:14px;font-weight:700;color:#0D0D0D;border-left:1px solid #E8E3DC;border-right:1px solid #E8E3DC;height:52px;display:flex;align-items:center;justify-content:center;">
                        {{ $quantity }}
                    </span>
                    <button type="button"
                            wire:click="incrementQuantity"
                            @if($quantity >= $stock || $stock <= 0) disabled @endif
                            style="width:44px;height:52px;background:#FFFFFF;border:none;font-size:20px;font-weight:300;color:#0D0D0D;cursor:pointer;transition:background 0.2s;display:flex;align-items:center;justify-content:center;"
                            onmouseover="this.style.background='#F7F4EF'"
                            onmouseout="this.style.background='#FFFFFF'">
                        +
                    </button>
                </div>

                {{-- Add to Cart Button --}}
                <button type="button"
                        wire:click="addToCart"
                        @if($stock <= 0) disabled @endif
                        wire:loading.attr="disabled"
                        style="flex:1;height:52px;background:{{ $stock > 0 ? '#0D0D0D' : '#CCC' }};color:#FFFFFF;border:none;font-size:11px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;cursor:{{ $stock > 0 ? 'pointer' : 'not-allowed' }};transition:background 0.25s ease;display:flex;align-items:center;justify-content:center;gap:8px;"
                        onmouseover="if(!this.disabled)this.style.background='#C9A96E'"
                        onmouseout="if(!this.disabled)this.style.background='{{ $stock > 0 ? '#0D0D0D' : '#CCC' }}'">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span wire:loading.remove wire:target="addToCart">{{ $stock > 0 ? 'ADD TO CART' : 'OUT OF STOCK' }}</span>
                    <span wire:loading wire:target="addToCart">ADDING...</span>
                </button>
            </div>

            {{-- Success Message --}}
            @if($cartMessage)
                <div style="padding:12px 16px;background:#F0FFF4;border:1px solid #C6F6D5;margin-bottom:16px;display:flex;align-items:center;gap:10px;">
                    <svg width="16" height="16" fill="none" stroke="#38A169" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span style="font-size:12px;font-weight:600;color:#276749;">{{ $cartMessage }}</span>
                </div>
            @endif

            @error('quantity')
                <p style="font-size:12px;font-weight:600;color:#C53030;margin-bottom:12px;">{{ $message }}</p>
            @enderror

            {{-- Trust Signals --}}
            <div style="border-top:1px solid #E8E3DC;padding-top:20px;display:flex;flex-direction:column;gap:10px;">
                @foreach([
                    ['🚚', 'Rs. 350 Nationwide Delivery', 'TCS / Leopards — 2-5 business days'],
                    ['🧵', '100% Premium Fabric', 'Breathable, skin-safe materials'],
                    ['📲', 'WhatsApp: 0324-9171213', 'Order support & queries'],
                ] as $trust)
                <div style="display:flex;align-items:center;gap:12px;">
                    <span style="font-size:16px;flex-shrink:0;">{{ $trust[0] }}</span>
                    <div>
                        <div style="font-size:11px;font-weight:700;color:#0D0D0D;letter-spacing:0.03em;">{{ $trust[1] }}</div>
                        <div style="font-size:10px;color:#AAA;margin-top:1px;">{{ $trust[2] }}</div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- ── Full Description ── --}}
    @if($product->description)
        <div style="margin-top:64px;padding:48px;background:#FFFFFF;border:1px solid #E8E3DC;">
            <h2 style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:600;color:#0D0D0D;margin:0 0 24px;padding-bottom:16px;border-bottom:1px solid #E8E3DC;">
                Product Description
            </h2>
            <div style="font-size:14px;color:#555;line-height:1.9;max-width:800px;">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
    @endif

    {{-- ── Related Products ── --}}
    @if($relatedProducts->isNotEmpty())
        <div style="margin-top:64px;">
            <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:32px;padding-bottom:16px;border-bottom:1px solid #E8E3DC;">
                <div>
                    <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin:0 0 6px;">You May Also Like</p>
                    <h2 style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:600;color:#0D0D0D;margin:0;line-height:1.1;">Related Products</h2>
                </div>
                <a href="{{ route('shop') }}"
                   style="font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;text-decoration:none;border-bottom:1px solid #0D0D0D;padding-bottom:2px;white-space:nowrap;"
                   onmouseover="this.style.color='#C9A96E';this.style.borderColor='#C9A96E'"
                   onmouseout="this.style.color='#0D0D0D';this.style.borderColor='#0D0D0D'">View All</a>
            </div>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;" class="related-products-grid">
                @foreach($relatedProducts as $relProduct)
                    <x-storefront.product-card :product="$relProduct" />
                @endforeach
            </div>
        </div>
    @endif

</div>

<style>
/* ── PRODUCT SHOW RESPONSIVE ── */
.product-show-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; }
.product-main-img-wrap { aspect-ratio: 1 / 1; }
.product-main-img-wrap img { aspect-ratio: 1 / 1; }

@media (max-width: 1024px) {
    .product-show-grid { grid-template-columns: 1fr; gap: 40px; }
    .product-main-img-wrap { max-width: 520px; margin: 0 auto; }
}
@media (max-width: 640px) {
    .product-show-grid { gap: 28px; }
    .related-products-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 12px !important; }
}
</style>
