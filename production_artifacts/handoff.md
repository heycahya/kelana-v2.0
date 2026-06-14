# 🤝 Dokumentasi Handoff & Progress Sistem - Kelana v2.0
**Fitur Utama:** Integrasi Pembayaran Midtrans, Sistem Chat CS Terintegrasi Database + Bot Otomatis, Logika Kode Promo, Dashboard Admin ERP, Lapangan Trip Leader (Mobile-First + Scanner QR), & Dedicated Statistics Screen.
**Status:** Selesai, Terintegrasi Penuh, & Siap Ditinjau (Ready for Review) ✅

---

## 📋 1. Ringkasan Pencapaian Sistem (Executive Summary)

Sistem Kelana v2.0 telah berevolusi dari cetak biru menjadi platform ERP (Enterprise Resource Planning) dan Operasional Lapangan pariwisata petualangan yang terintegrasi penuh. Semua komponen mockup statis telah dieliminasi dan digantikan oleh fungsionalitas reaktif yang terhubung langsung dengan database MySQL. Penyelarasan visual total telah disesuaikan agar mematuhi estetika **Siohioma Style** (desain datar/flat, tanpa bayangan/shadow, radius sudut membulat 26px (`rounded-[26px]` / `rounded-3xl`), font Figtree, dan palet warna Forest Green alamiah).

---

## 🛠️ 2. Arsitektur Teknis & Database Schema

### 2.1. Skema Database Tambahan & Modifikasi
Berikut adalah migrasi database utama yang membedakan Kelana v2.0 dari versi awal:

1.  **Tabel `pemesanan` (Modifikasi Kolom)**:
    *   `promo_code` (`VARCHAR(50)`, `nullable`): Menyimpan kode promo yang digunakan saat checkout (`MERDEKA20`, `RINJANIPAS`, `KOMODOLUX`).
    *   `diskon` (`DECIMAL(10,2)`, default `0.00`): Jumlah nominal pemotongan harga tiket yang berhasil diterapkan.
    *   `attendance_status` (`ENUM('belum_hadir', 'hadir', 'absen')`, default `'belum_hadir'`).
    *   `jumlah_hadir` (`INT`, default `0`): Jumlah fisik peserta rombongan yang berhasil check-in di lapangan.
    *   `total_biaya_addons` (`DECIMAL(10,2)`, default `0.00`): Total akumulasi biaya dari opsi perlengkapan tambahan yang dibeli.

2.  **Tabel `messages` (Baru - Sistem Chat CS)**:
    *   `id_message` (`BIGINT`, primary key, auto-increment)
    *   `sender_type` (`ENUM('admin', 'customer', 'trip_leader')`)
    *   `sender_id` (`INT`)
    *   `receiver_type` (`ENUM('admin', 'customer', 'trip_leader')`)
    *   `receiver_id` (`INT`)
    *   `message` (`TEXT`)
    *   `is_read` (`BOOLEAN`, default `false`)
    *   `created_at` / `updated_at` (`TIMESTAMP`)

3.  **Tabel `add_ons` & `pemesanan_addon` (Sistem Add-on)**:
    *   Tabel master `add_ons` menyimpan opsi perlengkapan tambahan (Tenda Dome, Sleeping Bag, Carrier Pack, dll).
    *   Tabel pivot `pemesanan_addon` menghubungkan `id_pemesanan` dengan `id_addon` beserta kolom `qty` untuk mendukung pembelian multi-item.

---

## 🏢 3. Rincian Modul & Fitur Terimplementasi

### 3.1. Gerbang Autentikasi Multi-Guard & Multi-Role
*   Mendukung dynamic login satu formulir pada `/login`. Sistem secara berurutan mencoba mencocokkan kredensial pada guard:
    1.  `admin` (berbasis `username`).
    2.  `trip_leader` (berbasis `email`).
    3.  `customer` (berbasis `email`).
*   Middleware otentikasi role (`EnsureUserIsAdmin`, `EnsureUserIsTripLeader`, `EnsureUserIsCustomer`) disempurnakan dengan pemeriksaan guard secara eksplisit (`Auth::guard('nama_guard')->check()`) untuk mencegah kebocoran otentikasi dan menghindari redirect loop 403.
*   Registrasi `/register` otomatis mencatat data ke tabel `customers` dan mengarahkan pengguna kembali ke alur checkout pemesanan sebelumnya secara dinamis (`redirect()->intended()`).

