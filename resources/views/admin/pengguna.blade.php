<section class="tab-pane fade" id="tab-pengguna" tabindex="0">
    <div class="admin-title">
        <div>
            <p class="text-success fw-bold mb-1">Manajemen Pengguna</p>
            <h1>Pengguna</h1>
        </div>

    </div>

    <div class="user-filter-tabs mb-3" role="group" aria-label="Pilih jenis pengguna">
        <button class="active" type="button" data-admin-user-filter="Petani">Petani</button>
        <button type="button" data-admin-user-filter="Pembeli">Pembeli</button>
    </div>

    <div class="row g-3">
        <div class="col-xl-4">
            <article class="admin-card">
                <h2 data-admin-user-form-title>Tambah Pengguna</h2>
                <form class="admin-form" data-admin-user-form>
                    <input type="hidden" data-admin-user-id>
                    <input type="hidden" value="Petani" data-admin-user-role>

                    <label class="form-label">
                        Nama Lengkap
                        <input class="form-control" type="text" required data-admin-user-name>
                    </label>

                    <label class="form-label">
                        No Handphone
                        <input class="form-control" type="tel" required data-admin-user-phone>
                    </label>

                    <label class="form-label" data-admin-user-farmer-field>
                        NIK Petani
                        <input class="form-control" type="text" inputmode="numeric" minlength="16" maxlength="16" pattern="[0-9]{16}" data-admin-user-nik placeholder="16 digit NIK">
                    </label>

                    <label class="form-label" data-admin-user-buyer-field hidden>
                        Nama Gudang
                        <input class="form-control" type="text" data-admin-user-warehouse placeholder="Nama gudang pembeli">
                    </label>

                    <label class="form-label">
                        Alamat
                        <textarea class="form-control" rows="2" data-admin-user-address placeholder="Alamat petani atau pembeli"></textarea>
                    </label>

                    <div class="row g-2" data-admin-user-farmer-limit>
                        <div class="col-12">
                            <label class="form-label">
                                Luas Lahan
                                <input class="form-control" type="text" inputmode="numeric" data-admin-user-land-area placeholder="Contoh: 2500">
                                <small class="text-secondary">Isi luas lahan dalam meter.</small>
                            </label>
                        </div>
                        <div class="col-12">
                            <div class="form-label mb-2">Batas Pembelian Pupuk per Produk</div>
                            <div class="d-grid gap-2" data-admin-user-fertilizer-limits></div>
                            <small class="text-secondary">Isi maksimal karung yang boleh dibeli untuk setiap produk. Kosongkan atau 0 jika produk tersebut belum dibatasi.</small>
                        </div>
                    </div>

                    <label class="form-label" data-admin-user-status-field>
                        Status
                        <select class="form-select" data-admin-user-status>
                            <option>Aktif</option>
                            <option>Menunggu</option>
                            <option>Nonaktif</option>
                        </select>
                    </label>

                    <label class="form-label">
                        Password Baru
                        <input class="form-control" type="password" minlength="6" autocomplete="new-password" data-admin-user-password placeholder="Kosongkan jika tidak diganti">
                    </label>

                    <label class="form-label">
                        Konfirmasi Password
                        <input class="form-control" type="password" minlength="6" autocomplete="new-password" data-admin-user-password-confirmation placeholder="Ulangi password baru">
                        <small class="text-secondary">Isi hanya saat admin ingin mengubah password pengguna.</small>
                    </label>

                    <button class="btn btn-success w-100" type="submit">Simpan Pengguna</button>
                    <button class="btn btn-outline-secondary w-100" type="button" data-admin-user-reset>Reset Form</button>
                </form>
            </article>
        </div>

        <div class="col-xl-8">
            <article class="admin-card">
                <div class="admin-user-list-header">
                    <div>
                        <h2 data-admin-user-list-title>Daftar Petani</h2>
                        <small class="text-secondary" data-admin-user-result-count></small>
                    </div>

                    <form class="admin-user-search" role="search" data-admin-user-search-form>
                        <label class="visually-hidden" for="admin-user-search">Cari pengguna</label>
                        <input
                            class="form-control"
                            id="admin-user-search"
                            type="search"
                            autocomplete="off"
                            placeholder="Cari nama atau NIK petani"
                            data-farmer-placeholder="Cari nama atau NIK petani"
                            data-buyer-placeholder="Cari nama atau No. HP pembeli"
                            data-admin-user-search
                        >
                        <button class="btn btn-success" type="submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="m20 20-4-4"></path>
                            </svg>
                            <span>Cari</span>
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>No HP</th>
                                <th>Data Diri</th>
                                <th>Lahan & Pupuk</th>
                                <th>Status</th>
                                <th>Password</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody data-admin-users></tbody>
                    </table>
                </div>
            </article>
        </div>
    </div>
</section>
