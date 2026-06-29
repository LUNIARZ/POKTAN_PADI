<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POKTAN Lancang Kuning</title>
    @vite('resources/css/login.css')
</head>
<body>
    <main class="halaman-login" style="--login-bg: url('{{ asset('assets/bg-sawah.jpg') }}');">
        <section class="identitas-aplikasi" aria-label="POKTAN Lancang Kuning">
            <div class="bingkai-logo">
                <img class="logo-aplikasi" src="{{ asset('assets/logo-padi.png') }}" alt="Logo POKTAN Lancang Kuning">
            </div>
            <h1>POKTAN</h1>
            <p>Lancang Kuning</p>
        </section>

        <section class="kartu-login" aria-labelledby="login-title">
            <form class="form-login" action="{{ route('login.store') }}" method="POST">
                @csrf

                <h2 id="login-title">Masuk ke Akun Anda</h2>

                @if (session('status'))
                    <p class="pesan-login pesan-sukses" role="status">{{ session('status') }}</p>
                @endif

                @if ($errors->any())
                    <p class="pesan-login pesan-gagal" role="alert">{{ $errors->first() }}</p>
                @endif

                <div class="kolom-formulir">
                    <label for="username">NIK atau Nomor Handphone</label>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        placeholder="Masukkan NIK atau No. HP"
                        autocomplete="username"
                        value="{{ old('username') }}"
                        required>
                </div>

                <div class="kolom-formulir kolom-password">
                    <label for="password">Password</label>
                    <div class="wadah-password">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Password"
                            autocomplete="current-password"
                            required
                            data-input-password
                        >
                        <button
                            class="tombol-lihat-password"
                            type="button"
                            aria-label="Tampilkan password"
                            aria-pressed="false"
                            data-toggle-password
                        >
                            <svg class="ikon-password ikon-mata-terbuka" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="ikon-password ikon-mata-tertutup" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" hidden>
                                <path d="m3 3 18 18"></path>
                                <path d="M10.6 5.2A11.4 11.4 0 0 1 12 5c6.5 0 10 7 10 7a17.4 17.4 0 0 1-2.1 3.1"></path>
                                <path d="M6.6 6.6C3.6 8.5 2 12 2 12s3.5 7 10 7a10.5 10.5 0 0 0 5.4-1.6"></path>
                                <path d="M10.7 10.7a2 2 0 0 0 2.6 2.6"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <button class="tombol-masuk" type="submit">Masuk</button>

                <p class="teks-daftar">
                    Belum punya akun?
                    <a href="{{ route('daftar') }}">Daftar Petani</a>
                    atau
                    <a href="{{ route('daftar-pembeli') }}">Daftar Pembeli</a>
                </p>
            </form>
        </section>
    </main>

    <script>
        (() => {
            const input = document.querySelector('[data-input-password]');
            const button = document.querySelector('[data-toggle-password]');

            button?.addEventListener('click', () => {
                const visible = input.type === 'text';
                input.type = visible ? 'password' : 'text';
                button.classList.toggle('aktif', !visible);
                button.setAttribute('aria-pressed', visible ? 'false' : 'true');
                button.setAttribute('aria-label', visible ? 'Tampilkan password' : 'Sembunyikan password');
                button.setAttribute('title', visible ? 'Password tidak terlihat' : 'Password terlihat');
                button.querySelector('.ikon-mata-terbuka').hidden = !visible;
                button.querySelector('.ikon-mata-tertutup').hidden = visible;
                input.focus({ preventScroll: true });
                input.setSelectionRange(input.value.length, input.value.length);
            });
        })();
    </script>
</body>
</html>
