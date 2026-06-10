# 🤝 Handoff Document - Kelana v2.0
**Features:** Customer Registration, Multi-Role Authentication, CRUD Admin, Customer Booking, Midtrans Webhook, Digital Ticket, Trip Leader Manifest Check-In, Cetak Laporan PDF Rekap Peserta, & Modul Ulasan & Rating Customer
**Issue References:** 
- [#3 - Register Customer API](https://github.com/heycahya/kelana-v2.0/issues/3)
- [#5 - Multi-Role Login API](https://github.com/heycahya/kelana-v2.0/issues/5)
- [#7 - Bug: Missing Sanctum migrations](https://github.com/heycahya/kelana-v2.0/issues/7)
- [#8 - CRUD Master Paket Wisata (Admin Only)](https://github.com/heycahya/kelana-v2.0/issues/8)
- [#9 - CRUD Manajemen Jadwal Trip (Admin Only)](https://github.com/heycahya/kelana-v2.0/issues/9)
- [#10 - API Pemesanan & Integrasi Midtrans Sandbox (Role Customer)](https://github.com/heycahya/kelana-v2.0/issues/10)
- [#11 - API Webhook Notification Midtrans](https://github.com/heycahya/kelana-v2.0/issues/11)
- [#12 - Modul Tiket Digital Customer & Manifes Check-In Trip Leader](https://github.com/heycahya/kelana-v2.0/issues/12)
- [#14 - Modul Admin Back-office & Cetak Laporan PDF Rekap Peserta](https://github.com/heycahya/kelana-v2.0/issues/14)
- [#16 - Modul Ulasan & Rating (Review) Sisi Customer](https://github.com/heycahya/kelana-v2.0/issues/16)
**Branch:** `feature/phase-6-customer-reviews`
**Status:** Completed & Verified ✅

---

## 📋 Ringkasan Pekerjaan (Oleh AI Code Worker)
Telah diimplementasikan fitur **Modul Ulasan & Rating (Review) dari sisi Customer (Phase 6)** oleh AI Code Worker. Fitur ini memungkinkan pelanggan (*Customer*) yang telah menyelesaikan pemesanan dengan status lunas (`PAID`) untuk memberikan ulasan beserta rating (skala 1-5) pada jadwal trip tertentu. Sistem ini dilengkapi proteksi keamanan data dan aturan bisnis yang ketat:
1. **Otorisasi Ketat**: Customer hanya diperbolehkan mengirimkan ulasan jika memiliki riwayat pesanan tiket dengan status pembayaran `PAID` pada jadwal trip terkait.
2. **Pencegahan Duplikasi (Anti-Spam)**: Customer hanya boleh mengirimkan ulasan maksimal satu kali untuk setiap jadwal trip. Proteksi ini dikunci di tingkat database dengan *Composite Unique Key* dan divalidasi ulang pada tingkat *Controller*.

Seluruh kasus uji otomatis (`32` hingga `36`) telah ditambahkan di `test-api.php` dan sukses memverifikasi skenario sukses ulasan, penolakan ulasan ganda, penolakan ulasan tanpa tiket lunas, validasi rating diluar skala, dan proteksi rute admin.

---

## 🛠️ Perubahan Berkas (File Changes)

### 1. Migrasi Database
- **[2026_06_10_180000_create_ulasan_table.php](file:///c:/Development/kelana-v2.0/app_build/database/migrations/2026_06_10_180000_create_ulasan_table.php)**
  - Membuat tabel `ulasan` dengan primary key `id_ulasan`.
  - Menambahkan foreign key `id_customer` ke tabel `customers` dan `id_jadwal` ke tabel `jadwal_trip` dengan aturan cascade delete (`onDelete('cascade')`).
  - Menambahkan composite unique key `['id_customer', 'id_jadwal']` untuk mencegah ulasan ganda di tingkat database.

### 2. Model Database
- **[Ulasan.php](file:///c:/Development/kelana-v2.0/app_build/app/Models/Ulasan.php)**
  - Mendefinisikan class model `Ulasan` beserta property `$fillable` menggunakan PHP attribute `#[Fillable]`.
  - Mendefinisikan relasi `customer()` dan `jadwalTrip()` (`belongsTo`).

### 3. Controller
- **[UlasanController.php](file:///c:/Development/kelana-v2.0/app_build/app/Http/Controllers/Api/Customer/UlasanController.php)**
  - Mengimplementasikan validasi input request (mengharuskan rating 1-5, string komentar nullable, dan `id_jadwal` yang valid).
  - Melakukan pemeriksaan otorisasi riwayat pembayaran lunas (`PAID`) melalui model `Pemesanan` (mengembalikan status `403 Forbidden` jika tidak valid).
  - Melakukan pemeriksaan duplikasi ulasan di tabel `ulasan` (mengembalikan status `409 Conflict` jika sudah pernah mengulas).
  - Menyimpan ulasan ke database dan mengembalikan status `201 Created` beserta objek ulasan.

### 4. Routing
- **[api.php](file:///c:/Development/kelana-v2.0/app_build/routes/api.php)**
  - Mendaftarkan endpoint `POST /api/v1/customer/ulasan` di dalam kelompok route middleware `['auth:sanctum', 'customer']`.

### 5. Script Testing
- **[test-api.php](file:///c:/Development/kelana-v2.0/app_build/test-api.php)**
  - Menambahkan kasus uji otomatis `[32]` hingga `[36]` untuk memvalidasi ulasan sukses, penolakan ulasan ganda (409), penolakan ulasan tanpa tiket lunas (403), validasi rating di luar jangkauan (422), dan proteksi middleware customer terhadap akses admin (403).

---

## 🧪 Hasil Pengujian (Test Results)

Skenario uji otomatis berjalan sukses secara keseluruhan dengan log keluaran sebagai berikut (setelah database di-reset):

```text
==================================================
       KELANA V2.0 - AUTOMATED API TESTER
==================================================

[1] Melakukan Login Admin...
✅ BERHASIL: Login Admin sukses. Token didapatkan.

[2] Mengambil Semua Paket Wisata (Admin Only)...
✅ BERHASIL (HTTP 200): Data diambil. Jumlah paket: 2

[3] Menambahkan Paket Wisata Baru...
✅ BERHASIL (HTTP 201): Paket ditambahkan dengan ID 8

[4] Melihat Detail Paket Wisata ID 8...
✅ BERHASIL (HTTP 200): Nama Paket = Trip Pantai Tiga Warna

[5] Mengupdate Paket Wisata ID 8...
✅ BERHASIL (HTTP 200): Nama terupdate = Trip Pantai Tiga Warna (Updated)

[6] Menghapus Paket Wisata ID 8...
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
✅ BERHASIL (HTTP 201): Jadwal ditambahkan dengan ID 8

[10] Menambahkan Jadwal Trip Baru (Gagal Validasi - tanggal_selesai sebelum tanggal_mulai & status_trip salah)...
✅ BERHASIL (HTTP 422 Unprocessable Entity): Validasi berhasil menolak data tidak valid.
Response Errors: {"tanggal_selesai":"Kolom tanggal selesai harus bernilai setelah atau sama dengan tanggal mulai.","kuota":"Kuota tidak boleh kurang dari 1.","status_trip":"Status trip tidak valid. Pilih dari: Draft, Open, Berjalan, Selesai, Batal."}

[11] Melihat Detail Jadwal Trip ID 8...
✅ BERHASIL (HTTP 200): Paket Wisata = Trip Bromo Midnight
Trip Leader = Adi Wijaya

[12] Mengupdate Jadwal Trip ID 8 (Ubah kuota & status)...
✅ BERHASIL (HTTP 200): Kuota terupdate = 25, Status terupdate = Berjalan

[13] Menghapus Jadwal Trip ID 8...
✅ BERHASIL (HTTP 200): Jadwal berhasil dihapus.

[14] Mencoba mengakses rute Jadwal Trip admin menggunakan token Customer...
✅ BERHASIL (HTTP 403 Forbidden): Rute Jadwal Trip terproteksi dengan benar!

--------------------------------------------------
       PENGUJIAN API PEMESANAN & INTEGRASI
--------------------------------------------------

[15] Membuat Pemesanan Baru (Valid - 2 peserta)...
✅ BERHASIL (HTTP 201 Created):
   Booking Code: TRIP-20260610-5880
   Total Harga : 700000
   Snap Token  : 194a6062-6b86-4ad0-ab41-35b00a963646

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
     Kuota awal jadwal ID 1: 5
     Kuota setelah booking (dikurangi 3): 2
     Mengirim Webhook Midtrans EXPIRE untuk booking TRIP-20260610-3793...
     Kuota setelah webhook EXPIRE (harus kembali bertambah 3): 5
✅ BERHASIL (HTTP 200 OK): Status EXPIRE diproses dan kuota berhasil dikembalikan!

--------------------------------------------------
   PENGUJIAN TIKET DIGITAL & MANIFES CHECK-IN
--------------------------------------------------

[23] Customer melihat tiket digital untuk booking TRIP-20260610-5880...
✅ BERHASIL (HTTP 200 OK): Detail tiket berhasil dimuat.
   Paket Wisata : Trip Bromo Midnight
   Jumlah Hadir : 0 / 2

[24] Melakukan Login Trip Leader...
✅ BERHASIL: Login Trip Leader sukses. Token didapatkan.

[25] Trip Leader melihat manifes untuk Jadwal Trip ID 1...
✅ BERHASIL (HTTP 200 OK): Manifes berhasil dimuat. Jumlah booking PAID: 5

[26] Trip Leader memproses check-in (1 orang) untuk booking TRIP-20260610-5880...
✅ BERHASIL (HTTP 200 OK): Check-in berhasil. Hadir sekarang: 1 / 2

[27] Trip Leader memproses check-in berlebih (2 orang lagi) untuk booking TRIP-20260610-5880 (Kuota tersisa 1)...
✅ BERHASIL (HTTP 422 Unprocessable Entity): Sistem menolak check-in melebihi kuota.
   Message: Jumlah check-in melebihi total kuota tiket.

[28] Trip Leader memproses check-in sisa kuota (1 orang) untuk booking TRIP-20260610-5880...
✅ BERHASIL (HTTP 200 OK): Check-in sisa kuota sukses. Status Kehadiran: hadir

[29] Mencoba melakukan check-in menggunakan token Customer...
✅ BERHASIL (HTTP 403 Forbidden): Rute check-in terproteksi dari Customer dengan benar!

--------------------------------------------------
       PENGUJIAN LAPORAN PDF REKAP PESERTA
--------------------------------------------------

[30] Admin mengunduh laporan PDF rekap peserta untuk Jadwal Trip ID 1...
✅ BERHASIL (HTTP 200 OK): Dokumen PDF berhasil diunduh and diverifikasi (PDF Signature ditemukan).

[31] Mencoba mengunduh laporan PDF menggunakan token Customer...
✅ BERHASIL (HTTP 403 Forbidden): Rute laporan terproteksi dari Customer dengan benar!

--------------------------------------------------
       PENGUJIAN MODUL ULASAN & RATING
--------------------------------------------------

[32] Customer mengirimkan ulasan valid untuk Jadwal Trip ID 1...
✅ BERHASIL (HTTP 201 Created): Ulasan berhasil disimpan.
   Rating  : 5
   Komentar: Trip yang sangat menyenangkan!

[33] Customer mencoba mengirimkan ulasan ganda untuk Jadwal Trip ID 1...
✅ BERHASIL (HTTP 409 Conflict): Sistem menolak ulasan ganda dengan benar.
   Message: Conflict: Anda sudah memberikan ulasan untuk jadwal trip ini.

[34] Customer mencoba mengirimkan ulasan untuk Jadwal Trip ID 2 (tidak ada booking lunas)...
✅ BERHASIL (HTTP 403 Forbidden): Sistem menolak ulasan karena tidak ada tiket lunas.
   Message: Forbidden: Anda tidak memiliki riwayat pemesanan yang telah lunas untuk jadwal trip ini.

[35] Customer mencoba mengirimkan ulasan dengan rating tidak valid (6)...
✅ BERHASIL (HTTP 422 Unprocessable Entity): Validasi berhasil menolak rating tidak valid.
   Errors: {"rating":"Rating maksimal bernilai 5."}

[36] Admin mencoba mengirimkan ulasan (diharapkan ditolak middleware customer)...
✅ BERHASIL (HTTP 403 Forbidden): Rute terproteksi dari Admin dengan benar.

==================================================
             PENGUJIAN SELESAI
==================================================
```

---

## 🚀 Langkah Selanjutnya (Next Steps)
1. **User**: Meninjau perubahan dan melakukan commit/push menggunakan Git.
2. **User**: Menggabungkan (*merge*) Pull Request branch `feature/phase-6-customer-reviews` ke branch utama `main` setelah disetujui.
3. **User**: Menyerahkan dokumen handoff ini kepada AI Orchestrator (@architect-pm) sebagai laporan serah terima selesainya pengerjaan Phase 6.
