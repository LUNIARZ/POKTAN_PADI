<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POKTAN PADI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>
<body>

    <div class="phone-frame">

        <!-- Header -->
        <header class="header">
            <button class="icon-btn" aria-label="Buka menu">
                <svg width="20" height="16" viewBox="0 0 20 16" fill="none"><path d="M1 1H19M1 8H19M1 15H19" stroke="#2f5d3a" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
            <div class="brand">
                <div class="brand-logo">
                    <svg width="40" height="40" viewBox="0 0 40 40">
                        <circle cx="20" cy="20" r="19" fill="#fff" stroke="#2f5d3a" stroke-width="2"/>
                        <path d="M20 8c4 3 6 8 6 13-2-1-4-1-6 1-2-2-4-2-6-1 0-5 2-10 6-13z" fill="#e9b949"/>
                        <path d="M14 27c2-3 4-4 6-4s4 1 6 4" stroke="#2f5d3a" stroke-width="1.4" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="brand-text">
                    <span class="brand-title">POKTAN</span>
                    <span class="brand-subtitle">PADI</span>
                </div>
            </div>
            <button class="icon-btn notif-btn" aria-label="Notifikasi">
                <svg width="18" height="20" viewBox="0 0 18 20" fill="none"><path d="M9 1c-3 0-5.5 2.4-5.5 5.5v3.4c0 .7-.3 1.4-.8 1.9L1.5 13c-.6.6-.2 1.7.7 1.7h13.6c.9 0 1.3-1.1.7-1.7l-1.2-1.2c-.5-.5-.8-1.2-.8-1.9V6.5C14.5 3.4 12 1 9 1Z" stroke="#2f5d3a" stroke-width="1.5" stroke-linejoin="round"/><path d="M7 17a2 2 0 0 0 4 0" stroke="#2f5d3a" stroke-width="1.5" stroke-linecap="round"/></svg>
                <span class="notif-dot"></span>
            </button>
        </header>

        <!-- Hero -->
            <section class="hero" style="background-image: url('{{ asset('images/bg-landing-padi.png') }}')">
            <div class="hero-text">
                <h1>POKTAN<br>PADI</h1>
                <p class="hero-desc">Platform digital untuk petani dan pembeli gabah &amp; beras berkualitas.</p>
                <div class="hero-badge">
                    <span class="hero-badge-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M9 12l2 2 4-4" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="10" stroke="#fff" stroke-width="1.6"/></svg>
                    </span>
                    <span>Mendukung petani lokal, membangun pertanian Indonesia yang maju.</span>
                </div>
            </div>
        </section>

        <!-- Produk Petani -->
        <section class="produk-section">
            <div class="produk-header">
                <h2 class="section-title" style="margin:0">Produk Petani</h2>
                <span class="produk-count">{{ $produk->count() }} produk tersedia</span>
            </div>

            @if($produk->isEmpty())
                <div class="produk-empty">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"><path d="M3 3h18v2H3zM5 5l1 14h12l1-14" stroke="#9aa79c" stroke-width="1.6" stroke-linejoin="round"/></svg>
                    <p>Belum ada produk tersedia.</p>
                </div>
            @else
                <div class="produk-grid">
                    @foreach($produk as $item)
                        <div class="produk-card">
                            <div class="produk-img">
                                @if($item->gambar_produk)
                                    <img
                                        src="{{ asset(ltrim($item->gambar_produk, '/')) }}"
                                        alt="{{ $item->nama_produk }}"
                                        loading="lazy"
                                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                                    >
                                    <div class="produk-img-placeholder" style="display:none">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" stroke="#b0c8b0" stroke-width="1.6"/><circle cx="8.5" cy="8.5" r="1.5" fill="#b0c8b0"/><path d="M21 15l-5-5L5 21" stroke="#b0c8b0" stroke-width="1.6" stroke-linejoin="round"/></svg>
                                    </div>
                                @else
                                    <div class="produk-img-placeholder">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" stroke="#b0c8b0" stroke-width="1.6"/><circle cx="8.5" cy="8.5" r="1.5" fill="#b0c8b0"/><path d="M21 15l-5-5L5 21" stroke="#b0c8b0" stroke-width="1.6" stroke-linejoin="round"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="produk-info">
                                <h3 class="produk-nama">{{ $item->nama_produk }}</h3>
                                @if($item->deskripsi)
                                    <p class="produk-desc">{{ Str::limit($item->deskripsi, 40) }}</p>
                                @endif
                                <span class="produk-harga">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    <small>/{{ $item->satuan ?? 'Kg' }}</small>
                                </span>
                                <div class="produk-meta">
                                    <span>{{ $item->penjual->name ?? '-' }}</span>
                                    @if($item->alamat_produk)
                                        <span>{{ $item->alamat_produk }}</span>
                                    @endif
                                    <span>Stok {{ $item->jumlah_stok }} {{ $item->satuan ?? 'Kg' }}</span>
                                </div>
                                <a href="{{ route('login') }}" class="produk-btn">Beli</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Kenapa POKTAN -->
        <section class="why-section">
            <h2 class="section-title">Kenapa POKTAN?</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none"><path d="M12 2l8 3v6c0 5-3.5 8.5-8 11-4.5-2.5-8-6-8-11V5l8-3z" fill="#2f5d3a"/><path d="M8.5 12l2.3 2.3L16 9" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3>Aman &amp; Terpercaya</h3>
                    <p>Transaksi aman dengan sistem terverifikasi.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="5" fill="#e9b949"/><text x="12" y="10.5" font-size="6" text-anchor="middle" fill="#2f5d3a" font-weight="700" font-family="Poppins,sans-serif">Rp</text><path d="M4 19c1.5-2.5 4-4 8-4s6.5 1.5 8 4" stroke="#2f5d3a" stroke-width="1.8" stroke-linecap="round" fill="none"/></svg>
                    </div>
                    <h3>Harga Transparan</h3>
                    <p>Harga jelas, tanpa perantara.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" fill="#e9b949"/><path d="M5 8c2-3 4-4 7-4s5 1 7 4" stroke="#2f5d3a" stroke-width="1.6" fill="none" stroke-linecap="round"/><path d="M5 21c0-4 3-7 7-7s7 3 7 7" fill="#2f5d3a"/></svg>
                    </div>
                    <h3>Dukung Petani Lokal</h3>
                    <p>Setiap transaksi membantu petani Indonesia.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="28" height="22" viewBox="0 0 28 22" fill="none"><rect x="1" y="4" width="14" height="10" rx="1.5" fill="#2f5d3a"/><path d="M15 8h6l4 4v2h-10V8z" fill="#2f5d3a"/><circle cx="7" cy="18" r="2.4" fill="#1a2e22"/><circle cx="20" cy="18" r="2.4" fill="#1a2e22"/></svg>
                    </div>
                    <h3>Transaksi Mudah</h3>
                    <p>Belanja gabah &amp; beras kapan saja, di mana saja.</p>
                </div>
            </div>
        </section>

        <!-- App showcase -->
        <section class="app-showcase">
            <div class="app-showcase-bg"></div>
            <div class="phone-mock" aria-hidden="true">
                <div class="phone-mock-screen">
                    <div class="phone-mock-logo">
                        <svg width="34" height="34" viewBox="0 0 40 40"><circle cx="20" cy="20" r="19" fill="#fff" stroke="#2f5d3a" stroke-width="2"/><path d="M20 8c4 3 6 8 6 13-2-1-4-1-6 1-2-2-4-2-6-1 0-5 2-10 6-13z" fill="#e9b949"/></svg>
                    </div>
                    <span class="phone-mock-title">POKTAN</span>
                    <span class="phone-mock-subtitle">PADI</span>
                </div>
            </div>
            <div class="app-showcase-content">
                <h2>Semua dalam Satu Aplikasi</h2>
                <p>Kelola transaksi, pantau pesanan, dan terhubung langsung dengan petani.</p>
                <div class="app-feature-list">
                    <div class="app-feature">
                        <span class="app-feature-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" stroke="#fff" stroke-width="1.8"/><path d="M4 20c0-4 3.5-7 8-7s8 3 8 7" stroke="#fff" stroke-width="1.8"/><path d="M9 12.5l1.6 1.6L14 10.5" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        <div><h4>Akun Terverifikasi</h4><p>Identitas pengguna terjamin aman.</p></div>
                    </div>
                    <div class="app-feature">
                        <span class="app-feature-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="5" y="3" width="14" height="18" rx="1.5" stroke="#fff" stroke-width="1.6"/><path d="M8 8h8M8 12h8M8 16h5" stroke="#fff" stroke-width="1.6" stroke-linecap="round"/></svg></span>
                        <div><h4>Riwayat Transaksi</h4><p>Lihat semua transaksi dengan mudah.</p></div>
                    </div>
                    <div class="app-feature">
                        <span class="app-feature-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 2c-3.3 0-6 2.7-6 6v3.5c0 .8-.3 1.5-.9 2.1L4 14.8c-.6.6-.2 1.7.7 1.7h14.6c.9 0 1.3-1.1.7-1.7l-1.1-1.2c-.6-.6-.9-1.3-.9-2.1V8c0-3.3-2.7-6-6-6Z" stroke="#fff" stroke-width="1.6" stroke-linejoin="round"/><path d="M10 19a2 2 0 0 0 4 0" stroke="#fff" stroke-width="1.6" stroke-linecap="round"/></svg></span>
                        <div><h4>Notifikasi Real-time</h4><p>Dapatkan informasi terbaru seputar pesanan Anda.</p></div>
                    </div>
                    <div class="app-feature">
                        <span class="app-feature-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 13a8 8 0 0 1 16 0" stroke="#fff" stroke-width="1.6"/><rect x="3" y="13" width="4" height="6" rx="1.5" stroke="#fff" stroke-width="1.6"/><rect x="17" y="13" width="4" height="6" rx="1.5" stroke="#fff" stroke-width="1.6"/></svg></span>
                        <div><h4>Layanan Bantuan</h4><p>Tim kami siap membantu kapan saja.</p></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Join section -->
        <section class="join-section">
            <div class="join-top">
                <div>
                    <h2>Bergabung Bersama Kami</h2>
                    <p>Ribuan petani dan pembeli sudah mempercayai POKTAN PADI.</p>
                </div>
                <a href="{{ route('login') }}" class="cta-btn">
                    Daftar / Masuk
                    <svg width="16" height="14" viewBox="0 0 16 14" fill="none"><path d="M1 7h13M9 1l6 6-6 6" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
            <div class="stats-row">
                <div class="stat">
                    <span class="stat-icon"><svg width="22" height="18" viewBox="0 0 24 20" fill="none"><circle cx="8" cy="5" r="3.2" fill="#2f5d3a"/><circle cx="17" cy="6" r="2.6" fill="#2f5d3a" opacity="0.7"/><path d="M1 18c0-3.8 3.1-6.5 7-6.5s7 2.7 7 6.5" stroke="#2f5d3a" stroke-width="1.6" fill="none"/><path d="M15 12.2c3 .3 5 2.5 5 5.8" stroke="#2f5d3a" stroke-width="1.6" fill="none" stroke-linecap="round"/></svg></span>
                    <div><span class="stat-num">120+</span><span class="stat-label">Petani Aktif</span></div>
                </div>
                <div class="stat">
                    <span class="stat-icon"><svg width="22" height="18" viewBox="0 0 24 20" fill="none"><path d="M2 11l3-4 4 2 4-5 4 3 3-2" stroke="#2f5d3a" stroke-width="1.7" fill="none" stroke-linecap="round" stroke-linejoin="round"/><circle cx="6" cy="13" r="2" fill="#2f5d3a"/><circle cx="18" cy="11" r="2" fill="#2f5d3a"/></svg></span>
                    <div><span class="stat-num">5000+</span><span class="stat-label">Pembeli Terdaftar</span></div>
                </div>
                <div class="stat">
                    <span class="stat-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M6 8h12l-1 12.5a1.5 1.5 0 0 1-1.5 1.5h-7a1.5 1.5 0 0 1-1.5-1.5L6 8Z" stroke="#2f5d3a" stroke-width="1.7" stroke-linejoin="round"/><path d="M9 8V6a3 3 0 0 1 6 0v2" stroke="#2f5d3a" stroke-width="1.7"/><path d="M9.5 13.5l1.7 1.7 3.3-3.4" stroke="#2f5d3a" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    <div><span class="stat-num">50rb+</span><span class="stat-label">Transaksi Berhasil</span></div>
                </div>
            </div>
        </section>

        

        <!-- Quote -->
        <section class="quote-section">
            <span class="quote-mark">&ldquo;</span>
            <p>Bersama POKTAN, wujudkan pertanian yang sejahtera dan berkelanjutan.</p>
            <div class="quote-illustration" aria-hidden="true"></div>
        </section>

        <!-- Bottom nav -->
        <nav class="bottom-nav">
            <button class="nav-item active" data-target="beranda" type="button">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M3 11l9-7 9 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 10v9a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1v-9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span>Beranda</span>
            </button>
            <button class="nav-item" data-target="informasi" type="button">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="M12 11v5.5M12 7.8v.1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <span>Informasi</span>
            </button>
            <button class="nav-item" data-target="tentang" type="button">
                <svg width="22" height="20" viewBox="0 0 24 20" fill="none"><circle cx="8" cy="5" r="3.2" stroke="currentColor" stroke-width="1.8"/><circle cx="17" cy="6" r="2.6" stroke="currentColor" stroke-width="1.8"/><path d="M1 18c0-3.8 3.1-6.5 7-6.5s7 2.7 7 6.5" stroke="currentColor" stroke-width="1.8"/><path d="M15 12.2c3 .3 5 2.5 5 5.8" stroke="currentColor" stroke-width="1.8"/></svg>
                <span>Tentang Kami</span>
            </button>
            <a href="{{ route('login') }}" class="nav-item">
                <svg width="18" height="20" viewBox="0 0 18 20" fill="none"><circle cx="9" cy="5" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M1 19c0-4.4 3.6-7.5 8-7.5s8 3.1 8 7.5" stroke="currentColor" stroke-width="1.8"/></svg>
                <span>Masuk</span>
            </a>
        </nav>

    </div>

</body>
</html>