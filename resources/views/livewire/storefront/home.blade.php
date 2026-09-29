<div>

{{-- ═══════════════════════════════════════════════════════
     HERO SLIDER — Sapphire-style full-width carousel
     4 slides · Left/Right arrows · Dot indicators · Auto-play
═══════════════════════════════════════════════════════ --}}
<section id="hero-slider" style="position:relative;overflow:hidden;background:#111;width:100%;height:92vh;min-height:560px;max-height:860px;">

    {{-- ── SLIDES TRACK ── --}}
    <div id="slider-track" style="display:flex;height:100%;transition:transform 0.7s cubic-bezier(0.77,0,0.18,1);will-change:transform;">

        @php
        $slides = [
            [
                'image'    => asset('images/hero_kids_teal.png'),
                'label'    => 'New Season 2026',
                'title'    => 'Crafted for Little Moments',
                'italic'   => 'Little Moments',
                'sub'      => 'Discover our new season collection — premium kidswear designed for comfort, style & every childhood adventure.',
                'cta1_text'=> 'Shop New Arrivals',
                'cta1_url' => route('shop', ['new_arrival' => 1]),
                'cta2_text'=> 'Explore All',
                'cta2_url' => route('shop'),
                'align'    => 'left',
                'overlay'  => 'linear-gradient(to right, rgba(10,10,10,0.72) 0%, rgba(10,10,10,0.3) 55%, transparent 100%)',
            ],
            [
                'image'    => asset('images/slide_girls_collection.png'),
                'label'    => 'Girls Collection',
                'title'    => 'Elegance in Every Stitch',
                'italic'   => 'Every Stitch',
                'sub'      => 'Embroidered kurtis, floral sets & festive dresses — crafted for girls who love to shine.',
                'cta1_text'=> 'Shop Girls',
                'cta1_url' => route('shop', ['category' => 'girls']),
                'cta2_text'=> 'View All',
                'cta2_url' => route('shop'),
                'align'    => 'right',
                'overlay'  => 'linear-gradient(to left, rgba(10,10,10,0.72) 0%, rgba(10,10,10,0.3) 55%, transparent 100%)',
            ],
            [
                'image'    => asset('images/slide_eid_collection.png'),
                'label'    => 'Eid Festive Collection',
                'title'    => 'Dressed for the Occasion',
                'italic'   => 'the Occasion',
                'sub'      => 'Gold embroidery, rich fabrics & festive sets that make every Eid moment unforgettable.',
                'cta1_text'=> 'Shop Festive',
                'cta1_url' => route('shop', ['new_arrival' => 1]),
                'cta2_text'=> 'View All',
                'cta2_url' => route('shop'),
                'align'    => 'left',
                'overlay'  => 'linear-gradient(to right, rgba(10,10,10,0.70) 0%, rgba(10,10,10,0.25) 55%, transparent 100%)',
            ],
            [
                'image'    => asset('images/slide_newborn_collection.png'),
                'label'    => 'Newborn & Baby',
                'title'    => 'Gentle from Day One',
                'italic'   => 'Day One',
                'sub'      => 'Soft, breathable cotton sets for your tiniest. From 0–3 months all the way through toddler years.',
                'cta1_text'=> 'Shop Newborn',
                'cta1_url' => route('shop', ['category' => 'newborn']),
                'cta2_text'=> 'View All',
                'cta2_url' => route('shop'),
                'align'    => 'right',
                'overlay'  => 'linear-gradient(to left, rgba(10,10,10,0.70) 0%, rgba(10,10,10,0.25) 55%, transparent 100%)',
            ],
        ];
        @endphp

        @foreach($slides as $i => $slide)
        <div style="min-width:100%;height:100%;position:relative;flex-shrink:0;">
            {{-- Background Image --}}
            <img src="{{ $slide['image'] }}"
                 alt="{{ $slide['label'] }}"
                 style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center top;"
                 loading="{{ $i === 0 ? 'eager' : 'lazy' }}">

            {{-- Gradient overlay --}}
            <div style="position:absolute;inset:0;background:{{ $slide['overlay'] }};"></div>

            {{-- Slide Content --}}
            <div style="position:relative;z-index:2;height:100%;display:flex;align-items:center;padding:0 80px;max-width:1400px;margin:0 auto;{{ $slide['align'] === 'right' ? 'justify-content:flex-end;' : 'justify-content:flex-start;' }}">
                <div style="max-width:540px;{{ $slide['align'] === 'right' ? 'text-align:right;' : 'text-align:left;' }}">
                    {{-- Label --}}
                    <p style="font-size:11px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:18px;">
                        {{ $slide['label'] }}
                    </p>

                    {{-- Title --}}
                    <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(40px,5.5vw,74px);font-weight:600;color:#FFFFFF;line-height:1.08;margin-bottom:18px;">
                        @php
                            $parts = explode($slide['italic'], $slide['title']);
                        @endphp
                        @if(count($parts) === 2)
                            {{ $parts[0] }}<em style="font-style:italic;color:#C9A96E;">{{ $slide['italic'] }}</em>{{ $parts[1] }}
                        @else
                            {{ $slide['title'] }}
                        @endif
                    </h2>

                    {{-- Subtitle --}}
                    <p style="font-size:15px;color:rgba(255,255,255,0.72);line-height:1.75;margin-bottom:36px;font-weight:400;max-width:420px;{{ $slide['align'] === 'right' ? 'margin-left:auto;' : '' }}">
                        {{ $slide['sub'] }}
                    </p>

                    {{-- CTAs --}}
                    <div style="display:flex;gap:12px;flex-wrap:wrap;{{ $slide['align'] === 'right' ? 'justify-content:flex-end;' : 'justify-content:flex-start;' }}">
                        <a href="{{ $slide['cta1_url'] }}"
                           style="display:inline-flex;align-items:center;padding:14px 36px;background:#FFFFFF;color:#0D0D0D;font-size:11px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;border:1px solid #FFFFFF;transition:background 0.25s,color 0.25s;"
                           onmouseover="this.style.background='transparent';this.style.color='#FFFFFF'"
                           onmouseout="this.style.background='#FFFFFF';this.style.color='#0D0D0D'">
                            {{ $slide['cta1_text'] }}
                        </a>
                        <a href="{{ $slide['cta2_url'] }}"
                           style="display:inline-flex;align-items:center;padding:14px 36px;background:transparent;color:#FFFFFF;font-size:11px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;border:1px solid rgba(255,255,255,0.45);transition:background 0.25s,border-color 0.25s;"
                           onmouseover="this.style.background='rgba(255,255,255,0.12)';this.style.borderColor='rgba(255,255,255,0.8)'"
                           onmouseout="this.style.background='transparent';this.style.borderColor='rgba(255,255,255,0.45)'">
                            {{ $slide['cta2_text'] }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── LEFT ARROW ── --}}
    <button id="slider-prev"
            aria-label="Previous slide"
            style="position:absolute;left:24px;top:50%;transform:translateY(-50%);z-index:10;width:52px;height:52px;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.3);color:#FFFFFF;display:flex;align-items:center;justify-content:center;cursor:pointer;backdrop-filter:blur(6px);transition:background 0.2s,border-color 0.2s;"
            onmouseover="this.style.background='rgba(255,255,255,0.25)';this.style.borderColor='rgba(255,255,255,0.7)'"
            onmouseout="this.style.background='rgba(255,255,255,0.12)';this.style.borderColor='rgba(255,255,255,0.3)'">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>

    {{-- ── RIGHT ARROW ── --}}
    <button id="slider-next"
            aria-label="Next slide"
            style="position:absolute;right:24px;top:50%;transform:translateY(-50%);z-index:10;width:52px;height:52px;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.3);color:#FFFFFF;display:flex;align-items:center;justify-content:center;cursor:pointer;backdrop-filter:blur(6px);transition:background 0.2s,border-color 0.2s;"
            onmouseover="this.style.background='rgba(255,255,255,0.25)';this.style.borderColor='rgba(255,255,255,0.7)'"
            onmouseout="this.style.background='rgba(255,255,255,0.12)';this.style.borderColor='rgba(255,255,255,0.3)'">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>

    {{-- ── DOT INDICATORS ── --}}
    <div id="slider-dots" style="position:absolute;bottom:28px;left:50%;transform:translateX(-50%);z-index:10;display:flex;gap:8px;align-items:center;">
        @foreach($slides as $i => $slide)
            <button class="slider-dot"
                    data-index="{{ $i }}"
                    aria-label="Go to slide {{ $i + 1 }}"
                    style="width:{{ $i === 0 ? '28px' : '8px' }};height:8px;background:{{ $i === 0 ? '#FFFFFF' : 'rgba(255,255,255,0.4)' }};border:none;cursor:pointer;transition:all 0.35s ease;padding:0;border-radius:4px;">
            </button>
        @endforeach
    </div>

    {{-- ── SLIDE COUNTER ── --}}
    <div id="slide-counter" style="position:absolute;bottom:28px;right:36px;z-index:10;font-size:12px;font-weight:500;color:rgba(255,255,255,0.55);letter-spacing:0.08em;">
        <span id="current-slide">01</span> / <span>0{{ count($slides) }}</span>
    </div>
