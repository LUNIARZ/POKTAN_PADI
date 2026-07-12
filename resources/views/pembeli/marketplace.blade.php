<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pojok Belanja Pembeli</title>

    @vite(['resources/css/marketplace-pembeli.css', 'resources/css/navigasi-bawah.css', 'resources/js/marketplace-pembeli.js', 'resources/js/maintenance.js'])
</head>

<body>
    <main class="halaman-marketplace-pembeli">
        <header class="kepala-pembeli">
            <div>
                <h1>Pojok Belanja</h1>
            </div>

            <a class="tombol-notifikasi" href="{{ route('pembeli.notifikasi') }}" aria-label="Buka notifikasi pembeli">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                    <path d="M10 21h4"></path>
                </svg>
            </a>
        </header>

        <section class="konten-marketplace-pembeli">
            <div class="pencarian-produk" role="search">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle>
                    <path d="m16 16 4 4"></path>
                </svg>
                <input type="search" placeholder="Cari beras, gabah, bibit..." data-cari-produk>
            </div>

            <div class="ringkasan-pasar">
                <strong>Produk Petani</strong>
                <span data-total-produk>0 produk tersedia</span>
            </div>

            <section class="daftar-produk-pembeli" aria-label="Daftar produk petani" data-daftar-produk-pembeli></section>
        </section>

        <section class="panel-jumlah-beli" id="panel-jumlah-beli" hidden data-panel-jumlah-beli>
            <div class="pegangan-panel-beli" aria-hidden="true"></div>

            <div class="kepala-panel-beli">
                <div>
                    <p data-panel-petani>Petani Lokal</p>
                    <h2 data-panel-nama-produk>Produk Petani</h2>
                </div>

                <button class="tombol-tutup-panel-beli" type="button" aria-label="Tutup pilihan jumlah" data-tutup-panel-beli>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="ringkasan-panel-beli">
                <img src="{{ asset('assets/marketplace/karung_padi_dengan_beras_dan_daun.png') }}" alt="" data-panel-gambar-produk>

                <div>
                    <strong data-panel-harga-produk>Rp0</strong>
                    <span data-panel-stok-produk>Stok tersedia</span>
                    <span class="panel-alamat-produk" data-panel-alamat-produk>Alamat produk</span>
                </div>
            </div>

            <div class="jumlah-panel-beli">
                <span>Jumlah Beli (Kg)</span>

                <div class="pengatur-jumlah-pembeli pengatur-jumlah-panel">
                    <button class="tombol-jumlah-pembeli" type="button" aria-label="Kurangi jumlah beli" data-panel-kurang>-</button>
                    <label class="input-jumlah-beli">
                        <input type="text" inputmode="numeric" aria-label="Jumlah beli dalam kilogram" value="1" data-panel-jumlah>
                        <span>Kg</span>
                    </label>
                    <button class="tombol-jumlah-pembeli" type="button" aria-label="Tambah jumlah beli" data-panel-tambah>+</button>
                </div>
            </div>

            <div class="total-panel-beli">
                <span>Total Bayar</span>
                <strong data-panel-total-bayar>Rp0</strong>
            </div>

            <form class="form-pembayaran-pembeli" data-form-pembayaran-pembeli>
                <label class="catatan-pembelian">
                    <span>Catatan untuk Petani</span>
                    <textarea rows="3" maxlength="500" placeholder="Contoh: Tolong dikemas rapi dan hubungi sebelum pengiriman." data-catatan-pembeli></textarea>
                </label>

                <fieldset>
                    <legend>Metode Pembayaran</legend>

                    <label class="opsi-pembayaran-pembeli" for="pembayaran-transfer" data-payment-option>
                        <input id="pembayaran-transfer" type="radio" name="metode_pembayaran" value="Transfer">
                        <span class="ikon-pembayaran-pembeli">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                <path d="M3 10h18"></path>
                                <path d="M7 15h4"></path>
                            </svg>
                        </span>
                        <span class="teks-pembayaran-pembeli">
                            <strong>Transfer <em data-payment-badge>Aktif</em></strong>
                            <small>Bayar melalui rekening bank</small>
                        </span>
                    </label>

                    <label class="opsi-pembayaran-pembeli" for="pembayaran-qris" data-payment-option>
                        <input id="pembayaran-qris" type="radio" name="metode_pembayaran" value="QRIS">
                        <span class="ikon-pembayaran-pembeli">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 4h6v6H4V4Z"></path>
                                <path d="M14 4h6v6h-6V4Z"></path>
                                <path d="M4 14h6v6H4v-6Z"></path>
                                <path d="M14 14h2v2h-2v-2Z"></path>
                                <path d="M18 14h2v6h-4v-2h2v-4Z"></path>
                            </svg>
                        </span>
                        <span class="teks-pembayaran-pembeli">
                            <strong>QRIS <em data-payment-badge>Aktif</em></strong>
                            <small>Bayar dengan scan kode QR</small>
                        </span>
                    </label>

                    <label class="opsi-pembayaran-pembeli terpilih" for="pembayaran-tunai" data-payment-option>
                        <input id="pembayaran-tunai" type="radio" name="metode_pembayaran" value="Tunai" checked>
                        <span class="ikon-pembayaran-pembeli">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M6 9v0"></path>
                                <path d="M18 15v0"></path>
                            </svg>
                        </span>
                        <span class="teks-pembayaran-pembeli">
                            <strong>Tunai <em data-payment-badge>Rekomendasi</em></strong>
                            <small>Bayar saat produk diterima</small>
                        </span>
                    </label>
                </fieldset>

                <p class="status-pembayaran-pembeli" data-status-pembayaran-pembeli aria-live="polite"></p>

                <button class="tombol-konfirmasi-beli" type="submit" data-konfirmasi-beli>Kirim Permintaan</button>
            </form>
        </section>

        <div class="toast-pembeli" role="status" aria-live="polite" hidden data-toast-pembeli></div>

        <x-navigasi-pembeli aktif="marketplace" />
    </main>
</body>
</html>
