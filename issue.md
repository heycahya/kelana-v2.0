# Phase 4: Implementasi Modul Tiket Digital Customer & Manifes Check-In Trip Leader

**Konteks Fitur:**
1. Customer yang memesan trip dan status lunas (PAID) dapat melihat tiket digital.
2. Trip Leader (petugas di lapangan) dapat melihat daftar manifes peserta yang lunas berdasarkan jadwal trip.
3. Trip Leader dapat melakukan check-in kehadiran secara kuantitatif (berdasarkan jumlah yang hadir per kode booking), tanpa pendataan nama individu secara spesifik.

Berikut adalah spesifikasi arsitektur teknis dan instruksi implementasi secara low-level untuk dikerjakan oleh @engineer-coder.

---

## 1. Migrasi Database & Model (`pemesanan`)

**Tugas:** Menambahkan tracking kuantitas check-in pada tabel pemesanan.

1. **Buat file migrasi baru** menggunakan artisan:
   ```bash
   php artisan make:migration add_jumlah_hadir_to_pemesanan_table --table=pemesanan
   ```
2. **Definisikan Schema Migrasi**:
   Di dalam method `up()`, tambahkan kolom `jumlah_hadir`:
   ```php
   public function up(): void
   {
       Schema::table('pemesanan', function (Blueprint $table) {
           // Asumsi nama kolom tabel adalah pemesanan. Ganti jika nama tabel berbeda.
           $table->integer('jumlah_hadir')->default(0)->after('status');
       });
   }
   ```
   Di dalam method `down()`:
   ```php
   public function down(): void
   {
       Schema::table('pemesanan', function (Blueprint $table) {
           $table->dropColumn('jumlah_hadir');
       });
   }
   ```
3. **Update Model `Pemesanan`** (misal: `app/Models/Pemesanan.php`):
   Tambahkan `'jumlah_hadir'` ke dalam array properti `$fillable`.

---

## 2. Pembuatan Custom Middleware (Proteksi Role Trip Leader)

**Tugas:** Membuat middleware untuk membatasi endpoint manifes dan check-in agar hanya bisa diakses oleh Trip Leader.

1. **Buat file Middleware**:
   ```bash
   php artisan make:middleware TripLeaderMiddleware
   ```
2. **Implementasikan Logika Pengecekan Role** di `app/Http/Middleware/TripLeaderMiddleware.php`:
   ```php
   public function handle(Request $request, Closure $next): Response
   {
       // Asumsi tabel users memiliki kolom 'role'. Sesuaikan jika menggunakan Spatie Permission dsb.
       if (auth()->check() && auth()->user()->role === 'trip_leader') {
           return $next($request);
       }

       return response()->json([
           'success' => false,
           'message' => 'Akses ditolak. Anda tidak memiliki otoritas sebagai Trip Leader.'
       ], 403);
   }
   ```
3. **Registrasi Middleware**:
   Daftarkan alias middleware ini (misalnya `role.tripleader`).
   - Jika menggunakan **Laravel 11+**, daftarkan di `bootstrap/app.php` pada blok `->withMiddleware(...)`.
   - Jika menggunakan **Laravel 10-**, daftarkan di `app/Http/Kernel.php` di dalam array `$middlewareAliases`.

---

## 3. Pemetaan Rute API (`routes/api.php`)

**Tugas:** Mendefinisikan endpoint untuk kebutuhan Customer dan Trip Leader.

Tambahkan blok kode ini di `routes/api.php`:

```php
// Endpoint untuk Customer (Akses tiket digital miliknya sendiri)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/customer/tiket/{kode_booking}', [TiketController::class, 'showTicket']);
});

// Endpoint untuk Trip Leader (Manifes & Check-in)
Route::middleware(['auth:sanctum', 'role.tripleader'])->group(function () {
    // Mengambil data peserta yang sudah PAID pada jadwal tertentu
    Route::get('/trip-leader/manifest/{jadwal_trip_id}', [ManifestController::class, 'getManifest']);
    
    // Melakukan update jumlah kehadiran peserta
    Route::post('/trip-leader/check-in', [ManifestController::class, 'processCheckIn']);
});
```
*(Ingat: generate controller yang dibutuhkan dengan `php artisan make:controller TiketController` dan `php artisan make:controller ManifestController`)*

---

## 4. Rancangan Logika Controller

