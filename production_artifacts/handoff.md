# 🤝 Handoff Document - Kelana v2.0
**Features:** Customer Registration API & Multi-Role Authentication API
**Issue References:** 
- [#3 - Register Customer API](https://github.com/heycahya/kelana-v2.0/issues/3)
- [#5 - Multi-Role Login API](https://github.com/heycahya/kelana-v2.0/issues/5)
- [#7 - Bug: Missing Sanctum migrations](https://github.com/heycahya/kelana-v2.0/issues/7)
**Branch:** `feat/register-user`
**Status:** Completed & Verified ✅

---

## 📋 Ringkasan Pekerjaan
Telah diimplementasikan fitur API Registrasi untuk tipe user **Customer** serta fitur API Login **Multi-Role** untuk tiga tipe aktor utama: **Admin**, **Customer**, dan **Trip Leader**. Seluruh sistem sesi API diamankan menggunakan token dari **Laravel Sanctum**.

---

## 🛠️ Perubahan Berkas (File Changes)

### 1. Model Baru & Terupdate
- **[Customer.php](file:///c:/Development/kelana-v2.0/app_build/app/Models/Customer.php)**
  - Ditambahkan trait `HasApiTokens` dan diubah agar meng-extends `Authenticatable`.
- **[Admin.php](file:///c:/Development/kelana-v2.0/app_build/app/Models/Admin.php)**
  - Model baru untuk tabel `admins` dengan primary key `id_admin` dan trait `HasApiTokens`.
- **[TripLeader.php](file:///c:/Development/kelana-v2.0/app_build/app/Models/TripLeader.php)**
  - Model baru untuk tabel `trip_leaders` dengan primary key `id_leader` dan trait `HasApiTokens`.

### 2. Migrasi Database Baru
- **[0001_01_01_000004_create_admins_table.php](file:///c:/Development/kelana-v2.0/app_build/database/migrations/0001_01_01_000004_create_admins_table.php)**
  - Skema tabel `admins` untuk login administrator.
- **[0001_01_01_000005_create_trip_leaders_table.php](file:///c:/Development/kelana-v2.0/app_build/database/migrations/0001_01_01_000005_create_trip_leaders_table.php)**
  - Skema tabel `trip_leaders` untuk petugas lapangan.
- **2026_06_09_143751_create_personal_access_tokens_table.php**
  - Skema tabel bawaan Laravel Sanctum untuk mencatat log token API.

### 3. Controller & Routing
- **[AuthController.php](file:///c:/Development/kelana-v2.0/app_build/app/Http/Controllers/Api/AuthController.php)**
  - Ditambahkan fungsi `login` yang secara berurutan memeriksa kredensial di tabel `admins` (menggunakan username), `customers` (menggunakan email), dan `trip_leaders` (menggunakan email).
- **[api.php](file:///c:/Development/kelana-v2.0/app_build/routes/api.php)**
  - Ditambahkan route `POST /api/v1/auth/login`.

### 4. Database Seeder
- **[DatabaseSeeder.php](file:///c:/Development/kelana-v2.0/app_build/database/seeders/DatabaseSeeder.php)**
  - Diperbarui untuk meng-seed data dummy awal untuk ketiga role pengguna agar langsung siap ditest.

---

## 🧪 Hasil Pengujian (Test Results)

### 1. Migrasi Database & Seeding
```bash
Dropping all tables .................................... DONE
Running migrations.
  0001_01_01_000003_create_customers_table ............. DONE
  0001_01_01_000004_create_admins_table ................ DONE
  0001_01_01_000005_create_trip_leaders_table .......... DONE
  2026_06_09_143751_create_personal_access_tokens_table . DONE
Seeding database.
```

### 2. Login Admin (HTTP 200 OK)
- **Response Success:**
  ```json
  {
      "status":  "success",
      "message":  "Login berhasil.",
      "data":  {
                   "token":  "1|hllXKElfUGBu6YG4kAHd1HWby4zoJl3i1hr7ovHr1ebc89a6",
                   "user":  {
                                "id":  1,
                                "nama":  "Admin Kelana",
                                "role":  "admin"
                            }
               }
  }
  ```

### 3. Login Customer (HTTP 200 OK)
- **Response Success:**
  ```json
  {
      "status":  "success",
      "message":  "Login berhasil.",
      "data":  {
                   "token":  "2|jRreQOSw0KP7LHU1OzQhRgnmiHIJhbpVWCaC7SbRde554319",
                   "user":  {
                                "id":  1,
                                "nama":  "Budi Santoso",
                                "role":  "customer"
                            }
               }
  }
  ```

### 4. Login Trip Leader (HTTP 200 OK)
- **Response Success:**
  ```json
  {
      "status":  "success",
      "message":  "Login berhasil.",
      "data":  {
                   "token":  "3|8DuONSm3ugVgBk3bA7GwVCpAhUEc0qF2oNmuZdYd8ad4392d",
                   "user":  {
                                "id":  1,
                                "nama":  "Adi Wijaya",
                                "role":  "trip_leader"
                            }
               }
  }
  ```

### 5. Login Gagal (HTTP 401 Unauthorized)
- **Response Error:**
  ```json
  {
      "status": "error",
      "message": "Email/Username atau password tidak valid."
  }
  ```

---

## 🚀 Langkah Selanjutnya (Next Steps)
1. Gabungkan (*merge*) seluruh pekerjaan ini ke branch utama dengan membuat Pull Request menggunakan GitHub CLI.
2. Lanjutkan siklus perencanaan baru untuk fitur berikutnya seperti:
   - CRUD data paket wisata dan pencarian destinasi wisata.
