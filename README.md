# POKTAN Lancang Kuning

Aplikasi web Laravel untuk pengelolaan kelompok tani, marketplace hasil pertanian, pemesanan pupuk, jadwal tanam, hasil panen, cuaca, notifikasi, dan administrasi pengguna.

## Kebutuhan

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- MySQL 8+ atau MariaDB 10.6+
- Ekstensi PHP yang dibutuhkan Laravel dan driver PDO MySQL

## Instalasi lokal

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan storage:link
```

Akun admin awal:

```text
Username: admin
Password: Admin12345
```

Ubah `ADMIN_INITIAL_PASSWORD` pada `.env` sebelum menjalankan seeder jika ingin memakai password admin lain.

## Deployment production

1. Salin `.env.example` menjadi `.env`.
2. Ubah `APP_ENV=production`, `APP_DEBUG=false`, lalu isi `APP_URL`, `APP_KEY`, kredensial database, email, dan `ADMIN_INITIAL_PASSWORD` dengan password admin yang kuat.
3. Arahkan document root web server ke folder `public`.
4. Jalankan:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan storage:link
php artisan migrate --force
php artisan optimize
```

5. Pastikan `storage` dan `bootstrap/cache` dapat ditulis oleh proses web.
6. Jalankan worker queue menggunakan process manager:

```bash
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

7. Jadwalkan Laravel scheduler setiap menit:

```cron
* * * * * cd /path/to/poktan && php artisan schedule:run >> /dev/null 2>&1
```

Production wajib menggunakan HTTPS dengan `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, dan password database khusus yang tidak memakai akun `root`.

## Verifikasi sebelum rilis

```bash
php artisan test
vendor/bin/pint --test
npm audit --omit=dev
npm run build
php artisan route:list --except-vendor
```

Endpoint health check Laravel tersedia pada `/up`.