### 3.2. Portal Publik & Customer Experience
*   **Landing Page (`welcome.blade.php`)**: Desain berestetika tinggi dengan floating navigation bar, slider promosi, grid filter kategori petualangan (Mountain Trekking, Sailing, Rimba), dan blok testimonial/kredibilitas Kelana yang minimalis.
*   **Halaman Detail Paket (`publik/detail.blade.php`)**:
    *   *Creative Editorial Photo Gallery collage* & sliding track animasi modern menggunakan Alpine.js.
    *   Informasi rincian durasi trip, rute perjalanan, fasilitas terintegrasi, Leaflet.js Interactive Map, dan profil Trip Leader yang ditugaskan.
    *   Formulir pemesanan sisi kanan yang statis (non-sticky) dengan Floating Date Picker melayang anti-jitter dan Stepper Counter peserta.
*   **Slide-over Wishlist & Cart Drawer**: Sidebar panel melayang (drawer) berbasis Alpine.js untuk menyimpan wishlist destinasi (Heart button) dan pesanan tertunda (Cart) langsung tanpa memuat ulang halaman.
*   **History & Active Bookings (`/my-bookings`)**: Halaman khusus yang memisahkan E-Ticket aktif (berstatus `PAID`) dengan riwayat perjalanan lampau untuk kebersihan layout dasbor utama customer.

### 3.3. Admin Back-Office (Kelana ERP Suite)
*   **Dasbor Ringkasan**: Menampilkan metrik Net Income (real-time dari database), total Pax terjual, daftar booking terbaru, diagram donut performa sales SVG, dan grafik batang vertikal pendapatan (CSS double-pill bar).
*   **Master Paket Wisata & Jadwal Trip**: CRUD lengkap terhubung database dengan proteksi konfirmasi modal Alpine.js.
*   **Manajemen Pelanggan (`admin/customers`)**: Tabel detail informasi pelanggan, total pesanan, dan akumulasi uang yang dibelanjakan.
*   **Transaksi Real-Time (`admin/transaksi`)**: Panel pantau dan kelola pemesanan, filter status (PENDING, PAID, EXPIRED, CANCELLED), rincian kode promo, diskon, add-ons, serta aksi perubahan status langsung.
*   **Laporan Finansial (`admin/laporan`)**: Ekspor data manifes peserta terjadwal ke format PDF berkualitas cetak menggunakan library `laravel-dompdf`.
*   **Workspace Inbox CS (`admin/messages`)**: Pusat komunikasi 2 panel di mana admin membalas chat customer dan memantau koordinasi trip leader dengan pembaruan reaktif polling 3 detik.
*   **Pengaturan Sistem (`admin/settings`)**: Panel manajemen konfigurasi ERP (Auto-booking toggle, Sandbox Midtrans API configuration, dan server SMTP).

### 3.4. Aplikasi Lapangan Trip Leader (Mobile-Responsive Suite)
*   **Field Ops Dashboard (`trip-leader/dashboard`)**: Layout khusus berukuran mobile yang responsif, menampilkan daftar penugasan trip aktif beserta detail tanggal, status keberangkatan, dan kuota sisa.
*   **Manifes Check-In & Scanner QR (`trip-leader/manifest/{id}`)**:
    *   Sisi kiri menampilkan daftar manifes peserta berstatus PAID.
    *   Sisi kanan mengintegrasikan kamera handphone/PC via library `html5-qrcode` untuk memindai kode QR E-ticket digital customer.
    *   Mendukung input kode booking manual dengan validasi database transaksi aman (`lockForUpdate`).
*   **Dedicated Statistics Screen (`trip-leader/statistics`)**:
    *   Halaman terdedikasi (rute `/trip-leader/statistics`) yang memisahkan data performa leader dari dashboard utama.
    *   Visualisasi metrik: Total penugasan trip, total peserta yang dipandu, rasio check-in kumulatif (progress bar forest green).
    *   Breakdown status trip dalam format grafik persentase horizontal minimalis.
    *   Tabel riwayat kinerja trip per jadwal keberangkatan untuk analisis detail kehadiran.
