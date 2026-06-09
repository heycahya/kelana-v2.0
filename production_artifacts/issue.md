# Implementasi CRUD Master Paket Wisata (Admin Only)

## 1. Deskripsi Tugas
Melakukan implementasi RESTful API untuk fitur CRUD pada tabel `paket_wisata`. Endpoint ini merupakan bagian dari modul Back-Office dan bersifat tertutup, sehingga **hanya boleh diakses oleh pengguna dengan role Admin**.

## 2. Persiapan Model Database
**File Model:** `app/Models/PaketWisata.php`

Pastikan Model disesuaikan dengan struktur tabel:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketWisata extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'paket_wisata';

    // Menentukan primary key custom
    protected $primaryKey = 'id_paket';

    // Kolom yang dapat diisi melalui mass assignment
    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'harga',
        'rute',
        'fasilitas'
    ];
}
```

## 3. Spesifikasi Routing & Middleware
**File Route:** `routes/api.php`

Gunakan middleware `auth:sanctum` untuk memastikan pengguna terautentikasi. Karena Laravel Sanctum multi-role dalam proyek ini umumnya membedakan role lewat *token ability* atau *middleware custom*, pastikan terdapat check bahwa user adalah admin (contoh: middleware `ability:admin` atau `is_admin`).

```php
use App\Http\Controllers\Api\Admin\PaketManagementController;
use Illuminate\Support\Facades\Route;

// Prefix /api/v1/admin
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'ability:admin'])->group(function () {
    Route::get('/paket-wisata', [PaketManagementController::class, 'index']);
    Route::post('/paket-wisata', [PaketManagementController::class, 'store']);
    Route::get('/paket-wisata/{id}', [PaketManagementController::class, 'show']);
    Route::put('/paket-wisata/{id}', [PaketManagementController::class, 'update']);
    Route::delete('/paket-wisata/{id}', [PaketManagementController::class, 'destroy']);
});
```
*(Catatan untuk Coder: Sesuaikan alias middleware role admin dengan yang telah disepakati atau buat logic pengecekan role manual di Controller jika middleware khusus belum ada `if ($request->user()->role !== 'admin') abort(403);`)*

## 4. Spesifikasi Controller & Validasi
**File Controller:** `app/Http/Controllers/Api/Admin/PaketManagementController.php`

Gunakan `Illuminate\Support\Facades\Validator` atau *FormRequest* untuk validasi, dan kembalikan JSON Response konsisten pada setiap method.

### A. Tampilkan Semua Data (`index` method)
- **Tujuan:** Mendapatkan semua data master paket wisata.
- **Logika:** `PaketWisata::all()` atau `PaketWisata::orderBy('created_at', 'desc')->get()`.
- **Response Success (200 OK):**
```json
{
  "success": true,
  "message": "Data paket wisata berhasil diambil",
  "data": [
    {
      "id_paket": 1,
      "nama_paket": "Open Trip Bromo Midnight",
      "deskripsi": "Menikmati sunrise dari penanjakan.",
      "harga": "350000.00",
      "rute": "Malang -> Bromo -> Malang",
      "fasilitas": "Jeep, Tiket Masuk, Snack",
      "created_at": "2026-06-09T10:00:00.000000Z",
      "updated_at": "2026-06-09T10:00:00.000000Z"
    }
  ]
}
```

### B. Tambah Data Baru (`store` method)
- **Tujuan:** Menyimpan data paket wisata baru.
- **Aturan Validasi (`$request->validate(...)`):**
  - `nama_paket`: `required|string|max:150`
  - `deskripsi`: `required|string`
  - `harga`: `required|numeric|min:0`
  - `rute`: `required|string`
  - `fasilitas`: `required|string`
- **Response Validation Error (422 Unprocessable Entity):**
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "nama_paket": ["Kolom nama paket wajib diisi."],
    "harga": ["Kolom harga harus berupa angka."]
  }
}
```
- **Response Success (201 Created):**
```json
{
  "success": true,
  "message": "Data paket wisata berhasil ditambahkan",
  "data": {
    "id_paket": 2,
    "nama_paket": "...",
    "deskripsi": "...",
    "harga": "...",
    "rute": "...",
    "fasilitas": "...",
    "created_at": "...",
    "updated_at": "..."
  }
}
```

### C. Tampilkan Detail Data (`show` method)
- **Tujuan:** Menampilkan satu paket wisata berdasarkan `id_paket`.
- **Logika:** `PaketWisata::find($id)`.
- **Response Data Tidak Ditemukan (404 Not Found):**
```json
{
  "success": false,
  "message": "Data paket wisata tidak ditemukan",
  "data": null
}
```
- **Response Success (200 OK):**
```json
{
  "success": true,
  "message": "Detail paket wisata",
  "data": {
    "id_paket": 1,
    "nama_paket": "...",
    ...
  }
}
```

### D. Update Data (`update` method)
- **Tujuan:** Mengubah data paket wisata spesifik berdasarkan `id_paket`.
- **Logika:** Cari dengan `find($id)`. Jika null, kembalikan response 404. Jika ditemukan, lakukan validasi dan `update()`.
- **Aturan Validasi:** Sama dengan method `store`.
- **Response Data Tidak Ditemukan (404 Not Found):** (Format sama seperti method Show).
- **Response Validation Error (422 Unprocessable Entity):** (Format sama seperti method Store).
- **Response Success (200 OK):**
```json
{
  "success": true,
  "message": "Data paket wisata berhasil diperbarui",
  "data": {
    "id_paket": 1,
    "nama_paket": "Nama Update...",
    ...
  }
}
```

### E. Hapus Data (`destroy` method)
- **Tujuan:** Menghapus paket wisata dari database.
- **Logika:** Cari dengan `find($id)`. Jika null, kembalikan response 404. Jika ada, lakukan `delete()`.
- **Response Data Tidak Ditemukan (404 Not Found):** (Format sama seperti method Show).
- **Response Success (200 OK):**
```json
{
  "success": true,
  "message": "Data paket wisata berhasil dihapus",
  "data": null
}
```

## 5. Instruksi Khusus Untuk Coder
1. **Response Format Standard:** Harap buat struktur JSON yang seragam (`success`, `message`, `data`/`errors`). Anda bisa membuat *Helper* atau *Trait* jika diperlukan untuk menghasilkan response ini agar kodenya tetap DRY (*Don't Repeat Yourself*).
2. **Naming Convention:** Semua nama property di JSON disesuaikan dengan nama kolom tabel aslinya, jangan di-cast ke camelCase, tetapkan dengan `snake_case` (misalnya `nama_paket`).
3. **Penyimpanan:** Jangan mengeksekusi migration secara terpisah. Jika diperlukan, modifikasi langsung logic pada `PaketManagementController.php`.