</section>

{{-- ── SLIDER JAVASCRIPT ── --}}
<script>
(function () {
    const track    = document.getElementById('slider-track');
    const prevBtn  = document.getElementById('slider-prev');
    const nextBtn  = document.getElementById('slider-next');
    const dots     = document.querySelectorAll('.slider-dot');
    const counter  = document.getElementById('current-slide');
    const total    = {{ count($slides) }};
    let current    = 0;
    let autoTimer  = null;
    let isAnimating = false;

    function goTo(index, instant) {
        if (isAnimating) return;
        isAnimating = true;

        // Wrap around
        current = ((index % total) + total) % total;

        // Move track
        track.style.transition = instant ? 'none' : 'transform 0.7s cubic-bezier(0.77,0,0.18,1)';
        track.style.transform  = `translateX(-${current * 100}%)`;

        // Update dots
        dots.forEach((dot, i) => {
            const active = i === current;
            dot.style.width      = active ? '28px'              : '8px';
            dot.style.background = active ? '#FFFFFF'           : 'rgba(255,255,255,0.4)';
        });

        // Update counter
        if (counter) counter.textContent = String(current + 1).padStart(2, '0');

        setTimeout(() => { isAnimating = false; }, 750);
    }

    function startAuto() {
        clearInterval(autoTimer);
        autoTimer = setInterval(() => goTo(current + 1), 5000);
    }

    function stopAuto() { clearInterval(autoTimer); }

    // Arrow buttons
    if (prevBtn) prevBtn.addEventListener('click', () => { stopAuto(); goTo(current - 1); startAuto(); });
    if (nextBtn) nextBtn.addEventListener('click', () => { stopAuto(); goTo(current + 1); startAuto(); });

    // Dot buttons
    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            stopAuto();
            goTo(parseInt(dot.dataset.index));
            startAuto();
        });
    });

    // Pause on hover
    const section = document.getElementById('hero-slider');
    if (section) {
        section.addEventListener('mouseenter', stopAuto);
        section.addEventListener('mouseleave', startAuto);
    }

    // Touch/swipe support
    let touchStartX = 0;
    if (section) {
        section.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
        section.addEventListener('touchend', e => {
            const diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) { stopAuto(); goTo(diff > 0 ? current + 1 : current - 1); startAuto(); }
        }, { passive: true });
    }

    // Init
    goTo(0, true);
    startAuto();
})();
</script>


