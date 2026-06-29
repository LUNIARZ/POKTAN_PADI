<section class="tab-pane fade" id="tab-pengaturan" tabindex="0">
    <div class="admin-title">
        <div>
            <p class="text-success fw-bold mb-1">Konfigurasi</p>
            <div class="admin-title-inline">
                <h1>Pengaturan Aplikasi</h1>
                <button class="btn btn-success px-4" type="submit" form="admin-settings-form">
                    Simpan Pengaturan
                </button>
            </div>
        </div>
    </div>

    <form class="admin-settings-form" id="admin-settings-form" data-admin-settings-form>
        <div class="row g-3">
            <div class="col-xl-6">
                <article class="admin-card setting-card">
                    <h2>Identitas Aplikasi</h2>

                    <label class="form-label">
                        Nama Aplikasi
                        <input class="form-control" type="text" maxlength="150" required data-admin-setting-app-name>
                    </label>

                    <label class="form-label">
                        Lokasi Aplikasi
                        <input class="form-control" type="text" maxlength="255" data-admin-setting-location>
                    </label>
                </article>
            </div>

            <div class="col-xl-6">
                <article class="admin-card setting-card">
                    <h2>Pengaturan Pembeli</h2>

                    <label class="form-label">
                        Status Marketplace Pembeli
                        <select class="form-select" data-admin-setting-marketplace>
                            <option>Aktif</option>
                            <option>Perawatan</option>
                            <option>Nonaktif</option>
                        </select>
                        <small class="text-secondary" data-admin-marketplace-description>
                            Marketplace dapat digunakan oleh pembeli.
                        </small>
                    </label>

                    <div class="form-label">
                        Pembayaran Marketplace Pembeli
                        <div class="payment-method-grid" data-admin-payment-group="buyer">
                            @foreach (['Tunai', 'Transfer', 'QRIS'] as $method)
                                <label class="setting-switch">
                                    <span class="setting-switch-copy">
                                        <strong>{{ $method }}</strong>
                                        <small data-setting-switch-state>Aktif</small>
                                    </span>
                                    <span class="setting-switch-control">
                                        <input type="checkbox" role="switch" value="{{ $method }}" data-admin-payment-enabled="buyer">
                                        <span class="setting-switch-track" aria-hidden="true"></span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <small class="text-secondary">Switch aktif berarti metode pembayaran dapat dipilih oleh pembeli.</small>
                    </div>
                </article>

            </div>

            <div class="col-xl-6">
                <article class="admin-card setting-card">
                    <h2>Pengaturan Petani</h2>

                    <div class="form-label">
                        Pembayaran Pupuk Petani
                        <div class="payment-method-grid" data-admin-payment-group="farmer">
                            @foreach (['Tunai', 'Transfer', 'QRIS'] as $method)
                                <label class="setting-switch">
                                    <span class="setting-switch-copy">
                                        <strong>{{ $method }}</strong>
                                        <small data-setting-switch-state>Aktif</small>
                                    </span>
                                    <span class="setting-switch-control">
                                        <input type="checkbox" role="switch" value="{{ $method }}" data-admin-payment-enabled="farmer">
                                        <span class="setting-switch-track" aria-hidden="true"></span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <small class="text-secondary">Switch aktif berarti metode pembayaran dapat dipilih oleh petani.</small>
                    </div>
                </article>
            </div>

            <div class="col-xl-6">
                <article class="admin-card setting-card">
                    <h2>Maintenance Aplikasi</h2>

                    <label class="setting-switch">
                        <span class="setting-switch-copy">
                            <strong>Status Maintenance</strong>
                            <small data-setting-switch-state>Nonaktif</small>
                        </span>
                        <span class="setting-switch-control">
                            <input type="checkbox" role="switch" data-admin-setting-maintenance>
                            <span class="setting-switch-track" aria-hidden="true"></span>
                        </span>
                    </label>

                    <label class="form-label">
                        Pesan Maintenance
                        <textarea class="form-control" rows="4" maxlength="2000" required data-admin-setting-maintenance-message></textarea>
                    </label>

                    <small class="text-secondary">Aktifkan saat aplikasi sedang dalam perawatan agar halaman pengguna dapat menampilkan pemberitahuan maintenance.</small>
                </article>
            </div>
        </div>

        <div class="admin-settings-feedback alert mb-0 mt-3" role="status" aria-live="polite" data-admin-settings-feedback hidden></div>
    </form>
</section>
