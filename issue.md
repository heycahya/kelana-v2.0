# MEGA PROMPT ULTIMATE: Eksekusi Tuntas Admin & Leader Dashboard

Ini adalah spesifikasi teknis tingkat tertinggi. Anda sebagai AI Coder **WAJIB** membaca dan mengeksekusi ini secara brutal dan presisi tanpa melewatkan satupun logika atau variabel. Waktu kita sangat sempit, sehingga prompt ini sudah menyediakan hampir 80% dari struktur kodenya!

**⚠️ UI/UX MASTER RULES (WAJIB DIIKUTI TANPA PENGECUALIAN):**
- Warna Utama: `Forest Green` (`#1e5e3a`), Background Content `Warm Cream` (`#dfdfd6`), Sidebar/Nav `Midnight Forest` (`#0f1a15`), Teks Terang `#f4f3ed`.
- Semua panel, tombol, tabel, dan input *form* WAJIB menggunakan sudut lengkung `rounded-[26px]`.
- DILARANG MENGGUNAKAN `box-shadow` atau bayangan apapun. Semuanya 100% Flat Design.
- Anda wajib mengaktifkan interaksi SSR (Server Side Rendering) penuh via Laravel Blade Controller. Dilarang menggunakan React/Vue/Livewire di bagian ini. Gunakan murni Alpine.js untuk state reaktif kecil (alert/modal).

---

## ⚙️ FASE 1: KONFIGURASI ROUTING (`routes/web.php`)

Ganti atau tambahkan blok routing ini dengan presisi absolut:

```php
// --- ADMIN BACK-OFFICE ROUTES ---
Route::middleware(['auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminWeb\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('paket', \App\Http\Controllers\AdminWeb\PaketWebController::class);
    Route::resource('jadwal', \App\Http\Controllers\AdminWeb\JadwalWebController::class);
    Route::get('/laporan', [\App\Http\Controllers\AdminWeb\LaporanWebController::class, 'index'])->name('laporan.index');
});

// --- TRIP LEADER FIELD APP ROUTES ---
Route::middleware(['auth:trip_leader', 'trip_leader'])->prefix('trip-leader')->name('leader.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\TripLeader\LeaderDashboardController::class, 'index'])->name('dashboard');
    Route::get('/manifest/{id_jadwal}', [\App\Http\Controllers\TripLeader\ManifestWebController::class, 'show'])->name('manifest.show');
    Route::post('/manifest/check-in', [\App\Http\Controllers\TripLeader\ManifestWebController::class, 'checkIn'])->name('manifest.checkIn');
});
```

---

## 🏢 FASE 2: IMPLEMENTASI ADMIN ERP (BACK-OFFICE)

