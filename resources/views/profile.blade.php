<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile</title>

    @vite(['resources/css/profile.css', 'resources/css/navigasi-bawah.css', 'resources/js/profile.js', 'resources/js/maintenance.js'])
</head>

<body>
    <main class="halaman-profile">
        <input
            class="input-foto-profile"
            id="input-foto-profile"
            type="file"
            accept="image/*"
            data-input-foto-profile
        >

        <header class="kepala-profile">
            <h1>Profile</h1>

            <button class="tombol-edit-profile" type="button" aria-label="Ubah foto profile" data-pilih-foto-profile>
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 20h9"></path>
                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"></path>
                </svg>
            </button>
        </header>

        <section class="konten-profile">
            <article class="kartu-identitas">
                <img class="gambar-bg-identitas" src="{{ asset('assets/bg-sawah.jpg') }}" alt="">
                <div class="lapisan-identitas" aria-hidden="true"></div>

                <div class="isi-identitas">
                    <label class="foto-profile" for="input-foto-profile" aria-label="Pilih foto profile">
                        <img
                            src="{{ asset('assets/profile/gambar_profile.png') }}"
                            alt="Foto profil {{ auth()->user()->name }}"
                            data-avatar-profile
                        >
                        <span class="status-terverifikasi" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m5 13 4 4L19 7"></path>
                            </svg>
                        </span>
                    </label>

                    <div class="teks-identitas">
                        <h2>{{ auth()->user()->name }}</h2>
                        <p>Petani</p>

                        <div class="lokasi-profile">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 2C8.14 2 5 5.14 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.86-3.14-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"></path>
                            </svg>
                            <span data-profile-lokasi>{{ auth()->user()->nama_lokasi ?: auth()->user()->alamat ?: 'Lokasi belum tersedia' }}</span>
                        </div>
                    </div>
                </div>

                <p class="status-foto-profile" aria-live="polite" data-status-foto-profile></p>
            </article>

            <section class="menu-profile" aria-label="Menu profile">
                <a href="{{ route('data-diri') }}" class="item-menu-profile">
                    <span class="ikon-menu-profile hijau-penuh">
                        <img src="{{ asset('assets/profile/gambar_profile.png') }}" alt="">
                    </span>
                    <span class="teks-menu-profile">
                        <strong>Data Diri</strong>
                        <small>Kelola informasi pribadi Anda</small>
                    </span>
                    <svg class="panah-menu" viewBox="0 0 24 24" fill="none" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>

                <a href="{{ route('riwayat-transaksi') }}" class="item-menu-profile">
                    <span class="ikon-menu-profile ungu-muda">
                        <img src="{{ asset('assets/profile/gambar_riwayat_transaksi.png') }}" alt="">
                    </span>
                    <span class="teks-menu-profile">
                        <strong>Riwayat Transaksi</strong>
                        <small>Lihat riwayat pembelian dan pesanan</small>
                    </span>
                    <svg class="panah-menu" viewBox="0 0 24 24" fill="none" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>

                <a
                    href="https://wa.me/6282177119351"
                    class="item-menu-profile item-menu-whatsapp"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span class="ikon-menu-profile ikon-whatsapp">
                        <img src="{{ asset('assets/profile/whatsApp.png') }}" alt="">
                    </span>
                    <span class="teks-menu-profile">
                        <strong>WhatsApp</strong>
                        <small>Hubungi admin untuk mendapatkan bantuan</small>
                    </span>
                    <svg class="panah-menu" viewBox="0 0 24 24" fill="none" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>

            </section>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="tombol-keluar" aria-label="Keluar">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M10 17l5-5-5-5"></path><path d="M15 12H3"></path><path d="M21 3v18"></path>
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </section>

        <x-navigasi-bawah aktif="profile" />
    </main>
</body>
</html>
