<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - POKTAN Lancang Kuning</title>
    @vite(['resources/css/dashboard.css', 'resources/css/navigasi-bawah.css', 'resources/js/dashboard-weather.js', 'resources/js/maintenance.js'])
</head>
<body>
    <main class="halaman-dashboard" aria-label="Dashboard POKTAN Lancang Kuning">
        <div class="bingkai-dashboard">
            <header class="banner-dashboard">
                <img
                    class="gambar-banner"
                    src="{{ asset('assets/dashboard-banner.png') }}"
                    alt="Ilustrasi sawah hijau, gunung, langit cerah, awan, dan petani memakai caping memegang tablet"
                    width="440"
                    height="250"
                >

                <div class="aksi-banner" aria-label="Aksi dashboard">
                    <a class="tombol-ikon" href="{{ route('notifikasi') }}" aria-label="Buka notifikasi">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.7 21a2 2 0 0 1-3.4 0" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="badge-pembelian-dashboard" data-dashboard-badge-pembelian hidden>0</span>
                    </a>
                </div>
            </header>

            <section class="konten-dashboard">
                <section class="bagian-dashboard" aria-labelledby="menu-cepat-title">
                    <div class="kepala-bagian">
                        <h2 id="menu-cepat-title">Menu Cepat</h2>
                    </div>

                    <div class="daftar-menu">
                        <a class="item-menu" href="{{ route('lahan-saya') }}">
                            <span class="kotak-ikon kotak-ikon-hijau">
                                <img src="{{ asset('assets/380b60f2347e3d7e3398557810a28570.png') }}" alt="" width="41" height="41">
                            </span>
                            <span>Lahan Saya</span>
                        </a>

                        <a class="item-menu" href="{{ route('cuaca') }}">
                            <span class="kotak-ikon kotak-ikon-biru">
                                <img src="{{ asset('assets/64f868d5b8420abeee611d71587187f4.png') }}" alt="" width="41" height="41">
                            </span>
                            <span>Cuaca</span>
                        </a>

                        <a class="item-menu" href="{{ route('pupuk') }}">
                            <span class="kotak-ikon kotak-ikon-kuning">
                                <img src="{{ asset('assets/22e64d98711f269d11b18c73acbc0640.png') }}" alt="" width="41" height="41">
                            </span>
                            <span>Pupuk</span>
                        </a>

                        <a class="item-menu" href="{{ route('marketplace') }}">
                            <span class="kotak-ikon kotak-ikon-ungu">
                                <img src="{{ asset('assets/8207277b91ab508f851fdb73aae6af45.png') }}" alt="" width="41" height="41">
                            </span>
                            <span>Marketplace</span>
                        </a>
                    </div>
                </section>

                <section class="permintaan-lokasi" data-lokasi-permintaan hidden role="status">
                    <span class="ikon-permintaan-lokasi" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </span>

                    <div class="teks-permintaan-lokasi">
                        <h2>Aktifkan Lokasi</h2>
                        <p data-lokasi-pesan>Izinkan akses lokasi agar cuaca dan nama lokasi dapat ditampilkan.</p>
                    </div>

                    <button class="tombol-aktifkan-lokasi" type="button" data-lokasi-aktifkan>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path d="M12 2v4"></path>
                            <path d="M12 18v4"></path>
                            <path d="M2 12h4"></path>
                            <path d="M18 12h4"></path>
                            <circle cx="12" cy="12" r="4"></circle>
                        </svg>
                        Aktifkan
                    </button>
                </section>

                <section
                    class="kartu-cuaca"
                    aria-label="Cuaca lokasi Anda"
                    data-cuaca-widget
                >
                    <div class="cuaca-ringkas">
                        <img
                            class="ikon-cuaca"
                            src="{{ asset('assets/c0d58d3639360db73301bf07dd1e74a7.png') }}"
                            alt="Ikon cuaca"
                            width="84"
                            height="84"
                            data-cuaca-ikon
                            data-cuaca-ikon-default="{{ asset('assets/c0d58d3639360db73301bf07dd1e74a7.png') }}"
                        >

                        <div class="info-cuaca">
                            <p class="suhu-cuaca" data-cuaca-suhu>--&deg;C</p>
                            <p class="deskripsi-cuaca" data-cuaca-deskripsi>Mengambil lokasi</p>
                        </div>
                    </div>

                    <hr class="garis-cuaca">

                    <div class="detail-cuaca">
                        <div class="baris-cuaca">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#ef3b66" aria-hidden="true">
                                <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z" stroke-linejoin="round"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span data-cuaca-lokasi>Lokasi Anda</span>
                        </div>

                        <div class="baris-cuaca">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#0f9b6e" aria-hidden="true">
                                <path d="M12 3s6 6.5 6 11a6 6 0 0 1-12 0c0-4.5 6-11 6-11Z" stroke-linejoin="round"/>
                            </svg>
                            <span data-cuaca-kelembaban>Kelembaban --%</span>
                        </div>

                        <div class="baris-cuaca">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#547589" aria-hidden="true">
                                <path d="M3 8h11a3 3 0 1 0-3-3" stroke-linecap="round"/>
                                <path d="M3 12h16a3 3 0 1 1-3 3" stroke-linecap="round"/>
                                <path d="M3 16h8" stroke-linecap="round"/>
                            </svg>
                            <span data-cuaca-angin>Angin -- km/jam</span>
                        </div>
                    </div>
                </section>

                <section class="bagian-dashboard" aria-labelledby="fitur-utama-title">
                    <div class="kepala-bagian">
                        <h2 id="fitur-utama-title">Fitur Utama</h2>
                    </div>

                    <div class="daftar-fitur">
                        <a class="kartu-fitur" href="{{ route('lahan-saya') }}">
                            <span class="ikon-fitur ikon-fitur-hijau">
                                <img src="{{ asset('assets/c8bf8beb54ee409ed96f36c298a08745.png') }}" alt="" width="27" height="27">
                            </span>
                            <strong>Lahan Saya</strong>
                            <span>Kelola lahan pertanian</span>
                        </a>

                        <a class="kartu-fitur" href="{{ route('jadwal-tanam') }}">
                            <span class="ikon-fitur ikon-fitur-kuning">
                                <img src="{{ asset('assets/15b76236080b2a5c0f77766f782d5a90.png') }}" alt="" width="50" height="50">
                            </span>
                            <strong>Jadwal Tanam</strong>
                            <span>Catat dan kelola jadwal tanam</span>
                        </a>

                        <a class="kartu-fitur" href="{{ route('edukasi') }}">
                            <span class="ikon-fitur ikon-fitur-ungu">
                                <img src="{{ asset('assets/cd1f383c69b93d4fbbb5d7faff7d4675.png') }}" alt="" width="27" height="27">
                            </span>
                            <strong>Edukasi</strong>
                            <span>Tips &amp; informasi pertanian</span>
                        </a>

                        <a class="kartu-fitur" href="{{ route('hama-penyakit') }}">
                            <span class="ikon-fitur ikon-fitur-merah">
                                <img src="{{ asset('assets/e0abcd1368c3aded38abeab48ba8995c.png') }}" alt="" width="27" height="27">
                            </span>
                            <strong>Hama &amp; Penyakit</strong>
                            <span>Kenali dan atasi masalah</span>
                        </a>

                        <a class="kartu-fitur" href="{{ route('lumbung-padi') }}">
                            <span class="ikon-fitur ikon-fitur-kuning">
                                <img src="{{ asset('assets/lumbung-padi.png') }}" alt="" width="34" height="34">
                            </span>
                            <strong>Lumbung Padi</strong>
                            <span>Catat dan Kelola Hasil panen anda</span>
                        </a>
                    </div>
                </section>
            </section>
        </div>
    </main>

    <x-navigasi-bawah aktif="beranda" />
</body>
</html>
