**Development Method:** Solo Developer - Vibe Coding
**Document Purpose:** Single Source of Truth (Notion Master Document)

---

## **🏗️ PHASE 1: SETUP & CONTEXT**

### **1.1. Core Goal**

Aplikasi ini menyelesaikan masalah pencarian informasi open trip wisata alam yang tidak terpusat, isu keamanan transaksi, dan kesulitan koordinasi lapangan bagi solo traveler dengan menyediakan platform website terintegrasi untuk pencarian destinasi, pemesanan tiket terpercaya, serta manajemen lapangan digital oleh Trip Leader.

### **1.2. Tech Stack & Dependencies (Locked)**

Untuk menjamin konsistensi *vibe coding* agar AI Agent tidak salah menulis struktur dan versi kode, teknologi dikunci pada spesifikasi berikut:

- **Framework Core:** Laravel 11.x (PHP 8.2+)
- **Architecture Pattern:** API-Driven Architecture (RESTful API) untuk Backend.
- **Database:** MySQL 8.0+ (Menggunakan skema relasional ketat untuk validasi integritas data).
- **Authentication:** Laravel Sanctum untuk Token-based Authentication (Multi-Role API).
- **Absolute Dependencies (Package Composer):**
    - `midtrans/midtrans-php` ➔ Integrasi resmi Payment Gateway Midtrans (Snap Pop-Up & Webhook Notification).
    - `barryvdh/laravel-dompdf` ➔ Generator dokumen PDF untuk mencetak laporan rekap admin dan daftar manifes lapangan Trip Leader.

---

## **🎯 PHASE 2: SCOPE & FLOW (100% Match with Chapter II Report)**

### **2.1. System Actors & Roles**

Sistem mengisolasi hak akses berdasarkan 3 aktor utama yang didefinisikan pada Diagram Use Case:

1. **Customer:** Pengguna umum yang mendaftar, memesan paket trip, melakukan pembayaran, dan melakukan konfirmasi kehadiran mandiri.
2. **Admin:** Pemilik bisnis/pengelola data master paket wisata, pemantau transaksi keuangan, dan pengelola data pengguna.
3. **Trip Leader:** Petugas lapangan yang bertugas memvalidasi kehadiran fisik peserta secara digital dan mengontrol status perjalanan.

### **2.2. Strict MVP Features**

### **A. Modul Customer**

- **Autentikasi (Tabel Use Case 2.2 & 2.3):** Registrasi akun baru & Login multi-role. Pengguna baru otomatis mendapatkan role `customer`.
- **Melihat Daftar Trip (Tabel Use Case 2.1):** Katalog utama yang menampilkan daftar destinasi, harga, tanggal keberangkatan, rincian itinerary, dan sisa kuota.
- **Booking Open Trip (Tabel Use Case 2.4):** Formulir pemesanan paket open trip. Mengisi jumlah slot kursi dan nama peserta. Status awal pesanan diset otomatis: `PENDING`.
- **Payment Gateway - Midtrans (Tabel Use Case 2.5):** Memproses pembayaran menggunakan Midtrans Snap Token dalam mode **Sandbox** (`\Midtrans\Config::$isProduction = false`). Status akan diperbarui secara **otomatis** oleh Webhook Midtrans menjadi `CONFIRMED` saat pembayaran sukses.
- **Menerima Konfirmasi Booking (Tabel Use Case 2.6):** Halaman dashboard/tiket digital yang menampilkan detail kode booking apabila status pembayaran telah `CONFIRMED`.
- **Konfirmasi Kehadiran (Tabel Use Case 2.7):** Tombol mandiri di dashboard customer untuk menyatakan kesiapan berangkat pada Hari-H sebelum berkumpul di titik temu.

### **B. Modul Admin**

- **Login Admin (Tabel Use Case 2.8):** Gerbang masuk khusus autentikasi panel admin.
- **Mengelola Data Trip / CRUD (Tabel Use Case 2.9):** Manajemen data paket trip (Membuat baru, membaca, memperbarui data, atau menghapus trip). Atribut data wajib: Judul, Deskripsi, Harga, Kuota Maksimal, Tanggal Berangkat, Titik Temu.
- **Monitoring Transaksi (Tabel Use Case 2.10):** Memantau status transaksi Midtrans pelanggan secara *real-time*. Admin dapat melakukan aksi pembatalan manual (`CANCELLED`) jika terjadi masalah operasional di luar sistem.
- **Mengelola Data Peserta (Tabel Use Case 2.11):** Hak akses melihat profil pengguna, menghapus akun bermasalah, atau melakukan pembaruan tingkat hak akses dari user biasa menjadi `trip_leader`.
- **Cetak Laporan Peserta (Tabel Use Case 2.12):** Mengunduh/mencetak rekapitulasi manifes seluruh peserta yang terdaftar pada satu trip tertentu ke dalam format PDF menggunakan `laravel-dompdf`.

