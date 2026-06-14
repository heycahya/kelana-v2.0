@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="border-b border-[#dfdfd6] pb-6">
        <h1 class="text-3xl font-bold tracking-tight text-near-black">Master Paket Wisata</h1>
        <p class="text-graphite text-sm mt-1">Kelola katalog dan rute perjalanan eksklusif Kelana.</p>
    </div>

    <!-- Table Container -->
    <div class="bg-white p-8 rounded-[26px] border border-stone">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-near-black text-white">
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider rounded-l-[16px]">ID</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Nama Paket</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Rute</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Fasilitas</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Harga</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider rounded-r-[16px] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone/50 font-medium text-sm text-near-black">
                    @forelse($pakets as $paket)
                        <tr class="hover:bg-warm-cream/35 transition duration-150">
                            <td class="p-5 font-semibold text-stone">#{{ $paket->id_paket }}</td>
                            <td class="p-5 max-w-sm">
                                <span class="font-bold text-near-black block text-base">{{ $paket->nama_paket }}</span>
                                <span class="text-xs text-graphite font-normal block truncate mt-1" title="{{ $paket->deskripsi }}">{{ $paket->deskripsi }}</span>
                            </td>
                            <td class="p-5 text-graphite">{{ $paket->rute }}</td>
                            <td class="p-5 text-graphite text-xs max-w-xs truncate" title="{{ $paket->fasilitas }}">{{ $paket->fasilitas }}</td>
                            <td class="p-5 font-bold text-electric-lime text-base">Rp {{ number_format($paket->harga, 0, ',', '.') }}</td>
                            <td class="p-5 text-right space-x-2">
                                <a href="{{ route('admin.paket.edit', $paket->id_paket) }}" class="inline-block bg-warm-cream text-near-black border border-stone font-semibold px-4 py-2 rounded-full hover:bg-stone/20 text-xs transition duration-150">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.paket.destroy', $paket->id_paket) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?')">
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
                            <td colspan="6" class="p-10 text-center text-stone-400 font-semibold">Belum ada paket wisata terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $pakets->links() }}
        </div>
    </div>
</div>
@endsection
