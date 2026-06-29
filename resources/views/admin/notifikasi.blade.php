<section class="tab-pane fade" id="tab-notifikasi" tabindex="0">
    <div class="admin-title">
        <div>
            <p class="text-success fw-bold mb-1">Pusat Informasi</p>
            <h1>Notifikasi Aplikasi</h1>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-4">
            <article class="admin-card">
                <h2>Buat Notifikasi</h2>
                <form class="admin-form" data-admin-notification-form>
                    <label class="form-label">
                        Judul
                        <input class="form-control" type="text" required data-admin-notification-title>
                    </label>

                    <label class="form-label">
                        Kategori
                        <select class="form-select" data-admin-notification-category>
                            <option>Pupuk</option>
                            <option>Edukasi</option>
                            <option>Hama & Penyakit</option>
                            <option>Sistem</option>
                        </select>
                    </label>

                    <label class="form-label">
                        Penerima
                        <select class="form-select" data-admin-notification-target>
                            <option value="semua">Semua Pengguna</option>
                            <option value="petani">Petani</option>
                            <option value="pembeli">Pembeli</option>
                        </select>
                    </label>

                    <label class="form-label">
                        Pesan
                        <textarea class="form-control" rows="4" required data-admin-notification-message></textarea>
                    </label>

                    <button class="btn btn-success w-100" type="submit">Simpan Notifikasi</button>
                    <div class="alert alert-success mb-0" role="status" aria-live="polite" data-admin-notification-success hidden></div>
                </form>
            </article>
        </div>

        <div class="col-xl-8">
            <article class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0">Daftar Notifikasi</h2>
                    <span class="badge text-bg-success" data-admin-notification-count>0 notifikasi</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Penerima</th>
                                <th>Pesan</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody data-admin-notifications></tbody>
                    </table>
                </div>
            </article>
        </div>
    </div>
</section>
