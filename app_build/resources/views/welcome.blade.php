<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Kelana') }} - Jelajahi Dunia</title>

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
                Merasa lelah? Temukan lokasi terbaik untuk terhubung kembali dengan alam dan temukan ketenangan batin bersama Kelana.
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
        <div class="max-w-[1200px] mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-5xl font-medium tracking-tight mb-6">Pengalaman tak terbatas.</h2>
                <p class="text-xl text-graphite leading-relaxed">
                    Kami mendefinisikan ulang cara Anda berpetualang. Dengan standar layanan premium, setiap perjalanan dirancang untuk menjadi <span class="text-electric-lime">aman, nyaman, dan tak terlupakan.</span>
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-[#1a1a1a] border border-graphite/50 rounded-[26px] p-10 hover:border-electric-lime transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-[18px] bg-electric-lime/10 flex items-center justify-center mb-8 group-hover:bg-electric-lime transition-colors">
                        <svg class="w-8 h-8 text-electric-lime group-hover:text-near-black transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-2xl font-medium mb-4 text-white">Trip Leader Tersertifikasi</h3>
                    <p class="text-graphite leading-relaxed">Setiap perjalanan dipandu oleh ahli berpengalaman yang telah melewati seleksi ketat untuk memastikan standar pelayanan terbaik.</p>
                </div>

                <div class="bg-[#1a1a1a] border border-graphite/50 rounded-[26px] p-10 hover:border-electric-lime transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-[18px] bg-electric-lime/10 flex items-center justify-center mb-8 group-hover:bg-electric-lime transition-colors">
                        <svg class="w-8 h-8 text-electric-lime group-hover:text-near-black transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                    </div>
                    <h3 class="text-2xl font-medium mb-4 text-white">Rencana Eksklusif</h3>
                    <p class="text-graphite leading-relaxed">Akses ke destinasi tersembunyi dan pengalaman otentik lokal yang dirancang khusus dan tidak bisa didapatkan di tempat lain.</p>
                </div>

                <div class="bg-[#1a1a1a] border border-graphite/50 rounded-[26px] p-10 hover:border-electric-lime transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-[18px] bg-electric-lime/10 flex items-center justify-center mb-8 group-hover:bg-electric-lime transition-colors">
                        <svg class="w-8 h-8 text-electric-lime group-hover:text-near-black transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="text-2xl font-medium mb-4 text-white">Aman & Terjamin</h3>
                    <p class="text-graphite leading-relaxed">Prioritas utama pada keamanan dengan asuransi komprehensif, kendaraan terawat, dan protokol keselamatan tinggi.</p>
                </div>
            </div>
        </div>
    </section>



    <section class="bg-parchment-card py-32 px-6">
        <div class="max-w-[800px] mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-medium tracking-tight text-near-black mb-8">Kredibilitas yang tak perlu diragukan.</h2>
            <p class="text-xl text-graphite mb-12">
                Lebih dari 10,000 pelancong telah mempercayakan liburan mereka kepada kami. Keamanan terjamin dengan asuransi komprehensif di setiap langkah.
            </p>
            <div class="grid grid-cols-3 gap-8 text-center border-t border-stone pt-12">
                <div>
                    <h4 class="text-5xl font-medium text-near-black mb-2">10k+</h4>
                    <p class="text-graphite">Pelanggan Puas</p>
                </div>
                <div>
                    <h4 class="text-5xl font-medium text-near-black mb-2">50+</h4>
                    <p class="text-graphite">Destinasi</p>
                </div>
                <div>
                    <h4 class="text-5xl font-medium text-near-black mb-2">100%</h4>
                    <p class="text-graphite">Aman & Nyaman</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Katalog Paket Wisata (Premium Grid) -->
    <section id="destinasi" class="w-full bg-warm-cream py-32 px-6">
        <div class="max-w-[1200px] mx-auto">
            <div class="mb-16 text-center">
                <h2 class="text-[50px] font-medium tracking-tight text-near-black">Pilih Petualangan Anda.</h2>
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
                    <a href="{{ route('register') }}" class="group relative flex flex-col justify-end w-full aspect-[3/4] max-h-[600px] rounded-[32px] overflow-hidden shadow-2xl border border-stone/20">
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
                    <a href="{{ route('register') }}" class="group relative flex flex-col justify-end w-full aspect-[3/4] max-h-[600px] rounded-[32px] overflow-hidden shadow-2xl border border-stone/20">
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
                <h2 class="text-[40px] font-medium tracking-tight text-near-black mb-4">Pertanyaan yang Sering Diajukan</h2>
                <p class="text-graphite text-lg">Temukan jawaban untuk pertanyaan umum tentang layanan kami.</p>
            </div>

            <div class="w-full block space-y-4">
                <!-- FAQ 1 -->
                <div x-data="{ open: false }" class="border border-stone rounded-[26px] bg-white overflow-hidden w-full block box-border">
                    <button @click="open = !open" class="w-full flex justify-between items-center font-medium p-6 text-near-black focus:outline-none box-border">
                        <span class="text-left leading-relaxed">Bagaimana cara melakukan booking paket perjalanan?</span>
                        <span class="transition-transform duration-300 flex-shrink-0 ml-4" :class="open ? 'rotate-180' : ''">
                            <svg fill="none" height="24" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </button>
                    <div x-show="open" class="text-graphite px-6 pb-6 w-full text-left box-border" style="display: none;">
                        Anda dapat memilih destinasi yang diinginkan di halaman Katalog, lalu klik "Lihat Detail" dan ikuti proses pemesanan yang tersedia. Anda perlu login atau mendaftar akun terlebih dahulu untuk menyelesaikan pemesanan.
                    </div>
                </div>
                <!-- FAQ 2 -->
                <div x-data="{ open: false }" class="border border-stone rounded-[26px] bg-white overflow-hidden w-full block box-border">
                    <button @click="open = !open" class="w-full flex justify-between items-center font-medium p-6 text-near-black focus:outline-none box-border">
                        <span class="text-left leading-relaxed">Apakah harga paket sudah termasuk tiket pesawat?</span>
                        <span class="transition-transform duration-300 flex-shrink-0 ml-4" :class="open ? 'rotate-180' : ''">
                            <svg fill="none" height="24" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </button>
                    <div x-show="open" class="text-graphite px-6 pb-6 w-full text-left box-border" style="display: none;">
                        Secara umum, harga paket kami belum termasuk tiket pesawat dari kota asal Anda ke titik kumpul (meeting point). Namun, transportasi selama perjalanan di destinasi sudah ditanggung oleh Kelana.
                    </div>
                </div>
                <!-- FAQ 3 -->
                <div x-data="{ open: false }" class="border border-stone rounded-[26px] bg-white overflow-hidden w-full block box-border">
                    <button @click="open = !open" class="w-full flex justify-between items-center font-medium p-6 text-near-black focus:outline-none box-border">
                        <span class="text-left leading-relaxed">Apakah perjalanan ini aman untuk pemula?</span>
                        <span class="transition-transform duration-300 flex-shrink-0 ml-4" :class="open ? 'rotate-180' : ''">
                            <svg fill="none" height="24" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </button>
                    <div x-show="open" class="text-graphite px-6 pb-6 w-full text-left box-border" style="display: none;">
                        Tentu. Sebagian besar trip kami dirancang aman untuk pemula. Setiap grup akan dipandu oleh Trip Leader tersertifikasi yang akan memastikan keselamatan dan kenyamanan seluruh peserta.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. Comprehensive Footer -->
    <footer class="bg-near-black text-white pt-24 pb-8 px-6">
        <div class="max-w-[1200px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <!-- Kolom 1 -->
            <div>
                <a href="/" class="text-3xl font-medium tracking-tight mb-6 block text-white">Kelana</a>
                <p class="text-graphite leading-relaxed">
                    Membuka pintu menuju petualangan luar biasa dengan standar kenyamanan dan keamanan kelas dunia.
                </p>
            </div>
            
            <!-- Kolom 2 -->
            <div>
                <h4 class="text-lg font-medium mb-6 text-white">Perusahaan</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="text-graphite hover:text-white transition-colors">Tentang Kami</a></li>
                    <li><a href="#" class="text-graphite hover:text-white transition-colors">Karir</a></li>
                    <li><a href="#" class="text-graphite hover:text-white transition-colors">Blog</a></li>
                </ul>
            </div>
            
            <!-- Kolom 3 -->
            <div>
                <h4 class="text-lg font-medium mb-6 text-white">Dukungan</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="text-graphite hover:text-white transition-colors">Pusat Bantuan</a></li>
                    <li><a href="#" class="text-graphite hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="text-graphite hover:text-white transition-colors">Kebijakan Privasi</a></li>
                </ul>
            </div>
            
            <!-- Kolom 4 -->
            <div>
                <h4 class="text-lg font-medium mb-6 text-white">Newsletter</h4>
                <p class="text-graphite mb-4">Dapatkan info promo dan destinasi terbaru.</p>
                <form class="flex items-center border border-graphite rounded-full p-1 focus-within:border-electric-lime transition-colors">
                    <input type="email" placeholder="Email Anda" class="bg-transparent border-none outline-none focus:ring-0 text-white w-full px-4" required>
                    <button type="submit" class="bg-electric-lime text-near-black w-10 h-10 rounded-full flex items-center justify-center shrink-0 hover:opacity-90 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Copyright Banner -->
        <div class="max-w-[1200px] mx-auto mt-24 pt-8 border-t border-charcoal text-graphite text-sm flex flex-col md:flex-row justify-between items-center gap-4">
            <p>&copy; {{ date('Y') }} Kelana Travel. Hak Cipta Dilindungi.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-white transition-colors">Instagram</a>
                <a href="#" class="hover:text-white transition-colors">Twitter</a>
                <a href="#" class="hover:text-white transition-colors">LinkedIn</a>
            </div>
        </div>
    </footer>

</body>
</html>
