<div>

{{-- ═══════════════════════════════════════════════════════════════
     HERO SLIDER — Exact Sapphire match
     · Full-bleed product image fills the screen
     · Text at BOTTOM CENTER: Title + Type + SHOP NOW
     · Arrows on far left & far right edges (no box/border)
     · Dot indicators bottom center
     · Auto-play 5s, pause on hover, swipe on mobile
═══════════════════════════════════════════════════════════════ --}}
<section id="hero-slider" style="position:relative;overflow:hidden;background:#EDE8DF;width:100%;height:92vh;min-height:520px;max-height:900px;">

    {{-- SLIDES TRACK --}}
    <div id="slider-track" style="display:flex;height:100%;transition:transform 0.65s cubic-bezier(0.77,0,0.18,1);will-change:transform;">

        @php
        $slides = [
            [
                'image' => asset('images/hero_kids_teal.png'),
                'title' => 'CRAFTED FOR LITTLE MOMENTS',
                'type'  => 'UNSTITCHED',
                'url'   => route('shop', ['new_arrival' => 1]),
            ],
            [
                'image' => asset('images/slide_girls_collection.png'),
                'title' => 'GARDEN PARTY',
                'type'  => 'GIRLS COLLECTION',
                'url'   => route('shop', ['category' => 'girls']),
            ],
            [
                'image' => asset('images/slide_eid_collection.png'),
                'title' => 'FESTIVE GLOW',
                'type'  => 'EID COLLECTION',
                'url'   => route('shop', ['new_arrival' => 1]),
            ],
            [
                'image' => asset('images/slide_newborn_collection.png'),
                'title' => 'GENTLE FROM DAY ONE',
                'type'  => 'NEWBORN & BABY',
                'url'   => route('shop', ['category' => 'newborn']),
            ],
        ];
        @endphp

        @foreach($slides as $i => $slide)
        <div style="min-width:100%;height:100%;position:relative;flex-shrink:0;">

            {{-- Full-bleed image — clean, no heavy dark overlay --}}
            <img src="{{ $slide['image'] }}"
                 alt="{{ $slide['title'] }}"
                 style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;"
                 loading="{{ $i === 0 ? 'eager' : 'lazy' }}">

            {{-- Subtle bottom vignette only — for text legibility --}}
            <div style="position:absolute;bottom:0;left:0;right:0;height:40%;background:linear-gradient(to top,rgba(0,0,0,0.60) 0%,rgba(0,0,0,0.2) 50%,transparent 100%);"></div>

            {{-- TEXT — BOTTOM CENTER exactly like Sapphire --}}
            <div style="position:absolute;bottom:0;left:0;right:0;z-index:5;text-align:center;padding-bottom:56px;">
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,4.5vw,56px);font-weight:700;color:#FFFFFF;line-height:1.05;letter-spacing:0.04em;margin-bottom:8px;">
                    {{ $slide['title'] }}
                </h2>
                <p style="font-size:11px;font-weight:700;letter-spacing:0.28em;text-transform:uppercase;color:rgba(255,255,255,0.88);margin-bottom:20px;">
                    {{ $slide['type'] }}
                </p>
                <a href="{{ $slide['url'] }}"
                   style="display:inline-block;font-size:11px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#FFFFFF;text-decoration:none;border-bottom:1px solid rgba(255,255,255,0.65);padding-bottom:3px;transition:border-color 0.25s,color 0.25s;"
                   onmouseover="this.style.borderColor='#C9A96E';this.style.color='#C9A96E'"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.65)';this.style.color='#FFFFFF'">SHOP NOW</a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- LEFT ARROW — far left edge, exactly like Sapphire --}}
    <button id="slider-prev"
            aria-label="Previous slide"
            style="position:absolute;left:0;top:50%;transform:translateY(-50%);z-index:10;width:56px;height:80px;background:transparent;border:none;color:#FFFFFF;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.2s;"
            onmouseover="this.style.background='rgba(255,255,255,0.1)'"
            onmouseout="this.style.background='transparent'">
        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </button>

    {{-- RIGHT ARROW — far right edge, exactly like Sapphire --}}
    <button id="slider-next"
            aria-label="Next slide"
            style="position:absolute;right:0;top:50%;transform:translateY(-50%);z-index:10;width:56px;height:80px;background:transparent;border:none;color:#FFFFFF;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.2s;"
            onmouseover="this.style.background='rgba(255,255,255,0.1)'"
            onmouseout="this.style.background='transparent'">
        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </button>

    {{-- DOT INDICATORS --}}
    <div id="slider-dots" style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);z-index:10;display:flex;gap:6px;align-items:center;">
        @foreach($slides as $i => $slide)
            <button class="slider-dot"
                    data-index="{{ $i }}"
                    aria-label="Slide {{ $i + 1 }}"
                    style="width:{{ $i === 0 ? '22px' : '6px' }};height:6px;background:{{ $i === 0 ? '#FFFFFF' : 'rgba(255,255,255,0.4)' }};border:none;cursor:pointer;transition:all 0.3s ease;padding:0;border-radius:3px;">
            </button>
        @endforeach
    </div>
