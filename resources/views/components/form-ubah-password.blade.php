<article class="kartu-data-diri kartu-password">
    <div class="kepala-password">
        <span class="ikon-kunci" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="4" y="10" width="16" height="11" rx="3"></rect>
                <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                <path d="M12 14v3"></path>
            </svg>
        </span>
        <div>
            <h2>Ubah Password</h2>
            <p>Gunakan minimal 8 karakter yang mengandung huruf dan angka.</p>
        </div>
    </div>

    <form class="form-data-diri form-password" data-form-password>
        <label>
            <span>Password Saat Ini</span>
            <span class="wadah-input-password">
                <input
                    type="password"
                    name="current_password"
                    autocomplete="current-password"
                    placeholder="Masukkan password saat ini"
                    required
                    data-password-field
                >
                <button class="tombol-toggle-password" type="button" aria-label="Tampilkan password" aria-pressed="false" data-toggle-password>
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </span>
        </label>

        <label>
            <span>Password Baru</span>
            <span class="wadah-input-password">
                <input
                    type="password"
                    name="password"
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                    required
                    data-password-field
                >
                <button class="tombol-toggle-password" type="button" aria-label="Tampilkan password" aria-pressed="false" data-toggle-password>
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </span>
        </label>

        <label>
            <span>Konfirmasi Password Baru</span>
            <span class="wadah-input-password">
                <input
                    type="password"
                    name="password_confirmation"
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Ulangi password baru"
                    required
                    data-password-field
                >
                <button class="tombol-toggle-password" type="button" aria-label="Tampilkan password" aria-pressed="false" data-toggle-password>
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </span>
        </label>

        <p class="status-password" aria-live="polite" data-status-password></p>
        <button type="submit" data-simpan-password>SIMPAN PASSWORD</button>
    </form>
</article>