### A. Layout Utama (`resources/views/layouts/admin.blade.php`)
Tuliskan struktur file ini secara murni:
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Admin - Kelana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#dfdfd6] text-[#0f1a15] font-sans flex min-h-screen">
    
    <!-- SIDEBAR KIRI -->
    <aside class="w-72 bg-[#0f1a15] text-[#f4f3ed] fixed h-full p-8 flex flex-col justify-between z-50">
        <div>
            <h1 class="text-4xl font-extrabold mb-12 tracking-tight text-[#1e5e3a]">Kelana<span class="text-white">ERP</span></h1>
            <nav class="space-y-4">
                <!-- Tambahkan Request::routeIs() untuk active state -->
                <a href="{{ route('admin.dashboard') }}" class="block p-4 rounded-[26px] transition font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-[#1e5e3a]' : 'hover:bg-[#1e5e3a]/50' }}">📊 Dashboard Utama</a>
                <a href="{{ route('admin.paket.index') }}" class="block p-4 rounded-[26px] transition font-semibold {{ request()->routeIs('admin.paket.*') ? 'bg-[#1e5e3a]' : 'hover:bg-[#1e5e3a]/50' }}">📦 Master Paket Wisata</a>
                <a href="{{ route('admin.jadwal.index') }}" class="block p-4 rounded-[26px] transition font-semibold {{ request()->routeIs('admin.jadwal.*') ? 'bg-[#1e5e3a]' : 'hover:bg-[#1e5e3a]/50' }}">🗓️ Jadwal & Penugasan</a>
                <a href="{{ route('admin.laporan.index') }}" class="block p-4 rounded-[26px] transition font-semibold {{ request()->routeIs('admin.laporan.*') ? 'bg-[#1e5e3a]' : 'hover:bg-[#1e5e3a]/50' }}">📄 Laporan Finansial</a>
            </nav>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-8">
            @csrf
            <button type="submit" class="w-full text-center p-4 rounded-[26px] bg-red-600/20 text-red-400 font-bold hover:bg-red-600 hover:text-white transition">🚪 Logout System</button>
        </form>
    </aside>

    <!-- KONTEN KANAN -->
    <main class="flex-1 ml-72 p-10 relative">
        <!-- GLOBAL ALERT COMPONENT (ALPINE.JS) -->
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)" class="bg-[#1e5e3a] text-white p-6 rounded-[26px] mb-8 flex justify-between items-center">
                <p class="font-bold">✅ {{ session('success') }}</p>
                <button @click="show = false" class="text-white font-bold px-4 py-2 bg-black/20 rounded-full">Tutup</button>
            </div>
        @endif
        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition class="bg-red-600 text-white p-6 rounded-[26px] mb-8 flex justify-between items-center">
                <p class="font-bold">❌ {{ session('error') }}</p>
                <button @click="show = false" class="text-white font-bold px-4 py-2 bg-black/20 rounded-full">Tutup</button>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
```

### B. Arsitektur Controller Admin (Logic Brutal)
Anda harus membuat controller web di `app/Http/Controllers/AdminWeb/`. Berikut logika inti metode-metodenya:

1. **`AdminDashboardController@index`**:
   Lakukan query efisien. Hitung uang masuk, tiket terjual, dan status trip.
   ```php
   $revenue = \App\Models\Pemesanan::where('status_pembayaran', 'PAID')->sum('gross_amount');
   $pax = \App\Models\Pemesanan::where('status_pembayaran', 'PAID')->sum('jumlah_hadir');
   $recent_bookings = \App\Models\Pemesanan::with(['customer', 'jadwalTrip.paketWisata'])->orderBy('created_at', 'desc')->take(5)->get();
   return view('admin.dashboard', compact('revenue', 'pax', 'recent_bookings'));
   ```

2. **`PaketWebController` (Master Paket)**:
   - `index()`: `$pakets = PaketWisata::orderBy('created_at', 'desc')->paginate(10); return view('admin.paket.index', compact('pakets'));`
   - `create()`: Cukup `return view('admin.paket.create');`
   - `store(Request $request)`:
     ```php
     $validated = $request->validate([
         'nama_paket' => 'required|string|max:255',
         'destinasi' => 'required|string|max:255',
         'deskripsi' => 'required|string',
         'harga' => 'required|numeric|min:0',
         // Tambahkan field lain sesuai migration
     ]);
     PaketWisata::create($validated);
     return redirect()->route('admin.paket.index')->with('success', 'Paket Wisata berhasil dibuat!');
     ```

3. **`JadwalWebController` (Master Jadwal & Penugasan)**:
   - `index()`: `$jadwals = JadwalTrip::with(['paketWisata', 'tripLeader'])->orderBy('tanggal_mulai', 'desc')->paginate(10);`
   - `create()`: 
     **KRITIKAL**: Anda WAJIB mengirim data master ke Form!
     `$pakets = PaketWisata::all(); $leaders = TripLeader::all(); return view('admin.jadwal.create', compact('pakets', 'leaders'));`
   - `store(Request $request)`:
     ```php
     $validated = $request->validate([
         'id_paket' => 'required|exists:paket_wisata,id_paket',
         'id_trip_leader' => 'required|exists:trip_leaders,id_trip_leader',
         'tanggal_mulai' => 'required|date',
         'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
         'kuota_peserta' => 'required|integer|min:1'
     ]);
     // Otomatis inject sisa kuota = kuota_peserta saat pembuatan baru
     $validated['sisa_kuota'] = $validated['kuota_peserta']; 
     JadwalTrip::create($validated);
     return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dan Penugasan Leader berhasil dibuat!');
     ```

### C. Desain View Admin (Struktur Form & Tabel)
- **Tabel Data (`index.blade.php`)**: Gunakan `<table class="w-full text-left bg-white rounded-[26px]">`. Baris Thead harus `bg-[#0f1a15] text-[#f4f3ed]`. Baris Td dipisahkan dengan `border-b border-stone-200`. Beri padding `p-6` pada Th dan Td.
- **Form Input (`create/edit.blade.php`)**:
  - Gunakan `grid grid-cols-2 gap-8` untuk menjejerkan input secara desktop.
  - Setiap input text/select: `<input class="w-full p-4 rounded-[26px] bg-stone-100 border-0 focus:ring-4 focus:ring-[#1e5e3a] focus:bg-white transition">`.
  - Wajib letakkan error span: `@error('field_name') <span class="text-red-500 font-bold ml-2 mt-2 inline-block">! {{ $message }}</span> @enderror`.

---

## 📱 FASE 3: TRIP LEADER FIELD APP (MOBILE DASHBOARD)

### A. Layout Mobile (`resources/views/layouts/leader.blade.php`)
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-[#0f1a15]">
    <!-- Container peniru layar HP (Responsive constraints) -->
    <div class="max-w-md mx-auto min-h-screen bg-[#f4f3ed] relative shadow-2xl overflow-x-hidden">
        
        <!-- Header -->
        <header class="bg-[#0f1a15] text-[#f4f3ed] p-6 sticky top-0 z-50 rounded-b-[26px] shadow-lg">
            <h1 class="text-2xl font-extrabold text-center">Field Ops <span class="text-[#1e5e3a]">Kelana</span></h1>
        </header>

        <!-- Flash Messages untuk Mobile -->
        @if (session('success'))
            <div class="m-4 bg-green-500 text-white p-4 rounded-[26px] font-bold text-center animate-bounce">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="m-4 bg-red-600 text-white p-4 rounded-[26px] font-bold text-center animate-pulse">{{ session('error') }}</div>
        @endif

        <main class="p-4 pb-32">
            @yield('content')
        </main>

        <!-- Bottom Tabs -->
        <nav class="fixed bottom-0 max-w-md w-full bg-white p-4 flex justify-between items-center rounded-t-[26px] shadow-[0_-10px_30px_rgba(0,0,0,0.05)] border-t border-stone-100">
            <a href="{{ route('leader.dashboard') }}" class="flex-1 text-center font-bold text-[#1e5e3a]">📋 Jadwal</a>
            <form method="POST" action="{{ route('logout') }}" class="flex-1 text-center">
                @csrf
                <button type="submit" class="font-bold text-red-500 w-full">Keluar</button>
            </form>
        </nav>
    </div>
</body>
</html>
```

### B. Arsitektur Controller Leader
Di `ManifestWebController.php`, pastikan fungsi `checkIn` kebal dari serangan (Robustness):
```php
public function checkIn(Request $request) {
    $request->validate(['kode_booking' => 'required|string']);
    $pesanan = \App\Models\Pemesanan::where('kode_booking', $request->kode_booking)->first();
    
    // Verifikasi eksistensi dan pembayaran
    if(!$pesanan) { return back()->with('error', '❌ Tiket tidak ditemukan di sistem!'); }
    if($pesanan->status_pembayaran !== 'PAID') { return back()->with('error', '❌ Tiket belum lunas!'); }
    
    // Verifikasi apakah tiket ini untuk jadwal yang dibuka
    // (Jika perlu tambahkan validasi $pesanan->id_jadwal == request('id_jadwal_terkait'))
    
    // Verifikasi apakah sudah check-in
    if($pesanan->attendance_status === 'hadir') { 
        return back()->with('error', '⚠️ Peserta ini SUDAH check-in sebelumnya!'); 
    }
    
    // Sukses: Ubah status ke hadir
    $pesanan->attendance_status = 'hadir';
    $pesanan->save();
    return back()->with('success', '✅ ' . $pesanan->customer->name . ' Berhasil Check-In!');
}
```

### C. Halaman SAKTI Scanner QR (`manifest.blade.php`)
Tuliskan blok *view* ini secara absolut untuk mengeksekusi integrasi kamera *smartphone*!
```html
@extends('layouts.leader')
@section('content')

<!-- Header Trip Info -->
<div class="bg-white p-6 rounded-[26px] mb-6 border border-stone-200">
    <h2 class="font-extrabold text-2xl text-[#0f1a15] mb-2">{{ $jadwal->paketWisata->nama_paket }}</h2>
    <p class="text-stone-500 font-bold">📅 {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}</p>
    <div class="mt-4 p-4 bg-stone-100 rounded-[16px] text-center font-bold text-lg text-[#1e5e3a]">
        Total Peserta: {{ $jadwal->pemesanan->sum('jumlah_hadir') }} Pax
    </div>
</div>

<!-- Kamera Scanner Widget -->
<div class="bg-white p-6 rounded-[26px] mb-6 border border-stone-200 text-center">
    <h3 class="font-bold text-xl mb-4 text-[#0f1a15]">Scan E-Ticket QR</h3>
    <div id="reader" class="w-full rounded-[26px] overflow-hidden border-4 border-[#1e5e3a] bg-black min-h-[250px]"></div>
    
    <form id="checkInForm" action="{{ route('leader.manifest.checkIn') }}" method="POST" class="mt-6">
        @csrf
        <input type="hidden" name="kode_booking" id="qr_result">
        <p class="font-bold text-stone-400 mb-2">Atau masukkan kode manual:</p>
        <input type="text" name="kode_booking" placeholder="Ketik KODE-XXX" class="w-full p-4 text-center text-lg font-bold uppercase rounded-[26px] bg-stone-100 border-0 focus:ring-4 focus:ring-[#1e5e3a] mb-4">
        <button type="submit" class="w-full bg-[#1e5e3a] text-white font-extrabold text-lg p-5 rounded-[26px]">Check-In Manual</button>
    </form>
</div>

<!-- List Manifes Peserta -->
<div class="space-y-4">
    <h3 class="font-bold text-2xl text-[#0f1a15] mb-4">Daftar Manifes</h3>
    @forelse($jadwal->pemesanan->where('status_pembayaran', 'PAID') as $pesanan)
    <div class="bg-white p-5 rounded-[26px] flex justify-between items-center border border-stone-200">
        <div>
            <p class="font-bold text-lg text-[#0f1a15]">{{ $pesanan->customer->name ?? 'Anonim' }}</p>
            <p class="text-sm font-bold text-stone-500">{{ $pesanan->kode_booking }}</p>
        </div>
        <div class="text-right">
            <p class="font-extrabold text-[#1e5e3a] mb-2">{{ $pesanan->jumlah_hadir }} Pax</p>
            @if($pesanan->attendance_status === 'hadir')
                <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-extrabold text-xs">HADIR</span>
            @else
                <span class="bg-stone-100 text-stone-500 px-4 py-2 rounded-full font-bold text-xs">MENUNGGU</span>
            @endif
        </div>
    </div>
    @empty
    <p class="text-center text-stone-500 font-bold p-6">Belum ada peserta yang lunas.</p>
    @endforelse
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
```

## 🚀 EKSEKUSI FINAL
Coder, jalankan cetak biru di atas Halaman-per-Halaman. Anda hanya perlu menyatukan kepingan-kepingan *puzzle* yang sudah dituliskan di atas. Lakukan kompilasi Tailwind (`npm run dev`) setelah seluruh views disimpan. Laporkan di STATE.md saat seluruh fungsionalitas ini sukses berjalan!

---

## 🎨 FASE 4: PENYELARASAN DESAIN ULTRA-PREMIUM (SIOHIOMA DASHBOARD ALIGNMENT)
Untuk memastikan kepuasan visual pengguna yang menginginkan tampilan yang bersih, premium, dan mirip dengan landing page serta mockup Siohioma:

### 1. LAYOUT ADMIN (`layouts/admin.blade.php`) & LEADER (`layouts/leader.blade.php`)
- **Logo Siohioma-Style**: Ganti inisial teks dengan ikon bintang/asterisk 8-arah menggunakan SVG murni (`stroke-[#1e5e3a]`) dan nama brand "Kelana" berwarna putih bersih.
- **Sidebar Navigasi**:
  - Ganti warna latar belakang sidebar menjadi `#0b1611` (Deep Midnight Forest).
  - Link menu harus flat tanpa background kecuali ada status aktif.
  - Active state: Sediakan indikator bar vertikal warna hijau (`#1e5e3a`) di ujung kiri item menu aktif. Teks menu aktif berwarna putih bersih, teks menu tidak aktif berwarna abu-abu redup (`#8e8e93`).
  - Tambahkan kategori label kecil "MENU" dan "GENERAL" dengan huruf kapital kecil berwarna redup (`#8e8e93`/40).
  - Tambahkan menu dummy/statik "Customers", "Messages" (dengan badge angka 13 berwarna hijau muda), "Settings", dan "Security" untuk kelengkapan visual mockup.
- **Top Header**:
  - Tempatkan label dropdown view di sisi kanan (sebelah kanan tombol kontekstual) berlabel "Sales Admin" dengan menu dropdown rata kanan (`right-0`).
  - Tempatkan search bar rounded capsule di bagian kiri dengan ikon pencarian.
  - Di kanan header, hilangkan tombol aksi ikonik/shortcut (Laporan Finansial & Master Paket Wisata) agar tidak redundan dengan menu sidebar. Cukup tampilkan tombol "+ Add new trip" / "+ Add new product" yang kontekstual.

### 2. VIEW ADMIN DASHBOARD (`admin/dashboard.blade.php`)
- **Flat Theme & Borders**: Hilangkan semua box-shadow. Gunakan murni border stone tipis (`border border-stone-200` atau `border-[#dfdfd6]`).
- **Update Banner**: Banner kiri atas berwarna hijau gelap `#0b1611` dengan indikator titik merah berdenyut (pulsing dot), info tanggal ("Feb 12th 2026"), teks promo "Sales revenue increased 40% in 1 week", dan tautan "See Statistics >".
- **Net Income & Return Cards**: Buat card flat putih bersih dengan header judul ("Net Income", "Total Return"), ikon tiga titik `•••` di kanan, angka nominal besar, dan teks persentase tren naik/turun di bagian bawah.
- **Daftar Transaksi**: Tampilkan list transaksi dengan ikon lingkaran abu-abu di kiri, nama pelanggan & tanggal pemesanan di tengah, serta status ("Completed" hijau / "Pending" hitam-abu) & kode booking di kanan.
- **Bagan Pendapatan (Revenue)**: Implementasikan grafik batang CSS vertikal yang menampilkan batang ganda (forest green dan lime green) bulat penuh (pill shape) berdampingan untuk setiap bulan.
- **Sales Report**: Tampilkan chart horizontal progress bar yang menampilkan bar bulat penuh warna forest green, lime green, dan electric lime sesuai target mockup.
- **Total View Performance & Promo Banner**:
  - Tampilkan donut chart SVG melingkar dengan teks tengah "Total Count / 565K".
  - Lengkapi tombol "Guide Views" tipis dan legenda warna di bawah donut chart.
  - Tampilkan promo banner bawah warna krem-kehijauan dengan watermark SVG bintang asterisk besar di pojok kanan bawah.

### 3. VIEW LEADER DASHBOARD (`leader/dashboard.blade.php`) & MANIFEST (`leader/manifest.blade.php`)
- Selaraskan desain dashboard Trip Ops dengan tema datar, warna hijau-krem yang bersih, border tipis, dan tombol ber-capsule rounded-full tanpa shadow.
- Manifest splitscreen desktop: Sisi kiri menampilkan tabel data manifes yang rapi dengan header hitam-hijau, sisi kanan menampilkan widget QR scanner dengan sudut lengkung `rounded-[20px]`.

---

## 🛠️ FASE 5: PEMBERSIHAN NAVBAR & LOGIKA KODE PROMO DATABASE-INTEGRATED

### 1. Pembersihan Menu "How It Works"
- Hapus tautan placeholder `"How It Works"` dari navigasi bar di file-file berikut agar tidak menjadi pajangan kosong:
  - `resources/views/welcome.blade.php` (line 38)
  - `resources/views/dashboard.blade.php` (line 62)
  - `resources/views/components/navbar.blade.php` (line 21)

### 2. Logika Kode Promo yang Terintegrasi Database
- **Skema Database**: Buat file migration untuk menambahkan kolom `promo_code` (string, 50, nullable) dan `diskon` (decimal, 10, 2, default 0) di tabel `pemesanan`.
- **Model `Pemesanan`**: Tambahkan field `promo_code` dan `diskon` ke dalam property `$fillable`.
- **Backend Booking Logic (`BookingWebController` & `PemesananController` API)**:
  - Validasi kode promo (`MERDEKA20`, `RINJANIPAS`, `KOMODOLUX`) dan hitung besar diskon (20% untuk MERDEKA20, 10% untuk RINJANIPAS, Rp 100.000 untuk KOMODOLUX).
  - Terapkan diskon pada base price (`jumlah_peserta * paket->harga`).
  - Simpan nilai `promo_code` dan `diskon` ke database saat record `Pemesanan` berhasil dibuat.
  - Kirim total harga bersih (`total_harga + total_biaya_addons`) ke Snap Midtrans sebagai `gross_amount` pembayaran.
- **Tampilan Booking History (`customer/bookings.blade.php`)**:
  - Tampilkan rincian diskon yang diterapkan jika ada.
  - Perbaiki bug kalkulasi Total Bayar agar menyertakan biaya add-ons dan memotong diskon (`total_harga + total_biaya_addons`).
- **Tampilan Daftar Transaksi (`admin/transaksi/index.blade.php`)**:
  - Tampilkan kode promo yang digunakan dan jumlah diskon yang diperoleh pelanggan.
- **E-Ticket PDF (`pdf/tiket-digital.blade.php`)**:
  - Tambahkan baris rincian pembayaran (Subtotal, Diskon Promo, Add-ons, dan Total Pembayaran) jika promo code digunakan, agar terkesan premium dan profesional.

---

## 💬 FASE 6: FITUR CHAT CUSTOMER SERVICE (DATABASE-INTEGRATED)

### 1. Skema Database & Model
- **Tabel `messages`**: Buat migration untuk tabel pesan dengan kolom `sender_type` (admin/customer/trip_leader), `sender_id`, `receiver_type` (admin/customer/trip_leader), `receiver_id`, `message` (text), dan `is_read` (boolean, default false).
- **Model `Message`**: Tambahkan properti `$fillable` dan buat relasi dinamis (`sender`, `receiver`) untuk menargetkan model `Customer`, `Admin`, atau `TripLeader` berdasarkan tipe guard terkait.

### 2. Antarmuka Pengguna (UI) & Logika Reaktif (Alpine.js)
- **Floating Chat Customer**:
  - Tampilkan tombol bubble chat melayang berwarna hijau di sudut kanan bawah semua halaman publik (diintegrasikan via `components/navbar` & `customer-wishlist-cart` drawer).
  - Dilengkapi dot indikator merah jika ada balasan admin yang belum dibaca.
  - Box chat memiliki header hitam premium (Siohioma Style), area stream balon percakapan, dan form input pesan yang secara real-time mengirim request POST dan melakukan polling pesan baru setiap 3 detik.
- **Inbox Dashboard Admin (`admin/messages/index.blade.php`)**:
  - Tampilkan antarmuka obrolan 2 panel yang mewah. Panel kiri berisi daftar kontak (semua customer & trip leader) diurutkan berdasarkan aktivitas pesan terbaru beserta badge unread count.
  - Panel kanan berisi thread pesan aktif dengan bubble chat Forest Green untuk Admin, dan putih-krem untuk incoming messages.
  - Terintegrasi dengan polling otomatis 3 detik dan load thread instan saat kontak diklik.
- **Chat Support Trip Leader (`leader/chat.blade.php`)**:
  - Sediakan menu **"Chat Ops Support"** di sidebar navigasi leader untuk koordinasi lapangan.
  - Tampilan chat satu kolom yang mobile-friendly bagi leader untuk melaporkan isu operasional atau berkoordinasi langsung dengan HQ Admin.

---

## 📊 FASE 7: DEDICATED TRIP LEADER STATISTICS SCREEN

### 1. Controller Method (`LeaderDashboardController.php`)
- Tambahkan metode `statistics()` yang menghitung performa tugas real-time leader:
  - Jumlah Trip keseluruhan (`totalTrips`)
  - Jumlah status trip breakdown (`Draft`, `Open`, `Berjalan`, `Selesai`)
  - Total Pax Lunas Dipandu (`totalPax`)
  - Total Pax yang Hadir (`attendedPax`)
  - Rasio Check-in (`checkInRate`)
- Kembalikan ke blade template `leader.statistics`.

### 2. Tampilan Blade View (`leader/statistics.blade.php`)
- Menggunakan layout `@extends('layouts.leader')`.
- Desain 100% Siohioma Flat:
  - Card statistik utama: Total Penugasan, Total Peserta Dipandu (Pax), Rasio Check-in (Progress bar Forest Green `#1e5e3a`).
  - Breakdown status trip dengan visual horizontal CSS bars yang bersih dan rounded pill.
  - Tabel Riwayat Kinerja Trip yang memuat daftar paket wisata, tanggal, status, jumlah peserta (Checked-in / Total PAID), dan rasio check-in masing-masing jadwal trip.

### 3. Tautan Sidebar Layout (`layouts/leader.blade.php`)
- Perbarui item menu **Statistics**:
  - `href` mengarah ke `{{ route('leader.statistics') }}`.
  - Tambahkan indikator active state vertical green bar (`#1e5e3a`) dan teks font-semibold ketika `request()->routeIs('leader.statistics')`.
  - Hindari tabrakan active menu dengan Jadwal Memandu.