### A. `TiketController@showTicket` (Customer)
- **Logika:**
  1. Cari pesanan berdasarkan parameter `$kode_booking`.
  2. **Validasi Kepemilikan:** Pastikan `user_id` pada pesanan sama dengan `auth()->id()`. Jika beda, return 403 Forbidden.
  3. **Validasi Lunas:** Cek apakah `status` == 'PAID'. Jika bukan, return 403/404 dengan pesan "Tiket belum lunas/tidak valid".
  4. Return data JSON yang memuat detail trip, tanggal, jadwal, kuota tiket yang dibeli, dan `jumlah_hadir` saat ini.

### B. `ManifestController@getManifest` (Trip Leader)
- **Logika:**
  1. Ambil data semua pesanan dari tabel `pemesanan` dengan kondisi `jadwal_trip_id` sama dengan parameter.
  2. Filter tambahan: hanya pesanan dengan status 'PAID'.
  3. **Eager Load:** Relasikan (join/with) ke model `User` (untuk nama pemesan) dan `PaketTrip` agar tidak N+1 query.
  4. Return berupa array JSON daftar manifes (berisi kode booking, nama customer, kuota orang, dan total `jumlah_hadir`).

### C. `ManifestController@processCheckIn` (Trip Leader - KRITIS)
Fungsi ini memodifikasi data secara asinkron (multi-user/petugas yang mungkin tapping bersamaan), wajib menggunakan Transaction & Row Locking (`lockForUpdate`).

- **Logika Detail:**
  1. **Validasi Request:**
     ```php
     $request->validate([
         'kode_booking' => 'required|string',
         'jumlah_check_in' => 'required|integer|min:1'
     ]);
     ```
  2. Buka transaksi database:
     ```php
     DB::beginTransaction();
     ```
  3. Lakukan query dengan **Pessimistic Locking**:
     ```php
     try {
         $pesanan = Pemesanan::where('kode_booking', $request->kode_booking)
                        ->lockForUpdate()
                        ->first();
         
         // 4. Jika pesanan tidak ditemukan
         if (!$pesanan) {
             DB::rollBack();
             return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
         }

         // 5. Jika pesanan belum dibayar
         if ($pesanan->status !== 'PAID') {
             DB::rollBack();
             return response()->json(['message' => 'Pesanan belum lunas'], 400);
         }

         // 6. Validasi Akumulasi Hadir vs Total Kuota
         // Asumsi kolom total orang pada pesanan bernama 'jumlah_peserta'
         $akumulasi_hadir_baru = $pesanan->jumlah_hadir + $request->jumlah_check_in;
         
         if ($akumulasi_hadir_baru > $pesanan->jumlah_peserta) {
             DB::rollBack();
             return response()->json([
                 'message' => 'Jumlah check-in melebihi sisa kuota tiket',
                 'sisa_kuota' => $pesanan->jumlah_peserta - $pesanan->jumlah_hadir
             ], 422);
         }

         // 7. Update data
         $pesanan->jumlah_hadir = $akumulasi_hadir_baru;
         $pesanan->save();

         // 8. Selesaikan transaksi
         DB::commit();

         return response()->json([
             'message' => 'Check-in berhasil diproses',
             'jumlah_hadir_sekarang' => $pesanan->jumlah_hadir,
             'total_kuota' => $pesanan->jumlah_peserta
         ], 200);

     } catch (\Exception $e) {
         DB::rollBack();
         // Tambahkan log error (Log::error($e->getMessage()))
         return response()->json(['message' => 'Terjadi kesalahan sistem saat check-in'], 500);
     }
     ```

---

## 5. Dokumentasi API & Sistem (Production Artifact)

**Tugas:** Memastikan seluruh perubahan dan endpoint API yang baru dibuat didokumentasikan dengan rapi di direktori `production_artifact/` sebelum di-merge ke production.

1. **Update API Documentation**:
   - Buka/buat file dokumentasi API (misalnya `production_artifact/api_docs.md` atau skema Swagger/Postman collection yang ada).
   - Definisikan secara detail endpoint baru:
     - `GET /api/customer/tiket/{kode_booking}` (beserta skema respons).
     - `GET /api/trip-leader/manifest/{jadwal_trip_id}` (beserta skema respons).
     - `POST /api/trip-leader/check-in` (beserta skema *request body*, dan seluruh kemungkinan kode respons seperti 200, 400, 404, 422, dan 500).
2. **Update Arsitektur Sistem**:
   - Jika ada file seperti `production_artifact/system_architecture.md` atau `database_schema.md`, tambahkan penjelasan mengenai kolom baru `jumlah_hadir` di tabel `pemesanan`.
   - Dokumentasikan alur mekanisme konkurensi check-in yang menggunakan *Pessimistic Locking* (`lockForUpdate`) agar developer lain paham rasionalisasi desainnya.
