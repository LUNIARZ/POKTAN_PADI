<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hama & Penyakit</title>

    @vite(['resources/css/hama-penyakit.css', 'resources/css/navigasi-bawah.css', 'resources/js/konten-aplikasi.js', 'resources/js/maintenance.js'])
</head>

<body>
    <main class="halaman-hama" data-admin-content-page="Hama & Penyakit">

        <header class="kepala-halaman">
            <a href="{{ route('dashboard') }}" class="tombol-kembali" aria-label="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"></path>
                    <path d="M12 19l-7-7 7-7"></path>
                </svg>
            </a>

            <h1 class="judul-halaman">Hama & Penyakit</h1>

            <span class="ruang-header" aria-hidden="true"></span>
        </header>

        <section class="konten-hama">
            <section class="daftar-masalah" data-admin-content-list>

                <article class="kartu-masalah">
                    <img
                        src="{{ asset('assets/hama-penyakit/serangga_hijau_di_atas_daun.png') }}"
                        alt="Wereng pada daun"
                        class="gambar-masalah"
                    >

                    <div class="isi-masalah">
                        <h3>Wereng</h3>
                        <p>
                            Serangga kecil penghisap cairan tanaman yang menyebabkan daun menguning dan pertumbuhan terhambat.
                        </p>
                    </div>

                    <a href="#" class="tautan-solusi">
                        Lihat Solusi
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"></path>
                        </svg>
                    </a>
                </article>

                <article class="kartu-masalah">
                    <img
                        src="{{ asset('assets/hama-penyakit/rupanya_ulat_di_atas_daun.png') }}"
                        alt="Ulat daun"
                        class="gambar-masalah"
                    >

                    <div class="isi-masalah">
                        <h3>Ulat Daun</h3>
                        <p>
                            Larva memakan daun hingga berlubang atau habis. Serangan berat dapat menurunkan hasil panen.
                        </p>
                    </div>

                    <a href="#" class="tautan-solusi">
                        Lihat Solusi
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"></path>
                        </svg>
                    </a>
                </article>

                <article class="kartu-masalah">
                    <img
                        src="{{ asset('assets/hama-penyakit/daun_hijau_dengan_bercak_kecokelatan.png') }}"
                        alt="Bercak daun"
                        class="gambar-masalah"
                    >

                    <div class="isi-masalah">
                        <h3>Bercak Daun</h3>
                        <p>
                            Terdapat bercak cokelat atau kuning pada daun yang dapat meluas dan menyebabkan daun mengering.
                        </p>
                    </div>

                    <a href="#" class="tautan-solusi">
                        Lihat Solusi
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"></path>
                        </svg>
                    </a>
                </article>

                <article class="kartu-masalah">
                    <img
                        src="{{ asset('assets/hama-penyakit/akar_tanaman_di_tanah_lembap.png') }}"
                        alt="Busuk akar"
                        class="gambar-masalah"
                    >

                    <div class="isi-masalah">
                        <h3>Busuk Akar</h3>
                        <p>
                            Akar menjadi cokelat kehitaman, lembek, dan berbau busuk. Tanaman menjadi layu dan mudah mati.
                        </p>
                    </div>

                    <a href="#" class="tautan-solusi">
                        Lihat Solusi
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"></path>
                        </svg>
                    </a>
                </article>

            </section>

            <section class="kartu-pencegahan">
                <img
                    src="{{ asset('assets/hama-penyakit/perisai_hijau_bersinar_dengan_daun.png') }}"
                    alt="Perisai perlindungan tanaman"
                    class="gambar-pencegahan"
                >

                <div class="isi-pencegahan">
                    <h2>Pencegahan</h2>

                    <ul>
                        <li>Gunakan benih unggul dan sehat.</li>
                        <li>Lakukan rotasi tanaman dan jaga kebersihan lahan.</li>
                        <li>Pantau tanaman secara rutin sejak dini.</li>
                    </ul>
                </div>
            </section>

        </section>

        <x-navigasi-bawah aktif="beranda" />

    </main>
</body>
</html>
