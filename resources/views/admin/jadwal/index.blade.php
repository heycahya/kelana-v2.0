@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="border-b border-[#dfdfd6] pb-6">
        <h1 class="text-3xl font-bold tracking-tight text-near-black">Jadwal & Penugasan</h1>
        <p class="text-graphite text-sm mt-1">Kelola jadwal keberangkatan trip dan penugasan Trip Leader.</p>
    </div>

    <!-- Table Container -->
    <div class="bg-white p-8 rounded-[26px] border border-stone">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-near-black text-white">
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider rounded-l-[16px]">ID</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Paket Wisata</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Trip Leader</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Tanggal Mulai</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Tanggal Selesai</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Kuota Tersisa</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Status</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider rounded-r-[16px] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone/50 font-medium text-sm text-near-black">
                    @forelse($jadwals as $jadwal)
                        <tr class="hover:bg-warm-cream/35 transition duration-150">
                            <td class="p-5 font-semibold text-stone">#{{ $jadwal->id_jadwal }}</td>
                            <td class="p-5">
                                <span class="font-bold text-near-black block text-base">{{ $jadwal->paketWisata->nama_paket ?? '-' }}</span>
                            </td>
                            <td class="p-5">
                                <span class="font-bold text-near-black block">{{ $jadwal->tripLeader->nama_leader ?? '-' }}</span>
                                <span class="text-xs text-graphite font-normal">{{ $jadwal->tripLeader->email ?? '' }}</span>
                            </td>
                            <td class="p-5 text-graphite text-xs">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}</td>
                            <td class="p-5 text-graphite text-xs">{{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y') }}</td>
                            <td class="p-5">
                                <span class="font-bold text-electric-lime text-base">{{ $jadwal->sisa_kuota }}</span>
                                <span class="text-graphite text-xs">/ {{ $jadwal->kuota }} Pax</span>
                            </td>
                            <td class="p-5">
                                @if($jadwal->status_trip === 'Draft')
                                    <span class="inline-block bg-stone/20 text-graphite border border-stone px-2.5 py-0.5 rounded-full text-xs font-semibold">DRAFT</span>
                                @elseif($jadwal->status_trip === 'Open')
                                    <span class="inline-block bg-green-50 text-green-700 border border-green-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">OPEN</span>
                                @elseif($jadwal->status_trip === 'Berjalan')
                                    <span class="inline-block bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">RUNNING</span>
                                @elseif($jadwal->status_trip === 'Selesai')
                                    <span class="inline-block bg-purple-50 text-purple-700 border border-purple-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">DONE</span>
                                @else
                                    <span class="inline-block bg-red-50 text-red-700 border border-red-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">{{ strtoupper($jadwal->status_trip) }}</span>
                                @endif
                            </td>
                            <td class="p-5 text-right space-x-2">
                                <a href="{{ route('admin.jadwal.edit', $jadwal->id_jadwal) }}" class="inline-block bg-warm-cream text-near-black border border-stone font-semibold px-4 py-2 rounded-full hover:bg-stone/20 text-xs transition duration-150">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.jadwal.destroy', $jadwal->id_jadwal) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-50 text-red-700 border border-red-200 font-semibold px-4 py-2 rounded-full hover:bg-red-600 hover:text-white hover:border-red-600 text-xs transition duration-150">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-10 text-center text-stone-400 font-semibold">Belum ada jadwal trip terdaftar.</td>
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
