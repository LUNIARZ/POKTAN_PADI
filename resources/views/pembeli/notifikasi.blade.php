<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Notifikasi Pembeli</title>

    @vite(['resources/css/notifikasi.css', 'resources/css/navigasi-bawah.css', 'resources/js/notifikasi.js', 'resources/js/maintenance.js'])
</head>

<body>
    <main class="halaman-notifikasi">
        <header class="kepala-halaman">
            <a href="{{ route('pembeli.marketplace') }}" class="tombol-bulat" aria-label="Kembali ke marketplace pembeli">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"></path>
                    <path d="M12 19l-7-7 7-7"></path>
                </svg>
            </a>

            <h1 class="judul-halaman">Notifikasi</h1>

            <div class="aksi-kanan">
                <button
                    class="tombol-bulat"
                    type="button"
                    aria-label="Filter Notifikasi"
                    aria-controls="panel-filter-notifikasi"
                    aria-expanded="false"
                    data-tombol-filter
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 7h16"></path>
                        <path d="M7 12h10"></path>
                        <path d="M10 17h4"></path>
                    </svg>
                </button>
            </div>
        </header>

        <section class="konten-notifikasi">
            <article class="kartu-notifikasi">
                <div class="daftar-notifikasi" data-daftar-notifikasi data-notifikasi-pembeli-marketplace></div>
            </article>
        </section>

        <section
            class="panel-filter"
            id="panel-filter-notifikasi"
            aria-label="Filter notifikasi"
            hidden
            data-panel-filter
        >
            <div class="pegangan-filter" aria-hidden="true"></div>
            <button class="tombol-tutup-filter" type="button" aria-label="Tutup filter" data-filter-tutup>
                <span aria-hidden="true">&times;</span>
            </button>

            <div class="bagian-filter">
                <h2>Urutkan</h2>

                <div class="pilihan-urut" data-grup-urut>
                    <button class="tombol-urut aktif" type="button" data-urut="terbaru">Terbaru</button>
                    <button class="tombol-urut" type="button" data-urut="terlama">Terlama</button>
                </div>
            </div>

            <div class="bagian-filter">
                <h2>Tanggal</h2>

                <div class="grid-tanggal">
                    <div class="kolom-tanggal">
                        <label for="tanggal-mulai">Tanggal Mulai</label>
                        <table class="tabel-tanggal">
                            <tbody>
                                <tr>
                                    <td>
                                        <input id="tanggal-mulai" type="date" placeholder="DD/MM/YYYY" data-tanggal-mulai>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="kolom-tanggal">
                        <label for="tanggal-akhir">Tanggal Akhir</label>
                        <table class="tabel-tanggal">
                            <tbody>
                                <tr>
                                    <td>
                                        <input id="tanggal-akhir" type="date" placeholder="DD/MM/YYYY" data-tanggal-akhir>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="aksi-filter">
                <button class="tombol-reset" type="button" data-filter-reset>RESET</button>
                <button class="tombol-terapkan" type="button" data-filter-terapkan>TERAPKAN</button>
            </div>
        </section>

        <x-navigasi-pembeli aktif="notifikasi" />
    </main>
</body>
</html>
