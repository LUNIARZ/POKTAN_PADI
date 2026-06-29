<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edukasi</title>

    @vite(['resources/css/edukasi.css', 'resources/css/navigasi-bawah.css', 'resources/js/konten-aplikasi.js', 'resources/js/maintenance.js'])
</head>

<body>
    <main class="halaman-edukasi" data-admin-content-page="Edukasi">

        <header class="kepala-halaman">
            <a href="{{ route('dashboard') }}" class="tombol-bulat" aria-label="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"></path>
                    <path d="M12 19l-7-7 7-7"></path>
                </svg>
            </a>

            <h1 class="judul-halaman">Edukasi</h1>

            <span class="ruang-header" aria-hidden="true"></span>
        </header>

        <section class="konten-edukasi">

            <article class="kartu-unggulan">
                <img
                    src="{{ asset('assets/edukasi/petani_dengan_tablet_di_sawah_terasering.png') }}"
                    alt="Petani menggunakan tablet di sawah"
                    class="gambar-unggulan"
                >

                <div class="lapisan-unggulan"></div>

                <div class="isi-unggulan">
                    <span class="label-unggulan">Artikel Unggulan</span>

                    <h2 class="judul-unggulan">Kenali Musim, Tingkatkan Hasil</h2>

                    <p class="deskripsi-unggulan">
                        Pahami pola musim untuk menentukan waktu tanam terbaik.
                    </p>

                    <button class="tombol-baca">
                        Baca Selengkapnya
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"></path>
                            <path d="M13 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </article>

            <div class="daftar-artikel" data-admin-content-list>

                <article class="kartu-artikel">
                    <img
                        src="{{ asset('assets/edukasi/tumbuhnya_benih_di_tray_kebun.png') }}"
                        alt="Bibit cabai"
                        class="gambar-artikel"
                    >

                    <div class="isi-artikel">
                        <div class="baris-judul-artikel">
                            <h3 class="judul-artikel">Cara Menyemai Bibit</h3>

                            <span class="label-jenis label-artikel">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                    <path d="M7 3h8l4 4v14H7z"></path>
                                    <path d="M15 3v5h5"></path>
                                    <path d="M10 13h7"></path>
                                    <path d="M10 17h7"></path>
                                </svg>
                                Artikel
                            </span>
                        </div>

                        <p class="deskripsi-artikel">
                            Langkah menyemai bibit agar tumbuh sehat dan siap dipindah tanam.
                        </p>
                    </div>

                    <a class="panah-kartu" href="#" aria-label="Baca artikel Cara Menyemai Bibit Cabai">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"></path>
                        </svg>
                    </a>
                </article>

                <article class="kartu-artikel">
                    <img
                        src="{{ asset('assets/edukasi/daun_dengan_bekas_gigitan_dan_ulat.png') }}"
                        alt="Ulat pada daun"
                        class="gambar-artikel"
                    >

                    <div class="isi-artikel">
                        <div class="baris-judul-artikel">
                            <h3 class="judul-artikel">Hama Ulat Daun: Ciri dan Cara Atasi</h3>

                            <span class="label-jenis label-video">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M10 8l6 4-6 4z"></path>
                                </svg>
                                Video
                            </span>
                        </div>

                        <p class="deskripsi-artikel">
                            Kenali gejala serangan ulat daun dan cara mengendalikannya.
                        </p>
                    </div>

                    <a class="panah-kartu" href="#" aria-label="Baca artikel Hama Ulat Daun">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"></path>
                        </svg>
                    </a>
                </article>

                <article class="kartu-artikel">
                    <img
                        src="{{ asset('assets/edukasi/menanam_bibit_dengan_pupuk_granula.png') }}"
                        alt="Pemberian pupuk pada tanaman"
                        class="gambar-artikel"
                    >

                    <div class="isi-artikel">
                        <div class="baris-judul-artikel">
                            <h3 class="judul-artikel judul-artikel-pemupukan">Pemupukan Seimbang untuk Tanaman</h3>

                            <span class="label-jenis label-artikel">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                    <path d="M7 3h8l4 4v14H7z"></path>
                                    <path d="M15 3v5h5"></path>
                                    <path d="M10 13h7"></path>
                                    <path d="M10 17h7"></path>
                                </svg>
                                Artikel
                            </span>
                        </div>

                        <p class="deskripsi-artikel">
                            Pilih jenis pupuk, dosis, dan waktu aplikasi yang tepat.
                        </p>
                    </div>

                    <a class="panah-kartu" href="#" aria-label="Baca artikel Pemupukan Seimbang">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"></path>
                        </svg>
                    </a>
                </article>

                <article class="kartu-artikel">
                    <img
                        src="{{ asset('assets/edukasi/padi_beras_menuju_panen_akhir.png') }}"
                        alt="Padi siap panen"
                        class="gambar-artikel"
                    >

                    <div class="isi-artikel">
                        <div class="baris-judul-artikel">
                            <h3 class="judul-artikel">Kapan Waktu Panen Padi yang Tepat?</h3>

                            <span class="label-jenis label-video">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M10 8l6 4-6 4z"></path>
                                </svg>
                                Video
                            </span>
                        </div>

                        <p class="deskripsi-artikel">
                            Kenali ciri padi siap panen agar kualitas hasil tetap baik.
                        </p>
                    </div>

                    <a class="panah-kartu" href="#" aria-label="Baca artikel Waktu Panen Padi">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"></path>
                        </svg>
                    </a>
                </article>

            </div>

            <h2 class="judul-bagian">Tips Hari Ini</h2>

            <article class="kartu-tips">
                <img
                    src="{{ asset('assets/edukasi/jam_alarm_dan_tanaman_hijau.png') }}"
                    alt="Jam alarm dan tanaman"
                    class="gambar-tips"
                >

                <div>
                    <div class="label-tips">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 2l2.9 2.1 3.6-.1 1 3.4 2.8 2.2-1.2 3.3 1.2 3.3-2.8 2.2-1 3.4-3.6-.1L12 24l-2.9-2.1-3.6.1-1-3.4-2.8-2.2 1.2-3.3-1.2-3.3 2.8-2.2 1-3.4 3.6.1L12 2z"></path>
                        </svg>
                        Tips Hari Ini
                    </div>

                    <h3 class="judul-tips">Siram Tanaman pada Pagi atau Sore Hari</h3>

                    <p class="deskripsi-tips">
                        Penyiraman pada waktu yang tepat membantu tanaman menyerap air lebih baik.
                    </p>
                </div>

                <a class="tombol-panah-tips" href="#" aria-label="Buka tips">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="M13 5l7 7-7 7"></path>
                    </svg>
                </a>
            </article>

        </section>

        <x-navigasi-bawah aktif="beranda" />

    </main>
</body>
</html>
