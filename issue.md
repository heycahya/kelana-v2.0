# Phase 8: Modul Profil Customer & Riwayat Pemesanan Komprehensif

## Deskripsi Tugas
Mengimplementasikan modul profil dan riwayat pemesanan untuk Customer yang telah terautentikasi. Tugas ini meliputi pembuatan endpoint API untuk melihat dan memperbarui profil, serta menyajikan data riwayat pemesanan yang secara logis dibagi menjadi dua kategori: 'Active Trips' dan 'Past Trips', dengan memuat data aktual kehadiran lapangan.

## 1. Registrasi Rute (routes/api.php)
Buka file `routes/api.php` dan tambahkan registrasi rute baru. Pastikan rute-rute ini berada di dalam grup middleware `auth:sanctum` dan middleware otorisasi role `customer`.

```php
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\PesananHistoryController;

Route::middleware(['auth:sanctum', 'customer'])->group(function () {
    // Manajemen Profil Customer
    Route::get('/customer/profile', [ProfileController::class, 'show']);
    Route::put('/customer/profile', [ProfileController::class, 'update']);

    // Riwayat Pemesanan Customer
    Route::get('/customer/pesanan-history', [PesananHistoryController::class, 'index']);
});
```

## 2. Implementasi ProfileController
Buat file controller baru di `app/Http/Controllers/Customer/ProfileController.php`.

### A. Method `show()`
- **Tujuan**: Menampilkan data profil customer yang sedang login.
- **Logika**:
  - Panggil `auth()->user()` untuk mendapatkan instance User.
  - Return JSON dengan status HTTP 200 yang berisikan detail user (seperti `id`, `name`, `email`, `phone`, dan `emergency_contact`).

### B. Method `update(Request $request)`
- **Tujuan**: Memperbarui informasi profil customer yang sedang login.
- **Aturan Validasi Input**:
  - `name`: `required|string|max:255`
  - `email`: `required|email|max:255|unique:users,email,' . auth()->id()` (Wajib ada proteksi exclude ID user saat ini agar validasi unique tidak gagal jika email tidak diubah, serta mencegah bentrok dengan user lain).
  - `phone`: `nullable|string|regex:/^[0-9]+$/|min:10|max:15` (Validasi format angka agar tidak berisi karakter aneh).
  - `emergency_contact`: `nullable|string|max:255`
- **Logika Eksekusi**:
  1. Validasi request menggunakan `$request->validate([...])`.
  2. Dapatkan instance user saat ini: `$user = auth()->user();`.
  3. Lakukan pembaruan data: `$user->update($validatedData);`.
  4. Return JSON response HTTP 200, contoh message: `"Profil berhasil diperbarui"`, beserta data profil terbaru.

## 3. Implementasi PesananHistoryController
Buat file controller baru di `app/Http/Controllers/Customer/PesananHistoryController.php`.

### Method `index()`
- **Tujuan**: Menampilkan seluruh riwayat transaksi customer, dengan pembagian data `active_trips` dan `past_trips`.
- **Relasi (Eager Loading)**:
  Wajib melakukan query `with()` pada relasi terkait untuk mengurangi masalah N+1 query. Contoh relasi yang di-load: `jadwal` (Jadwal Trip) dan relasi `trip`/`paket_trip` untuk menampikan nama dan detail tour.
- **Logika Query Database**:
  1. Siapkan identifier customer: `$customerId = auth()->id();`.
  2. **Active Trips**:
     Lakukan query ke tabel Pemesanan (misal: model `Booking` atau `Pemesanan`).
     - Filter: `where('customer_id', $customerId)`.
     - Filter status: `where('status_pembayaran', 'PAID')`.
     - Filter conditional date menggunakan relasi jadwal: 
       Gunakan `whereHas('jadwal', function($q) { ... })` dengan kondisi `tanggal_mulai >= now()->toDateString()` DAN status_perjalanan bukan 'Selesai'.
  3. **Past Trips**:
     Lakukan query ke tabel Pemesanan yang sama.
     - Filter: `where('customer_id', $customerId)`.
     - Filter conditional date: 
       Gunakan `whereHas('jadwal', function($q) { ... })` dengan kondisi `tanggal_mulai < now()->toDateString()` ATAU `status_perjalanan = 'Selesai'`. (Perhatikan pengelompokan kondisi `orWhere` di dalam scope query).
- **Pembentukan Response Data**:
  Objek pemesanan harus memuat secara transparan total kuota rombongan dan kolom `jumlah_hadir` yang diinputkan oleh Trip Leader (hasil pengerjaan Phase 4). Response kembalian harus dipisahkan kuncinya seperti berikut:

```json
{
    "success": true,
    "data": {
        "active_trips": [
            {
                "id": 101,
                "trip_name": "Pendakian Rinjani",
                "tanggal_mulai": "2026-10-15",
                "status_pembayaran": "PAID",
                "kuota_rombongan": 5,
                "jumlah_hadir": 0,
                "status_perjalanan": "Menunggu"
            }
        ],
        "past_trips": [
            {
                "id": 87,
                "trip_name": "Open Trip Bromo",
                "tanggal_mulai": "2026-05-10",
                "status_pembayaran": "PAID",
                "kuota_rombongan": 3,
                "jumlah_hadir": 3,
                "status_perjalanan": "Selesai"
            }
        ]
    }
}
```

## Aturan Eksekusi untuk @engineer-coder
- Pastikan kode ditulis dengan DRY logic dan menggunakan best practices standar Laravel.
- Gunakan nama field relasi/kolom eksisting yang sesuai dengan database `kelana-v2.0` (jika nama model adalah `Booking`, sesuaikan dengan nama class `Booking`).
- Setelah selesai, pastikan Anda memperbarui log pada file `STATE.md`.
