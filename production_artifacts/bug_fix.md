# 🐛 Bug Fix Ticket: Missing personal_access_tokens Table

## 📌 Deskripsi Masalah
Saat melakukan pengujian login, server mengembalikan error `500 Internal Server Error`. Berdasarkan analisis log [laravel.log](file:///c:/Development/kelana-v2.0/app_build/storage/logs/laravel.log), penyebab utamanya adalah:
`SQLSTATE[42S02]: Base table or view not found: 1146 Table 'kelana_v2.personal_access_tokens' doesn't exist`

## 🔍 Akar Masalah (Root Cause)
Laravel Sanctum memerlukan tabel `personal_access_tokens` untuk menyimpan token sesi API. Namun, berkas migrasi Sanctum belum dipublikasikan (*published*) ke direktori `database/migrations/` lokal, sehingga saat menjalankan `php artisan migrate:fresh --seed`, tabel tersebut tidak ikut dibuat.

## 🛠️ Langkah Solusi (Remediasi)
User perlu mempublikasikan file konfigurasi dan migrasi Sanctum, kemudian menjalankan ulang migrasi database:

1. **Publish Migrasi Sanctum:**
   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   ```

2. **Jalankan Ulang Migrasi & Seed:**
   ```bash
   php artisan migrate:fresh --seed
   ```
