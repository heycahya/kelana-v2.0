# 🤝 Handoff Document - Kelana v2.0
**Features:** Customer Registration, Multi-Role Authentication, CRUD Admin, Customer Booking, Midtrans Webhook, Digital Ticket, & Trip Leader Manifest Check-In
**Issue References:** 
- [#3 - Register Customer API](https://github.com/heycahya/kelana-v2.0/issues/3)
- [#5 - Multi-Role Login API](https://github.com/heycahya/kelana-v2.0/issues/5)
- [#7 - Bug: Missing Sanctum migrations](https://github.com/heycahya/kelana-v2.0/issues/7)
- [#8 - CRUD Master Paket Wisata (Admin Only)](https://github.com/heycahya/kelana-v2.0/issues/8)
- [#9 - CRUD Manajemen Jadwal Trip (Admin Only)](https://github.com/heycahya/kelana-v2.0/issues/9)
- [#10 - API Pemesanan & Integrasi Midtrans Sandbox (Role Customer)](https://github.com/heycahya/kelana-v2.0/issues/10)
- [#11 - API Webhook Notification Midtrans](https://github.com/heycahya/kelana-v2.0/issues/11)
- [#12 - Modul Tiket Digital Customer & Manifes Check-In Trip Leader](https://github.com/heycahya/kelana-v2.0/issues/12)
**Branch:** `feature/phase-4-digital-ticket-manifest`
**Status:** Completed & Verified ✅

---

## 📋 Ringkasan Pekerjaan
Telah diimplementasikan secara lengkap modul Tiket Digital untuk Customer serta manifes peserta lunas & check-in kehadiran mandiri berbasis kuantitas untuk Trip Leader. Untuk menjamin keamanan transaksi dan kekokohan sistem, check-in diimplementasikan menggunakan **Database Transaction** dan **Pessimistic Locking** (`lockForUpdate`) untuk menghindari kondisi balapan (*race condition*) jika terdapat banyak petugas tapping bersamaan. Rute dan middleware diisolasi sehingga data manifes hanya bisa dibaca oleh Trip Leader yang ditugaskan pada trip tersebut. Pengujian terotomasi pada `test-api.php` telah diperluas dan sukses memverifikasi semua alur operasi normal serta batasan penolakan kuota check-in.

---

## 🛠️ Perubahan Berkas (File Changes)

### 1. Model Database
- **[Pemesanan.php](file:///c:/Development/kelana-v2.0/app_build/app/Models/Pemesanan.php)**
  - Menambahkan kolom `'jumlah_hadir'` pada fillable attributes list agar dapat dimutasi.

### 2. Migrasi Database
- **[2026_06_10_173000_add_jumlah_hadir_to_pemesanan_table.php](file:///c:/Development/kelana-v2.0/app_build/database/migrations/2026_06_10_173000_add_jumlah_hadir_to_pemesanan_table.php)**
  - Menambahkan kolom integer `jumlah_hadir` dengan nilai default `0` setelah kolom `status_pembayaran` di tabel `pemesanan`.

### 3. Middleware & Routing
- **[EnsureUserIsTripLeader.php](file:///c:/Development/kelana-v2.0/app_build/app/Http/Middleware/EnsureUserIsTripLeader.php)**
  - Middleware baru untuk membatasi request agar hanya diijinkan jika instansi pengguna adalah `App\Models\TripLeader`.
- **[app.php](file:///c:/Development/kelana-v2.0/app_build/bootstrap/app.php)**
  - Mendaftarkan alias middleware `'trip_leader'` untuk memetakan class `EnsureUserIsTripLeader`.
- **[api.php](file:///c:/Development/kelana-v2.0/app_build/routes/api.php)**
  - Mendaftarkan endpoint `GET /v1/customer/tiket/{booking_code}` di bawah middleware customer.
  - Mendaftarkan `GET /v1/trip-leader/manifest/{id_jadwal}` dan `POST /v1/trip-leader/check-in` di bawah middleware trip_leader.

### 4. Controller
- **[TiketController.php](file:///c:/Development/kelana-v2.0/app_build/app/Http/Controllers/Api/Customer/TiketController.php)**
  - Mengembalikan representasi JSON tiket digital. Memvalidasi status kelunasan pembayaran (`PAID`) dan memverifikasi bahwa tiket tersebut dibeli oleh customer bersangkutan.
- **[ManifestController.php](file:///c:/Development/kelana-v2.0/app_build/app/Http/Controllers/Api/TripLeader/ManifestController.php)**
  - Menampilkan manifes peserta PAID pada jadwal trip. Mengamankan data agar hanya Trip Leader penanggung jawab jadwal tersebut yang bisa membacanya.
  - Memproses check-in kuantitas berbasis `lockForUpdate` dalam `DB::transaction()`. Memastikan akumulasi jumlah hadir tidak pernah melebihi total kuota yang dipesan. Mengubah status kehadiran (`attendance_status`) menjadi `'hadir'` otomatis ketika total kehadiran rombongan lengkap.

### 5. Script Testing
- **[test-api.php](file:///c:/Development/kelana-v2.0/app_build/test-api.php)**
  - Ditambahkan kasus uji `23` hingga `29` yang mensimulasikan seluruh alur fitur baru dan pengujian proteksi rute.

---

## 🧪 Hasil Pengujian (Test Results)

Skenario uji otomatis berjalan sukses dengan log keluaran sebagai berikut:

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

--------------------------------------------------
   PENGUJIAN TIKET DIGITAL & MANIFES CHECK-IN    
--------------------------------------------------

[23] Customer melihat tiket digital untuk booking TRIP-20260609-7724...
✅ BERHASIL (HTTP 200 OK): Detail tiket berhasil dimuat.
   Paket Wisata : Trip Bromo Midnight
   Jumlah Hadir : 0 / 2

[24] Melakukan Login Trip Leader...
✅ BERHASIL: Login Trip Leader sukses. Token didapatkan.

[25] Trip Leader melihat manifes untuk Jadwal Trip ID 1...
✅ BERHASIL (HTTP 200 OK): Manifes berhasil dimuat. Jumlah booking PAID: 1

[26] Trip Leader memproses check-in (1 orang) untuk booking TRIP-20260609-7724...
✅ BERHASIL (HTTP 200 OK): Check-in berhasil. Hadir sekarang: 1 / 2

[27] Trip Leader memproses check-in berlebih (2 orang lagi) untuk booking TRIP-20260609-7724 (Kuota tersisa 1)...
✅ BERHASIL (HTTP 422 Unprocessable Entity): Sistem menolak check-in melebihi kuota.
   Message: Jumlah check-in melebihi total kuota tiket.

[28] Trip Leader memproses check-in sisa kuota (1 orang) untuk booking TRIP-20260609-7724...
✅ BERHASIL (HTTP 200 OK): Check-in sisa kuota sukses. Status Kehadiran: hadir

[29] Mencoba melakukan check-in menggunakan token Customer...
✅ BERHASIL (HTTP 403 Forbidden): Rute check-in terproteksi dari Customer dengan benar!

==================================================
             PENGUJIAN SELESAI
==================================================
```

---

## 🚀 Langkah Selanjutnya (Next Steps)
1. Lakukan penggabungan (*merge*) Pull Request ke branch utama `main`.
2. Mulai Phase 5: Implementasi Modul Admin Back-office & Cetak Laporan PDF Rekap Peserta via `laravel-dompdf`.
