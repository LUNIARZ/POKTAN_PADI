<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Transaksi</title>

    @vite(['resources/css/riwayat-transaksi.css', 'resources/css/navigasi-bawah.css', 'resources/js/riwayat-transaksi.js', 'resources/js/maintenance.js'])
</head>

<body>
    <main class="halaman-riwayat-transaksi">
        <header class="kepala-halaman">
            <a href="{{ route('profile') }}" class="tombol-bulat" aria-label="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"></path>
                    <path d="M12 19l-7-7 7-7"></path>
                </svg>
            </a>

            <h1 class="judul-halaman">Riwayat Transaksi</h1>

            <div class="ruang-kanan" aria-hidden="true"></div>
        </header>

        <section class="konten-riwayat-transaksi">
            <article class="kartu-ringkasan-riwayat">
                <span class="ikon-ringkasan-riwayat" aria-hidden="true">
                    <img src="{{ asset('assets/profile/gambar_riwayat_transaksi.png') }}" alt="">
                </span>

                <div>
                    <p>Total Transaksi</p>
                    <strong data-total-transaksi>0 transaksi</strong>
                    <small data-total-nilai>Total Rp0</small>
                </div>
            </article>

            <nav class="filter-riwayat" aria-label="Filter riwayat transaksi">
                <button class="aktif" type="button" data-filter-riwayat="semua" aria-pressed="true">Semua</button>
                <button type="button" data-filter-riwayat="marketplace" aria-pressed="false">Marketplace</button>
                <button type="button" data-filter-riwayat="pupuk" aria-pressed="false">Pupuk</button>
            </nav>

            <section class="daftar-riwayat-transaksi" data-daftar-riwayat-transaksi aria-live="polite"></section>
        </section>

        <x-navigasi-bawah aktif="profile" />
    </main>
</body>
</html>
