@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center border-b border-stone pb-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-near-black">Detail Jadwal & Penugasan</h1>
            <p class="text-graphite text-sm mt-1">Data lengkap untuk jadwal trip #{{ $jadwal->id_jadwal }}.</p>
        </div>
        <a href="{{ route('admin.jadwal.index') }}" class="bg-warm-cream text-near-black border border-stone font-semibold px-5 py-3 rounded-[26px] hover:bg-stone/20 transition duration-150 text-sm">
            ⬅️ Kembali
        </a>
    </div>

    <div class="bg-white p-8 rounded-[26px] border border-stone space-y-6">
        <div>
            <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block">Paket Wisata</span>
            <h2 class="text-2xl font-bold text-near-black mt-1">{{ $jadwal->paketWisata->nama_paket ?? '-' }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-stone/50 py-6">
            <div>
                <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block">Trip Leader Ditugaskan</span>
                <p class="text-lg font-bold text-near-black mt-1">{{ $jadwal->tripLeader->nama_leader ?? '-' }}</p>
                <p class="text-xs text-graphite mt-0.5">{{ $jadwal->tripLeader->email ?? '' }}</p>
            </div>
            <div>
                <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block">Status Perjalanan</span>
                <p class="text-lg font-bold text-electric-lime mt-1">{{ $jadwal->status_trip }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-b border-stone/50 py-6">
            <div>
                <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block">Tanggal Mulai</span>
                <p class="text-base font-semibold text-near-black mt-1">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d F Y') }}</p>
            </div>
            <div>
                <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block">Tanggal Selesai</span>
                <p class="text-base font-semibold text-near-black mt-1">{{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d F Y') }}</p>
            </div>
            <div>
                <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block">Kuota Tersedia</span>
                <p class="text-base font-bold text-electric-lime mt-1">{{ $jadwal->sisa_kuota }} / {{ $jadwal->kuota }} Pax</p>
            </div>
        </div>
    </div>
</div>
@endsection
