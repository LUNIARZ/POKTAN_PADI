<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - POKTAN Lancang Kuning</title>
    @vite(['resources/css/login.css', 'resources/js/form-autentikasi.js'])
</head>
<body>
    <main class="halaman-login halaman-daftar" style="--login-bg: url('{{ asset('assets/bg-sawah.jpg') }}');">
        <section class="identitas-aplikasi" aria-label="POKTAN Lancang Kuning">
            <div class="bingkai-logo">
                <img class="logo-aplikasi" src="{{ asset('assets/logo-padi.png') }}" alt="Logo POKTAN Lancang Kuning">
            </div>
            <h1>POKTAN</h1>
            <p>Lancang Kuning</p>
        </section>

        <section class="kartu-login kartu-daftar" aria-labelledby="daftar-title">
            <form class="form-login" action="{{ route('daftar.store') }}" method="POST">
                @csrf

                <h2 id="daftar-title">Buat Akun Baru</h2>

                <section class="ketentuan-daftar" aria-labelledby="ketentuan-petani-title">
                    <h3 id="ketentuan-petani-title">Ketentuan Daftar Petani</h3>
                    <ul>
                        <li>NIK wajib 16 digit angka dan belum pernah terdaftar.</li>
                        <li>Nomor handphone harus aktif dan dapat memakai format 08, 62, +62, atau 812.</li>
                        <li>Password minimal 8 karakter serta mengandung huruf dan angka.</li>
                        <li>Akun petani menunggu aktivasi admin sebelum bisa digunakan.</li>
                    </ul>
                </section>

                <div
                    class="pesan-login pesan-validasi"
                    role="alert"
                    aria-live="assertive"
                    tabindex="-1"
                    data-registration-alert
                    @if (! $errors->any()) hidden @endif
                >
                    @if ($errors->any())
                        <strong>Periksa kembali data berikut:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="kolom-formulir @error('nik') memiliki-error @enderror">
                    <label for="nik">NIK</label>
                    <input
                        id="nik"
                        name="nik"
                        type="text"
                        inputmode="numeric"
                        minlength="16"
                        maxlength="16"
                        pattern="[0-9]{16}"
                        placeholder="NIK"
                        autocomplete="off"
                        value="{{ old('nik') }}"
                        @error('nik') aria-invalid="true" aria-describedby="nik-error" @enderror
                        required
                    >
                    @error('nik')
                        <p id="nik-error" class="pesan-field" role="alert" data-field-error>{{ $message }}</p>
                    @enderror
                </div>

                <div class="kolom-formulir @error('nama') memiliki-error @enderror">
                    <label for="nama">Nama</label>
                    <input
                        id="nama"
                        name="nama"
                        type="text"
                        placeholder="Nama"
                        autocomplete="name"
                        value="{{ old('nama') }}"
                        @error('nama') aria-invalid="true" aria-describedby="nama-error" @enderror
                        required
                    >
                    @error('nama')
                        <p id="nama-error" class="pesan-field" role="alert" data-field-error>{{ $message }}</p>
                    @enderror
                </div>

                <div class="kolom-formulir @error('no_hp') memiliki-error @enderror">
                    <label for="no_hp">No HP</label>
                    <input
                        id="no_hp"
                        name="no_hp"
                        type="tel"
                        inputmode="tel"
                        placeholder="Contoh: 081234567890"
                        autocomplete="tel"
                        value="{{ old('no_hp') }}"
                        @error('no_hp') aria-invalid="true" aria-describedby="no_hp-error" @enderror
                        required
                    >
                    @error('no_hp')
                        <p id="no_hp-error" class="pesan-field" role="alert" data-field-error>{{ $message }}</p>
                    @enderror
                </div>

                <div class="kolom-formulir kolom-password @error('password') memiliki-error @enderror">
                    <label for="password">Password</label>
                    <div class="wadah-password">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            minlength="8"
                            placeholder="Minimal 8 karakter, huruf dan angka"
                            autocomplete="new-password"
                            @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                            required
                            data-password-field
                        >
                        <button class="tombol-lihat-password" type="button" aria-label="Tampilkan password" aria-pressed="false" data-toggle-password>
                            <svg class="ikon-password" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p id="password-error" class="pesan-field" role="alert" data-field-error>{{ $message }}</p>
                    @enderror
                </div>

                <div class="kolom-formulir kolom-password @error('password_confirmation') memiliki-error @enderror">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="wadah-password">
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            minlength="8"
                            placeholder="Ulangi password yang sama"
                            autocomplete="new-password"
                            @error('password_confirmation') aria-invalid="true" aria-describedby="password_confirmation-error" @enderror
                            required
                            data-password-field
                        >
                        <button class="tombol-lihat-password" type="button" aria-label="Tampilkan konfirmasi password" aria-pressed="false" data-toggle-password>
                            <svg class="ikon-password" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p id="password_confirmation-error" class="pesan-field" role="alert" data-field-error>{{ $message }}</p>
                    @enderror
                </div>

                <button class="tombol-masuk" type="submit">Daftar</button>

                <p class="teks-daftar">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Masuk di sini</a>
                </p>
            </form>
        </section>
    </main>
</body>
</html>
