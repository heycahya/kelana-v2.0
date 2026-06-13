# Rombak Total Enterprise Checkout Flow & Perbaikan Data Sistem

Terdapat dua kelompok masalah pada alur reservasi saat ini: (1) UI Checkout yang tidak profesional dan (2) Logika kalkulasi Add-ons serta pelabelan identitas produk yang keliru. Anda ditugaskan untuk mengeksekusi kedua perbaikan ini secara serentak.

**🚨 ATURAN WAJIB UI (SOURCE OF TRUTH) 🚨**
Tetap patuhi *Forest Green Nature Theme* (`design.md`), sudut `rounded-[26px]`, dan hindari penggunaan `box-shadow` bawaan Tailwind.

## 🎯 BAGIAN 1: Rombak Arsitektur Layout Checkout UI
Buka file `resources/views/customer/booking.blade.php`. Hapus *layout* sempit terpusat yang lama, ganti menjadi layout lebar (`max-w-7xl mx-auto px-4 py-8`).

### A. Secure Checkout Header & Progress Stepper
- Buat *header* minimalis: Logo Kelana di kiri, teks "Secure Checkout" (dengan ikon gembok) di kanan.
- **Visual Progress Stepper**: Pelacak progres horizontal di bawah header:
  - `[1] Detail Pesanan` (Status Aktif: Titik `Forest Green`, teks tebal).
  - `[2] Pembayaran` (Status Inaktif: Titik & teks abu-abu).
  - `[3] E-Ticket` (Status Inaktif: Titik & teks abu-abu).

### B. Split Layout (2/3 Kiri, 1/3 Kanan)
Gunakan grid CSS (`grid grid-cols-1 lg:grid-cols-12 gap-8`):
- **KOLOM KIRI (Detail Form - `lg:col-span-8`)**
  - **Card 1: Detail Pemesan**: Kotak putih statis menampilkan Nama, Email, dan No HP *Customer* yang sedang *login*.
  - **Card 2: Peserta & Add-ons**: Integrasikan *stepper* jumlah peserta (Pax) dan opsi *Add-ons* (jika ada).
  - **Card 3: Special Request**: Textarea opsional untuk catatan medis/alergi.
- **KOLOM KANAN (Order Summary - `lg:col-span-4`)**
  - Buat **Sticky Summary Card** (menempel saat di-scroll). Menampilkan *thumbnail* trip, Judul, Tanggal, dan Kuota.
  - **Struk Kalkulasi**: Tampilkan breakdown `[Harga Tiket x Pax]`, `[Total Add-ons]`, dan `[Total Bayar Akhir]`.
  - **Tombol Aksi**: Tombol hijau raksasa **"Lanjut ke Pembayaran"** (memanggil fungsi Snap Midtrans). Letakkan teks kecil *"Pembayaran 100% aman"* di bawah tombol.

---

## 🎯 BAGIAN 2: Perbaikan Logika Sistem & Identitas

### A. Logika Kalkulasi Add-ons & Midtrans (Backend)
- Di dalam metode pembuatan pemesanan (`BookingWebController` atau `PemesananController`), pastikan logika menangkap pilihan *Add-ons*.
- `total_biaya_addons` = Penjumlahan (Harga Add-on × Kuantitas).
- *Gross Amount* Midtrans WAJIB = `(Harga Tiket × Jumlah Peserta) + total_biaya_addons`.
- Pastikan data terekam di pivot table `pemesanan_addon`.

### B. Koreksi Identitas Produk (Bukan Marketplace)
- Buka `resources/views/publik/detail.blade.php`.
- Hapus semua teks yang melabeli profil orang sebagai "Penyedia Jasa", "Diselenggarakan Oleh", atau "Host". Ini memberi impresi Kelana adalah *marketplace* lepas.
- Ganti label profil secara tegas menjadi: **"Kelana Certified Trip Leader"** atau **"Pemandu Perjalanan Anda"**. 

### C. Refactoring Data Dummy (Seeder)
- Buka `database/seeders/PaketWisataSeeder.php` dan `JadwalTripSeeder.php`.
- Rapikan data *dummy*. Gunakan *copywriting* elegan yang menegaskan bahwa paket ini adalah "Produk Eksklusif Kelana". Pastikan nama-nama Trip Leader terdengar profesional (e.g., "Bima - Senior Guide Kelana").

### Aturan Pelaporan Akhir
Di laporan akhir (STATE.md/Handoff), **WAJIB** laporkan bahwa total tagihan Midtrans kini sudah akurat memperhitungkan nilai *Add-ons* dan instruksikan *user* untuk mengetes halaman Checkout baru.
