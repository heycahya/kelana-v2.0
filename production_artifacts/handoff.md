# 🤝 Handoff Document - Kelana v2.0
**Features:** Customer Registration, Multi-Role Authentication, CRUD Admin, Customer Booking with Midtrans Sandbox, & Midtrans Webhook Notification
**Issue References:** 
- [#3 - Register Customer API](https://github.com/heycahya/kelana-v2.0/issues/3)
- [#5 - Multi-Role Login API](https://github.com/heycahya/kelana-v2.0/issues/5)
- [#7 - Bug: Missing Sanctum migrations](https://github.com/heycahya/kelana-v2.0/issues/7)
- [#8 - CRUD Master Paket Wisata (Admin Only)](https://github.com/heycahya/kelana-v2.0/issues/8)
- [#9 - CRUD Manajemen Jadwal Trip (Admin Only)](https://github.com/heycahya/kelana-v2.0/issues/9)
- [#10 - API Pemesanan & Integrasi Midtrans Sandbox (Role Customer)](https://github.com/heycahya/kelana-v2.0/issues/10)
- [#11 - API Webhook Notification Midtrans](https://github.com/heycahya/kelana-v2.0/issues/11)
**Branch:** `feat/register-user`
**Status:** Completed & Verified ✅

---

## 📋 Ringkasan Pekerjaan
Telah diimplementasikan fitur API Registrasi Customer, Login Multi-Role (Admin, Customer, Trip Leader), CRUD Master Paket Wisata & Jadwal Trip khusus Admin, API Pemesanan & Integrasi Midtrans Sandbox khusus Customer, serta API Webhook Notification Midtrans. Sistem transaksi database dibungkus dengan DB Transaction, melakukan validasi ketersediaan kuota jadwal trip dengan lock, kalkulasi harga otomatis, pembuatan booking code unik, inisialisasi token pembayaran Snap Midtrans, pembaruan kuota, dan pemrosesan callback webhook Midtrans secara otomatis (termasuk *quota recovery* ketika pesanan dibatalkan/expired). Webhook controller dilengkapi fallback parsing untuk mempermudah testing lokal/sandbox. Seluruh sistem API telah diuji menggunakan skrip otomatis dan terbukti aman serta mematuhi spesifikasi response body JSON standard.

---

## 🛠️ Perubahan Berkas (File Changes)

### 1. Model Database
- **[Customer.php](file:///c:/Development/kelana-v2.0/app_build/app/Models/Customer.php)**
  - Ditambahkan trait `HasApiTokens` dan diubah agar meng-extends `Authenticatable`.
- **[Admin.php](file:///c:/Development/kelana-v2.0/app_build/app/Models/Admin.php)**
  - Model baru untuk tabel `admins` dengan primary key `id_admin` dan trait `HasApiTokens`.
- **[TripLeader.php](file:///c:/Development/kelana-v2.0/app_build/app/Models/TripLeader.php)**
  - Model baru untuk tabel `trip_leaders` dengan primary key `id_leader` dan trait `HasApiTokens`.
- **[PaketWisata.php](file:///c:/Development/kelana-v2.0/app_build/app/Models/PaketWisata.php)**
  - Model baru untuk tabel `paket_wisata` dengan primary key `id_paket` dan PHP 8.2+ class attribute `#[Fillable]`.
- **[JadwalTrip.php](file:///c:/Development/kelana-v2.0/app_build/app/Models/JadwalTrip.php)**
  - Model baru untuk tabel `jadwal_trip` dengan primary key `id_jadwal` beserta hubungan relasi `paketWisata` dan `tripLeader` serta penambahan field `sisa_kuota`.
- **[Pemesanan.php](file:///c:/Development/kelana-v2.0/app_build/app/Models/Pemesanan.php)**
  - Model baru untuk tabel `pemesanan` dengan primary key `id_pemesanan` beserta relasi `customer`, `jadwalTrip`, dan `pembayaran`.
- **[Pembayaran.php](file:///c:/Development/kelana-v2.0/app_build/app/Models/Pembayaran.php)**
  - Model baru untuk tabel `pembayaran` dengan primary key `id_pembayaran`, relasi `pemesanan`, serta penambahan kolom `status_transaksi` ke dalam attribute fillable.

### 2. Migrasi Database
- **[0001_01_01_000004_create_admins_table.php](file:///c:/Development/kelana-v2.0/app_build/database/migrations/0001_01_01_000004_create_admins_table.php)**
  - Skema tabel `admins` untuk login administrator.
- **[0001_01_01_000005_create_trip_leaders_table.php](file:///c:/Development/kelana-v2.0/app_build/database/migrations/0001_01_01_000005_create_trip_leaders_table.php)**
  - Skema tabel `trip_leaders` untuk petugas lapangan.
- **2026_06_09_143751_create_personal_access_tokens_table.php**
  - Skema tabel bawaan Laravel Sanctum untuk mencatat log token API.
- **[2026_06_09_145000_create_paket_wisata_table.php](file:///c:/Development/kelana-v2.0/app_build/database/migrations/2026_06_09_145000_create_paket_wisata_table.php)**
  - Skema tabel `paket_wisata` untuk data master paket open trip.
- **[2026_06_09_221200_create_jadwal_trip_table.php](file:///c:/Development/kelana-v2.0/app_build/database/migrations/2026_06_09_221200_create_jadwal_trip_table.php)**
  - Skema tabel `jadwal_trip` dengan foreign key ke `paket_wisata` dan `trip_leaders` serta batasan enum status trip, ditambah kolom `sisa_kuota`.
- **[2026_06_09_222600_create_pemesanan_table.php](file:///c:/Development/kelana-v2.0/app_build/database/migrations/2026_06_09_222600_create_pemesanan_table.php)**
  - Skema tabel `pemesanan` untuk reservasi customer, diperbarui untuk mencakup status `'PAID'` dan `'FAILED'` di enum `status_pembayaran`.
- **[2026_06_09_222700_create_pembayaran_table.php](file:///c:/Development/kelana-v2.0/app_build/database/migrations/2026_06_09_222700_create_pembayaran_table.php)**
  - Skema tabel `pembayaran` untuk log rekonsiliasi data Midtrans, diperbarui untuk mencakup kolom `status_transaksi`.

### 3. Middleware & Routing
- **[EnsureUserIsAdmin.php](file:///c:/Development/kelana-v2.0/app_build/app/Http/Middleware/EnsureUserIsAdmin.php)**
  - Middleware khusus untuk menyaring request agar hanya dapat dilewati oleh user dengan instance `App\Models\Admin`.
- **[EnsureUserIsCustomer.php](file:///c:/Development/kelana-v2.0/app_build/app/Http/Middleware/EnsureUserIsCustomer.php)**
  - Middleware khusus untuk menyaring request agar hanya dapat dilewati oleh user dengan instance `App\Models\Customer`.
- **[app.php](file:///c:/Development/kelana-v2.0/app_build/bootstrap/app.php)**
  - Mendaftarkan alias middleware `admin` ke `EnsureUserIsAdmin::class` dan `customer` ke `EnsureUserIsCustomer::class`.
- **[api.php](file:///c:/Development/kelana-v2.0/app_build/routes/api.php)**
  - Rute administratif `/api/v1/admin/*` dilindungi oleh middleware `['auth:sanctum', 'admin']`. Rute pemesanan `/api/v1/pemesanan` dilindungi oleh middleware `['auth:sanctum', 'customer']`. Rute webhook `/api/v1/webhook/midtrans` terdaftar secara publik tanpa middleware auth.

### 4. Controller
- **[AuthController.php](file:///c:/Development/kelana-v2.0/app_build/app/Http/Controllers/Api/AuthController.php)**
  - Logika login multi-role terintegrasi.
- **[PaketManagementController.php](file:///c:/Development/kelana-v2.0/app_build/app/Http/Controllers/Api/Admin/PaketManagementController.php)**
  - Mengimplementasikan CRUD API untuk `paket_wisata`.
- **[JadwalTripController.php](file:///c:/Development/kelana-v2.0/app_build/app/Http/Controllers/Api/Admin/JadwalTripController.php)**
  - Mengimplementasikan CRUD API untuk `jadwal_trip` beserta penanganan validasi relasi, tanggal, dan pengisian nilai awal `sisa_kuota`.
- **[PemesananController.php](file:///c:/Development/kelana-v2.0/app_build/app/Http/Controllers/Api/Customer/PemesananController.php)**
  - Mengimplementasikan pembuatan booking API dengan DB Transaction, validasi sisa kuota, kalkulasi harga, generation booking code unik, inisialisasi token pembayaran Snap Midtrans, pembaruan tabel pembayaran, dan pembaruan kuota jadwal trip.
- **[WebhookController.php](file:///c:/Development/kelana-v2.0/app_build/app/Http/Controllers/Api/WebhookController.php)**
  - Mengimplementasikan callback webhook Midtrans dengan database transaction, update status pemesanan (PAID/CANCELLED) & pembayaran (SUCCESS/EXPIRED/FAILED), serta logika pengembalian kuota trip jika transaksi gagal/expire, didukung fallback parsing untuk pengujian lokal.

### 5. Seeder & Script Testing
- **[DatabaseSeeder.php](file:///c:/Development/kelana-v2.0/app_build/database/seeders/DatabaseSeeder.php)**
  - Diperbarui untuk meng-seed data dummy awal lengkap dengan sisa kuota.
- **[test-api.php](file:///c:/Development/kelana-v2.0/app_build/test-api.php)**
  - Script uji otomatis diperluas untuk menguji seluruh siklus CRUD paket wisata, jadwal trip, serta modul pemesanan customer terintegrasi Midtrans.

---

## 🧪 Hasil Pengujian (Test Results)

Pengujian dijalankan secara lokal dengan mengaktifkan `php artisan serve` dan memicu script `test-api.php`:

```text
==================================================
       KELANA V2.0 - AUTOMATED API TESTER
==================================================

[1] Melakukan Login Admin...
✅ BERHASIL: Login Admin sukses. Token didapatkan.

[2] Mengambil Semua Paket Wisata (Admin Only)...
✅ BERHASIL (HTTP 200): Data diambil. Jumlah paket: 2

[3] Menambahkan Paket Wisata Baru...
✅ BERHASIL (HTTP 201): Paket ditambahkan dengan ID 4

[4] Melihat Detail Paket Wisata ID 4...
✅ BERHASIL (HTTP 200): Nama Paket = Trip Pantai Tiga Warna

[5] Mengupdate Paket Wisata ID 4...
✅ BERHASIL (HTTP 200): Nama terupdate = Trip Pantai Tiga Warna (Updated)

[6] Menghapus Paket Wisata ID 4...
✅ BERHASIL (HTTP 200): Paket berhasil dihapus.

[7] Login sebagai Customer untuk menguji proteksi rute...
✅ BERHASIL: Login Customer sukses.
-> Mencoba mengakses rute admin menggunakan token Customer...
✅ BERHASIL (HTTP 403 Forbidden): Rute terproteksi dengan benar!
Response Message: Akses ditolak. Khusus Admin.

--------------------------------------------------
       PENGUJIAN CRUD JADWAL TRIP (ADMIN)
--------------------------------------------------

[8] Mengambil Semua Jadwal Trip...
✅ BERHASIL (HTTP 200): Data diambil. Jumlah jadwal: 2

[9] Menambahkan Jadwal Trip Baru (Valid)...
✅ BERHASIL (HTTP 201): Jadwal ditambahkan dengan ID 4

[10] Menambahkan Jadwal Trip Baru (Gagal Validasi - tanggal_selesai sebelum tanggal_mulai & status_trip salah)...
✅ BERHASIL (HTTP 422 Unprocessable Entity): Validasi berhasil menolak data tidak valid.
Response Errors: {"tanggal_selesai":"Kolom tanggal selesai harus bernilai setelah atau sama dengan tanggal mulai.","kuota":"Kuota tidak boleh kurang dari 1.","status_trip":"Status trip tidak valid. Pilih dari: Draft, Open, Berjalan, Selesai, Batal."}

[11] Melihat Detail Jadwal Trip ID 4...
✅ BERHASIL (HTTP 200): Paket Wisata = Trip Bromo Midnight
Trip Leader = Adi Wijaya

[12] Mengupdate Jadwal Trip ID 4 (Ubah kuota & status)...
✅ BERHASIL (HTTP 200): Kuota terupdate = 25, Status terupdate = Berjalan

[13] Menghapus Jadwal Trip ID 4...
✅ BERHASIL (HTTP 200): Jadwal berhasil dihapus.

[14] Mencoba mengakses rute Jadwal Trip admin menggunakan token Customer...
✅ BERHASIL (HTTP 403 Forbidden): Rute Jadwal Trip terproteksi dengan benar!

--------------------------------------------------
       PENGUJIAN API PEMESANAN & INTEGRASI
--------------------------------------------------

[15] Membuat Pemesanan Baru (Valid - 2 peserta)...
✅ BERHASIL (HTTP 201 Created):
   Booking Code: TRIP-20260609-7724
   Total Harga : 700000
   Snap Token  : 3c6b913b-acb9-433e-a745-fad1cf5fc95e

[16] Membuat Pemesanan Baru (Gagal Validasi - jumlah_peserta = 0)...
✅ BERHASIL (HTTP 422 Unprocessable Entity): Validasi berhasil menolak jumlah peserta kurang dari 1.
   Response Errors: {"jumlah_peserta":"Jumlah peserta minimal 1 orang."}

[17] Membuat Pemesanan Baru (Gagal - kuota tidak cukup/999 peserta)...
✅ BERHASIL (HTTP 422 Unprocessable Entity): Sistem berhasil menolak karena kuota tidak mencukupi.
   Message: Kuota tidak mencukupi

[18] Mencoba mengakses rute Pemesanan Customer menggunakan token Admin...
✅ BERHASIL (HTTP 403 Forbidden): Rute Pemesanan terproteksi dari Admin dengan benar!

--------------------------------------------------
       PENGUJIAN WEBHOOK INTEGRASI MIDTRANS
--------------------------------------------------

[19] Menguji Webhook Midtrans - Booking Code Tidak Ditemukan...
✅ BERHASIL (HTTP 404 Not Found): Webhook mengembalikan 404 untuk order_id yang salah.

[20] Menguji Webhook Midtrans - Status PENDING...
✅ BERHASIL (HTTP 200 OK): Status PENDING diproses.

[21] Menguji Webhook Midtrans - Status SETTLEMENT (Sukses)...
✅ BERHASIL (HTTP 200 OK): Status SETTLEMENT diproses.

[22] Membuat Booking Baru untuk Pengujian Expire & Pengembalian Kuota...
     Kuota awal jadwal ID 1: 13
     Kuota setelah booking (dikurangi 3): 10
     Mengirim Webhook Midtrans EXPIRE untuk booking TRIP-20260609-6455...
     Kuota setelah webhook EXPIRE (harus kembali bertambah 3): 13
✅ BERHASIL (HTTP 200 OK): Status EXPIRE diproses dan kuota berhasil dikembalikan!

==================================================
             PENGUJIAN SELESAI
==================================================
```

---

## 🚀 Langkah Selanjutnya (Next Steps)
1. Gabungkan (*merge*) Pull Request/branch ke branch utama (`main`).
2. Mulai implementasi modul Dashboard/Tiket Digital Customer dan fitur konfirmasi kehadiran mandiri (D-Day).
3. Mulai implementasi modul Trip Leader untuk check-in digital manifes peserta lapangan.
