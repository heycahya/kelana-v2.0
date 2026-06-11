<x-guest-layout>
    <div class="py-12 px-6 max-w-4xl mx-auto w-full">
        <!-- Back Link -->
        <a href="/" class="inline-flex items-center text-graphite hover:text-near-black font-semibold mb-8 transition duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Katalog
        </a>

        <!-- Package Content -->
        <div class="bg-white rounded-3xl p-8 md:p-10 border border-stone">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-stone pb-6 mb-8 gap-4">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-widest text-graphite block mb-1">Paket Wisata Premium</span>
                    <h1 class="text-4xl md:text-5xl font-black text-near-black tracking-tight leading-tight">{{ $paket->nama_paket }}</h1>
                </div>
                <div class="bg-warm-cream p-4 rounded-2xl border border-stone text-right md:min-w-[200px]">
                    <p class="text-xs text-graphite uppercase tracking-wider">Harga per Orang</p>
                    <span class="font-extrabold text-2xl text-near-black">Rp {{ number_format($paket->harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-near-black mb-3">Deskripsi Paket</h3>
                <p class="text-graphite leading-relaxed text-lg">{{ $paket->deskripsi }}</p>
            </div>

            <!-- Route and Facilities -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="bg-parchment-card p-6 rounded-3xl border border-stone">
                    <h4 class="text-md font-bold text-near-black mb-2 uppercase tracking-wide">Rute Perjalanan</h4>
                    <p class="text-graphite font-medium leading-relaxed">{{ $paket->rute }}</p>
                </div>
                <div class="bg-parchment-card p-6 rounded-3xl border border-stone">
                    <h4 class="text-md font-bold text-near-black mb-2 uppercase tracking-wide">Fasilitas Termasuk</h4>
                    <p class="text-graphite font-medium leading-relaxed">{{ $paket->fasilitas }}</p>
                </div>
            </div>

            <!-- Active Schedules -->
            <div>
                <h3 class="text-2xl font-bold text-near-black mb-6 tracking-tight">Jadwal Keberangkatan</h3>
                
                <div class="space-y-4">
                    @forelse($activeSchedules as $jadwal)
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-6 bg-parchment-card rounded-3xl border border-stone gap-4">
                            <div>
                                <span class="text-xs text-graphite uppercase tracking-wider block mb-1">Tanggal Mulai</span>
                                <p class="font-extrabold text-xl text-near-black">
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}
                                    @if($jadwal->tanggal_mulai !== $jadwal->tanggal_selesai)
                                        <span class="text-sm font-normal text-graphite"> s/d {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y') }}</span>
                                    @endif
                                </p>
                                <span class="text-sm font-semibold text-graphite">Sisa Kuota: <span class="text-near-black font-bold">{{ $jadwal->sisa_kuota }}</span> kursi</span>
                            </div>
                            
                            @if($jadwal->sisa_kuota > 0)
                                <a href="{{ route('customer.booking', ['jadwal_id' => $jadwal->id_jadwal]) }}" class="w-full sm:w-auto text-center px-6 py-3 bg-electric-lime text-near-black font-bold rounded-3xl hover:opacity-90 transition duration-200">
                                    Pesan Kursi
                                </a>
                            @else
                                <span class="w-full sm:w-auto text-center px-6 py-3 bg-stone text-graphite font-bold rounded-3xl cursor-not-allowed">
                                    Penuh
                                </span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-10 bg-parchment-card border border-stone rounded-3xl">
                            <p class="text-graphite">Belum ada jadwal keberangkatan aktif untuk paket ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