</section>

{{-- SLIDER JS --}}
<script>
(function(){
    var track   = document.getElementById('slider-track');
    var prev    = document.getElementById('slider-prev');
    var next    = document.getElementById('slider-next');
    var dots    = document.querySelectorAll('.slider-dot');
    var total   = {{ count($slides) }};
    var cur     = 0;
    var timer   = null;
    var busy    = false;

    function go(n) {
        if (busy) return;
        busy = true;
        cur = ((n % total) + total) % total;
        track.style.transform = 'translateX(-' + (cur * 100) + '%)';
        dots.forEach(function(d, i) {
            d.style.width      = i === cur ? '22px' : '6px';
            d.style.background = i === cur ? '#FFFFFF' : 'rgba(255,255,255,0.4)';
        });
        setTimeout(function(){ busy = false; }, 700);
    }

    function startAuto() {
        clearInterval(timer);
        timer = setInterval(function(){ go(cur + 1); }, 5000);
    }

    prev && prev.addEventListener('click', function(){ clearInterval(timer); go(cur - 1); startAuto(); });
    next && next.addEventListener('click', function(){ clearInterval(timer); go(cur + 1); startAuto(); });
    dots.forEach(function(d){
        d.addEventListener('click', function(){ clearInterval(timer); go(parseInt(d.dataset.index)); startAuto(); });
    });

    var sec = document.getElementById('hero-slider');
    sec && sec.addEventListener('mouseenter', function(){ clearInterval(timer); });
    sec && sec.addEventListener('mouseleave', startAuto);

    // Touch swipe
    var tx = 0;
    sec && sec.addEventListener('touchstart', function(e){ tx = e.touches[0].clientX; }, { passive: true });
    sec && sec.addEventListener('touchend', function(e){
        var dx = tx - e.changedTouches[0].clientX;
        if (Math.abs(dx) > 50) { clearInterval(timer); go(dx > 0 ? cur+1 : cur-1); startAuto(); }
    }, { passive: true });

    go(0);
    startAuto();
})();
</script>


{{-- ═══════════════════════════════════════════════════════════════
     TRUST BAR
═══════════════════════════════════════════════════════════════ --}}
<div style="background:#0D0D0D;">
    <div style="max-width:1400px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);border-left:1px solid rgba(255,255,255,0.07);">
        @foreach([
            ['icon'=>'🧵','t'=>'Premium Fabric',   's'=>'100% breathable cotton'],
            ['icon'=>'🚚','t'=>'Rs 350 Delivery',  's'=>'Nationwide — TCS / Leopards'],
            ['icon'=>'📲','t'=>'WhatsApp Orders',  's'=>'0324-9171213'],
            ['icon'=>'🔒','t'=>'Advance Payment',  's'=>'Secure & simple'],
        ] as $tb)
        <div style="display:flex;align-items:center;gap:14px;padding:22px 28px;border-right:1px solid rgba(255,255,255,0.07);">
            <span style="font-size:18px;flex-shrink:0;">{{ $tb['icon'] }}</span>
            <div>
                <div style="font-size:12px;font-weight:700;color:#FFFFFF;letter-spacing:0.04em;">{{ $tb['t'] }}</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.38);margin-top:2px;">{{ $tb['s'] }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════════
     4 COLLECTION TILES — Sapphire-style portrait grid
