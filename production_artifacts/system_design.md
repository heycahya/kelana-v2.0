# 📐 Dokumen Perancangan Sistem - Kelana v2.0
**Judul Proyek:** Sistem Informasi Open Trip Wisata Alam Berbasis Web (Kelana v2.0)
**Development Method:** Solo Developer - Vibe Coding (AI-Assisted Golden Loop)
**Document Purpose:** Single Source of Truth (Dokumen Acuan Tunggal Perancangan & Implementasi)
**Versi Dokumen:** 2.0 (Final - Terintegrasi Penuh)
**Tanggal Pembaruan Terakhir:** 14 Juni 2026

---

## 🏗️ PHASE 1: SETUP & CONTEXT

### 1.1. Core Goal (Tujuan Inti Sistem)

Aplikasi ini menyelesaikan masalah pencarian informasi open trip wisata alam yang tidak terpusat, isu keamanan transaksi, dan kesulitan koordinasi lapangan bagi solo traveler dengan menyediakan platform website terintegrasi untuk pencarian destinasi, pemesanan tiket terpercaya, serta manajemen lapangan digital oleh Trip Leader.

Kelana v2.0 telah berevolusi dari cetak biru menjadi platform **ERP (Enterprise Resource Planning)** dan **Operasional Lapangan** pariwisata petualangan yang terintegrasi penuh. Seluruh komponen mockup statis telah dieliminasi dan digantikan oleh fungsionalitas reaktif yang terhubung langsung dengan database MySQL.

### 1.2. Tech Stack & Dependencies (Locked)

Untuk menjamin konsistensi *vibe coding* agar AI Agent tidak salah menulis struktur dan versi kode, teknologi dikunci pada spesifikasi berikut:

- **Framework Core:** Laravel 11.x (PHP 8.2+)
- **Architecture Pattern:** Hybrid — API-Driven (RESTful API via Sanctum) untuk backend services, SSR (Server-Side Rendering via Blade) untuk seluruh tampilan web dashboard.
- **Frontend Rendering:** Laravel Blade Templating + Alpine.js (Reaktivitas ringan tanpa SPA).
- **CSS Framework:** Tailwind CSS v3.x (Dikonfigurasi dengan token warna kustom Kelana).
- **Database:** MySQL 8.0+ (Menggunakan skema relasional ketat untuk validasi integritas data).
- **Authentication:** 
  - Laravel Sanctum untuk Token-based Authentication (Multi-Role API).
  - Laravel Breeze (Blade Scaffolding) untuk Session-based Authentication (Multi-Guard Web).
  - Laravel Socialite untuk OAuth2 Social Login (Google) — *Belum Diimplementasi, Menunggu Eksekusi Prompt #105*.

- **Absolute Dependencies (Package Composer):**
  - `laravel/breeze` ➔ Scaffolding autentikasi web (Login, Register, Profile) dengan Blade.
  - `laravel/sanctum` ➔ Token-based API authentication untuk endpoint RESTful.
  - `laravel/socialite` ➔ OAuth2 Social Login (Google Sign-In) — *Belum diinstal*.
  - `midtrans/midtrans-php` ➔ Integrasi resmi Payment Gateway Midtrans (Snap Pop-Up & Webhook Notification).
  - `barryvdh/laravel-dompdf` ➔ Generator dokumen PDF untuk laporan rekap admin dan E-Ticket digital customer.

- **CDN Dependencies (Frontend):**
  - `Leaflet.js` ➔ Peta interaktif pada halaman detail paket wisata.
  - `html5-qrcode` ➔ Scanner QR Code berbasis kamera browser untuk check-in manifes Trip Leader.
  - `Midtrans Snap.js` ➔ Pop-up dialog pembayaran resmi Midtrans di sisi klien.

### 1.3. Estetika & Design System (Siohioma Style)

Seluruh antarmuka Kelana v2.0 mematuhi sistem desain berikut:

- **Primary Color:** `Forest Green (#1e5e3a)`
- **Background Content:** `Warm Cream Sand (#dfdfd6)` untuk area konten utama, `Warm Cream Light (#f4f3ed)` untuk halaman publik.
- **Sidebar & Navigation:** `Midnight Forest (#0f1a15)` / `Deep Midnight (#0b1611)`
- **Typography:** Font `Figtree` (diimpor via Vite/CSS) dengan *contrast ratio* standar WCAG AA.
- **Styling Rules:**
  - Sudut membulat konstan `rounded-[26px]` untuk kartu, panel, tabel, dan gambar.
  - *Pill-shape* (`rounded-full`) untuk tombol aksi dan badge status.
  - Desain 100% **Flat** (DILARANG menggunakan `box-shadow`). Pemisah visual menggunakan `border` tipis (`border-[#dfdfd6]`).
- **Brand Logo:** Ikon asterisk/bintang 8-arah (SVG murni) + teks tipografi "Kelana".

---

## 🎯 PHASE 2: SCOPE & FLOW

