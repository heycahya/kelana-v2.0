# Integrasi Multi-Role Authentication pada Web (Session-based)

## Deskripsi Tugas
Mengubah sistem autentikasi bawaan Laravel Breeze agar mendukung multi-role session login secara terintegrasi menggunakan tabel database kustom (`admins`, `customers`, dan `trip_leaders`) alih-alih tabel `users` bawaan Laravel.

## 🎯 Target Utama
1. **Multi-Role Login**: Pengguna (Admin, Customer, Trip Leader) dapat login melalui formulir `/login` di browser menggunakan kredensial mereka masing-masing.
2. **Dynamic Redirect**: Setelah berhasil login, pengguna secara otomatis diarahkan ke dashboard masing-masing peran.
3. **Customer Registration**: Halaman pendaftaran `/register` mendaftarkan pengguna baru langsung ke tabel `customers`.
4. **Logout Aman**: Sesi dari semua peran dibersihkan dengan benar saat logout dilakukan.

## 📋 Langkah-langkah Implementasi Low-Level

### 1. Konfigurasi Auth Guards & Providers
Buka file `config/auth.php` dan sesuaikan konfigurasinya.

- **Guards**: Tambahkan guard untuk setiap role (bisa menggantikan `web` atau membiarkannya jika tidak dipakai):
  ```php
  'guards' => [
      'admin' => [
          'driver' => 'session',
          'provider' => 'admins',
      ],
      'customer' => [
          'driver' => 'session',
          'provider' => 'customers',
      ],
      'trip_leader' => [
          'driver' => 'session',
          'provider' => 'trip_leaders',
      ],
  ],
  ```

- **Providers**: Tambahkan provider untuk masing-masing tabel/model:
  ```php
  'providers' => [
      'admins' => [
          'driver' => 'eloquent',
          'model' => App\Models\Admin::class,
      ],
      'customers' => [
          'driver' => 'eloquent',
          'model' => App\Models\Customer::class,
      ],
      'trip_leaders' => [
          'driver' => 'eloquent',
          'model' => App\Models\TripLeader::class,
      ],
  ],
  ```

- **Default**: Ubah guard dan provider default ke `customer` jika diperlukan.

### 2. Penyesuaian Models
Pastikan model `Admin`, `Customer`, dan `TripLeader` menge-extend class `Illuminate\Foundation\Auth\User` (sebagai Authenticatable) bukan `Illuminate\Database\Eloquent\Model` biasa.
- Di dalam file model masing-masing, ubah extends-nya.
- Contoh: `class Customer extends \Illuminate\Foundation\Auth\User`
- Lakukan hal yang sama untuk `Admin` dan `TripLeader`.

### 3. Modifikasi Logika Login (AuthenticatedSessionController & LoginRequest)
Buka `app/Http/Requests/Auth/LoginRequest.php` jika Anda memodifikasi fungsi bawaan Breeze, atau ubah langsung `store()` di `app/Http/Controllers/Auth/AuthenticatedSessionController.php`.

- **Logika Percobaan Login**:
  Karena kita menggunakan 3 guard berbeda dengan 1 form login, kita harus mengecek kredensial ke masing-masing guard secara berurutan.

  Contoh logika implementasi pada saat autentikasi:
  ```php
  $credentials = $request->only('email', 'password');
  $remember = $request->boolean('remember');
  $guard = null;

  if (Auth::guard('admin')->attempt($credentials, $remember)) {
      $guard = 'admin';
  } elseif (Auth::guard('trip_leader')->attempt($credentials, $remember)) {
      $guard = 'trip_leader';
  } elseif (Auth::guard('customer')->attempt($credentials, $remember)) {
      $guard = 'customer';
  }

  if (!$guard) {
      throw ValidationException::withMessages([
          'email' => trans('auth.failed'),
      ]);
  }
  ```

- **Dynamic Redirect**:
  Setelah session di-regenerate (`$request->session()->regenerate()`), arahkan ke dashboard yang sesuai:
  ```php
  if ($guard === 'admin') {
      return redirect()->intended(route('admin.dashboard', absolute: false));
  } elseif ($guard === 'trip_leader') {
      return redirect()->intended(route('trip_leader.dashboard', absolute: false));
  } else {
      return redirect()->intended(route('dashboard', absolute: false));
  }
  ```

### 4. Modifikasi Logika Registrasi (RegisteredUserController)
Buka file `app/Http/Controllers/Auth/RegisteredUserController.php` (Breeze).

- **Method `store`**:
  Ubah agar menyimpan data ke tabel `customers` (menggunakan model `Customer`).
  - Sesuaikan rules validasi agar mengecek unique pada tabel `customers`: 
    `'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Customer::class],`
  - Ganti `User::create` menjadi `Customer::create([...])`.
  - Login user setelah registrasi dengan menspesifikkan guard: `Auth::guard('customer')->login($user);`
  - Arahkan ke rute dashboard customer.

### 5. Modifikasi Logika Logout (AuthenticatedSessionController)
Buka file `app/Http/Controllers/Auth/AuthenticatedSessionController.php`.

- Pada method `destroy(Request $request)`:
  Pastikan user logout dari guard yang sedang aktif saat ini.
  ```php
  if (Auth::guard('admin')->check()) {
      Auth::guard('admin')->logout();
  } elseif (Auth::guard('trip_leader')->check()) {
      Auth::guard('trip_leader')->logout();
  } elseif (Auth::guard('customer')->check()) {
      Auth::guard('customer')->logout();
  }

  $request->session()->invalidate();
  $request->session()->regenerateToken();

  return redirect('/');
  ```

### 6. Perlindungan Rute dan Middleware
- Buka file `routes/web.php`. Untuk rute spesifik, pastikan Anda memanggil middleware auth dengan parameter guard yang tepat: `Route::middleware('auth:customer')`, `Route::middleware('auth:admin')`, atau `Route::middleware('auth:trip_leader')`.
- Jika Anda memiliki rute dashboard masing-masing role, pastikan alias rute (`name()`) sesuai dengan redirect yang dibuat di Controller Login.

## Aturan Eksekusi untuk @engineer-coder
- Harap tuliskan kode secara modular dan DRY.
- Gunakan `use` statement yang sesuai di setiap file (seperti `Auth`, `ValidationException`, `Route`, dll).
- Pastikan bahwa jika ada komponen Breeze yang tidak dibutuhkan lagi, Anda cukup abaikan atau sesuaikan.
- Tulis log pengembangan di file `STATE.md` setelah seluruh tahap ini selesai diimplementasikan tanpa error.
