<section class="tab-pane fade show active" id="tab-dashboard" tabindex="0">
    <div class="admin-title">
        <div>
            <p class="text-success fw-bold mb-1">Ringkasan Sistem</p>
            <h1>Dashboard Admin</h1>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <article class="stat-card">
                <span>Petani</span>
                <strong data-stat-petani>0</strong>
                <small>Akun petani terdaftar</small>
            </article>
        </div>
        <div class="col-sm-6 col-xl-3">
            <article class="stat-card">
                <span>Pembeli</span>
                <strong data-stat-pembeli>0</strong>
                <small>Akun pembeli terdaftar</small>
            </article>
        </div>
        <div class="col-sm-6 col-xl-3">
            <article class="stat-card">
                <span>Menunggu Aktivasi</span>
                <strong data-stat-menunggu>0</strong>
                <small>Akun yang perlu diperiksa admin</small>
            </article>
        </div>
        <div class="col-sm-6 col-xl-3">
            <article class="stat-card">
                <span>Produk Marketplace</span>
                <strong data-stat-produk>0</strong>
                <small>Produk hasil pertanian</small>
            </article>
        </div>
        <div class="col-sm-6 col-xl-3">
            <article class="stat-card">
                <span>Pupuk</span>
                <strong data-stat-pupuk>0</strong>
                <small>Produk pupuk tambahan</small>
            </article>
        </div>
        <div class="col-sm-6 col-xl-3">
            <article class="stat-card">
                <span>Pesanan Pupuk</span>
                <strong data-stat-pesanan>0</strong>
                <small>Pesanan pupuk dari petani</small>
            </article>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <article class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Aktivitas Terbaru</h2>
                    <span class="badge text-bg-light">Realtime database</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Aktivitas</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody data-admin-aktivitas></tbody>
                    </table>
                </div>
            </article>
        </div>

        <div class="col-xl-4">
            <article class="admin-card">
                <h2>Tentang Aplikasi</h2>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between px-0">
                        <span>Marketplace</span>
                        <strong class="text-success" data-status-marketplace>Aktif</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between px-0">
                        <span>Notifikasi</span>
                        <strong class="text-success" data-status-notification>0 tersimpan</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between px-0">
                        <span>Maintenance</span>
                        <strong class="text-success" data-status-maintenance>Nonaktif</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between px-0">
                        <span>Mode Data</span>
                        <strong class="text-success">Database MySQL</strong>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>
