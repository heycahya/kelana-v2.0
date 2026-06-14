@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="border-b border-[#dfdfd6] pb-6">
        <h1 class="text-3xl font-bold tracking-tight text-near-black">Customer Management</h1>
        <p class="text-graphite text-sm mt-1">Kelola data pelanggan terdaftar dan riwayat pembelian tiket Kelana.</p>
    </div>

    <!-- Table Container -->
    <div class="bg-white p-8 rounded-[24px] border border-[#dfdfd6]">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-near-black text-white">
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider rounded-l-[16px]">Customer</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Kontak & Detail</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Lokasi / Alamat</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider text-center">Total Bookings</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider text-right rounded-r-[16px]">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone/50 font-medium text-sm text-near-black">
                    @forelse($customers as $c)
                        <tr class="hover:bg-warm-cream/35 transition duration-150">
                            <td class="p-5">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-10 h-10 rounded-full bg-[#f4f3ed] border border-[#dfdfd6] flex items-center justify-center font-bold text-near-black uppercase text-sm">
                                        {{ substr($c->nama_customer, 0, 2) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-near-black text-base block">{{ $c->nama_customer }}</span>
                                        <span class="text-xs text-stone-500 font-normal">Registered User</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5">
                                <span class="block text-near-black font-semibold">{{ $c->email }}</span>
                                <span class="block text-xs text-graphite font-normal mt-0.5">{{ $c->no_telp ?? '-' }}</span>
                            </td>
                            <td class="p-5">
                                <span class="text-graphite font-normal block max-w-xs truncate" title="{{ $c->alamat ?? '-' }}">{{ $c->alamat ?? '-' }}</span>
                            </td>
                            <td class="p-5 text-center">
                                <span class="inline-block bg-[#f4f3ed] border border-[#dfdfd6] px-3.5 py-1 rounded-full text-xs font-bold text-near-black">
                                    {{ $c->bookings_count }} Bookings
                                </span>
                            </td>
                            <td class="p-5 text-right font-bold text-[#1e5e3a] text-base">
                                Rp {{ number_format($c->total_spent, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-stone-400 font-semibold">Belum ada customer terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
