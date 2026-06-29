<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pupuk</title>

    @vite(['resources/css/pupuk.css', 'resources/css/navigasi-bawah.css', 'resources/js/pupuk.js', 'resources/js/maintenance.js'])
</head>
<body>

<main class="layar-aplikasi">

    <!-- HEADER ATAS -->
    <header class="kepala-halaman">
        <a href="{{ route('dashboard') }}" class="tombol-bulat" aria-label="Kembali">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5"></path>
                <path d="M12 19l-7-7 7-7"></path>
            </svg>
        </a>

        <h1>Pupuk</h1>

        <div class="aksi-header">
            <button
                class="tombol-bulat tombol-riwayat"
                type="button"
                aria-label="Riwayat transaksi pesanan"
                aria-expanded="false"
                aria-controls="panel-riwayat-pupuk"
                data-buka-riwayat
            >
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M7 3h10l2 3v15l-3-2-3 2-3-2-3 2V3Z"></path>
                    <path d="M9 9h6"></path>
                    <path d="M9 13h5"></path>
                </svg>
                <span class="badge-header" data-jumlah-riwayat hidden>0</span>
            </button>

            <button
                class="tombol-bulat tombol-checkout"
                type="button"
                aria-label="Buka checkout"
                aria-expanded="false"
                aria-controls="panel-checkout-pupuk"
                data-buka-checkout
            >
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 7h12l1 14H5L6 7Z"></path>
                    <path d="M9 7a3 3 0 0 1 6 0"></path>
                    <path d="M9 12h6"></path>
                </svg>
                <span class="badge-header" data-jumlah-keranjang hidden>0</span>
            </button>
        </div>
    </header>

    <!-- KONTEN HALAMAN -->
    <section class="isi-halaman">

        <!-- BANNER REKOMENDASI -->
        <section class="banner-rekomendasi">
            <img
                src="{{ asset('assets/pupuk/ladang_padi_pagi_yang_tenang.png') }}"
                alt="Ladang padi"
                class="gambar-banner"
            >

            <div class="teks-banner">
                <p>Rekomendasi Pupuk</p>
                <h2>Untuk Padi</h2>
                <span>
                    Penuhi kebutuhan hara untuk pertumbuhan optimal dan hasil panen maksimal.
                </span>
            </div>
        </section>

        <!-- DAFTAR PRODUK -->
        <section class="daftar-produk" data-daftar-produk-pupuk></section>

        <!-- TIPS PEMUPUKAN -->
        <section class="kartu-tips">
            <img
                src="{{ asset('assets/pupuk/ikon_lampu_pada_latar_belakang_transparan.png') }}"
                alt="Tips"
                class="gambar-tips"
            >

            <div>
                <h3>Tips Pemupukan</h3>
                <p>
                    Berikan pupuk sesuai dosis dan fase pertumbuhan padi.
                    Pemupukan tepat waktu dapat meningkatkan hasil panen.
                </p>
            </div>
        </section>

    </section>

    <section class="panel-checkout" id="panel-checkout-pupuk" hidden data-panel-checkout>
        <div class="pegangan-panel" aria-hidden="true"></div>

        <div class="kepala-panel-checkout">
            <div>
                <h2>Checkout</h2>
                <p>Pilih metode pembayaran pesanan pupuk.</p>
            </div>

            <button class="tombol-tutup-checkout" type="button" aria-label="Tutup checkout" data-tutup-checkout>
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="ringkasan-checkout">
            <span>Keranjang</span>
            <strong data-checkout-produk>Belum memilih produk</strong>
            <small data-checkout-harga>Tekan BELI pada satu atau beberapa produk</small>
        </div>

        <div class="pembatas-pupuk" hidden data-pembatas-pupuk>
            <label for="petani-pupuk">Petani</label>
            <select id="petani-pupuk" data-pilih-petani-pupuk></select>
            <small data-info-batas-pupuk>Belum ada batas pembelian pupuk.</small>
        </div>

        <div class="daftar-checkout" hidden data-daftar-checkout></div>

        <div class="total-checkout" hidden data-total-checkout>
            <span>Total Bayar</span>
            <strong data-total-bayar>Rp 0</strong>
        </div>

        <form class="form-checkout" data-form-checkout>
            <fieldset>
                <legend>Metode Pembayaran</legend>

                <label class="opsi-pembayaran" for="pupuk-pembayaran-transfer" data-payment-option>
                    <input id="pupuk-pembayaran-transfer" type="radio" name="metode_pembayaran" value="Transfer">
                    <span class="ikon-pembayaran">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <path d="M3 10h18"></path>
                            <path d="M7 15h4"></path>
                        </svg>
                    </span>
                    <span class="teks-pembayaran">
                        <strong>Transfer <em data-payment-badge>Aktif</em></strong>
                        <small>Bayar melalui rekening bank</small>
                    </span>
                </label>

                <label class="opsi-pembayaran" for="pupuk-pembayaran-qris" data-payment-option>
                    <input id="pupuk-pembayaran-qris" type="radio" name="metode_pembayaran" value="QRIS">
                    <span class="ikon-pembayaran">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 4h6v6H4V4Z"></path>
                            <path d="M14 4h6v6h-6V4Z"></path>
                            <path d="M4 14h6v6H4v-6Z"></path>
                            <path d="M14 14h2v2h-2v-2Z"></path>
                            <path d="M18 14h2v6h-4v-2h2v-4Z"></path>
                        </svg>
                    </span>
                    <span class="teks-pembayaran">
                        <strong>QRIS <em data-payment-badge>Aktif</em></strong>
                        <small>Bayar dengan scan kode QR</small>
                    </span>
                </label>

                <label class="opsi-pembayaran terpilih" for="pupuk-pembayaran-tunai" data-payment-option>
                    <input id="pupuk-pembayaran-tunai" type="radio" name="metode_pembayaran" value="Tunai" checked>
                    <span class="ikon-pembayaran">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M6 9v0"></path>
                            <path d="M18 15v0"></path>
                        </svg>
                    </span>
                    <span class="teks-pembayaran">
                        <strong>Tunai <em data-payment-badge>Rekomendasi</em></strong>
                        <small>Bayar saat pupuk diterima</small>
                    </span>
                </label>
            </fieldset>

            <p class="status-checkout" data-status-checkout aria-live="polite"></p>

            <button class="tombol-lanjut-checkout" type="submit" data-submit-checkout>LANJUTKAN</button>
        </form>
    </section>

    <section class="panel-riwayat" id="panel-riwayat-pupuk" hidden data-panel-riwayat>
        <div class="pegangan-panel" aria-hidden="true"></div>

        <div class="kepala-panel-checkout">
            <div>
                <h2>Riwayat Pesanan</h2>
                <p>Pantau rincian dan status pesanan pupuk Anda.</p>
            </div>

            <button class="tombol-tutup-checkout" type="button" aria-label="Tutup riwayat pesanan" data-tutup-riwayat>
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <p class="status-aksi-riwayat-pupuk" role="status" aria-live="polite" data-status-riwayat-pupuk hidden></p>

        <div class="daftar-riwayat" data-daftar-riwayat aria-live="polite"></div>
    </section>

    <x-navigasi-bawah aktif="pupuk" />

</main>

</body>
</html>