═══════════════════════════════════════════════════════════════ --}}
<section style="padding:72px 0;background:#FFFFFF;">
    <div style="max-width:1400px;margin:0 auto;padding:0 40px;">

        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;">
            <div>
                <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:8px;">The Collections</p>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3.5vw,44px);font-weight:600;color:#0D0D0D;line-height:1.1;">Shop by Category</h2>
            </div>
            <a href="{{ route('shop') }}"
               style="font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;text-decoration:none;border-bottom:1px solid #0D0D0D;padding-bottom:2px;white-space:nowrap;"
               onmouseover="this.style.color='#C9A96E';this.style.borderColor='#C9A96E'"
               onmouseout="this.style.color='#0D0D0D';this.style.borderColor='#0D0D0D'">View All Collections</a>
        </div>

        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">
            @php
            $cols = [
                ['image'=>asset('images/slide_girls_collection.png'), 'label'=>'Girls','title'=>'Garden Party',       'sub'=>'Embroidered & Floral',     'url'=>route('shop',['category'=>'girls'])],
                ['image'=>asset('images/article_boys_navy.png'),      'label'=>'Boys', 'title'=>'Little Gentlemen',   'sub'=>'Classic & Contemporary',   'url'=>route('shop',['category'=>'boys'])],
                ['image'=>asset('images/slide_newborn_collection.png'),'label'=>'Newborn','title'=>'First Days',      'sub'=>'Soft & Gentle Fabrics',    'url'=>route('shop',['category'=>'newborn'])],
                ['image'=>asset('images/slide_eid_collection.png'),   'label'=>'Eid',  'title'=>'Festive Glow',      'sub'=>'Gold Embroidery Sets',     'url'=>route('shop',['new_arrival'=>1])],
            ];
            @endphp

            @foreach($cols as $col)
            <a href="{{ $col['url'] }}"
               style="display:block;text-decoration:none;position:relative;overflow:hidden;aspect-ratio:3/4;background:#EDE9E1;"
               class="col-tile">
                <img src="{{ $col['image'] }}"
                     alt="{{ $col['title'] }}"
                     style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:transform 0.65s cubic-bezier(0.25,0.46,0.45,0.94);"
                     class="col-tile-img" loading="lazy">
                <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,10,0.75) 0%,rgba(10,10,10,0.15) 45%,transparent 70%);"></div>
                <div style="position:absolute;bottom:0;left:0;right:0;padding:26px 22px;">
                    <p style="font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#C9A96E;margin-bottom:5px;">{{ $col['label'] }}</p>
                    <h3 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:#FFFFFF;line-height:1.15;margin-bottom:5px;">{{ $col['title'] }}</h3>
                    <p style="font-size:12px;color:rgba(255,255,255,0.6);margin-bottom:14px;">{{ $col['sub'] }}</p>
                    <span class="col-tile-cta" style="font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#FFFFFF;border-bottom:1px solid rgba(255,255,255,0.5);padding-bottom:2px;transition:all 0.2s;">SHOP NOW</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
