@extends('layouts.leader')

@section('content')
<style>
    /* Styling overrides for HTML5 QR Code Scanner to look ultra-premium and fit sidebar panel */
    #reader {
        border: none !important;
        background-color: #0f1a15 !important;
        box-shadow: none !important;
    }
    #reader video {
        border-radius: 20px !important;
        object-fit: cover !important;
    }
    #reader img {
        display: none !important;
    }
    #reader__dashboard_section_csr button, 
    #reader__camera_permission_button {
        background-color: #1e5e3a !important;
        color: white !important;
        font-family: 'Figtree', sans-serif !important;
        font-weight: 700 !important;
        border-radius: 9999px !important;
        padding: 10px 20px !important;
        font-size: 12px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        border: none !important;
        margin: 15px auto !important;
        cursor: pointer !important;
        display: block !important;
        transition: all 0.15s ease !important;
    }
    #reader__dashboard_section_csr button:active {
        transform: scale(0.95) !important;
    }
    #reader__dashboard_section_csr select {
        background-color: #f4f3ed !important;
        border: 1px solid #dfdfd6 !important;
        border-radius: 12px !important;
        padding: 8px 12px !important;
        font-weight: bold !important;
        color: #0f1a15 !important;
        font-family: 'Figtree', sans-serif !important;
        margin: 10px auto !important;
        outline: none !important;
    }
    #reader__scan_region {
        background-color: #0f1a15 !important;
    }
</style>

