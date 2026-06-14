@extends('layouts.leader')

@section('content')
<div class="space-y-8">
    <!-- Header Title -->
    <div>
        <h2 class="font-extrabold text-2xl text-near-black tracking-tight">Statistik Kinerja</h2>
        <p class="text-graphite font-normal text-xs mt-1">Pantau performa penugasan dan tingkat kehadiran peserta Anda di lapangan.</p>
    </div>

    <!-- Statistics Cards Row (100% Flat Siohioma Style) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left 2 Cols: Assigned Trips History Table -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between border-b border-[#dfdfd6] pb-4">
                <h3 class="font-bold text-lg text-near-black tracking-tight">Riwayat Penugasan & Check-In</h3>
                <span class="text-xs font-semibold text-graphite/60">{{ $jadwals->count() }} Trips Total</span>
            </div>

            <div class="bg-white border border-[#dfdfd6] rounded-[24px] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#dfdfd6] bg-[#f4f3ed]/60 text-[10px] uppercase tracking-wider text-graphite/60 font-sans">
                                <th class="py-4 px-6 font-bold">Trip / Paket Wisata</th>
                                <th class="py-4 px-6 font-bold">Tanggal</th>
                                <th class="py-4 px-6 font-bold text-center">Kehadiran</th>
                                <th class="py-4 px-6 font-bold text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#dfdfd6]/60">
                            @forelse($jadwals as $jadwal)
                                @php
                                    $paidPemesanan = $jadwal->pemesanan->where('status_pembayaran', 'PAID');
                                    $tripPax = $paidPemesanan->sum('jumlah_peserta');
                                    $tripAttended = $paidPemesanan->where('attendance_status', 'hadir')->sum('jumlah_peserta');
                                    $tripRate = $tripPax > 0 ? round(($tripAttended / $tripPax) * 100) : 100;
                                @endphp
                                <tr class="hover:bg-warm-cream/20 transition-colors">
                                    <td class="py-5 px-6">
                                        <div class="space-y-1">
                                            <p class="font-bold text-sm text-near-black tracking-tight leading-snug">
                                                {{ $jadwal->paketWisata->nama_paket ?? 'Paket Wisata' }}
                                            </p>
                                            <div class="flex items-center space-x-2">
                                                @if($jadwal->status_trip === 'Draft')
                                                    <span class="bg-[#f4f3ed] text-graphite border border-[#dfdfd6] px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wider uppercase">DRAFT</span>
                                                @elseif($jadwal->status_trip === 'Open')
                                                    <span class="bg-[#eaf5ea] text-[#1e5e3a] border border-[#1e5e3a]/20 px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wider uppercase">OPEN</span>
                                                @elseif($jadwal->status_trip === 'Berjalan')
                                                    <span class="bg-blue-50 text-blue-800 border border-blue-200 px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wider uppercase">RUNNING</span>
                                                @elseif($jadwal->status_trip === 'Selesai')
                                                    <span class="bg-purple-50 text-purple-800 border border-purple-200 px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wider uppercase">DONE</span>
                                                @else
                                                    <span class="bg-red-50 text-red-800 border border-red-200 px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wider uppercase">{{ $jadwal->status_trip }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-5 px-6 text-xs text-graphite font-semibold whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y') }}
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        <div class="inline-block space-y-1 text-center">
                                            <p class="text-xs font-bold text-near-black">
                                                {{ $tripAttended }} / {{ $tripPax }} <span class="text-graphite font-normal">Pax</span>
                                            </p>
                                            <div class="flex items-center justify-center space-x-1">
                                                <div class="w-12 bg-[#f4f3ed] h-1.5 rounded-full overflow-hidden border border-[#dfdfd6]/60">
                                                    <div class="bg-[#1e5e3a] h-full" style="width: {{ $tripRate }}%"></div>
                                                </div>
                                                <span class="text-[9px] font-bold text-graphite/80">{{ $tripRate }}%</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-5 px-6 text-right">
                                        <a href="{{ route('leader.manifest.show', $jadwal->id_jadwal) }}" class="inline-block bg-[#f4f3ed] text-[#1e5e3a] border border-[#dfdfd6] font-bold text-[10px] px-3.5 py-1.5 rounded-full hover:bg-[#1e5e3a] hover:text-white hover:border-[#1e5e3a] transition-all uppercase tracking-wider">
                                            Manifes
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-stone-400 font-semibold text-sm">
                                        Belum ada data penugasan trip.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Trip Status Breakdown Panel -->
        <div class="space-y-4">
            <div class="border-b border-[#dfdfd6] pb-4">
                <h3 class="font-bold text-lg text-near-black tracking-tight">Status Penugasan</h3>
            </div>

            <div class="bg-white p-6 rounded-[24px] border border-[#dfdfd6] space-y-6">
                <!-- Status Row Template helper function for percentage calculations -->
                @php
                    $calcPercent = function($count, $total) {
                        return $total > 0 ? round(($count / $total) * 100) : 0;
                    };
                @endphp

                <!-- Status 1: Draft -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-semibold text-near-black">
                        <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-stone-300 mr-2"></span>Draft</span>
                        <span>{{ $draftTrips }} Trip ({{ $calcPercent($draftTrips, $totalTrips) }}%)</span>
                    </div>
                    <div class="w-full bg-[#f4f3ed] h-2.5 rounded-full overflow-hidden border border-[#dfdfd6]/60">
                        <div class="bg-stone-400 h-full" style="width: {{ $calcPercent($draftTrips, $totalTrips) }}%"></div>
                    </div>
                </div>

                <!-- Status 2: Open -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-semibold text-near-black">
                        <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-green-300 mr-2"></span>Open for Booking</span>
                        <span>{{ $openTrips }} Trip ({{ $calcPercent($openTrips, $totalTrips) }}%)</span>
                    </div>
                    <div class="w-full bg-[#f4f3ed] h-2.5 rounded-full overflow-hidden border border-[#dfdfd6]/60">
                        <div class="bg-[#1e5e3a] h-full" style="width: {{ $calcPercent($openTrips, $totalTrips) }}%"></div>
                    </div>
                </div>

                <!-- Status 3: Running (Berjalan) -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-semibold text-near-black">
                        <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-blue-400 mr-2"></span>Berjalan (Running)</span>
                        <span>{{ $runningTrips }} Trip ({{ $calcPercent($runningTrips, $totalTrips) }}%)</span>
                    </div>
                    <div class="w-full bg-[#f4f3ed] h-2.5 rounded-full overflow-hidden border border-[#dfdfd6]/60">
                        <div class="bg-blue-500 h-full" style="width: {{ $calcPercent($runningTrips, $totalTrips) }}%"></div>
                    </div>
                </div>

                <!-- Status 4: Done (Selesai) -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-semibold text-near-black">
                        <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-purple-400 mr-2"></span>Selesai (Completed)</span>
                        <span>{{ $completedTrips }} Trip ({{ $calcPercent($completedTrips, $totalTrips) }}%)</span>
                    </div>
                    <div class="w-full bg-[#f4f3ed] h-2.5 rounded-full overflow-hidden border border-[#dfdfd6]/60">
                        <div class="bg-purple-600 h-full" style="width: {{ $calcPercent($completedTrips, $totalTrips) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
