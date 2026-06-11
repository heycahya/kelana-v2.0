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

    <!-- 1. Top Announcement Banner -->
    <div class="w-full bg-electric-lime text-near-black py-3 text-center text-sm font-medium">
        Diskon 10% untuk open trip pertama Anda. Gunakan kode KELANA10
    </div>

    <!-- 2. Premium Navigation Bar -->
    <nav class="w-full bg-warm-cream border-b border-stone sticky top-0 z-50">
        <div class="max-w-[1200px] mx-auto px-6 py-5 flex justify-between items-center">
            <!-- Bagian Kiri (Logo) -->
            <a href="/" class="text-2xl font-medium tracking-tight">Kelana</a>

            <!-- Bagian Tengah (Menu) -->
            <div class="hidden md:flex space-x-8">
                <a href="#" class="text-graphite hover:text-near-black transition-colors">Beranda</a>
                <a href="#destinasi" class="text-graphite hover:text-near-black transition-colors">Destinasi</a>
                <a href="#" class="text-graphite hover:text-near-black transition-colors">Cara Kerja</a>
                <a href="#" class="text-graphite hover:text-near-black transition-colors">Testimoni</a>
            </div>

            <!-- Bagian Kanan (Auth) -->
            <div class="flex items-center space-x-3">
                @if (Auth::guard('customer')->check())
                    <a href="{{ route('dashboard') }}" class="bg-electric-lime border border-near-black px-6 py-2.5 rounded-[26px] font-medium">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="border border-near-black px-6 py-2.5 rounded-[26px]">Logout</button>
                    </form>
                @elseif (Auth::guard('admin')->check())
                    <a href="{{ route('admin.dashboard') }}" class="bg-electric-lime border border-near-black px-6 py-2.5 rounded-[26px] font-medium">Admin Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="border border-near-black px-6 py-2.5 rounded-[26px]">Logout</button>
                    </form>
                @elseif (Auth::guard('trip_leader')->check())
                    <a href="{{ route('trip_leader.dashboard') }}" class="bg-electric-lime border border-near-black px-6 py-2.5 rounded-[26px] font-medium">Leader Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="border border-near-black px-6 py-2.5 rounded-[26px]">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="border border-near-black px-6 py-2.5 rounded-[26px]">Login</a>
                    <a href="{{ route('register') }}" class="bg-electric-lime border border-near-black px-6 py-2.5 rounded-[26px] font-medium">Register</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- 3. Massive Hero Section -->
    <section class="relative min-h-[85vh] flex flex-col items-center justify-center text-center px-6 py-24 bg-warm-cream overflow-hidden">
        <h1 class="text-[60px] md:text-[90px] leading-[0.89] tracking-[-0.03em] font-medium text-near-black max-w-4xl mb-8 z-10">Jelajahi Dunia dengan Kelana.</h1>
        <p class="text-graphite text-xl max-w-2xl mx-auto mb-12 z-10 leading-relaxed">
            Temukan pengalaman tak terlupakan dan petualangan premium bersama trip leader terbaik. Perjalanan impianmu dimulai dari sini.
        </p>
        <div class="flex gap-4 justify-center z-10">
            <a href="#destinasi" class="bg-electric-lime border border-near-black px-8 py-3 rounded-[26px] font-medium hover:opacity-90 transition">Lihat Destinasi</a>
            <a href="#" class="border border-near-black px-8 py-3 rounded-[26px] hover:bg-stone transition font-medium">Cara Kerja</a>
        </div>
        
        <!-- Floating Cards (Visual Dekoratif) -->
        <div class="absolute left-10 md:left-24 top-1/3 w-48 h-64 bg-white border border-stone rounded-[26px] -rotate-6 shadow-none z-0 overflow-hidden hidden md:block">
            <div class="w-full h-2/3 bg-stone opacity-50"></div>
            <div class="p-4">
                <div class="h-2 bg-stone rounded w-3/4 mb-2"></div>
                <div class="h-2 bg-stone rounded w-1/2"></div>
            </div>
        </div>
        <div class="absolute right-10 md:right-24 bottom-1/4 w-48 h-64 bg-white border border-stone rounded-[26px] rotate-3 shadow-none z-0 overflow-hidden hidden md:block">
            <div class="w-full h-2/3 bg-stone opacity-50"></div>
            <div class="p-4">
                <div class="h-2 bg-stone rounded w-3/4 mb-2"></div>
                <div class="h-2 bg-stone rounded w-1/2"></div>
            </div>
        </div>
    </section>

    <!-- 4. Value Proposition (Dark & Cream Sections) -->
    <section class="bg-near-black text-white py-32 px-6">
        <div class="max-w-[1200px] mx-auto grid md:grid-cols-2 gap-16">
            <div class="flex flex-col justify-center">
                <h2 class="text-5xl font-medium tracking-tight mb-6">Pengalaman tak terbatas.</h2>
                <p class="text-xl text-graphite leading-relaxed">
                    Kami mendefinisikan ulang cara Anda berpetualang. Dengan standar layanan enterprise, setiap perjalanan menjadi <span class="text-electric-lime">aman, nyaman, dan berkesan.</span>
                </p>
            </div>
            <div class="grid grid-cols-1 gap-8">
                <div class="border border-graphite rounded-[26px] p-8 hover:border-electric-lime transition-colors">
                    <h3 class="text-2xl font-medium mb-3 text-electric-lime">Trip Leader Tersertifikasi</h3>
                    <p class="text-graphite">Setiap perjalanan dipandu oleh ahli berpengalaman yang telah melewati seleksi ketat.</p>
                </div>
                <div class="border border-graphite rounded-[26px] p-8 hover:border-electric-lime transition-colors">
                    <h3 class="text-2xl font-medium mb-3 text-electric-lime">Rencana Perjalanan Eksklusif</h3>
                    <p class="text-graphite">Akses ke destinasi tersembunyi dan pengalaman yang tidak bisa didapatkan di tempat lain.</p>
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
    <section id="destinasi" class="max-w-[1200px] mx-auto px-6 py-32 bg-warm-cream">
        <div class="mb-16">
            <h2 class="text-[50px] font-medium tracking-tight text-near-black">Pilih Petualangan Anda.</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($paketWisata ?? [] as $paket)
                <div class="bg-white rounded-[26px] p-8 border border-stone flex flex-col h-full hover:border-near-black transition-colors">
                    <div class="mb-6">
                        <!-- Image Area -->
                        <div class="h-56 bg-stone rounded-[18px] mb-6 overflow-hidden flex items-center justify-center relative">
                            <!-- Badge -->
                            <span class="absolute top-4 left-4 bg-mint-confirm text-near-black text-xs font-bold px-3 py-1 rounded-[8px]">
                                Tersedia
                            </span>
                            <span class="text-near-black font-medium text-lg opacity-40">Gambar Destinasi</span>
                        </div>
                        
                        <h3 class="text-2xl font-medium text-near-black mb-3">{{ $paket->nama_paket ?? 'Nama Paket' }}</h3>
                        <p class="text-graphite mb-6 line-clamp-3 leading-relaxed">{{ $paket->deskripsi ?? 'Deskripsi paket wisata...' }}</p>
                    </div>

                    <div class="mt-auto">
                        <div class="border-t border-stone pt-6 mb-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm text-graphite mb-1">Mulai dari</p>
                                <span class="font-medium text-2xl text-near-black">Rp {{ number_format($paket->harga ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-near-black font-medium">★ 4.9</span>
                            </div>
                        </div>
                        <a href="{{ route('paket.detail', $paket->id_paket ?? 1) }}" class="block w-full text-center px-6 py-3.5 bg-white border border-near-black text-near-black rounded-[26px] font-medium hover:bg-electric-lime transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <!-- Dummy Cards for Preview -->
                @for($i=1; $i<=3; $i++)
                <div class="bg-white rounded-[26px] p-8 border border-stone flex flex-col h-full hover:border-near-black transition-colors">
                    <div class="mb-6">
                        <div class="h-56 bg-stone rounded-[18px] mb-6 overflow-hidden flex items-center justify-center relative">
                            @if($i == 2)
                            <span class="absolute top-4 left-4 bg-coral-alert text-near-black text-xs font-bold px-3 py-1 rounded-[8px]">
                                Hampir Penuh
                            </span>
                            @else
                            <span class="absolute top-4 left-4 bg-mint-confirm text-near-black text-xs font-bold px-3 py-1 rounded-[8px]">
                                Tersedia
                            </span>
                            @endif
                            <span class="text-near-black font-medium text-lg opacity-40">Gambar Destinasi</span>
                        </div>
                        <h3 class="text-2xl font-medium text-near-black mb-3">Paket Petualangan {{ $i }}</h3>
                        <p class="text-graphite mb-6 line-clamp-3 leading-relaxed">Nikmati perjalanan tak terlupakan ke destinasi menakjubkan dengan panduan ahli dan fasilitas premium.</p>
                    </div>
                    <div class="mt-auto">
                        <div class="border-t border-stone pt-6 mb-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm text-graphite mb-1">Mulai dari</p>
                                <span class="font-medium text-2xl text-near-black">Rp 5.000.000</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-near-black font-medium">★ 4.9</span>
                            </div>
                        </div>
                        <a href="#" class="block w-full text-center px-6 py-3.5 bg-white border border-near-black text-near-black rounded-[26px] font-medium hover:bg-electric-lime transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                @endfor
            @endforelse
        </div>
    </section>

    <!-- 6. Comprehensive Footer -->
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
