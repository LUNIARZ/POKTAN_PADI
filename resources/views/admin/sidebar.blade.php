<aside class="sidebar col-md-3 col-lg-2 p-0 bg-white border-end">
    <div class="offcanvas-md offcanvas-start bg-white" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">POKTAN</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Tutup"></button>
        </div>

        <div class="offcanvas-body d-md-flex flex-column p-0 pt-md-3 overflow-y-auto">
            <nav class="admin-nav" aria-label="Menu admin" role="tablist">
                <button class="nav-link active" data-admin-tab data-bs-target="#tab-dashboard" type="button" role="tab" aria-controls="tab-dashboard" aria-selected="true">
                    Dashboard
                </button>

                <details class="admin-nav-group" data-admin-menu-group>
                    <summary class="admin-menu-toggle" aria-controls="submenu-manajemen" data-admin-menu-toggle>
                        <span>Manajemen</span>
                        <span class="admin-menu-chevron" aria-hidden="true"></span>
                    </summary>
                    <div class="admin-submenu" id="submenu-manajemen" data-admin-submenu>
                        <div class="admin-submenu-inner">
                            <button class="nav-link" data-admin-tab data-bs-target="#tab-pengguna" type="button" role="tab" aria-controls="tab-pengguna" aria-selected="false">Pengguna</button>
                            <button class="nav-link" data-admin-tab data-bs-target="#tab-jadwal-tanam" type="button" role="tab" aria-controls="tab-jadwal-tanam" aria-selected="false">Jadwal Tanam</button>
                        </div>
                    </div>
                </details>

                <details class="admin-nav-group" data-admin-menu-group>
                    <summary class="admin-menu-toggle" aria-controls="submenu-pupuk" data-admin-menu-toggle>
                        <span>Pupuk</span>
                        <span class="admin-menu-chevron" aria-hidden="true"></span>
                    </summary>
                    <div class="admin-submenu" id="submenu-pupuk" data-admin-submenu>
                        <div class="admin-submenu-inner">
                            <button class="nav-link" data-admin-tab data-bs-target="#tab-pupuk" type="button" role="tab" aria-controls="tab-pupuk" aria-selected="false">Produk Pupuk</button>
                            <button class="nav-link" data-admin-tab data-bs-target="#tab-pesanan" type="button" role="tab" aria-controls="tab-pesanan" aria-selected="false">Pesanan Pupuk</button>
                        </div>
                    </div>
                </details>

                <details class="admin-nav-group" data-admin-menu-group>
                    <summary class="admin-menu-toggle" aria-controls="submenu-informasi" data-admin-menu-toggle>
                        <span>Informasi</span>
                        <span class="admin-menu-chevron" aria-hidden="true"></span>
                    </summary>
                    <div class="admin-submenu" id="submenu-informasi" data-admin-submenu>
                        <div class="admin-submenu-inner">
                            <button class="nav-link" data-admin-tab data-bs-target="#tab-notifikasi" type="button" role="tab" aria-controls="tab-notifikasi" aria-selected="false">Notifikasi</button>
                            <button class="nav-link" data-admin-tab data-bs-target="#tab-konten" type="button" role="tab" aria-controls="tab-konten" aria-selected="false">Konten Aplikasi</button>
                        </div>
                    </div>
                </details>

                <details class="admin-nav-group" data-admin-menu-group>
                    <summary class="admin-menu-toggle" aria-controls="submenu-sistem" data-admin-menu-toggle>
                        <span>Sistem</span>
                        <span class="admin-menu-chevron" aria-hidden="true"></span>
                    </summary>
                    <div class="admin-submenu" id="submenu-sistem" data-admin-submenu>
                        <div class="admin-submenu-inner">
                            <button class="nav-link" data-admin-tab data-bs-target="#tab-pengaturan" type="button" role="tab" aria-controls="tab-pengaturan" aria-selected="false">Pengaturan</button>
                            <button class="nav-link" data-admin-tab data-bs-target="#tab-akun-admin" type="button" role="tab" aria-controls="tab-akun-admin" aria-selected="false">Akun Admin</button>
                        </div>
                    </div>
                </details>

                <button class="nav-link" data-admin-tab data-bs-target="#tab-laporan" type="button" role="tab" aria-controls="tab-laporan" aria-selected="false">
                    Cetak Laporan
                </button>
            </nav>

            <hr class="my-3">

            <div class="px-3 pb-3 small text-secondary">
                <strong class="d-block text-dark">Admin</strong>
                Kelola data operasional aplikasi dari satu tempat.
            </div>
        </div>
    </div>
</aside>
