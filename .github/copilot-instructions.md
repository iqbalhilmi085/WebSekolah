<!-- Instruksi Copilot untuk Laravel_webSekolah -->
# Instruksi Copilot / Agen AI

Tujuan: Membantu agen AI langsung produktif bekerja pada aplikasi pembayaran sekolah berbasis Laravel ini.

- Jenis proyek: Aplikasi Laravel 12 (PHP) monolitik dengan front-end Vite/Tailwind (lihat [composer.json](composer.json) dan [package.json](package.json)).
- Area penting: autentikasi (`app/Http/Controllers/AuthController.php`), manajemen siswa (`app/Models/Siswa.php`, `app/Http/Controllers/SiswaController.php`), pembayaran (`app/Models/Pembayaran.php`, `app/Http/Controllers/PembayaranController.php`), rute di [routes/web.php](routes/web.php) dan tampilan Blade di `resources/views`.

Gambaran umum
- Struktur: logika utama berada di controller + model Eloquent; controller mengembalikan Blade view (tidak ada API JSON publik).
- Alur data umum: form -> validasi di controller -> create/update Eloquent -> redirect dengan flash message. File unggahan disimpan di disk `public` (`store(..., 'public')`).

Alur kerja developer (perintah penting)
- Setup lokal singkat:
  1. `composer install`
  2. salin `.env.example` -> `.env` lalu atur koneksi DB
  3. `php artisan key:generate`
  4. `php artisan migrate --seed`
  5. `npm install` dan `npm run dev` (atau `npm run build` untuk produksi)
- Script composer berguna: `composer run setup`, `composer run dev`, `composer run test`.
- Menjalankan test: `composer run test` (menjalankan `php artisan test`).

Kebiasaan dan konvensi proyek
- Nama tabel DB: model menggunakan `protected $table` (contoh: `siswa`, `pembayaran`) — jangan ubah tanpa memperbarui migrasi/validasi.
- Validasi: tiap controller memakai `$request->validate([...])`. Enum penting: `status` = `lunas, belum_lunas, cicilan`; `jenis_kelamin` = `L,P`.
- Upload file: disimpan di `storage/app/public` dan diakses lewat `public` disk. Saat mengganti file, controller menghapus file lama dengan `Storage::disk('public')->delete(...)`.
- Pagination default controller: `->paginate(20)` — sesuaikan UI jika mengubah nilai ini.
- Penamaan view: gunakan nama blade yang sudah ada (mis. `pembayaran.index`, `siswa.create`).

Integrasi dan dependensi eksternal
- Front-end: Vite, Tailwind, Axios (lihat [package.json](package.json)).
- Tidak ada API eksternal terdeteksi. Storage menggunakan disk lokal; jika pindah ke S3, perbarui `config/filesystems.php` dan pemanggilan `store(..., 'public')`.

Contoh tempat lihat pola
- CRUD: `app/Http/Controllers/SiswaController.php` dan `PembayaranController.php` (lihat `store`, `update`, `destroy`).
- Helper model: `Siswa` memakai accessor seperti `getTotalPembayaranAttribute` dan relasi `pembayaran()`.

Keamanan & testing praktis
- Jangan lupa hapus file lama saat menghapus/replace record untuk menghindari orphan files.
- Gunakan seeders di `database/seeders` ketika menambahkan data contoh.

File yang harus diubah bila perilaku diubah
- Rute: [routes/web.php](routes/web.php)
- Controller: `app/Http/Controllers/*Controller.php`
- Model: `app/Models/*` (ingat `protected $table`)
- Views: `resources/views/**`
- Asset: `resources/js`, `resources/css`, `vite.config.js`

Cuplikan kode (salin-pakai)
- Validasi pembayaran sesuai aturan proyek:
  ```php
  $v = $request->validate([
      'siswa_id' => 'required|exists:siswa,id',
      'status' => 'required|in:lunas,belum_lunas,cicilan',
  ]);
  ```
- Hapus file lama sebelum mengganti upload:
  ```php
  if ($model->file) { Storage::disk('public')->delete($model->file); }
  $path = $request->file('foto')->store('foto_siswa', 'public');
  ```

Catatan untuk agen AI
- Hati-hati saat mengganti nama tabel/kolom — model memakai `$table` dan validasi mengandalkan nama tabel tersebut.
- Terapkan perubahan kecil dan terfokus; jalankan `composer run test` setelah perubahan yang signifikan.
- Bila menambah field baru: perbarui validasi controller, `$fillable` model, migrasi, dan tampilan terkait.

Butuh bagian terjemahan/ contoh Blade atau seeding lebih lengkap? Beri tahu bagian mana yang mau saya perluas.

