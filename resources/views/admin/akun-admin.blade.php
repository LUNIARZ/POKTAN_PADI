<section class="tab-pane fade" id="tab-akun-admin" tabindex="0">
    <div class="admin-title">
        <div>
            <p class="text-success fw-bold mb-1">Keamanan</p>
            <h1>Akun Admin</h1>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-6">
            <article class="admin-card">
                <h2>Ubah Password Admin</h2>
                <form class="admin-form" data-admin-password-form>
                    <label class="form-label">
                        Password Saat Ini
                        <input class="form-control" type="password" autocomplete="current-password" required data-admin-current-password>
                    </label>

                    <label class="form-label">
                        Password Baru
                        <input class="form-control" type="password" autocomplete="new-password" minlength="6" required data-admin-new-password>
                    </label>

                    <label class="form-label">
                        Konfirmasi Password Baru
                        <input class="form-control" type="password" autocomplete="new-password" minlength="6" required data-admin-confirm-password>
                    </label>

                    <button class="btn btn-success w-100" type="submit">Ubah Password Admin</button>
                </form>
            </article>
        </div>
    </div>
</section>
