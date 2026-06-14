@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-center border-b border-stone pb-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-near-black">Edit Jadwal & Penugasan</h1>
            <p class="text-graphite text-sm mt-1">Perbarui jadwal trip #{{ $jadwal->id_jadwal }} dan penugasan Trip Leader.</p>
        </div>
        <a href="{{ route('admin.jadwal.index') }}" class="bg-warm-cream text-near-black border border-stone font-semibold px-5 py-3 rounded-[26px] hover:bg-stone/20 transition duration-150 text-sm">
            ⬅️ Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white p-10 rounded-[26px] border border-stone">
        <form action="{{ route('admin.jadwal.update', $jadwal->id_jadwal) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Paket Wisata -->
                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-sm">Paket Wisata</label>
                    <select name="id_paket" class="w-full p-4 rounded-[26px] bg-warm-cream/40 border border-stone focus:border-electric-lime focus:ring-1 focus:ring-electric-lime focus:bg-white transition duration-150 text-near-black font-semibold text-sm">
                        @foreach($pakets as $paket)
                            <option value="{{ $paket->id_paket }}" {{ old('id_paket', $jadwal->id_paket) == $paket->id_paket ? 'selected' : '' }}>
                                {{ $paket->nama_paket }} (Rp {{ number_format($paket->harga, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_paket')
                        <span class="text-red-600 font-bold ml-2 mt-2 inline-block text-xs">! {{ $message }}</span>
                    @enderror
                </div>

                <!-- Trip Leader -->
                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-sm">Trip Leader Ditugaskan</label>
                    <select name="id_leader" class="w-full p-4 rounded-[26px] bg-warm-cream/40 border border-stone focus:border-electric-lime focus:ring-1 focus:ring-electric-lime focus:bg-white transition duration-150 text-near-black font-semibold text-sm">
                        @foreach($leaders as $leader)
                            <option value="{{ $leader->id_leader }}" {{ old('id_leader', $jadwal->id_leader) == $leader->id_leader ? 'selected' : '' }}>
                                {{ $leader->nama_leader }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_leader')
                        <span class="text-red-600 font-bold ml-2 mt-2 inline-block text-xs">! {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal Mulai -->
                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-sm">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $jadwal->tanggal_mulai) }}" class="w-full p-4 rounded-[26px] bg-warm-cream/40 border border-stone focus:border-electric-lime focus:ring-1 focus:ring-electric-lime focus:bg-white transition duration-150 text-near-black font-semibold text-sm">
                    @error('tanggal_mulai')
                        <span class="text-red-600 font-bold ml-2 mt-2 inline-block text-xs">! {{ $message }}</span>
                    @enderror
                </div>

                <!-- Tanggal Selesai -->
                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-sm">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $jadwal->tanggal_selesai) }}" class="w-full p-4 rounded-[26px] bg-warm-cream/40 border border-stone focus:border-electric-lime focus:ring-1 focus:ring-electric-lime focus:bg-white transition duration-150 text-near-black font-semibold text-sm">
                    @error('tanggal_selesai')
                        <span class="text-red-600 font-bold ml-2 mt-2 inline-block text-xs">! {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kuota -->
                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-sm">Kuota Peserta (Pax)</label>
                    <input type="number" name="kuota" value="{{ old('kuota', $jadwal->kuota) }}" class="w-full p-4 rounded-[26px] bg-warm-cream/40 border border-stone focus:border-electric-lime focus:ring-1 focus:ring-electric-lime focus:bg-white transition duration-150 text-near-black font-semibold text-sm">
                    @error('kuota')
                        <span class="text-red-600 font-bold ml-2 mt-2 inline-block text-xs">! {{ $message }}</span>
                    @enderror
                </div>

                <!-- Status Trip -->
                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-sm">Status Trip</label>
                    <select name="status_trip" class="w-full p-4 rounded-[26px] bg-warm-cream/40 border border-stone focus:border-electric-lime focus:ring-1 focus:ring-electric-lime focus:bg-white transition duration-150 text-near-black font-semibold text-sm">
                        <option value="Draft" {{ old('status_trip', $jadwal->status_trip) == 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Open" {{ old('status_trip', $jadwal->status_trip) == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Berjalan" {{ old('status_trip', $jadwal->status_trip) == 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="Selesai" {{ old('status_trip', $jadwal->status_trip) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Batal" {{ old('status_trip', $jadwal->status_trip) == 'Batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                    @error('status_trip')
                        <span class="text-red-600 font-bold ml-2 mt-2 inline-block text-xs">! {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-electric-lime text-white font-bold text-base py-4 rounded-[26px] hover:bg-electric-lime/90 transition duration-150 transform active:scale-95">
                    Perbarui Jadwal & Penugasan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
