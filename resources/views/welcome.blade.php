<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Kelana') }} - TripAdvisor-Style Discovery Page</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-warm-cream text-near-black font-sans antialiased min-h-screen flex flex-col" x-data="Object.assign({ scrolled: false, activeCategory: 'all' }, wishlistCartData())" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- 1. Floating Navbar -->
    <nav class="w-full fixed top-0 left-0 z-50 transition-all duration-300 border-b"
         :class="scrolled ? 'bg-[#0f1a15] py-4 border-white/10' : 'bg-transparent py-6 border-transparent'">
        <div class="max-w-[1400px] mx-auto px-6 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-[#1e5e3a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="2" x2="12" y2="22"></line>
                    <line x1="12" y1="12" x2="2" y2="12"></line>
                    <line x1="12" y1="12" x2="22" y2="12"></line>
                    <line x1="12" y1="12" x2="4.93" y2="4.93"></line>
                    <line x1="12" y1="12" x2="19.07" y2="19.07"></line>
                    <line x1="12" y1="12" x2="4.93" y2="19.07"></line>
                    <line x1="12" y1="12" x2="19.07" y2="4.93"></line>
                </svg>
                <span>Kelana</span>
            </a>

            <!-- Center Menu -->
            <div class="hidden md:flex space-x-8 text-sm font-semibold tracking-wide">
                <a href="{{ url('/') }}" class="text-white/80 hover:text-white transition-colors">Home</a>
                <a href="{{ url('/#destinasi') }}" class="text-white/80 hover:text-white transition-colors">Destinations</a>
                <a href="#" class="text-white/80 hover:text-white transition-colors">Testimonials</a>
            </div>

            <!-- Right Side (Auth) -->
            <div class="flex items-center space-x-4">
                @if (Auth::guard('customer')->check())
                    <!-- Wishlist (Heart) -->
                    <a href="#" @click.prevent="isWishlistOpen = true" class="text-white hover:text-[#1e5e3a] p-2 transition-colors relative" aria-label="Wishlist">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span x-show="wishlistItems.length > 0" class="absolute -top-0.5 -right-0.5 min-w-5 h-5 flex items-center justify-center bg-[#1e5e3a] text-white rounded-full text-[9px] font-bold px-1" x-text="wishlistItems.length" style="display: none;"></span>
                    </a>
                    
                    <!-- Cart/Bookings -->
                    <a href="#" @click.prevent="isCartOpen = true" class="text-white hover:text-[#1e5e3a] p-2 transition-colors relative" aria-label="Bookings">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span x-show="cartItem" class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-[#1e5e3a] rounded-full" style="display: none;"></span>
                    </a>

                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center focus:outline-none">
                            <div class="w-9 h-9 rounded-full bg-[#1e5e3a] text-white flex items-center justify-center font-bold border border-white/20 hover:border-white transition-all duration-300">
                                {{ strtoupper(substr(Auth::guard('customer')->user()->name ?? 'C', 0, 1)) }}
                            </div>
                        </button>
                        
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-[#0f1a15] border border-white/10 rounded-xl py-2 shadow-2xl z-50 text-sm"
                             style="display: none;">
                            <div class="px-4 py-2 text-xs text-white/50 border-b border-white/10">
                                Signed in as <br/>
                                <span class="font-semibold text-white truncate block">{{ Auth::guard('customer')->user()->email }}</span>
                            </div>
                            <a href="{{ route('customer.bookings') }}" class="block px-4 py-2.5 text-white hover:bg-white/10 transition-colors">My Bookings</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-white hover:bg-white/10 transition-colors">Profile Settings</a>
                            <div class="border-t border-white/10 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2.5 text-red-400 hover:bg-white/10 transition-colors">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @elseif (Auth::guard('admin')->check())
                    <a href="{{ route('admin.dashboard') }}" class="bg-[#1e5e3a] hover:bg-[#154329] border border-transparent px-6 py-2.5 rounded-[26px] font-semibold text-white transition-all duration-300">Admin Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-white/10 hover:bg-white/20 border border-transparent px-6 py-2.5 rounded-[26px] font-semibold text-white transition-all duration-300">Logout</button>
                    </form>
                @elseif (Auth::guard('trip_leader')->check())
                    <a href="{{ route('trip_leader.dashboard') }}" class="bg-[#1e5e3a] hover:bg-[#154329] border border-transparent px-6 py-2.5 rounded-[26px] font-semibold text-white transition-all duration-300">Leader Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-white/10 hover:bg-white/20 border border-transparent px-6 py-2.5 rounded-[26px] font-semibold text-white transition-all duration-300">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="bg-white/10 hover:bg-white/20 border border-transparent px-6 py-2.5 rounded-[26px] font-semibold text-white transition-all duration-300">Login</a>
                    <a href="{{ route('register') }}" class="bg-[#1e5e3a] hover:bg-[#154329] border border-transparent px-6 py-2.5 rounded-[26px] font-semibold text-white transition-all duration-300">Register</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- GLOBAL TOAST NOTIFICATION COMPONENT -->
    @if (session('success') || session('error'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed bottom-5 right-5 z-[9999] max-w-sm w-full bg-white border border-stone/30 rounded-[24px] p-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-2xl">{{ session('success') ? '✅' : '❌' }}</span>
                <div>
                    <h4 class="font-bold text-xs uppercase tracking-wider text-near-black">{{ session('success') ? 'Sukses' : 'Peringatan' }}</h4>
                    <p class="text-xs text-graphite font-semibold mt-0.5">{{ session('success') ?? session('error') }}</p>
                </div>
            </div>
            <button @click="show = false" class="text-xs font-bold text-near-black bg-stone/20 hover:bg-stone/30 px-3 py-1.5 rounded-full transition shrink-0">Tutup</button>
        </div>
    @endif

    <!-- 2. Immersive Hero Section -->
    <section class="relative min-h-[90vh] flex flex-col items-center justify-center text-center px-6 py-32 bg-[#0f1a15] overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=2000&q=80" alt="Nature Hero Background" class="w-full h-full object-cover opacity-80">
            <div class="absolute inset-0 bg-gradient-to-b from-[#0f1a15]/40 via-[#0f1a15]/10 to-[#0f1a15]"></div>
        </div>

        <div class="relative z-10 w-full max-w-4xl mx-auto mt-24">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md text-white border border-white/20 text-xs font-semibold uppercase tracking-wider mb-6">
                ⛰️ Voted best peaceful place in the world
            </span>
            <h1 class="text-[50px] md:text-[80px] leading-[1.05] tracking-tight font-medium text-white mb-6">
                The best place to find <br/> your <span class="italic font-serif text-[#f4f3ed]">Inner Peace</span>
            </h1>
            <p class="text-white/80 text-lg md:text-xl max-w-2xl mx-auto mb-12">
                Feeling tired? Find the best locations to reconnect with nature and find your inner peace with Kelana.
            </p>
            
            <!-- Search Bar Capsule -->
            <form method="GET" action="{{ route('home') }}#destinasi" class="bg-white p-2 rounded-full max-w-2xl mx-auto flex items-center border border-stone/30 w-full">
                <div class="flex-grow flex items-center pl-4">
                    <svg class="w-5 h-5 text-[#3f4e45]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input id="search-input" type="text" name="search" value="{{ request('search') }}" placeholder="Search for a location..." class="w-full bg-transparent border-0 focus:ring-0 text-[#0f1a15] placeholder-[#3f4e45] px-3 py-2 outline-none">
                </div>
                <button type="submit" class="bg-[#1e5e3a] hover:bg-[#154329] text-white px-8 py-3 rounded-full font-medium transition whitespace-nowrap">
                    Search Now
                </button>
            </form>
        </div>
    </section>

    <!-- 3. Promo Banner Slider Section -->
    <section class="w-full bg-[#f4f3ed] pt-12 pb-8 px-6">
        <div x-data="{ 
            activeSlide: 0, 
            slidesCount: 3,
            init() { 
                setInterval(() => { this.activeSlide = (this.activeSlide + 1) % this.slidesCount }, 5000) 
            } 
        }" class="relative w-full max-w-[1400px] mx-auto overflow-hidden rounded-[26px]">
            <!-- Slides Track -->
            <div class="relative w-full flex transition-transform duration-700 ease-in-out" :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                
                <!-- Slide 1 -->
                <div class="w-full flex-shrink-0 relative p-8 md:p-16 text-white min-h-[380px] md:min-h-[420px] flex items-center">
                    <img src="https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1600&q=80" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/45 to-transparent"></div>
                    <div class="relative z-10 max-w-2xl">
                        <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider mb-4 border border-white/20">Spesial Kemerdekaan</span>
                        <h3 class="text-3xl md:text-5xl font-black tracking-tight mb-3">Diskon Pendakian Merdeka</h3>
                        <p class="text-white/80 text-sm md:text-lg mb-6 max-w-lg">Nikmati potongan hingga 20% untuk open trip Bromo Midnight & Gunung Semeru khusus bulan ini.</p>
                        <div class="flex items-center gap-4">
                            <div class="border border-dashed border-white/50 bg-black/40 rounded-xl px-4 py-2">
                                <span class="text-[10px] text-white/60 block uppercase font-mono tracking-wider">Gunakan Kode</span>
                                <span class="font-mono font-bold tracking-wider text-[#dfdfd6]">MERDEKA20</span>
                            </div>
                            <a href="#destinasi" class="bg-[#1e5e3a] hover:bg-[#154329] text-white px-8 py-3.5 rounded-full font-bold transition text-sm">Cari Trip</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="w-full flex-shrink-0 relative p-8 md:p-16 text-white min-h-[380px] md:min-h-[420px] flex items-center">
                    <img src="https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=1600&q=80" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/45 to-transparent"></div>
                    <div class="relative z-10 max-w-2xl">
                        <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider mb-4 border border-white/20">Ekspedisi Komodo</span>
                        <h3 class="text-3xl md:text-5xl font-black tracking-tight mb-3">Sailing Phinisi Cashback</h3>
                        <p class="text-white/80 text-sm md:text-lg mb-6 max-w-lg">Dapatkan potongan harga langsung sebesar Rp 500.000 untuk petualangan layar phinisi 3D2N.</p>
                        <div class="flex items-center gap-4">
                            <div class="border border-dashed border-white/50 bg-black/40 rounded-xl px-4 py-2">
                                <span class="text-[10px] text-white/60 block uppercase font-mono tracking-wider">Gunakan Kode</span>
                                <span class="font-mono font-bold tracking-wider text-[#dfdfd6]">RINJANIPAS</span>
                            </div>
                            <a href="#destinasi" class="bg-[#1e5e3a] hover:bg-[#154329] text-white px-8 py-3.5 rounded-full font-bold transition text-sm">Cari Trip</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="w-full flex-shrink-0 relative p-8 md:p-16 text-white min-h-[380px] md:min-h-[420px] flex items-center">
                    <img src="https://images.unsplash.com/photo-1522199755839-a2bacb67c546?auto=format&fit=crop&w=1600&q=80" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/45 to-transparent"></div>
                    <div class="relative z-10 max-w-2xl">
                        <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider mb-4 border border-white/20">Spesial Dokumentasi</span>
                        <h3 class="text-3xl md:text-5xl font-black tracking-tight mb-3">Gratis Dokumentasi Premium</h3>
                        <p class="text-white/80 text-sm md:text-lg mb-6 max-w-lg">Dapatkan foto dan video drone gratis dari tim dokumentasi profesional kami untuk trip Rinjani & Ijen.</p>
                        <div class="flex items-center gap-4">
                            <div class="border border-dashed border-white/50 bg-black/40 rounded-xl px-4 py-2">
                                <span class="text-[10px] text-white/60 block uppercase font-mono tracking-wider">Gunakan Kode</span>
                                <span class="font-mono font-bold tracking-wider text-[#dfdfd6]">KOMODOLUX</span>
                            </div>
                            <a href="#destinasi" class="bg-[#1e5e3a] hover:bg-[#154329] text-white px-8 py-3.5 rounded-full font-bold transition text-sm">Cari Trip</a>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- Dot Indicators -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-2 z-10">
                <button @click="activeSlide = 0" class="w-2.5 h-2.5 rounded-full transition-all duration-300" :class="activeSlide === 0 ? 'bg-white w-6' : 'bg-white/40'"></button>
                <button @click="activeSlide = 1" class="w-2.5 h-2.5 rounded-full transition-all duration-300" :class="activeSlide === 1 ? 'bg-white w-6' : 'bg-white/40'"></button>
                <button @click="activeSlide = 2" class="w-2.5 h-2.5 rounded-full transition-all duration-300" :class="activeSlide === 2 ? 'bg-white w-6' : 'bg-white/40'"></button>
            </div>
        </div>
    </section>

    <!-- PHP block for loading real cards and mock items -->
    @php
        $cards = [];
        
        // Add real database items first
        foreach($paketWisata ?? [] as $paket) {
            $nameLower = strtolower($paket->nama_paket);
            
            // Context categories: mountain (Pendakian Gunung), beach (Sailing & Laut), forest (Petualangan Rimba)
            if (str_contains($nameLower, 'komodo') || str_contains($nameLower, 'pulau') || str_contains($nameLower, 'pantai') || str_contains($nameLower, 'sailing') || str_contains($nameLower, 'snorkeling') || str_contains($nameLower, 'surfing') || str_contains($nameLower, 'rafting') || str_contains($nameLower, 'arung jeram')) {
                $category = 'beach';
            } elseif (str_contains($nameLower, 'hutan') || str_contains($nameLower, 'rimba') || str_contains($nameLower, 'orangutan') || str_contains($nameLower, 'caving') || str_contains($nameLower, 'jomblang') || str_contains($nameLower, 'ijen') || str_contains($nameLower, 'lawang') || str_contains($nameLower, 'baluran')) {
                $category = 'forest';
            } else {
                $category = 'mountain';
            }
            
            $rating = 4.8;
            $reviewsCount = 42;
            if ($paket->reviews && $paket->reviews->count() > 0) {
                $rating = round($paket->reviews->avg('rating'), 1);
                $reviewsCount = $paket->reviews->count();
            } else {
                $reviewsCount = (($paket->id_paket * 17) % 150) + 12;
            }
            
            $primaryImage = $paket->galleries->where('is_primary', true)->first()->image_url ?? 'https://images.unsplash.com/photo-1506012787146-f92b2d7d6d96?auto=format&fit=crop&w=800&q=80';
            
            $cards[] = [
                'id' => $paket->id_paket,
                'nama' => $paket->nama_paket,
                'harga' => $paket->harga,
                'rute' => $paket->rute,
                'gambar' => $primaryImage,
                'kategori' => $category,
                'rating' => $rating,
                'reviews_count' => $reviewsCount,
                'is_dummy' => false,
                'badge' => $paket->harga > 1800000 ? 'Paling Laku' : ($paket->harga < 600000 ? 'Best Value' : 'Kuota Terbatas')
            ];
        }

        // Add pure adventure open trip dummy items to make the catalog comprehensive (up to 16 cards)
        $firstRealId = count($paketWisata) > 0 ? $paketWisata->first()->id_paket : 1;
        $mockTours = [
            [
                'id' => $firstRealId,
                'nama' => 'Sailing Raja Ampat Luxury Phinisi 4D3N',
                'harga' => 8500000,
                'rute' => 'Raja Ampat, Papua Barat',
                'gambar' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&w=800&q=80',
                'kategori' => 'beach',
                'rating' => 5.0,
                'reviews_count' => 84,
                'is_dummy' => true,
                'badge' => 'Paling Laku'
            ],
            [
                'id' => $firstRealId,
                'nama' => 'Pendakian Puncak Cartenz Pyramid Ekspedisi',
                'harga' => 45000000,
                'rute' => 'Papua Tengah',
                'gambar' => 'https://images.unsplash.com/photo-1454496522488-7a8e488e8606?auto=format&fit=crop&w=800&q=80',
                'kategori' => 'mountain',
                'rating' => 4.9,
                'reviews_count' => 12,
                'is_dummy' => true,
                'badge' => 'Rare Experience'
            ],
            [
                'id' => $firstRealId,
                'nama' => 'Jungle Trekking Orangutan & River Rafting',
                'harga' => 1750000,
                'rute' => 'Tanjung Puting, Kalimantan Tengah',
                'gambar' => 'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?auto=format&fit=crop&w=800&q=80',
                'kategori' => 'forest',
                'rating' => 4.8,
                'reviews_count' => 67,
                'is_dummy' => true,
                'badge' => 'Kuota Terbatas'
            ],
            [
                'id' => $firstRealId,
                'nama' => 'Snorkeling Gili Trawangan & Lombok Escape',
                'harga' => 1100000,
                'rute' => 'Gili Islands, Lombok',
                'gambar' => 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=800&q=80',
                'kategori' => 'beach',
                'rating' => 4.7,
                'reviews_count' => 134,
                'is_dummy' => true,
                'badge' => 'Paling Laku'
            ],
            [
                'id' => $firstRealId,
                'nama' => 'Pendakian Gunung Gede Pangrango Suryakencana',
                'harga' => 480000,
                'rute' => 'Cianjur, Jawa Barat',
                'gambar' => 'https://images.unsplash.com/photo-1522199755839-a2bacb67c546?auto=format&fit=crop&w=800&q=80',
                'kategori' => 'mountain',
                'rating' => 4.8,
                'reviews_count' => 242,
                'is_dummy' => true,
                'badge' => 'Best Value'
            ],
            [
                'id' => $firstRealId,
                'nama' => 'Trekking Tebing Lembah Harau & Kampung Minang',
                'harga' => 1250000,
                'rute' => 'Payakumbuh, Sumatera Barat',
                'gambar' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                'kategori' => 'forest',
                'rating' => 4.9,
                'reviews_count' => 38,
                'is_dummy' => true,
                'badge' => 'Hidden Gem'
            ]
        ];

        // Merge mock tours into cards list so it looks populated and massive
        // $cards = array_merge($cards, $mockTours);
    @endphp


    <!-- 5. Paling Laku (Best Sellers) -->
    <section class="w-full bg-[#f4f3ed] py-12 px-6">
        <div class="max-w-[1400px] mx-auto">
            <div class="mb-8">
                <h3 class="text-3xl md:text-4xl font-bold tracking-tight text-[#0f1a15]">Paling Laku di Kelana</h3>
                <p class="text-[#3f4e45] text-sm mt-1">Aktivitas open trip terlaris pilihan pelancong Indonesia</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $bestSellers = array_filter($cards, function($card) {
                        return $card['harga'] >= 1200000;
                    });
                    $bestSellers = array_slice($bestSellers, 0, 4);
                @endphp
                @foreach($bestSellers as $card)
                    <a href="{{ route('paket.detail', $card['id']) }}" class="group bg-white rounded-[26px] overflow-hidden border border-stone/30 block hover:scale-[1.02] transition-transform duration-300">
                        <div class="relative overflow-hidden aspect-[4/3] bg-stone/20">
                            <img src="{{ $card['gambar'] }}" alt="{{ $card['nama'] }}" class="w-full h-full object-cover">
                            <span class="absolute top-4 left-4 bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Paling Laku</span>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-[#3f4e45] font-semibold flex items-center gap-1">📍 {{ $card['rute'] }}</span>
                                <span class="text-xs font-bold text-[#1e5e3a]">★ {{ number_format($card['rating'], 1) }} ({{ $card['reviews_count'] }})</span>
                            </div>
                            <h4 class="text-base font-bold text-[#0f1a15] mb-4 line-clamp-1 group-hover:text-[#1e5e3a] transition">{{ $card['nama'] }}</h4>
                            <div class="pt-4 border-t border-stone/50 flex justify-between items-center">
                                <div>
                                    <span class="text-xs text-[#3f4e45] block">mulai dari</span>
                                    <span class="text-base font-bold text-[#0f1a15]">Rp {{ number_format($card['harga'], 0, ',', '.') }} <span class="text-xs text-[#3f4e45] font-normal">/ org</span></span>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-[#1e5e3a]/10 text-[#1e5e3a] group-hover:bg-[#1e5e3a] group-hover:text-white flex items-center justify-center transition-all duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 6. Kemungkinan Terjual Habis (Fast Selling/Limited Capacity) -->
    <section class="w-full bg-[#f4f3ed] py-12 px-6">
        <div class="max-w-[1400px] mx-auto">
            <div class="mb-8">
                <h3 class="text-3xl md:text-4xl font-bold tracking-tight text-[#0f1a15]">Kemungkinan Akan Terjual Habis</h3>
                <p class="text-[#3f4e45] text-sm mt-1">Destinasi favorit dengan kapasitas sisa kuota yang menipis</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $fastSelling = array_filter($cards, function($card) {
                        return $card['harga'] < 1200000;
                    });
                    $fastSelling = array_slice($fastSelling, 0, 4);
                @endphp
                @foreach($fastSelling as $card)
                    <a href="{{ route('paket.detail', $card['id']) }}" class="group bg-white rounded-[26px] overflow-hidden border border-stone/30 block hover:scale-[1.02] transition-transform duration-300">
                        <div class="relative overflow-hidden aspect-[4/3] bg-stone/20">
                            <img src="{{ $card['gambar'] }}" alt="{{ $card['nama'] }}" class="w-full h-full object-cover">
                            <span class="absolute top-4 left-4 bg-orange-600 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Kuota Terbatas</span>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-[#3f4e45] font-semibold flex items-center gap-1">📍 {{ $card['rute'] }}</span>
                                <span class="text-xs font-bold text-[#1e5e3a]">★ {{ number_format($card['rating'], 1) }} ({{ $card['reviews_count'] }})</span>
                            </div>
                            <h4 class="text-base font-bold text-[#0f1a15] mb-4 line-clamp-1 group-hover:text-[#1e5e3a] transition">{{ $card['nama'] }}</h4>
                            <div class="pt-4 border-t border-stone/50 flex justify-between items-center">
                                <div>
                                    <span class="text-xs text-[#3f4e45] block">mulai dari</span>
                                    <span class="text-base font-bold text-[#0f1a15]">Rp {{ number_format($card['harga'], 0, ',', '.') }} <span class="text-xs text-[#3f4e45] font-normal">/ org</span></span>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-[#1e5e3a]/10 text-[#1e5e3a] group-hover:bg-[#1e5e3a] group-hover:text-white flex items-center justify-center transition-all duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 7. Main Destinations Catalog (Filtered via Alpine.js) -->
    <div id="destinasi" class="w-full bg-[#f4f3ed] py-20 px-6 border-t border-stone/30">
        <div class="max-w-[1400px] mx-auto">
            <div class="mb-12">
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-[#0f1a15] mb-8">Pilih Destinasi Petualanganmu</h2>
                
                <!-- Tab Kategori Wisata -->
                <div class="flex gap-3 overflow-x-auto pb-4 no-scrollbar">
                    <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-[#1e5e3a] text-white' : 'border border-[#1e5e3a] text-[#1e5e3a] hover:bg-[#1e5e3a]/10'" class="rounded-full px-6 py-2.5 text-sm font-semibold tracking-wide transition whitespace-nowrap">
                        Semua Wisata
                    </button>
                    <button @click="activeCategory = 'mountain'" :class="activeCategory === 'mountain' ? 'bg-[#1e5e3a] text-white' : 'border border-[#1e5e3a] text-[#1e5e3a] hover:bg-[#1e5e3a]/10'" class="rounded-full px-6 py-2.5 text-sm font-semibold tracking-wide transition whitespace-nowrap">
                        Pendakian Gunung
                    </button>
                    <button @click="activeCategory = 'beach'" :class="activeCategory === 'beach' ? 'bg-[#1e5e3a] text-white' : 'border border-[#1e5e3a] text-[#1e5e3a] hover:bg-[#1e5e3a]/10'" class="rounded-full px-6 py-2.5 text-sm font-semibold tracking-wide transition whitespace-nowrap">
                        Sailing & Laut
                    </button>
                    <button @click="activeCategory = 'forest'" :class="activeCategory === 'forest' ? 'bg-[#1e5e3a] text-white' : 'border border-[#1e5e3a] text-[#1e5e3a] hover:bg-[#1e5e3a]/10'" class="rounded-full px-6 py-2.5 text-sm font-semibold tracking-wide transition whitespace-nowrap">
                        Petualangan Rimba
                    </button>
                </div>

                <!-- Info Pencarian Aktif -->
                @if(request('search'))
                    <div class="mt-6 flex flex-wrap items-center justify-between gap-3 bg-white border border-stone/30 p-4 rounded-[26px]">
                        <span class="text-xs font-semibold text-[#0f1a15]">
                            🔎 Menampilkan hasil pencarian untuk: <strong class="text-[#1e5e3a]">"{{ request('search') }}"</strong>
                        </span>
                        <a href="{{ route('home') }}#destinasi" class="bg-near-black hover:bg-[#1e5e3a] text-white text-[10px] font-bold px-4 py-2 rounded-full transition">
                            Hapus Filter Pencarian ×
                        </a>
                    </div>
                @endif
            </div>

            @if(count($cards) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($cards as $card)
                            <a href="{{ route('paket.detail', $card['id']) }}" 
                               x-show="activeCategory === 'all' || activeCategory === '{{ $card['kategori'] }}'" 
                               class="group bg-white rounded-[26px] overflow-hidden relative border border-stone/30 block hover:scale-[1.02] transition-transform duration-300">
                            <!-- Image Container with Wishlist and Badge -->
                            <div class="relative overflow-hidden aspect-[4/3] bg-stone/20">
                                <img src="{{ $card['gambar'] }}" alt="{{ $card['nama'] }}" class="w-full h-full object-cover transition-transform duration-75 group-hover:scale-[1.03]">
                                
                                <!-- Wishlist Heart Button -->
                                <button class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm hover:bg-white rounded-full p-2.5 transition z-20 focus:outline-none" 
                                        :class="wishlistItems.some(item => item.paket_wisata_id == {{ $card['id'] }}) ? 'text-[#1e5e3a]' : 'text-near-black'"
                                        aria-label="Add to Wishlist" 
                                        @click.prevent="toggleWishlist({{ $card['id'] }})">
                                    <svg class="w-5 h-5" :fill="wishlistItems.some(item => item.paket_wisata_id == {{ $card['id'] }}) ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>

                                <!-- Badge Kelangkaan -->
                                <span class="absolute bottom-4 left-4 bg-[#1e5e3a] text-white px-3 py-1 rounded-full text-xs font-bold">
                                    {{ $card['badge'] }}
                                </span>
                            </div>

                            <!-- Card Content -->
                            <div class="p-6">
                                <!-- Location & Rating Row -->
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs text-[#3f4e45] font-semibold tracking-wide flex items-center gap-1">
                                        <svg class="w-4 h-4 text-[#1e5e3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $card['rute'] }}
                                    </span>
                                    <span class="text-xs font-bold text-[#1e5e3a] flex items-center gap-0.5">
                                        ★ {{ number_format($card['rating'], 1) }} ({{ $card['reviews_count'] }})
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-[#0f1a15] mb-4 line-clamp-1 hover:text-[#1e5e3a] transition">
                                    {{ $card['nama'] }}
                                </h3>
                                <div class="flex items-center justify-between pt-4 border-t border-stone/50">
                                    <div>
                                        <span class="text-xs text-[#3f4e45] block">Price starts from</span>
                                        <span class="text-lg font-bold text-[#0f1a15]">Rp {{ number_format($card['harga'], 0, ',', '.') }}</span>
                                    </div>
                                    <!-- Arrow CTA -->
                                    <div class="w-10 h-10 bg-[#1e5e3a]/10 text-[#1e5e3a] group-hover:bg-[#1e5e3a] group-hover:text-white rounded-full flex items-center justify-center transition-all duration-300">
                                        <svg class="w-5 h-5 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-white border border-stone/30 rounded-[26px] p-12 text-center max-w-xl mx-auto space-y-4">
                    <div class="text-5xl">🔍❌</div>
                    <h3 class="text-lg font-bold text-near-black">Destinasi Tidak Ditemukan</h3>
                    <p class="text-xs text-graphite leading-relaxed">Maaf, kami tidak dapat menemukan destinasi atau paket wisata yang cocok dengan kata kunci <strong class="text-near-black">"{{ request('search') }}"</strong>. Silakan coba kata kunci lain atau bersihkan pencarian untuk melihat semua paket.</p>
                    <a href="{{ route('home') }}#destinasi" class="inline-block bg-[#1e5e3a] hover:bg-[#154329] text-white px-6 py-2.5 rounded-full text-xs font-bold transition">
                        Lihat Semua Wisata
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- 8. Tempat ikonik yang harus Anda lihat -->
    <section class="w-full bg-white py-20 px-6 border-t border-stone/30">
        <div class="max-w-[1400px] mx-auto">
            <div class="mb-10 text-center md:text-left">
                <h3 class="text-3xl md:text-4xl font-bold tracking-tight text-[#0f1a15]">Tempat ikonik yang harus Anda lihat</h3>
                <p class="text-[#3f4e45] text-sm mt-1">Eksplorasi destinasi open trip terpopuler di Indonesia pilihan Kelana</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                <!-- Yogyakarta -->
                <button @click="activeCategory = 'forest'; document.getElementById('destinasi').scrollIntoView({ behavior: 'smooth' })" class="group flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full overflow-hidden mb-3 border-2 border-stone group-hover:border-[#1e5e3a] transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=150&q=80" alt="Yogyakarta" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <span class="text-sm font-bold text-[#0f1a15] group-hover:text-[#1e5e3a] transition">Yogyakarta</span>
                </button>
                
                <!-- Bali -->
                <button @click="activeCategory = 'beach'; document.getElementById('destinasi').scrollIntoView({ behavior: 'smooth' })" class="group flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full overflow-hidden mb-3 border-2 border-stone group-hover:border-[#1e5e3a] transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=150&q=80" alt="Bali" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <span class="text-sm font-bold text-[#0f1a15] group-hover:text-[#1e5e3a] transition">Bali</span>
                </button>

                <!-- Bromo -->
                <button @click="activeCategory = 'mountain'; document.getElementById('destinasi').scrollIntoView({ behavior: 'smooth' })" class="group flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full overflow-hidden mb-3 border-2 border-stone group-hover:border-[#1e5e3a] transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=150&q=80" alt="Bromo" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <span class="text-sm font-bold text-[#0f1a15] group-hover:text-[#1e5e3a] transition">Bromo</span>
                </button>

                <!-- Komodo -->
                <button @click="activeCategory = 'beach'; document.getElementById('destinasi').scrollIntoView({ behavior: 'smooth' })" class="group flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full overflow-hidden mb-3 border-2 border-stone group-hover:border-[#1e5e3a] transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=150&q=80" alt="Komodo" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <span class="text-sm font-bold text-[#0f1a15] group-hover:text-[#1e5e3a] transition">Komodo</span>
                </button>

                <!-- Toraja -->
                <button @click="activeCategory = 'forest'; document.getElementById('destinasi').scrollIntoView({ behavior: 'smooth' })" class="group flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full overflow-hidden mb-3 border-2 border-stone group-hover:border-[#1e5e3a] transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=150&q=80" alt="Toraja" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <span class="text-sm font-bold text-[#0f1a15] group-hover:text-[#1e5e3a] transition">Toraja</span>
                </button>

                <!-- Rinjani -->
                <button @click="activeCategory = 'mountain'; document.getElementById('destinasi').scrollIntoView({ behavior: 'smooth' })" class="group flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full overflow-hidden mb-3 border-2 border-stone group-hover:border-[#1e5e3a] transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1522199755839-a2bacb67c546?auto=format&fit=crop&w=150&q=80" alt="Rinjani" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <span class="text-sm font-bold text-[#0f1a15] group-hover:text-[#1e5e3a] transition">Rinjani</span>
                </button>

                <!-- Ijen -->
                <button @click="activeCategory = 'forest'; document.getElementById('destinasi').scrollIntoView({ behavior: 'smooth' })" class="group flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full overflow-hidden mb-3 border-2 border-stone group-hover:border-[#1e5e3a] transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=150&q=80" alt="Ijen" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <span class="text-sm font-bold text-[#0f1a15] group-hover:text-[#1e5e3a] transition">Ijen</span>
                </button>

                <!-- Langkat -->
                <button @click="activeCategory = 'forest'; document.getElementById('destinasi').scrollIntoView({ behavior: 'smooth' })" class="group flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full overflow-hidden mb-3 border-2 border-stone group-hover:border-[#1e5e3a] transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?auto=format&fit=crop&w=150&q=80" alt="Langkat" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <span class="text-sm font-bold text-[#0f1a15] group-hover:text-[#1e5e3a] transition">Langkat</span>
                </button>
            </div>
        </div>
    </section>

    <!-- 9. Credibility Stats Section -->
    <section class="bg-white border-y border-stone/40 py-24 px-6">
        <div class="max-w-[1400px] mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <div class="flex flex-col items-center">
                    <span class="text-6xl font-extrabold text-[#1e5e3a] tracking-tight mb-3">10k+</span>
                    <span class="text-sm font-semibold uppercase tracking-wider text-[#3f4e45]">Positive Reviews</span>
                </div>
                <div class="flex flex-col items-center border-y md:border-y-0 md:border-x border-stone/40 py-8 md:py-0">
                    <span class="text-6xl font-extrabold text-[#1e5e3a] tracking-tight mb-3">50+</span>
                    <span class="text-sm font-semibold uppercase tracking-wider text-[#3f4e45]">Destinations</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-6xl font-extrabold text-[#1e5e3a] tracking-tight mb-3">100%</span>
                    <span class="text-sm font-semibold uppercase tracking-wider text-[#3f4e45]">Safety Record</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 10. "About Kelana" Storytelling Section -->
    <section class="bg-[#f4f3ed] py-28 px-6">
        <div class="max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Left Column -->
            <div>
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-[#0f1a15] mb-6">Apa itu Kelana?</h2>
                <p class="text-[#3f4e45] text-lg mb-6 leading-relaxed">
                    Kelana adalah platform perjalanan premium yang menghubungkan Anda dengan keindahan alam dunia secara mendalam dan personal. Kami percaya bahwa petualangan sejati tidak hanya tentang destinasi, melainkan kenyamanan, keamanan, dan makna yang Anda temukan sepanjang perjalanan.
                </p>
                <p class="text-[#3f4e45] text-lg mb-8 leading-relaxed">
                    Setiap perjalanan kami dirancang secara eksklusif dan dipandu oleh Trip Leader bersertifikat internasional. Dari jalur pendakian pegunungan mistis hingga pelayaran pulau tropis terpencil, kami memastikan setiap momen terasa istimewa dan bebas khawatir.
                </p>
                <div class="flex gap-4">
                    <a href="#search-input" @click.prevent="document.getElementById('search-input').scrollIntoView({ behavior: 'smooth' }); document.getElementById('search-input').focus()" class="bg-[#1e5e3a] hover:bg-[#154329] text-white px-8 py-3.5 rounded-full font-semibold transition text-sm">Cari Destinasi</a>
                </div>
            </div>
            <!-- Right Column: Masonry Collage -->
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-6">
                    <img src="https://images.unsplash.com/photo-1501555088652-021faa106b9b?auto=format&fit=crop&w=600&q=80" alt="Mountain Trekker" class="w-full h-[360px] object-cover rounded-[26px]">
                </div>
                <div class="col-span-6 flex flex-col gap-4">
                    <img src="https://images.unsplash.com/photo-1527631746610-bca00a040d60?auto=format&fit=crop&w=600&q=80" alt="Sailing Komodo" class="w-full h-[172px] object-cover rounded-[26px]">
                    <img src="https://images.unsplash.com/photo-1530789253388-582c481c54b0?auto=format&fit=crop&w=600&q=80" alt="Tropical Beach" class="w-full h-[172px] object-cover rounded-[26px]">
                </div>
            </div>
        </div>
    </section>

    <!-- 11. FAQ Section -->
    <section class="w-full bg-[#f4f3ed] py-24 px-6 border-t border-stone/30">
        <div class="max-w-[800px] mx-auto w-full">
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-[#1e5e3a] uppercase tracking-widest block mb-2">Help Center</span>
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-[#0f1a15] mb-4">Frequently Asked Questions</h2>
                <p class="text-[#3f4e45] text-lg">Find answers to common questions about our services.</p>
            </div>

            <div x-data="{ selected: null }" class="space-y-4">
                <!-- FAQ 1 -->
                <div class="w-full block">
                    <div @click="selected = (selected === 1 ? null : 1)" class="bg-white rounded-full px-8 py-5 flex justify-between items-center cursor-pointer transition-all duration-300 hover:scale-[1.01] border border-stone/30">
                        <span class="font-bold text-[#0f1a15] text-left leading-relaxed">How do I book a travel package?</span>
                        <span class="transition-transform duration-300 flex-shrink-0 ml-4" :class="selected === 1 ? 'rotate-180' : ''">
                            <svg class="text-[#1e5e3a] w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </div>
                    <div x-show="selected === 1" class="text-[#3f4e45] px-8 pt-4 pb-2 w-full text-left leading-relaxed transition-all duration-300">
                        You can select your desired destination on the catalog page, click "View Details," and follow the booking process. You need to log in or register an account first to complete the booking.
                    </div>
                </div>
                
                <!-- FAQ 2 -->
                <div class="w-full block">
                    <div @click="selected = (selected === 2 ? null : 2)" class="bg-white rounded-full px-8 py-5 flex justify-between items-center cursor-pointer transition-all duration-300 hover:scale-[1.01] border border-stone/30">
                        <span class="font-bold text-[#0f1a15] text-left leading-relaxed">Is the flight ticket included in the package price?</span>
                        <span class="transition-transform duration-300 flex-shrink-0 ml-4" :class="selected === 2 ? 'rotate-180' : ''">
                            <svg class="text-[#1e5e3a] w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </div>
                    <div x-show="selected === 2" class="text-[#3f4e45] px-8 pt-4 pb-2 w-full text-left leading-relaxed transition-all duration-300">
                        Generally, our package prices do not include flight tickets from your home city to the meeting point. However, local transport during the trip is fully covered by Kelana.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="w-full block">
                    <div @click="selected = (selected === 3 ? null : 3)" class="bg-white rounded-full px-8 py-5 flex justify-between items-center cursor-pointer transition-all duration-300 hover:scale-[1.01] border border-stone/30">
                        <span class="font-bold text-[#0f1a15] text-left leading-relaxed">Is this trip safe for beginners?</span>
                        <span class="transition-transform duration-300 flex-shrink-0 ml-4" :class="selected === 3 ? 'rotate-180' : ''">
                            <svg class="text-[#1e5e3a] w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </div>
                    <div x-show="selected === 3" class="text-[#3f4e45] px-8 pt-4 pb-2 w-full text-left leading-relaxed transition-all duration-300">
                        Of course. Most of our trips are designed to be safe for beginners. Each group will be guided by a certified Trip Leader to ensure the safety and comfort of all participants.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 12. Comprehensive Footer -->
    <footer class="bg-[#0b1611] text-white pt-24 pb-8 px-6">
        <div class="max-w-[1400px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <!-- Kolom 1 -->
            <div>
                <a href="/" class="text-3xl font-bold tracking-tight text-white mb-6 block">
                    Kelana
                </a>
                <p class="text-white/60 leading-relaxed text-sm">
                    Opening doors to extraordinary adventures with world-class comfort and safety standards.
                </p>
            </div>
            
            <!-- Kolom 2 -->
            <div>
                <h4 class="text-lg font-medium mb-6 text-white">Company</h4>
                <ul class="space-y-4 text-sm text-white/60">
                    <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                </ul>
            </div>
            
            <!-- Kolom 3 -->
            <div>
                <h4 class="text-lg font-medium mb-6 text-white">Support</h4>
                <ul class="space-y-4 text-sm text-white/60">
                    <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Terms & Conditions</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                </ul>
            </div>
            
            <!-- Kolom 4 -->
            <div>
                <h4 class="text-lg font-medium mb-6 text-white">Newsletter</h4>
                <p class="text-sm text-white/60 mb-6">Get the latest promo and destination updates.</p>
                <form class="flex items-center border border-white/20 rounded-full p-1 focus-within:border-[#1e5e3a] transition-colors bg-white/5">
                    <input type="email" placeholder="Your Email" class="bg-transparent border-none outline-none focus:ring-0 text-white w-full px-4 text-sm" required>
                    <button type="submit" class="bg-[#1e5e3a] text-white w-10 h-10 rounded-full flex items-center justify-center shrink-0 hover:bg-[#154329] transition-all duration-300 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Copyright Banner -->
        <div class="max-w-[1400px] mx-auto mt-24 pt-8 border-t border-white/10 text-white/40 text-sm flex flex-col md:flex-row justify-between items-center gap-4">
            <p>&copy; {{ date('Y') }} Kelana. All Rights Reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-white transition-colors">Instagram</a>
                <a href="#" class="hover:text-white transition-colors">Twitter</a>
                <a href="#" class="hover:text-white transition-colors">LinkedIn</a>
            </div>
        </div>
    </footer>

    @include('components.customer-wishlist-cart')
</body>
</html>
