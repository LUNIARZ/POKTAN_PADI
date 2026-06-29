<section class="tab-pane fade" id="tab-pupuk" tabindex="0">
    <div class="admin-title">
        <div>
            <p class="text-success fw-bold mb-1">Katalog Pupuk</p>
            <h1>Produk Pupuk</h1>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-4">
            <article class="admin-card">
                <h2 data-admin-fertilizer-form-title>Tambah Produk Pupuk</h2>
                <form class="admin-form" data-admin-fertilizer-form>
                    <input type="hidden" data-admin-fertilizer-id>

                    <label class="form-label">
                        Nama Pupuk
                        <input class="form-control" type="text" required data-admin-fertilizer-name>
                    </label>

                    <label class="form-label">
                        Harga
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input
                                class="form-control"
                                type="text"
                                inputmode="numeric"
                                placeholder="Contoh: 120.000"
                                autocomplete="off"
                                required
                                data-admin-fertilizer-price
                            >
                        </div>
                        <small class="text-secondary">Harga otomatis ditulis dalam format Rupiah.</small>
                    </label>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">
                                Stok
                                <input class="form-control" type="text" inputmode="numeric" required data-admin-fertilizer-stock>
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="form-label">
                                Kemasan
                                <input class="form-control" type="text" value="50 kg" required data-admin-fertilizer-package>
                            </label>
                        </div>
                    </div>

                    <label class="form-label">
                        Deskripsi
                        <textarea class="form-control" rows="3" required data-admin-fertilizer-description></textarea>
                    </label>

                    <div class="form-label">
                        Gambar Produk Pupuk
                        <input type="hidden" data-admin-fertilizer-image>
                        <input class="visually-hidden" type="file" accept="image/*" data-admin-fertilizer-image-file>
                        <div class="content-image-picker">
                            <img src="" alt="" data-admin-fertilizer-image-preview hidden>
                            <div>
                                <div class="text-secondary small" data-admin-fertilizer-image-name>Belum ada gambar dipilih.</div>
                                <div class="btn-group btn-group-sm mt-2">
                                    <button class="btn btn-outline-success" type="button" data-admin-fertilizer-image-button>Pilih dari Galeri</button>
                                    <button class="btn btn-outline-secondary" type="button" data-admin-fertilizer-image-clear>Hapus</button>
                                </div>
                            </div>
                        </div>
                        <small class="text-secondary">Pilih gambar dari penyimpanan perangkat. Kosongkan untuk memakai gambar bawaan produk pupuk.</small>
                    </div>

                    <button class="btn btn-success w-100" type="submit">Simpan Produk Pupuk</button>
                    <button class="btn btn-outline-secondary w-100" type="button" data-admin-fertilizer-reset>Reset Form</button>
                </form>
            </article>
        </div>

        <div class="col-xl-8">
            <article class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Daftar Produk Pupuk</h2>
                    <span class="badge text-bg-success" data-admin-fertilizer-count>0 produk</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Kemasan</th>
                                <th>Stok</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody data-admin-fertilizers></tbody>
                    </table>
                </div>
            </article>
        </div>
    </div>
</section>
