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

---

### 🗄️ Database Structure Normalization & ERD Prep
Telah diimplementasikan normalisasi struktur database untuk standarisasi Primary Key dan perubahan sistem chat dari model polymorphic ke Room-based Chat demi integritas data referensial yang lebih kuat.

#### Perubahan Detail:
1. **Refaktor Sistem Chat ke Room-Based**:
   - Membuat migrasi `database/migrations/2026_06_14_205000_create_chat_rooms_system.php` untuk mendefinisikan tabel `chat_rooms` dan `chat_participants` (tabel jembatan role partisipan).
   - Menghapus dan mendesain ulang tabel `messages` agar mereferensikan `id_room` (FK ke `chat_rooms`) dengan kolom pengirim `sender_role` (enum) dan `sender_id`.
   - Membuat model baru `App\Models\ChatRoom` dan `App\Models\ChatParticipant`, serta merestrukturisasi model `App\Models\Message` (menambahkan accessor/appends `sender_type` untuk backward compatibility dengan frontend).
   - Menyesuaikan logika controller chat: `CustomerChatWebController`, `LeaderChatWebController`, `ChatAdminWebController`, `WebhookController` (webhook bot), `BookingWebController`, `PemesananController` (API), dan `TransaksiWebController`.

2. **Dokumentasi Snapshot Finansial**:
   - Menambahkan PHPDoc block pada model `App\Models\Pemesanan` di atas properti `$fillable` yang mendokumentasikan secara tertulis bahwa kolom `total_harga` dan `diskon` bertindak sebagai *historical snapshot* transaksi dan tidak boleh dihitung ulang secara dinamis.

3. **Standarisasi Penamaan Primary Key (Generic ID Rename)**:
   - Membuat migrasi `database/migrations/2026_06_14_204000_rename_generic_primary_keys.php` untuk mengubah kolom primary key generik `id` menjadi spesifik sesuai standar penamaan tabel:
     - `paket_wisata_galleries.id` ➔ `id_gallery`
     - `pemesanan_addon.id` ➔ `id_pemesanan_addon`
     - `wishlists.id` ➔ `id_wishlist`
   - Memperbarui properti `protected $primaryKey` pada model `PaketWisataGallery`, `PemesananAddon` (Pivot), dan `Wishlist`.
   - Memperbarui akses primary key pada `WishlistWebController`.