{{-- ═══════════════════════════════════════════════════════
     TRUST BAR
═══════════════════════════════════════════════════════ --}}
<div style="background:#0D0D0D;padding:0;">
    <div style="max-width:1400px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);border-left:1px solid rgba(255,255,255,0.08);">
        @foreach([
            ['icon'=>'🧵', 'title'=>'Premium Fabric',    'sub'=>'100% breathable cotton'],
            ['icon'=>'🚚', 'title'=>'Rs 350 Delivery',   'sub'=>'Nationwide via TCS / Leopards'],
            ['icon'=>'📲', 'title'=>'WhatsApp Orders',   'sub'=>'0324-9171213'],
            ['icon'=>'🔒', 'title'=>'Advance Payment',   'sub'=>'Secure & simple'],
        ] as $t)
        <div style="display:flex;align-items:center;gap:14px;padding:22px 28px;border-right:1px solid rgba(255,255,255,0.08);">
            <span style="font-size:20px;flex-shrink:0;">{{ $t['icon'] }}</span>
            <div>
                <div style="font-size:12px;font-weight:700;color:#FFFFFF;letter-spacing:0.04em;">{{ $t['title'] }}</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">{{ $t['sub'] }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════
     4 EDITORIAL COLLECTION TILES  (Sapphire-style)
     Equal columns · Portrait image · Hover zoom · Text overlay
