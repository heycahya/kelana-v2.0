# Issue: Rombak Total Landing Page (Kelana Premium Flow)

## Deskripsi Tugas
Anda ditugaskan sebagai @engineer-coder untuk mengeksekusi perombakan total UI pada halaman utama (`resources/views/welcome.blade.php`). Ikuti semua spesifikasi low-level di bawah ini secara presisi. Hindari penulisan CSS kustom jika bisa diselesaikan dengan utility classes Tailwind CSS.

## 🚨 ATURAN WAJIB (SOURCE OF TRUTH) 🚨
1. **Warna & Gaya Dasar**: WAJIB menggunakan palet *Forest Green Nature Theme*:
   - Hijau Utama: `#1e5e3a`
   - Gelap: `#0f1a15`
   - Latar Terang/Cream: `#f4f3ed`
   - Sangat Gelap (Midnight): `#0b1611`
2. **Eksekusi Flat & Modern**: Radius sudut harus ekstrim menggunakan `rounded-[26px]`. **DILARANG** menggunakan properti `shadow` atau `box-shadow` bawaan Tailwind. Semua elemen harus terlihat flat.
3. **Logika Halaman Universal**: Pada Card Destinasi, saat diklik (dibungkus tag `<a>` atau `<button>`), terapkan logika pengecekan auth: 
   ```blade
   @if(Auth::guard('customer')->check())
       {{-- Arahkan ke rute Detail Trip --}}
   @else
       {{-- Lempar paksa ke rute Login --}}
   @endif
   ```

---

## 🎯 Arsitektur UI & Instruksi Eksekusi (Atas ke Bawah)

### 1. Immersive Hero Section & Floating Navbar
- **Navbar**: 
  - Gunakan `absolute` atau `fixed`, `w-full`, `z-50`, posisikan `top-0`. 
  - Pastikan Navbar berada *PALING ATAS* melayang di atas gambar Hero. 
  - Background Navbar transparan saat di atas, dan gunakan `x-data` Alpine.js untuk mendeteksi event scroll window (`@scroll.window`). Ubah background menjadi pekat `#0f1a15` saat scroll.
- **Hero**:
  - Bungkus dalam container dengan `min-h-[80vh]`, `relative`, `flex items-center justify-center`.
  - Gunakan background image penuh (cari placeholder gambar alam/misty mountains atau pesawat).
  - Teks: Tipografi raksasa diletakkan di tengah.
  - **Search Bar**: Di bawah teks raksasa, buat bilah pencarian bergaya kapsul Tripadvisor (`rounded-full bg-white p-2 flex items-center max-w-2xl w-full`). Berisi input text dan tombol "Search" kapsul dengan warna `#1e5e3a`.

### 2. Promo Banner Slider (Auto-Slide)
- Gantikan elemen *Social Proof/Trust Banner* lama dengan bagian ini.
- Buat container melintang (lebar penuh) memuat **Carousel/Slider Banner Promo**.
- Gunakan Alpine.js untuk logika slider: `x-data="{ activeSlide: 0, slides: [1, 2, 3], init() { setInterval(() => { this.activeSlide = (this.activeSlide + 1) % this.slides.length }, 4000) } }"`
- Desain Slide: Lebar penuh, bisa digeser. Berisi konten promo misal "Diskon 20% Pendakian Merdeka" atau "Cashback Rinjani".
- Kontrol Slider: Tambahkan fitur deteksi swipe jika memungkinkan, atau navigasi manual (dot pagination/tombol panah kiri-kanan).

### 3. Ekstensifikasi Katalog (Kategori & Produk)
- Latar Belakang: Langsung di bawah Slider, gunakan warna `Warm Cream` (`bg-[#f4f3ed]`). Berikan padding vertical yang lega.
- **Tab Kategori Wisata**: 
  - Deretan filter kategori diletakkan mendatar di atas grid. 
  - Gunakan `flex gap-4 overflow-x-auto pb-4 mb-8`.
  - Tombol kategori berbentuk kapsul (`rounded-full px-6 py-2 border border-[#1e5e3a] text-[#1e5e3a] hover:bg-[#1e5e3a] hover:text-white transition`). Contoh label: *"Semua"*, *"Gunung & Rimba"*, *"Pantai & Pulau"*, *"Budaya & Tradisi"*.
