@props(['aktif' => 'beranda'])

@php
    $menuNavigasi = [
        ['kunci' => 'beranda', 'label' => 'Beranda', 'url' => route('dashboard')],
        ['kunci' => 'lahan', 'label' => 'Lahan', 'url' => route('lahan-saya')],
        ['kunci' => 'marketplace', 'label' => 'Produk Saya', 'url' => route('marketplace')],
        ['kunci' => 'notifikasi', 'label' => 'Notifikasi', 'url' => route('notifikasi')],
        ['kunci' => 'profile', 'label' => 'Profile', 'url' => route('profile')],
    ];
@endphp

<nav class="navigasi-bawah" aria-label="Navigasi utama">
    @foreach ($menuNavigasi as $menu)
        @php
            $sedangAktif = $aktif === $menu['kunci'];
        @endphp

        <a
            @class(['item-navigasi', 'aktif' => $sedangAktif])
            href="{{ $menu['url'] }}"
            @if ($sedangAktif) aria-current="page" @endif
        >
            @switch($menu['kunci'])
                @case('beranda')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path d="m3 10 9-7 9 7" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 9v11h14V9" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10 20v-6h4v6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    @break

                @case('lahan')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path d="M12 21V9" stroke-linecap="round"/>
                        <path d="M8 9c-3.2 0-5-2.4-5-6 3.6 0 6 1.8 6 5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 9c3.2 0 5-2.4 5-6-3.6 0-6 1.8-6 5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    @break

                @case('marketplace')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path d="M6 7h12l1 14H5L6 7Z" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 7a3 3 0 0 1 6 0" stroke-linecap="round"/>
                    </svg>
                    @break

                @case('notifikasi')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13.7 21a2 2 0 0 1-3.4 0" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    @break

                @case('profile')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 21a8 8 0 0 1 16 0" stroke-linecap="round"/>
                    </svg>
                    @break
            @endswitch

            <span>{{ $menu['label'] }}</span>
        </a>
    @endforeach
</nav>