═══════════════════════════════════════════════════════ --}}
<section style="padding:72px 0;background:#FFFFFF;">
    <div style="max-width:1400px;margin:0 auto;padding:0 40px;">

        {{-- Section Header --}}
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;">
            <div>
                <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:8px;">The Collections</p>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3.5vw,44px);font-weight:600;color:#0D0D0D;line-height:1.1;">Shop by Category</h2>
            </div>
            <a href="{{ route('shop') }}"
               style="font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#0D0D0D;text-decoration:none;border-bottom:1px solid #0D0D0D;padding-bottom:2px;white-space:nowrap;transition:color 0.2s,border-color 0.2s;"
               onmouseover="this.style.color='#C9A96E';this.style.borderColor='#C9A96E'"
               onmouseout="this.style.color='#0D0D0D';this.style.borderColor='#0D0D0D'">View All Collections</a>
        </div>

        {{-- 4-Column Grid --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">

            @php
            $collections = [
                [
                    'image'  => asset('images/slide_girls_collection.png'),
                    'label'  => 'Girls',
                    'title'  => 'Garden Party',
                    'sub'    => 'Embroidered & Floral',
                    'url'    => route('shop', ['category' => 'girls']),
                ],
                [
                    'image'  => asset('images/article_boys_navy.png'),
                    'label'  => 'Boys',
                    'title'  => 'Little Gentlemen',
                    'sub'    => 'Classic & Contemporary',
                    'url'    => route('shop', ['category' => 'boys']),
                ],
                [
                    'image'  => asset('images/slide_newborn_collection.png'),
                    'label'  => 'Newborn',
                    'title'  => 'First Days',
                    'sub'    => 'Soft & Gentle Fabrics',
                    'url'    => route('shop', ['category' => 'newborn']),
                ],
                [
                    'image'  => asset('images/slide_eid_collection.png'),
                    'label'  => 'Eid Collection',
                    'title'  => 'Festive Glow',
                    'sub'    => 'Gold Embroidery Sets',
                    'url'    => route('shop', ['new_arrival' => 1]),
                ],
            ];
            @endphp

            @foreach($collections as $col)
            <a href="{{ $col['url'] }}"
               style="display:block;text-decoration:none;position:relative;overflow:hidden;aspect-ratio:3/4;background:#EDE9E1;"
               class="collection-tile">

                <img src="{{ $col['image'] }}"
                     alt="{{ $col['title'] }}"
                     style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:transform 0.65s cubic-bezier(0.25,0.46,0.45,0.94);"
                     class="collection-tile-img"
                     loading="lazy"
                     onerror="this.style.display='none'">

                {{-- Bottom gradient + text --}}
                <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,10,0.78) 0%,rgba(10,10,10,0.18) 45%,transparent 70%);"></div>

                <div style="position:absolute;bottom:0;left:0;right:0;padding:28px 24px;">
                    <p style="font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#C9A96E;margin-bottom:5px;">{{ $col['label'] }}</p>
                    <h3 style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:600;color:#FFFFFF;line-height:1.15;margin-bottom:5px;">{{ $col['title'] }}</h3>
                    <p style="font-size:12px;color:rgba(255,255,255,0.65);margin-bottom:16px;">{{ $col['sub'] }}</p>
                    <span style="font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#FFFFFF;border-bottom:1px solid rgba(255,255,255,0.55);padding-bottom:2px;transition:border-color 0.2s;">SHOP NOW</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<style>
    .collection-tile:hover .collection-tile-img { transform: scale(1.06); }
    .collection-tile:hover span { border-bottom-color: #C9A96E !important; color: #C9A96E !important; }
</style>


{{-- ═══════════════════════════════════════════════════════
     NEW ARRIVALS
═══════════════════════════════════════════════════════ --}}
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
            <x-storefront.empty-state
                title="New Arrivals Coming Soon"
                description="Our upcoming seasonal drops will be added soon. Check back shortly!"
                actionText="Explore Categories"
                actionUrl="{{ route('shop') }}" />
        @endif
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════
     EDITORIAL CTA BANNER — Dark full-width
═══════════════════════════════════════════════════════ --}}
<section style="background:#0D0D0D;padding:88px 40px;text-align:center;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 20% 50%,rgba(201,169,110,0.09) 0%,transparent 60%),radial-gradient(ellipse at 80% 50%,rgba(201,169,110,0.07) 0%,transparent 60%);pointer-events:none;"></div>
    <div style="position:relative;z-index:1;max-width:640px;margin:0 auto;">
        <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:16px;">Explore Our Collection</p>
        <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(36px,5vw,62px);font-weight:600;color:#FFFFFF;line-height:1.08;margin-bottom:20px;">
            Find Their Next <em style="font-style:italic;color:#C9A96E;">Favourite</em> Look
        </h2>
        <p style="font-size:15px;color:rgba(255,255,255,0.5);line-height:1.8;margin-bottom:36px;">
            Browse our complete collection of shirts, dresses, rompers, tops &amp; accessories.
        </p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('shop') }}"
               style="display:inline-flex;align-items:center;padding:14px 40px;background:#C9A96E;color:#FFFFFF;font-size:11px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;border:1px solid #C9A96E;transition:background 0.2s;"
               onmouseover="this.style.background='#a07830';this.style.borderColor='#a07830'"
               onmouseout="this.style.background='#C9A96E';this.style.borderColor='#C9A96E'">Explore Full Catalog</a>
            <a href="{{ route('shop', ['new_arrival' => 1]) }}"
               style="display:inline-flex;align-items:center;padding:14px 40px;background:transparent;color:#FFFFFF;font-size:11px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;border:1px solid rgba(255,255,255,0.35);transition:background 0.2s,border-color 0.2s;"
               onmouseover="this.style.background='rgba(255,255,255,0.08)';this.style.borderColor='rgba(255,255,255,0.7)'"
               onmouseout="this.style.background='transparent';this.style.borderColor='rgba(255,255,255,0.35)'">New Arrivals</a>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════
     DYNAMIC CATALOG EXPLORER