- **Grid Katalog Skala Besar**:
  - Gunakan `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6`.
  - Render minimal 6-8 slot kartu (statiskan jika belum ada data).
- **Card Product (Gaya Tripadvisor)**:
  - Container card: `bg-white rounded-[26px] overflow-hidden relative border border-gray-200 block`. Bebas dari class `shadow`.
  - Gambar thumbnail di atas (`aspect-[4/3] object-cover w-full`).
  - **Wishlist Button**: Tombol Hati absolut melayang di pojok kanan atas gambar (`absolute top-4 right-4 bg-white/80 rounded-full p-2`).
  - **Badge Kelangkaan**: Badge absolut melayang di bagian bawah gambar (`absolute bottom-4 left-4 bg-[#1e5e3a] text-white px-3 py-1 rounded-full text-xs font-bold`).
  - Konten bawah: Judul trip, rating bintang (ikon bintang hijau), lokasi, dan keterangan rentang harga.
  - Terapkan **Logika Halaman Universal** pada klik (link href) card ini.

### 4. Credibility Stats
- Bagian transisi memanjang di bawah katalog.
- Layout: Menampilkan 3 deretan angka (grid 3 kolom rata tengah) dengan margin besar.
- Angka raksasa: **10k+** (Positive Reviews), **50+** (Destinasi), **100%** (Safety Record).
- Desain font mencolok untuk angkanya (`text-5xl` atau `6xl font-bold`).

### 5. "About Kelana" Storytelling
- Container: Split layout 50:50.
- **Kolom Kiri**: 
  - Teks tipografi *"Apa itu Kelana?"* (`text-4xl lg:text-5xl font-bold mb-6 text-[#0f1a15]`).
  - Paragraf penjelasan tentang komitmen Trip Leader bersertifikasi, perjalanan privat, dst.
- **Kolom Kanan (Masonry Kolase)**:
  - Buat susunan grid asimetris untuk 3-4 foto alam. 
  - Buat style grid masonry (gambar 1 menjulang ke bawah, gambar lainnya bertumpuk). Pastikan semua gambar `rounded-[26px] object-cover`.

### 6. FAQ (Frequently Asked Questions)
- Container: Letakkan di tengah, beri ruang nafas/padding besar. Latar menggunakan cream/terang.
- **Accordion (Alpine.js)**:
  - Gunakan `x-data="{ selected: null }"`.
  - Desain kotak pertanyaan berbentuk kapsul (`bg-white rounded-full px-8 py-4 cursor-pointer`). 
  - Konten jawaban muncul dengan transisi dropdown saat `selected` aktif.

### 7. Comprehensive Footer
- Latar Belakang: Blok raksasa berwarna `Midnight Forest` (`bg-[#0b1611] text-white py-16`).
- Layout: 4 kolom raksasa (grid md:grid-cols-4).
- Memuat: Logo perusahaan di kiri, tautan bantuan/navigasi di tengah, form berlangganan *Newsletter* yang modern di kanan (input email kapsul panjang dengan tombol langganan di dalamnya).

---

## Aturan Pelaporan Akhir
Saat Anda sudah menyelesaikan pembuatan/edit file dan mencatat log di `STATE.md` (Handoff), Anda **WAJIB** menyertakan blok berikut di bagian bawah log tersebut:

> **Langkah Pengujian untuk User:**
> Silakan buka terminal, jalankan `php artisan serve` dan/atau `npm run dev` untuk mengompilasi aset, lalu periksa antarmuka Landing Page yang baru di rute URL root (`/`). Pastikan untuk menguji interaksi klik pada Card Destinasi (pengalihan Login vs Detail), efek transisi pada Floating Navbar, putaran otomatis Promo Slider, serta klik pada accordion FAQ.
