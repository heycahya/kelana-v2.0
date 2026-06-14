<div align="center">
  <h1>🏕️ Kelana v2.0 - Enterprise Open Trip System</h1>
  <p><strong>Platform Manajemen Pariwisata Petualangan Terintegrasi</strong></p>
  <p>Dibangun dengan Laravel 11, Tailwind CSS (Siohioma Style), dan Alpine.js</p>
</div>

---

## 🚀 Tentang Proyek
Kelana v2.0 adalah sebuah platform *Enterprise Resource Planning* (ERP) dan Operasional Lapangan berbasis web yang dirancang khusus untuk memecahkan masalah pencarian informasi open trip wisata alam, keamanan transaksi, dan koordinasi lapangan bagi *solo traveler*.

Aplikasi ini tidak hanya berfungsi sebagai etalase pemesanan (B2C), tetapi juga menyediakan **Sistem Back-Office (Admin)** yang canggih dan **Aplikasi Lapangan (Trip Leader)** untuk *check-in* digital berbasis QR Code.

## 🛠️ Tech Stack & Infrastruktur
*   **Backend:** Laravel 11.x (PHP 8.2+), RESTful API via Sanctum.
*   **Frontend:** Laravel Blade SSR, Alpine.js (Reaktivitas), Tailwind CSS (Kustomisasi Tema Siohioma).
*   **Database:** MySQL 8.0+ (Relasional ketat dengan 13 Tabel terpadu).
*   **Integrasi Pihak Ketiga:**
    *   **Midtrans Snap:** Payment Gateway & Asynchronous Webhook (Sandbox).
    *   **Laravel DomPDF:** Generator laporan dan cetak E-Ticket.
    *   **HTML5-QRCode:** Kamera pemindai tiket digital berbasis browser.
    *   **Leaflet.js:** Peta interaktif destinasi wisata.

## ✨ Fitur Utama (Highlight)
1.  **Arsitektur Multi-Guard (3 Role):** Pemisahan akses absolut antara *Customer*, *Admin*, dan *Trip Leader* dengan satu gerbang masuk terpadu.
2.  **Siohioma Design System:** Antarmuka premium 100% *Flat Design* (tanpa shadow), sudut `rounded-26px`, warna *Forest Green*, dan tipografi *Figtree*.
3.  **Booking Engine & Midtrans Integration:** Alur *checkout* presisi dengan dukungan pemotongan kuota instan, kode promo dinamis, dan *Add-ons* perlengkapan.
4.  **Field Ops App (Mobile-First):** *Trip Leader* dapat menggunakan *smartphone* mereka untuk membuka manifes dan me-*scan* kode QR peserta langsung dari *browser*.
5.  **Smart Chat & Auto-Responder:** Sistem percakapan CS bawaan (Customer ↔ Admin ↔ Leader) yang dilengkapi bot notifikasi otomatis saat pembayaran lunas.

---

## ⚙️ Cara Instalasi (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan sistem Kelana v2.0 di komputer lokal Anda:

### 1. Kloning Repositori
```bash
git clone https://github.com/heycahya/kelana-v2.0.git
cd kelana-v2.0/app_build
```

### 2. Instalasi Dependensi
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
Salin file environment dan *generate app key*:
```bash
cp .env.example .env
php artisan key:generate
```
*(Catatan: Pastikan Anda telah mengatur kredensial database `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` dan API Keys Midtrans di dalam file `.env` Anda)*

### 4. Migrasi & Pengisian Data Awal (Seeding)
Bangun struktur tabel dan isi dengan data *dummy* premium (termasuk paket trip, admin, leader, dan customer):
```bash
php artisan migrate:fresh --seed
```

### 5. Kompilasi Aset Frontend
```bash
npm run build
```

### 6. Jalankan Server
```bash
php artisan serve
```
Buka browser Anda di: `http://localhost:8000`

---

## 🔑 Kredensial Pengujian (Data Seeder)

Untuk mencoba masing-masing modul, silakan login dengan akun berikut (Password untuk semua akun adalah: `password`):

| Role | Identitas Login | Tujuan Modul |
| :--- | :--- | :--- |
| **Admin ERP** | Username: `admin` | `/admin/dashboard` |
| **Trip Leader** | Email: `adi.wijaya@kelana.com` | `/trip-leader/dashboard` |
| **Customer** | Email: `budi.santoso@kelana.com` | `/dashboard` |

---
*Proyek ini dikembangkan menggunakan metodologi Solo Developer - Vibe Coding yang diorkestrasikan melalui framework arsitektur Golden Loop AI.*
