@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-center border-b border-stone pb-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-near-black">Laporan Finansial & Rekap</h1>
            <p class="text-graphite text-sm mt-1">Unduh rekap manifest peserta dan finansial per jadwal keberangkatan.</p>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white p-8 rounded-[26px] border border-stone">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-near-black text-white">
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider rounded-l-[16px]">ID</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Paket Wisata</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Tanggal Mulai</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Trip Leader</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Pemesanan Lunas</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider rounded-r-[16px] text-right">Laporan PDF</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone/50 font-medium text-sm text-near-black">
                    @forelse($jadwals as $jadwal)
                        <tr class="hover:bg-warm-cream/35 transition duration-150">
                            <td class="p-5 font-semibold text-stone">#{{ $jadwal->id_jadwal }}</td>
                            <td class="p-5">
                                <span class="font-bold text-near-black block text-base">{{ $jadwal->paketWisata->nama_paket ?? '-' }}</span>
                            </td>
                            <td class="p-5 text-graphite text-xs">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}</td>
                            <td class="p-5 text-graphite text-sm">{{ $jadwal->tripLeader->nama_leader ?? '-' }}</td>
                            <td class="p-5 font-bold text-electric-lime text-base">{{ $jadwal->paid_bookings_count }} Transaksi</td>
                            <td class="p-5 text-right">
                                <a href="{{ route('admin.laporan.download', $jadwal->id_jadwal) }}" class="inline-block bg-electric-lime text-white font-semibold px-5 py-2.5 rounded-full hover:bg-electric-lime/95 text-xs transition duration-150 transform hover:-translate-y-0.5 active:translate-y-0">
                                    📥 Unduh PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-stone-400 font-semibold">Belum ada data jadwal trip terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $jadwals->links() }}
        </div>
    </div>
</div>
@endsection
