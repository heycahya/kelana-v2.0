@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center border-b border-stone pb-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-near-black">Detail Paket Wisata</h1>
            <p class="text-graphite text-sm mt-1">Data lengkap untuk paket #{{ $paket->id_paket }}.</p>
        </div>
        <a href="{{ route('admin.paket.index') }}" class="bg-warm-cream text-near-black border border-stone font-semibold px-5 py-3 rounded-[26px] hover:bg-stone/20 transition duration-150 text-sm">
            ⬅️ Kembali
        </a>
    </div>

    <div class="bg-white p-8 rounded-[26px] border border-stone space-y-6">
        <div>
            <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block">Nama Paket</span>
            <h2 class="text-2xl font-bold text-near-black mt-1">{{ $paket->nama_paket }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-b border-stone/50 py-6">
            <div>
                <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block">Harga</span>
                <p class="text-lg font-bold text-electric-lime mt-1">Rp {{ number_format($paket->harga, 0, ',', '.') }}</p>
            </div>
            <div>
                <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block">Rute</span>
                <p class="text-lg font-bold text-near-black mt-1">{{ $paket->rute }}</p>
            </div>
        </div>

        <div>
            <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block">Fasilitas</span>
            <p class="text-near-black font-semibold text-sm mt-1">{{ $paket->fasilitas }}</p>
        </div>

        <div>
            <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block">Deskripsi</span>
            <p class="text-near-black font-semibold text-sm whitespace-pre-line leading-relaxed mt-1">{{ $paket->deskripsi }}</p>
        </div>

        @if($paket->latitude && $paket->longitude)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-stone/50 pt-6">
                <div>
                    <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block">Latitude</span>
                    <p class="text-near-black font-mono text-xs mt-1">{{ $paket->latitude }}</p>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block">Longitude</span>
                    <p class="text-near-black font-mono text-xs mt-1">{{ $paket->longitude }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