### **C. Modul Trip Leader**

- **Login Trip Leader (Tabel Use Case 2.13):** Autentikasi masuk khusus petugas lapangan.
- **Melihat Daftar Peserta (Tabel Use Case 2.14):** Menampilkan daftar nama dan informasi kontak peserta aktif khusus untuk paket trip yang ditugaskan kepada dirinya.
- **Check-in Peserta (Tabel Use Case 2.15):** Tombol validasi kehadiran fisik digital di lokasi titik temu (lapangan) untuk merubah status absensi peserta (*Belum Hadir* ➔ *Hadir* / *Absen*).
- **Cetak Daftar Peserta Lapangan (Tabel Use Case 2.16):** Fitur ekspor daftar manifes peserta ke format PDF untuk dicetak sebagai dokumen pegangan luring (*offline*) di lapangan.
- **Memandu Perjalanan (Tabel Use Case 2.17):** Tombol kontrol berkala untuk memperbarui status perjalanan fisik aplikasi (*"Belum Mulai"* ➔ *"Dalam Perjalanan"* ➔ *"Selesai"*).

### **2.3. User Flow Overview (Sesuai Diagram Urutan)**

### **1. Customer Flow**

> Lihat Katalog Trip ➔ Pilih Paket ➔ Isi Form Booking (Status: PENDING) ➔ Eksekusi Pembayaran Midtrans Snap ➔ [Sistem Webhook Midtrans] ➔ Status Otomatis: CONFIRMED ➔ Tiket Digital Aktif ➔ Klik Konfirmasi Kehadiran (Hari-H)
> 

### **2. Admin Flow**

> Login Admin ➔ Buka Dashboard Admin ➔ CRUD Paket Trip ➔ Masuk Menu Transaksi ➔ Pantau Status Pembayaran (Auto-Update via Webhook) ➔ Buka Menu Laporan ➔ Unduh PDF Rekap Peserta
> 

### **3. Trip Leader Flow**

> Login Trip Leader ➔ Masuk Dasbor Lapangan ➔ Pilih Jadwal Trip yang Dipandu ➔ Lihat Manifes Peserta ➔ Klik Unduh PDF Manifes Lapangan (Untuk Pegangan Offline) ➔ Lakukan Check-in Digital (Hari-H) saat Peserta Datang di Titik Temu ➔ Klik "Mulai Perjalanan" (Status: Dalam Perjalanan) ➔ Klik "Selesai" jika Trip Selesai
> 

## **🏗️ PHASE 3: SYSTEM ARCHITECTURE & TECHNICAL DESIGN**

### **3.1. Spesifikasi File (Kamus Data MySQL - Sesuai ERD & LRS)**

**1. Tabel: `admins`**

- **Fungsi:** Menyimpan data kredensial Admin untuk mengelola sistem.
- **Primary Key:** `id_admin`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
| --- | --- | --- | --- | --- |
| 1 | id_admin | INT | - | Primary Key, Auto Increment |
| 2 | username | VARCHAR | 50 | Unique, untuk login admin |
| 3 | password | VARCHAR | 255 | Terenkripsi |
| 4 | nama_admin | VARCHAR | 100 | Nama lengkap |
| 5 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 6 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**2. Tabel: `customers`**

- **Fungsi:** Menyimpan data profil dan akun solo traveler/peserta open trip.
- **Primary Key:** `id_customer`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
| --- | --- | --- | --- | --- |
| 1 | id_customer | INT | - | Primary Key, Auto Increment |
| 2 | nama_customer | VARCHAR | 100 | Nama lengkap |
| 3 | email | VARCHAR | 100 | Unique, untuk login |
| 4 | password | VARCHAR | 255 | Terenkripsi |
| 5 | no_telp | VARCHAR | 15 | Nomor kontak aktif |
| 6 | alamat | TEXT | - | Alamat tinggal |
| 7 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 8 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**3. Tabel: `trip_leaders`**

- **Fungsi:** Menyimpan data petugas pemandu lapangan.
- **Primary Key:** `id_leader`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
| --- | --- | --- | --- | --- |
| 1 | id_leader | INT | - | Primary Key, Auto Increment |
| 2 | nama_leader | VARCHAR | 100 | Nama lengkap |
| 3 | no_telp | VARCHAR | 15 | Nomor kontak aktif |
| 4 | email | VARCHAR | 100 | Unique, untuk login leader |
| 5 | password | VARCHAR | 255 | Terenkripsi |
| 6 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 7 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**4. Tabel: `paket_wisata`**

