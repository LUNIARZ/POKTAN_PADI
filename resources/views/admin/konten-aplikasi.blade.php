<section class="tab-pane fade" id="tab-konten" tabindex="0">
    <div class="admin-title">
        <div>
            <p class="text-success fw-bold mb-1">Edukasi dan Hama</p>
            <h1>Konten Aplikasi</h1>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-4">
            <article class="admin-card">
                <h2 data-admin-content-form-title>Tambah Konten</h2>
                <form class="admin-form" data-admin-content-form>
                    <input type="hidden" data-admin-content-id>

                    <label class="form-label">
                        Halaman Tujuan
                        <select class="form-select" data-admin-content-category>
                            <option>Edukasi</option>
                            <option>Hama & Penyakit</option>
                        </select>
                    </label>

                    <label class="form-label">
                        Judul Konten
                        <input class="form-control" type="text" required data-admin-content-title>
                    </label>

                    <label class="form-label">
                        Jenis Konten
                        <select class="form-select" data-admin-content-type>
                            <option>Artikel</option>
                            <option>Video</option>
                            <option>Panduan</option>
                            <option>Solusi</option>
                        </select>
                    </label>

                    <label class="form-label">
                        Deskripsi
                        <textarea class="form-control" rows="3" required data-admin-content-description></textarea>
                    </label>

                    <div class="form-label">
                        Gambar Konten
                        <input type="hidden" data-admin-content-image>
                        <input class="visually-hidden" type="file" accept="image/*" data-admin-content-image-file>
                        <div class="content-image-picker">
                            <img src="" alt="" data-admin-content-image-preview hidden>
                            <div>
                                <div class="text-secondary small" data-admin-content-image-name>Belum ada gambar dipilih.</div>
                                <div class="btn-group btn-group-sm mt-2">
                                    <button class="btn btn-outline-success" type="button" data-admin-content-image-button>Pilih dari Galeri</button>
                                    <button class="btn btn-outline-secondary" type="button" data-admin-content-image-clear>Hapus</button>
                                </div>
                            </div>
                        </div>
                        <small class="text-secondary">Pilih gambar dari penyimpanan perangkat. Kosongkan untuk memakai gambar bawaan sesuai halaman.</small>
                    </div>

                    <label class="form-label">
                        Link Tujuan Saat Diklik
                        <input class="form-control" type="text" required data-admin-content-link placeholder="https://contoh.com/artikel atau /edukasi">
                    </label>

                    <button class="btn btn-success w-100" type="submit">Simpan Konten</button>
                    <button class="btn btn-outline-secondary w-100" type="button" data-admin-content-reset>Reset Form</button>
                    <div
                        class="alert alert-success mb-0"
                        role="status"
                        aria-live="polite"
                        data-admin-content-success
                        hidden
                    ></div>
                </form>
            </article>
        </div>

        <div class="col-xl-8">
            <article class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Daftar Konten Tambahan</h2>
                    <span class="badge text-bg-success" data-admin-content-count>0 konten</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Konten</th>
                                <th>Halaman</th>
                                <th>Link</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody data-admin-contents></tbody>
                    </table>
                </div>
            </article>
        </div>
    </div>
</section>
