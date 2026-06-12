# 🤝 Handoff Document - Kelana v2.0
**Features:** Ekspansi Arsitektur Database Enterprise (Kelana v2.0)
**Issue References:** 
- [#32 - Ekspansi Arsitektur Database Enterprise (Kelana v2.0)](https://github.com/heycahya/kelana-v2.0/issues/32)
**Pull Request:** https://github.com/heycahya/kelana-v2.0/pull/33
**Branch:** `feature/enterprise-database-expansion`
**Status:** Completed & Verified ✅

---

## 📋 Ringkasan Pekerjaan (Oleh AI Code Worker)
Telah diimplementasikan penambahan tabel dan relasi baru skala *enterprise* untuk mendukung fitur Peta Interaktif, Galeri Masonry, Upsell/Add-ons, Wishlist, Profil Trip Leader, dan Scan QR Code.

### Detail Fungsionalitas Modul:
1. **Modifikasi Tabel `paket_wisata`**:
   - Menambahkan kolom `latitude` dan `longitude` (nullable) setelah `fasilitas` untuk navigasi peta.
2. **Modifikasi Tabel `trip_leaders`**:
   - Menambahkan kolom `avatar` (nullable), `bio` (nullable), dan `rating_akumulatif` (default 5.0) untuk trust profile.
3. **Modifikasi Tabel `pemesanan`**:
   - Menambahkan kolom `qr_code_token` (unique nullable) and `total_biaya_addons` (default 0) setelah `total_harga`.
4. **Tabel Baru `paket_wisata_galleries`**:
   - Menyimpan gambar pendukung masonry grid dengan foreign key ke `paket_wisata` (`id_paket`) yang bersifat cascade delete.
5. **Tabel Baru `add_ons` & Pivot `pemesanan_addon`**:
   - Menyimpan add-on (nama, harga, deskripsi) dan relasi booking detail (pemesanan_id, add_on_id, kuantitas, subtotal).
6. **Tabel Baru `wishlists`**:
   - Menyimpan relasi favorit customer (`id_customer` ke `id_paket`) dengan unique constraint agar tidak ada duplikasi data.
7. **Pembaruan Eloquent Relationships**:
   - Mendefinisikan seluruh relasi baru pada model `PaketWisata`, `Pemesanan`, `Customer`, `TripLeader`, `PaketWisataGallery`, `AddOn`, dan `Wishlist`.
   - Menyelaraskan attribute PHP `#[Fillable]` pada model dengan skema database yang diperbarui.

---

## 🛠️ Perubahan Berkas (File Changes)

### 1. Migrasi Database
- **`2026_06_12_163628_add_maps_to_paket_wisata_table.php`**
- **`2026_06_12_163630_add_profile_fields_to_trip_leaders_table.php`**
- **`2026_06_12_163631_add_qr_and_addons_to_pemesanan_table.php`**
- **`2026_06_12_163633_create_paket_wisata_galleries_table.php`**
- **`2026_06_12_163635_create_add_ons_table.php`**
- **`2026_06_12_163636_create_pemesanan_addon_table.php`**
- **`2026_06_12_163638_create_wishlists_table.php`**

### 2. Model Eloquent
- **`PaketWisataGallery.php`**
- **`AddOn.php`**
- **`Wishlist.php`**
- **`PaketWisata.php`** (update)
- **`TripLeader.php`** (update)
- **`Pemesanan.php`** (update)
- **`Customer.php`** (update)

---

## 🧪 Hasil Pengujian (Test Results)
Pengujian otomatis melalui `test-api.php` berjalan sukses 100% (50/50 test cases passed):
- Migrasi database fresh dan seeding berjalan lancar.
- Seluruh endpoint API dari auth, CRUD paket/jadwal, pemesanan, tiket digital, manifes, ulasan, katalog publik, hingga profil customer lolos tanpa regresi.
