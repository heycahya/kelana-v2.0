# 🐛 Bug Fix: Multi-Role Login Email/Username Input Validation

## 🔴 Root Cause
Dalam setup otentikasi multi-guard Kelana v2.0:
- Admin login menggunakan **Username** (`admin.kelana`).
- Customer & Trip Leader login menggunakan **Email** (`budi.santoso@kelana.com` / `adi.wijaya@kelana.com`).

Namun, berkas `app_build/resources/views/auth/login.blade.php` mendefinisikan tipe input untuk kolom `email` sebagai `type="email"`.
Hal ini menyebabkan peramban (browser) secara otomatis melakukan validasi format email HTML5 dan memblokir pengiriman formulir jika pengguna memasukkan username non-email seperti `admin.kelana`.

## 🛠️ Solusi
1. Mengubah tipe input kolom login dari `type="email"` menjadi `type="text"` pada berkas `app_build/resources/views/auth/login.blade.php`.
2. Menyesuaikan label dan placeholder input agar mempermudah pengguna (`Email address or Username` / `Email or Username`).
