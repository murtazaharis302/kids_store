<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Al Hayat Kids — Premium Children\'s Fashion' }}</title>
    <meta name="description" content="Discover stylish, comfortable, and high-quality clothing for newborn, baby, girls, and boys at Al Hayat Kids Pakistan.">

    <!-- Google Fonts: Outfit (brand/headings) + Cormorant Garamond (display) + Inter (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('style.css') }}">
        <link rel="stylesheet" href="/style.css">
        <link rel="stylesheet" href="/build/assets/app-BzJL9BKc.css">
        <script type="module" src="/build/assets/app-l0sNRNKZ.js"></script>
    @endif
    @livewireStyles

    <style>
        /* ============================================================
           AL HAYAT KIDS — SAPPHIRE-INSPIRED DESIGN SYSTEM
           Palette: #000000 (ink), #FFFFFF (white), #F7F4EF (cream),
                    #C9A96E (gold), #2C2C2C (charcoal), #888 (mid-gray)
        ============================================================ */
        :root {
            --ink:       #0D0D0D;
            --white:     #FFFFFF;
            --cream:     #F7F4EF;
            --cream-2:   #EDE9E1;
            --gold:      #C9A96E;
            --gold-light:#E8D5AA;
            --charcoal:  #2C2C2C;
            --mid:       #888888;
            --border:    #E0DBD3;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--white);
            color: var(--ink);
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* Outfit = brand/logo/headings, Cormorant = editorial display text */
        .font-brand   { font-family: 'Outfit', sans-serif; }
        .font-display { font-family: 'Cormorant Garamond', serif; }

        /* ── Announcement Bar ── */
        @keyframes ticker {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .ticker-wrap { overflow:hidden; position:relative; }
        .ticker-track {
            display: inline-flex;
            white-space: nowrap;
            animation: ticker 40s linear infinite;
        }
        .ticker-track:hover { animation-play-state: paused; }
        .ticker-item { display:inline-flex; align-items:center; gap:20px; padding:0 40px; }

        /* ── Header ── */
        #site-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            transition: box-shadow 0.3s;
        }
        #site-header.scrolled { box-shadow: 0 2px 24px rgba(0,0,0,0.08); }

        /* ── Navigation ── */
        .nav-link {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--charcoal);
            text-decoration: none;
            position: relative;
            padding-bottom: 2px;
            transition: color 0.2s;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            left: 0; right: 0; bottom: -2px;
            height: 1px;
            background: var(--ink);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.25s ease;
        }
        .nav-link:hover { color: var(--ink); }
        .nav-link:hover::after { transform: scaleX(1); }
        .nav-link.sale-link { color: var(--gold); }
        .nav-link.sale-link:hover { color: #a07830; }
        .nav-link.sale-link::after { background: var(--gold); }

        /* ── Dropdown ── */
        .dropdown-menu {
            position: absolute;
            top: calc(100% + 12px);
            left: 50%;
            transform: translateX(-50%);
            background: var(--white);
            border: 1px solid var(--border);
            min-width: 220px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.1);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.2s, transform 0.2s;
            transform: translateX(-50%) translateY(-6px);
        }
        .dropdown-wrapper:hover .dropdown-menu,
        .dropdown-menu:hover {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateX(-50%) translateY(0);
        }
        .dropdown-menu a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 18px;
            font-size: 12px;
            font-weight: 500;
            color: var(--charcoal);
            text-decoration: none;
            border-bottom: 1px solid var(--border);
            transition: background 0.15s, color 0.15s;
        }
        .dropdown-menu a:last-child { border-bottom: none; }
        .dropdown-menu a:hover { background: var(--cream); color: var(--ink); }
        .dropdown-menu a span.badge {
            font-size: 10px;
            color: var(--mid);
            font-weight: 400;
        }
        .dropdown-header {
            padding: 10px 18px 8px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--gold);
            border-bottom: 1px solid var(--border);
            background: var(--cream);
        }

        /* ── Mobile Drawer ── */
        #mobile-drawer {
            position: fixed;
            inset: 0;
            z-index: 200;
            display: none;
        }
        #mobile-drawer.open { display: block; }
        #mobile-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.5);
        }
        #mobile-panel {
            position: absolute;
            top: 0; left: 0; bottom: 0;
            width: 88%;
            max-width: 360px;
            background: var(--white);
            overflow-y: auto;
            transform: translateX(-100%);
            transition: transform 0.35s cubic-bezier(0.4,0,0.2,1);
        }
        #mobile-drawer.open #mobile-panel { transform: translateX(0); }

        /* ── Icon Buttons ── */
        .icon-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            color: var(--charcoal);
            border-radius: 0;
            transition: color 0.2s, background 0.2s;
            text-decoration: none;
            position: relative;
            background: transparent;
            border: none;
            cursor: pointer;
        }
        .icon-btn:hover { color: var(--ink); }
        .icon-btn .badge-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 16px; height: 16px;
            background: var(--ink);
            color: var(--white);
            border-radius: 50%;
            font-size: 9px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Footer ── */
        #site-footer {
            background: var(--ink);
            color: rgba(255,255,255,0.75);
        }
        #site-footer a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 13px;
            line-height: 2;
            transition: color 0.2s;
        }
        #site-footer a:hover { color: var(--white); }
        .footer-heading {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--white);
            margin-bottom: 16px;
        }
        .footer-divider { border-color: rgba(255,255,255,0.12); }

        /* ── Search Box ── */
        .search-input {
            background: var(--cream);
            border: 1px solid var(--border);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: var(--ink);
            padding: 10px 14px 10px 38px;
            width: 100%;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            border-radius: 0;
        }
        .search-input:focus {
            border-color: var(--ink);
            background: var(--white);
        }
        .search-input::placeholder { color: var(--mid); }

        /* ── Utility ── */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 36px;
            background: var(--ink);
            color: var(--white);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            border: 1px solid var(--ink);
            cursor: pointer;
        }
        .btn-primary:hover {
            background: transparent;
            color: var(--ink);
        }
        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 36px;
            background: transparent;
            color: var(--ink);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            border: 1px solid var(--ink);
            transition: background 0.2s, color 0.2s;
            cursor: pointer;
        }
        .btn-outline:hover {
            background: var(--ink);
            color: var(--white);
        }
        .btn-gold {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 36px;
            background: var(--gold);
            color: var(--white);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            border: 1px solid var(--gold);
            transition: background 0.2s;
            cursor: pointer;
        }
        .btn-gold:hover { background: #a07830; border-color: #a07830; }

        .section-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 8px;
        }
        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(28px, 4vw, 48px);
            font-weight: 600;
            color: var(--ink);
            line-height: 1.15;
        }
        .view-all-link {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--ink);
            text-decoration: none;
            border-bottom: 1px solid var(--ink);
            padding-bottom: 1px;
            transition: color 0.2s, border-color 0.2s;
        }
        .view-all-link:hover { color: var(--gold); border-color: var(--gold); }

        /* ── Smooth page wrapper ── */
        main { flex: 1; }
    </style>