<style>
    .col-tile:hover .col-tile-img { transform: scale(1.06); }
    .col-tile:hover .col-tile-cta { color:#C9A96E; border-bottom-color:#C9A96E; }
</style>


{{-- ═══════════════════════════════════════════════════════════════
     NEW ARRIVALS
═══════════════════════════════════════════════════════════════ --}}
<section style="padding:72px 0;background:#F7F4EF;">
    <div style="max-width:1400px;margin:0 auto;padding:0 40px;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:40px;">
            <div>
                <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:8px;">Just In</p>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3.5vw,44px);font-weight:600;color:#0D0D0D;line-height:1.1;">New Arrivals</h2>
            </div>
            <a href="{{ route('shop', ['new_arrival' => 1]) }}"
               style="font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;text-decoration:none;border-bottom:1px solid #0D0D0D;padding-bottom:2px;white-space:nowrap;"
               onmouseover="this.style.color='#C9A96E';this.style.borderColor='#C9A96E'"
               onmouseout="this.style.color='#0D0D0D';this.style.borderColor='#0D0D0D'">View All</a>
        </div>
        @if($newArrivals->isNotEmpty())
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
                @foreach($newArrivals as $product)
                    <x-storefront.product-card :product="$product" />
                @endforeach
            </div>
        @else
            <x-storefront.empty-state title="New Arrivals Coming Soon" description="Our upcoming seasonal drops will be added soon." actionText="Explore Categories" actionUrl="{{ route('shop') }}" />
        @endif
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════
     DARK EDITORIAL CTA BANNER
═══════════════════════════════════════════════════════════════ --}}
<section style="background:#0D0D0D;padding:88px 40px;text-align:center;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 20% 50%,rgba(201,169,110,0.09) 0%,transparent 60%),radial-gradient(ellipse at 80% 50%,rgba(201,169,110,0.07) 0%,transparent 60%);pointer-events:none;"></div>
    <div style="position:relative;z-index:1;max-width:620px;margin:0 auto;">
        <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:16px;">Explore Our Collection</p>
        <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(34px,5vw,60px);font-weight:600;color:#FFFFFF;line-height:1.08;margin-bottom:20px;">
            Find Their Next <em style="font-style:italic;color:#C9A96E;">Favourite</em> Look
        </h2>
        <p style="font-size:15px;color:rgba(255,255,255,0.48);line-height:1.8;margin-bottom:36px;">Browse our complete collection of shirts, dresses, rompers, tops &amp; accessories.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('shop') }}"
               style="display:inline-flex;align-items:center;padding:14px 40px;background:#C9A96E;color:#FFFFFF;font-size:11px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;border:1px solid #C9A96E;transition:background 0.2s;"
               onmouseover="this.style.background='#a07830';this.style.borderColor='#a07830'"
               onmouseout="this.style.background='#C9A96E';this.style.borderColor='#C9A96E'">Explore Full Catalog</a>
            <a href="{{ route('shop', ['new_arrival' => 1]) }}"
               style="display:inline-flex;align-items:center;padding:14px 40px;background:transparent;color:#FFFFFF;font-size:11px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;border:1px solid rgba(255,255,255,0.3);transition:background 0.2s;"
               onmouseover="this.style.background='rgba(255,255,255,0.08)'"
               onmouseout="this.style.background='transparent'">New Arrivals</a>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════
     DYNAMIC CATALOG EXPLORER
═══════════════════════════════════════════════════════════════ --}}
<section style="padding:72px 0;background:#FFFFFF;">
    <div style="max-width:1400px;margin:0 auto;padding:0 40px;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:16px;">
            <div>
                <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:8px;">Curated for You</p>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3.5vw,44px);font-weight:600;color:#0D0D0D;line-height:1.1;">Dress Collection</h2>
            </div>
            <button type="button" wire:click="shuffleProducts"
                    style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#0D0D0D;color:#FFFFFF;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;border:1px solid #0D0D0D;cursor:pointer;"
                    onmouseover="this.style.background='#2C2C2C'" onmouseout="this.style.background='#0D0D0D'">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Shuffle
            </button>
        </div>

        <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;margin-bottom:36px;scrollbar-width:none;">
            <button type="button" wire:click="selectCategory('all')"
                    style="flex-shrink:0;padding:9px 22px;font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;white-space:nowrap;{{ $selectedCategorySlug === 'all' ? 'background:#0D0D0D;color:#FFFFFF;border:1px solid #0D0D0D;' : 'background:transparent;color:#2C2C2C;border:1px solid #E0DBD3;' }}">
                All
            </button>
            @foreach($categories as $ct)
                <button type="button" wire:click="selectCategory('{{ $ct->slug }}')"
                        style="flex-shrink:0;padding:9px 22px;font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;white-space:nowrap;{{ $selectedCategorySlug === $ct->slug ? 'background:#0D0D0D;color:#FFFFFF;border:1px solid #0D0D0D;' : 'background:transparent;color:#2C2C2C;border:1px solid #E0DBD3;' }}">
                    {{ $ct->name }} ({{ $ct->products_count }})
                </button>
            @endforeach
        </div>

        @if($showcaseProducts->isNotEmpty())
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
                @foreach($showcaseProducts as $product)
                    <x-storefront.product-card :product="$product" />
                @endforeach
            </div>
        @else
            <x-storefront.empty-state title="No Products in this Category" description="Products for this category will be available soon." actionText="Explore All" actionUrl="{{ route('shop') }}" />
        @endif
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════
     FEATURED COLLECTION
