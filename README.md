# Pengajuan Perubahan Judul Skripsi — versi Laravel + MySQL

Folder ini adalah **tambahan** untuk proyek Laravel baru (Laravel 11/12, PHP 8.2+). Fitur sama dengan versi Google Apps Script.

## Isi
| File | Fungsi |
|---|---|
| `app/Http/Controllers/ApiController.php` | Seluruh logika (login, pengajuan, persetujuan, catatan, admin, import) |
| `database/migrations/2026_01_01_000000_create_pengajuan_tables.php` | Tabel `prodi`, `akun`, `pengajuan` + akun admin & 2 prodi awal |
| `routes/web.php` | Halaman utama `/` dan endpoint `POST /rpc` |
| `resources/views/app.blade.php` | Tampilan (form, kotak masuk, surat A4, verifikasi QR) |

## Pasang (VPS / server kampus)
```bash
composer create-project laravel/laravel pengajuan-judul
cd pengajuan-judul
# salin isi folder ini ke dalam proyek (timpa routes/web.php), lalu:
cp .env.example .env        # jika belum ada
php artisan key:generate
```
Edit `.env`:
```
APP_URL=https://alamat-anda.ac.id
APP_TIMEZONE=Asia/Jakarta
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=pengajuan_judul
DB_USERNAME=...
DB_PASSWORD=...
BCRYPT_ROUNDS=10
SESSION_DRIVER=file
CACHE_STORE=file
APP_DEBUG=false
APP_ENV=production
```
Buat database kosong, lalu:
```bash
php artisan migrate
php artisan config:cache && php artisan route:cache
```
Arahkan *document root* web server ke folder `public/`.

## Pasang (shared hosting cPanel)
1. Jalankan langkah di atas di komputer lokal (PHP 8.2 + Composer), lalu `composer install --no-dev`.
2. Unggah seluruh proyek; set *Document Root* domain ke `public/` (atau isi `public/` ke `public_html` dan sesuaikan path di `index.php`).
3. Impor database lewat phpMyAdmin atau jalankan `php artisan migrate` (Terminal cPanel).

## Login pertama
`admin` / `admin` → **segera ganti** di menu *Ganti Password*.

## PENTING
- **Jangan mengganti `APP_KEY`** setelah ada pengajuan yang disetujui. Kunci ini dipakai untuk kode tanda tangan elektronik (HMAC); jika berubah, semua cap lama menjadi tidak valid. Simpan cadangannya.
- Pakai **HTTPS** agar login dan QR verifikasi aman.
- Cadangkan database secara berkala (`mysqldump`).
- Bila `CACHE_STORE=file`, folder `storage/` harus bisa ditulis web server (sesi login disimpan di sana).
- Import ratusan dosen bisa memakan waktu puluhan detik (hash password); biarkan prosesnya selesai.

## Memindahkan data dari Google Sheets (opsional)
Password di Sheets memakai hash berbeda, jadi akun tidak bisa dipindah langsung: import ulang dosen dari Excel (password = NIDN) dan buat ulang akun Kaprodi di menu *Prodi & Kaprodi*. Data pengajuan lama dapat diekspor ke CSV dan diimpor ke tabel `pengajuan` (kolom sama dengan sheet), tetapi cap tanda tangan lama tidak valid karena kunci HMAC berbeda; setujui ulang atau sahkan manual lewat admin.
