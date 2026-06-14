@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-center border-b border-stone pb-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-near-black">Edit Paket Wisata</h1>
            <p class="text-graphite text-sm mt-1">Perbarui data paket #{{ $paket->id_paket }} - {{ $paket->nama_paket }}.</p>
        </div>
        <a href="{{ route('admin.paket.index') }}" class="bg-warm-cream text-near-black border border-stone font-semibold px-5 py-3 rounded-[26px] hover:bg-stone/20 transition duration-150 text-sm">
            ⬅️ Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white p-10 rounded-[26px] border border-stone">
        <form action="{{ route('admin.paket.update', $paket->id_paket) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Paket -->
                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-sm">Nama Paket Wisata</label>
                    <input type="text" name="nama_paket" value="{{ old('nama_paket', $paket->nama_paket) }}" class="w-full p-4 rounded-[26px] bg-warm-cream/40 border border-stone focus:border-electric-lime focus:ring-1 focus:ring-electric-lime focus:bg-white transition duration-150 text-near-black font-semibold text-sm">
                    @error('nama_paket')
                        <span class="text-red-600 font-bold ml-2 mt-2 inline-block text-xs">! {{ $message }}</span>
                    @enderror
                </div>

                <!-- Harga -->
                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-sm">Harga Paket (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga', intval($paket->harga)) }}" class="w-full p-4 rounded-[26px] bg-warm-cream/40 border border-stone focus:border-electric-lime focus:ring-1 focus:ring-electric-lime focus:bg-white transition duration-150 text-near-black font-semibold text-sm">
                    @error('harga')
                        <span class="text-red-600 font-bold ml-2 mt-2 inline-block text-xs">! {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Rute -->
                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-sm">Rute</label>
                    <input type="text" name="rute" value="{{ old('rute', $paket->rute) }}" class="w-full p-4 rounded-[26px] bg-warm-cream/40 border border-stone focus:border-electric-lime focus:ring-1 focus:ring-electric-lime focus:bg-white transition duration-150 text-near-black font-semibold text-sm">
                    @error('rute')
                        <span class="text-red-600 font-bold ml-2 mt-2 inline-block text-xs">! {{ $message }}</span>
                    @enderror
                </div>

                <!-- Fasilitas -->
                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-sm">Fasilitas</label>
                    <input type="text" name="fasilitas" value="{{ old('fasilitas', $paket->fasilitas) }}" class="w-full p-4 rounded-[26px] bg-warm-cream/40 border border-stone focus:border-electric-lime focus:ring-1 focus:ring-electric-lime focus:bg-white transition duration-150 text-near-black font-semibold text-sm">
                    @error('fasilitas')
                        <span class="text-red-600 font-bold ml-2 mt-2 inline-block text-xs">! {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Latitude -->
                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-sm">Latitude Map (Opsional)</label>
                    <input type="text" name="latitude" value="{{ old('latitude', $paket->latitude) }}" class="w-full p-4 rounded-[26px] bg-warm-cream/40 border border-stone focus:border-electric-lime focus:ring-1 focus:ring-electric-lime focus:bg-white transition duration-150 text-near-black font-semibold text-sm">
                    @error('latitude')
                        <span class="text-red-600 font-bold ml-2 mt-2 inline-block text-xs">! {{ $message }}</span>
                    @enderror
                </div>

                <!-- Longitude -->
                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-sm">Longitude Map (Opsional)</label>
                    <input type="text" name="longitude" value="{{ old('longitude', $paket->longitude) }}" class="w-full p-4 rounded-[26px] bg-warm-cream/40 border border-stone focus:border-electric-lime focus:ring-1 focus:ring-electric-lime focus:bg-white transition duration-150 text-near-black font-semibold text-sm">
                    @error('longitude')
                        <span class="text-red-600 font-bold ml-2 mt-2 inline-block text-xs">! {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="flex flex-col">
                <label class="font-semibold text-near-black mb-2 text-sm">Deskripsi Paket Wisata</label>
                <textarea name="deskripsi" rows="6" class="w-full p-5 rounded-[26px] bg-warm-cream/40 border border-stone focus:border-electric-lime focus:ring-1 focus:ring-electric-lime focus:bg-white transition duration-150 text-near-black font-semibold text-sm">{{ old('deskripsi', $paket->deskripsi) }}</textarea>
                @error('deskripsi')
                    <span class="text-red-600 font-bold ml-2 mt-2 inline-block text-xs">! {{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-electric-lime text-white font-bold text-base py-4 rounded-[26px] hover:bg-electric-lime/90 transition duration-150 transform active:scale-95">
                    Perbarui Paket Wisata
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
