# State Pengembangan - Phase 4: Tiket Digital & Manifes Check-In

## Progress:
- [x] Buat file migrasi untuk menambah kolom `jumlah_hadir` di tabel `pemesanan`.
- [x] Jalankan migrasi database (instruksi disiapkan untuk User).
- [x] Update Model `Pemesanan` dengan `jumlah_hadir` di fillable.
- [x] Buat dan daftarkan Middleware `TripLeaderMiddleware` (`EnsureUserIsTripLeader`).
- [x] Daftarkan Route API di `routes/api.php`.
- [x] Buat Controller `TiketController` untuk Customer.
- [x] Buat Controller `ManifestController` untuk Trip Leader (dengan `lockForUpdate` & DB Transaction).
- [x] Perluas skrip test di `test-api.php`.
- [x] Buat & update `production_artifacts/` untuk dokumentasi API & Arsitektur.

## Detail Perubahan:
1. Membuat migrasi database `2026_06_10_173000_add_jumlah_hadir_to_pemesanan_table.php` untuk menambahkan kolom `jumlah_hadir` di tabel `pemesanan`.
2. Menambahkan `jumlah_hadir` pada parameter `Fillable` di Model `Pemesanan.php`.
3. Membuat middleware `EnsureUserIsTripLeader` untuk verifikasi instansi model `TripLeader` dan mendaftarkannya dengan alias `trip_leader` di `bootstrap/app.php`.
4. Mendaftarkan endpoint tiket digital customer, manifes trip leader, dan check-in trip leader pada `routes/api.php`.
5. Membuat `TiketController` dengan pengecekan kepemilikan booking dan status `PAID`.
6. Membuat `ManifestController` dengan DB transaction, `lockForUpdate` pessimistic lock untuk mencegah race condition, validasi sisa kuota, serta update `attendance_status` otomatis menjadi `'hadir'` saat seluruh peserta hadir.
7. Memperluas skrip pengujian `test-api.php` dengan 7 scenario uji baru (scenario 23-29).
8. Memperbarui `STATE.md` dan `issue.md` di dalam folder `production_artifacts/`.
