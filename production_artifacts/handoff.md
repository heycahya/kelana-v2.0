# 🤝 Handoff & Progress Document - Kelana v2.0
**Features:** Customer Wishlist & Cart Pop-up System with AJAX Hardening
**Issue References:** #38
**Branch:** `feature/wishlist-cart-issue-38`
**Status:** Completed & Ready for Review ✅

---

## 📋 Summary of Work & Progress

We have successfully implemented and polished the Customer Wishlist and Cart Pop-up System for Kelana v2.0. The system features a slide-over drawer for the wishlist and a center modal for pending orders (cart) built on top of Laravel and Alpine.js. 

We also hardened the AJAX state synchronization to handle network/database connection errors gracefully without breaking the UI.

### 1. Wishlist Slide-Over Drawer
*   **Trigger**: Linked to the Heart icon in the Customer Navbar.
*   **Design**: Rendered on a Warm Cream (`#f4f3ed`) sliding panel that transitions smoothly from the right edge (`translate-x-full` to `translate-x-0`).
*   **Dynamic Actions**:
    *   Saves/unsaves catalog items instantly.
    *   Replaces the heart status to a filled Lush Forest Green color dynamically using a global event system.
    *   Removes items directly inside the drawer using a delete trash icon with real-time UI updates.

### 2. Cart / Pending Booking Center Modal
*   **Trigger**: Linked to the Shopping Bag icon in the Customer Navbar.
*   **Design**: Centered overlay modal utilizing Flat aesthetics (no box shadows) and uniform `rounded-[26px]` corners.
*   **Logic**:
    *   **Empty State**: Displays a custom travel-bag illustration and a CTA redirecting back to catalog navigation if no pending order exists.
    *   **Active State**: Displays the current `Holding Order` with itinerary departure, group participant count, total calculated cost, a primary "Lanjutkan Pembayaran" button (re-opens Midtrans Snap), and a secondary "Batalkan Pesanan" button (releases reserved seats and cancels the hold).

### 3. AJAX State Hardening (Connection Refused Fix)
*   **The Problem**: If the local database is down (e.g., `SQLSTATE[HY000] [2002] MySQL connection refused`), Laravel throws a 500 error. The AJAX handlers parsed this JSON/HTML exception payload directly and populated `this.cartItem` as a truthy object, causing the cart modal to display `undefined Pax` and `Rp NaN`.
*   **The Fix**:
    *   Ensured the API responses are validated using `res.ok` before attempting JSON translation.
    *   Verified that the parsed payload contains valid properties (e.g., `id_pemesanan` for the Cart) before assigning the state.
    *   Hardened catch blocks to fallback gracefully to `null`/empty values.

---

## 🛠️ File Changes List

*   **`app_build/app/Http/Controllers/Customer/WishlistWebController.php`**
    *   Manages listing, adding, and removing wishlist records mapped to the authenticated customer database.
*   **`app_build/app/Http/Controllers/Customer/CartWebController.php`**
    *   Serves the latest `PENDING` booking hold as the customer's cart, and processes cancellations (restores schedule capacity and marks payment as `FAILED`).
*   **`app_build/resources/views/components/customer-wishlist-cart.blade.php`**
    *   The core Alpine.js-powered wrapper containing the Wishlist Drawer and Cart Modal HTML overlays and AJAX synchronization routines.
*   **`app_build/resources/views/components/navbar.blade.php`**
    *   Integrates interactive Wishlist and Cart trigger actions, badge counters, and checks.
*   **`app_build/resources/views/welcome.blade.php`** & **`dashboard.blade.php`**
    *   Integrated card-level heart click triggers and includes the global drawer/modal component template.
*   **`app_build/resources/views/publik/detail.blade.php`**
    *   Integrated dynamic wishlist toggling on the main slider photo area.
*   **`app_build/routes/web.php`**
    *   Configured web endpoints for wishlist and cart actions under the `'auth:customer'` guard.
*   **`production_artifacts/STATE.md`** & **`production_artifacts/handoff.md`**
    *   Updated logs and checklists.

---

## 🧪 Skenario Pengujian untuk User (Testing Guide)

Silakan jalankan langkah-langkah berikut untuk menguji fitur Wishlist dan Cart:

1.  **Jalankan Lingkungan Lokal**:
    *   Jalankan server: `php artisan serve`
    *   Kompilasi aset CSS: `npm run dev`
2.  **Masuk Aplikasi**:
    *   Login ke URL `/login` menggunakan akun Customer default:
        *   **Email**: `budi.santoso@kelana.com`
        *   **Password**: `password`
3.  **Uji Fitur Wishlist (Slide-over Drawer)**:
    *   Klik **ikon Hati** di Navbar kanan. Pastikan laci meluncur halus dengan latar redup.
    *   Tutup laci, lalu klik **tombol Hati** di kartu katalog (Halaman Utama / Detail). Ikon hati akan berubah menjadi hijau pekat (`#1e5e3a`).
    *   Buka kembali laci Wishlist; pastikan item tersebut terdaftar di dalam laci.
    *   Klik ikon **Tempat Sampah** di dalam laci. Item akan terhapus seketika, dan ikon hati di katalog akan kembali kosong.
4.  **Uji Fitur Cart (Center Modal)**:
    *   Pergi ke halaman detail paket trip, pilih jadwal, lalu klik **"Book Now"**.
    *   Tutup pop-up snap Midtrans tanpa membayar (pesanan tersimpan dalam status `PENDING` / Hold).
    *   Klik **ikon Tas Belanja** di Navbar. Modal "Keranjang Pemesanan" akan terbuka di tengah layar.
    *   Pastikan detail pesanan (*paket*, *keberangkatan*, *jumlah peserta*, dan *total harga*) tampil sempurna.
    *   Klik **"Lanjutkan Pembayaran"** untuk menyelesaikan transaksi, atau **"Batalkan Pesanan"** untuk mengosongkan keranjang (kuota trip otomatis dikembalikan).
5.  **Uji Batas Toleransi Kesalahan (Robustness Test)**:
    *   Matikan database MySQL Anda (Laragon/XAMPP).
    *   Buka keranjang belanja. Keranjang harus mendeteksi respons error, menolak memprosesnya sebagai data valid, dan menampilkan **"Keranjang Anda masih kosong"** (Empty State) alih-alih menampilkan *undefined Pax / Rp NaN*.