</head>
<body class="flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false }">

    {{-- ══════════════════════════════════════════
         ANNOUNCEMENT BAR — Clean ticker
    ══════════════════════════════════════════ --}}
    <div style="background:#0D0D0D;" class="ticker-wrap select-none">
        <div style="position:absolute;left:0;top:0;bottom:0;width:48px;background:linear-gradient(to right,#0D0D0D,transparent);z-index:2;pointer-events:none;"></div>
        <div style="position:absolute;right:0;top:0;bottom:0;width:48px;background:linear-gradient(to left,#0D0D0D,transparent);z-index:2;pointer-events:none;"></div>
        <div class="ticker-track" style="padding:10px 0;">
            {{-- Item set A --}}
            <span class="ticker-item">
                <span style="font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#C9A96E;">Welcome to Al Hayat Kids</span>
                <span style="color:rgba(255,255,255,0.25);">✦</span>
                <span style="font-size:12px;font-weight:500;color:rgba(255,255,255,0.75);">Rs 350 Delivery Across Pakistan</span>
                <span style="color:rgba(255,255,255,0.25);">✦</span>
                <span style="font-size:12px;font-weight:500;color:rgba(255,255,255,0.75);">Pay in advance · WhatsApp <strong style="color:#fff;font-weight:700;">0324-9171213</strong></span>
                <span style="color:rgba(255,255,255,0.25);">✦</span>
                <span style="font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#C9A96E;">COD Not Available</span>
                <span style="color:rgba(255,255,255,0.25);">✦</span>
                <span style="font-size:12px;font-weight:500;color:rgba(255,255,255,0.75);">Premium Quality Kids Wear · Stitched &amp; Unstitched</span>
                <span style="color:rgba(255,255,255,0.25);">✦</span>
            </span>
            {{-- Item set B — exact duplicate for seamless loop --}}
            <span class="ticker-item" aria-hidden="true">
                <span style="font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#C9A96E;">Welcome to Al Hayat Kids</span>
                <span style="color:rgba(255,255,255,0.25);">✦</span>
                <span style="font-size:12px;font-weight:500;color:rgba(255,255,255,0.75);">Rs 350 Delivery Across Pakistan</span>
                <span style="color:rgba(255,255,255,0.25);">✦</span>
                <span style="font-size:12px;font-weight:500;color:rgba(255,255,255,0.75);">Pay in advance · WhatsApp <strong style="color:#fff;font-weight:700;">0324-9171213</strong></span>
                <span style="color:rgba(255,255,255,0.25);">✦</span>
                <span style="font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#C9A96E;">COD Not Available</span>
                <span style="color:rgba(255,255,255,0.25);">✦</span>
                <span style="font-size:12px;font-weight:500;color:rgba(255,255,255,0.75);">Premium Quality Kids Wear · Stitched &amp; Unstitched</span>
                <span style="color:rgba(255,255,255,0.25);">✦</span>
            </span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         MAIN HEADER
    ══════════════════════════════════════════ --}}
    <header id="site-header">
        <div style="max-width:1400px;margin:0 auto;padding:0 24px;">

            {{-- Top Row: Logo | Search | Icons --}}
            <div style="display:grid;grid-template-columns:1fr auto 1fr;align-items:center;padding:18px 0;gap:16px;">

                {{-- Left: Logo --}}
                <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:12px;text-decoration:none;width:fit-content;">
                    <img src="{{ asset('images/logo.png') }}" alt="Al Hayat Kids" style="height:52px;width:auto;object-fit:contain;" onerror="this.style.display='none'">
                    <div>
                        <div class="font-brand" style="font-size:22px;font-weight:800;color:#0D0D0D;line-height:1;letter-spacing:0.06em;text-transform:uppercase;">AL HAYAT KIDS</div>
                        <div style="font-size:9px;font-weight:500;letter-spacing:0.22em;text-transform:uppercase;color:#888;margin-top:3px;">Luxury Children's Wear</div>
                    </div>
                </a>

                {{-- Center: Search (desktop) --}}
                <form action="{{ route('shop') }}" method="GET" class="hidden md:block" style="min-width:340px;max-width:480px;width:100%;">
                    <div style="position:relative;">
                        <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:#888;pointer-events:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input class="search-input" type="text" name="search" value="{{ request('search') }}" placeholder="Search dresses, newborn sets, categories…" aria-label="Search products">
                    </div>
                </form>

                {{-- Right: Icons --}}
                <div style="display:flex;align-items:center;justify-content:flex-end;gap:4px;">

                    {{-- Wishlist --}}
                    <a href="{{ route('shop') }}" class="icon-btn" title="Wishlist" aria-label="Wishlist">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </a>

                    {{-- Cart --}}
                    <livewire:storefront.cart-header-count />

                    {{-- Account --}}
                    <div class="hidden sm:flex items-center" style="margin-left:8px;padding-left:12px;border-left:1px solid #E0DBD3;">
                        @guest
                            <a href="{{ route('login') }}" class="nav-link" style="margin-right:16px;">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-primary" style="padding:8px 20px;font-size:11px;">Register</a>
                            @endif
                        @endguest
                        @auth
                            <div style="display:flex;align-items:center;gap:8px;">
                                @if(in_array(auth()->user()->role, ['admin', 'staff']))
                                    <a href="{{ route('admin.dashboard') }}" style="font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#C9A96E;text-decoration:none;">Admin</a>
                                @endif
                                <a href="{{ route('dashboard') }}" class="nav-link">Account</a>
                                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="background:none;border:none;cursor:pointer;font-size:12px;font-weight:500;color:#888;padding:0;transition:color 0.2s;" onmouseover="this.style.color='#0D0D0D'" onmouseout="this.style.color='#888'">Logout</button>
                                </form>
                            </div>
                        @endauth
                    </div>

                    {{-- Mobile Menu Toggle --}}
                    <button class="icon-btn md:hidden" id="mobile-menu-btn" aria-label="Open menu" style="margin-left:4px;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    </button>
                </div>
            </div>

            {{-- Bottom Row: Desktop Navigation --}}
            @php
                $navCategories = \App\Models\Category::whereNull('parent_id')->where('status', true)->orderBy('sort_order')->get();
                $deskAgeAdded = false;
            @endphp
            <nav class="hidden md:flex" style="align-items:center;justify-content:center;gap:40px;padding:14px 0;border-top:1px solid #E0DBD3;">

                <a href="{{ route('shop') }}" class="nav-link">Shop All</a>
                <a href="{{ route('shop', ['new_arrival' => 1]) }}" class="nav-link">New Arrivals</a>

                @foreach($navCategories as $navCat)
                    <a href="{{ route('shop', ['category' => $navCat->slug]) }}" class="nav-link">{{ $navCat->name }}</a>
                    @if(strtolower(trim($navCat->name)) === 'newborn')
                        @php $deskAgeAdded = true; @endphp
                        <div class="dropdown-wrapper" style="position:relative;">
                            <button style="background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:4px;" class="nav-link">
                                Shop by Age
                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="dropdown-menu">
                                <div class="dropdown-header">Select Age Group</div>
                                <a href="{{ route('shop', ['age_group' => '0-3-months']) }}"><span>0–3 Months</span><span class="badge">Infant</span></a>
                                <a href="{{ route('shop', ['age_group' => '3-6-months']) }}"><span>3–6 Months</span><span class="badge">Infant</span></a>
                                <a href="{{ route('shop', ['age_group' => '6-9-months']) }}"><span>6–9 Months</span><span class="badge">Baby</span></a>
                                <a href="{{ route('shop', ['age_group' => '9-12-months']) }}"><span>9–12 Months</span><span class="badge">Baby</span></a>
                                <a href="{{ route('shop', ['age_group' => '1-2-years']) }}"><span>1–2 Years</span><span class="badge">Toddler</span></a>
                                <a href="{{ route('shop', ['age_group' => '3-4-years']) }}"><span>3–4 Years</span><span class="badge">Kids</span></a>
                                <a href="{{ route('shop', ['age_group' => '5-6-years']) }}"><span>5–6 Years</span><span class="badge">Kids</span></a>
                                <a href="{{ route('shop', ['age_group' => '7-8-years']) }}"><span>7–8 Years</span><span class="badge">Kids</span></a>
                                <a href="{{ route('shop', ['age_group' => '9-10-years']) }}"><span>9–10 Years</span><span class="badge">Junior</span></a>
                                <a href="{{ route('shop', ['age_group' => '11-12-years']) }}"><span>11–12 Years</span><span class="badge">Junior</span></a>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if(!$deskAgeAdded)
                    <div class="dropdown-wrapper" style="position:relative;">
                        <button style="background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:4px;" class="nav-link">
                            Shop by Age
                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="dropdown-menu">
                            <div class="dropdown-header">Select Age Group</div>
                            <a href="{{ route('shop', ['age_group' => '0-3-months']) }}"><span>0–3 Months</span><span class="badge">Infant</span></a>
                            <a href="{{ route('shop', ['age_group' => '3-6-months']) }}"><span>3–6 Months</span><span class="badge">Infant</span></a>
                            <a href="{{ route('shop', ['age_group' => '6-9-months']) }}"><span>6–9 Months</span><span class="badge">Baby</span></a>
                            <a href="{{ route('shop', ['age_group' => '9-12-months']) }}"><span>9–12 Months</span><span class="badge">Baby</span></a>
                            <a href="{{ route('shop', ['age_group' => '1-2-years']) }}"><span>1–2 Years</span><span class="badge">Toddler</span></a>
                            <a href="{{ route('shop', ['age_group' => '3-4-years']) }}"><span>3–4 Years</span><span class="badge">Kids</span></a>
                            <a href="{{ route('shop', ['age_group' => '5-6-years']) }}"><span>5–6 Years</span><span class="badge">Kids</span></a>
                            <a href="{{ route('shop', ['age_group' => '7-8-years']) }}"><span>7–8 Years</span><span class="badge">Kids</span></a>
                            <a href="{{ route('shop', ['age_group' => '9-10-years']) }}"><span>9–10 Years</span><span class="badge">Junior</span></a>
                            <a href="{{ route('shop', ['age_group' => '11-12-years']) }}"><span>11–12 Years</span><span class="badge">Junior</span></a>
                        </div>
                    </div>
                @endif

                <a href="{{ route('shop', ['sale' => 1]) }}" class="nav-link sale-link">Sale</a>
            </nav>
        </div>
    </header>

    {{-- ══════════════════════════════════════════
         MOBILE NAVIGATION DRAWER
    ══════════════════════════════════════════ --}}
    <div id="mobile-drawer">
        <div id="mobile-overlay"></div>
        <div id="mobile-panel">
            {{-- Panel Header --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #E0DBD3;">
                <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
                    <img src="{{ asset('images/logo.png') }}" alt="Al Hayat Kids" style="height:36px;width:auto;" onerror="this.style.display='none'">
                    <span class="font-display" style="font-size:18px;font-weight:600;color:#0D0D0D;">Al Hayat Kids</span>
                </a>
                <button id="mobile-close-btn" style="background:none;border:none;cursor:pointer;color:#888;padding:4px;" aria-label="Close menu">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Mobile Search --}}
            <div style="padding:20px 24px;border-bottom:1px solid #E0DBD3;">
                <form action="{{ route('shop') }}" method="GET">
                    <div style="position:relative;">
                        <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:#888;pointer-events:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input class="search-input" type="text" name="search" placeholder="Search kidswear…" aria-label="Search mobile">
                    </div>
                </form>
            </div>

            {{-- Mobile Nav Links --}}
            <nav style="padding:24px;display:flex;flex-direction:column;gap:0;">
                <div style="font-size:10px;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;color:#C9A96E;margin-bottom:12px;">Collections</div>
                @php $mobLinks = [
                    ['label'=>'Shop All','url'=>route('shop')],
                    ['label'=>'New Arrivals','url'=>route('shop',['new_arrival'=>1])],
                    ['label'=>'Sale','url'=>route('shop',['sale'=>1])],
                ]; @endphp
                @foreach($mobLinks as $ml)
                    <a href="{{ $ml['url'] }}" style="display:block;padding:12px 0;font-size:14px;font-weight:500;color:#0D0D0D;text-decoration:none;border-bottom:1px solid #E0DBD3;">{{ $ml['label'] }}</a>
                @endforeach
                @php $mobileNavCats = \App\Models\Category::whereNull('parent_id')->where('status', true)->orderBy('sort_order')->get(); @endphp
                @foreach($mobileNavCats as $mc)
                    <a href="{{ route('shop', ['category' => $mc->slug]) }}" style="display:block;padding:12px 0;font-size:14px;font-weight:500;color:#0D0D0D;text-decoration:none;border-bottom:1px solid #E0DBD3;">{{ $mc->name }}</a>
                @endforeach

                <div style="font-size:10px;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;color:#C9A96E;margin-top:24px;margin-bottom:12px;">Shop by Age</div>
                @foreach([['0-3-months','0–3 Months'],['3-6-months','3–6 Months'],['6-9-months','6–9 Months'],['9-12-months','9–12 Months'],['1-2-years','1–2 Years'],['3-4-years','3–4 Years'],['5-6-years','5–6 Years'],['7-8-years','7–8 Years'],['9-10-years','9–10 Years'],['11-12-years','11–12 Years']] as [$slug, $label])
                    <a href="{{ route('shop', ['age_group' => $slug]) }}" style="display:block;padding:10px 0;font-size:13px;font-weight:400;color:#2C2C2C;text-decoration:none;border-bottom:1px solid #E0DBD3;">{{ $label }}</a>
                @endforeach

                {{-- Mobile Auth --}}
                <div style="margin-top:28px;padding-top:20px;border-top:1px solid #E0DBD3;">
                    @guest
                        <a href="{{ route('login') }}" style="display:block;padding:12px 0;font-size:14px;font-weight:500;color:#0D0D0D;text-decoration:none;">Log in</a>
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" style="display:block;padding:12px 0;font-size:14px;font-weight:500;color:#C9A96E;text-decoration:none;">Create Account</a>
                        @endif
                    @endguest
                    @auth
                        <a href="{{ route('dashboard') }}" style="display:block;padding:12px 0;font-size:14px;font-weight:500;color:#0D0D0D;text-decoration:none;">My Account</a>
                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:none;border:none;cursor:pointer;font-size:14px;font-weight:500;color:#888;padding:12px 0;">Logout</button>
                        </form>
                    @endauth
                </div>
            </nav>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════════════ --}}
    <main>
        {{ $slot }}
    </main>

    {{-- ══════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════ --}}
    <footer id="site-footer">
        {{-- Footer Top Grid --}}
        <div style="max-width:1400px;margin:0 auto;padding:64px 24px 40px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:48px;">

            {{-- Brand Column --}}
            <div>
                <div class="font-display" style="font-size:24px;font-weight:600;color:#FFFFFF;margin-bottom:12px;letter-spacing:0.02em;">AL HAYAT <span style="color:#C9A96E;">KIDS</span></div>
                <p style="font-size:13px;line-height:1.8;color:rgba(255,255,255,0.55);max-width:220px;">Premium children's fashion — comfort, elegance &amp; quality crafted for every little moment.</p>
                <div style="margin-top:20px;display:flex;gap:12px;">
                    {{-- WhatsApp --}}
                    <a href="https://wa.me/923249171213" target="_blank" rel="noopener" style="width:36px;height:36px;border:1px solid rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6);transition:all 0.2s;text-decoration:none;" title="WhatsApp" onmouseover="this.style.borderColor='#C9A96E';this.style.color='#C9A96E'" onmouseout="this.style.borderColor='rgba(255,255,255,0.15)';this.style.color='rgba(255,255,255,0.6)'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                    {{-- Instagram placeholder --}}
                    <a href="#" style="width:36px;height:36px;border:1px solid rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6);transition:all 0.2s;text-decoration:none;" title="Instagram" onmouseover="this.style.borderColor='#C9A96E';this.style.color='#C9A96E'" onmouseout="this.style.borderColor='rgba(255,255,255,0.15)';this.style.color='rgba(255,255,255,0.6)'">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="1.5"/><circle cx="12" cy="12" r="4" stroke-width="1.5"/><circle cx="17.5" cy="6.5" r="0.5" fill="currentColor"/></svg>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <div class="footer-heading">Quick Links</div>
                <div style="display:flex;flex-direction:column;">
                    <a href="{{ route('shop') }}">Shop All</a>
                    <a href="{{ route('shop', ['new_arrival' => 1]) }}">New Arrivals</a>
                    <a href="{{ route('shop', ['sale' => 1]) }}">Sale</a>
                    @foreach(\App\Models\Category::whereNull('parent_id')->where('status', true)->take(4)->get() as $fc)
                        <a href="{{ route('shop', ['category' => $fc->slug]) }}">{{ $fc->name }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Customer Care --}}
            <div>
                <div class="footer-heading">Customer Care</div>
                <div style="display:flex;flex-direction:column;">
                    @auth
                        <a href="{{ route('dashboard') }}">My Account</a>
                        <a href="{{ route('dashboard') }}">My Orders</a>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}">Log In</a>
                        <a href="{{ route('register') }}">Create Account</a>
                    @endguest
                    <a href="https://wa.me/923249171213" target="_blank" rel="noopener">WhatsApp Support</a>
                </div>
            </div>

            {{-- Contact --}}
            <div>
                <div class="footer-heading">Contact</div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div style="font-size:13px;color:rgba(255,255,255,0.55);line-height:1.7;">
                        <div style="color:rgba(255,255,255,0.85);font-weight:600;margin-bottom:4px;">WhatsApp Orders</div>
                        <a href="https://wa.me/923249171213" style="color:#C9A96E;text-decoration:none;font-weight:600;">0324-9171213</a>
                    </div>
                    <div style="font-size:13px;color:rgba(255,255,255,0.55);line-height:1.7;">
                        <div style="color:rgba(255,255,255,0.85);font-weight:600;margin-bottom:2px;">Delivery</div>
                        Rs 350 nationwide via TCS / Leopards
                    </div>
                    <div style="font-size:13px;color:rgba(255,255,255,0.55);">
                        <div style="color:rgba(255,255,255,0.85);font-weight:600;margin-bottom:2px;">Payment</div>
                        Advance payment only · No COD
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Bottom --}}
        <div style="border-top:1px solid rgba(255,255,255,0.1);max-width:1400px;margin:0 auto;padding:20px 24px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;">
            <span style="font-size:12px;color:rgba(255,255,255,0.35);">&copy; {{ date('Y') }} Al Hayat Kids. All rights reserved.</span>
            <span style="font-size:12px;color:rgba(255,255,255,0.35);">Premium Children's Fashion · Pakistan</span>
        </div>
    </footer>

    @livewireScripts

    <script>
        // ── Mobile Drawer ──
        const drawer = document.getElementById('mobile-drawer');
        const overlay = document.getElementById('mobile-overlay');
        const openBtn = document.getElementById('mobile-menu-btn');
        const closeBtn = document.getElementById('mobile-close-btn');
        if(openBtn)  openBtn.addEventListener('click', () => drawer.classList.add('open'));
        if(closeBtn) closeBtn.addEventListener('click', () => drawer.classList.remove('open'));
        if(overlay)  overlay.addEventListener('click', () => drawer.classList.remove('open'));

        // ── Header scroll shadow ──
        const hdr = document.getElementById('site-header');
        window.addEventListener('scroll', () => {
            if(hdr) hdr.classList.toggle('scrolled', window.scrollY > 10);
        });
    </script>
</body>
</html>
