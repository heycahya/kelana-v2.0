# 📝 Instruksi Kerja: Modul Admin Back-office & Cetak Laporan PDF Rekap Peserta (Phase 5)

## 📌 Konteks Fitur
1. **Back-office Admin**: Admin membutuhkan fitur back-office untuk mengelola dan memantau rekap operasional trip yang berjalan. Salah satu fitur krusialnya adalah mencetak manifes final peserta.
2. **Cetak PDF Manifes**: Diperlukan endpoint khusus Admin untuk mengunduh laporan rekap peserta per jadwal trip dalam bentuk dokumen PDF. Untuk pembuatan PDF, kita akan menggunakan library `barryvdh/laravel-dompdf` yang sudah terpasang.
3. **Konten Dokumen PDF**: PDF yang dihasilkan wajib memuat:
   - Detail paket wisata (Nama Paket).
   - Informasi jadwal trip (Tanggal Keberangkatan & Nama Trip Leader).
   - Statistik total pendapatan (akumulasi transaksi berhasil/PAID).
   - Tabel manifes seluruh peserta yang pemesanannya berstatus lunas (`PAID`).

---

## 🏗️ Arsitektur Teknis

### 1. Pemetaan Rute API
Tambahkan rute baru di `routes/api.php` di bawah prefix `v1`. Rute ini harus dilindungi menggunakan middleware autentikasi dan otorisasi khusus admin.

**Rute:**
- `GET /api/v1/admin/laporan/rekap-peserta/{id_jadwal}`

**Middleware:**
- `auth:sanctum`
- `admin` (alias dari `EnsureUserIsAdmin`)

### 2. Rancangan Controller
Buat Controller baru `app/Http/Controllers/Api/Admin/LaporanController.php`.

**Tugas utama fungsi `downloadRekapPeserta($id_jadwal)`:**
- Lakukan validasi keberadaan data `JadwalTrip` berdasarkan `$id_jadwal`. Jika tidak ada, kembalikan response 404.
- Lakukan *Eager Loading* untuk merelasikan tabel `PaketWisata` dan `TripLeader` yang terkait dengan `JadwalTrip` tersebut.
- Ambil semua data dari tabel `Pemesanan` yang berelasi dengan `$id_jadwal` di mana `status_pembayaran` bernilai `'PAID'`. Lakukan *Eager Loading* ke relasi user/customer jika diperlukan untuk menampilkan nama pemesan.
- Hitung total pendapatan (Total Revenue) dengan menjumlahkan kolom `total_harga` pada semua pemesanan yang `'PAID'` tersebut.
- Gunakan facade `Pdf` dari library dompdf untuk memuat view Blade dan meneruskan data variabel (jadwal, paket, trip leader, pemesanan, total pendapatan).
- Kembalikan file PDF sebagai response `download` (contoh: `return $pdf->download('Rekap_Peserta_Trip_'.$id_jadwal.'.pdf');`).

### 3. Desain Template Blade untuk PDF
Buat file template view baru untuk mencetak laporan PDF di `resources/views/pdf/rekap-peserta.blade.php`.

**Struktur View Blade:**
- Harus menggunakan tag HTML dasar dan styling inline atau CSS dasar yang didukung oleh dompdf.
- **Kop Dokumen/Header**: Menampilkan judul "Laporan Rekap Peserta Trip".
- **Bagian Informasi Trip**: 
  - Nama Paket Wisata
  - Tanggal Keberangkatan
  - Nama Trip Leader
- **Bagian Statistik**:
  - Total Peserta (PAID)
  - Total Pendapatan (Format Rupiah)
- **Bagian Tabel Manifes**:
  - Kolom tabel: No, Kode Booking, Nama Pemesan, Jumlah Peserta (Kuantitas tiket), Jumlah Hadir, Status.
  - Lakukan *looping* (foreach) data pemesanan yang diteruskan dari Controller.

---

## 🚀 Langkah Pengerjaan (@engineer-coder)
1. **Membaca Instruksi**: Pahami struktur arsitektur yang dirancang di atas.
2. **Pembuatan File View**: Buat direktori `resources/views/pdf` dan file `rekap-peserta.blade.php`, lalu buat layout HTML sederhana dan rapi.
3. **Pembuatan Controller**: Buat `LaporanController.php` beserta logic penarikan data dan perhitungannya, serta hubungkan dengan library DOMPDF (`use Barryvdh\DomPDF\Facade\Pdf;`).
4. **Registrasi Route**: Update `routes/api.php` dengan menambahkan route khusus Admin untuk fitur laporan.
5. **Testing/Verifikasi**: Pastikan melakukan update file testing jika diperlukan atau siapkan script pengujian terminal yang mendemonstrasikan hasil cetak PDF lewat respons HTTP (atau verifikasi bahwa endpoint merespons dengan HTTP status 200 dan header `application/pdf`).
6. **Update STATE.md**: Setelah semua selesai, tulis update pada `STATE.md` di direktori `production_artifacts/`.

Selamat bekerja!
