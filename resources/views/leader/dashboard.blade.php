@extends('layouts.leader')

@section('content')
<div class="space-y-8">
    <!-- Header/Overview Profile -->
    <div class="bg-white p-8 rounded-[24px] border border-[#dfdfd6] relative overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center space-x-6 relative z-10">
            <img class="w-16 h-16 rounded-full border border-[#dfdfd6] object-cover" src="{{ auth()->user()->avatar ?? 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80' }}" alt="{{ auth()->user()->nama_leader }}">
            <div>
                <span class="text-[9px] font-bold uppercase tracking-widest text-[#1e5e3a] bg-[#1e5e3a]/10 px-3 py-1 rounded-full border border-[#1e5e3a]/20">Sertifikasi Internasional</span>
                <h2 class="font-extrabold text-2xl text-near-black mt-2 tracking-tight">{{ auth()->user()->nama_leader }}</h2>
                <p class="text-graphite font-normal text-xs mt-1 max-w-xl leading-relaxed">{{ auth()->user()->bio ?? 'Senior Certified Trip Leader at Kelana, specialize in mountain hiking and tropical jungle excursions.' }}</p>
            </div>
        </div>
        
        <div class="bg-warm-cream border border-[#dfdfd6] rounded-[20px] p-5 text-center min-w-[150px] relative z-10 self-stretch md:self-auto flex flex-col justify-center">
            <span class="text-[8px] font-bold text-graphite/60 uppercase tracking-widest block">Accumulated Rating</span>
            <div class="flex items-center justify-center space-x-2 mt-1">
                <span class="text-2xl font-black text-[#1e5e3a] tracking-tight">{{ auth()->user()->rating_akumulatif ?? '5.0' }}</span>
                <span class="text-yellow-500 text-lg">★</span>
            </div>
        </div>
    </div>

    <!-- Statistics Row (Dynamic & DB-integrated) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="stats-section">
        <!-- Stat Card 1: Total Trips -->
        <div class="bg-white p-6 rounded-[24px] border border-[#dfdfd6] flex flex-col justify-between h-32">
            <span class="text-[10px] font-bold uppercase tracking-widest text-graphite/60">Total Penugasan</span>
            <h3 class="text-3xl font-extrabold text-near-black tracking-tight mt-2">{{ $totalTrips }}</h3>
            <span class="text-[10px] font-semibold text-stone-500 mt-1">Schedules assigned to you</span>
        </div>

        <!-- Stat Card 2: Total Pax Guided -->
        <div class="bg-white p-6 rounded-[24px] border border-[#dfdfd6] flex flex-col justify-between h-32">
            <span class="text-[10px] font-bold uppercase tracking-widest text-graphite/60">Total Peserta Dipandu</span>
            <h3 class="text-3xl font-extrabold text-[#1e5e3a] tracking-tight mt-2">{{ $totalPax }} <span class="text-xs font-semibold text-graphite/60">Pax</span></h3>
            <span class="text-[10px] font-semibold text-stone-500 mt-1">Confirmed PAID clients</span>
        </div>

        <!-- Stat Card 3: Check-in Rate -->
        <div class="bg-white p-6 rounded-[24px] border border-[#dfdfd6] flex flex-col justify-between h-32">
            <span class="text-[10px] font-bold uppercase tracking-widest text-graphite/60">Rasio Check-In Lapangan</span>
            <h3 class="text-3xl font-extrabold text-near-black tracking-tight mt-2">{{ $checkInRate }}%</h3>
            <div class="w-full bg-[#f4f3ed] h-2 rounded-full overflow-hidden border border-[#dfdfd6]/60 mt-1">
                <div class="bg-[#1e5e3a] h-full" style="width: {{ $checkInRate }}%"></div>
            </div>
        </div>
    </div>

    <!-- Assigned Trips Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-[#dfdfd6] pb-4">
            <h3 class="font-bold text-lg text-near-black tracking-tight">Jadwal Penugasan Memandu</h3>
            <span class="text-xs font-semibold text-graphite/60">{{ $jadwals->count() }} Trips Assigned</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($jadwals as $jadwal)
                <div class="bg-white rounded-[24px] border border-[#dfdfd6] p-6 flex flex-col justify-between h-72 hover:border-[#1e5e3a] transition duration-150">
                    <div class="space-y-4">
                        <div class="flex justify-between items-start gap-4">
                            <h4 class="font-bold text-base text-near-black leading-snug tracking-tight">
                                {{ $jadwal->paketWisata->nama_paket ?? 'Paket Wisata' }}
                            </h4>
                            @if($jadwal->status_trip === 'Draft')
                                <span class="bg-[#f4f3ed] text-graphite border border-[#dfdfd6] px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase leading-none">DRAFT</span>
                            @elseif($jadwal->status_trip === 'Open')
                                <span class="bg-[#eaf5ea] text-[#1e5e3a] border border-[#1e5e3a]/20 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase leading-none">OPEN</span>
                            @elseif($jadwal->status_trip === 'Berjalan')
                                <span class="bg-blue-50 text-blue-800 border border-blue-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase leading-none">RUNNING</span>
                            @elseif($jadwal->status_trip === 'Selesai')
                                <span class="bg-purple-50 text-purple-800 border border-purple-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase leading-none">DONE</span>
                            @else
                                <span class="bg-red-50 text-red-800 border border-red-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase leading-none">{{ $jadwal->status_trip }}</span>
                            @endif
                        </div>

                        <!-- Date and pax counters -->
                        <div class="space-y-2.5 text-xs font-semibold text-graphite">
                            <div class="flex items-center">
                                <span class="w-6 text-sm">📅</span>
                                <span>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 text-sm">👥</span>
                                <span>Kuota Sisa: <strong class="text-[#1e5e3a] font-bold">{{ $jadwal->sisa_kuota }}</strong> / {{ $jadwal->kuota }} Pax</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 text-sm">🗺️</span>
                                <span class="truncate max-w-[200px]">{{ $jadwal->paketWisata->rute ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('leader.manifest.show', $jadwal->id_jadwal) }}" class="w-full bg-[#1e5e3a] text-white font-bold text-center py-3 rounded-full hover:bg-near-black transition duration-150 text-xs uppercase tracking-wider mt-4 block">
                        🔍 Buka Manifes & Scan QR
                    </a>
                </div>
            @empty
                <div class="col-span-full bg-white p-12 rounded-[24px] border border-[#dfdfd6] text-center">
                    <p class="text-stone-400 font-semibold text-sm">Belum ada penugasan trip aktif untuk akun Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