═══════════════════════════════════════════════════════════════ --}}
@if($featuredCollection && $featuredCollection->products->isNotEmpty())
<section style="padding:72px 0;background:#F7F4EF;">
    <div style="max-width:1400px;margin:0 auto;padding:0 40px;">
        <div style="position:relative;background:#0D0D0D;padding:52px 56px;margin-bottom:48px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:24px;overflow:hidden;">
            <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 90% 50%,rgba(201,169,110,0.14) 0%,transparent 55%);pointer-events:none;"></div>
            <div style="position:relative;z-index:1;">
                <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:12px;">Featured</p>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(26px,4vw,48px);font-weight:600;color:#FFFFFF;line-height:1.1;margin-bottom:10px;">{{ $featuredCollection->name }}</h2>
                @if($featuredCollection->description)
                    <p style="font-size:14px;color:rgba(255,255,255,0.5);max-width:480px;line-height:1.75;">{{ $featuredCollection->description }}</p>
                @endif
            </div>
            <a href="{{ route('shop') }}"
               style="position:relative;z-index:1;flex-shrink:0;display:inline-flex;align-items:center;padding:14px 36px;background:#C9A96E;color:#FFFFFF;font-size:11px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;border:1px solid #C9A96E;"
               onmouseover="this.style.background='#a07830'" onmouseout="this.style.background='#C9A96E'">Shop Collection</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
            @foreach($featuredCollection->products as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ═══════════════════════════════════════════════════════════════
     SHOP BY AGE
═══════════════════════════════════════════════════════════════ --}}
@if($ageGroups->isNotEmpty())
<section style="padding:56px 0;background:#EDE9E1;border-top:1px solid #E0DBD3;border-bottom:1px solid #E0DBD3;">
    <div style="max-width:1400px;margin:0 auto;padding:0 40px;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:28px;">
            <div>
                <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:8px;">For Every Stage</p>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(24px,3vw,38px);font-weight:600;color:#0D0D0D;line-height:1.1;">Shop by Age</h2>
            </div>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            @foreach($ageGroups as $ag)
                <a href="{{ route('shop', ['age_group' => $ag->slug]) }}"
                   style="display:inline-flex;align-items:center;padding:10px 24px;background:#FFFFFF;border:1px solid #E0DBD3;font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#2C2C2C;text-decoration:none;transition:all 0.2s;"
                   onmouseover="this.style.background='#0D0D0D';this.style.color='#FFFFFF';this.style.borderColor='#0D0D0D'"
                   onmouseout="this.style.background='#FFFFFF';this.style.color='#2C2C2C';this.style.borderColor='#E0DBD3'">{{ $ag->name }}</a>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ═══════════════════════════════════════════════════════════════
     SALE PRODUCTS
