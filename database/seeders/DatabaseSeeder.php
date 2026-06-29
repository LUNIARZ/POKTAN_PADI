<?php

namespace Database\Seeders;

use App\Models\MetodePembayaran;
use App\Models\ProdukPupuk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! User::where('username', 'admin')->exists()) {
            $adminPassword = config('auth.admin_initial_password');

            if (blank($adminPassword) || $adminPassword === 'change-this-before-seeding') {
                $adminPassword = 'Admin12345';
            }

            User::create([
                'name' => 'Admin Lancang Kuning',
                'username' => 'admin',
                'peran' => 'admin',
                'status' => 'aktif',
                'password' => $adminPassword,
                'password_updated_at' => now(),
            ]);
        }

        DB::table('pengaturan_aplikasi')->insertOrIgnore([
            [
                'id' => 1,
                'nama_aplikasi' => 'POKTAN Lancang Kuning',
                'lokasi_aplikasi' => 'Lancang Kuning',
                'status_marketplace' => 'aktif',
                'maintenance_aktif' => false,
                'pesan_maintenance' => 'Aplikasi sedang dalam perawatan. Silakan coba lagi nanti.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        foreach (['marketplace_pembeli', 'pupuk_petani'] as $context) {
            foreach (['tunai' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS'] as $method => $label) {
                MetodePembayaran::firstOrCreate(
                    ['konteks' => $context, 'metode' => $method],
                    ['nama_tampilan' => $label, 'aktif' => true]
                );
            }
        }

        $products = [
            ['Urea', 'urea', 'Nitrogen 46% untuk pertumbuhan daun.', '50 kg', 120000, '/assets/pupuk/tas_pupuk_urea_dengan_granula.png'],
            ['NPK 16-16-16', 'npk-16-16-16', 'Pupuk seimbang untuk fase vegetatif dan generatif.', '50 kg', 160000, '/assets/pupuk/pupuk_majemuk_npk_16_16_16.png'],
            ['Pupuk Organik', 'pupuk-organik', 'Memperbaiki struktur tanah dan meningkatkan kesuburan.', '25 kg', 85000, '/assets/pupuk/pupuk_organik_dengan_tanah_kompos.png'],
            ['KCL', 'kcl', 'Sumber kalium untuk meningkatkan kualitas hasil.', '50 kg', 130000, '/assets/pupuk/tas_pupuk_kcl_dengan_granula.png'],
        ];

        foreach ($products as [$name, $slug, $description, $package, $price, $image]) {
            ProdukPupuk::firstOrCreate(
                ['slug' => $slug],
                [
                    'nama_produk' => $name,
                    'deskripsi' => $description,
                    'ukuran_kemasan' => $package,
                    'harga' => $price,
                    'jumlah_stok' => 0,
                    'gambar_produk' => $image,
                    'aktif' => true,
                ]
            );
        }
    }
}
