<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pembeli - POKTAN Lancang Kuning</title>
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

        <section class="kartu-login kartu-daftar" aria-labelledby="daftar-pembeli-title">
            <form class="form-login" action="{{ route('daftar-pembeli.store') }}" method="POST">
                @csrf

                <h2 id="daftar-pembeli-title">Daftar Akun Pembeli</h2>

                <section class="ketentuan-daftar" aria-labelledby="ketentuan-pembeli-title">
                    <h3 id="ketentuan-pembeli-title">Ketentuan Daftar Pembeli</h3>
                    <ul>
                        <li>Nama lengkap wajib diisi sesuai identitas pembeli.</li>
                        <li>Nomor handphone harus aktif dan belum pernah terdaftar.</li>
                        <li>Nama gudang boleh dikosongkan dan akan dibuat otomatis.</li>
                        <li>Akun pembeli langsung aktif setelah pendaftaran berhasil.</li>
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

                <div class="kolom-formulir @error('nama_lengkap') memiliki-error @enderror">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input
                        id="nama_lengkap"
                        name="nama_lengkap"
                        type="text"
                        placeholder="Nama Lengkap"
                        autocomplete="name"
                        value="{{ old('nama_lengkap') }}"
                        @error('nama_lengkap') aria-invalid="true" aria-describedby="nama_lengkap-error" @enderror
                        required
                    >
                    @error('nama_lengkap')
                        <p id="nama_lengkap-error" class="pesan-field" role="alert" data-field-error>{{ $message }}</p>
                    @enderror
                </div>

                <div class="kolom-formulir @error('no_handphone') memiliki-error @enderror">
                    <label for="no_handphone">No Handphone</label>
                    <input
                        id="no_handphone"
                        name="no_handphone"
                        type="tel"
                        inputmode="tel"
                        placeholder="Contoh: 081234567890"
                        autocomplete="tel"
                        value="{{ old('no_handphone') }}"
                        @error('no_handphone') aria-invalid="true" aria-describedby="no_handphone-error" @enderror
                        required
                    >
                    <small class="petunjuk-login">Bisa memakai awalan 08, 62, +62, atau langsung 812.</small>
                    @error('no_handphone')
                        <p id="no_handphone-error" class="pesan-field" role="alert" data-field-error>{{ $message }}</p>
                    @enderror
                </div>

                <div class="kolom-formulir @error('nama_gudang') memiliki-error @enderror">
                    <label for="nama_gudang">Nama Gudang</label>
                    <input
                        id="nama_gudang"
                        name="nama_gudang"
                        type="text"
                        value="{{ old('nama_gudang') }}"
                        placeholder="Nama Gudang (opsional)"
                        @error('nama_gudang') aria-invalid="true" aria-describedby="nama_gudang-error" @enderror
                    >
                    @error('nama_gudang')
                        <p id="nama_gudang-error" class="pesan-field" role="alert" data-field-error>{{ $message }}</p>
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