- **Fungsi:** Menyimpan data master destinasi dan rincian paket open trip.
- **Primary Key:** `id_paket`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
| --- | --- | --- | --- | --- |
| 1 | id_paket | INT | - | Primary Key, Auto Increment |
| 2 | nama_paket | VARCHAR | 150 | Nama destinasi/paket |
| 3 | deskripsi | TEXT | - | Itinerary dan detail penjelasan |
| 4 | harga | DECIMAL | 10,2 | Harga per orang/slot |
| 5 | rute | TEXT | - | Detail rute perjalanan |
| 6 | fasilitas | TEXT | - | Daftar fasilitas yang didapat |
| 7 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 8 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**5. Tabel: `jadwal_trip`**

- **Fungsi:** Menghubungkan paket wisata dengan jadwal keberangkatan dan Trip Leader.
- **Primary Key:** `id_jadwal`
- **Foreign Key:** `id_paket`, `id_leader`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
| --- | --- | --- | --- | --- |
| 1 | id_jadwal | INT | - | Primary Key, Auto Increment |
| 2 | id_paket | INT | - | Foreign Key ➔ paket_wisata.id_paket |
| 3 | id_leader | INT | - | Foreign Key ➔ trip_leaders.id_leader |
| 4 | tgl_keberangkatan | DATE | - | Tanggal pelaksanaan trip |
| 5 | sisa_kuota | INT | - | Slot yang masih tersedia |
| 6 | status_trip | ENUM | - | 'Belum Mulai', 'Dalam Perjalanan', 'Selesai' |
| 7 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 8 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**6. Tabel: `pemesanan`**

- **Fungsi:** Mencatat transaksi reservasi paket trip yang dilakukan oleh Customer.
- **Primary Key:** `id_pemesanan`
- **Foreign Key:** `id_customer`, `id_jadwal`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
| --- | --- | --- | --- | --- |
| 1 | id_pemesanan | INT | - | Primary Key, Auto Increment |
| 2 | booking_code | VARCHAR | 50 | Unique (Contoh: TRIP-2026-XXXX) |
| 3 | id_customer | INT | - | Foreign Key ➔ customers.id_customer |
| 4 | id_jadwal | INT | - | Foreign Key ➔ jadwal_trip.id_jadwal |
| 5 | tgl_pemesanan | DATETIME | - | Waktu reservasi dibuat |
| 6 | jumlah_peserta | INT | - | Total slot/kursi yang dipesan |
| 7 | total_harga | DECIMAL | 10,2 | jumlah_peserta x paket_wisata.harga |
| 8 | status_pembayaran | ENUM | - | 'PENDING', 'WAITING_VERIFICATION', 'CONFIRMED', 'CANCELLED', 'PAID', 'FAILED' |
| 9 | attendance_status | ENUM | - | 'belum_hadir', 'hadir', 'absen' (Untuk Check-in) |
| 10 | jumlah_hadir | INT | - | Kuantitas peserta yang telah check-in (Default: 0) |
| 11 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 12 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

**7. Tabel: `pembayaran`**

- **Fungsi:** Mencatat log rekonsiliasi data pembayaran Midtrans Gateway.
- **Primary Key:** `id_pembayaran`
- **Foreign Key:** `id_pemesanan`

| No | Nama Field | Tipe Data | Panjang | Keterangan |
| --- | --- | --- | --- | --- |
| 1 | id_pembayaran | INT | - | Primary Key, Auto Increment |
| 2 | id_pemesanan | INT | - | Foreign Key ➔ pemesanan.id_pemesanan |
| 3 | transaction_id | VARCHAR | 255 | Order ID / ID transaksi resmi Midtrans |
| 4 | snap_token | VARCHAR | 255 | Nullable, Token Pop-up Midtrans Snap |
| 5 | tgl_pembayaran | DATETIME | - | Waktu sukses bayar dari webhook |
| 6 | jumlah_bayar | DECIMAL | 10,2 | Total dana yang masuk |
| 7 | metode_pembayaran | VARCHAR | 100 | Jenis (Contoh: gopay, bank_transfer) |
| 8 | bukti_pembayaran | VARCHAR | 255 | Nullable, URL log response dari Midtrans |
| 9 | created_at | TIMESTAMP | - | Catatan waktu dibuat |
| 10 | updated_at | TIMESTAMP | - | Catatan waktu diubah |

---

### **3.2. Peta Folder & Struktur Kode Laravel 11 (Scaffolding Map)**

Aturan penempatan file Controller dan Model agar AI Agent bekerja secara teratur mengikuti penamaan komponen tabel asli Anda:

