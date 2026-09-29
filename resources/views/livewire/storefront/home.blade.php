<div>
    {{-- ══════════════════════════════════════════
         1. HERO — FULL-WIDTH EDITORIAL BANNER
    ══════════════════════════════════════════ --}}
    <section id="hero" style="position:relative;overflow:hidden;background:#F7F4EF;min-height:90vh;display:flex;align-items:stretch;">

        {{-- Background image --}}
        <div style="position:absolute;inset:0;z-index:0;">
            <img src="{{ asset('images/hero_kids_teal.png') }}"
                 alt="Al Hayat Kids Campaign"
                 style="width:100%;height:100%;object-fit:cover;object-position:center top;"
                 onerror="this.src='{{ asset('images/hero_kids.png') }}'">
            {{-- gradient overlay for text readability --}}
            <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(13,13,13,0.65) 0%,rgba(13,13,13,0.25) 55%,rgba(13,13,13,0.0) 100%);"></div>
        </div>

        {{-- Hero Content --}}
        <div style="position:relative;z-index:2;max-width:1400px;margin:0 auto;padding:80px 24px;width:100%;display:flex;align-items:center;">
            <div style="max-width:560px;">
                <p class="section-label" style="color:#C9A96E;margin-bottom:16px;animation:fadeUp 0.8s ease both;">New Season</p>
                <h1 class="font-display" style="font-size:clamp(44px,6vw,78px);font-weight:600;color:#FFFFFF;line-height:1.05;margin-bottom:20px;animation:fadeUp 0.9s 0.1s ease both;">
                    Crafted for<br>Little <em style="font-style:italic;color:#C9A96E;">Moments</em>
                </h1>
                <p style="font-size:15px;color:rgba(255,255,255,0.75);line-height:1.75;max-width:400px;margin-bottom:36px;font-weight:400;animation:fadeUp 1s 0.2s ease both;">
                    Discover our new season collection — premium kidswear designed for comfort, style &amp; every childhood adventure.
                </p>
                <div style="display:flex;flex-wrap:wrap;gap:12px;animation:fadeUp 1s 0.3s ease both;">
                    <a href="{{ route('shop', ['new_arrival' => 1]) }}" class="btn-primary">Shop New Arrivals</a>
                    <a href="{{ route('shop') }}" class="btn-outline" style="border-color:rgba(255,255,255,0.5);color:#FFFFFF;" onmouseover="this.style.background='rgba(255,255,255,0.15)'" onmouseout="this.style.background='transparent'">Explore Collections</a>
                </div>
            </div>
        </div>

        {{-- Bottom scroll cue --}}
        <div style="position:absolute;bottom:28px;left:50%;transform:translateX(-50%);z-index:2;display:flex;flex-direction:column;align-items:center;gap:6px;color:rgba(255,255,255,0.5);">
            <span style="font-size:10px;font-weight:600;letter-spacing:0.15em;text-transform:uppercase;">Scroll</span>
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         2. TRUST BAR
    ══════════════════════════════════════════ --}}
    <div style="background:#0D0D0D;padding:18px 24px;">
        <div style="max-width:1400px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:20px;text-align:center;">
            @foreach([
                ['icon'=>'🧵','title'=>'Premium Fabric','sub'=>'100% breathable cotton'],
                ['icon'=>'🚚','title'=>'Rs 350 Delivery','sub'=>'Nationwide via TCS'],
                ['icon'=>'📲','title'=>'WhatsApp Orders','sub'=>'0324-9171213'],
                ['icon'=>'✦','title'=>'Pay in Advance','sub'=>'Secure & easy payment'],
            ] as $trust)
            <div style="display:flex;align-items:center;justify-content:center;gap:12px;">
                <span style="font-size:18px;">{{ $trust['icon'] }}</span>
                <div style="text-align:left;">
                    <div style="font-size:12px;font-weight:700;color:#FFFFFF;letter-spacing:0.04em;">{{ $trust['title'] }}</div>
                    <div style="font-size:11px;color:rgba(255,255,255,0.45);margin-top:1px;">{{ $trust['sub'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         3. EDITORIAL ARTICLES — 4 COLLECTION CARDS
         (Sapphire-style: large image tiles with text overlay)
    ══════════════════════════════════════════ --}}
    <section style="padding:80px 0;background:#FFFFFF;">
        <div style="max-width:1400px;margin:0 auto;padding:0 24px;">

            {{-- Section Header --}}
            <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:40px;flex-wrap:wrap;gap:16px;">
                <div>
                    <p class="section-label">The Collections</p>
                    <h2 class="section-title">Shop by Category</h2>
                </div>
                <a href="{{ route('shop') }}" class="view-all-link">View All Collections</a>
            </div>

            {{-- 4-Column Article Grid --}}
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">

                @php
                $articles = [
                    [
                        'image'    => asset('images/article_girls_sage.png'),
                        'label'    => 'Girls Collection',
                        'title'    => 'Garden Party',
                        'subtitle' => 'Floral & Embroidered',
                        'url'      => route('shop', ['category' => 'girls']),
                        'bg'       => '#EDE9E1',
                    ],
                    [
                        'image'    => asset('images/article_boys_navy.png'),
                        'label'    => 'Boys Collection',
                        'title'    => 'Little Gentlemen',
                        'subtitle' => 'Classic & Contemporary',
                        'url'      => route('shop', ['category' => 'boys']),
                        'bg'       => '#E8EDF0',
                    ],
                    [
                        'image'    => asset('images/article_newborn_cream.png'),
                        'label'    => 'Newborn',
                        'title'    => 'First Days',
                        'subtitle' => 'Soft & Gentle Fabrics',
                        'url'      => route('shop', ['category' => 'newborn']),
                        'bg'       => '#F4F0EA',
                    ],
                    [
                        'image'    => asset('images/article_eid_gold.png'),
                        'label'    => 'Eid Collection',
                        'title'    => 'Festive Glow',
                        'subtitle' => 'Gold Embroidery Sets',
                        'url'      => route('shop', ['new_arrival' => 1]),
                        'bg'       => '#F5EFE0',
                    ],
                ];
                @endphp

                @foreach($articles as $article)
                <a href="{{ $article['url'] }}"
                   style="display:block;text-decoration:none;position:relative;overflow:hidden;background:{{ $article['bg'] }};aspect-ratio:3/4;group;"
                   class="article-card">
                    {{-- Image --}}
                    <img src="{{ $article['image'] }}"
                         alt="{{ $article['title'] }}"
                         style="width:100%;height:100%;object-fit:cover;transition:transform 0.6s ease;display:block;"
                         class="article-img"
                         loading="lazy"
                         onerror="this.style.display='none'">

                    {{-- Bottom text overlay --}}
                    <div style="position:absolute;bottom:0;left:0;right:0;padding:28px 22px;background:linear-gradient(to top,rgba(13,13,13,0.72) 0%,rgba(13,13,13,0) 100%);">
                        <p style="font-size:10px;font-weight:600;letter-spacing:0.18em;text-transform:uppercase;color:#C9A96E;margin-bottom:4px;">{{ $article['label'] }}</p>
                        <h3 class="font-display" style="font-size:22px;font-weight:600;color:#FFFFFF;line-height:1.15;margin-bottom:4px;">{{ $article['title'] }}</h3>
                        <p style="font-size:12px;color:rgba(255,255,255,0.7);margin-bottom:14px;">{{ $article['subtitle'] }}</p>
                        <span style="font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#FFFFFF;border-bottom:1px solid rgba(255,255,255,0.5);padding-bottom:2px;">Shop Now</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Hover effect for article cards --}}
    <style>
        .article-card:hover .article-img { transform: scale(1.04); }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 900px) {
            .article-card { aspect-ratio: 3/4 !important; }
            .articles-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
        @media (max-width: 560px) {
            .articles-grid { grid-template-columns: 1fr 1fr !important; }
        }
    </style>

    {{-- ══════════════════════════════════════════
         4. SHOP BY CATEGORY (dynamic DB categories)
    ══════════════════════════════════════════ --}}
    @if($categories->isNotEmpty())
    <section style="padding:80px 0;background:#F7F4EF;">
        <div style="max-width:1400px;margin:0 auto;padding:0 24px;">
            <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:40px;flex-wrap:wrap;gap:16px;">
                <div>
                    <p class="section-label">Discover</p>
                    <h2 class="section-title">All Categories</h2>
                </div>
                <a href="{{ route('shop') }}" class="view-all-link">View All</a>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;">
                @foreach($categories as $category)
                    @php
                        $catImg   = $category->image;
                        $catImgUrl = '';
                        $hasCatImg = false;
                        if ($catImg) {
                            if (\Illuminate\Support\Str::startsWith($catImg, ['http://', 'https://'])) {
                                $catImgUrl = $catImg; $hasCatImg = true;
                            } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($catImg)) {
                                $catImgUrl = asset('storage/' . $catImg); $hasCatImg = true;
                            } elseif (file_exists(public_path($catImg))) {
                                $catImgUrl = asset($catImg); $hasCatImg = true;
                            }
                        }
                    @endphp
                    <a href="{{ route('shop', ['category' => $category->slug]) }}"
                       style="display:block;text-decoration:none;position:relative;overflow:hidden;background:#FFFFFF;border:1px solid #E0DBD3;aspect-ratio:4/5;transition:border-color 0.25s;"
                       class="cat-card"
                       onmouseover="this.style.borderColor='#C9A96E'"
                       onmouseout="this.style.borderColor='#E0DBD3'">
                        @if($hasCatImg)
                            <img src="{{ $catImgUrl }}" alt="{{ $category->name }}" style="width:100%;height:80%;object-fit:cover;display:block;transition:transform 0.5s ease;" class="cat-img" loading="lazy">
                        @else
                            <div style="width:100%;height:80%;background:#EDE9E1;display:flex;align-items:center;justify-content:center;">
                                <svg width="36" height="36" fill="none" stroke="#C9A96E" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                        @endif
                        <div style="padding:14px 16px;background:#FFFFFF;height:20%;display:flex;flex-direction:column;justify-content:center;">
                            <h3 style="font-size:13px;font-weight:600;color:#0D0D0D;margin-bottom:2px;">{{ $category->name }}</h3>
                            <span style="font-size:11px;color:#888;">{{ $category->products_count ?? 0 }} items</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    <style>.cat-card:hover .cat-img { transform: scale(1.04); }</style>
    @endif

    {{-- ══════════════════════════════════════════
         5. NEW ARRIVALS — PRODUCT GRID
    ══════════════════════════════════════════ --}}
    <section style="padding:80px 0;background:#FFFFFF;">
        <div style="max-width:1400px;margin:0 auto;padding:0 24px;">
            <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:40px;flex-wrap:wrap;gap:16px;">
                <div>
                    <p class="section-label">Just In</p>
                    <h2 class="section-title">New Arrivals</h2>
                </div>
                <a href="{{ route('shop', ['new_arrival' => 1]) }}" class="view-all-link">View All</a>
            </div>

            @if($newArrivals->isNotEmpty())
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px;">
                    @foreach($newArrivals as $product)
                        <x-storefront.product-card :product="$product" />
                    @endforeach
                </div>
            @else
                <x-storefront.empty-state
                    title="No New Arrivals Yet"
                    description="Our upcoming seasonal drops will be added soon."
                    actionText="Explore Categories"
                    actionUrl="{{ route('shop') }}" />
            @endif
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         6. EDITORIAL BANNER — FULL WIDTH CTA
    ══════════════════════════════════════════ --}}
    <section style="background:#0D0D0D;padding:80px 24px;text-align:center;position:relative;overflow:hidden;">
        {{-- subtle background texture --}}
        <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 20% 50%,rgba(201,169,110,0.08) 0%,transparent 60%),radial-gradient(circle at 80% 50%,rgba(201,169,110,0.06) 0%,transparent 60%);pointer-events:none;"></div>
        <div style="position:relative;z-index:1;max-width:700px;margin:0 auto;">
            <p class="section-label" style="color:#C9A96E;margin-bottom:16px;">Explore Our Dress Collection</p>
            <h2 class="font-display" style="font-size:clamp(36px,5vw,60px);font-weight:600;color:#FFFFFF;line-height:1.1;margin-bottom:20px;">
                Find Their Next <em style="font-style:italic;color:#C9A96E;">Favourite</em> Look
            </h2>
            <p style="font-size:15px;color:rgba(255,255,255,0.55);line-height:1.75;margin-bottom:36px;">
                Browse our complete collection of shirts, dresses, rompers, tops &amp; accessories — crafted for every little personality.
            </p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('shop') }}" class="btn-gold">Explore Full Catalog</a>
                <a href="{{ route('shop', ['new_arrival' => 1]) }}" class="btn-outline" style="border-color:rgba(255,255,255,0.3);color:#FFFFFF;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">New Arrivals</a>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         7. DYNAMIC CATALOG EXPLORER
    ══════════════════════════════════════════ --}}
    <section style="padding:80px 0;background:#F7F4EF;">
        <div style="max-width:1400px;margin:0 auto;padding:0 24px;">

            {{-- Header --}}
            <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:32px;flex-wrap:wrap;gap:16px;">
                <div>
                    <p class="section-label">Curated for You</p>
                    <h2 class="section-title">Dress Collection</h2>
                    <p style="font-size:13px;color:#888;margin-top:6px;">Switch categories to discover more outfit combinations</p>
                </div>
                <button type="button"
                        wire:click="shuffleProducts"
                        style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#0D0D0D;color:#FFFFFF;font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;border:1px solid #0D0D0D;cursor:pointer;transition:background 0.2s;"
                        onmouseover="this.style.background='#2C2C2C'" onmouseout="this.style.background='#0D0D0D'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Shuffle Outfits
                </button>
            </div>

            {{-- Category Filter Tabs --}}
            <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:8px;margin-bottom:32px;scrollbar-width:none;">
                <button type="button"
                        wire:click="selectCategory('all')"
                        style="flex-shrink:0;padding:9px 20px;font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;border:1px solid;cursor:pointer;transition:all 0.2s;white-space:nowrap;{{ $selectedCategorySlug === 'all' ? 'background:#0D0D0D;color:#FFFFFF;border-color:#0D0D0D;' : 'background:transparent;color:#2C2C2C;border-color:#E0DBD3;' }}">
                    All Products
                </button>
                @foreach($categories as $catTab)
                    <button type="button"
                            wire:click="selectCategory('{{ $catTab->slug }}')"
                            style="flex-shrink:0;padding:9px 20px;font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;border:1px solid;cursor:pointer;transition:all 0.2s;white-space:nowrap;{{ $selectedCategorySlug === $catTab->slug ? 'background:#0D0D0D;color:#FFFFFF;border-color:#0D0D0D;' : 'background:transparent;color:#2C2C2C;border-color:#E0DBD3;' }}">
                        {{ $catTab->name }} ({{ $catTab->products_count }})
                    </button>
                @endforeach
            </div>

            {{-- Products Grid --}}
            @if($showcaseProducts->isNotEmpty())
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px;">
                    @foreach($showcaseProducts as $product)
                        <x-storefront.product-card :product="$product" />
                    @endforeach
                </div>
            @else
                <x-storefront.empty-state
                    title="No Products in this Category"
                    description="Products for this category will be available soon."
                    actionText="Explore All Outfits"
                    actionUrl="{{ route('shop') }}" />
            @endif
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         8. FEATURED COLLECTION
    ══════════════════════════════════════════ --}}
    @if($featuredCollection && $featuredCollection->products->isNotEmpty())
    <section style="padding:80px 0;background:#FFFFFF;">
        <div style="max-width:1400px;margin:0 auto;padding:0 24px;">

            {{-- Feature Banner --}}
            <div style="position:relative;overflow:hidden;background:#0D0D0D;padding:56px 48px;margin-bottom:48px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:24px;">
                <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 80% 50%,rgba(201,169,110,0.12) 0%,transparent 60%);pointer-events:none;"></div>
                <div style="position:relative;z-index:1;">
                    <p class="section-label" style="color:#C9A96E;margin-bottom:12px;">Featured</p>
                    <h2 class="font-display" style="font-size:clamp(28px,4vw,48px);font-weight:600;color:#FFFFFF;line-height:1.1;margin-bottom:10px;">{{ $featuredCollection->name }}</h2>
                    @if($featuredCollection->description)
                        <p style="font-size:14px;color:rgba(255,255,255,0.55);max-width:480px;line-height:1.75;">{{ $featuredCollection->description }}</p>
                    @endif
                </div>
                <a href="{{ route('shop') }}" class="btn-gold" style="position:relative;z-index:1;flex-shrink:0;">Shop Collection</a>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px;">
                @foreach($featuredCollection->products as $product)
                    <x-storefront.product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════════
         9. SHOP BY AGE — CLEAN PILL ROW
    ══════════════════════════════════════════ --}}
    @if($ageGroups->isNotEmpty())
    <section style="padding:60px 0;background:#EDE9E1;border-top:1px solid #E0DBD3;">
        <div style="max-width:1400px;margin:0 auto;padding:0 24px;">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:28px;">
                <div>
                    <p class="section-label">For Every Stage</p>
                    <h2 class="section-title" style="font-size:clamp(24px,3vw,36px);">Shop by Age</h2>
                </div>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                @foreach($ageGroups as $ageGroup)
                    <a href="{{ route('shop', ['age_group' => $ageGroup->slug]) }}"
                       style="display:inline-flex;align-items:center;gap:6px;padding:10px 22px;background:#FFFFFF;border:1px solid #E0DBD3;font-size:12px;font-weight:600;letter-spacing:0.06em;color:#2C2C2C;text-decoration:none;text-transform:uppercase;transition:all 0.2s;"
                       onmouseover="this.style.background='#0D0D0D';this.style.color='#FFFFFF';this.style.borderColor='#0D0D0D'"
                       onmouseout="this.style.background='#FFFFFF';this.style.color='#2C2C2C';this.style.borderColor='#E0DBD3'">
                        {{ $ageGroup->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════════
         10. SALE PRODUCTS
    ══════════════════════════════════════════ --}}
    @if($saleProducts->isNotEmpty())
    <section style="padding:80px 0;background:#FFFFFF;">
        <div style="max-width:1400px;margin:0 auto;padding:0 24px;">
            <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:40px;flex-wrap:wrap;gap:16px;">
                <div>
                    <p class="section-label" style="color:#C9A96E;">Limited Time</p>
                    <h2 class="section-title">Special Offers &amp; Sale</h2>
                </div>
                <a href="{{ route('shop', ['sale' => 1]) }}" class="view-all-link">View All Deals</a>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px;">
                @foreach($saleProducts as $product)
                    <x-storefront.product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════════
         11. BRAND VALUES — 3 PILLARS
    ══════════════════════════════════════════ --}}
    <section style="padding:80px 0;background:#F7F4EF;border-top:1px solid #E0DBD3;">
        <div style="max-width:1400px;margin:0 auto;padding:0 24px;">
            <div style="text-align:center;margin-bottom:56px;">
                <p class="section-label">Our Promise</p>
                <h2 class="section-title">Why Families Love Al Hayat Kids</h2>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:32px;">
                @foreach([
                    ['icon'=>'🧵','heading'=>'Comfort for Everyday Adventures','body'=>'Soft, breathable fabrics that keep up with active play, nap times, and daily adventures.'],
                    ['icon'=>'✦','heading'=>'Styles for Little Personalities','body'=>'Charming palettes, delightful patterns, and timeless cuts crafted for little personalities.'],
                    ['icon'=>'🎀','heading'=>'Thoughtful Details','body'=>'Flexible waistbands, tagless labels, and easy-snap closures — designed for stress-free dressing.'],
                ] as $pillar)
                <div style="padding:40px 32px;background:#FFFFFF;border:1px solid #E0DBD3;">
                    <div style="font-size:28px;margin-bottom:20px;">{{ $pillar['icon'] }}</div>
                    <h3 class="font-display" style="font-size:22px;font-weight:600;color:#0D0D0D;margin-bottom:12px;line-height:1.25;">{{ $pillar['heading'] }}</h3>
                    <p style="font-size:13px;color:#888;line-height:1.8;">{{ $pillar['body'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

</div>
