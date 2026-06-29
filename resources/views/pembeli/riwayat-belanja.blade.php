<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pesanan Pembeli</title>

    @vite(['resources/css/riwayat-transaksi.css', 'resources/css/navigasi-bawah.css', 'resources/js/riwayat-belanja-pembeli.js', 'resources/js/maintenance.js'])
</head>

<body>
    <main class="halaman-riwayat-transaksi">
        <header class="kepala-halaman">
            <a href="{{ route('pembeli.marketplace') }}" class="tombol-bulat" aria-label="Kembali ke marketplace pembeli">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"></path>
                    <path d="M12 19l-7-7 7-7"></path>
                </svg>
            </a>

            <h1 class="judul-halaman">Pesanan Saya</h1>

            <div class="ruang-kanan" aria-hidden="true"></div>
        </header>

        <section class="konten-riwayat-transaksi">
            <article class="kartu-ringkasan-riwayat">
                <span class="ikon-ringkasan-riwayat" aria-hidden="true">
                    <img src="{{ asset('assets/profile/gambar_riwayat_transaksi.png') }}" alt="">
                </span>

                <div>
                    <p>Total Pesanan</p>
                    <strong data-total-belanja>0 pesanan</strong>
                    <small data-total-nilai-belanja>Total Rp0</small>
                </div>
            </article>

            <nav class="filter-riwayat" aria-label="Filter riwayat belanja">
                <button class="aktif" type="button" data-filter-belanja="semua" aria-pressed="true">Semua</button>
                <button type="button" data-filter-belanja="menunggu" aria-pressed="false">Menunggu</button>
                <button type="button" data-filter-belanja="disetujui" aria-pressed="false">Disetujui</button>
                <button type="button" data-filter-belanja="ditolak" aria-pressed="false">Ditolak</button>
                <button type="button" data-filter-belanja="selesai" aria-pressed="false">Selesai</button>
                <button type="button" data-filter-belanja="dibatalkan" aria-pressed="false">Dibatalkan</button>
            </nav>

            <p class="status-riwayat-belanja" role="status" aria-live="polite" data-status-riwayat-belanja hidden></p>

            <section class="daftar-riwayat-transaksi" data-daftar-riwayat-belanja aria-live="polite"></section>
        </section>

        <x-navigasi-pembeli aktif="pesanan" />
    </main>
</body>
</html>
