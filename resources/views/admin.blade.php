<!doctype html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - POKTAN</title>

    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>

<body class="admin-body">
    <header class="admin-header">
        <div class="admin-brand-group">
            <button class="admin-menu-button d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Buka menu admin">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 6h16"></path>
                    <path d="M4 12h16"></path>
                    <path d="M4 18h16"></path>
                </svg>
            </button>

            <button class="admin-sidebar-button d-none d-md-inline-grid" type="button" aria-controls="sidebarMenu" aria-expanded="true" aria-label="Tutup sidebar admin" title="Tutup sidebar" data-admin-sidebar-toggle>
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                    <path d="M9 4v16"></path>
                    <path d="m15 9-3 3 3 3"></path>
                </svg>
            </button>

            <a class="admin-brand fw-black" href="{{ route('admin') }}">
                <img src="{{ asset('assets/logo-padi.png') }}" alt="Logo POKTAN" width="34" height="34">
                <span>POKTAN</span>
            </a>
        </div>

        <div class="admin-header-actions d-flex align-items-center gap-2 ms-auto">
            <span class="badge rounded-pill text-bg-success d-none d-lg-inline-flex">{{ auth()->user()->name }}</span>
            <button class="btn btn-outline-success btn-sm d-none d-sm-inline-flex" type="button" data-admin-refresh>Refresh Panel</button>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-danger btn-sm" type="submit">Logout</button>
            </form>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 admin-main">
                <div class="tab-content">
                    @include('admin.dashboard')
                    @include('admin.pengguna')
                    @include('admin.jadwal-tanam')
                    @include('admin.produk-pupuk')
                    @include('admin.pesanan-pupuk')
                    @include('admin.notifikasi')
                    @include('admin.konten-aplikasi')
                    @include('admin.laporan')
                    @include('admin.pengaturan')
                    @include('admin.akun-admin')
                </div>
            </main>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast align-items-center text-bg-dark border-0" role="status" aria-live="polite" aria-atomic="true" data-admin-toast>
            <div class="d-flex">
                <div class="toast-body" data-admin-toast-body>Data tersimpan.</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
            </div>
        </div>
    </div>
</body>
</html>
