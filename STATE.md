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

---

### 👤 Siklus Pelanggan: Form Ulasan & Google Socialite
Telah diimplementasikan form ulasan/rating pelanggan dan integrasi Google Login menggunakan Laravel Socialite untuk menyempurnakan alur pengguna.

#### Perubahan Detail:
1. **Fase 1: Form Ulasan & Rating**:
   - Menambahkan relasi `ulasan()` ke model `App\Models\JadwalTrip`.
   - Melakukan eager-load relasi `ulasan` pada `BookingWebController@myBookings` untuk mencegah N+1 query.
   - Menambahkan route POST `/my-bookings/ulasan` untuk menyimpan ulasan.
   - Membuat `App\Http\Controllers\CustomerWeb\UlasanWebController` untuk validasi ulasan (wajib memiliki booking lunas status PAID dan pengecekan ulasan ganda).
   - Mengintegrasikan dialog ulasan modal menggunakan **Alpine.js** di `resources/views/customer/bookings.blade.php` dengan input bintang interaktif (1-5) dan komentar dalam tema *Forest Green* Siohioma Style.

2. **Fase 2: Integrasi Login Google (Laravel Socialite)**:
   - Menambahkan konfigurasi provider `google` di `config/services.php`.
   - Menambahkan rute redirect dan callback Google Socialite di `routes/web.php`.
   - Membuat `App\Http\Controllers\Auth\SocialiteController` untuk mengarahkan pengguna ke Google, menangani data callback, mencocokkan/membuat akun customer secara dinamis, dan melakukan autentikasi ke guard `customer`.
   - Menghubungkan tautan tombol Google Login di `login.blade.php` dan `register.blade.php` menuju rute redirect Socialite.

3. **Fase 3: Refactoring Chat CS ke Slide-Over Drawer**:
   - Mengubah komponen chat mengambang (floating widget) pada `resources/views/components/customer-wishlist-cart.blade.php` menjadi Slide-Over Drawer dari sisi kanan layar, menyerupai layout Drawer Wishlist.
   - Menyelaraskan visual transisi, overlay latar belakang gelap `#0f1a15]/50`, dan layout header CS dengan warna Siohioma-style `#0b1611`.
   - Menghapus floating button lama karena pemicu tombol chat sudah terintegrasi langsung pada navbar.
   - Memastikan auto-scroll chat ke dasar stream berfungsi reaktif menggunakan properti overflow dan layout flex.
   - **[Peningkatan Baru]** Mengimplementasikan daftar kontak chat (Contacts List View) pada drawer sebelum masuk ke ruang percakapan (Chat Thread View), persis seperti panel admin/trip leader. Customer dapat memilih untuk mengobrol dengan **Customer Service (Admin CS)** maupun **Trip Leader** yang bertugas pada pemesanan perjalanan mereka yang sudah lunas (PAID).
   - Menambahkan API `/chat/contacts` pada `CustomerChatWebController` untuk mengidentifikasi dan memuat semua Trip Leader yang memiliki reservasi berstatus PAID dengan customer bersangkutan secara dinamis.
   - Menyesuaikan model pengiriman dan penerimaan pesan reaktif berbasis parameter `contact_type` & `contact_id`.

4. **Pembatasan Kuantitas Peserta & Add-ons**:
   - Membatasi tombol peningkatan peserta (`jumlahPeserta++`) pada [detail.blade.php](file:///c:/Development/kelana-v2.0/resources/views/publik/detail.blade.php) agar tidak melebihi sisa kuota jadwal trip yang dipilih (`selectedScheduleQuota`).
   - Menyinkronkan dan membatasi counter kuantitas peserta pada [booking.blade.php](file:///c:/Development/kelana-v2.0/resources/views/customer/booking.blade.php) agar tidak melebihi sisa kuota pada backend maupun frontend.
   - Mengimplementasikan logika pembatasan kuantitas add-ons di mana kuantitas maksimal masing-masing add-on yang dipilih dibatasi maksimal sejumlah peserta perjalanan (`jumlah`).
   - Memberikan pengecualian khusus untuk add-on yang mengandung kata "drone" (misalnya *Dokumentasi Drone & GoPro*) di mana kuantitasnya dibatasi maksimal **1** saja per booking, baik di frontend maupun validasi/auto-clamp backend [BookingWebController.php](file:///c:/Development/kelana-v2.0/app/Http/Controllers/Customer/BookingWebController.php).
   - Menambahkan fungsi otomatis `clampAddonQuantities()` pada checkout form untuk mereduksi kuantitas add-on secara real-time apabila jumlah peserta dikurangi oleh pengguna.
