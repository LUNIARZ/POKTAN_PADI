<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Diri</title>

    @vite(['resources/css/data-diri.css', 'resources/css/navigasi-bawah.css', 'resources/js/data-diri.js', 'resources/js/maintenance.js'])
</head>

<body>
    <main class="halaman-data-diri">
        <header class="kepala-halaman">
            <a href="{{ route('profile') }}" class="tombol-bulat" aria-label="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"></path>
                    <path d="M12 19l-7-7 7-7"></path>
                </svg>
            </a>

            <h1 class="judul-halaman">Data Diri</h1>

            <div class="ruang-kanan" aria-hidden="true"></div>
        </header>

        <section class="konten-data-diri">
            <article class="kartu-data-diri">
                <div class="ringkasan-data-diri">
                    <span class="ikon-data-diri" aria-hidden="true">
                        <img src="{{ asset('assets/profile/gambar_profile.png') }}" alt="">
                    </span>

                    <div>
                        <h2>Informasi Pribadi</h2>
                        <p>Lengkapi data diri untuk kebutuhan akun petani Anda.</p>
                    </div>
                </div>

                <form class="form-data-diri" action="{{ route('profile.update') }}" method="POST">
                    @csrf

                    @if (session('status')) <p>{{ session('status') }}</p> @endif
                    @if ($errors->any()) <p>{{ $errors->first() }}</p> @endif

                    <label>
                        <span>NIK</span>
                        <input
                            type="text"
                            name="nik"
                            inputmode="numeric"
                            minlength="16"
                            maxlength="16"
                            pattern="[0-9]{16}"
                            placeholder="Masukkan NIK"
                            value="{{ old('nik', auth()->user()->nik) }}"
                            required
                        >
                    </label>

                    <label>
                        <span>Nama Lengkap</span>
                        <input
                            type="text"
                            name="nama_lengkap"
                            placeholder="Masukkan nama lengkap"
                            autocomplete="name"
                            value="{{ old('nama_lengkap', auth()->user()->name) }}"
                            required
                        >
                    </label>

                    <label>
                        <span>No Handphone</span>
                        <input
                            type="tel"
                            name="no_handphone"
                            inputmode="tel"
                            placeholder="Masukkan no handphone"
                            autocomplete="tel"
                            value="{{ old('no_handphone', auth()->user()->nomor_hp) }}"
                            required
                        >
                    </label>

                    <label>
                        <span>Alamat</span>
                        <textarea
                            name="alamat"
                            rows="4"
                            placeholder="Masukkan alamat lengkap"
                            required
                        >{{ old('alamat', auth()->user()->alamat) }}</textarea>
                    </label>

                    <button type="submit">SIMPAN</button>
                </form>
            </article>

            <x-form-ubah-password />
        </section>

        <x-navigasi-bawah aktif="profile" />
    </main>
</body>
</html>