### 2.1. System Actors & Roles

Sistem mengisolasi hak akses berdasarkan 3 aktor utama yang didefinisikan pada Diagram Use Case:

1. **Customer:** Pengguna umum yang mendaftar, menjelajahi katalog, menyimpan wishlist, memesan paket trip, melakukan pembayaran via Midtrans, mengunduh E-Ticket PDF, berkomunikasi dengan CS via chat, dan memberikan ulasan/rating pasca-perjalanan.
2. **Admin:** Pemilik bisnis/pengelola ERP yang mengelola data master paket wisata & jadwal, memantau transaksi keuangan secara real-time, mengelola data pelanggan, berkomunikasi via inbox CS, mencetak laporan PDF, dan mengonfigurasi pengaturan sistem.
3. **Trip Leader:** Petugas lapangan yang ditugaskan per jadwal keberangkatan. Memvalidasi kehadiran fisik peserta secara digital (QR Scan atau manual), memantau statistik performa, dan berkoordinasi dengan Admin HQ via chat operasional.

### 2.2. Fitur Terimplementasi (Aktual)

### A. Modul Customer

| No | Fitur | Status | Keterangan |
|----|-------|--------|------------|
| 1 | Registrasi & Login (Email/Password) | ✅ Selesai | Multi-guard session via Laravel Breeze |
| 2 | Login Google (OAuth2 Socialite) | ⏳ Menunggu | Prompt #105 sudah disiapkan |
| 3 | Landing Page Premium | ✅ Selesai | Floating navbar, promo slider, grid kategori, FAQ accordion, testimonial |
| 4 | Katalog & Pencarian Destinasi | ✅ Selesai | Full-text search, filter kategori, pagination |
| 5 | Detail Paket Wisata | ✅ Selesai | Galeri foto editorial, Leaflet.js Map, profil Trip Leader, date picker |
| 6 | Wishlist (Slide-over Drawer) | ✅ Selesai | Alpine.js drawer, toggle hati, AJAX sync |
| 7 | Keranjang / Pending Orders (Center Modal) | ✅ Selesai | Alpine.js modal, lanjutkan pembayaran |
| 8 | Booking & Checkout (Enterprise Flow) | ✅ Selesai | Split-layout, progress stepper, add-ons grid, kode promo |
| 9 | Payment Gateway Midtrans Snap | ✅ Selesai | Sandbox mode, webhook otomatis |
| 10 | E-Ticket Digital (PDF Download) | ✅ Selesai | QR Code dinamis, laravel-dompdf |
| 11 | Dashboard & Riwayat Perjalanan | ✅ Selesai | Pemisahan tiket aktif & past trips di `/my-bookings` |
| 12 | Chat CS (Floating Widget) | ✅ Selesai | Polling reaktif 3 detik, bot auto-responder |
| 13 | Form Ulasan & Rating (UI Bintang) | ⏳ Menunggu | Backend API sudah ada, UI modal belum dieksekusi (Prompt #105) |

### B. Modul Admin (Back-Office ERP)

| No | Fitur | Status | Keterangan |
|----|-------|--------|------------|
| 1 | Login Admin (Username-based) | ✅ Selesai | Guard `admin` via `LoginRequest.php` |
| 2 | Dashboard Ringkasan (Overview) | ✅ Selesai | Net Income, Pax terjual, donut chart SVG, bar chart CSS |
| 3 | CRUD Master Paket Wisata | ✅ Selesai | Index, Create, Edit, Show, Delete + validasi |
| 4 | CRUD Jadwal & Penugasan Leader | ✅ Selesai | Dropdown relasi Paket & Leader, auto-inject `sisa_kuota` |
| 5 | Manajemen Pelanggan | ✅ Selesai | Tabel customer + total belanja |
| 6 | Panel Transaksi Real-Time | ✅ Selesai | Filter status, update status manual, detail promo/add-ons |
| 7 | Laporan Finansial (PDF Export) | ✅ Selesai | Rekap manifes peserta per jadwal via `laravel-dompdf` |
| 8 | Workspace Inbox CS (Chat 2-Panel) | ✅ Selesai | Polling reaktif, thread customer & leader |
| 9 | Pengaturan Sistem | ✅ Selesai | Toggle booking otomatis, konfigurasi Midtrans API, SMTP |

### C. Modul Trip Leader (Field Operations)

| No | Fitur | Status | Keterangan |
|----|-------|--------|------------|
| 1 | Login Trip Leader (Email-based) | ✅ Selesai | Guard `trip_leader` via `LoginRequest.php` |
| 2 | Dashboard Jadwal Memandu | ✅ Selesai | Daftar penugasan aktif, kuota sisa, tanggal |
| 3 | Manifes & Check-In (QR Scanner) | ✅ Selesai | html5-qrcode CDN, kamera browser, input manual fallback |
| 4 | Dedicated Statistics Screen | ✅ Selesai | Rasio check-in, breakdown status trip, riwayat performa |
| 5 | Chat Ops Support | ✅ Selesai | Koordinasi dengan Admin HQ & customer aktif |

### 2.3. Sistem Otomatisasi Terintegrasi

| No | Sistem | Keterangan |
|----|--------|------------|
| 1 | **Midtrans Webhook** | Callback asinkron `settlement`/`capture` ➔ auto-update status `PAID`, buat record `pembayaran`, potong `sisa_kuota` |
| 2 | **Chat Auto-Responder Bot** | Trigger dari perubahan status pembayaran ➔ pesan sambutan Leader ke Customer, laporan ke Admin |
| 3 | **Kode Promo Engine** | `MERDEKA20` (20%), `RINJANIPAS` (10%), `KOMODOLUX` (Rp100.000 flat). Formula: `(Harga × Pax) - Diskon + Add-ons` |
| 4 | **QR E-Ticket** | Kode booking di-*encode* menjadi gambar QR pada PDF tiket. Scanner Leader membaca QR ini untuk check-in instan |

### 2.4. User Flow Overview (Diagram Alur)

### 1. Customer Flow
> Buka Landing Page ➔ Jelajahi Katalog / Search ➔ Klik Detail Paket ➔ Pilih Tanggal & Pax ➔ Klik "Book Now" ➔ Halaman Checkout (Pilih Add-ons, Input Promo) ➔ "Proceed to Payment" ➔ Pop-up Midtrans Snap ➔ [Webhook: Auto-update PAID] ➔ Dashboard: E-Ticket Aktif ➔ Download PDF ➔ [Hari-H] Leader Scan QR ➔ Check-in ➔ Trip Selesai ➔ Beri Ulasan ⭐

### 2. Admin Flow
> Login (Username) ➔ Dashboard ERP (Revenue, Donut Chart) ➔ CRUD Paket Wisata ➔ CRUD Jadwal & Assign Leader ➔ Monitor Transaksi Real-Time ➔ Balas Chat CS ➔ Unduh PDF Laporan ➔ Konfigurasi Sistem

### 3. Trip Leader Flow
> Login (Email) ➔ Dashboard Jadwal Memandu ➔ Buka Manifes Peserta ➔ Scan QR / Input Manual ➔ Check-in Peserta ➔ Pantau Statistik Performa ➔ Chat Ops ke Admin HQ

---

## 🏗️ PHASE 3: SYSTEM ARCHITECTURE & TECHNICAL DESIGN

### 3.1. Autentikasi Multi-Guard (config/auth.php)

Sistem menggunakan 3 guard terpisah dengan provider masing-masing:

| Guard | Provider | Model | Identifier | Redirect Setelah Login |
|-------|----------|-------|------------|----------------------|
| `admin` | `admins` | `App\Models\Admin` | `username` | `/admin/dashboard` |
| `trip_leader` | `trip_leaders` | `App\Models\TripLeader` | `email` | `/trip-leader/dashboard` |
| `customer` | `customers` | `App\Models\Customer` | `email` | `/dashboard` |

**Mekanisme Login Terpadu:** Satu form `/login` menggunakan `LoginRequest.php` yang mencoba autentikasi secara sequential pada guard `admin` ➔ `trip_leader` ➔ `customer`. Guard yang berhasil akan diaktifkan dan user dialihkan ke dashboard sesuai perannya.

### 3.2. Middleware Otorisasi

| Middleware | File | Fungsi |
|------------|------|--------|
| `admin` | `EnsureUserIsAdmin.php` | Verifikasi `Auth::guard('admin')->check()`, abort 403 jika gagal |
| `trip_leader` | `EnsureUserIsTripLeader.php` | Verifikasi `Auth::guard('trip_leader')->check()`, abort 403 jika gagal |
| `customer` | `EnsureUserIsCustomer.php` | Verifikasi `Auth::guard('customer')->check()`, redirect `/login` jika gagal |

### 3.3. Spesifikasi File Database (Kamus Data MySQL — Sesuai ERD & LRS)

**1. Tabel: `admins`**
- **Fungsi:** Menyimpan data kredensial Admin untuk mengelola sistem ERP.
- **Primary Key:** `id_admin`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
|----|-----------|-----------|---------|------------|
| 1 | id_admin | INT | - | Primary Key, Auto Increment |
| 2 | username | VARCHAR | 50 | Unique, untuk login admin |
| 3 | password | VARCHAR | 255 | Terenkripsi (bcrypt) |
| 4 | nama_admin | VARCHAR | 100 | Nama lengkap |
| 5 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 6 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**2. Tabel: `customers`**
- **Fungsi:** Menyimpan data profil dan akun solo traveler/peserta open trip.
- **Primary Key:** `id_customer`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
|----|-----------|-----------|---------|------------|
| 1 | id_customer | INT | - | Primary Key, Auto Increment |
| 2 | nama_customer | VARCHAR | 100 | Nama lengkap |
| 3 | email | VARCHAR | 100 | Unique, untuk login |
| 4 | password | VARCHAR | 255 | Terenkripsi (bcrypt) |
| 5 | no_telp | VARCHAR | 15 | Nomor kontak aktif |
| 6 | alamat | TEXT | - | Alamat tinggal |
| 7 | kontak_darurat | VARCHAR | 15 | Nullable, kontak darurat peserta |
| 8 | google_id | VARCHAR | 255 | Nullable, ID OAuth2 Google (untuk Socialite) |
| 9 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 10 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**3. Tabel: `trip_leaders`**
- **Fungsi:** Menyimpan data petugas pemandu lapangan beserta profil publik.
- **Primary Key:** `id_trip_leader`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
|----|-----------|-----------|---------|------------|
| 1 | id_trip_leader | INT | - | Primary Key, Auto Increment |
| 2 | nama_leader | VARCHAR | 100 | Nama lengkap |
| 3 | no_telp | VARCHAR | 15 | Nomor kontak aktif |
| 4 | email | VARCHAR | 100 | Unique, untuk login leader |
| 5 | password | VARCHAR | 255 | Terenkripsi (bcrypt) |
| 6 | avatar | VARCHAR | 255 | Nullable, URL foto profil |
| 7 | bio | TEXT | - | Nullable, deskripsi singkat leader |
| 8 | rating_akumulatif | DECIMAL | 3,2 | Nullable, rata-rata rating dari ulasan |
| 9 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 10 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**4. Tabel: `paket_wisata`**
- **Fungsi:** Menyimpan data master destinasi dan rincian paket open trip.
- **Primary Key:** `id_paket`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
|----|-----------|-----------|---------|------------|
| 1 | id_paket | INT | - | Primary Key, Auto Increment |
| 2 | nama_paket | VARCHAR | 150 | Nama destinasi/paket |
| 3 | destinasi | VARCHAR | 150 | Lokasi/kota tujuan |
| 4 | deskripsi | TEXT | - | Itinerary dan detail penjelasan |
| 5 | harga | DECIMAL | 10,2 | Harga per orang/slot (Rupiah) |
| 6 | rute | TEXT | - | Detail rute perjalanan |
| 7 | fasilitas | TEXT | - | Daftar fasilitas yang didapat |
| 8 | latitude | DECIMAL | 10,7 | Koordinat peta (Leaflet.js) |
| 9 | longitude | DECIMAL | 10,7 | Koordinat peta (Leaflet.js) |
| 10 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 11 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**5. Tabel: `paket_wisata_galleries`**
- **Fungsi:** Menyimpan URL gambar galeri untuk tampilan masonry/editorial di halaman detail paket.
- **Primary Key:** `id`
- **Foreign Key:** `id_paket` ➔ `paket_wisata.id_paket`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
|----|-----------|-----------|---------|------------|
| 1 | id | INT | - | Primary Key, Auto Increment |
| 2 | id_paket | INT | - | Foreign Key ➔ paket_wisata.id_paket |
| 3 | image_url | VARCHAR | 500 | URL gambar (Unsplash/CDN) |
| 4 | caption | VARCHAR | 255 | Nullable, keterangan gambar |
| 5 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 6 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**6. Tabel: `jadwal_trip`**
- **Fungsi:** Menghubungkan paket wisata dengan jadwal keberangkatan dan Trip Leader yang ditugaskan.
- **Primary Key:** `id_jadwal`
- **Foreign Key:** `id_paket` ➔ `paket_wisata.id_paket`, `id_trip_leader` ➔ `trip_leaders.id_trip_leader`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
|----|-----------|-----------|---------|------------|
| 1 | id_jadwal | INT | - | Primary Key, Auto Increment |
| 2 | id_paket | INT | - | Foreign Key ➔ paket_wisata.id_paket |
| 3 | id_trip_leader | INT | - | Foreign Key ➔ trip_leaders.id_trip_leader |
| 4 | tanggal_mulai | DATE | - | Tanggal keberangkatan |
| 5 | tanggal_selesai | DATE | - | Tanggal kembali |
| 6 | kuota_peserta | INT | - | Kapasitas maksimal peserta |
| 7 | sisa_kuota | INT | - | Slot yang masih tersedia (auto-decrement saat PAID) |
| 8 | status_trip | ENUM | - | 'Belum Mulai', 'Dalam Perjalanan', 'Selesai' |
| 9 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 10 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**7. Tabel: `pemesanan`**
- **Fungsi:** Mencatat transaksi reservasi paket trip yang dilakukan oleh Customer.
- **Primary Key:** `id_pemesanan`
- **Foreign Key:** `id_customer` ➔ `customers.id_customer`, `id_jadwal` ➔ `jadwal_trip.id_jadwal`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
|----|-----------|-----------|---------|------------|
| 1 | id_pemesanan | INT | - | Primary Key, Auto Increment |
| 2 | kode_booking | VARCHAR | 50 | Unique (Format: `TRIP-2026-XXXX`) |
| 3 | id_customer | INT | - | Foreign Key ➔ customers.id_customer |
| 4 | id_jadwal | INT | - | Foreign Key ➔ jadwal_trip.id_jadwal |
| 5 | tgl_pemesanan | DATETIME | - | Waktu reservasi dibuat |
| 6 | jumlah_peserta | INT | - | Total slot/kursi yang dipesan |
| 7 | total_harga | DECIMAL | 10,2 | Kalkulasi: (harga × pax) - diskon + add-ons |
| 8 | gross_amount | DECIMAL | 10,2 | Total yang dikirim ke Midtrans |
| 9 | status_pembayaran | ENUM | - | 'PENDING', 'PAID', 'EXPIRED', 'CANCELLED', 'FAILED' |
| 10 | attendance_status | ENUM | - | 'belum_hadir', 'hadir', 'absen' |
| 11 | jumlah_hadir | INT | - | Kuantitas peserta yang telah check-in (Default: 0) |
| 12 | promo_code | VARCHAR | 50 | Nullable, kode promo yang digunakan |
| 13 | diskon | DECIMAL | 10,2 | Nominal pemotongan harga (Default: 0.00) |
| 14 | total_biaya_addons | DECIMAL | 10,2 | Akumulasi biaya add-ons (Default: 0.00) |
| 15 | qr_token | VARCHAR | 255 | Nullable, token unik untuk QR Code E-Ticket |
| 16 | catatan_khusus | TEXT | - | Nullable, permintaan khusus/medis dari customer |
| 17 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 18 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**8. Tabel: `pembayaran`**
- **Fungsi:** Mencatat log rekonsiliasi data pembayaran Midtrans Gateway.
- **Primary Key:** `id_pembayaran`
- **Foreign Key:** `id_pemesanan` ➔ `pemesanan.id_pemesanan`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
|----|-----------|-----------|---------|------------|
| 1 | id_pembayaran | INT | - | Primary Key, Auto Increment |
| 2 | id_pemesanan | INT | - | Foreign Key ➔ pemesanan.id_pemesanan |
| 3 | transaction_id | VARCHAR | 255 | ID transaksi resmi Midtrans |
| 4 | snap_token | VARCHAR | 255 | Nullable, Token Pop-up Midtrans Snap |
| 5 | tgl_pembayaran | DATETIME | - | Waktu sukses bayar dari webhook |
| 6 | jumlah_bayar | DECIMAL | 10,2 | Total dana yang masuk |
| 7 | metode_pembayaran | VARCHAR | 100 | Jenis (gopay, bank_transfer, credit_card, dll) |
| 8 | status_transaksi | VARCHAR | 50 | Status dari Midtrans (settlement, capture, dll) |
| 9 | bukti_pembayaran | VARCHAR | 255 | Nullable, URL log response dari Midtrans |
| 10 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 11 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**9. Tabel: `ulasan`**
- **Fungsi:** Menyimpan rating bintang dan komentar testimoni customer pasca-perjalanan.
- **Primary Key:** `id_ulasan`
- **Foreign Key:** `id_customer`, `id_jadwal`
- **Unique Constraint:** Composite `['id_customer', 'id_jadwal']` (mencegah ulasan ganda per customer per jadwal)

| No | Nama Field | Tipe Data | Panjang | Keterangan |
|----|-----------|-----------|---------|------------|
| 1 | id_ulasan | INT | - | Primary Key, Auto Increment |
| 2 | id_customer | INT | - | Foreign Key ➔ customers.id_customer (CASCADE) |
| 3 | id_jadwal | INT | - | Foreign Key ➔ jadwal_trip.id_jadwal (CASCADE) |
| 4 | rating | INT | - | Nilai 1-5 (bintang) |
| 5 | komentar | TEXT | - | Nullable, teks testimoni |
| 6 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 7 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**10. Tabel: `add_ons`**
- **Fungsi:** Menyimpan data master perlengkapan tambahan (upsell) yang bisa dibeli customer saat checkout.
- **Primary Key:** `id_addon`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
|----|-----------|-----------|---------|------------|
| 1 | id_addon | INT | - | Primary Key, Auto Increment |
| 2 | nama_addon | VARCHAR | 150 | Nama item (Tenda Dome, Sleeping Bag, dll) |
| 3 | harga | DECIMAL | 10,2 | Harga per unit |
| 4 | deskripsi | TEXT | - | Nullable, keterangan item |
| 5 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 6 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**11. Tabel: `pemesanan_addon` (Tabel Pivot)**
- **Fungsi:** Relasi many-to-many antara pemesanan dan add-on, mencatat kuantitas item per pesanan.
- **Foreign Key:** `id_pemesanan`, `id_addon`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
|----|-----------|-----------|---------|------------|
| 1 | id | INT | - | Primary Key, Auto Increment |
| 2 | id_pemesanan | INT | - | Foreign Key ➔ pemesanan.id_pemesanan (CASCADE) |
| 3 | id_addon | INT | - | Foreign Key ➔ add_ons.id_addon (CASCADE) |
| 4 | qty | INT | - | Kuantitas item yang dibeli (Default: 1) |
| 5 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 6 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**12. Tabel: `wishlists`**
- **Fungsi:** Menyimpan daftar favorit/wishlist paket wisata per customer.
- **Foreign Key:** `id_customer`, `id_paket`
- **Unique Constraint:** Composite `['id_customer', 'id_paket']`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
|----|-----------|-----------|---------|------------|
| 1 | id | INT | - | Primary Key, Auto Increment |
| 2 | id_customer | INT | - | Foreign Key ➔ customers.id_customer (CASCADE) |
| 3 | id_paket | INT | - | Foreign Key ➔ paket_wisata.id_paket (CASCADE) |
| 4 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 5 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**13. Tabel: `messages`**
- **Fungsi:** Menyimpan pesan chat CS antar aktor (Customer ↔ Admin ↔ Trip Leader).
- **Primary Key:** `id_message`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
|----|-----------|-----------|---------|------------|
| 1 | id_message | BIGINT | - | Primary Key, Auto Increment |
| 2 | sender_type | ENUM | - | 'admin', 'customer', 'trip_leader' |
| 3 | sender_id | INT | - | ID pengirim (polymorphic) |
| 4 | receiver_type | ENUM | - | 'admin', 'customer', 'trip_leader' |
| 5 | receiver_id | INT | - | ID penerima (polymorphic) |
| 6 | message | TEXT | - | Isi pesan |
| 7 | is_read | BOOLEAN | - | Status baca (Default: false) |
| 8 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 9 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

### 3.4. Relasi Antar-Tabel (ERD Summary)

```
admins ──────────────────────────────────────────────────────┐
                                                              │ (messages polymorphic)
customers ─┬── pemesanan ─┬── pembayaran                      │
           │              ├── pemesanan_addon ── add_ons       │
           │              └── (attendance via trip_leader)     │
           ├── wishlists ── paket_wisata                      │
           ├── ulasan ──────┐                                 │
           └── messages ────┤                                 │
                            │                                 │
paket_wisata ─┬── jadwal_trip ─┬── pemesanan                  │
              │                └── ulasan                     │
              └── paket_wisata_galleries                      │
                                                              │
trip_leaders ──── jadwal_trip                                  │
              └── messages ───────────────────────────────────┘
```

---

## 🗂️ PHASE 4: SCAFFOLDING MAP (Peta Folder & Struktur Kode)

### 4.1. Models (`app/Models/`)

| Model | Tabel | Relasi Utama |
|-------|-------|-------------|
| `Admin.php` | `admins` | — |
| `Customer.php` | `customers` | hasMany: Pemesanan, Wishlist, Ulasan, Message |
| `TripLeader.php` | `trip_leaders` | hasMany: JadwalTrip, Message |
| `PaketWisata.php` | `paket_wisata` | hasMany: JadwalTrip, PaketWisataGallery. hasManyThrough: Ulasan |
| `PaketWisataGallery.php` | `paket_wisata_galleries` | belongsTo: PaketWisata |
| `JadwalTrip.php` | `jadwal_trip` | belongsTo: PaketWisata, TripLeader. hasMany: Pemesanan |
| `Pemesanan.php` | `pemesanan` | belongsTo: Customer, JadwalTrip. hasMany: Pembayaran. belongsToMany: AddOn (pivot) |
| `Pembayaran.php` | `pembayaran` | belongsTo: Pemesanan |
| `Ulasan.php` | `ulasan` | belongsTo: Customer, JadwalTrip |
| `AddOn.php` | `add_ons` | belongsToMany: Pemesanan (pivot) |
| `Wishlist.php` | `wishlists` | belongsTo: Customer, PaketWisata |
| `Message.php` | `messages` | Polymorphic (sender/receiver) |

### 4.2. Controllers

```
app/Http/Controllers/
├── Auth/                                    # Laravel Breeze Auth (Login, Register, Profile)
│   ├── AuthenticatedSessionController.php   # Multi-guard login/logout/redirect
│   ├── RegisteredUserController.php         # Customer registration + intended redirect
│   └── (SocialiteController.php)            # [BELUM DIBUAT] Google OAuth callback
│
├── KatalogWebController.php                 # Halaman publik: Landing Page & Detail Paket
├── ProfileController.php                    # Breeze profile editor (multi-role)
│
├── Customer/                                # Web Controllers (Customer Guard)
│   ├── BookingWebController.php             # Checkout, promo, add-ons, Midtrans Snap token
│   ├── CartWebController.php                # Pending orders modal data
│   ├── WishlistWebController.php            # AJAX wishlist toggle
│   └── CustomerChatWebController.php        # Customer floating chat widget
│
├── AdminWeb/                                # Web Controllers (Admin Guard)
│   ├── AdminDashboardController.php         # Dashboard overview + stats
│   ├── PaketWebController.php               # CRUD paket wisata
│   ├── JadwalWebController.php              # CRUD jadwal & penugasan leader
│   ├── CustomerWebController.php            # Daftar pelanggan
│   ├── TransaksiWebController.php           # Monitor & kelola transaksi
│   ├── LaporanWebController.php             # Laporan finansial + PDF export
│   └── ChatAdminWebController.php           # Inbox CS 2-panel
│
├── TripLeader/                              # Web Controllers (Trip Leader Guard)
│   ├── LeaderDashboardController.php        # Jadwal memandu + statistik performa
│   ├── ManifestWebController.php            # Manifes peserta + QR check-in
│   └── LeaderChatWebController.php          # Chat ops support
│
└── Api/                                     # RESTful API Controllers (Sanctum Token)
    ├── AuthController.php                   # API Register & Login multi-role
    ├── WebhookController.php                # Midtrans webhook notification handler
    ├── Admin/
    │   ├── PaketManagementController.php    # API CRUD paket wisata
    │   ├── JadwalTripController.php         # API CRUD jadwal trip
    │   └── LaporanController.php            # API PDF rekap manifes
    ├── Customer/
    │   ├── PemesananController.php          # API booking + Midtrans token
    │   ├── TiketController.php              # API E-ticket digital
    │   ├── UlasanController.php             # API submit ulasan & rating
    │   ├── ProfileController.php            # API profil customer
    │   └── PesananHistoryController.php     # API riwayat pemesanan
    ├── TripLeader/
    │   └── ManifestController.php           # API manifes + check-in (lockForUpdate)
    └── Publik/
        └── KatalogController.php            # API katalog publik (search, filter, pagination)
```

### 4.3. Views & Layouts

```
resources/views/
├── welcome.blade.php                  # Landing Page Premium (Hero, Promo, Katalog, FAQ, Footer)
├── dashboard.blade.php                # Customer Dashboard (Ringkasan, E-Ticket, Riwayat)
│
├── layouts/
│   ├── app.blade.php                  # Layout default Laravel Breeze
│   ├── guest.blade.php                # Layout halaman publik (Login, Register, Katalog)
│   ├── admin.blade.php                # Layout Admin ERP (Sidebar + Main Content)
│   └── leader.blade.php               # Layout Trip Leader (Sidebar Mobile-First)
│
├── auth/
│   ├── login.blade.php                # Form login terpadu multi-role
│   └── register.blade.php             # Form registrasi customer
│
├── publik/
│   └── detail.blade.php               # Detail paket (Galeri, Map, Leader, Booking Form)
│
├── customer/
│   ├── booking.blade.php              # Halaman checkout enterprise (Split, Stepper, Add-ons)
│   └── my-bookings.blade.php          # Riwayat tiket aktif & past trips (terpisah dari dashboard)
│
├── admin/
│   ├── dashboard.blade.php            # Overview ERP (Stats, Charts, Recent Bookings)
│   ├── paket/                         # index, create, edit, show
│   ├── jadwal/                        # index, create, edit, show
│   ├── customer/                      # index (daftar pelanggan)
│   ├── transaksi/                     # index (monitor & kelola transaksi)
│   ├── laporan/                       # index (daftar laporan + PDF download)
│   ├── messages/                      # index (inbox CS 2-panel)
│   └── settings/                      # index (konfigurasi sistem)
│
├── leader/
│   ├── dashboard.blade.php            # Jadwal memandu (kartu penugasan)
│   ├── manifest.blade.php             # Manifes peserta + QR Scanner + Manual Check-in
│   ├── statistics.blade.php           # Halaman statistik performa dedicated
│   └── chat.blade.php                 # Chat ops support 2-kolom
│
├── components/
│   └── navbar.blade.php               # Navbar reusable (Wishlist Heart, Cart Bag, Avatar)
│
└── pdf/
    └── rekap-peserta.blade.php        # Template cetak PDF rekap manifes
```

### 4.4. Seeders (`database/seeders/`)

| Seeder | Fungsi |
|--------|--------|
| `DatabaseSeeder.php` | Orchestrator utama, memanggil semua seeder secara berurutan + seed Admin, Customer, TripLeader |
| `PaketWisataSeeder.php` | 6 paket wisata eksklusif Kelana (Mountain Trekking, Sailing, Rimba) dengan koordinat & galeri |
| `JadwalTripSeeder.php` | Jadwal keberangkatan aktif & masa depan untuk setiap paket |
| `AddOnSeeder.php` | Item perlengkapan tambahan (Tenda Dome, Sleeping Bag, Carrier Pack, dll) |
| `PemesananSeeder.php` | Transaksi booking dummy untuk mengisi dashboard |
| `UlasanSeeder.php` | Ulasan & rating dummy dari customer |

### 4.5. Routing Map (`routes/web.php` & `routes/api.php`)

**Web Routes (Session-Based):**

| Prefix | Middleware | Controller | Fungsi |
|--------|-----------|------------|--------|
| `/` | guest | `KatalogWebController@index` | Landing page |
| `/paket/{id}` | guest | `KatalogWebController@show` | Detail paket |
| `/dashboard` | auth:customer | `dashboard.blade.php` | Customer dashboard |
| `/my-bookings` | auth:customer | - | Riwayat booking |
| `/booking/{id}` | auth:customer | `BookingWebController` | Checkout flow |
| `/admin/*` | auth:admin, admin | `AdminWeb\*` | Seluruh modul ERP |
| `/trip-leader/*` | auth:trip_leader, trip_leader | `TripLeader\*` | Seluruh modul field ops |

**API Routes (Token-Based, Prefix `/api/v1/`):**

| Method | Endpoint | Middleware | Controller |
|--------|----------|-----------|------------|
| POST | `/auth/register` | - | `AuthController@register` |
| POST | `/auth/login` | - | `AuthController@login` |
| POST | `/webhook/midtrans` | - | `WebhookController@handle` |
| GET | `/publik/paket-wisata` | - | `KatalogController@index` |
| GET | `/publik/paket-wisata/{id}` | - | `KatalogController@show` |
| POST | `/pemesanan` | sanctum, customer | `PemesananController@store` |
| GET | `/customer/tiket/{code}` | sanctum, customer | `TiketController@show` |
| POST | `/customer/ulasan` | sanctum, customer | `UlasanController@store` |
| GET | `/customer/profile` | sanctum, customer | `ProfileController@show` |
| PUT | `/customer/profile` | sanctum, customer | `ProfileController@update` |
| GET | `/customer/pesanan` | sanctum, customer | `PesananHistoryController@index` |
| GET | `/admin/paket-wisata` | sanctum, admin | `PaketManagementController` (CRUD) |
| GET | `/admin/jadwal-trip` | sanctum, admin | `JadwalTripController` (CRUD) |
| GET | `/admin/laporan/rekap/{id}` | sanctum, admin | `LaporanController@rekapPeserta` |
| GET | `/trip-leader/manifest/{id}` | sanctum, trip_leader | `ManifestController@show` |
| POST | `/trip-leader/check-in` | sanctum, trip_leader | `ManifestController@processCheckIn` |

---

## 🚀 PHASE 5: DEVELOPMENT BLUEPRINT (High-Level Planning)

### 5.1. Fase Pembangunan yang Telah Dilalui

| Fase | Nama | Status |
|------|------|--------|
| 1 | Initialization & Environment | ✅ Selesai |
| 2 | Authentication & Multi-Guard Security | ✅ Selesai |
| 3 | Customer Transactional Flow (Booking, Midtrans, E-Ticket) | ✅ Selesai |
| 4 | Front-End Premium (Landing Page, Detail, Wishlist, Cart) | ✅ Selesai |
| 5 | Admin Back-Office ERP (CRUD, Transaksi, Laporan, Chat, Settings) | ✅ Selesai |
| 6 | Trip Leader Field Operations (Dashboard, Manifest, QR, Statistics, Chat) | ✅ Selesai |

### 5.2. Fitur yang Menunggu Implementasi

| No | Fitur | Prompt | Prioritas |
|----|-------|--------|-----------|
| 1 | UI Modal Ulasan & Rating (Bintang Alpine.js di Dashboard Customer) | `105_customer_review_and_socialite.txt` | Tinggi |
| 2 | Login Google (Laravel Socialite OAuth2) | `105_customer_review_and_socialite.txt` | Sedang |

---

## ⚖️ DEVELOPMENT GUIDELINES (Source of Truth)

- **Architecture:** Hybrid — RESTful API (Sanctum) untuk layanan data, SSR Blade untuk rendering UI.
- **Design Language:** Siohioma Style — Flat, Forest Green, Rounded-26px, No Shadow, Figtree Font.
- **Frontend Reactivity:** Alpine.js untuk komponen interaktif (modal, drawer, dropdown, scanner).
- **Operational Integrity:** Validasi relasional ketat di MySQL, `lockForUpdate` untuk operasi transaksional kritis.
- **Efficiency:** Automasi status via Webhook Midtrans dan Chat Auto-Responder Bot.
- **Security:** Multi-guard isolation, middleware role-based, CSRF protection pada semua form web.

---

## 🧪 APPENDIX: Data Pengujian (Test Credentials)

| Role | Identifier | Password |
|------|-----------|----------|
| Admin | Username: `admin` | `password` |
| Trip Leader | Email: `adi.wijaya@kelana.com` | `password` |
| Customer | Email: `budi.santoso@kelana.com` | `password` |

**Kode Promo Aktif:**
- `MERDEKA20` ➔ Diskon 20%
- `RINJANIPAS` ➔ Diskon 10%
- `KOMODOLUX` ➔ Diskon Rp 100.000

**Midtrans Mode:** Sandbox (`MIDTRANS_IS_PRODUCTION=false`)