```
app/
├── Models/
│   ├── Admin.php
│   ├── Customer.php
│   ├── TripLeader.php
│   ├── PaketWisata.php
│   ├── JadwalTrip.php
│   ├── Pemesanan.php
│   └── Pembayaran.php
└── Http/
    └── Controllers/
        ├── Api/
        │   ├── AuthController.php            # Endpoint Register & Login (Multi-Role via Sanctum)
        │   ├── Customer/
        │   │   ├── KatalogController.php     # Endpoint Katalog Paket & Jadwal
        │   │   ├── PemesananController.php   # Endpoint Submit form booking & konfirmasi
        │   │   └── PaymentController.php     # Integrasi callback notification Midtrans
        │   ├── Admin/
        │   │   ├── PaketManagementController.php # Endpoint CRUD paket_wisata & jadwal_trip
        │   │   ├── VerificationController.php    # Endpoint konfirmasi manual status
        │   │   └── ReportController.php          # Endpoint cetak laporan PDF
        │   └── TripLeader/
        │       ├── LeaderDashboardController.php # Endpoint list jadwal & manifes peserta
        │       └── FieldCheckInController.php    # Endpoint update attendance_status
```

## **🚀 PHASE 4: MINDMAPPING & DEVELOPMENT BLUEPRINT**

> 💡 **Tujuan:** Peta konseptual untuk menyelaraskan alur kerja sistem. Dokumen ini menjadi referensi tunggal bagi kita saat menyusun *prompt* instruksi untuk AI di setiap tahap pengembangan.
> 

---

# **📑 HIGH-LEVEL PLANNING: SISTEM INFORMASI OPEN TRIP (KELANA-V2.0)**

### **1. 🏗️ PHASE 1: INITIALIZATION & ENVIRONMENT**

- **Core Framework:** Laravel 11.x (Bersih/No Sub-folder).
- **Infrastructure:** MySQL 8.0, Laravel Sanctum (API).
- **Essential Pipeline:**
    - Setup `.env` (MySQL & Midtrans Sandbox Credentials).
    - Dependencies Injection: `midtrans/midtrans-php`, `barryvdh/laravel-dompdf`.
    - Folder Structure: `app_build/` & `production_artifacts/`.

### **2. 🔑 PHASE 2: AUTHENTICATION & SECURITY**

- **Entry Point:** Splash screen (`/welcome`) dengan animasi *fade-in*.
- **Auth Flow (API-Based):**
    - Login: Multi-role API endpoint (`/api/login`) mem-return Token Sanctum.
    - Registration: API `POST /api/v1/auth/register` (Customer).
- **UI Rules:** Dark mode base (`bg-slate-950`), primary `indigo-600`.

### **3. 🌐 PHASE 3: GUEST EXPERIENCE (PUBLIC)**

- **Catalog Exploration:** Grid system (`rounded-xl`), real-time kuota display.
- **Trip Details:** Itinerary, route, fasilitas, dan logic button state (Active vs Disabled/Full).

### **4. 👤 PHASE 4: CUSTOMER TRANSACTIONAL FLOW**

- **Booking Engine:** Input slot, dynamic price calculation.
- **Payment Loop:** Midtrans Snap integration, status sync via Webhook (`PENDING` ➔ `CONFIRMED`).
- **Digital Asset:** Dashboard, e-ticket, dan fitur kehadiran mandiri (D-Day).

### **5. 🎛️ PHASE 5: ADMIN BACK-OFFICE**

- **Dashboard & Analytics:** Revenue metrics, trip activity.
- **Data Control:** CRUD Master Paket, Jadwal, & Trip Leader Assignment.
- **Financial & Report:** Midtrans monitoring, PDF Export (Rekap Manifes).
- **User Governance:** Role management (Promote user to `trip_leader`).

### **6. 🧳 PHASE 6: TRIP LEADER FIELD OPERATIONS**

- **Mobile-First Dashboard:** Penugasan jadwal (Agile).
- **Field Management:**
    - Digital Manifest (Real-time check-in: `Hadir`/`Absen`).
    - Status Control (`Belum Mulai` ➔ `Dalam Perjalanan` ➔ `Selesai`).
    - Offline Support (Export PDF Manifes).

---

## **⚖️ DEVELOPMENT GUIDELINES (Source of Truth)**

- **Architecture:** RESTful API Backend.
- **Design Language:** Minimalist, Dark Mode, Rounded-xl components (Diimplementasi pada sisi Klien).
- **Operational Integrity:** Validasi relasional ketat di sisi MySQL.
- **Efficiency:** Automasi status via Webhook Midtrans (No manual approval needed).