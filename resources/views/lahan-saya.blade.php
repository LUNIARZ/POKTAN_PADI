<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lahan Saya</title>

    @vite(['resources/css/lahan-saya.css', 'resources/css/navigasi-bawah.css', 'resources/js/maintenance.js'])
</head>

<body>
    <main class="halaman-lahan">
        <header class="kepala-halaman">
            <a href="{{ route('dashboard') }}" class="tombol-bulat" aria-label="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"></path>
                    <path d="M12 19l-7-7 7-7"></path>
                </svg>
            </a>

            <h1 class="judul-halaman">Lahan Saya</h1>

            <div class="ruang-kanan" aria-hidden="true"></div>
        </header>

        <section class="konten-lahan">
            <article class="kartu-lahan">
                <div class="ringkasan-lahan">
                    <div
                        class="area-gambar-lahan"
                        role="img"
                        aria-label="Hamparan lahan sawah padi"
                        style="--gambar-lahan: url('{{ asset('assets/lahan_sawah.png') }}');"
                    ></div>

                    <div class="teks-ringkasan">
                        <h2>Lahan Padi Anda</h2>
                        <p>Informasi luas lahan padi yang anda miliki.</p>
                    </div>
                </div>

                <div class="wadah-tabel">
                    <table class="tabel-lahan">
                        <thead>
                            <tr>
                                <th scope="col">Luas Lahan</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Pemilik</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lahan as $item)
                                <tr>
                                    <td><strong>{{ $item->nama_lahan }}</strong><span>(per Meter)</span></td>
                                    <td>{{ number_format($item->luas_meter, 0, ',', '.') }}</td>
                                    <td>{{ $item->nama_pemilik ?: auth()->user()->name }}</td>
                                </tr>
                            @empty
                                <tr><td><strong>Luas Lahan Padi</strong><span>(per Meter)</span></td><td>0</td><td>{{ auth()->user()->name }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        <x-navigasi-bawah aktif="lahan" />
    </main>
</body>
</html>
