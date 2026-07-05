import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/admin.css',
                'resources/css/login.css',
                'resources/css/dashboard.css',
                'resources/css/lahan-saya.css',
                'resources/css/jadwal-tanam.css',
                'resources/css/notifikasi.css',
                'resources/css/cuaca.css',
                'resources/css/pupuk.css',
                'resources/css/marketplace.css',
                'resources/css/marketplace-pembeli.css',
                'resources/css/lumbung-padi.css',
                'resources/css/edukasi.css',
                'resources/css/hama-penyakit.css',
                'resources/css/profile.css',
                'resources/css/data-diri.css',
                'resources/css/riwayat-transaksi.css',
                'resources/css/navigasi-bawah.css',
                'resources/js/admin.js',
                'resources/js/cuaca.js',
                'resources/js/dashboard-weather.js',
                'resources/js/data-diri.js',
                'resources/js/form-autentikasi.js',
                'resources/js/jadwal-tanam.js',
                'resources/js/konten-aplikasi.js',
                'resources/js/notifikasi.js',
                'resources/js/lumbung-padi.js',
                'resources/js/marketplace.js',
                'resources/js/marketplace-pembeli.js',
                'resources/js/maintenance.js',
                'resources/js/pupuk.js',
                'resources/js/profile.js',
                'resources/js/riwayat-transaksi.js',
                'resources/js/riwayat-belanja-pembeli.js',

                'resources/css/landing.css',
                'resources/js/landing.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        hmr: {
            host: '192.168.1.13',
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});