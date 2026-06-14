<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiket Saya - Kelana</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-warm-cream font-sans text-near-black antialiased min-h-screen flex flex-col">
    @include('components.navbar')

    <main class="max-w-[1400px] mx-auto px-6 py-12 flex-grow w-full">
        <!-- Back Button -->
        <div class="mb-6 flex justify-start">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full border border-transparent bg-stone/50 text-xs font-semibold uppercase tracking-wider text-near-black hover:bg-near-black hover:text-white transition-all duration-300 ease-in-out">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span>Back</span>
            </a>
        </div>

        <!-- Header -->
        <div class="mb-10 pb-6 border-b border-stone/50">
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-[#0f1a15]">Tiket & Perjalanan Saya</h1>
            <p class="text-[#3f4e45] text-sm mt-1">Kelola tiket aktif Anda dan lihat riwayat petualangan Anda yang telah selesai.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Active Trip E-Tickets -->
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <h3 class="text-xl font-bold text-[#0f1a15]">Tiket Aktif Anda</h3>
                    <p class="text-[#3f4e45] text-xs mt-0.5">E-Ticket untuk open trip Anda yang akan datang</p>
                </div>

                @forelse($activeTrips ?? [] as $trip)
                    <div class="bg-white rounded-[26px] border border-stone p-6">
                        <div class="flex flex-wrap justify-between items-center border-b border-stone pb-4 mb-6 gap-3">
                            <div>
                                <p class="text-[10px] text-[#3f4e45] uppercase tracking-wider font-bold">Kode Booking</p>
                                <h4 class="text-xl font-extrabold text-[#0f1a15] tracking-tight font-mono">{{ $trip->booking_code }}</h4>
                            </div>
                            <span class="px-3.5 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ $trip->status_pembayaran === 'PAID' ? 'bg-[#1e5e3a]/10 text-[#1e5e3a] border border-[#1e5e3a]/20' : 'bg-yellow-50 text-yellow-700 border border-yellow-200' }}">
                                {{ $trip->status_pembayaran }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <p class="text-[10px] text-[#3f4e45] uppercase tracking-wider font-bold">Destinasi Open Trip</p>
                                <a href="{{ route('paket.detail', $trip->jadwal->id_paket) }}" class="font-bold text-base text-[#0f1a15] hover:text-[#1e5e3a] transition">
                                    {{ $trip->jadwal->paketWisata->nama_paket }}
                                </a>
                            </div>
                            <div>
                                <p class="text-[10px] text-[#3f4e45] uppercase tracking-wider font-bold">Tanggal Keberangkatan</p>
                                <p class="font-bold text-base text-[#0f1a15]">{{ \Carbon\Carbon::parse($trip->jadwal->tanggal_mulai)->format('d M Y') }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-[10px] text-[#3f4e45] uppercase tracking-wider font-bold">Jumlah Peserta</p>
                                <p class="font-bold text-[#0f1a15] text-base">
                                    {{ $trip->jumlah_peserta }} Orang 
                                    <span class="text-xs font-medium text-[#3f4e45]">
                                        (Kehadiran di lokasi: <span class="text-[#0f1a15] font-bold">{{ $trip->jumlah_hadir ?? 0 }}</span>)
                                    </span>
                                </p>
                            </div>
                            
                            <!-- Payment Breakdown -->
                            <div class="sm:col-span-2 mt-4 pt-4 border-t border-stone/30">
                                <p class="text-[10px] text-[#3f4e45] uppercase tracking-wider font-bold">Rincian Pembayaran</p>
                                <div class="flex flex-wrap gap-x-6 gap-y-2 mt-1.5 text-xs text-[#3f4e45] font-semibold">
                                    <span>Paket: <strong class="text-[#0f1a15]">Rp {{ number_format($trip->jumlah_peserta * $trip->jadwal->paketWisata->harga, 0, ',', '.') }}</strong></span>
                                    @if($trip->promo_code)
                                        <span class="text-[#1e5e3a]">Diskon ({{ $trip->promo_code }}): <strong class="font-bold">-Rp {{ number_format($trip->diskon, 0, ',', '.') }}</strong></span>
                                    @endif
                                    @if($trip->total_biaya_addons > 0)
                                        <span>Add-ons: <strong class="text-[#0f1a15]">+Rp {{ number_format($trip->total_biaya_addons, 0, ',', '.') }}</strong></span>
                                    @endif
                                    <span>Total: <strong class="text-electric-lime text-sm">Rp {{ number_format($trip->total_harga + $trip->total_biaya_addons, 0, ',', '.') }}</strong></span>
                                </div>
                            </div>
                        </div>

                        @if($trip->status_pembayaran === 'PAID')
                            <div class="mt-6 pt-4 border-t border-stone flex justify-end">
                                <a href="{{ route('customer.booking.ticket.pdf', $trip->booking_code) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-electric-lime text-white text-[10px] font-bold uppercase tracking-wider hover:bg-near-black hover:scale-105 active:scale-95 transition-all duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    <span>Download E-Ticket (PDF)</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-16 bg-white border border-stone rounded-[26px] p-8">
                        <svg class="w-12 h-12 text-[#3f4e45] mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        <p class="text-[#3f4e45] text-base font-semibold">Anda belum memiliki tiket aktif saat ini.</p>
                        <a href="{{ route('dashboard') }}#destinasi" class="inline-block mt-4 px-6 py-2.5 bg-[#1e5e3a] text-white text-xs font-bold uppercase tracking-wider rounded-full hover:bg-[#154329] transition">
                            Temukan Open Trip
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Past Trips History -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-bold text-[#0f1a15]">Riwayat Perjalanan</h3>
                    <p class="text-[#3f4e45] text-xs mt-0.5">Perjalanan yang telah Anda selesaikan</p>
                </div>

                <div class="space-y-4">
                    @forelse($pastTrips ?? [] as $trip)
                        <div class="bg-white rounded-[26px] p-6 border border-stone">
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-xs text-[#3f4e45] font-bold uppercase tracking-wider">
                                    {{ \Carbon\Carbon::parse($trip->jadwal->tanggal_mulai)->format('d M Y') }}
                                </span>
                                <span class="px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-stone/50 text-near-black">
                                    SELESAI
                                </span>
                            </div>
                            <h4 class="font-bold text-[#0f1a15] tracking-tight mb-2">
                                {{ $trip->jadwal->paketWisata->nama_paket }}
                            </h4>
                            <p class="text-xs text-[#3f4e45] font-semibold">Kode Booking: <span class="font-mono">{{ $trip->booking_code }}</span></p>
                            <p class="text-xs text-[#3f4e45] mt-1 font-semibold">
                                Total Bayar: <span class="font-bold text-[#0f1a15]">Rp {{ number_format($trip->total_harga + $trip->total_biaya_addons, 0, ',', '.') }}</span>
                                @if($trip->promo_code)
                                    <span class="text-[10px] text-[#1e5e3a] font-bold block mt-0.5">Promo: {{ $trip->promo_code }} (-Rp {{ number_format($trip->diskon, 0, ',', '.') }})</span>
                                @endif
                            </p>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-white border border-stone rounded-[26px] p-6">
                            <p class="text-[#3f4e45] text-xs font-semibold">Belum ada riwayat perjalanan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#0f1a15] text-white py-12 px-6 border-t border-white/10 mt-auto">
        <div class="max-w-[1400px] mx-auto text-center">
            <p class="text-xs text-white/40">&copy; {{ date('Y') }} Kelana Travel. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
