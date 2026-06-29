<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jadwal Tanam</title>

    @vite(['resources/css/jadwal-tanam.css', 'resources/css/navigasi-bawah.css', 'resources/js/jadwal-tanam.js', 'resources/js/maintenance.js'])
</head>

<body>
    <main class="halaman-jadwal">
        <header class="kepala-halaman">
            <a href="{{ route('dashboard') }}" class="tombol-bulat" aria-label="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"></path>
                    <path d="M12 19l-7-7 7-7"></path>
                </svg>
            </a>

            <h1 class="judul-halaman">Jadwal Tanam</h1>

            <div class="ruang-kanan" aria-hidden="true"></div>
        </header>

        <section class="konten-jadwal">
            <article class="banner-jadwal">
                <img
                    src="{{ asset('assets/tanam_padi.png') }}"
                    alt="Petani menanam bibit padi bersama di sawah"
                    width="1456"
                    height="1087"
                >

                <div class="isi-banner">
                    <span data-ringkasan-status>Proses aktif</span>
                    <h2 data-ringkasan-tahap>Pembibitan</h2>
                    <p data-ringkasan-deskripsi>Menyiapkan benih padi sampai siap dipindahkan ke lahan.</p>
                </div>
            </article>

            <section class="kartu-kalender" aria-label="Kalender jadwal tanam">
                <label>
                    <span>Kalender Tanam</span>
                    <strong>Tanggal mulai semai</strong>
                    <input type="date" disabled data-tanggal-semai>
                </label>

                <p data-ringkasan-kalender>Tanggal mulai otomatis mengikuti hari saat proses tanam dimulai.</p>

                <button class="tombol-mulai-jadwal" type="button" data-mulai-jadwal>
                    Mulai Proses Tanam
                </button>
            </section>

            <section class="kartu-progress" aria-label="Progress jadwal tanam">
                <div class="kepala-progress">
                    <div>
                        <span>Progress</span>
                        <strong data-progress-teks>0 dari 4 selesai</strong>
                    </div>

                    <button class="tombol-reset" type="button" hidden data-reset-jadwal>Mulai Ulang</button>
                </div>

                <div class="jalur-progress" aria-hidden="true">
                    <span data-progress-bar></span>
                </div>
            </section>

            <section class="daftar-proses" aria-label="Daftar proses tanam" data-daftar-proses></section>
        </section>

        <x-navigasi-bawah aktif="beranda" />
    </main>
</body>
</html>
