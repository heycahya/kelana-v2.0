@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="border-b border-[#dfdfd6] pb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-near-black">Transaksi Pemesanan</h1>
            <p class="text-graphite text-sm mt-1">Kelola transaksi booking customer, status pembayaran, dan penagihan.</p>
        </div>
        
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('admin.transaksi.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode / Customer..." class="px-5 py-2.5 rounded-full border border-[#dfdfd6] text-xs font-semibold bg-white placeholder-graphite/40 w-full sm:w-60 outline-none focus:border-near-black transition">
            <select name="status" class="px-5 py-2.5 rounded-full border border-[#dfdfd6] text-xs font-semibold bg-white outline-none focus:border-near-black transition">
                <option value="">Semua Status</option>
                <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>PENDING</option>
                <option value="PAID" {{ request('status') === 'PAID' ? 'selected' : '' }}>PAID</option>
                <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>FAILED</option>
                <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>CANCELLED</option>
            </select>
            <button type="submit" class="bg-near-black text-white px-6 py-2.5 rounded-full text-xs font-bold hover:bg-[#1e5e3a] transition duration-150">
                Filter
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.transaksi.index') }}" class="bg-stone/20 text-near-black px-6 py-2.5 rounded-full text-xs font-semibold hover:bg-stone/30 transition duration-150">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table Container -->
    <div class="bg-white p-8 rounded-[24px] border border-[#dfdfd6]">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-near-black text-white">
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider rounded-l-[16px]">Kode Booking</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Customer</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Trip Paket & Jadwal</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Total Harga</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider">Status Pembayaran</th>
                        <th class="p-5 font-semibold text-xs uppercase tracking-wider text-right rounded-r-[16px]">Aksi Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone/50 font-medium text-sm text-near-black">
                    @forelse($transaksis as $t)
                        <tr class="hover:bg-warm-cream/35 transition duration-150">
                            <td class="p-5">
                                <span class="font-bold text-near-black text-base block">{{ $t->booking_code }}</span>
                                <span class="text-xs text-stone-500 font-normal">Tgl: {{ \Carbon\Carbon::parse($t->tgl_pemesanan)->format('d M Y H:i') }}</span>
                            </td>
                            <td class="p-5">
                                <span class="font-bold text-near-black block">{{ $t->customer->nama_customer ?? '-' }}</span>
                                <span class="text-xs text-graphite font-normal">{{ $t->customer->email ?? '' }}</span>
                            </td>
                            <td class="p-5">
                                <span class="font-bold text-near-black block">{{ $t->jadwalTrip->paketWisata->nama_paket ?? '-' }}</span>
                                <span class="text-xs text-graphite font-semibold block mt-0.5">📅 {{ $t->jadwalTrip ? \Carbon\Carbon::parse($t->jadwalTrip->tanggal_mulai)->format('d M Y') : '-' }}</span>
                            </td>
                            <td class="p-5">
                                <span class="font-bold text-base block text-near-black">Rp {{ number_format($t->total_harga + ($t->total_biaya_addons ?? 0), 0, ',', '.') }}</span>
                                @if($t->promo_code)
                                    <span class="text-[10px] text-[#1e5e3a] font-bold block mt-0.5">Promo: {{ $t->promo_code }} (-Rp {{ number_format($t->diskon, 0, ',', '.') }})</span>
                                @endif
                                @if($t->total_biaya_addons > 0)
                                    <span class="text-[10px] text-stone-500 font-semibold block mt-0.5">Add-ons: +Rp {{ number_format($t->total_biaya_addons, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="p-5">
                                @if($t->status_pembayaran === 'PENDING')
                                    <span class="inline-block bg-yellow-50 text-yellow-700 border border-yellow-200 px-3 py-1 rounded-full text-xs font-bold">PENDING</span>
                                @elseif($t->status_pembayaran === 'PAID')
                                    <span class="inline-block bg-green-50 text-green-700 border border-green-200 px-3 py-1 rounded-full text-xs font-bold">PAID</span>
                                @elseif($t->status_pembayaran === 'FAILED')
                                    <span class="inline-block bg-red-50 text-red-700 border border-red-200 px-3 py-1 rounded-full text-xs font-bold">FAILED</span>
                                @else
                                    <span class="inline-block bg-red-50 text-red-700 border border-red-200 px-3 py-1 rounded-full text-xs font-bold">{{ $t->status_pembayaran }}</span>
                                @endif
                            </td>
                            <td class="p-5 text-right">
                                <form action="{{ route('admin.transaksi.updateStatus', $t->id_pemesanan) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    <select name="status_pembayaran" class="px-3 py-1.5 rounded-full border border-[#dfdfd6] text-xs font-semibold bg-white outline-none focus:border-near-black transition">
                                        <option value="PENDING" {{ $t->status_pembayaran === 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                        <option value="PAID" {{ $t->status_pembayaran === 'PAID' ? 'selected' : '' }}>PAID</option>
                                        <option value="FAILED" {{ $t->status_pembayaran === 'FAILED' ? 'selected' : '' }}>FAILED</option>
                                        <option value="CANCELLED" {{ $t->status_pembayaran === 'CANCELLED' ? 'selected' : '' }}>CANCELLED</option>
                                    </select>
                                    <button type="submit" class="bg-near-black text-white hover:bg-[#1e5e3a] text-xs font-bold px-4 py-1.5 rounded-full transition duration-150">
                                        Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-stone-400 font-semibold">Belum ada data transaksi booking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $transaksis->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