═══════════════════════════════════════════════════════════════ --}}
@if($saleProducts->isNotEmpty())
<section style="padding:72px 0;background:#FFFFFF;">
    <div style="max-width:1400px;margin:0 auto;padding:0 40px;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:40px;">
            <div>
                <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:8px;">Limited Time</p>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3.5vw,44px);font-weight:600;color:#0D0D0D;line-height:1.1;">Special Offers &amp; Sale</h2>
            </div>
            <a href="{{ route('shop', ['sale' => 1]) }}"
               style="font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;text-decoration:none;border-bottom:1px solid #0D0D0D;padding-bottom:2px;white-space:nowrap;"
               onmouseover="this.style.color='#C9A96E';this.style.borderColor='#C9A96E'"
               onmouseout="this.style.color='#0D0D0D';this.style.borderColor='#0D0D0D'">View All Deals</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
            @foreach($saleProducts as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ═══════════════════════════════════════════════════════════════
     BRAND VALUES
═══════════════════════════════════════════════════════════════ --}}
<section style="padding:72px 0;background:#F7F4EF;border-top:1px solid #E0DBD3;">
    <div style="max-width:1400px;margin:0 auto;padding:0 40px;">
        <div style="text-align:center;margin-bottom:56px;">
            <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:10px;">Our Promise</p>
            <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3.5vw,44px);font-weight:600;color:#0D0D0D;line-height:1.1;">Why Families Love Al Hayat Kids</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
            @foreach([
                ['icon'=>'🧵','h'=>'Comfort for Every Adventure','p'=>'Soft, breathable fabrics that keep up with active play, nap times, and daily adventures.'],
                ['icon'=>'✦', 'h'=>'Styles for Little Personalities','p'=>'Charming palettes, delightful patterns, and timeless cuts crafted for every little personality.'],
                ['icon'=>'🎀','h'=>'Thoughtful Details','p'=>'Flexible waistbands, tagless labels, and easy-snap closures — designed for stress-free dressing.'],
            ] as $v)
            <div style="padding:40px 36px;background:#FFFFFF;border:1px solid #E0DBD3;">
                <div style="font-size:26px;margin-bottom:20px;">{{ $v['icon'] }}</div>
                <h3 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:#0D0D0D;margin-bottom:12px;line-height:1.25;">{{ $v['h'] }}</h3>
                <p style="font-size:13px;color:#888;line-height:1.8;">{{ $v['p'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════
     ALL CATEGORIES (DB-driven)
═══════════════════════════════════════════════════════════════ --}}
@if($categories->isNotEmpty())
<section style="padding:72px 0;background:#FFFFFF;">
    <div style="max-width:1400px;margin:0 auto;padding:0 40px;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;">
            <div>
                <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:8px;">Discover</p>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3.5vw,44px);font-weight:600;color:#0D0D0D;line-height:1.1;">All Categories</h2>
            </div>
            <a href="{{ route('shop') }}"
               style="font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;text-decoration:none;border-bottom:1px solid #0D0D0D;padding-bottom:2px;white-space:nowrap;"
               onmouseover="this.style.color='#C9A96E';this.style.borderColor='#C9A96E'"
               onmouseout="this.style.color='#0D0D0D';this.style.borderColor='#0D0D0D'">View All</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;">
            @foreach($categories as $cat)
                @php
                    $ci=$cat->image; $cu=''; $hci=false;
                    if($ci){ if(\Illuminate\Support\Str::startsWith($ci,['http://','https://'])){ $cu=$ci;$hci=true; } elseif(\Illuminate\Support\Facades\Storage::disk('public')->exists($ci)){ $cu=asset('storage/'.$ci);$hci=true; } elseif(file_exists(public_path($ci))){ $cu=asset($ci);$hci=true; } }
                @endphp
                <a href="{{ route('shop',['category'=>$cat->slug]) }}"
                   style="display:block;text-decoration:none;border:1px solid #E0DBD3;overflow:hidden;transition:border-color 0.25s;"
                   onmouseover="this.style.borderColor='#C9A96E'" onmouseout="this.style.borderColor='#E0DBD3'">
                    <div style="aspect-ratio:4/3;overflow:hidden;background:#EDE9E1;">
                        @if($hci)
                            <img src="{{ $cu }}" alt="{{ $cat->name }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s;" class="cat-zi" loading="lazy">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#C9A96E;">
                                <svg width="30" height="30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div style="padding:14px 16px;background:#FFFFFF;">
                        <h3 style="font-size:13px;font-weight:600;color:#0D0D0D;margin-bottom:2px;">{{ $cat->name }}</h3>
                        <span style="font-size:11px;color:#888;">{{ $cat->products_count ?? 0 }} items</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
<style>a:hover .cat-zi { transform:scale(1.05); }</style>
@endif


{{-- Responsive --}}
<style>
@media (max-width:1024px) {
    #hero-slider { height:75vh; }
}
@media (max-width:768px) {
    #hero-slider { height:68vh; min-height:420px; }
    div[style*="grid-template-columns:repeat(4,1fr)"] { grid-template-columns:repeat(2,1fr) !important; }
    div[style*="grid-template-columns:repeat(3,1fr)"] { grid-template-columns:1fr !important; }
    div[style*="padding:0 40px"] { padding:0 20px !important; }
    div[style*="padding:88px 40px"] { padding:56px 20px !important; }
    div[style*="padding:52px 56px"] { padding:36px 28px !important; }
    #hero-slider [style*="padding-bottom:56px"] { padding-bottom:48px !important; }
}
@media (max-width:480px) {
    #hero-slider { height:78vh; }
    #hero-slider h2 { font-size:26px !important; }
    div[style*="grid-template-columns:repeat(4,1fr)"] { grid-template-columns:repeat(2,1fr) !important; gap:10px !important; }
}
</style>

</div>
