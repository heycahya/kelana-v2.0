# 🤝 Handoff Document - Kelana v2.0
**Feature:** Customer Registration API
**Issue Reference:** [#3 (https://github.com/heycahya/kelana-v2.0/issues/3)](https://github.com/heycahya/kelana-v2.0/issues/3)
**Branch:** `feat/register-user`
**Status:** Completed & Verified ✅

---

## 📋 Ringkasan Pekerjaan
Telah diimplementasikan fitur API Registrasi untuk tipe user **Customer** sesuai dengan rancangan spesifikasi kamus data database. API ini menerima pendaftaran customer, memvalidasi input, melakukan enkripsi password, mengecek duplikasi email, dan menyimpan data ke database MySQL.

---

## 🛠️ Perubahan Berkas (File Changes)

### 1. Berkas Baru (New Files)
- **`app_build/app/Models/Customer.php`**
  - Model Eloquent untuk tabel `customers` dengan konfigurasi custom primary key (`id_customer`) dan attributes mapping.
- **`app_build/database/migrations/0001_01_01_000003_create_customers_table.php`**
  - Berkas migrasi database untuk membuat tabel `customers` yang bersesuaian dengan struktur database MySQL.
- **`app_build/app/Http/Controllers/Api/AuthController.php`**
  - Controller API yang bertugas memvalidasi request body, mengenkripsi password dengan `bcrypt`, memeriksa keunikan email, dan merespons dengan JSON terformat.
- **`app_build/routes/api.php`**
  - Berkas routing khusus untuk API. Mendaftarkan endpoint `POST /v1/auth/register`.

### 2. Berkas Modifikasi (Modified Files)
- **`app_build/bootstrap/app.php`**
  - Mendaftarkan berkas routing `routes/api.php` agar dapat diproses dengan prefix default `/api` oleh Laravel 11/12/13.

---

## 🧪 Hasil Pengujian (Test Results)

### 1. Migrasi Database
Migrasi berjalan sukses tanpa hambatan:
```bash
0001_01_01_000003_create_customers_table .... DONE
```

### 2. Registrasi Sukses (HTTP 201)
Request valid berhasil diproses dan mengembalikan response JSON yang bersih (password disembunyikan):
- **Request Body:**
  ```json
  {
      "nama_customer": "Budi Santoso",
      "email": "budi.santoso@example.com",
      "password": "PasswordRahasia123!",
      "no_telp": "081234567890",
      "alamat": "Jl. Merdeka No. 45"
  }
  ```
- **Response Success (201 Created):**
  ```json
  {
      "status": "success",
      "message": "Registrasi berhasil.",
      "data": {
          "id_customer": 1,
          "nama_customer": "Budi Santoso",
          "email": "budi.santoso@example.com"
      }
  }
  ```

### 3. Validasi Duplikasi Email (HTTP 400)
Ketika email yang sama didaftarkan kembali, sistem mengembalikan validasi gagal dan menolak input:
- **Response Error (400 Bad Request):**
  ```json
  {
      "status": "error",
      "message": "Validasi gagal",
      "errors": {
          "email": "Email sudah terdaftar."
      }
  }
  ```

---

## 🚀 Langkah Selanjutnya (Next Steps)
1. Lakukan *pull request* (PR) untuk menggabungkan branch `feat/register-user` ke branch utama.
2. Lanjutkan siklus perencanaan baru untuk fitur berikutnya seperti:
   - API Login untuk Customer/Admin/Trip Leader.
   - Manajemen destinasi dan rincian paket wisata (CRUD `paket_wisata`).
