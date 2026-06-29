<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Marketplace</title>

    @vite(['resources/css/marketplace.css', 'resources/css/navigasi-bawah.css', 'resources/js/marketplace.js', 'resources/js/maintenance.js'])
</head>

<body>
    <main class="halaman-marketplace">

        <header class="kepala-halaman">
            <a href="{{ route('dashboard') }}" class="tombol-bulat" aria-label="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"></path>
                    <path d="M12 19l-7-7 7-7"></path>
                </svg>
            </a>

            <h1 class="judul-halaman">Marketplace</h1>

            <div class="aksi-kanan">
                <button
                    class="tombol-bulat"
                    type="button"
                    aria-label="Notifikasi Pembelian"
                    aria-expanded="false"
                    aria-controls="panel-notifikasi-pesanan"
                    data-buka-notifikasi-pesanan
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                        <path d="M10 21h4"></path>
                    </svg>
                    <span class="badge-notifikasi" data-jumlah-pesanan hidden>0</span>
                </button>

                <button
                    class="tombol-bulat"
                    type="button"
                    aria-label="Tambah Produk"
                    aria-expanded="false"
                    aria-controls="panel-produk"
                    data-buka-panel-produk
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg>
                </button>
            </div>
        </header>

        <section
            class="konten-marketplace"
            data-daftar-produk
            data-gambar-default="{{ asset('assets/marketplace/karung_padi_dengan_beras_dan_daun.png') }}"
        >
        </section>

        <section class="panel-produk" id="panel-produk" hidden data-panel-produk>
            <div class="pegangan-panel" aria-hidden="true"></div>

            <div class="kepala-panel-produk">
                <h2 data-judul-panel-produk>Tambah Produk</h2>
                <button class="tombol-tutup-panel" type="button" aria-label="Tutup panel tambah produk" data-tutup-panel-produk>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-produk" data-form-produk>
                <input type="hidden" data-input-id-produk>

                <label>
                    <span>Nama Produk</span>
                    <input type="text" placeholder="Contoh: Karung Padi" required data-input-nama-produk>
                </label>

                <label>
                    <span>Deskripsi</span>
                    <textarea rows="3" placeholder="Tulis deskripsi singkat produk" required data-input-deskripsi-produk></textarea>
                </label>

                <label>
                    <span>Alamat Produk</span>
                    <textarea rows="2" placeholder="Contoh: Lancang Kuning, Kab. Sleman" required data-input-alamat-produk></textarea>
                </label>

                <div class="grid-form-produk">
                    <label>
                        <span>Harga</span>
                        <div class="input-dengan-satuan">
                            <strong>Rp</strong>
                            <input
                                type="text"
                                inputmode="numeric"
                                placeholder="120.000"
                                autocomplete="off"
                                required
                                data-input-harga-produk
                            >
                            <strong>/Kg</strong>
                        </div>
                        <small class="petunjuk-harga-produk">Harga otomatis ditulis dalam format Rupiah.</small>
                    </label>

                    <label>
                        <span>Stok</span>
                        <input type="text" inputmode="numeric" placeholder="120" required data-input-stok-produk>
                    </label>
                </div>

                <label>
                    <span>Satuan Stok</span>
                    <input type="text" placeholder="karung" required data-input-satuan-produk>
                </label>

                <label>
                    <span>Foto Produk</span>
                    <input
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        aria-describedby="petunjuk-foto-produk"
                        data-input-gambar-produk
                    >
                    <small id="petunjuk-foto-produk" class="petunjuk-foto-produk">
                        Gunakan JPG, PNG, atau WebP dengan ukuran maksimal 2 MB. Foto tidak akan dikompres.
                    </small>
                </label>

                <p class="status-form-produk" role="status" aria-live="polite" tabindex="-1" hidden data-form-produk-status></p>

                <button class="tombol-simpan-produk" type="submit" data-tombol-simpan-produk>SIMPAN</button>
            </form>
        </section>

        <section class="panel-pesanan" id="panel-notifikasi-pesanan" hidden data-panel-notifikasi-pesanan>
            <div class="pegangan-panel" aria-hidden="true"></div>

            <div class="kepala-panel-pesanan">
                <div>
                    <h2>Notifikasi Pembelian</h2>
                    <p>Konfirmasi pembeli yang ingin membeli produk Anda.</p>
                </div>

                <button class="tombol-tutup-panel" type="button" aria-label="Tutup notifikasi pembelian" data-tutup-notifikasi-pesanan>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="daftar-pesanan" data-daftar-pesanan></div>
        </section>

        <x-navigasi-bawah aktif="marketplace" />

    </main>
</body>
</html>
