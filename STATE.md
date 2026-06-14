# STATE.md - Kelana v2.0 Development State Log

## Tanggal: 14 Juni 2026

### 🛡️ Security Hotfix & Schema Refactor
Telah diimplementasikan perbaikan keamanan kritis pada webhook Midtrans, pembaharuan presisi data desimal pada database untuk mencegah overflow, serta pengamanan race condition (overbooking) menggunakan DB locking.

#### Perubahan Detail:
1. **Validasi Signature Webhook Midtrans (`app/Http/Controllers/Api/WebhookController.php`)**:
   - Menambahkan verifikasi hash SHA512 signature key (`order_id`, `status_code`, `gross_amount`, `MIDTRANS_SERVER_KEY`) sebelum melakukan perubahan status pemesanan.
   - Membatalkan transaksi dengan respons `403 Forbidden` jika signature tidak cocok.
   - Tetap mempertahankan fallback local mock testing jika env `MIDTRANS_SERVER_KEY` tidak dikonfigurasi atau bernilai `placeholder`.

2. **Refaktor Skema Desimal (`database/migrations/2026_06_14_200500_alter_decimal_columns_to_15_2.php`)**:
   - Membuat migrasi baru untuk menaikkan presisi kolom harga/bayar dari `10,2` menjadi `15,2` guna mendukung nominal transaksi hingga triliunan rupiah (mencegah overflow).
   - Kolom yang diubah:
     - `paket_wisata.harga`
     - `pemesanan.total_harga`
     - `pemesanan.diskon`
     - `pembayaran.jumlah_bayar` (menggantikan `total_tagihan`).

3. **DB Locking Concurrency (`lockForUpdate()`)**:
   - Melindungi setiap penambahan/pengurangan kolom `sisa_kuota` di tabel `jadwal_trip` menggunakan query `lockForUpdate()` di dalam *DB Transaction* untuk mencegah overbooking / race condition saat ada beberapa pembayaran simultan.
   - File yang disesuaikan:
     - [CartWebController.php](file:///c:/Development/kelana-v2.0/app/Http/Controllers/Customer/CartWebController.php)
     - [JadwalWebController.php](file:///c:/Development/kelana-v2.0/app/Http/Controllers/AdminWeb/JadwalWebController.php)
     - [JadwalTripController.php](file:///c:/Development/kelana-v2.0/app/Http/Controllers/Api/Admin/JadwalTripController.php)

4. **Perbaikan Middleware Autentikasi Role (`app/Http/Middleware/EnsureUserIs*.php`)**:
   - Memperbaiki middleware `EnsureUserIsAdmin`, `EnsureUserIsCustomer`, dan `EnsureUserIsTripLeader` agar dapat mengenali user yang terautentikasi melalui token API (Sanctum) dengan melakukan pengecekan tipe instance model user (`instanceof`), di samping pengecekan session guard bawaan. Ini menyelesaikan isu error `403 Forbidden` ("Akses ditolak") ketika mengakses endpoint API terproteksi setelah berhasil login.