<div class="space-y-8">
    <!-- Header Trip Details Card -->
    <div class="bg-white p-8 rounded-[26px] border border-stone">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <a href="{{ route('leader.dashboard') }}" class="text-electric-lime font-bold text-xs flex items-center mb-3">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Kembali Ke Dashboard
                </a>
                <h1 class="text-3xl font-bold text-near-black leading-tight">{{ $jadwal->paketWisata->nama_paket ?? 'Trip' }}</h1>
                <p class="text-graphite font-semibold text-sm mt-1 flex items-center">
                    <span class="mr-2">📅 Keberangkatan:</span> {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d F Y') }}
                </p>
            </div>
            
            <div class="bg-warm-cream border border-stone rounded-[20px] p-5 flex flex-col justify-center self-stretch md:self-auto min-w-[220px]">
                <span class="text-[10px] font-bold text-graphite uppercase tracking-widest block mb-1">Status Kehadiran Manifes</span>
                <div class="flex justify-between items-baseline">
                    <span class="text-2xl font-bold text-electric-lime">
                        {{ $jadwal->pemesanan->where('status_pembayaran', 'PAID')->sum('jumlah_hadir') }} 
                        <span class="text-graphite text-xs font-semibold">/ {{ $jadwal->pemesanan->where('status_pembayaran', 'PAID')->sum('jumlah_peserta') }} Pax</span>
                    </span>
                    @php
                        $totalPax = $jadwal->pemesanan->where('status_pembayaran', 'PAID')->sum('jumlah_peserta');
                        $hadirPax = $jadwal->pemesanan->where('status_pembayaran', 'PAID')->sum('jumlah_hadir');
                        $percent = $totalPax > 0 ? round(($hadirPax / $totalPax) * 100) : 0;
                    @endphp
                    <span class="text-xs font-bold text-electric-lime">{{ $percent }}%</span>
                </div>
                <!-- Progress bar -->
                <div class="w-full bg-stone h-2 rounded-full mt-2 overflow-hidden border border-stone-200">
                    <div class="bg-electric-lime h-full" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Workspace Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Side: Manifest Table List (8 Cols) -->
        <div class="lg:col-span-8 bg-white p-8 rounded-[26px] border border-stone">
            <h3 class="text-xl font-bold text-near-black mb-6">Daftar Manifes Kehadiran</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-near-black text-white">
                            <th class="p-5 font-semibold text-xs uppercase tracking-wider rounded-l-[16px]">Pelanggan</th>
                            <th class="p-5 font-semibold text-xs uppercase tracking-wider">Kode Booking</th>
                            <th class="p-5 font-semibold text-xs uppercase tracking-wider">Kontak</th>
                            <th class="p-5 font-semibold text-xs uppercase tracking-wider">Pax Rombongan</th>
                            <th class="p-5 font-semibold text-xs uppercase tracking-wider rounded-r-[16px] text-center">Status Presensi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone/50 font-medium text-sm text-near-black">
                        @forelse($jadwal->pemesanan->where('status_pembayaran', 'PAID') as $pesanan)
                            <tr class="hover:bg-warm-cream/35 transition duration-150">
                                <td class="p-5">
                                    <span class="font-bold text-near-black block">{{ $pesanan->customer->nama_customer ?? 'Anonim' }}</span>
                                    <span class="text-xs text-graphite font-normal block">Pembeli Tiket</span>
                                </td>
                                <td class="p-5">
                                    <span class="font-mono bg-warm-cream border border-stone px-2.5 py-1 rounded-lg text-xs text-near-black">
                                        {{ $pesanan->booking_code }}
                                    </span>
                                </td>
                                <td class="p-5">
                                    <span class="block text-xs text-near-black">{{ $pesanan->customer->email ?? '-' }}</span>
                                    <span class="block text-[10px] text-graphite mt-0.5">{{ $pesanan->customer->no_telp ?? '-' }}</span>
                                </td>
                                <td class="p-5 text-center font-bold text-near-black text-base">
                                    {{ $pesanan->jumlah_peserta }} Pax
                                </td>
                                <td class="p-5 text-center">
                                    @if($pesanan->attendance_status === 'hadir')
                                        <span class="inline-block bg-green-50 text-green-700 border border-green-200 px-3 py-1 rounded-full text-xs font-semibold">HADIR ({{ $pesanan->jumlah_hadir }}/{{ $pesanan->jumlah_peserta }})</span>
                                    @else
                                        <span class="inline-block bg-yellow-50 text-yellow-700 border border-yellow-200 px-3 py-1 rounded-full text-xs font-semibold">MENUNGGU</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-stone-400 font-semibold">Belum ada manifes terdaftar yang lunas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Side: Sticky Camera Scanner & Manual Action Widget (4 Cols) -->
        <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-28">
            <div class="bg-white p-6 rounded-[24px] border border-[#dfdfd6]">
                <h3 class="font-bold text-lg mb-4 text-near-black text-center tracking-tight">Kamera Scanner QR</h3>
                <div id="reader" class="w-full rounded-[24px] overflow-hidden border border-[#dfdfd6] bg-black min-h-[250px] relative"></div>
                
                <form id="checkInForm" action="{{ route('leader.manifest.checkIn') }}" method="POST" class="mt-6">
                    @csrf
                    <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">
                    <input type="hidden" name="kode_booking" id="qr_result">
                    
                    <p class="font-semibold text-graphite mb-2 text-center text-xs">Atau input kode booking manual:</p>
                    <input type="text" name="kode_booking" placeholder="Ketik TRIP-YYYYMMDD-XXXX" class="w-full p-4 text-center text-xs font-bold uppercase rounded-[24px] bg-warm-cream/40 border border-[#dfdfd6] focus:border-[#1e5e3a] focus:ring-0 mb-4 transition">
                    
                    <button type="submit" class="w-full bg-[#1e5e3a] hover:bg-near-black text-white font-bold text-xs py-4 rounded-full transition duration-150 uppercase tracking-wider">
                        Check-In Manual
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Logic Injeksi Kamera Html5-Qrcode -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function onScanSuccess(decodedText, decodedResult) {
            // Hentikan pemindaian agar tidak ter-submit dua kali
            html5QrcodeScanner.clear();
            // Isi input tersembunyi
            document.getElementById('qr_result').value = decodedText;
            // Submit form secara paksa ke backend
            document.getElementById('checkInForm').submit();
        }
        function onScanFailure(error) { /* Ignore background scanning errors */ }
        
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 }, false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    });
</script>
@endsection
