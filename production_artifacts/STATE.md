# 📝 Development State Log - Kelana v2.0

## Current Status
- **Phase**: PHASE 3: EXECUTION & CODING
- **Feature**: API Pemesanan & Integrasi Midtrans Sandbox (Role Customer) (Issue #10)
- **Status**: Completed & Verified ✅

## Tasks Checklist
- [x] Create feature branch `feat/register-user` (User)
- [x] Clean install Laravel 11.x inside `app_build/` (User)
- [x] Install dependency `laravel/breeze` (blade) (User)
- [x] Install dependency `midtrans/midtrans-php` (User)
- [x] Install dependency `barryvdh/laravel-dompdf` (User)
- [x] Configure `.env` with MySQL & Midtrans Keys (AI)
- [x] Create Customer Model `app/Models/Customer.php` (AI)
- [x] Create Migration `database/migrations/0001_01_01_000003_create_customers_table.php` (AI)
- [x] Create Controller `app/Http/Controllers/Api/AuthController.php` (AI)
- [x] Create API routing file `routes/api.php` and register API routes (AI)
- [x] Verify Register API `/api/v1/auth/register` (User)
- [x] Create Admin Model & Migration (AI)
- [x] Create TripLeader Model & Migration (AI)
- [x] Update Customer Model with `HasApiTokens` (AI)
- [x] Update `AuthController.php` with multi-role `login` logic (AI)
- [x] Update `routes/api.php` with `/auth/login` route (AI)
- [x] Update `DatabaseSeeder.php` to seed Admin, Customer, and TripLeader (AI)
- [x] Install Laravel Sanctum dependency `laravel/sanctum` (User)
- [x] Run migrations & database seeding (User)
- [x] Verify Login API via HTTP Request for Admin, Customer, and Trip Leader (User)
- [x] Create Migration for `paket_wisata` table (AI)
- [x] Create Model `app/Models/PaketWisata.php` with PHP attributes (AI)
- [x] Create custom middleware `EnsureUserIsAdmin` to authorize only Admin requests (AI)
- [x] Register `admin` middleware alias in `bootstrap/app.php` (AI)
- [x] Create CRUD routes under `/api/v1/admin/paket-wisata` with `auth:sanctum` and `admin` middleware (AI)
- [x] Implement CRUD operations in `PaketManagementController` with exact specifications (AI)
- [x] Seed `DatabaseSeeder` with `PaketWisata` dummy data (AI)
- [x] Create Migration for `jadwal_trip` table (AI)
- [x] Create Model `app/Models/JadwalTrip.php` with PHP attributes & relations (AI)
- [x] Create CRUD routes under `/api/v1/admin/jadwal-trip` with Sanctum & admin middleware (AI)
- [x] Implement CRUD operations in `JadwalTripController` with custom validations (exists, date, after_or_equal) (AI)
- [x] Seed `DatabaseSeeder` with `JadwalTrip` dummy data (AI)
- [x] Extend `test-api.php` to include Jadwal Trip CRUD and validation tests (AI)
- [x] Create Migration for `pemesanan` table (AI)
- [x] Create Migration for `pembayaran` table (AI)
- [x] Create Model `app/Models/Pemesanan.php` with PHP attributes & relations (AI)
- [x] Create Model `app/Models/Pembayaran.php` with PHP attributes & relations (AI)
- [x] Create custom middleware `EnsureUserIsCustomer` to restrict access to Customer role (AI)
- [x] Register `customer` middleware in `bootstrap/app.php` (AI)
- [x] Add endpoint `POST /api/v1/pemesanan` with Sanctum and customer middleware (AI)
- [x] Implement database transaction, booking logic, and Midtrans Snap token generation in `Customer\PemesananController` (AI)
- [x] Update `DatabaseSeeder.php` to include initial `sisa_kuota` data (AI)
- [x] Update `draft_perancangan.md` for Midtrans Sandbox mode (AI)
- [x] Extend `test-api.php` with comprehensive Booking API test cases (AI)

## Notes
- Model `Customer`, `Admin`, dan `TripLeader` sekarang telah dikonfigurasi sebagai class `Authenticatable` dengan trait `HasApiTokens` dari Laravel Sanctum.
- Migrasi database tabel `admins` dan `trip_leaders` telah dibuat agar terstruktur sesuai dengan spesifikasi draft perancangan.
- Controller `AuthController` diperbarui dengan menambahkan fungsi `login()` yang melakukan verifikasi multi-role berturut-turut pada tabel `admins` (dengan field `username`), `customers` (dengan field `email`), dan `trip_leaders` (dengan field `email`).
- Endpoint POST `/api/v1/auth/login` telah didaftarkan pada routing `routes/api.php`.
- File `DatabaseSeeder.php` telah dikonfigurasi untuk menambahkan data dummy awal untuk ketiga jenis user demi memudahkan proses testing.
- Implementasi CRUD Master Paket Wisata (Admin Only) telah selesai.
- Implementasi CRUD Jadwal Trip (Admin Only) telah selesai:
  - Migrasi `2026_06_09_221200_create_jadwal_trip_table.php` dan model `JadwalTrip.php` telah dibuat lengkap dengan relasi Eloquent.
- Implementasi API Pemesanan & Integrasi Midtrans Sandbox (Role Customer) telah selesai:
  - Migrasi untuk tabel `pemesanan` (`2026_06_09_222600_create_pemesanan_table.php`) dan tabel `pembayaran` (`2026_06_09_222700_create_pembayaran_table.php`) telah dibuat.
  - Model `Pemesanan.php` dan `Pembayaran.php` telah diimplementasikan lengkap dengan relasi antartabel.
  - Custom middleware `EnsureUserIsCustomer` dibuat dan didaftarkan sebagai alias `'customer'` di `bootstrap/app.php`.
  - Endpoint `POST /api/v1/pemesanan` dilindungi dengan `auth:sanctum` dan middleware `customer`.
  - Method `store()` pada `PemesananController` menangani logic database transaction, validasi sisa kuota, kalkulasi harga, generation booking code unik, integrasi Snap API Midtrans Sandbox, dan update data pembayaran (snap_token) & sisa kuota jadwal trip.
  - Skrip testing `test-api.php` diperluas dengan skenario pengujian lengkap: pemesanan sukses, gagal validasi, kuota tidak mencukupi, dan proteksi hak akses admin.
- Karena Sanctum membutuhkan package composer dan eksekusi migrasi, instruksi langkah-langkah pengujian terminal diserahkan kepada User untuk dieksekusi secara lokal.