═══════════════════════════════════════════════════════ --}}
<section style="padding:72px 0;background:#FFFFFF;">
    <div style="max-width:1400px;margin:0 auto;padding:0 40px;">

        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:16px;">
            <div>
                <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#C9A96E;margin-bottom:8px;">Curated for You</p>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3.5vw,44px);font-weight:600;color:#0D0D0D;line-height:1.1;">Dress Collection</h2>
            </div>
            <button type="button"
                    wire:click="shuffleProducts"
                    style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#0D0D0D;color:#FFFFFF;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;border:1px solid #0D0D0D;cursor:pointer;transition:background 0.2s;"
                    onmouseover="this.style.background='#2C2C2C'"
                    onmouseout="this.style.background='#0D0D0D'">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Shuffle
            </button>
        </div>

        {{-- Filter tabs --}}
        <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;margin-bottom:36px;scrollbar-width:none;-ms-overflow-style:none;">
            <button type="button"
                    wire:click="selectCategory('all')"
                    style="flex-shrink:0;padding:9px 22px;font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;transition:all 0.2s;white-space:nowrap;{{ $selectedCategorySlug === 'all' ? 'background:#0D0D0D;color:#FFFFFF;border:1px solid #0D0D0D;' : 'background:transparent;color:#2C2C2C;border:1px solid #E0DBD3;' }}">
                All
            </button>
            @foreach($categories as $catTab)
                <button type="button"
                        wire:click="selectCategory('{{ $catTab->slug }}')"
                        style="flex-shrink:0;padding:9px 22px;font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;transition:all 0.2s;white-space:nowrap;{{ $selectedCategorySlug === $catTab->slug ? 'background:#0D0D0D;color:#FFFFFF;border:1px solid #0D0D0D;' : 'background:transparent;color:#2C2C2C;border:1px solid #E0DBD3;' }}">
                    {{ $catTab->name }} ({{ $catTab->products_count }})
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
            <x-storefront.empty-state
                title="No Products in this Category"
                description="Products for this category will be available soon."
                actionText="Explore All Outfits"
                actionUrl="{{ route('shop') }}" />
        @endif
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════
     FEATURED COLLECTION
