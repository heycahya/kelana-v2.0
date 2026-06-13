<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Kelana') }} - Explore the World</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-warm-cream text-near-black font-sans antialiased min-h-screen flex flex-col">

    @include('components.navbar')

    <!-- 3. Massive Hero Section -->
    <section class="relative min-h-[90vh] flex flex-col items-center justify-center text-center px-6 py-32 bg-near-black overflow-hidden mt-[-80px]">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1506012787146-f92b2d7d6d96?auto=format&fit=crop&w=2000&q=80" alt="Nature Hero Background" class="w-full h-full object-cover opacity-90">
            <!-- Gradient to blend into the warm-cream section below -->
            <div class="absolute inset-0 bg-gradient-to-b from-near-black/50 via-near-black/20 to-near-black/90"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 w-full max-w-4xl mx-auto mt-24">
            <span class="inline-block px-4 py-1 rounded-full bg-white/10 backdrop-blur-md text-warm-cream border border-white/20 text-sm font-medium mb-6">
                ⛰️ Voted best peaceful place in the world
            </span>
            <h1 class="text-[50px] md:text-[80px] leading-[1.05] tracking-tight font-medium text-white mb-6 drop-shadow-lg">
                The best place to find <br/> your <span class="italic text-warm-cream font-serif">Inner Peace</span>
            </h1>
            <p class="text-white/90 text-lg md:text-xl max-w-2xl mx-auto mb-12 drop-shadow-md">
                Feeling tired? Find the best locations to reconnect with nature and find your inner peace with Kelana.
            </p>
            
            <!-- Search Bar mimicking Escape reference -->
            <div class="bg-white/95 backdrop-blur-md p-2 rounded-[32px] max-w-2xl mx-auto flex items-center shadow-2xl border border-white/20">
                <div class="flex-grow flex items-center pl-4">
                    <svg class="w-5 h-5 text-graphite" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" placeholder="Search for a location..." class="w-full bg-transparent border-none focus:ring-0 text-near-black placeholder-graphite px-3 py-2 outline-none">
                </div>
                <a href="#destinasi" class="bg-near-black text-white px-8 py-3 rounded-[24px] font-medium hover:bg-near-black/90 transition shadow-md whitespace-nowrap">
                    Search Now
                </a>
            </div>
        </div>

        <!-- Logos at bottom mimicking Escape reference -->
        <div class="absolute bottom-8 left-0 right-0 z-10 flex flex-col items-center opacity-80">
            <p class="text-white/60 text-xs mb-4 uppercase tracking-widest font-medium">Featured as the safest place to go in</p>
            <div class="flex gap-8 md:gap-16 items-center justify-center flex-wrap px-6">
                <span class="text-xl md:text-2xl font-bold text-white font-serif italic">Forbes</span>
                <span class="text-xl md:text-2xl font-bold text-white tracking-tighter">Men'sHealth</span>
                <span class="text-xl md:text-2xl font-bold text-white uppercase tracking-tight">Bloomberg</span>
                <span class="text-xl md:text-2xl font-bold text-white font-serif">The Washington Post</span>
            </div>
        </div>
    </section>

    <!-- 4. Value Proposition -->
    <section class="bg-near-black text-white py-32 px-6">
        <div class="max-w-[1400px] mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-5xl font-medium tracking-tight mb-6">Limitless experiences.</h2>
                <p class="text-xl text-graphite leading-relaxed">
                    We redefine the way you adventure. With premium service standards, every journey is designed to be <span class="text-sprout-green">safe, comfortable, and unforgettable.</span>
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-[#1a1a1a] border border-graphite/50 rounded-[26px] p-10 hover:border-electric-lime transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-[18px] bg-electric-lime/10 flex items-center justify-center mb-8 group-hover:bg-electric-lime transition-colors">
                        <svg class="w-8 h-8 text-electric-lime group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-2xl font-medium mb-4 text-white">Certified Trip Leaders</h3>
                    <p class="text-graphite leading-relaxed">Every journey is guided by certified experts who have passed strict selection to ensure the best service standards.</p>
                </div>

                <div class="bg-[#1a1a1a] border border-graphite/50 rounded-[26px] p-10 hover:border-electric-lime transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-[18px] bg-electric-lime/10 flex items-center justify-center mb-8 group-hover:bg-electric-lime transition-colors">
                        <svg class="w-8 h-8 text-electric-lime group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                    </div>
                    <h3 class="text-2xl font-medium mb-4 text-white">Exclusive Itineraries</h3>
                    <p class="text-graphite leading-relaxed">Access to hidden destinations and authentic local experiences specially crafted and unavailable elsewhere.</p>
                </div>

                <div class="bg-[#1a1a1a] border border-graphite/50 rounded-[26px] p-10 hover:border-electric-lime transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-[18px] bg-electric-lime/10 flex items-center justify-center mb-8 group-hover:bg-electric-lime transition-colors">
                        <svg class="w-8 h-8 text-electric-lime group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="text-2xl font-medium mb-4 text-white">Safe & Insured</h3>
                    <p class="text-graphite leading-relaxed">Top priority on safety with comprehensive insurance, well-maintained vehicles, and high safety protocols.</p>
                </div>
            </div>
        </div>
    </section>



    <section class="bg-parchment-card py-32 px-6">
        <div class="max-w-[800px] mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-medium tracking-tight text-near-black mb-8">Credibility beyond doubt.</h2>
            <p class="text-xl text-graphite mb-12">
                More than 10,000 travelers have trusted their holidays with us. Safety guaranteed with comprehensive insurance at every step.
            </p>
            <div class="grid grid-cols-3 gap-8 text-center border-t border-stone pt-12">
                <div>
                    <h4 class="text-5xl font-medium text-near-black mb-2">10k+</h4>
                    <p class="text-graphite">Satisfied Customers</p>
                </div>
                <div>
                    <h4 class="text-5xl font-medium text-near-black mb-2">50+</h4>
                    <p class="text-graphite">Destinations</p>
                </div>
                <div>
                    <h4 class="text-5xl font-medium text-near-black mb-2">100%</h4>
                    <p class="text-graphite">Safe & Insured</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Katalog Paket Wisata (Premium Grid) -->
    <section id="destinasi" class="w-full bg-warm-cream py-32 px-6">
        <div class="max-w-[1400px] mx-auto">
            <div class="mb-16 text-center">
                <h2 class="text-[50px] font-medium tracking-tight text-near-black">Choose Your Adventure.</h2>
            </div>
            
            @php
                $unsplashImages = [
                    '1602002418082-a4443e081dd1',
                    '1522199755839-a2bacb67c546',
                    '1501555088652-021faa106b9b'
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12 w-full">
                @forelse($paketWisata ?? [] as $index => $paket)
                    <a href="{{ route('paket.detail', $paket->id_paket) }}" class="group relative flex flex-col justify-end w-full aspect-[3/4] max-h-[600px] rounded-[32px] overflow-hidden shadow-2xl border border-stone/20">
                        <!-- Full Background Image -->
                        <img src="https://images.unsplash.com/photo-{{ $unsplashImages[$index % 3] ?? '1602002418082-a4443e081dd1' }}?auto=format&fit=crop&w=800&q=80" alt="Destinasi" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-near-black/90 via-near-black/30 to-transparent"></div>
                        
                        <!-- Top Left Badge -->
                        <div class="absolute top-6 left-6 bg-white/90 backdrop-blur-md px-4 py-2 rounded-full shadow-lg">
                            <span class="text-xs font-bold text-near-black flex items-center gap-1">
                                ⭐ Prime Pick
                            </span>
                        </div>

                        <!-- Top Right Arrow Button -->
                        <div class="absolute top-6 right-6 w-12 h-12 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center transform transition-transform group-hover:rotate-45 shadow-lg">
                            <svg class="w-6 h-6 text-near-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>

                        <!-- Bottom Content -->
                        <div class="relative z-10 p-8">
                            <h3 class="text-white text-3xl font-bold mb-2 drop-shadow-md leading-tight">{{ $paket->nama_paket ?? 'Nama Paket' }}</h3>
                            <p class="text-white/90 font-medium text-xl drop-shadow-md">
                                Rp {{ number_format($paket->harga ?? 0, 0, ',', '.') }} <span class="text-white/60 text-sm font-normal">/ person</span>
                            </p>
                        </div>
                    </a>
                @empty
                    <!-- Dummy Cards for Preview -->
                    @for($i=1; $i<=3; $i++)
                    <a href="{{ route('paket.detail', $i) }}" class="group relative flex flex-col justify-end w-full aspect-[3/4] max-h-[600px] rounded-[32px] overflow-hidden shadow-2xl border border-stone/20">
                        <img src="https://images.unsplash.com/photo-{{ $unsplashImages[$i - 1] ?? '1602002418082-a4443e081dd1' }}?auto=format&fit=crop&w=800&q=80" alt="Destinasi" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-near-black/90 via-near-black/30 to-transparent"></div>
                        
                        <div class="absolute top-6 left-6 bg-white/90 backdrop-blur-md px-4 py-2 rounded-full shadow-lg">
                            <span class="text-xs font-bold text-near-black flex items-center gap-1">⭐ Prime Pick</span>
                        </div>

                        <div class="absolute top-6 right-6 w-12 h-12 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center transform transition-transform group-hover:rotate-45 shadow-lg">
                            <svg class="w-6 h-6 text-near-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>

                        <div class="relative z-10 p-8">
                            @php
                                $titles = ['Eksplorasi Bromo Midnight', 'Sailing Pulau Komodo', 'Pendakian Rinjani'];
                                $prices = [350000, 2500000, 1800000];
                            @endphp
                            <h3 class="text-white text-3xl font-bold mb-2 drop-shadow-md leading-tight">{{ $titles[$i-1] }}</h3>
                            <p class="text-white/90 font-medium text-xl drop-shadow-md">
                                Rp {{ number_format($prices[$i-1], 0, ',', '.') }} <span class="text-white/60 text-sm font-normal">/ person</span>
                            </p>
                        </div>
                    </a>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    <!-- 6. FAQ Section -->
    <section class="w-full bg-warm-cream py-24 px-6">
        <div class="max-w-[800px] mx-auto w-full">
            <div class="text-center mb-16">
                <h2 class="text-[40px] font-medium tracking-tight text-near-black mb-4">Frequently Asked Questions</h2>
                <p class="text-graphite text-lg">Find answers to common questions about our services.</p>
            </div>

            <div class="w-full block space-y-4">
                <!-- FAQ 1 -->
                <div x-data="{ open: false }" class="rounded-[26px] bg-white overflow-hidden w-full block box-border transition-all duration-300 hover:scale-[1.01]">
                    <button @click="open = !open" class="w-full flex justify-between items-center font-medium p-6 text-near-black focus:outline-none box-border">
                        <span class="text-left leading-relaxed">How do I book a travel package?</span>
                        <span class="transition-transform duration-300 flex-shrink-0 ml-4" :class="open ? 'rotate-180' : ''">
                            <svg class="text-electric-lime" fill="none" height="24" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </button>
                    <div x-show="open" class="text-graphite px-6 pb-6 w-full text-left box-border" style="display: none;">
                        You can select your desired destination on the catalog page, click "View Details," and follow the booking process. You need to log in or register an account first to complete the booking.
                    </div>
                </div>
                <!-- FAQ 2 -->
                <div x-data="{ open: false }" class="rounded-[26px] bg-white overflow-hidden w-full block box-border transition-all duration-300 hover:scale-[1.01]">
                    <button @click="open = !open" class="w-full flex justify-between items-center font-medium p-6 text-near-black focus:outline-none box-border">
                        <span class="text-left leading-relaxed">Is the flight ticket included in the package price?</span>
                        <span class="transition-transform duration-300 flex-shrink-0 ml-4" :class="open ? 'rotate-180' : ''">
                            <svg class="text-electric-lime" fill="none" height="24" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </button>
                    <div x-show="open" class="text-graphite px-6 pb-6 w-full text-left box-border" style="display: none;">
                        Generally, our package prices do not include flight tickets from your home city to the meeting point. However, local transport during the trip is fully covered by Kelana.
                    </div>
                </div>
                <!-- FAQ 3 -->
                <div x-data="{ open: false }" class="rounded-[26px] bg-white overflow-hidden w-full block box-border transition-all duration-300 hover:scale-[1.01]">
                    <button @click="open = !open" class="w-full flex justify-between items-center font-medium p-6 text-near-black focus:outline-none box-border">
                        <span class="text-left leading-relaxed">Is this trip safe for beginners?</span>
                        <span class="transition-transform duration-300 flex-shrink-0 ml-4" :class="open ? 'rotate-180' : ''">
                            <svg class="text-electric-lime" fill="none" height="24" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </button>
                    <div x-show="open" class="text-graphite px-6 pb-6 w-full text-left box-border" style="display: none;">
                        Of course. Most of our trips are designed to be safe for beginners. Each group will be guided by a certified Trip Leader to ensure the safety and comfort of all participants.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. Comprehensive Footer -->
    <footer class="bg-near-black text-white pt-24 pb-8 px-6">
        <div class="max-w-[1400px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <!-- Kolom 1 -->
            <div>
                <a href="/" class="text-3xl font-bold tracking-tight text-white mb-6 block">
                    Kelana
                </a>
                <p class="text-graphite leading-relaxed">
                    Opening doors to extraordinary adventures with world-class comfort and safety standards.
                </p>
            </div>
            
            <!-- Kolom 2 -->
            <div>
                <h4 class="text-lg font-medium mb-6 text-white">Company</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="text-graphite hover:text-white transition-colors">About Us</a></li>
                    <li><a href="#" class="text-graphite hover:text-white transition-colors">Careers</a></li>
                    <li><a href="#" class="text-graphite hover:text-white transition-colors">Blog</a></li>
                </ul>
            </div>
            
            <!-- Kolom 3 -->
            <div>
                <h4 class="text-lg font-medium mb-6 text-white">Support</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="text-graphite hover:text-white transition-colors">Help Center</a></li>
                    <li><a href="#" class="text-graphite hover:text-white transition-colors">Terms & Conditions</a></li>
                    <li><a href="#" class="text-graphite hover:text-white transition-colors">Privacy Policy</a></li>
                </ul>
            </div>
            
            <!-- Kolom 4 -->
            <div>
                <h4 class="text-lg font-medium mb-6 text-white">Newsletter</h4>
                <p class="text-graphite mb-4">Get the latest promo and destination updates.</p>
                <form class="flex items-center border border-graphite rounded-full p-1 focus-within:border-electric-lime transition-colors">
                    <input type="email" placeholder="Your Email" class="bg-transparent border-none outline-none focus:ring-0 text-white w-full px-4" required>
                    <button type="submit" class="bg-electric-lime text-white w-10 h-10 rounded-full flex items-center justify-center shrink-0 hover:bg-near-black hover:text-white hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Copyright Banner -->
        <div class="max-w-[1400px] mx-auto mt-24 pt-8 border-t border-charcoal text-graphite text-sm flex flex-col md:flex-row justify-between items-center gap-4">
            <p>&copy; {{ date('Y') }} Kelana Travel. All Rights Reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-white transition-colors">Instagram</a>
                <a href="#" class="hover:text-white transition-colors">Twitter</a>
                <a href="#" class="hover:text-white transition-colors">LinkedIn</a>
            </div>
        </div>
    </footer>

</body>
</html>
