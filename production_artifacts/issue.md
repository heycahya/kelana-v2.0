# Issue: Implementasi API Pemesanan & Integrasi Midtrans Sandbox (Role Customer)

## 1. Update Dokumentasi & Security
- **Update `draft_perancangan.md`**: Tambahkan spesifikasi bahwa sistem menggunakan Midtrans dalam mode **Sandbox** untuk sistem pemesanan.
- **Update `.gitignore`**: Pastikan file `.env` sudah terdaftar secara valid di `.gitignore` untuk mencegah kebocoran data sensitif. Semua credential Midtrans (`MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, dll) **wajib** ditaruh di dalam file `.env`.

## 2. API Pemesanan (POST `/api/v1/pemesanan`)
- **Rute**: Daftarkan endpoint `POST /pemesanan` di dalam `routes/api.php` dengan prefix `v1`. Lindungi rute ini dengan middleware `auth:sanctum` dan pastikan hanya dapat diakses oleh *guard* atau *role* `customer`.
- **Controller**: 
  - Buat `app/Http/Controllers/Api/Customer/PemesananController.php`.
  - Buat method `store(Request $request)`.
- **Validasi Input**:
  - `id_jadwal`: `required|exists:jadwal_trip,id_jadwal` (Sesuaikan nama primary key `jadwal_trip`).
  - `jumlah_peserta`: `required|integer|min:1`.

## 3. Low-Level Logic (DB Transaction)
Semua proses database query (Insert/Update) **wajib** dibungkus di dalam `DB::transaction()` untuk mencegah inkonistensi data bila proses gagal di tengah jalan.

1. **Cek Ketersediaan Kuota (tabel `jadwal_trip`)**:
   - Ambil data jadwal trip menggunakan `id_jadwal` dari request (gunakan `lockForUpdate()` jika memungkinkan).
   - Validasi ketersediaan: pastikan `sisa_kuota >= jumlah_peserta`.
   - Jika kurang, lemparkan error response JSON (HTTP 422):
     ```json
     {
       "status": "error",
       "message": "Kuota tidak mencukupi"
     }
     ```

2. **Kalkulasi**:
   - `total_harga = jumlah_peserta * harga paket_wisata`. (Ambil harga paket wisata dari relasi jadwal ke tabel paket).

3. **Generate Booking Code**:
   - Generate `booking_code` unik. Format yang direkomendasikan: `TRIP-YYYYMMDD-XXXX` (contoh: `TRIP-20231024-0001`).

4. **Insert tabel `pemesanan`**:
   - Lakukan insert ke tabel `pemesanan` dengan data setidaknya mencakup:
     - `booking_code` = kode unik.
     - `id_customer` = ID user yang login (`Auth::id()`).
     - `id_jadwal` = `$request->id_jadwal`.
     - `jumlah_peserta` = `$request->jumlah_peserta`.
     - `total_harga` = `$total_harga`.
     - `status_pembayaran` (atau `status_pemesanan`) = `'PENDING'`.

5. **Insert tabel `pembayaran`**:
   - Lakukan insert untuk data awal pembayaran:
     - `id_pemesanan` = ID dari data pemesanan yang baru di-insert.
     - `jumlah_bayar` = `$total_harga`.

6. **Integrasi Midtrans (Sandbox)**:
   - Pastikan konfigurasi diset di mode sandbox:
     ```php
     \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
     \Midtrans\Config::$isProduction = false;
     \Midtrans\Config::$isSanitized = true;
     \Midtrans\Config::$is3ds = true;
     ```
   - Siapkan array parameter:
     - `transaction_details`: `order_id` (isi dengan `booking_code`), `gross_amount` (isi dengan `$total_harga`).
     - `customer_details`: isi dengan nama, email, no_hp dari user yang login (`Auth::user()`).
   - Generate snap token dengan perintah: `$snapToken = \Midtrans\Snap::getSnapToken($params);`.

7. **Update tabel `pembayaran`**:
   - Update baris `pembayaran` yang baru di-insert tadi dengan menyimpan `$snapToken` ke field `snap_token` (pastikan kolom ini ada di database).

8. **Kurangi Kuota**:
   - Lakukan update nilai `sisa_kuota` di tabel `jadwal_trip` sehingga sisa_kuota berkurang sesuai `jumlah_peserta`.

## 4. Format Response
Jika transaksi di atas berhasil disimpan hingga commit, kembalikan HTTP `201 Created` dengan standard JSON response berikut:

**Sukses (HTTP 201)**:
```json
{
  "status": "success",
  "message": "Pemesanan berhasil dibuat.",
  "data": {
    "booking_code": "TRIP-YYYYMMDD-XXXX",
    "total_harga": 1500000,
    "snap_token": "xxxx-xxxx-xxxx-xxxx"
  }
}
```

## Catatan Eksekutor (@engineer-coder):
- Pastikan seluruh tabel/relasi dan field sesuai dengan struktur `draft_perancangan.md` terbaru.
- Setelah implementasi selesai dan sukses, tulis log/update progres di file `STATE.md`.