*   **Chat Ops Support (`trip-leader/chat`)**: Antarmuka chat ringkas bagi Trip Leader untuk melaporkan kondisi lapangan langsung ke HQ Admin.

---

## 🤖 4. Sistem Otomatisasi & Integrasi Pihak Ketiga

### 4.1. Payment Gateway Midtrans (Sandbox)
*   **Booking Checkout**: Customer memilih opsi add-ons, dan total biaya otomatis dihitung di backend. Token transaksi Snap dibuat dan dikirim ke frontend untuk membuka dialog pembayaran pop-up resmi.
*   **Automated Webhook (`WebhookController`)**: Menerima callback dari Midtrans secara asinkron. Jika status transaksi `settlement` atau `capture`, sistem otomatis memperbarui:
    *   Status pembayaran di tabel `pemesanan` menjadi `PAID`.
    *   Membuat record sukses di tabel `pembayaran`.
    *   Memotong stok kuota perjalanan secara otomatis di tabel `jadwal_trip`.
    *   Memicu sistem Auto-Responder Chat.

### 4.2. Chat CS Auto-Responder Bot
Ketika status pembayaran pemesanan diperbarui, sistem melalui Event Hook di database memicu pengiriman pesan otomatis:
1.  **Status `PAID`**:
    *   Trip Leader otomatis mengirim pesan perkenalan ke Customer:
        > *"Halo [Customer Name], saya [Leader Name] selaku Trip Leader Anda untuk paket [Nama Paket]. Mohon persiapkan perlengkapan Anda. Sampai jumpa di titik temu!"*
    *   Trip Leader mengirim laporan otomatis ke CS Admin:
        > *"Sistem Notifikasi: Saya siap mendampingi trip [Nama Paket] tanggal [Tanggal]. Tiket peserta [Customer Name] telah terverifikasi lunas."*
2.  **Status `PENDING` / `CANCELLED`**:
    *   Sistem CS otomatis mengirim instruksi tata cara pembayaran atau notifikasi pembatalan ke obrolan customer.

### 4.3. Skema Perhitungan Kode Promo (Diskon)
Tiga kode promo eksklusif diprogram langsung di dalam `BookingWebController` dan API checkout:
*   `MERDEKA20` ➔ Potongan harga **20%** dari harga tiket dasar (Base Ticket Price).
*   `RINJANIPAS` ➔ Potongan harga **10%** dari harga tiket dasar.
*   `KOMODOLUX` ➔ Potongan harga flat **Rp 100.000**.
*   *Formulasi kalkulasi:* `Total Bayar = (Harga Tiket x Pax) - Diskon Promo + Total Add-ons`.

---

## 📁 5. Daftar File Utama & Rute Baru (Scaffolding Map)

*   **Rute Baru (`routes/web.php` & `routes/api.php`)**:
    *   `GET /trip-leader/statistics` ➔ `LeaderDashboardController@statistics` (Rute visual statistik Trip Leader).
    *   `GET /admin/customers` ➔ `CustomerWebController@index` (Daftar Customer ERP).
    *   `GET /admin/transaksi` ➔ `TransaksiWebController@index` (Pusat Transaksi).
    *   `POST /admin/transaksi/{id}/update-status` ➔ Update status bayar manual.
    *   `GET /admin/messages` ➔ Inbox Chat Admin.
    *   `GET /trip-leader/chat` ➔ Chat Ops Leader.
*   **Controller Baru**:
    *   `app/Http/Controllers/AdminWeb/CustomerWebController.php`
    *   `app/Http/Controllers/AdminWeb/TransaksiWebController.php`
    *   `app/Http/Controllers/AdminWeb/ChatAdminWebController.php`
    *   `app/Http/Controllers/TripLeader/LeaderChatWebController.php`
*   **View Template Baru**:
    *   `resources/views/leader/statistics.blade.php`
    *   `resources/views/leader/chat.blade.php`
    *   `resources/views/admin/customer/index.blade.php`
    *   `resources/views/admin/transaksi/index.blade.php`
    *   `resources/views/admin/messages/index.blade.php`
    *   `resources/views/admin/settings/index.blade.php`

---

## 🧪 6. Panduan Pengujian Sistem (QA Test Scenarios)

Silakan ikuti instruksi berikut untuk memverifikasi fungsionalitas fitur baru:

### 6.1. Pengujian Login Multi-Role & Sidebar Navigasi
1.  Buka browser Anda dan akses `http://localhost:8000/login`.
2.  Lakukan pengujian login sebagai **Trip Leader**:
    *   Kredensial: Email `adi.wijaya@kelana.com`, Password `password`.
    *   Setelah masuk, Anda harus diarahkan langsung ke Dashboard Field Ops.
3.  Periksa sidebar menu sebelah kiri:
    *   Klik **Jadwal Memandu** ➔ Rute aktif `/trip-leader/dashboard`. Indikator garis hijau harus muncul di samping menu ini.
    *   Klik **Chat Ops Support** ➔ Rute aktif `/trip-leader/chat`. Indikator garis hijau harus berpindah ke sini.
    *   Klik **Statistics** ➔ Rute aktif `/trip-leader/statistics`. Menu ini sekarang memuat halaman statistik dedicated dan mengaktifkan indikator bar hijau visual dengan sempurna tanpa menyenggol menu "Jadwal Memandu".

### 6.2. Simulasi Transaksi Checkout, Kode Promo, & Add-ons
1.  Logout dari akun Trip Leader dan login sebagai **Customer**:
    *   Kredensial: Email `budi.santoso@kelana.com`, Password `password`.
2.  Akses salah satu Paket Wisata, tentukan tanggal keberangkatan, set Pax, dan klik **Book Now**.
3.  Pada halaman checkout:
    *   Pilih add-ons **Tenda Dome** dan **Sleeping Bag** pada grid.
    *   Masukkan kode promo `MERDEKA20` pada input promo dan klik Apply.
    *   Verifikasi bahwa struck ringkasan di sebelah kanan menghitung pemotongan 20% secara tepat dan menggabungkannya dengan total biaya add-ons.
    *   Klik **Proceed to Payment**. Salin **Kode Booking / Order ID** (Misal: `TRIP-2026-XXXX`) yang tertera pada Snap Modal.

### 6.3. Simulasi Sukses Bayar (Webhook Midtrans & Auto-Responder Chat)
1.  Buka terminal PowerShell atau Command Prompt Anda.
2.  Jalankan perintah curl berikut untuk menstimulasikan callback pembayaran sukses dari Midtrans Sandbox:
    ```powershell
    Invoke-RestMethod -Method Post -Uri "http://localhost:8000/api/v1/webhook/midtrans" -ContentType "application/json" -Body '{"order_id": "[KODE_BOOKING_ANDA]", "transaction_status": "settlement", "payment_type": "gopay", "transaction_id": "trx-101", "transaction_time": "2026-06-14 16:30:00", "gross_amount": "500000.00"}'
    ```
    *(Ganti `[KODE_BOOKING_ANDA]` dengan kode booking transaksi Anda).*
3.  Setelah respons webhook berhasil (`200 OK`), masuk kembali ke dashboard customer `/dashboard`.
4.  Status tiket Anda sekarang harus berubah menjadi **PAID** dengan tombol **Download E-Ticket (PDF)** yang aktif.
5.  Akses floating chat di pojok kanan bawah customer dashboard: Anda akan melihat pesan sambutan otomatis dari Trip Leader yang ditugaskan untuk memandu trip Anda tersebut.

### 6.4. Simulasi Check-In Manifest Trip Leader (Scan QR / Manual)
1.  Login kembali sebagai Trip Leader (`adi.wijaya@kelana.com` / `password`).
2.  Masuk ke menu **Jadwal Memandu**, lalu klik **Buka Manifes & Scan QR** pada jadwal perjalanan yang baru saja dipesan oleh customer Budi Santoso.
3.  Di halaman manifest:
    *   Kamera pemindai QR akan aktif secara otomatis.
    *   Tuliskan kode booking transaksi tadi pada input check-in manual di bawah kamera.
    *   Klik **Check-In Manual**.
4.  Status kehadiran customer Budi Santoso pada daftar tabel manifes di bawah harus berubah secara instan menjadi **HADIR** berwarna hijau.
5.  Akses rute menu **Statistics** pada sidebar Trip Leader: angka rasio kehadiran (Check-in Rate) dan jumlah peserta dipandu harus meningkat menyesuaikan data check-in terbaru tersebut secara real-time!
