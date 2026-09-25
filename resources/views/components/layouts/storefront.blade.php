<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Al Hayat Kids — Luxury Children\'s Fashion' }}</title>
    <meta name="description" content="Discover stylish, comfortable, and high-quality clothing for newborn, baby, girls, and boys at Al Hayat Kids Pakistan.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

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
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        @keyframes topBarMarquee {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-50%, 0, 0); }
        }
        .top-announcement-bar {
            background-color: #000000 !important; /* Sleek Black */
            color: #ffffff !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .animate-top-ticker {
            display: flex;
            width: max-content;
            animation: topBarMarquee 42s linear infinite;
        }
        .animate-top-ticker:hover {
            animation-play-state: paused;
        }
        .ticker-fade-left {
            background: linear-gradient(to right, #000000 0%, rgba(0, 0, 0, 0) 100%);
        }
        .ticker-fade-right {
            background: linear-gradient(to left, #000000 0%, rgba(0, 0, 0, 0) 100%);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased flex flex-col justify-between overflow-x-hidden" x-data="{ mobileMenuOpen: false }">

    <!-- Top Announcement Bar (Sleek Black Ticker) -->
    <div class="top-announcement-bar text-xs sm:text-sm py-2.5 overflow-hidden relative select-none z-50 shadow-xs">
        <!-- Soft Fade overlays on edges -->
        <div class="absolute left-0 top-0 bottom-0 w-10 sm:w-20 ticker-fade-left pointer-events-none z-10"></div>
        <div class="absolute right-0 top-0 bottom-0 w-10 sm:w-20 ticker-fade-right pointer-events-none z-10"></div>

        <div class="animate-top-ticker flex items-center">
            <!-- Set 1 -->
            <div class="flex items-center gap-12 sm:gap-16 px-6 shrink-0 tracking-wide font-semibold text-white">
                <span class="inline-flex items-center gap-2">
                    <span style="font-size: 1rem;">✨</span>
                    <span style="background: rgba(255,255,255,0.22); color: #ffffff; padding: 2.5px 12px; border-radius: 9999px; font-weight: 800; letter-spacing: 0.04em;">WELCOME TO AL HAYAT KIDS</span>
                    <span style="color: #ffffff;">•</span>
                    <span style="color: #ffffff; font-weight: 600;">Rs 350 Delivery Across Pakistan — Pay in advance & send us screenshot on WhatsApp <strong style="color: #ffffff; font-weight: 800;">0324-9171213</strong> to confirm your order <strong style="color: #ffffff; font-weight: 800;">(COD not available) 🚚</strong></span>
                </span>

                <span style="color: #ffffff; font-weight: 800;">✦</span>

                <span class="inline-flex items-center gap-2">
                    <span style="font-size: 1rem;">📲</span>
                    <span style="background: rgba(255,255,255,0.22); color: #ffffff; padding: 2.5px 12px; border-radius: 9999px; font-weight: 800; letter-spacing: 0.04em;">WELCOME TO AL HAYAT KIDS</span>
                    <span style="color: #ffffff;">•</span>
                    <span style="color: #ffffff; font-weight: 600;">Pay in advance & send screenshot on WhatsApp <strong style="color: #ffffff; font-weight: 800;">0324-9171213</strong> — Rs 350 Delivery Across Pakistan <strong style="color: #ffffff; font-weight: 800;">(COD not available) 💬</strong></span>
                </span>

                <span style="color: #ffffff; font-weight: 800;">✦</span>
            </div>

            <!-- Set 2 (Exact Duplicate for continuous seamless loop) -->
            <div class="flex items-center gap-12 sm:gap-16 px-6 shrink-0 tracking-wide font-semibold text-white" aria-hidden="true">
                <span class="inline-flex items-center gap-2">
                    <span style="font-size: 1rem;">✨</span>
                    <span style="background: rgba(255,255,255,0.22); color: #ffffff; padding: 2.5px 12px; border-radius: 9999px; font-weight: 800; letter-spacing: 0.04em;">WELCOME TO AL HAYAT KIDS</span>
                    <span style="color: #ffffff;">•</span>
                    <span style="color: #ffffff; font-weight: 600;">Rs 350 Delivery Across Pakistan — Pay in advance & send us screenshot on WhatsApp <strong style="color: #ffffff; font-weight: 800;">0324-9171213</strong> to confirm your order <strong style="color: #ffffff; font-weight: 800;">(COD not available) 🚚</strong></span>
                </span>

                <span style="color: #ffffff; font-weight: 800;">✦</span>

                <span class="inline-flex items-center gap-2">
                    <span style="font-size: 1rem;">📲</span>
                    <span style="background: rgba(255,255,255,0.22); color: #ffffff; padding: 2.5px 12px; border-radius: 9999px; font-weight: 800; letter-spacing: 0.04em;">WELCOME TO AL HAYAT KIDS</span>
                    <span style="color: #ffffff;">•</span>
                    <span style="color: #ffffff; font-weight: 600;">Pay in advance & send screenshot on WhatsApp <strong style="color: #ffffff; font-weight: 800;">0324-9171213</strong> — Rs 350 Delivery Across Pakistan <strong style="color: #ffffff; font-weight: 800;">(COD not available) 💬</strong></span>
                </span>

                <span style="color: #ffffff; font-weight: 800;">✦</span>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-3 sm:py-4 gap-4 md:gap-6">
                
                <!-- Logo & Brand Name -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Al Hayat Kids Logo" class="h-12 sm:h-16 lg:h-18 w-auto object-contain group-hover:scale-105 transition-transform duration-300" style="max-height: 64px; width: auto;">
                    <div class="flex flex-col">
                        <span class="font-extrabold text-xl sm:text-2xl lg:text-3xl text-slate-900 font-heading tracking-tight leading-none group-hover:text-rose-600 transition duration-200">
                            Al Hayat <span class="text-rose-600">Kids</span>
                        </span>
                        <span class="text-[10px] sm:text-xs font-bold text-slate-500 tracking-widest uppercase mt-0.5 sm:mt-1">Luxury Children's Wear</span>
                    </div>
                </a>

                <!-- Desktop Search Interface -->
                <form action="{{ route('shop') }}" method="GET" class="hidden md:flex flex-1 max-w-md mx-4">
                    <div class="relative w-full flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search kids dresses, categories, newborn sets..." 
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-100/90 border border-slate-200/90 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 focus:bg-white transition duration-200"
                               aria-label="Search storefront products">
                    </div>
                </form>

                <!-- Right Action Icons & Auth -->
                <div class="flex items-center gap-2 sm:gap-3">
                    
                    <!-- Wishlist Icon -->
                    <a href="{{ route('shop') }}" title="Wishlist" aria-label="Wishlist" class="p-2.5 sm:p-3 rounded-2xl text-slate-600 hover:text-rose-600 hover:bg-rose-50 transition relative border border-transparent hover:border-rose-100">
                        <svg class="w-5.5 h-5.5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span class="absolute top-1 right-1 w-4 h-4 sm:w-4.5 sm:h-4.5 bg-rose-500 text-white rounded-full text-[10px] font-bold flex items-center justify-center shadow-xs">0</span>
                    </a>

                    <!-- Cart Livewire Component -->
                    <livewire:storefront.cart-header-count />

                    <!-- Account Navigation UI -->
                    <div class="hidden sm:flex items-center gap-2 border-l border-slate-200 pl-3 ml-1">
                        @guest
                            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-rose-600 px-3 py-2 rounded-xl hover:bg-slate-100 transition">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 px-4 py-2 rounded-xl transition shadow-xs hover:shadow-md">
                                    Register
                                </a>
                            @endif
                        @endguest

                        @auth
                            <div class="flex items-center gap-2">
                                @if(in_array(auth()->user()->role, ['admin', 'staff']))
                                    <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200 px-3 py-2 rounded-xl transition">
                                        Admin Panel
                                    </a>
                                @endif

                                <a href="{{ route('dashboard') }}" class="text-sm font-bold text-slate-700 hover:text-rose-600 px-3 py-2 rounded-xl hover:bg-slate-100 transition">
                                    My Account
                                </a>

                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-rose-600 p-2 rounded-lg hover:bg-slate-100 transition" title="Logout">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @endauth
                    </div>

                    <!-- Mobile Menu Trigger -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            aria-label="Open Navigation Menu"
                            class="md:hidden p-2 text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Desktop Category Navigation Bar -->
            @php
                $navCategories = \App\Models\Category::whereNull('parent_id')->where('status', true)->orderBy('sort_order')->get();
                $deskAgeAdded = false;
            @endphp
            <nav class="hidden md:flex items-center justify-center py-3 border-t border-slate-100 text-sm font-bold text-slate-700 tracking-wide gap-8">
                <a href="{{ route('shop') }}" class="hover:text-rose-600 transition">Shop All</a>
                <a href="{{ route('shop', ['new_arrival' => 1]) }}" class="hover:text-rose-600 transition">New Arrivals</a>
                @foreach($navCategories as $navCat)
                    <a href="{{ route('shop', ['category' => $navCat->slug]) }}" class="hover:text-rose-600 transition">{{ $navCat->name }}</a>
                    @if(strtolower(trim($navCat->name)) === 'newborn')
                        @php $deskAgeAdded = true; @endphp
                        <!-- Shop by Age Dropdown Menu -->
                        <div class="relative inline-block text-left" x-data="{ open: false }" @mouseleave="open = false">
                            <button @click="open = !open" @mouseenter="open = true" class="flex items-center gap-1 hover:text-rose-600 transition focus:outline-none cursor-pointer py-1">
                                <span>Shop by Age</span>
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Popup Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                 class="absolute left-1/2 -translate-x-1/2 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-3 z-50 overflow-hidden divide-y divide-slate-100/60"
                                 style="display: none;"
                                 @click.away="open = false">
                                
                                <div class="px-4 py-2 text-[11px] font-extrabold uppercase tracking-wider text-rose-600 bg-rose-50/60 flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">👶 Select Age Group</span>
                                </div>

                                <div class="py-1">
                                    <a href="{{ route('shop', ['age_group' => '0-3-months']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition">
                                        <span>0 to 3 Months</span>
                                        <span class="text-[10px] text-slate-400 font-normal">Infant</span>
                                    </a>
                                    <a href="{{ route('shop', ['age_group' => '3-6-months']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition">
                                        <span>3 to 6 Months</span>
                                        <span class="text-[10px] text-slate-400 font-normal">Infant</span>
                                    </a>
                                    <a href="{{ route('shop', ['age_group' => '6-9-months']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition">
                                        <span>6 to 9 Months</span>
                                        <span class="text-[10px] text-slate-400 font-normal">Baby</span>
                                    </a>
                                    <a href="{{ route('shop', ['age_group' => '9-12-months']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition">
                                        <span>9 to 12 Months</span>
                                        <span class="text-[10px] text-slate-400 font-normal">Baby</span>
                                    </a>
                                    <a href="{{ route('shop', ['age_group' => '1-2-years']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition">
                                        <span>1 to 2 Years</span>
                                        <span class="text-[10px] text-slate-400 font-normal">Toddler</span>
                                    </a>
                                    <a href="{{ route('shop', ['age_group' => '3-4-years']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition">
                                        <span>3 to 4 Years</span>
                                        <span class="text-[10px] text-slate-400 font-normal">Kids</span>
                                    </a>
                                    <a href="{{ route('shop', ['age_group' => '5-6-years']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition">
                                        <span>5 to 6 Years</span>
                                        <span class="text-[10px] text-slate-400 font-normal">Kids</span>
                                    </a>
                                    <a href="{{ route('shop', ['age_group' => '7-8-years']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition">
                                        <span>7 to 8 Years</span>
                                        <span class="text-[10px] text-slate-400 font-normal">Kids</span>
                                    </a>
                                    <a href="{{ route('shop', ['age_group' => '9-12-years']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition">
                                        <span>9 to 12 Years</span>
                                        <span class="text-[10px] text-slate-400 font-normal">Junior</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if(!$deskAgeAdded)
                    <!-- Fallback Shop by Age Dropdown if Newborn is not present in navCategories loop -->
                    <div class="relative inline-block text-left" x-data="{ open: false }" @mouseleave="open = false">
                        <button @click="open = !open" @mouseenter="open = true" class="flex items-center gap-1 hover:text-rose-600 transition focus:outline-none cursor-pointer py-1">
                            <span>Shop by Age</span>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             class="absolute left-1/2 -translate-x-1/2 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-3 z-50 overflow-hidden divide-y divide-slate-100/60"
                             style="display: none;"
                             @click.away="open = false">
                            <div class="px-4 py-2 text-[11px] font-extrabold uppercase tracking-wider text-rose-600 bg-rose-50/60 flex items-center justify-between">
                                <span class="flex items-center gap-1.5">👶 Select Age Group</span>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('shop', ['age_group' => '0-3-months']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition"><span>0 to 3 Months</span><span class="text-[10px] text-slate-400 font-normal">Infant</span></a>
                                <a href="{{ route('shop', ['age_group' => '3-6-months']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition"><span>3 to 6 Months</span><span class="text-[10px] text-slate-400 font-normal">Infant</span></a>
                                <a href="{{ route('shop', ['age_group' => '6-9-months']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition"><span>6 to 9 Months</span><span class="text-[10px] text-slate-400 font-normal">Baby</span></a>
                                <a href="{{ route('shop', ['age_group' => '9-12-months']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition"><span>9 to 12 Months</span><span class="text-[10px] text-slate-400 font-normal">Baby</span></a>
                                <a href="{{ route('shop', ['age_group' => '1-2-years']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition"><span>1 to 2 Years</span><span class="text-[10px] text-slate-400 font-normal">Toddler</span></a>
                                <a href="{{ route('shop', ['age_group' => '3-4-years']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition"><span>3 to 4 Years</span><span class="text-[10px] text-slate-400 font-normal">Kids</span></a>
                                <a href="{{ route('shop', ['age_group' => '5-6-years']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition"><span>5 to 6 Years</span><span class="text-[10px] text-slate-400 font-normal">Kids</span></a>
                                <a href="{{ route('shop', ['age_group' => '7-8-years']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition"><span>7 to 8 Years</span><span class="text-[10px] text-slate-400 font-normal">Kids</span></a>
                                <a href="{{ route('shop', ['age_group' => '9-12-years']) }}" class="flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition"><span>9 to 12 Years</span><span class="text-[10px] text-slate-400 font-normal">Junior</span></a>
                            </div>
                        </div>
                    </div>
                @endif

                <a href="{{ route('shop', ['sale' => 1]) }}" class="text-rose-600 font-extrabold hover:text-rose-700 transition">Sale</a>
            </nav>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 md:hidden" style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60" @click="mobileMenuOpen = false"></div>

        <!-- Drawer Content -->
        <div class="fixed inset-y-0 left-0 w-4/5 max-w-sm bg-white shadow-2xl flex flex-col justify-between p-6 overflow-y-auto">
            <div class="space-y-6">
                <!-- Mobile Drawer Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Al Hayat Kids Logo" class="h-10 w-auto" style="max-height: 40px; width: auto;">
                        <span class="font-extrabold text-xl text-slate-900 font-heading">Al Hayat Kids</span>
                    </a>
                    <button @click="mobileMenuOpen = false" class="text-slate-400 hover:text-slate-700 p-1" aria-label="Close menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Mobile Search Form -->
                <form action="{{ route('shop') }}" method="GET">
                    <div class="relative w-full flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search kidswear..." 
                               class="w-full pl-9 pr-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500"
                               aria-label="Search products mobile">
                    </div>
                </form>

                <!-- Mobile Navigation Links -->
                <nav class="flex flex-col space-y-3 font-bold text-slate-800 text-sm">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 pt-2">Categories</div>
                    <a href="{{ route('shop') }}" class="py-1.5 hover:text-rose-600 transition">Shop All</a>
                    <a href="{{ route('shop', ['new_arrival' => 1]) }}" class="py-1.5 hover:text-rose-600 transition">New Arrivals</a>
                    @php $mobileAgeAdded = false; @endphp
                    @foreach($navCategories as $navCat)
                        <a href="{{ route('shop', ['category' => $navCat->slug]) }}" class="py-1.5 hover:text-rose-600 transition">{{ $navCat->name }}</a>
                        @if(strtolower(trim($navCat->name)) === 'newborn')
                            @php $mobileAgeAdded = true; @endphp
                            <div x-data="{ ageOpen: false }" class="border-y border-slate-100 py-1 my-1">
                                <button @click="ageOpen = !ageOpen" class="flex items-center justify-between w-full py-1.5 text-slate-800 font-bold hover:text-rose-600 transition">
                                    <span>Shop by Age</span>
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': ageOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="ageOpen" class="pl-3 py-1 space-y-1.5 border-l-2 border-rose-400 ml-1 mt-1" style="display: none;">
                                    <a href="{{ route('shop', ['age_group' => '0-3-months']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">0 to 3 Months</a>
                                    <a href="{{ route('shop', ['age_group' => '3-6-months']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">3 to 6 Months</a>
                                    <a href="{{ route('shop', ['age_group' => '6-9-months']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">6 to 9 Months</a>
                                    <a href="{{ route('shop', ['age_group' => '9-12-months']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">9 to 12 Months</a>
                                    <a href="{{ route('shop', ['age_group' => '1-2-years']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">1 to 2 Years</a>
                                    <a href="{{ route('shop', ['age_group' => '3-4-years']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">3 to 4 Years</a>
                                    <a href="{{ route('shop', ['age_group' => '5-6-years']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">5 to 6 Years</a>
                                    <a href="{{ route('shop', ['age_group' => '7-8-years']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">7 to 8 Years</a>
                                    <a href="{{ route('shop', ['age_group' => '9-12-years']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">9 to 12 Years</a>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if(!$mobileAgeAdded)
                        <div x-data="{ ageOpen: false }" class="border-y border-slate-100 py-1 my-1">
                            <button @click="ageOpen = !ageOpen" class="flex items-center justify-between w-full py-1.5 text-slate-800 font-bold hover:text-rose-600 transition">
                                <span>Shop by Age</span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': ageOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="ageOpen" class="pl-3 py-1 space-y-1.5 border-l-2 border-rose-400 ml-1 mt-1" style="display: none;">
                                <a href="{{ route('shop', ['age_group' => '0-3-months']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">0 to 3 Months</a>
                                <a href="{{ route('shop', ['age_group' => '3-6-months']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">3 to 6 Months</a>
                                <a href="{{ route('shop', ['age_group' => '6-9-months']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">6 to 9 Months</a>
                                <a href="{{ route('shop', ['age_group' => '9-12-months']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">9 to 12 Months</a>
                                <a href="{{ route('shop', ['age_group' => '1-2-years']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">1 to 2 Years</a>
                                <a href="{{ route('shop', ['age_group' => '3-4-years']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">3 to 4 Years</a>
                                <a href="{{ route('shop', ['age_group' => '5-6-years']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">5 to 6 Years</a>
                                <a href="{{ route('shop', ['age_group' => '7-8-years']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">7 to 8 Years</a>
                                <a href="{{ route('shop', ['age_group' => '9-12-years']) }}" class="block py-1 text-xs font-semibold text-slate-600 hover:text-rose-600">9 to 12 Years</a>
                            </div>
                        </div>
                    @endif
                    <a href="{{ route('shop', ['sale' => 1]) }}" class="py-1.5 text-rose-600 font-extrabold hover:text-rose-700 transition">Sale</a>
                </nav>
            </div>

            <!-- Mobile Drawer Auth & Account Footer -->
            <div class="pt-6 border-t border-slate-100 space-y-3 text-sm font-medium">
                @guest
                    <a href="{{ route('login') }}" class="block w-full py-2.5 text-center font-bold text-slate-800 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block w-full py-2.5 text-center font-bold text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition shadow-xs">
                            Register Account
                        </a>
                    @endif
                @endguest

                @auth
                    @if(in_array(auth()->user()->role, ['admin', 'staff']))
                        <a href="{{ route('admin.dashboard') }}" class="block w-full py-2 text-center font-bold text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">
                            Admin Panel
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="block w-full py-2 text-center font-bold text-slate-800 bg-slate-100 rounded-xl">
                        My Account ({{ auth()->user()->name }})
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full py-2 text-center font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition">
                            Log out
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Content Slot -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Luxury Storefront Footer -->
    <footer style="background-color: #0b0f19;" class="text-slate-300 relative border-t border-slate-800 mt-20">
        <!-- Top Gradient Accent Line -->
        <div class="h-1 bg-gradient-to-r from-rose-500 via-amber-400 to-rose-600 w-full"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-12">
            
            <!-- Value Highlights Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pb-12 mb-12 border-b border-slate-800/80 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center shrink-0 border border-rose-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white font-heading">Flat Rs. 350 Delivery</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Nationwide delivery across Pakistan</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white font-heading">Easy Returns</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Hassle-free 7-day size exchange</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white font-heading">100% Guaranteed</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Premium fabrics & durable craftsmanship</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-sky-500/10 text-sky-400 flex items-center justify-center shrink-0 border border-sky-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white font-heading">Dedicated Support</h4>
                        <p class="text-xs text-slate-400 mt-0.5">WhatsApp & Email customer care</p>
                    </div>
                </div>
            </div>

            <!-- Main Footer Links Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800/80">
                
                <!-- Brand Info Column (2 cols wide on LG) -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3.5">
                        <div class="p-2 bg-white rounded-2xl shadow-sm inline-block">
                            <img src="{{ asset('images/logo.png') }}" alt="Al Hayat Kids Logo" class="h-12 w-auto object-contain" style="max-height: 48px; width: auto;">
                        </div>
                        <div class="flex flex-col">
                            <span class="font-extrabold text-2xl text-white font-heading tracking-wide">Al Hayat Kids</span>
                            <span class="text-[11px] text-rose-400 font-bold uppercase tracking-widest">Luxury Children's Wear</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                        Stylish, high-quality garments crafted for newborn, baby, girls, and boys across Pakistan. Dedicated to supreme comfort, durability, and charming designs for everyday adventures.
                    </p>
                    <div class="pt-1 flex items-center gap-3 text-xs text-slate-300">
                        <span class="px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700 text-slate-300 font-semibold flex items-center gap-1.5">
                            <span class="text-base">🇵🇰</span> Serving Families Nationwide
                        </span>
                    </div>
                </div>

                <!-- SHOP Column -->
                <div class="space-y-3.5 text-xs">
                    <h3 class="text-xs font-extrabold text-white uppercase tracking-wider font-heading">Shop Collections</h3>
                    <ul class="space-y-2.5 font-medium text-slate-400">
                        <li><a href="{{ route('shop') }}" class="hover:text-rose-400 transition">All Storefront</a></li>
                        <li><a href="{{ route('shop', ['new_arrival' => 1]) }}" class="hover:text-rose-400 transition">New Arrivals</a></li>
                        @foreach($navCategories as $navCat)
                            <li><a href="{{ route('shop', ['category' => $navCat->slug]) }}" class="hover:text-rose-400 transition">{{ $navCat->name }}</a></li>
                        @endforeach
                        <li><a href="{{ route('shop', ['sale' => 1]) }}" class="text-rose-400 font-bold hover:text-rose-300 transition">Special Sale Items</a></li>
                    </ul>
                </div>

                <!-- HELP Column -->
                <div class="space-y-3.5 text-xs">
                    <h3 class="text-xs font-extrabold text-white uppercase tracking-wider font-heading">Customer Care</h3>
                    <ul class="space-y-2.5 font-medium text-slate-400">
                        <li><a href="#" @click.prevent="" class="hover:text-rose-400 transition">Track Your Order</a></li>
                        <li><a href="#" @click.prevent="" class="hover:text-rose-400 transition">Contact Customer Support</a></li>
                        <li><a href="#" @click.prevent="" class="hover:text-rose-400 transition">Shipping & Delivery Policy</a></li>
                        <li><a href="#" @click.prevent="" class="hover:text-rose-400 transition">Returns & Exchange Guide</a></li>
                        <li><a href="#" @click.prevent="" class="hover:text-rose-400 transition">Kids Size Guide</a></li>
                    </ul>
                </div>

                <!-- ACCOUNT & COMPANY Column -->
                <div class="space-y-3.5 text-xs">
                    <h3 class="text-xs font-extrabold text-white uppercase tracking-wider font-heading">Account & Legal</h3>
                    <ul class="space-y-2.5 font-medium text-slate-400">
                        <li><a href="{{ route('login') }}" class="hover:text-rose-400 transition">Customer Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-rose-400 transition">Create an Account</a></li>
                        <li><a href="#" @click.prevent="" class="hover:text-rose-400 transition">About Al Hayat Kids</a></li>
                        <li><a href="#" @click.prevent="" class="hover:text-rose-400 transition">Privacy Policy</a></li>
                        <li><a href="#" @click.prevent="" class="hover:text-rose-400 transition">Terms & Conditions</a></li>
                    </ul>
                </div>

            </div>

            <!-- Footer Bottom Bar -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <p>© {{ date('Y') }} <span class="text-white font-semibold">Al Hayat Kids</span>. All rights reserved.</p>
                <div class="flex items-center gap-3 text-[11px] font-medium text-slate-300">
                    <span class="bg-slate-900 border border-slate-800 px-3 py-1.5 rounded-xl flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        100% Secure Checkout
                    </span>
                    <span class="bg-slate-900 border border-slate-800 px-3 py-1.5 rounded-xl text-slate-400">
                        Cash on Delivery Available
                    </span>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>


