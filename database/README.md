# Database POKTAN Lancang Kuning

Project ini memakai schema database fresh yang mengikuti fitur aplikasi saat ini:

- admin, petani, pembeli, profil pengguna, dan lahan petani
- jadwal tanam, progres tahap tanam, dan hasil panen
- produk pupuk, batas pembelian pupuk, pesanan pupuk
- marketplace hasil petani dan pesanan pembeli
- notifikasi, konten edukasi/hama penyakit, pengaturan aplikasi, metode pembayaran
- tabel Laravel untuk session, cache, queue, dan failed jobs

## Cara Disarankan: Laravel Migration

Pastikan `.env` sudah mengarah ke database baru:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=poktan
DB_USERNAME=root
DB_PASSWORD=
ADMIN_INITIAL_PASSWORD=Admin12345
```

Lalu jalankan:

```bash
php artisan migrate:fresh --seed
```

Perintah itu membuat ulang seluruh tabel dan mengisi data awal. Jangan jalankan pada database produksi/lama yang masih ingin dipertahankan.

Akun admin awal:

```text
Username: admin
Password: Admin12345
```

Ganti `ADMIN_INITIAL_PASSWORD` sebelum menjalankan seeder jika ingin memakai password admin lain.

## Cara Alternatif: Import SQL

Import file berikut melalui phpMyAdmin, HeidiSQL, atau MySQL CLI:

```text
database/fresh_poktan_schema.sql
```

File SQL fresh sudah berisi akun admin awal:

```text
Username: admin
Password: Admin12345
```

Jika ingin mengganti password admin lewat seeder, isi `ADMIN_INITIAL_PASSWORD` lalu jalankan:

```bash
php artisan db:seed
```
