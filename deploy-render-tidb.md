# Runbook: Deploy Laravel ke Render.com + TiDB Cloud + UptimeRobot (pilot 2-3 bulan)

Dokumen ini untuk agent AI. Bagian A dikerjakan manusia (daftar akun dan klik di dashboard). Bagian B dikerjakan agent di repo. Jangan mengarang nilai rahasia; minta ke manusia.

## Alur

```
GitHub repo (Laravel)
   │  push
   ▼
Render Web Service (Docker, instance Free)  ──TLS, port 4000──►  TiDB Cloud Starter (MySQL-compatible)
   ▲
   │  ping HTTP GET /up tiap 5 menit
UptimeRobot (agar layanan tidak tidur)
```

Aturan penting:
- Render Free tidur setelah 15 menit tanpa trafik masuk. Ping tiap 5 menit mencegahnya.
- Ping HARUS ke `/up` (route ringan tanpa query database), supaya tidak menghabiskan Request Unit TiDB.
- Hanya SATU layanan Render yang boleh aktif (kuota 750 jam/bulan cukup untuk satu layanan 24 jam).
- Filesystem Render Free sementara. Jangan simpan data penting (upload, sesi) di disk server.
- Jangan pernah commit `.env` atau password.

---

## Bagian A: Langkah manual (manusia)

1. **GitHub**: pastikan proyek ada di repo GitHub (boleh private). Pastikan `.env` ada di `.gitignore`.
2. **TiDB Cloud**: daftar, buat instance **Starter** (gratis, tanpa kartu kredit). Pilih region yang dekat dengan region Render (mis. Singapura bila tersedia di kedua sisi).
   - Buat database dan user, lalu catat: `HOST`, `PORT` (umumnya 4000), `USERNAME` (biasanya berawalan prefix unik, mis. `xxxx.root`), `PASSWORD`, `DATABASE`.
   - Pada pengaturan akses, izinkan koneksi dari mana saja (Render Free tidak punya IP statis, jadi allowlist IP tidak bisa dipakai). Keamanan mengandalkan TLS + password kuat.
3. **Render**: daftar, lalu **New → Web Service** → hubungkan repo GitHub.
   - Runtime/Language: **Docker**
   - Instance type: **Free**
   - Region: sama/dekat dengan TiDB
   - Health Check Path: `/up`
   - Isi Environment Variables (lihat tabel di Bagian B langkah 4)
   - Klik Deploy (lakukan setelah Bagian B selesai dan sudah di-push).
4. **UptimeRobot**: daftar, **New monitor**:
   - Type: HTTP(s)
   - URL: `https://NAMA-LAYANAN.onrender.com/up`
   - Interval: 5 menit (maksimum yang ada di paket gratis)
   - Tambahkan email untuk alert.
   - Catatan: sumber berbeda soal apakah paket gratis boleh untuk penggunaan institusi/komersial. Baca ketentuan resminya. Alternatif setara: cron-job.org atau StatusCake dengan URL yang sama.

---

## Bagian B: Langkah agent (di repo)

### 1. Periksa proyek
- Baca `composer.json` untuk versi PHP dan ekstensi yang dibutuhkan; sesuaikan tag image PHP di Dockerfile dan daftar ekstensi.
- Cek apakah ada `package.json` dengan build frontend (Vite/Mix). Kalau ada, tambahkan stage Node di Dockerfile untuk `npm ci && npm run build` dan salin hasil `public/build` ke image akhir.
- Cek apakah aplikasi menyimpan file upload ke disk lokal. Kalau ya, laporkan ke manusia (file akan hilang saat restart); jangan diam-diam diabaikan.
- Cek apakah ada queue worker atau scheduler yang dibutuhkan. Free tier tidak punya worker terpisah: pakai `QUEUE_CONNECTION=sync`, dan laporkan jika ada jadwal (`schedule:run`) yang wajib jalan.

### 2. Buat file `Dockerfile` di root repo

```dockerfile
FROM php:8.3-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
      git unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libicu-dev libonig-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql mbstring zip gd bcmath intl opcache \
 && a2enmod rewrite headers \
 && rm -rf /var/lib/apt/lists/*

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev \
 && chown -R www-data:www-data storage bootstrap/cache

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 10000
CMD ["/entrypoint.sh"]
```

Sesuaikan versi PHP dengan `composer.json`. Jika ada build frontend, tambahkan stage Node (lihat langkah 1).

### 3. Buat `docker/entrypoint.sh` dan `.dockerignore`

`docker/entrypoint.sh`:

```sh
#!/bin/sh
set -e

# Render mengirim port lewat variabel PORT
PORT="${PORT:-10000}"
sed -ri "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  php artisan migrate --force
fi

exec apache2-foreground
```

`.dockerignore`:

```
.git
.env
vendor
node_modules
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
```

Jika `route:cache` gagal karena route berbasis closure, hapus baris itu dan laporkan.

### 4. Environment variables (diisi manusia di Render, nilai rahasia jangan di repo)