═══════════════════════════════════════════════════════ --}}
@if($featuredCollection && $featuredCollection->products->isNotEmpty())
<section style="padding:72px 0;background:#F7F4EF;">
    <div style="max-width:1400px;margin:0 auto;padding:0 40px;">

        {{-- Banner --}}
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
               style="position:relative;z-index:1;flex-shrink:0;display:inline-flex;align-items:center;padding:14px 36px;background:#C9A96E;color:#FFFFFF;font-size:11px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;border:1px solid #C9A96E;transition:background 0.2s;"
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


{{-- ═══════════════════════════════════════════════════════
     SHOP BY AGE
═══════════════════════════════════════════════════════ --}}
@if($ageGroups->isNotEmpty())
<section style="padding:60px 0;background:#EDE9E1;border-top:1px solid #E0DBD3;border-bottom:1px solid #E0DBD3;">
    <div style="max-width:1400px;margin:0 auto;padding:0 40px;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
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
                   onmouseout="this.style.background='#FFFFFF';this.style.color='#2C2C2C';this.style.borderColor='#E0DBD3'">
                    {{ $ag->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ═══════════════════════════════════════════════════════
     SALE PRODUCTS
═══════════════════════════════════════════════════════ --}}
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


{{-- ═══════════════════════════════════════════════════════
     BRAND VALUES — 3 pillars
═══════════════════════════════════════════════════════ --}}
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


{{-- ═══════════════════════════════════════════════════════
     SHOP BY CATEGORY  (DB-driven)
═══════════════════════════════════════════════════════ --}}
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
                    $ci = $cat->image; $cu=''; $hci=false;
                    if($ci){ if(\Illuminate\Support\Str::startsWith($ci,['http://','https://'])){ $cu=$ci;$hci=true; } elseif(\Illuminate\Support\Facades\Storage::disk('public')->exists($ci)){ $cu=asset('storage/'.$ci);$hci=true; } elseif(file_exists(public_path($ci))){ $cu=asset($ci);$hci=true; } }
                @endphp
                <a href="{{ route('shop', ['category' => $cat->slug]) }}"
                   style="display:block;text-decoration:none;border:1px solid #E0DBD3;overflow:hidden;transition:border-color 0.25s;"
                   onmouseover="this.style.borderColor='#C9A96E'"
                   onmouseout="this.style.borderColor='#E0DBD3'">
                    <div style="aspect-ratio:4/3;overflow:hidden;background:#EDE9E1;">
                        @if($hci)
                            <img src="{{ $cu }}" alt="{{ $cat->name }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s;" class="cat-zoom" loading="lazy">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#C9A96E;">
                                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
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
<style>a:hover .cat-zoom { transform: scale(1.05); }</style>
@endif


{{-- Responsive fixes --}}
<style>
@media (max-width: 1024px) {
    #hero-slider { height: 75vh; }
    #hero-slider [style*="padding:0 80px"] { padding: 0 40px !important; }
}
@media (max-width: 768px) {
    #hero-slider { height: 70vh; min-height: 480px; }
    #hero-slider [style*="padding:0 80px"] { padding: 0 24px !important; }
    #slider-prev { left: 12px !important; width: 40px !important; height: 40px !important; }
    #slider-next { right: 12px !important; width: 40px !important; height: 40px !important; }

    /* Trust bar → 2 cols on mobile */
    #trust-bar-grid { grid-template-columns: repeat(2,1fr) !important; }

    /* 4-col → 2-col on mobile */
    .four-col { grid-template-columns: repeat(2,1fr) !important; }
    .three-col { grid-template-columns: 1fr !important; }

    /* Sections padding */
    section > div { padding-left: 20px !important; padding-right: 20px !important; }
}
@media (max-width: 480px) {
    #hero-slider { height: 85vh; }
    .four-col { grid-template-columns: repeat(2,1fr) !important; gap: 10px !important; }
    .collection-tile h3 { font-size: 18px !important; }
}
</style>

</div>