| Variabel | Nilai |
|---|---|
| `APP_NAME` | nama aplikasi |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | hasil `php artisan key:generate --show` (jalankan lokal) |
| `APP_URL` | `https://NAMA-LAYANAN.onrender.com` |
| `LOG_CHANNEL` | `stderr` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | host dari TiDB |
| `DB_PORT` | `4000` (sesuai TiDB) |
| `DB_DATABASE` | nama database |
| `DB_USERNAME` | user lengkap dengan prefix |
| `DB_PASSWORD` | password dari TiDB |
| `MYSQL_ATTR_SSL_CA` | `/etc/ssl/certs/ca-certificates.crt` |
| `SESSION_DRIVER` | `cookie` (hemat query; boleh `database` jika dibutuhkan) |
| `CACHE_STORE` | `database` (Laravel 10 ke bawah: `CACHE_DRIVER=file` juga aman) |
| `QUEUE_CONNECTION` | `sync` |
| `RUN_MIGRATIONS` | `true` |

Pastikan `config/database.php` bagian `mysql` memakai `MYSQL_ATTR_SSL_CA` pada `options` (default Laravel sudah membacanya dari env). Jika proyek memakai konfigurasi kustom, tambahkan.

### 5. Pastikan route `/up` ada dan tidak menyentuh database
- Laravel 11+: sudah ada `/up` secara bawaan.
- Laravel lama: tambahkan di `routes/web.php`:
  ```php
  Route::get('/up', fn () => response('ok', 200));
  ```

### 6. Atur trusted proxy (Render ada di belakang proxy HTTPS)
Tanpa ini, URL aset bisa jadi `http://` dan muncul masalah mixed content atau redirect loop.
- Laravel 11+: di `bootstrap/app.php` pada `withMiddleware`: `$middleware->trustProxies(at: '*');`
- Laravel 10 ke bawah: di `app/Http/Middleware/TrustProxies.php`, set `protected $proxies = '*';`

### 7. Uji build lokal (jika Docker tersedia), lalu commit dan push
```bash
docker build -t laravel-pilot .
docker run --rm -p 10000:10000 -e PORT=10000 -e APP_KEY=... laravel-pilot
# buka http://localhost:10000/up  → harus "ok"
git add Dockerfile docker/entrypoint.sh .dockerignore
git commit -m "Add Docker deploy for Render"
git push
```
Jika Docker tidak tersedia di lingkungan agent, lewati uji lokal dan andalkan log build Render.

### 8. Deploy dan verifikasi (agent minta manusia menjalankan dari Bagian A, lalu cek)
Checklist:
- [ ] Build Render sukses, status **Live**.
- [ ] `https://NAMA-LAYANAN.onrender.com/up` mengembalikan `ok`.
- [ ] Halaman utama dan login terbuka tanpa error 500.
- [ ] Tabel hasil migrasi muncul di console TiDB.
- [ ] Uji CRUD satu fitur utama.
- [ ] Tunggu 20 menit tanpa membuka situs; buka lagi, respons harus cepat (tidak ada layar "spinning up"). Itu tanda UptimeRobot bekerja.
- [ ] Monitor UptimeRobot berstatus Up.

---

## Backup mingguan (wajib, agar mudah pindah ke kampus)

Dijalankan manusia atau agent dengan akses, dari mesin yang punya klien MySQL:

```bash
mysqldump -h HOST -P 4000 -u USERNAME -p \
  --ssl-mode=VERIFY_IDENTITY --ssl-ca=/etc/ssl/certs/ca-certificates.crt \
  DATABASE > backup-$(date +%F).sql
```

Sesuaikan flag TLS dengan klien yang dipakai. Simpan di tempat di luar Render (mis. Google Drive kampus).

---

## Migrasi ke server kampus (nanti)

1. Siapkan server kampus (PHP + MySQL, atau Docker).
2. Impor backup terbaru ke MySQL kampus.
3. Deploy repo yang sama; image Docker yang sama bisa dipakai tanpa diubah.
4. Ubah `.env`: `DB_*` ke MySQL kampus, hapus `MYSQL_ATTR_SSL_CA` bila tidak memakai TLS, `APP_URL` ke domain kampus.
5. Arahkan DNS/subdomain, uji, lalu matikan monitor UptimeRobot dan hentikan layanan Render.

---

## Troubleshooting

| Gejala | Kemungkinan penyebab | Tindakan |
|---|---|---|
| Deploy gagal "no open ports" / timeout | Apache tidak mendengarkan `$PORT` | Periksa `entrypoint.sh` dan EXPOSE; cek log |
| Error 500 | `APP_KEY` kosong atau konfigurasi cache salah | Isi `APP_KEY`; lihat log Render (`LOG_CHANNEL=stderr`) |
| Koneksi database ditolak / SSL error | `MYSQL_ATTR_SSL_CA` belum diset atau username tanpa prefix | Periksa env; pakai username lengkap dari TiDB |
| Koneksi database tiba-tiba gagal | Kuota gratis TiDB terlampaui (mis. >5 GiB data) | Cek penggunaan di console TiDB |
| Aset/CSS tidak muncul, redirect loop | Trusted proxy belum diatur / `APP_URL` salah | Lihat langkah 6 dan `APP_URL` |
| Layanan masih tidur | Monitor UptimeRobot tidak aktif atau URL salah | Periksa monitor dan URL `/up` |
| Data upload hilang | Filesystem Render sementara | Pindah penyimpanan ke layanan eksternal |
| Layanan ditangguhkan di akhir bulan | Kuota 750 jam habis (lebih dari satu layanan aktif) | Hanya satu layanan aktif |
